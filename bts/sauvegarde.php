<?php
session_start();  
if (!isset($_SESSION['nom']) || !isset($_SESSION['prenom'])) {
   
    header("Location: index.php");
    exit();
}

$nom = $_SESSION['nom'];
$prenom = $_SESSION['prenom'];

if (!isset($_SESSION['theme'])) {
    $_SESSION['theme'] = 'light';  
}

$themeClass = $_SESSION['theme'] === 'dark' ? 'dark-theme' : 'light-theme';
?>


<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sauvegarde manuelle de données
    $selected_data = $_POST['data-type'];
    $backup_type = $_POST['backup-type'];

    // Logique pour effectuer la sauvegarde des données (selon le type sélectionné)
    // Par exemple : sauvegarder dans la base de données ou créer un fichier zip

    // Ajouter une entrée à l'historique des sauvegardes
    // Cette partie peut être modifiée pour enregistrer réellement les sauvegardes dans une base de données ou un fichier
    $backup_time = date("Y-m-d H:i:s");
    $backup_history[] = ["time" => $backup_time, "data" => $selected_data, "type" => $backup_type];
    $_SESSION['backup_history'] = $backup_history;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Sauvegarde de Données</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="fontawesome/css/all.css" rel="stylesheet">
    <style>
        .light-theme {
            background-color: white;
            color: #333;
        }

        .dark-theme {
            background-color: #333;
            color: #f8f9fa;
        }

        .sidebar {
            background-color: black;
            width: 300px;
        }
        .sidebar .nav-link {
            position: relative;
            padding: 10px;
            transition: all 0.3s ease-in-out; 
            text-decoration: none;
        }

        .sidebar .nav-link:hover {
            border: 2px solid black; 
            color: black; 
        }

        .table th, .table td {
            font-size: 1.1rem; 
        }

        .sidebar .nav-link i {
            transition: transform 0.3s ease-in-out; 
        }

        .sidebar .nav-link:hover i {
            transform: rotate(360deg);
        }

        .sidebar .nav-link:active {
            color: red; 
            border: 2px solid black; 
        }

        .search-bar {
            margin-left: auto;
            padding: 5px 10px;
        }

        table, th, td {
            border: 1px solid #ddd; 
        }

        table th:hover {
            background-color: #8de1e3; 
        }

        .content {
            margin-left: 300px;
        }

        header {
            background-color: rgb(111, 235, 239);
        }

        .search-bar {
            margin-left: auto;
        }
    </style>
</head>
<body class="<?php echo $themeClass; ?>">

    <!-- Navbar -->
    <header class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <form method="POST" class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
                <div class="input-group">
                    <input class="form-control" type="text" name="search" placeholder="Rechercher par nom, prénom, email, etc." value="<?= isset($_POST['search']) ? htmlspecialchars($_POST['search']) : '' ?>" />
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>

            <div class="d-flex align-items-center text-light text-bold">
                <span><?php echo $nom . ' ' . $prenom; ?></span>
            </div>
        </div>
    </header>

    <div class="d-flex">
        <div class="sidebar flex-shrink-0 p-3">
            <h4 class="text-center mb-4">
                <a href="acceuil.php" style="text-decoration: none; color: white;">ADMINISTRATEUR</a>
            </h4>
            <div class="d-flex justify-content-center mb-3">
                <a class="nav-link" href="dasboard.php">
                    <img src="images/stephan.png" alt="User" class="rounded-circle" style="width: 80px; height: 80px;">
                </a>
            </div>
            <div class="nav flex-column">
                <li class="nav-item mb-3">
                    <a class="nav-link text-light" href="apprenant.php"><i class="fas fa-list me-2"></i>Listes Apprenants</a>
                </li>
                <li class="nav-item mb-3">
                    <a class="nav-link text-light" href="formateur.php"><i class="fas fa-list me-2"></i>Listes Formateurs</a>
                </li>
                <li class="nav-item mb-3">
            <a class="nav-link text-light" href="assiduite.php">
                <i class="fas fa-list me-2"></i>Listes Assiduité
            </a>
        </li>
        <li class="nav-item mb-3">
            <a class="nav-link text-light" href="cours.php">
                <i class="fas fa-list me-2"></i>Listes Cours
            </a>
        </li>
        <li class="nav-item mb-3">
            <a class="nav-link text-light" href="emploiTemps.php">
                <i class="fas fa-calendar me-2"></i>Emploi du Temps
            </a>
        </li>
        <li class="nav-item mb-3">
            <a class="nav-link text-light" href="comptabilite.php">
                <i class="fas fa-list me-2"></i>Listes Paiements
            </a>
        </li>
        <li class="nav-item mb-3">
            <a class="nav-link text-light" href="rapport.php">
                <i class="fas fa-file-alt me-2"></i>Affichage des Rapports
            </a>
        </li>
        <li class="nav-item mb-3">
            <a class="nav-link text-light" href="util.php">
                <i class="fas fa-users me-2"></i>Gestion des Utilisateurs
            </a>
        </li>
                <li class="nav-item mb-3">
                    <a class="nav-link text-light" href="sauvegarde.php"><i class="fas fa-database me-2"></i>Sauvegarde des Données</a>
                </li>
                <li class="nav-item mb-3">
            <a class="nav-link text-light" href="appli.php">
                <i class="fas fa-tools me-2"></i>Paramètres de l'Application
            </a>
        </li>
            </div>
        </div>

        <!-- Page Content -->
        <div class="content p-4">
            <h1>Sauvegarde des Données</h1>

            <form method="POST">
                <div class="form-group">
                    <label for="data-type">Sélectionner les données à sauvegarder :</label>
                    <select id="data-type" class="form-control" name="data-type">
                        <option value="all">Toutes les données</option>
                        <option value="students">Données des apprenants</option>
                        <option value="trainers">Données des formateurs</option>
                        <option value="payments">Données financières</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="backup-type">Type de sauvegarde :</label>
                    <select id="backup-type" class="form-control" name="backup-type">
                        <option value="full">Complète</option>
                        <option value="incremental">Incrémentielle</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Lancer la Sauvegarde</button>
            </form>

            <h3 class="mt-5">Historique des Sauvegardes</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Données sauvegardées</th>
                        <th>Type de sauvegarde</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($_SESSION['backup_history'])): ?>
                        <?php foreach ($_SESSION['backup_history'] as $backup): ?>
                            <tr>
                                <td><?php echo $backup['time']; ?></td>
                                <td><?php echo $backup['data']; ?></td>
                                <td><?php echo $backup['type']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-center">Aucune sauvegarde effectuée</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
