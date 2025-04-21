<?php
require_once 'database.php';


  $user = null;
  if (isset($_SESSION['user_id'])) {
    $stmtTopbar = $mysqli->prepare("SELECT * FROM users WHERE id = ?");
    if ($stmtTopbar) {
      $stmtTopbar->bind_param("i", $_SESSION['user_id']);
      $stmtTopbar->execute();
      $resultTopbar = $stmtTopbar->get_result();
      $user = $resultTopbar->fetch_assoc();
    }
  }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Topbar</title>
    <link rel="stylesheet" href="top-bar-style.css">
</head>
<body>
    <div id="top-bar">
        <div class="search-container">
            <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="rgba(0,0,0,0.4)"><path d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z"/></svg>
            <input id="inputBar" type="text" placeholder="Rechercher...">
        </div>

        <div class="icon-container">
            <button class="icon-btn notification-btn">
                <div class="popUp" id="notification-popup" style="display:none;">
                    <div style="padding: 1em; color: white;"></div>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M160-200v-80h80v-280q0-83 50-147.5T420-792v-28q0-25 17.5-42.5T480-880q25 0 42.5 17.5T540-820v28q80 20 130 84.5T720-560v280h80v80H160Zm320-300Zm0 420q-33 0-56.5-23.5T400-160h160q0 33-23.5 56.5T480-80ZM320-280h320v-280q0-66-47-113t-113-47q-66 0-113 47t-47 113v280Z"/></svg>
            </button>
            <button class="icon-btn profile-btn" onclick="toggleProfilePopup()">
                <div class="popUp" id="profile-popup" style="display:none;">
                    <div style="padding: 1em;">
                    <?php if ($user): ?>
                        <p><strong>Nom :</strong> <?= htmlspecialchars($user['nom']) ?></p>
                        <p><strong>Prénom :</strong> <?= htmlspecialchars($user['prenom']) ?></p>
                        <p><strong>Email :</strong> <?= htmlspecialchars($user['email']) ?></p>
                        <p><strong>Rôle :</strong> Administrateur</p>
                    <?php else: ?>
                        <p><strong>Non connecté</strong></p>
                    <?php endif; ?>
                    </div>
                </div>

                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M234-276q51-39 114-61.5T480-360q69 0 132 22.5T726-276q35-41 54.5-93T800-480q0-133-93.5-226.5T480-800q-133 0-226.5 93.5T160-480q0 59 19.5 111t54.5 93Zm246-164q-59 0-99.5-40.5T340-580q0-59 40.5-99.5T480-720q59 0 99.5 40.5T620-580q0 59-40.5 99.5T480-440Zm0 360q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q53 0 100-15.5t86-44.5q-39-29-86-44.5T480-280q-53 0-100 15.5T294-220q39 29 86 44.5T480-160Zm0-360q26 0 43-17t17-43q0-26-17-43t-43-17q-26 0-43 17t-17 43q0 26 17 43t43 17Zm0-60Zm0 360Z"/></svg>
            </button>
        </div>
    </div>
    <script>
        function toggleProfilePopup() {
            const popup = document.getElementById("profile-popup");
            popup.style.display = popup.style.display === "none" ? "block" : "none";
        }
    </script>

</body>
</html>