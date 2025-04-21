<?php
require_once 'database.php';
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

// Récupérer la liste des équipes pour le menu déroulant
$equipes_query = "SELECT * FROM equipe ORDER BY nomEquipe ASC";
$equipes_result = $mysqli->query($equipes_query);

// Initialiser les variables
$nom_materiel = '';
$materiel_mobilise = '';
$etat_fonctionnement = '';
$lieu_operation = '';
$nomEquipe = '';
$chefEquipe = '';
$date_operation = date('Y-m-d'); // Date du jour par défaut
$message = '';
$error = '';

// Traitement du formulaire lors de la soumission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Récupérer et sanitiser les données du formulaire
  $nom_materiel = $mysqli->real_escape_string($_POST['nom_materiel']);
  $materiel_mobilise = $mysqli->real_escape_string($_POST['materiel_mobilise']);
  $etat_fonctionnement = $mysqli->real_escape_string($_POST['etat_fonctionnement']);
  $lieu_operation = $mysqli->real_escape_string($_POST['lieu_operation']);
  $nomEquipe = $mysqli->real_escape_string($_POST['nomEquipe']);
  $chefEquipe = $mysqli->real_escape_string($_POST['chefEquipe']);
  $date_operation = $mysqli->real_escape_string($_POST['date_operation']);
  
  // Validation des champs obligatoires
  if (empty($nom_materiel) || empty($materiel_mobilise) || empty($etat_fonctionnement)) {
    $error = "Veuillez remplir tous les champs obligatoires.";
  } else {
    // Insérer les données dans la base de données
    $sql = "INSERT INTO materiel (nom_materiel, materiel_mobilise, etat_fonctionnement, lieu_operation, nomEquipe, chefEquipe, date_operation) 
            VALUES ('$nom_materiel', '$materiel_mobilise', '$etat_fonctionnement', '$lieu_operation', '$nomEquipe', '$chefEquipe', '$date_operation')";
    
    if ($mysqli->query($sql)) {
      $message = "Le matériel a été ajouté avec succès.";
      // Rediriger vers la page des matériels après un ajout réussi
      header("Location: materiel.php");
      exit();
    } else {
      $error = "Erreur lors de l'ajout du matériel: " . $mysqli->error;
    }
  }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ajouter un Matériel | Airblio</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="materiel.css">
  <link rel="stylesheet" href="ajouter_materiel.css">
</head>
<body>
  <?php include "navbar.php"?>
  
  <main>
    <?php include "topbar.php"?>
    
    <div class="materiel-bar">
      <h1>Matériel - Ajout</h1>
    </div>
    
    <div class="form-container">
      <?php if ($error): ?>
        <div class="error-message"><?= $error ?></div>
      <?php endif; ?>
      
      <?php if ($message): ?>
        <div class="success-message"><?= $message ?></div>
      <?php endif; ?>
      
      <form method="POST" action="">
        <div class="form-group">
          <label for="nom_materiel">Nom du Matériel *</label>
          <input type="text" id="nom_materiel" name="nom_materiel" value="<?= htmlspecialchars($nom_materiel) ?>" required>
        </div>
        
        <div class="form-group">
          <label for="materiel_mobilise">Type de Matériel *</label>
          <select id="materiel_mobilise" name="materiel_mobilise" required>
            <option value="" disabled <?= empty($materiel_mobilise) ? 'selected' : '' ?>>Sélectionnez un type</option>
            <option value="Véhicule" <?= $materiel_mobilise === 'Véhicule' ? 'selected' : '' ?>>Véhicule</option>
            <option value="Équipement" <?= $materiel_mobilise === 'Équipement' ? 'selected' : '' ?>>Équipement</option>
            <option value="Outil" <?= $materiel_mobilise === 'Outil' ? 'selected' : '' ?>>Outil</option>
            <option value="Machine" <?= $materiel_mobilise === 'Machine' ? 'selected' : '' ?>>Machine</option>
            <option value="Autre" <?= $materiel_mobilise === 'Autre' ? 'selected' : '' ?>>Autre</option>
          </select>
        </div>
        
        <div class="form-group">
          <label for="etat_fonctionnement">État de Fonctionnement *</label>
          <select id="etat_fonctionnement" name="etat_fonctionnement" required>
            <option value="" disabled <?= empty($etat_fonctionnement) ? 'selected' : '' ?>>Sélectionnez un état</option>
            <option value="Bon état" <?= $etat_fonctionnement === 'Bon état' ? 'selected' : '' ?>>Bon état</option>
            <option value="Usé" <?= $etat_fonctionnement === 'Etat moyen' ? 'selected' : '' ?>>Etat moyen</option>
            <option value="En panne" <?= $etat_fonctionnement === 'Mauvais état' ? 'selected' : '' ?>>Mauvais état</option>
            <option value="En maintenance" <?= $etat_fonctionnement === 'En maintenance' ? 'selected' : '' ?>>En maintenance</option>
          </select>
        </div>
        
        <div class="form-group">
          <label for="lieu_operation">Lieu</label>
          <input type="text" id="lieu_operation" name="lieu_operation" value="<?= htmlspecialchars($lieu_operation) ?>">
        </div>
        
        <div class="form-group">
          <label for="nomEquipe">Équipe</label>
          <select id="nomEquipe" name="nomEquipe">
            <option value="">Aucune équipe assignée</option>
            <?php while ($equipe = $equipes_result->fetch_assoc()): ?>
              <option value="<?= htmlspecialchars($equipe['nomEquipe']) ?>" <?= $nomEquipe === $equipe['nomEquipe'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($equipe['nomEquipe']) ?>
              </option>
            <?php endwhile; ?>
          </select>
        </div>
        
        <div class="form-group">
          <label for="chefEquipe">Responsable</label>
          <input type="text" id="chefEquipe" name="chefEquipe" value="<?= htmlspecialchars($chefEquipe) ?>">
        </div>
        
        <div class="form-group">
          <label for="date_operation">Date de dernière opération</label>
          <input type="date" id="date_operation" name="date_operation" value="<?= htmlspecialchars($date_operation) ?>">
        </div>
        
        <div class="form-actions">
          <button type="button" class="cancelBtn" onclick="window.location.href='materiel.php'">Annuler</button>
          <button type="submit" class="saveBtn">Enregistrer</button>
        </div>
      </form>
    </div>
  </main>
</body>
</html>