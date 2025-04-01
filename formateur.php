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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nomForm'], $_POST['prenomForm'], $_POST['email'], $_POST['telephone'], $_POST['specialite'])) {
    
    $nomForm = $_POST['nomForm'];
    $prenomForm = $_POST['prenomForm'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];
    $specialite = $_POST['specialite'];
    $photo = '';
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["photo"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if ($imageFileType == "jpg" || $imageFileType == "jpeg" || $imageFileType == "png" || $imageFileType == "gif") {
            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
                $photo = basename($_FILES["photo"]["name"]);
            }
        }
    }

    $conn = getConnection();

    $stmt = $conn->prepare("INSERT INTO Formateur (matricule, nomForm, prenomForm, email, telephone, specialite, photo) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $matricule, $nomForm, $prenomForm, $email, $telephone, $specialite, $photo);

    if ($stmt->execute()) {
        
        header('Location: formateur.php');
        exit;
    } else {
        echo "Erreur : " . $stmt->error;
    }

    $stmt->close();
    closeConnection($conn);
}

if (isset($_GET['delete'])) {
    $index = $_GET['delete'];

    $conn = getConnection();
    $stmt = $conn->prepare("DELETE FROM Formateur WHERE matricule = ?");
    $stmt->bind_param("s", $_GET['delete']);
    $stmt->execute();
    $stmt->close();
    closeConnection($conn);
    header('Location: formateur.php');
    exit;
}
$searchQuery = "";
if (isset($_POST['search'])) {
    $searchQuery = $_POST['search'];
}

$conn = getConnection();

// Préparer la requête de sélection avec ou sans le filtre de recherche
if ($searchQuery != "") {
    // Ajouter la condition WHERE pour filtrer par nom, prénom, email, etc.
    $query = "SELECT * FROM Formateur WHERE nomForm LIKE ? OR prenomForm LIKE ? OR email LIKE ? OR telephone LIKE ? OR specialite LIKE ?";
    $stmt = $conn->prepare($query);
    $searchTerm = "%" . $searchQuery . "%";  // Le "%" permet de faire une recherche partielle
    $stmt->bind_param("sssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm);
} else {
    // Si aucun mot-clé n'est soumis, afficher tous les formateurs
    $query = "SELECT * FROM Formateur";
    $stmt = $conn->prepare($query);
}

// Exécution de la requête
$stmt->execute();
$result = $stmt->get_result();
$formateurs = [];
while ($row = $result->fetch_assoc()) {
    $formateurs[] = $row;
}

$stmt->close();
closeConnection($conn);

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
        <h1 class="text-center text-bold text-dark">Liste des Formateurs</h1>
        
           
            <div class="card-body  ">
                <div class="table-responsive ">
                    <table class="table table-striped table-hover table-bordered rounded-3 align-middle mt-4 ">
                        <thead class="y">
                            <tr class="text-center fw-bold">
                                <th scope="col">ID</th>
                                <th scope="col">Nom</th>
                                <th scope="col">Prénom</th>
                                <th scope="col">Email</th>
                                <th scope="col">Téléphone</th>
                                <th scope="col">Spécialité</th>
                                <th scope="col">Photo</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                    <?php foreach ($formateurs as $index => $formateur): ?>
                        <tr class="text-center">
                            <td><?= $formateur['matricule'] ?></td>
                            <td><?= $formateur['nomForm'] ?></td>
                            <td><?= $formateur['prenomForm'] ?></td>
                            <td><?= $formateur['email'] ?></td>
                            <td><?= $formateur['telephone'] ?></td>
                            <td><?= $formateur['specialite'] ?></td>
                            <td>
                                <?php if ($formateur['photo']): ?>
                                    <img src="<?= 'uploads/' . $formateur['photo'] ?>" alt="Formateur" style="width: 50px; height: 50px; object-fit: cover;" />
                                <?php endif; ?>
                            </td>
                            <td>
                                <a class="btn btn-warning btn-edit me-2" href="traiteForm.php?matricule=<?=$formateur['matricule']?>">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="formateur.php?delete=<?= $formateur['matricule'] ?>" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                    </table>
                    <a class="nav-link text-dark" href="inscription.php">
                                        <i class="fas fa-tools me-2 text-primary"></i>inscrivez un Formateur
                                    </a>
                </div>
            </div>
        </div>

       
    </div>

    <!-- Scripts de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function conformDelete(){
            return confirm("voulez-vous vraiment supprimez cet utilisateur?")
        }

    </script>
</body>
</html>
