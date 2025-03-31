<?php
session_start();

if (!isset($_SESSION['nom']) || !isset($_SESSION['prenom']) || !isset($_SESSION['email']) || !isset($_SESSION['telephone']) || !isset($_SESSION['dateIns'])) {
    header("Location: index.php");
    exit();
}

$nom = $_SESSION['nom'];
$prenom = $_SESSION['prenom'];
$email = $_SESSION['email'];
$telephone = $_SESSION['telephone'];
$date = $_SESSION['dateIns'];

$photo = isset($_SESSION['photo']) && !empty($_SESSION['photo']) ? $_SERVER['localhost'] . '/BTS/bts/uploads/' . $_SESSION['photo'] : 'uploads/default-photo.PNG';
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
        <a href="acceuil.php" style="text-decoration: none; color: blue;">Apprenant</a>
    </h4>

    <div class="d-flex justify-content-center mb-3 ">
        <img src="" alt="User" class="rounded-circle" style="width: 60px; height: 60px;">
    </div>

    <div class="nav flex-column">
        <li class="nav-item mb-3">
            <a class="nav-link text-dark " href="profilA.php">
                <i class="fas fa-list me-2 bg-light"></i>Profil
            </a>
        </li>
    
        <li class="nav-item mb-3">
            <a class="nav-link text-dark" href="emploiT.php">
                <i class="fas fa-calendar me-2"></i>Emploi du Temps
            </a>
        </li>
        <li class="nav-item mb-3">
            <a class="nav-link text-dark" href="rapportA.php">
                <i class="fas fa-file-alt me-2"></i>Affichage des Rapports
            </a>
        </li>
       
    </div>
</div>
        
</head>
          
<body>
    <div class="container my-5">
        <h1 class="text-center text-bold">Profil de l'Apprenant</h1>
        <div class="card border-primary mb-3 rounded-3 shadow">
            <div class="card-header bg-secondary-subtle text-success rounded-3 d-flex justify-content-center align-items-center">
                <h3 class="mb-0"><i class="fas fa-user-graduate me-2"></i> Informations de l'Apprenant</h3>
            </div>
            <div class="card-body shadow">
                <div class="row">
                    <!-- Photo de l'apprenant -->
                    <div class="col-md-4 text-center">
                        <img
                            src="<?php echo htmlspecialchars($photo); ?>"
                            alt="Apprenant"
                            class="img-fluid rounded-circle"
                            style="width: 150px; height: 150px; object-fit: cover;"
                        />
                    </div>

                  
                    <div class="col-md-8">
                    <p class="text-muted">Noms et Prenoms:    <h4 class="text-success"><span><?php echo htmlspecialchars($nom . ' ' . $prenom); ?></span></h4></p>
                        <p class="text-muted">Email: <?php echo htmlspecialchars($email); ?></p>
                        <hr />
                        <div>
                            <h5>Téléphone</h5>
                            <p><?php echo htmlspecialchars($telephone); ?></p>
                        </div>
                       
                        <div>
                            <h5>Date d'inscription</h5>
                            <p><?php echo htmlspecialchars($date); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Button to modify the profile -->
        <div class="text-center">
            <button
                class="btn btn-warning rounded-5 shadow"
                onclick="alert('Modifier fonctionnalité non implémentée')"
            >
                <i class="fas fa-edit me-2"></i>Modifier Profil
            </button>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
