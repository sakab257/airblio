<?php
require_once 'database.php';
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

if (!isset($_GET['id'])) {
    header("Location: materiel.php");
    exit();
}

$materiel_id = (int) $_GET['id'];
$sql = "SELECT * FROM materiel WHERE id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $materiel_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Matériel non trouvé, rediriger vers la liste
    header("Location: materiel.php");
    exit();
}

$materiel = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Détail du Matériel | Airblio</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="materiel.css">
</head>
<body>
  <?php include 'navbar.php'; ?>
  
  <main>
    <?php include 'topbar.php'; ?>
    
    <div class="materiel-bar">
      <h1>Matériel - Détails</h1>
      <div style="display:flex;">
        <button type="submit" class="rightBtn" onclick="history.back()">
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000">
            <path d="m313-440 196 196q12 12 12 28t-12 28q-12 12-28 12t-28-12L253-388q-6-6-9-13.5t-3-16.5q0-9 3-16.5t9-13.5l200-200q12-12 28-12t28 12q12 12 12 28t-12 28L313-440Z"/>
          </svg>
          Retour
        </button>
      </div>
    </div>
    
    <div class="page-container">
      <div class="fiche-materiel">
        <div class="fiche-row">
          <span class="fiche-label">Nom du matériel :</span>
          <span class="fiche-value"><?= htmlspecialchars($materiel['nom_materiel']) ?></span>
        </div>
        <div class="fiche-row">
          <span class="fiche-label">Type :</span>
          <span class="fiche-value fiche-type"><?= htmlspecialchars($materiel['materiel_mobilise']) ?></span>
        </div>
        <div class="fiche-row">
          <span class="fiche-label">État de fonctionnement :</span>
          <span class="fiche-value <?= ($materiel['etat_fonctionnement'] === 'Bon état') ? 'fiche-etat-bon' : (($materiel['etat_fonctionnement'] === 'En maintenance') ? 'fiche-etat-maintenance' : 'fiche-etat-panne') ?>">
            <?= htmlspecialchars($materiel['etat_fonctionnement']) ?>
          </span>
        </div>
        <div class="fiche-row">
          <span class="fiche-label">Lieu d'opération :</span>
          <span class="fiche-value"><?= htmlspecialchars($materiel['lieu_operation']) ?></span>
        </div>
        <div class="fiche-row">
          <span class="fiche-label">Nom de l'équipe :</span>
          <span class="fiche-value"><?= htmlspecialchars($materiel['nomEquipe']) ?></span>
        </div>
        <div class="fiche-row">
          <span class="fiche-label">Chef d'équipe :</span>
          <span class="fiche-value"><?= htmlspecialchars($materiel['chefEquipe']) ?></span>
        </div>
        <div class="fiche-row">
          <span class="fiche-label">Date d'opération :</span>
          <span class="fiche-value"><?= htmlspecialchars($materiel['date_operation']) ?></span>
        </div>
        <div class="fiche-row">
          <span class="fiche-label">Commentaires :</span>
          <span class="fiche-value"><?= nl2br(htmlspecialchars($materiel['commentaires'])) ?></span>
        </div>
      </div>
    
    </div>
  </main>
</body>
</html>