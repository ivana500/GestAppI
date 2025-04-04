<?php
session_start();  
if (!isset($_SESSION['nom']) || !isset($_SESSION['prenom'])) {
   
    header("Location: ../connexion.php");
    exit();
}

$nom = $_SESSION['nom'];
$prenom = $_SESSION['prenom'];

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'formateur') {
    header("Location: ../index.php");  
    exit();
}
?>

<?php

require_once '../Fonctions/db_connection.php';

$conn = getConnection();


$sqlCours = "SELECT * FROM Cours";
$coursResult = $conn->query($sqlCours);
$cours = [];
if ($coursResult->num_rows > 0) {
    while ($row = $coursResult->fetch_assoc()) {
        $cours[] = $row;
    }
}

$sql = "SELECT A.idE, A.heureDebut, A.heureFin, A.jour, C.titreC
        FROM emploiTemps A
        JOIN refer R ON A.idE = R.idE
        JOIN Cours C ON R.idC = C.idC";

$result = $conn->query($sql);

if (!$result) {
    die("Erreur SQL : " . $conn->error);
}

$emploi = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $emploi[] = $row;
    }
} else {
    echo "Aucun emploi de temps trouvé dans la base de données.<br>";
}
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
          

        <!-- Main Content -->
        <div class="content flex-grow-1 p-5">
            <h1 class="text-center text-bold text-primary">Emploi du Temps</h1>

            <div class="card border-primary mb-3 rounded-3">
                <div class="card-header d-flex justify-content-between align-items-center bg-secondary-subtle text-primary rounded-3">
                    <h3 class="mb-0"><i class="fas fa-calendar me-2"></i>Emploi du Temps</h3>
                </div>
                <div class="card-body shadow">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered rounded-3 align-middle mt-4">
                            <thead class="table-success">
                                <tr class="text-center fw-bold">
                                    <th scope="col">ID</th>
                                    <th scope="col">Cours</th>
                                    <th scope="col">Heure de Début</th>
                                    <th scope="col">Heure de Fin</th>
                                    <th scope="col">Jour</th>
                                   
                                </tr>
                            </thead>
                            <tbody>
    <?php
    if (isset($emploi) && is_array($emploi)) {
        foreach ($emploi as $index => $emploiItem) {
            ?>
            <tr class="text-center">
                <td><?= $index + 1 ?></td>
                <td><?= $emploiItem['titreC'] ?></td>
                <td><?= $emploiItem['heureDebut'] ?></td>
                <td><?= $emploiItem['heureFin'] ?></td>
                <td><?= $emploiItem['jour'] ?></td>
              
            </tr>
            <?php
        }
    } else {
        echo "Aucun emploi de temps disponible.";
    }
    ?>
</tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
