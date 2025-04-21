<?php
require_once 'database.php';
session_start();

$result = $mysqli->query("SELECT * FROM equipe");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Equipes</title>
  <link rel="stylesheet" href="equipe.css">
</head>
<body>
  <?php include 'navbar.php'; ?>
  

  <main>
    <?php include 'topbar.php'; ?>
    <h1>Equipes</h1>
    <div class="page-container">
    
    <table class="table-equipes">
      <thead>
        <tr>
          <th>Nom Equipe</th>
          <th>Chef</th>
          <th>Numero de Tel</th>
          <th>Email</th>
          <th>Membres</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($row['nomEquipe']) ?></td>
            <td><?= htmlspecialchars($row['chefEquipe']) ?></td>
            <td><?= htmlspecialchars($row['numeroTel']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td>
              <?php 
              $membres = explode(',', $row['membres']);
              foreach ($membres as $membre): ?>
                <span class="members-badge m1"><?= htmlspecialchars(trim($membre)) ?></span>
                <span class="members-badge m2"><?= htmlspecialchars(trim($membre)) ?></span>
                <span class="members-badge m3"><?= htmlspecialchars(trim($membre)) ?></span>
                <span class="members-badge m4"><?= htmlspecialchars(trim($membre)) ?></span>
                <span class="members-badge m5"><?= htmlspecialchars(trim($membre)) ?></span>
              <?php endforeach; ?>
            </td>
            <td>
              <?php if ($row['status'] === 'Active'): ?>
                <span class="status-active">Active</span>
              <?php else: ?>
                <span class="status-inactive">Inactive</span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
  </main>
  
</body>
</html>