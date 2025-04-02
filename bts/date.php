<?php
session_start();  
if (!isset($_SESSION['nom']) || !isset($_SESSION['prenom'])) {
   
    header("Location: index.php");
    exit();
}

$nom = $_SESSION['nom'];
$prenom = $_SESSION['prenom'];
?>


<?php
require_once 'Fonctions/db_connection.php';
require_once 'Fonctions/fonctions.php';

$con = getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['titre'], $_POST['type'], $_POST['dateDebut'], $_POST['dateFin'], $_POST['description'])) {
    $titre = $_POST['titre'];
    $type = $_POST['type'];
    $description = $_POST['description'];  
    $dateDebut = $_POST['dateDebut'];
    $dateFin = $_POST['dateFin'];

    $sqlFormateur = "SELECT matricule, nomForm, prenomForm FROM Formateur WHERE specialite = ?";
    $stmt = $con->prepare($sqlFormateur);
    $stmt->bind_param("s", $titre);  
    $stmt->execute();
    $result = $stmt->get_result();
    

    if ($formateur = $result->fetch_assoc()) {
        $formateurId = $formateur['matricule'];

        // Insérer dans la table Cours
        $sql = "INSERT INTO Cours (idC, idForm, titreC, description, type) VALUES (UUID(), ?, ?, ?, ?)";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ssss", $formateurId, $titre, $description, $type);
        
        

        if ($stmt->execute()) {
            $coursId = $con->insert_id;  // Récupérer l'ID du cours inséré

            // Insérer dans la table suivieCours
            $sqlSuivieCours = "INSERT INTO suivieCours (idC, dateD, dateF) VALUES (?, ?, ?)";
            $stmtSuivie = $con->prepare($sqlSuivieCours);
            $stmtSuivie->bind_param("sss", $coursId, $dateDebut, $dateFin);
 

            
            if ($stmtSuivie->execute()) {
                echo "Cours ajouté avec succès.";
            } else {
                echo "Erreur lors de l'ajout du cours dans la table suivieCours : " . $stmtSuivie->error;
            }

            $stmtSuivie->close();
        } else {
            echo "Erreur lors de l'ajout du cours dans la table Cours : " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Formateur non trouvé pour ce type.";
    }
}
$sqlTypes = "SELECT DISTINCT type FROM Cours";
$resultTypes = $con->query($sqlTypes);
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
     <!-- Navbar -->
     <header class="text-white p-3 bg-light">
     <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
                <div class="input-group">
                    <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
                    <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>
                </div>
            </form>
            <div class="d-flex align-items-center text-light text-bold">
                <span><?php echo $nom . ' ' . $prenom; ?></span>
                
            </div>
        </div>
    </header>

    <div class="d-flex">
        
        <div class="sidebar flex-shrink-0 p-3 bg-light">
            

            
    <h4 class="text-center mb-4 y" >
        <a href="acceuil.php" style="text-decoration: none; color: blue;">ADMINISTRATEUR</a>
    </h4>

    <div class="d-flex justify-content-center mb-3 ">
        <img src="images/laperle.png" alt="User" class="rounded-circle" style="width: 60px; height: 60px;">
    </div>

    <div class="nav flex-column">
        <li class="nav-item mb-3">
            <a class="nav-link text-dark " href="apprenant.php">
                <i class="fas fa-list me-2 bg-light"></i>Listes Apprenants
            </a>
        </li>
        
        <li class="nav-item mb-3">
            <a class="nav-link text-dark" href="formateur.php">
                <i class="fas fa-list me-2"></i>Listes Formateurs
            </a>
        </li>
        <li class="nav-item mb-3">
            <a class="nav-link text-dark" href="assiduite.php">
                <i class="fas fa-list me-2"></i>Listes Assiduité
            </a>
        </li>
        <li class="nav-item mb-3">
            <a class="nav-link text-dark" href="cours.php">
                <i class="fas fa-list me-2"></i>Listes Cours
            </a>
        </li>
        <li class="nav-item mb-3">
            <a class="nav-link text-dark" href="emploiTemps.php">
                <i class="fas fa-calendar me-2"></i>Emploi du Temps
            </a>
        </li>
        <li class="nav-item mb-3">
            <a class="nav-link text-dark" href="comptabilite.php">
                <i class="fas fa-list me-2"></i>Listes Paiements
            </a>
        </li>
        <li class="nav-item mb-3">
            <a class="nav-link text-dark" href="rapport.php">
                <i class="fas fa-file-alt me-2"></i>Affichage des Rapports
            </a>
        </li>
        <li class="nav-item mb-3">
            <a class="nav-link text-dark" href="util.php">
                <i class="fas fa-users me-2"></i>Gestion des Utilisateurs
            </a>
        </li>
        <li class="nav-item mb-3">
            <a class="nav-link text-dark" href="sauvegarde.php">
                <i class="fas fa-database me-2"></i>Sauvegarde des Données
            </a>
        </li>
        <li class="nav-item mb-3">
            <a class="nav-link text-dark" href="appli.php">
                <i class="fas fa-tools me-2"></i>Paramètres de l'Application
            </a>
        </li>
    </div>
</div>
        
</head>
<body>
        <div class="container my-5">
            <h1 class="text-center text-bold">Liste des Cours</h1>
            <div class="card border-primary mb-3 rounded-3">
                <div class="card-header d-flex justify-content-between align-items-center bg-secondary-subtle text-success rounded-3">
                    <h3 class="mb-0"><i class="fas fa-book me-2"></i>Cours</h3>
                    <button class="btn btn-success rounded-5 shadow" data-bs-toggle="modal" data-bs-target="#addCoursModal">
                        <i class="fas fa-plus m-lg-1"></i>Ajouter un Cours
                    </button>
                </div>
                <div class="card-body shadow">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered rounded-3 align-middle mt-4">
                            <thead class="table-primary">
                                <tr class="text-center fw-bold">
                                    <th scope="col">Type</th>
                                    <th scope="col">Titre</th>
                                    <th scope="col">Formateur</th>
                                    <th scope="col">Date de Début</th>
                                    <th scope="col">Date de Fin</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
    <?php while ($typeRow = $resultTypes->fetch_assoc()) {
        $currentType = $typeRow['type'];
        
        $sqlCours = "
            SELECT C.titreC, F.nomForm, F.prenomForm, F.specialite, S.dateD, S.dateF
            FROM Cours C
            JOIN Formateur F ON C.idForm = F.matricule
            JOIN suivieCours S ON C.idC = S.idC
            WHERE C.type = ?
        ";
        $stmtCours = $con->prepare($sqlCours);
        $stmtCours->bind_param("s", $currentType);
        $stmtCours->execute();
        $resultCours = $stmtCours->get_result();

        $titles = [];
        $formateurs = [];
        $dates = [];

        while ($row = $resultCours->fetch_assoc()) {
            $titles[] = $row['titreC'];
            $formateurs[$row['specialite']][] = $row['nomForm'] . ' ' . $row['prenomForm'];
            $dateD[] = ['dateD' => $row['dateD'] ];
            $dateF[] = ['dateF' => $row['dateF'] ];
        }

        ?>
        <tr class="text-center">
            <td rowspan="<?= count($formateurs) ?>"> <?= $currentType ?> </td>
            <td>
                <?= implode('<br>', $titles) ?>
            </td>
            <td>
                <?php 
                
                foreach ($formateurs as $specialite => $listFormateurs) {
                    echo "<strong>" . $specialite . "</strong>: <br>";
                    echo implode('<br>', $listFormateurs) . "<br><br>";
                }
                ?>
            </td>
            <td>
                <?php 
                foreach ($dateD as $dated) {
                    echo  $dated['dateD'] . "<br><br>";
                }
                ?>
            </td>
            <td>
                <?php 
                foreach ($dateF as $datef) {
                    echo  $datef['dateF'] . "<br><br>";
                }
                ?>
            </td>
        </tr>
    <?php 
        } 
        if (count($titles) == 0) {
            echo "<tr><td colspan='4' class='text-center'>Aucun cours trouvé pour ce type.</td></tr>";
        }
        $stmtCours->close();
    
    ?>
</tbody>
                        </table>
                    </div>
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
                            <label for="dateDebut" class="form-label">Date de Début</label>
                            <input type="date" class="form-control" id="dateDebut" name="dateDebut" required>
                        </div>
                        <div class="mb-3">
                            <label for="dateFin" class="form-label">Date de Fin</label>
                            <input type="date" class="form-control" id="dateFin" name="dateFin" required>
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
