<?php
require_once 'database.php';
session_start();

if (!isset($_GET['id'])) {
  header("Location: commandes.php");
}

$commande_id = (int) $_GET['id'];
$commande = $mysqli->query("SELECT * FROM commandes WHERE id = $commande_id")->fetch_assoc();
$equipes = $mysqli->query("SELECT * FROM equipe WHERE status = 'Active'");

// Générer le prochain numéro d'intervention
$lastNum = $mysqli->query("SELECT numero FROM interventions ORDER BY id DESC LIMIT 1")->fetch_assoc()['numero'] ?? 'A1000';
preg_match('/A(\d+)/', $lastNum, $matches);
$nextNum = 'A' . str_pad((int)$matches[1] + 1, 4, '0', STR_PAD_LEFT);

// Empêcher l'insertion si une intervention existe déjà pour cette commande
$existe = $mysqli->query("SELECT COUNT(*) as total FROM interventions WHERE commande_id = $commande_id")
               ->fetch_assoc()['total'] > 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$existe) {
  $numero = $_POST['numero'];
  $date = $_POST['date_intervention'];
  $client = $_POST['demandeur'];
  $equipe = $_POST['equipe'];
  $type = $_POST['type'];
  $lieu = $_POST['lieu'];
  $statut = $_POST['statut'];
  $materiel = $_POST['materiel'];
  $temps = $_POST['temps_travail'];
  $cout = $_POST['cout'];
  $commentaire = $_POST['commentaire'];

  $stmt = $mysqli->prepare("INSERT INTO interventions (numero, date_intervention, client, equipe, type, lieu, statut, materiel, temps_travail, cout, commentaire, commande_id)
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("ssssssssssis", $numero, $date, $client, $equipe, $type, $lieu, $statut, $materiel, $temps, $cout, $commentaire, $commande_id);
  $stmt->execute();

  header("Location: intervention.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Affectation</title>
  <link rel="stylesheet" href="affectation.css">
</head>
<body>
  <?php include 'navbar.php'; ?>
  
    <main>
        <?php include 'topbar.php'; ?>
        <div class="page-container">
            
            <h1>Affecter une commande #<?= htmlspecialchars($commande['numero']) ?></h1>

            <?php if ($existe): ?>
            <p style="color: red; font-weight: bold; padding: 1em; background: #fdf0f0; border: 1px solid #e0b4b4; border-radius: 8px;">Une intervention est déjà liée à cette commande.</p>
            <?php else: ?>
            <form method="POST">
                <div class="formLine">
                    <label>Numéro intervention:
                        <input name="numero" value="<?= $nextNum ?>" readonly>
                    </label>

                    <label>Numéro commande:
                        <input type="numero" name="numero" value="<?= htmlspecialchars($commande['numero']) ?>" readonly>
                    </label>

                    <label>Date intervention:
                        <input type="date" name="date_intervention" value="<?= htmlspecialchars($commande['date_intervention']) ?>" required>
                    </label>

                    <label>Demandeur (client):
                        <input name="demandeur" value="<?= htmlspecialchars($commande['demandeur']) ?>" required>
                    </label>
                </div>

                <div class="formLine">
                    <label>Équipe:
                        <select name="equipe">
                        <?php while ($eq = $equipes->fetch_assoc()): ?>
                            <option value="<?= $eq['nomEquipe'] ?>"><?= $eq['nomEquipe'] ?> - <?= $eq['chefEquipe'] ?></option>
                        <?php endwhile; ?>
                        </select>
                    </label>

                    <label>Type:
                        <input name="type" placeholder="Installation, Maintenance, Audit..." required>
                    </label>

                    <label>Lieu:
                        <input name="lieu" placeholder="Ville ou site client" required>
                    </label>

                    <label>Statut:
                        <select name="statut">
                        <option value="En cours">En cours</option>
                        <option value="Validé">Validé</option>
                        </select>
                    </label>
                </div>

                <div class="formLine f2">
                    <label>Matériel:
                        <textarea name="materiel" placeholder="Liste matériel mobilisé"></textarea>
                    </label>

                    <label>Temps de travail:
                        <input name="temps_travail" placeholder="ex: 6h">
                    </label>

                    <label>Coûts:
                        <input type="number" name="cout" placeholder="Montant en €">
                    </label>
                </div>

            <label class="resize">Commentaires:
                <textarea name="commentaire" placeholder="Remarques supplémentaires"></textarea>
            </label>

            <button class="resize" type="submit">Créer l'intervention</button>
            </form>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>