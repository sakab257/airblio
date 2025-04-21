<?php
require_once 'database.php';
session_start();

// Gestion des filtres
$statusFilter = isset($_GET['statut']) && $_GET['statut'] !== '' ? $_GET['statut'] : '';

// Gestion de la suppression des commandes
if (isset($_POST['supprimer']) && isset($_POST['commandes_to_delete'])) {
    $ids_to_delete = implode(",", array_map('intval', $_POST['commandes_to_delete']));
    
    // Suppression des interventions liées aux commandes (à cause de la clé étrangère)
    $mysqli->query("DELETE FROM interventions WHERE commande_id IN ($ids_to_delete)");
    
    // Puis suppression des commandes
    $mysqli->query("DELETE FROM commandes WHERE id IN ($ids_to_delete)");
    
    // Redirection pour éviter la soumission multiple du formulaire
    header("Location: commandes.php");
    exit;
}

$limit = 10;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Construction de la requête avec filtres
$whereClause = $statusFilter ? " WHERE statut = '$statusFilter'" : "";

// Total des commandes avec filtres éventuels
$totalResult = $mysqli->query("SELECT COUNT(*) as total FROM commandes" . $whereClause);
$totalRows = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);

// Commandes pour la page en cours avec filtres éventuels
$query = "SELECT * FROM commandes" . $whereClause . " ORDER BY id DESC LIMIT $limit OFFSET $offset";
$commandes = $mysqli->query($query);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commandes | Airblio</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="commande.css">
</head>
<body>
<?php include "navbar.php"?>
    <main>
        <?php include "topbar.php"?>
        
        <div class="commande-bar">
            <h1>Commandes</h1>

            <div class="barBtn">
                <button type="submit" form="mainForm" class="rightBtn" name="supprimer" value="1">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000">
                    <path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"/>
                    </svg>
                    Supprimer
                </button>
                    
                <div class="filter-container">
                    <button type="button" class="rightBtn" id="showFiltersBtn">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000">
                        <path d="M400-240v-80h160v80H400ZM240-440v-80h480v80H240ZM120-640v-80h720v80H120Z"/>
                        </svg>
                        Filtres
                    </button>
                    
                    <form method="GET" action="commandes.php" id="filterForm">
                        <input type="hidden" name="page" value="1">
                        <select name="statut" class="filter-select" id="statusFilter">
                            <option value="">Toutes les commandes</option>
                            <option value="En cours" <?= $statusFilter === 'En cours' ? 'selected' : '' ?>>En cours</option>
                            <option value="Validé" <?= $statusFilter === 'Validé' ? 'selected' : '' ?>>Validé</option>
                        </select>
                        <button type="submit" class="filter-apply" id="applyFilterBtn">Appliquer</button>
                    </form>
                </div>

                <button type="button" class="rightBtn">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000"><path d="M260-160q-91 0-155.5-63T40-377q0-78 47-139t123-78q25-92 100-149t170-57q117 0 198.5 81.5T760-520q69 8 114.5 59.5T920-340q0 75-52.5 127.5T740-160H520q-33 0-56.5-23.5T440-240v-206l-64 62-56-56 160-160 160 160-56 56-64-62v206h220q42 0 71-29t29-71q0-42-29-71t-71-29h-60v-80q0-83-58.5-141.5T480-720q-83 0-141.5 58.5T280-520h-20q-58 0-99 41t-41 99q0 58 41 99t99 41h100v80H260Zm220-280Z"/></svg>
                    Exporter
                </button>
            </div>
        </div>
        
        <div class="commandesContainer">
            <form method="POST" action="commandes.php" id="mainForm">
                <table class="commandesTable">
                    <thead>
                    <tr>
                    <th>
                    <svg style="cursor:pointer;" id="toggle-all" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="#000">
                        <path d="M200-440v-80h560v80H200Z"/>
                    </svg>
                    </th>

                    <th>
                    <span class="th-content">
                        Numéro
                        <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="#000">
                        <path d="M440-800v487L216-537l-56 57 320 320 320-320-56-57-224 224v-487h-80Z"/>
                        </svg>
                    </span>
                    </th>

                    <th>
                    <span class="th-content">
                        Date de l'intervention
                        <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="#000">
                        <path d="M440-800v487L216-537l-56 57 320 320 320-320-56-57-224 224v-487h-80Z"/>
                        </svg>
                    </span>
                    </th>

                    <th>
                    <span class="th-content">
                        Objet
                        <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="#000">
                        <path d="M440-800v487L216-537l-56 57 320 320 320-320-56-57-224 224v-487h-80Z"/>
                        </svg>
                    </span>
                    </th>

                    <th>
                    <span class="th-content">
                        Demandeur
                        <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="#000">
                        <path d="M440-800v487L216-537l-56 57 320 320 320-320-56-57-224 224v-487h-80Z"/>
                        </svg>
                    </span>
                    </th>

                    <th>
                    <span class="th-content">
                        Status
                        <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="#000">
                        <path d="M440-800v487L216-537l-56 57 320 320 320-320-56-57-224 224v-487h-80Z"/>
                        </svg>
                    </span>
                    </th>

                    <th>
                    <span class="th-content">
                        Commentaires
                        <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="#000">
                        <path d="M440-800v487L216-537l-56 57 320 320 320-320-56-57-224 224v-487h-80Z"/>
                        </svg>
                    </span>
                    </th>

                    </tr>
                    </thead>
                    <tbody>
                    <?php while ($row = $commandes->fetch_assoc()): ?>
                        <tr class="commande-row">
                            <td>
                                <input name="commandes_to_delete[]" value="<?= $row['id'] ?>" type="checkbox" class="commande-checkbox">
                            </td>
                            <td 
                                <?php if ($row['statut'] === 'En cours'): ?>
                                style="cursor: pointer"
                                title="Cliquez pour affecter cette commande"
                                <?php else: ?>
                                style="cursor: not-allowed; opacity: 0.6"
                                title="Commande déjà validée — non modifiable"
                                <?php endif; ?>
                                onclick="window.location.href='affectation.php?id=<?= $row['id'] ?>'"><?= htmlspecialchars($row['numero']) ?>
                            </td>
                            <td 
                                <?php if ($row['statut'] === 'En cours'): ?>
                                style="cursor: pointer"
                                title="Cliquez pour affecter cette commande"
                                <?php else: ?>
                                style="cursor: not-allowed; opacity: 0.6"
                                title="Commande déjà validée — non modifiable"
                                <?php endif; ?> 
                                onclick="window.location.href='affectation.php?id=<?= $row['id'] ?>'"><?= htmlspecialchars($row['date_intervention']) ?>
                            </td>
                            <td 
                                <?php if ($row['statut'] === 'En cours'): ?>
                                style="cursor: pointer"
                                title="Cliquez pour affecter cette commande"
                                <?php else: ?>
                                style="cursor: not-allowed; opacity: 0.6"
                                title="Commande déjà validée — non modifiable"
                                <?php endif; ?>
                                onclick="window.location.href='affectation.php?id=<?= $row['id'] ?>'"><?= htmlspecialchars($row['objet']) ?>
                            </td>
                            <td 
                                <?php if ($row['statut'] === 'En cours'): ?>
                                style="cursor: pointer"
                                title="Cliquez pour affecter cette commande"
                                <?php else: ?>
                                style="cursor: not-allowed; opacity: 0.6"
                                title="Commande déjà validée — non modifiable"
                                <?php endif; ?>
                                onclick="window.location.href='affectation.php?id=<?= $row['id'] ?>'"><?= htmlspecialchars($row['demandeur']) ?>
                            </td>
                            <?php
                                $status = $row['statut'];
                                $statusClass = match($status) {
                                    'Validé' => 'status-valide',
                                    'En cours' => 'status-encours',
                                    default => 'status-autre',
                                };
                            ?>
                            <td 
                                <?php if ($row['statut'] === 'En cours'): ?>
                                style="cursor: pointer"
                                title="Cliquez pour affecter cette commande"
                                <?php else: ?>
                                style="cursor: not-allowed; opacity: 0.6"
                                title="Commande déjà validée — non modifiable"
                                <?php endif; ?>
                                onclick="window.location.href='affectation.php?id=<?= $row['id'] ?>'" class="<?= $statusClass ?>"><?= htmlspecialchars($status) ?>
                            </td>
                            <td 
                                <?php if ($row['statut'] === 'En cours'): ?>
                                style="cursor: pointer"
                                title="Cliquez pour affecter cette commande"
                                <?php else: ?>
                                style="cursor: not-allowed; opacity: 0.6"
                                title="Commande déjà validée — non modifiable"
                                <?php endif; ?>
                                onclick="window.location.href='affectation.php?id=<?= $row['id'] ?>'"><?= htmlspecialchars($row['commentaires']) ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
                <div class="pagination">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="?page=<?= $i ?>&statut=<?= $statusFilter ?>" class="<?= $i == $page ? 'active' : '' ?>">
                        <?= $i ?>
                        </a>
                    <?php endfor; ?>
                </div>
                <input type="hidden" name="supprimer" value="1">
            </form>
        </div>
    </main>
    
    <script>
        // Toggle All Checkboxes
        const toggleAll = document.getElementById("toggle-all");
        let allChecked = false;

        toggleAll.addEventListener("click", () => {
            allChecked = !allChecked;
            document.querySelectorAll(".commande-checkbox").forEach(cb => {
                cb.checked = allChecked;
            });
        });

        // Filter Button
        const showFiltersBtn = document.getElementById("showFiltersBtn");
        const statusFilter = document.getElementById("statusFilter");
        const applyFilterBtn = document.getElementById("applyFilterBtn");
        const barBtn = document.querySelector(".barBtn"); // Ajoutez cette ligne
        let filtersVisible = false;

        showFiltersBtn.addEventListener("click", () => {
            // Toggle filter elements visibility
            filtersVisible = !filtersVisible;
            
            // Add/remove active class to button
            if (filtersVisible) {
                showFiltersBtn.classList.add("active");
                statusFilter.classList.add("show");
                applyFilterBtn.classList.add("show");
                barBtn.style.transform = "translateX(-150px)"; // Ajoutez cette ligne
            } else {
                showFiltersBtn.classList.remove("active");
                statusFilter.classList.remove("show");
                applyFilterBtn.classList.remove("show");
                barBtn.style.transform = "translateX(0)"; // Ajoutez cette ligne
            }
            
            // Automatically focus on the filter select when opened
            if (filtersVisible) {
                statusFilter.focus();
            }
        });

        // Close filters when clicking outside
        document.addEventListener("click", (event) => {
            if (filtersVisible && 
                !showFiltersBtn.contains(event.target) && 
                !statusFilter.contains(event.target) && 
                !applyFilterBtn.contains(event.target)) {
                
                filtersVisible = false;
                showFiltersBtn.classList.remove("active");
                statusFilter.classList.remove("show");
                applyFilterBtn.classList.remove("show");
                barBtn.style.transform = "translateX(0)"; // Ajoutez cette ligne
            }
        });
    </script>
</body>
</html>