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

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");  
    exit();
}
?>


<?php 
require_once 'Fonctions/db_connection.php';
require_once 'Fonctions/fonctions.php';

$con = getConnection();


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['titre'], $_POST['type'], $_POST['dateD'], $_POST['dateF'], $_POST['description'])) {
    $id = $_POST['id'];
    $titre = $_POST['titre'];
    $type = $_POST['type'];
    $description = $_POST['description'];  
    $dateDebut = $_POST['dateD'];
    $dateFin = $_POST['dateF'];

    if (empty($dateDebut) || empty($dateFin)) {
        echo "Les dates de début et de fin sont obligatoires.";
        exit;
    }

    if (!strtotime($dateDebut) || !strtotime($dateFin)) {
        echo "Les dates doivent être valides (au format YYYY-MM-DD).";
        exit;
    }

    $sqlFormateur = "SELECT matricule, nomForm, prenomForm FROM Formateur WHERE specialite = ?";
    $stmt = $con->prepare($sqlFormateur);
    $stmt->bind_param("s", $titre);  
    $stmt->execute();
    $result = $stmt->get_result();

    if ($formateur = $result->fetch_assoc()) {
        $formateurId = $formateur['matricule'];

        $sql = "INSERT INTO Cours (idC, idForm, titreC, description, type, dateD, dateF) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("sssssss",$id, $formateurId, $titre, $description, $type, $dateDebut, $dateFin);

        if ($stmt->execute()) {
            echo "Cours ajouté avec succès.";
        } else {
            echo "Erreur lors de l'ajout du cours dans la table Cours : " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Formateur non trouvé pour ce type.";
    }
}

$sqlCours = "SELECT * FROM Cours";
$resultCours = $con->query($sqlCours);

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
<div class="card-header d-flex justify-content-between align-items-center bg-secondary-subtle text-primary rounded-3">
              
<button class="btn btn-success rounded-5 shadow" data-bs-toggle="modal" data-bs-target="#addCoursModal">
                        <i class="fas fa-plus m-lg-1"></i>Ajouter un Cours
                    </button> 
                   
          </div>
          
          <div class="container my-5">
          <a href= "enseig.php" class="btn btn-primary">
               Formateur par specialite
        </a>  
      <h1 class="text-center text-bold text-dark">Liste des cours </h1>
      
         
          <div class="card-body  ">
              <div class="table-responsive ">

            </div>
            <table class="table table-striped table-hover table-bordered rounded-3 align-middle mt-4 ">
                      <thead class="y">
        <tr class="text-center fw-bold">
          
            <th scope="col">Type</th>
            <th scope="col">Titre</th>
            <th scope="col">Description</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($resultCours->num_rows > 0): ?>
            <?php while ($transaction = $resultCours->fetch_assoc()): ?>
                <tr>
                    <td class="text-center"><?= htmlspecialchars($transaction['type']) ?></td>
                    <td class="text-center"><?= htmlspecialchars($transaction['titreC']) ?></td>
                    <td class="text-center"><?= htmlspecialchars($transaction['description']) ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="3" class="text-center">Aucun cours trouvé.</td>
            </tr>
        <?php endif; ?>
    </tbody>
    <?php
$con->close();
?>
</table>   
  
                </div>
            </div>

            <!-- Modal pour ajouter un cours -->
            <div class="modal fade" id="addCoursModal" tabindex="-1" aria-labelledby="addCoursModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-3 shadow">
                <div class="modal-header bg-dark-subtle">
                    <h5 class="modal-title text-success" id="addCoursModalLabel"><i class="fas fa-book me-2"></i>Ajouter un Cours</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST">
                    <div class="mb-3">
                            <label for="id" class="form-label">identifiant</label>
                            <input type="id" class="form-control" id="id" name="id" required>
                        </div>
                        <div class="mb-3">
                            <label for="titre" class="form-label">Titre du Cours</label>
                            <input type="text" class="form-control" id="titre" name="titre" required>
                        </div>
                        <div class="mb-3">
                            <label for="type" class="form-label">Type de Cours</label>
                            <input type="text" class="form-control" id="type" name="type" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="dateD" class="form-label">Date de debut</label>
                            <input class="form-control" id="dateD" name="dateD" type="date" required></input>
                        </div>
                        <div class="mb-3">
                            <label for="dateF" class="form-label">Date de fin</label>
                            <input class="form-control" id="dateF" name="dateF" type="date" required></input>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                            <button type="submit" class="btn btn-success">Ajouter Cours</button>
                        </div>
                    </form>
                </div>
                
            </div>
        </div>
    </div>
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
