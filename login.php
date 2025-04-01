<?php

$is_invalid = false;

if ($_SERVER["REQUEST_METHOD"] === "POST"){
    $mysqli = require __DIR__ . "/database.php";

    $sql = sprintf("SELECT * FROM users
                    WHERE email = '%s'",
                    $mysqli->real_escape_string($_POST["email"]));

    $result = $mysqli->query($sql);
    
    $user = $result->fetch_assoc();
    
    if($user){
        if(password_verify($_POST["password"], $user["mdp_hash"])){
            session_start();

            session_regenerate_id();

            $_SESSION["user_id"] = $user["id"];
            header("Location: index.php");
            exit;
        }
    }

    $is_invalid = true;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion | Airblio</title>
  <link rel="stylesheet" href="form-style.css">
</head>
<body>
  <div class="login-container">
    <div class="login-card">
        <h2>Connexion</h2>


        <?php if ($is_invalid):?>
            <em style="color:red;">Identifiants incorrects</em>
        <?php endif; ?>


        <form id="login-form" method="post">
            <div class="input-group">
                <label for="mail">Email</label> 
                <input name="email" id="mail" type="email" 
                value="<?= htmlspecialchars($_POST["email"] ?? "") ?>" placeholder="Entrez votre mail...">
            </div>

            <div class="input-group">
                <label for="password">Mot de passe</label> 
                <div class="password-wrapper">
                    <input name="password" id="password" type="password" placeholder="Entrez votre mot de passe...">
                    <img id="toggle-password" src="img/visibility_24dp_E3E3E3_FILL0_wght400_GRAD0_opsz24.svg" alt="Afficher le mot de passe" onclick="togglePassword()">
                </div>
            </div>

            

            <button type="submit" class="login-btn">Se connecter</button>
            <p class="register-link">Pas encore de compte ? <a href="signup.php">S'inscrire</a></p>
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
