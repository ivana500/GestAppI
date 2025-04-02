<?php
session_start();  
if (!isset($_SESSION['nom']) || !isset($_SESSION['prenom'])) {
   
    header("Location: index.php");
    exit();
}

$nom = $_SESSION['nom'];
$prenom = $_SESSION['prenom'];
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../fontawesome/css/all.css" rel="stylesheet">
    
    <style>
       
.sidebar {
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

.sidebar .nav-link:focus {
    outline: none;
    box-shadow: red; 
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
     
     <header class="text-white p-3 bg-light">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center search-bar">
                <input type="text" class="form-control me-2" placeholder="Recherche..." aria-label="Recherche">
            </div>
            <div class="d-flex align-items-center text-dark text-bold">
                <span><?php echo $nom . ' ' . $prenom; ?></span>
                
            </div>
        </div>
    </header>

    <div class="d-flex">
        
        <div class="sidebar flex-shrink-0 p-3 bg-light">
            

            
    <h4 class="text-center mb-4 y" >
        <a href="acceuil.php" style="text-decoration: none; color: blue;">Formateur</a>
    </h4>

    <div class="d-flex justify-content-center mb-3 ">
        <img src="" alt="User" class="rounded-circle" style="width: 60px; height: 60px;">
    </div>

    <div class="nav flex-column">
        <li class="nav-item mb-3">
            <a class="nav-link text-dark " href="profil.php">
                <i class="fas fa-list me-2 bg-light"></i>Profil
            </a>
        </li>
    
        <li class="nav-item mb-3">
            <a class="nav-link text-dark" href="emploi.php">
                <i class="fas fa-calendar me-2"></i>Emploi du Temps
            </a>
        </li>
        <li class="nav-item mb-3">
            <a class="nav-link text-dark" href="Rapport.php">
                <i class="fas fa-file-alt me-2"></i> Rapport
            </a>
        </li>
       
    </div>
</div>
        
</head>
          

<body>
    <div class="container my-5">
        <h1 class="text-center text-bold">Profil du Formateur</h1>
        
        <!-- Error message (if any) -->
        <?php if (isset($error) && $error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <div class="card border-primary mb-3 rounded-3 shadow">
            <div class="card-header bg-secondary-subtle text-success rounded-3 d-flex justify-content-center align-items-center">
                <h3 class="mb-0"><i class="fas fa-user-circle me-2"></i>Informations du Formateur</h3>
            </div>
            <div class="card-body shadow">
                <div class="row">
                    <!-- Photo du formateur -->
                    <div class="col-md-4 text-center">
                        <img
                            src="<?php echo htmlspecialchars($formateur['photo']); ?>"
                            alt="Formateur"
                            class="img-fluid rounded-circle"
                            style="width: 150px; height: 150px; object-fit: cover;"
                        />
                    </div>

                    <!-- Informations du formateur -->
                    <div class="col-md-8">
                        <h5>Noms et Prénoms</h5>
                        <h4 class="text-success"><?php echo htmlspecialchars($formateur['nomForm']) . ' ' . htmlspecialchars($formateur['prenomForm']); ?></h4>
                        <hr />
                        <div>
                            <h5>Email</h5>
                            <p><?php echo htmlspecialchars($formateur['email']); ?></p>
                        </div>
                        <div>
                            <h5>Téléphone</h5>
                            <p><?php echo htmlspecialchars($formateur['telephone']); ?></p>
                        </div>
                        <div>
                            <h5>Spécialité</h5>
                            <p><?php echo htmlspecialchars($formateur['specialite']); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
