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

require_once 'Fonctions/db_connection.php';

$conn = getConnection();

$sqlCours = "SELECT * FROM cours";
$coursResult = $conn->query($sqlCours);
$cours = [];
while ($ligne = $coursResult->fetch_assoc()) {
    $cours[$ligne['idC']] = $ligne;
}

$sqlApprenants = "SELECT * FROM Apprenant";
$apprenantResult = $conn->query($sqlApprenants);
$apprenants = [];
while ($row = $apprenantResult->fetch_assoc()) {
    $apprenants[$row['code']] = $row;
}

$sql = "SELECT 
    I.numero,  
    I.montant, 
    A.nomAp, 
    A.prenomAp, 
    C.type, 
    A.dateIns
FROM inscription I
JOIN Apprenant A ON I.idAp = A.code
LEFT JOIN suivieCours S ON A.code = S.idAp
LEFT JOIN cours C ON C.idC = S.idC";

$result = $conn->query($sql);

if (isset($_GET['delete'])) {
    $numero = $_GET['delete']; 

    $sqlDelete = "DELETE FROM inscription WHERE numero = ?";
    $stmt = $conn->prepare($sqlDelete);
    $stmt->bind_param("i", $numero); 
    $stmt->execute();
    $stmt->close();
    
    header('Location: comptabilite.php'); 
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
                            <tr class="text-center fw-bold">
                                <th scope="col">ID</th>
                                <th scope="col">Apprenant</th>
                                <th scope="col">Montant</th>
                                <th scope="col">Cours</th>
                                <th scope="col">Date Inscription</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
    <?php if ($result->num_rows > 0): ?>
        <?php while ($transaction = $result->fetch_assoc()): ?>
            <tr class="text-center">
                <td><?= $transaction['numero'] ?></td>
                <td><?= $transaction['nomAp'] . ' ' . $transaction['prenomAp'] ?></td>
                <td><?= $transaction['montant'] ?> FCFA</td>
                <td><?= $transaction['type'] ?></td>
                <td><?= $transaction['dateIns'] ?></td>
                <td>
                    <a href="index.php?delete=<?= $transaction['numero'] ?>" class="btn btn-danger btn-sm ms-2">Supprimer</a>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="6" class="text-center">Aucune transaction trouvée.</td>
        </tr>
    <?php endif; ?>
</tbody>

                    </table>

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
