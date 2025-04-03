<?php

require_once 'Fonctions/db_connection.php';
require_once 'Fonctions/fonctions.php';

if (isset($_GET['matricule'])) {
    $code = $_GET['matricule'];
    $conn = getConnection();
    $sql = "SELECT * FROM formateur WHERE matricule = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $code);  
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $formateurs = $result->fetch_assoc();  
    } else {
        echo "Aucun formateur trouvé avec ce matricule.";
        exit;
    }



    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modifier'])) {
        $nomAp = $_POST['nomAp'];
        $prenomAp = $_POST['prenomAp'];
        $email = $_POST['email'];
        $telephone = $_POST['telephone'];
        $specialite = $_POST['specialite'];
        $code = $_POST['matricule'];   

        $photo = $formateurs['photo'];  

        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $targetDir = "uploads/";
            $fileName = basename($_FILES['photo']['name']);
            $targetFile = $targetDir . $fileName;
            move_uploaded_file($_FILES['photo']['tmp_name'], $targetFile);
            $photo = $fileName; 
        }

       
        $updateSql = "UPDATE formateur SET nomForm = ?, prenomForm = ?, email = ?, telephone = ?, specialite = ?, photo = ? WHERE matricule = ?";
        $stmt = $conn->prepare($updateSql);
        $stmt->bind_param('sssssss', $nomAp, $prenomAp, $email, $telephone, $specialite, $photo, $code);

        if ($stmt->execute()) {
            header("Location: formateur.php");  
            exit;
        } else {
            echo "Erreur lors de la mise à jour du formateur.";
        }

        $stmt->close();
    }
    $conn->close();
} else {
    header("Location: formateur.php");
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
    <h2>Modifier le Formateur</h2>

    <form action="" method="POST" enctype="multipart/form-data">
    <div class="mb-3">
            <label for="matricule" class="form-label">Matricule</label>
            <input type="text" class="form-control" id="matricule" name="matricule" value="<?= $formateurs['matricule'] ?>" required>
        </div>
        
        <div class="mb-3">
            <label for="nomAp" class="form-label">Nom</label>
            <input type="text" class="form-control" id="nomAp" name="nomAp" value="<?= $formateurs['nomForm'] ?>" required>
        </div>

        <div class="mb-3">
            <label for="prenomAp" class="form-label">Prénom</label>
            <input type="text" class="form-control" id="prenomAp" name="prenomAp" value="<?= $formateurs['prenomForm'] ?>" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= $formateurs['email'] ?>" required>
        </div>

        <div class="mb-3">
            <label for="telephone" class="form-label">Téléphone</label>
            <input type="tel" class="form-control" id="telephone" name="telephone" value="<?= $formateurs['telephone'] ?>" required>
        </div>

        <div class="mb-3">
            <label for="specialite" class="form-label">Specialite</label>
            <input type="text" class="form-control" id="specialite" name="specialite" value="<?= $formateurs['specialite'] ?>" required>
        </div>

        <div class="mb-3">
            <label for="photo" class="form-label">Photo</label>
            <input type="file" class="form-control" id="photo" name="photo">
            <small>Actuelle: <img src="uploads/<?= $formateurs['photo'] ?>" alt="Photo" style="width: 50px; height: 50px;"></small>
        </div>

        <button type="submit" name="modifier" class="btn btn-primary">Sauvegarder les modifications</button>
    </form>
</div>

<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
