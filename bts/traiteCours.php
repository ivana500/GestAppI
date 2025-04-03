<?php
require_once 'Fonctions/db_connection.php';
require_once 'Fonctions/fonctions.php';

if (isset($_GET['idC'])) {
    $idC = $_GET['idC'];
    $conn = getConnection();

    $sql = "SELECT * FROM cours WHERE idC = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $idC);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $cours = $result->fetch_assoc();  
    } else {
        echo "Aucun cours trouvé.";
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modifier'])) {
        $titreC = $_POST['titre'];
        $typeC = $_POST['type'];
        $description = $_POST['description'];
        $dateD = $_POST['dateD'];
        $dateF = $_POST['dateF'];

        $updateSql = "UPDATE cours SET titreC = ?, type = ?, description = ?, dateD = ?, dateF = ? WHERE idC = ?";
        $stmt = $conn->prepare($updateSql);
        $stmt->bind_param('ssssss', $titreC, $typeC, $description, $dateD, $dateF, $idC);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Cours mis à jour avec succès!";
            header("Location: cours.php");  
            exit;
        } else {
            echo "Erreur lors de la mise à jour du cours.";
        }
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: cours.php");
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
        <label for="titre" class="form-label">Titre du Cours</label>
        <input type="text" class="form-control" id="titre" name="titre" value="<?= $cours['titreC']; ?>" required>
    </div>
    <div class="mb-3">
        <label for="type" class="form-label">Type de Cours</label>
        <input type="text" class="form-control" id="type" name="type" value="<?= $cours['type']; ?>" required>
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" name="description" required><?= $cours['description']; ?></textarea>
    </div>
    <div class="mb-3">
        <label for="dateD" class="form-label">Date de début</label>
        <input class="form-control" id="dateD" name="dateD" type="date" value="<?= $cours['dateD']; ?>" required>
    </div>
    <div class="mb-3">
        <label for="dateF" class="form-label">Date de fin</label>
        <input class="form-control" id="dateF" name="dateF" type="date" value="<?= $cours['dateF']; ?>" required>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
        <button type="submit" name="modifier" class="btn btn-success">Modifier Cours</button>
    </div>
</form>

</div>

<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
