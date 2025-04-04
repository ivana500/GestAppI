<?php
session_start();  
if (!isset($_SESSION['nom']) || !isset($_SESSION['prenom'])) {
   
    header("Location: connexion.php");
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

$searchQuery = "";
if (isset($_POST['search'])) {
    $searchQuery = $_POST['search'];
}

$conn = getConnection();

if ($searchQuery != "") {
    $query = "SELECT * FROM apprenant WHERE nomAp LIKE ? OR prenomAp LIKE ? OR email LIKE ? OR telephone LIKE ? OR cours LIKE ?";
    $stmt = $conn->prepare($query);
    $searchTerm = "%" . $searchQuery . "%";  
    $stmt->bind_param("sssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm);
} else {
    $query = "SELECT * FROM apprenant";
    $stmt = $conn->prepare($query);
}

$stmt->execute();
$result = $stmt->get_result();
$apprenants = [];
while ($row = $result->fetch_assoc()) {
    $apprenants[] = $row;
}

$stmt->close();

if (isset($_GET['code'])) {
    $code = $_GET['code'];

    $sqlDeleteSuivie = "DELETE FROM suivieCours WHERE idAp = ?";
    $stmtSuivie = $conn->prepare($sqlDeleteSuivie);
    $stmtSuivie->bind_param("s", $code);  
    $stmtSuivie->execute();
    $stmtSuivie->close();

    $sqlDeleteInscription = "DELETE FROM inscription WHERE idAp = ?";
    $stmtInscription = $conn->prepare($sqlDeleteInscription);
    $stmtInscription->bind_param("s", $code);  
    $stmtInscription->execute();
    $stmtInscription->close();

    $sqlDeleteApprenant = "DELETE FROM apprenant WHERE code = ?";
    $stmtApprenant = $conn->prepare($sqlDeleteApprenant);
    $stmtApprenant->bind_param("s", $code);  
    if ($stmtApprenant->execute()) {
        $_SESSION['success_message'] = "Apprenant supprimé avec succès!";
    } else {
        $_SESSION['error_message'] = "Erreur lors de la suppression de l'apprenant.";
    }
    $stmtApprenant->close();
    $conn->close();

    if (!isset($_SESSION['redirected'])) {
        $_SESSION['redirected'] = true;
        header('Location: apprenant.php');
        exit;
    } else {
        unset($_SESSION['redirected']);
    }
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
<body class="<?php echo $themeClass; ?>">    

        <div class="container my-5">
        <h1 class="text-center text-bold text-dark">Liste des Apprenants</h1>
        
           
            <div class="card-body  ">
                <div class="table-responsive ">
                    <table class="table table-striped table-hover table-bordered rounded-3 align-middle mt-4 ">
                        <thead class="y">
                            <tr class="text-center  ">
                                <th scope="col">Id</th>
                                <th scope="col">Nom</th>
                                <th scope="col">Prénom</th>
                                <th scope="col">Email</th>
                                <th scope="col">Téléphone</th>
                                <th scope="col">Date d'Inscription</th>
                                <th scope="col">Cours</th>
                                <th scope="col">Photo</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                    <?php foreach ($apprenants as $index => $apprenant): ?>
                        <tr class="text-center">
                            <td><?= $index + 1 ?></td>
                            <td><?= $apprenant['nomAp'] ?></td>
                            <td><?= $apprenant['prenomAp'] ?></td>
                            <td><?= $apprenant['email'] ?></td>
                            <td><?= $apprenant['telephone'] ?></td>
                            <td><?= $apprenant['dateIns'] ?></td>
                            <td><?= $apprenant['cours'] ?></td>
                            <td>
                                <?php if ($apprenant['photo']): ?>
                                    <img src="uploads/<?= $apprenant['photo'] ?>" alt="Photo" style="width: 50px; height: 50px; object-fit: cover;">
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <a class="btn btn-warning btn-edit me-2" href="traiteApp.php?code=<?= $apprenant['code'] ?>">
                                <i class="fas fa-pencil-alt"></i>
                                </a>
                                <a class="btn btn-danger btn-delete" href="apprenant.php?code=<?= $apprenant['code'] ?>" onClick="return confirm('Voulez-vous supprimer ce suivi ?')">
                                <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                    </table>
                    <a class="nav-link text-dark" href="inscriptionA.php">
                                        <i class="fas fa-tools me-2 text-primary"></i>inscrivez un apprenant
                                    </a>
                </div>
            </div>
        </div>

   

    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="js/bootstrap.min.js" type="text/javascript"></script>
    <script src="js/jquery-3.7.1.min.js" type="text/javascript"></script>
    <script src="js/popper.min.js" type="text/javascript"></script>
    
</body>
</html>
