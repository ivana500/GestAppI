<?php
session_start();  
if (!isset($_SESSION['nom']) || !isset($_SESSION['prenom'])) {
   
    header("Location: index.php");
    exit();
}

$nom = $_SESSION['nom'];
$prenom = $_SESSION['prenom'];
?>
<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SESSION['theme'] = $_POST['theme'];  
    $_SESSION['notifications'] = $_POST['notifications'];  
}

if (!isset($_SESSION['theme'])) {
    $_SESSION['theme'] = 'light';  
}

$themeClass = $_SESSION['theme'] === 'dark' ? 'dark-theme' : 'light-theme';
$notificationsStatus = $_SESSION['notifications'] ? 'Activées' : 'Désactivées';
?>


<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SESSION['theme'] = $_POST['theme'];  
    $_SESSION['notifications'] = $_POST['notifications'];  
}

if (!isset($_SESSION['theme'])) {
    $_SESSION['theme'] = 'light';  
}

$themeClass = $_SESSION['theme'] === 'dark' ? 'dark-theme' : 'light-theme';
$notificationsStatus = $_SESSION['notifications'] ? 'Activées' : 'Désactivées';
?>

<?php

if (!isset($_SESSION['theme'])) {
    $_SESSION['theme'] = 'light'; 
}

if (!isset($_SESSION['notifications'])) {
    $_SESSION['notifications'] = true; 
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['theme'])) {
        $_SESSION['theme'] = $_POST['theme'];
    }

    if (isset($_POST['notifications'])) {
        $_SESSION['notifications'] = $_POST['notifications'] === '1' ? true : false;
    }
}

$themeClass = $_SESSION['theme'] === 'dark' ? 'dark-theme' : 'light-theme';

$notificationsStatus = $_SESSION['notifications'] ? 'Activées' : 'Désactivées';
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paramètres de l'Application</title>
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
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            padding: 20px;
        }

        .sidebar .nav-link {
            position: relative;
            padding: 10px;
            transition: all 0.3s ease-in-out;
            text-decoration: none;
            color: white;
        }

        .sidebar .nav-link:hover {
            border: 2px solid black;
            color: black;
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

        /* Content */
        .content {
            margin-left: 300px;
            padding: 20px;
        }

        /* Navbar */
        header {
            background-color: rgb(111, 235, 239);
            padding: 10px;
        }

        .navbar-brand {
            color: white;
        }

        /* Bouton Principal */
        .primary-button {
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
        }

        .primary-button:hover {
            background-color: #0056b3;
        }

        .form-group {
            margin-bottom: 15px;
        }

        /* Cacher la barre de recherche */
        .search-bar {
            display: none;
        }

        /* Table */
        table, th, td {
            border: 1px solid #ddd;
        }

        table th:hover {
            background-color: #8de1e3;
        }
    </style>
</head>

<body class="<?php echo $themeClass; ?>">

    <!-- Sidebar -->
    <div class="sidebar">
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
                <a class="nav-link text-light" href="apprenant.php">
                    <i class="fas fa-list me-2"></i>Listes Apprenants
                </a>
            </li>
            <li class="nav-item mb-3">
                <a class="nav-link text-light" href="formateur.php">
                    <i class="fas fa-list me-2"></i>Listes Formateurs
                </a>
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
                <a class="nav-link text-light" href="sauvegarde.php">
                    <i class="fas fa-database me-2"></i>Sauvegarde des Données
                </a>
            </li>
            <li class="nav-item mb-3">
                <a class="nav-link text-light" href="appli.php">
                    <i class="fas fa-tools me-2"></i>Paramètres de l'Application
                </a>
            </li>
        </div>
    </div>

    <!-- Navbar -->
    <header class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center text-light text-bold">
                <span><?php echo $nom . ' ' . $prenom; ?></span>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="content">
        <h1>Paramètres de l'application</h1>
        
        <p>Notifications : <strong><?php echo $notificationsStatus; ?></strong></p>

        <form method="POST">
            <div class="form-group">
                <label for="theme">Choisissez le thème :</label>
                <select name="theme" id="theme">
                    <option value="light" <?php echo $_SESSION['theme'] === 'light' ? 'selected' : ''; ?>>Clair</option>
                    <option value="dark" <?php echo $_SESSION['theme'] === 'dark' ? 'selected' : ''; ?>>Sombre</option>
                </select>
            </div>

            <div class="form-group">
                <label for="notifications">Notifications :</label>
                <select name="notifications" id="notifications">
                    <option value="1" <?php echo $_SESSION['notifications'] ? 'selected' : ''; ?>>Activées</option>
                    <option value="0" <?php echo !$_SESSION['notifications'] ? 'selected' : ''; ?>>Désactivées</option>
                </select>
            </div>

            <button type="submit" class="primary-button">Sauvegarder</button>
        </form>

        <br>
        <button class="primary-button">Cliquez ici pour une action</button>
    </div>

</body>

</html>
