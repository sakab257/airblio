<?php

session_start();


if (isset($_SESSION["user_id"])){
    $mysqli = require __DIR__ . "/database.php";

    $sql = "SELECT * FROM users
            WHERE id = {$_SESSION["user_id"]}";

    $result = $mysqli->query($sql);

    $user = $result->fetch_assoc();
}

if(!isset($user)){
    header("Location: login.php");
    exit;
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Accueil | Airblio</title>
  <link rel="stylesheet" href="style.css">
  <script src="js/app.js" defer></script>
</head>
<body>
    <?php include "navbar.php"?>
    <main>
        <?php include "topbar.php"?>
        <h1>Mappemonde</h1>
        <div class="container"></div>
    </main>
</body>
</html>