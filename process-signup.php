<?php
// process-signup.php
require_once 'database.php';

$prenom = $_POST['prenom'] ?? '';
$nom = $_POST['nom'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (!$prenom || !$nom || !$email || !$password) {
    header("Location: signup.php?error=missing");
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: signup.php?error=email");
    exit;
}

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$sql = "SELECT id FROM users WHERE email = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    header("Location: signup.php?error=exists");
    exit;
}
$stmt->close();

$sql = "INSERT INTO users (prenom, nom, email, mdp_hash) VALUES (?, ?, ?, ?)";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ssss", $prenom, $nom, $email, $hashed_password);

if ($stmt->execute()) {
    header("Location: login.php?signup=success");
} else {
    echo "Erreur: " . $stmt->error;
}

$stmt->close();
$mysqli->close();
?>
