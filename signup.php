<?php

$is_invalid_email = false;
$is_email_exists = false;

if (isset($_GET['error'])) {
    if ($_GET['error'] === 'email') {
        $is_invalid_email = true;
    } elseif ($_GET['error'] === 'exists') {
        $is_email_exists = true;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription | Airblio</title>
    <link rel="stylesheet" href="form-style.css">
    <style>
        .error-message {
            color: red;
            margin: 10px 0;
        }
    </style>
</head>
<body>
<div class="login-container">
    <div class="login-card">
        <h2>Inscription</h2>
        <form id="signup-form" action="process-signup.php" method="post">
            <div class="input-group">
                <label for="prenom">Prénom</label>
                <input id="prenom" name="prenom" type="text" placeholder="Entrez votre prénom..." required>
            </div>

            <div class="input-group">
                <label for="nom">Nom</label>
                <input id="nom" name="nom" type="text" placeholder="Entrez votre nom..." required>
            </div>

            <div class="input-group">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" placeholder="Entrez votre email..." required>
            </div>

            <div class="input-group">
                <label for="password">Mot de passe</label>
                <div class="password-wrapper">
                    <input id="password" name="password" type="password" placeholder="Créez un mot de passe..." required>
                    <img id="toggle-password" src="img/visibility_24dp_E3E3E3_FILL0_wght400_GRAD0_opsz24.svg" alt="Afficher le mot de passe" onclick="togglePassword()">
                </div>
            </div>

            <?php if ($is_invalid_email): ?>
                <div class="error-message">Adresse email invalide.</div>
            <?php elseif ($is_email_exists): ?>
                <div class="error-message">Cet email est déjà utilisé.</div>
            <?php endif; ?>

            <button type="submit" class="login-btn">S'inscrire</button>
            <p class="register-link">Déjà un compte ? <a href="login.php">Se connecter</a></p>
        </form>
    </div>
</div>
<script>
    function togglePassword() {
        let passwordInput = document.getElementById("password");
        let toggleIcon = document.getElementById("toggle-password");

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            toggleIcon.src = "img/visibility_off_24dp_E3E3E3_FILL0_wght400_GRAD0_opsz24.svg";
            toggleIcon.alt = "Masquer le mot de passe";
        } else {
            passwordInput.type = "password";
            toggleIcon.src = "img/visibility_24dp_E3E3E3_FILL0_wght400_GRAD0_opsz24.svg";
            toggleIcon.alt = "Afficher le mot de passe";
        }
    }
</script>
</body>
</html>
