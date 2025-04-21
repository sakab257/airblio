<?php
require_once 'database.php';
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

$limit = 7;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$totalResult = $mysqli->query("SELECT COUNT(*) as total FROM materiel");
$totalRows = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);

$sql = "SELECT * FROM materiel ORDER BY nom_materiel ASC LIMIT $limit OFFSET $offset";
$result = $mysqli->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Matériels | Airblio</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="materiel.css">
</head>
<body>
  <?php include "navbar.php"?>
  
  <main>
    <?php include "topbar.php"?>
    <div class="materiel-bar">
      <h1>Matériels</h1>
      <div style="display:flex;">
        <button type="submit" class="rightBtn" onclick="window.location.href='ajouter_materiel.php'">
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000"><path d="M440-440H200v-80h240v-240h80v240h240v80H520v240h-80v-240Z"/></svg>
          Ajouter
        </button>
        <button type="submit" class="rightBtn">
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000"><path d="M480-80q-82 0-155-31.5t-127.5-86Q143-252 111.5-325T80-480q0-83 31.5-155.5t86-127Q252-817 325-848.5T480-880q83 0 155.5 31.5t127 86q54.5 54.5 86 127T880-480q0 82-31.5 155t-86 127.5q-54.5 54.5-127 86T480-80Zm0-82q26-36 45-75t31-83H404q12 44 31 83t45 75Zm-104-16q-18-33-31.5-68.5T322-320H204q29 50 72.5 87t99.5 55Zm208 0q56-18 99.5-55t72.5-87H638q-9 38-22.5 73.5T584-178ZM170-400h136q-3-20-4.5-39.5T300-480q0-21 1.5-40.5T306-560H170q-5 20-7.5 39.5T160-480q0 21 2.5 40.5T170-400Zm216 0h188q3-20 4.5-39.5T580-480q0-21-1.5-40.5T574-560H386q-3 20-4.5 39.5T380-480q0 21 1.5 40.5T386-400Zm268 0h136q5-20 7.5-39.5T800-480q0-21-2.5-40.5T790-560H654q3 20 4.5 39.5T660-480q0 21-1.5 40.5T654-400Zm-16-240h118q-29-50-72.5-87T584-782q18 33 31.5 68.5T638-640Zm-234 0h152q-12-44-31-83t-45-75q-26 36-45 75t-31 83Zm-200 0h118q9-38 22.5-73.5T376-782q-56 18-99.5 55T204-640Z"/></svg>
        </button>
      </div>
    </div>
    
    <div class="page-container">
      <table class="commandesTable">
        <thead>
          <tr>
            <th>Nom du Matériel</th>
            <th>Type</th>
            <th>État</th>
            <th>Lieu</th>
            <th>Équipe</th>
            <th>Responsable</th>
            <th>Date dernière opération</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()): ?>
          <tr class="intervention-row" onclick="window.location.href='materiel_detail.php?id=<?= $row['id'] ?>'">
            <td><?= htmlspecialchars($row['nom_materiel']) ?></td>
            <td><?= htmlspecialchars($row['materiel_mobilise']) ?></td>
            <td><?= htmlspecialchars($row['etat_fonctionnement']) ?></td>
            <td><?= htmlspecialchars($row['lieu_operation']) ?></td>
            <td><?= htmlspecialchars($row['nomEquipe']) ?></td>
            <td><?= htmlspecialchars($row['chefEquipe']) ?></td>
            <td><?= htmlspecialchars($row['date_operation']) ?></td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
      
      <div class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
          <a href="?page=<?= $i ?>" class="<?= $i == $page ? 'active' : '' ?>"> <?= $i ?> </a>
        <?php endfor; ?>
      </div>
    </div>
  </main>

  <script>
    // JavaScript pour activer le clic sur toute la rangée
    document.querySelectorAll('.intervention-row').forEach(row => {
      row.style.cursor = 'pointer';
    });
  </script>
</body>
</html>