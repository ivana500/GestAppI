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
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");  
    exit();
}
?>

<?php
require_once 'Fonctions/db_connection.php';
require_once 'Fonctions/fonctions.php';

if (isset($_GET['idAp']) && isset($_GET['idC'])) {
    $idAp = $_GET['idAp'];
    $idC = $_GET['idC'];

    $conn = getConnection();

    $checkApprenantSql = "SELECT * FROM Apprenant WHERE code = ?";
    $stmt = $conn->prepare($checkApprenantSql);
    $stmt->bind_param('s', $idAp);  
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        echo "L'apprenant avec l'ID $idAp n'existe pas dans la table Apprenant.";
        exit;
    }


    // Requête pour récupérer l'assiduité existante
    $sql = "SELECT * FROM suivieCours WHERE idAp = ? AND idC = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $idAp, $idC);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $assiduite = $result->fetch_assoc();  // Récupérer les données d'assiduité
    } else {
        echo "Aucune assiduité trouvée.";
        exit;
    }

    // Récupérer la liste des apprenants et des cours pour les afficher dans les selects
    $sqlApprenants = "SELECT * FROM Apprenant";
    $apprenantResult = $conn->query($sqlApprenants);
    $apprenants = [];
    if ($apprenantResult->num_rows > 0) {
        while ($row = $apprenantResult->fetch_assoc()) {
            $apprenants[] = $row;
        }
    }

    $sqlCours = "SELECT * FROM Cours";
    $coursResult = $conn->query($sqlCours);
    $cours = [];
    if ($coursResult->num_rows > 0) {
        while ($row = $coursResult->fetch_assoc()) {
            $cours[] = $row;
        }
    }

    // Traitement du formulaire de modification
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modifier'])) {
        $apprenantId = $_POST['apprenant'];
        $coursId = $_POST['cours'];
        $present = $_POST['present'];
        $date = $_POST['date'];


        $updateSql = "UPDATE suivieCours SET idAp = ?, idC = ?, date = ?, present = ? WHERE idAp = ? AND idC = ?";
        $stmt = $conn->prepare($updateSql);
        $stmt->bind_param('ssssss', $apprenantId, $coursId, $date, $present, $idAp, $idC);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Assiduité mise à jour avec succès!";
            header("Location: assiduite.php");
            exit;
        } else {
            echo "Erreur lors de la mise à jour de l'assiduité.";
        }
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: assiduite.php");
    exit;
}
?>




<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
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
        
        <div class="sidebar flex-shrink-0 p-3 ">
            

            
    <h4 class="text-center mb-4 y" >
        <a href="acceuil.php" style="text-decoration: none; color: white;">ADMINISTRATEUR</a>
    </h4>

    <div class="d-flex justify-content-center mb-3">
    <a class="nav-link" href="dasboard.php">
    <img src="images/stephan.png" alt="User" class="rounded-circle" style="width: 80px; height: 80px;">
</a>          </div>

    <div class="nav flex-column">
        <li class="nav-item mb-3">
        <a class="nav-link text-light " href="apprenant.php">
        <i class="fas fa-list me-2 ">   </i>Listes Apprenants
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
        
</head>
<body>

<div class="container mt-5">
    <h2>Modifier l'Apprenant</h2>

    <form action="" method="POST">
    <div class="mb-3">
        <label for="apprenant" class="form-label">Apprenant</label>
        <select class="form-select" id="apprenant" name="apprenant" required>
            <option value="">Sélectionner un Apprenant</option>
            <?php foreach ($apprenants as $apprenant): ?>
                <option value="<?= $apprenant['code'] ?>" 
                    <?= $assiduite['idAp'] == $apprenant['code'] ? 'selected' : '' ?>>
                    <?= $apprenant['nomAp'] . ' ' . $apprenant['prenomAp'] ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="cours" class="form-label">Cours</label>
        <select class="form-select" id="cours" name="cours" required>
            <option value="">Sélectionner un Cours</option>
            <?php foreach ($cours as $cours_item): ?>
                <option value="<?= $cours_item['idC'] ?>" 
                    <?= $assiduite['idC'] == $cours_item['idC'] ? 'selected' : '' ?>>
                    <?= $cours_item['titreC'] ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="present" class="form-label">Présent</label>
        <select class="form-select" id="present" name="present" required>
            <option value="1" <?= $assiduite['present'] == 1 ? 'selected' : '' ?>>Oui</option>
            <option value="0" <?= $assiduite['present'] == 0 ? 'selected' : '' ?>>Non</option>
        </select>
    </div>

    <div class="mb-3">
        <label for="date" class="form-label">Date du Cours</label>
        <input type="date" class="form-control" id="date" name="date" value="<?= $assiduite['date'] ?>" required />
    </div>

    <div class="d-flex justify-content-between">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        <button type="submit" name="modifier" class="btn btn-warning">modifier</button>
    </div>
</form>
</div>

<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
