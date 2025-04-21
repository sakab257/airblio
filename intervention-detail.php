<?php
require_once 'database.php';
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

// Vérifier si l'ID est fourni
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  header("Location: intervention.php");
  exit();
}

$id = (int) $_GET['id'];

// Récupérer les détails de l'intervention
$stmt = $mysqli->prepare("SELECT * FROM interventions WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
  header("Location: intervention.php");
  exit();
}

$intervention = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Détails de l'intervention #<?= htmlspecialchars($intervention['numero']) ?></title>
  <link rel="stylesheet" href="intervention.css">
  <style>
    .detail-actions {
      display: flex;
      gap: 15px;
      margin-top: 20px;
      justify-content: flex-end;
    }
    
    .back-btn, .edit-btn, .delete-btn {
      padding: 10px 20px;
      border-radius: 6px;
      font-size: 1em;
      cursor: pointer;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    
    .back-btn {
      background-color: var(--base-clr);
      border: 1px solid var(--line-clr);
      color: var(--text-clr);
    }
    
    .edit-btn {
      background-color: var(--accent-clr);
      border: none;
      color: white;
    }
    
    .delete-btn {
      background-color: #e74c3c;
      border: none;
      color: white;
    }
    
    .intervention-meta {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 30px;
      padding-bottom: 15px;
      border-bottom: 1px solid var(--line-clr);
    }
    
    .intervention-id {
      font-size: 1.2em;
      font-weight: 600;
      color: var(--accent-clr);
    }
    
    .intervention-status {
      padding: 6px 12px;
      background-color: rgba(46, 204, 113, 0.1);
      color: #2ecc71;
      border-radius: 20px;
      font-weight: 500;
    }
    
    .intervention-section {
      margin-bottom: 30px;
    }
    
    .section-title {
      font-size: 1.1em;
      margin-bottom: 15px;
      color: var(--secondary-text-clr);
      font-weight: 600;
    }
  </style>
</head>
<body>
  <?php include 'navbar.php'; ?>
  
  <main>
    <?php include 'topbar.php'; ?>
    <div class="intervention-bar">
      <h1>Détails de l'intervention</h1>
      <div style="display:flex;">
        <button type="button" class="rightBtn" onclick="window.location.href='intervention.php'">
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000">
            <path d="M360-240 120-480l240-240 56 56-144 144h568v80H272l144 144-56 56Z"/>
          </svg>
          Retour
        </button>
        <button type="button" class="rightBtn" onclick="generatePDF()">
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000">
            <path d="M320-240h320v-80H320v80Zm0-160h320v-80H320v80ZM240-80q-33 0-56.5-23.5T160-160v-640q0-33 23.5-56.5T240-880h320l240 240v480q0 33-23.5 56.5T720-80H240Zm280-520v-200H240v640h480v-440H520ZM240-800v200-200 640-640Z"/>
          </svg>
          Exporter PDF
        </button>
      </div>
    </div>
    <div class="page-container">
      <div class="fiche-intervention" style="max-width: 100%;">
        <div class="intervention-meta">
          <div class="intervention-id">
            Intervention #<?= htmlspecialchars($intervention['numero']) ?>
          </div>
          <div class="intervention-status">
            <?= htmlspecialchars($intervention['statut']) ?>
          </div>
        </div>
        
        <div class="intervention-section">
          <div class="section-title">Informations générales</div>
          <div class="fiche-row">
            <span class="fiche-label">Date d'intervention :</span>
            <span class="fiche-value"><?= htmlspecialchars($intervention['date_intervention']) ?></span>
          </div>
          <div class="fiche-row">
            <span class="fiche-label">Client :</span>
            <span class="fiche-value"><?= htmlspecialchars($intervention['client']) ?></span>
          </div>
          <div class="fiche-row">
            <span class="fiche-label">Lieu :</span>
            <span class="fiche-value"><?= htmlspecialchars($intervention['lieu']) ?></span>
          </div>
        </div>
        
        <div class="intervention-section">
          <div class="section-title">Détails techniques</div>
          <div class="fiche-row">
            <span class="fiche-label">Type d'intervention :</span>
            <span class="fiche-value fiche-type"><?= htmlspecialchars($intervention['type']) ?></span>
          </div>
          <div class="fiche-row">
            <span class="fiche-label">Équipe assignée :</span>
            <span class="fiche-value"><?= htmlspecialchars($intervention['equipe']) ?></span>
          </div>
          <div class="fiche-row">
            <span class="fiche-label">Matériel mobilisé :</span>
            <span class="fiche-value"><?= htmlspecialchars($intervention['materiel']) ?></span>
          </div>
        </div>
        
        <div class="intervention-section">
          <div class="section-title">Facturation</div>
          <div class="fiche-row">
            <span class="fiche-label">Temps de travail :</span>
            <span class="fiche-value fiche-temps"><?= htmlspecialchars($intervention['temps_travail']) ?></span>
          </div>
          <div class="fiche-row">
            <span class="fiche-label">Coût total :</span>
            <span class="fiche-value fiche-prix"><?= htmlspecialchars($intervention['cout']) ?> €</span>
          </div>
        </div>
        
        <div class="intervention-section">
          <div class="section-title">Notes additionnelles</div>
          <div class="fiche-row">
            <span class="fiche-value" style="white-space: pre-wrap;"><?= htmlspecialchars($intervention['commentaire']) ?></span>
          </div>
        </div>
      </div>
    </div>
  </main>

  <script>
    function confirmDelete(id) {
      if (confirm("Êtes-vous sûr de vouloir supprimer cette intervention ?")) {
        window.location.href = "delete-intervention.php?id=" + id;
      }
    }
    
    function generatePDF() {
      alert("Fonctionnalité d'export PDF en cours de développement");
      // Implémentation future pour l'export PDF
    }
  </script>
</body>
</html>