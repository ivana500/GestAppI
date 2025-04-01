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
require_once 'Fonctions/fonctions.php';

$searchQuery = "";
if (isset($_POST['search'])) {
    $searchQuery = $_POST['search'];
}

$conn = getConnection();
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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


$sqlStatistiquesApprenants = "SELECT COUNT(*) as total, MONTH(dateIns) as mois FROM apprenant GROUP BY MONTH(dateIns)";
$resultStats = $conn->query($sqlStatistiquesApprenants);
$statistiquesApprenants = [];
while ($row = $resultStats->fetch_assoc()) {
    $statistiquesApprenants[] = $row;
}

$sqlCoursFormateur = "SELECT f.nomForm, COUNT(c.idC) as totalCours 
                      FROM Cours c
                      JOIN Formateur f ON c.idForm = f.matricule
                      GROUP BY f.matricule";
$resultCoursFormateur = $conn->query($sqlCoursFormateur);
$coursParFormateur = [];
while ($row = $resultCoursFormateur->fetch_assoc()) {
    $coursParFormateur[] = $row;
}
$conn->close();

$conn = getConnection();

// Apprenants Inscrits
$sqlApprenants = "SELECT COUNT(*) AS total FROM apprenant";
$resultApprenants = $conn->query($sqlApprenants);
$apprenantsCount = $resultApprenants->fetch_assoc()['total'];

// Formateurs Actifs
$sqlFormateurs = "SELECT COUNT(*) AS total FROM Formateur";
$resultFormateurs = $conn->query($sqlFormateurs);
$formateursCount = $resultFormateurs->fetch_assoc()['total'];

// Cours Planifiés
$sqlCours = "SELECT COUNT(*) AS total FROM Cours";
$resultCours = $conn->query($sqlCours);
$coursCount = $resultCours->fetch_assoc()['total'];

// Paiements En Cours
$sqlPaiements = "SELECT SUM(montant) AS total FROM inscription ";
$resultPaiements = $conn->query($sqlPaiements);
$paiementsCount = $resultPaiements->fetch_assoc()['total'];

closeConnection($conn);
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Administrateur</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="fontawesome/css/all.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
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
        .nav-link img {
    pointer-events: auto;
}
    </style>
</head>
<body class="<?php echo $themeClass; ?>">    
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

    <!-- Sidebar -->
    <div class="d-flex">
        <div class="sidebar flex-shrink-0 p-3">
            <h4 class="text-center mb-4 y">
                <a href="acceuil.php" style="text-decoration: none; color: white;">ADMINISTRATEUR</a>
            </h4>
            <div class="d-flex justify-content-center mb-3">
            <a class="nav-link" href="dasboard.php">
    <img src="images/stephan.png" alt="User" class="rounded-circle" style="width: 80px; height: 80px;">
</a>            </div>
            <div class="nav flex-column">
                <li class="nav-item mb-3"><a class="nav-link text-light" href="apprenant.php"><i class="fas fa-list me-2"></i>Listes Apprenants</a></li>
                <li class="nav-item mb-3"><a class="nav-link text-light" href="formateur.php"><i class="fas fa-list me-2"></i>Listes Formateurs</a></li>
                <li class="nav-item mb-3"><a class="nav-link text-light" href="assiduite.php"><i class="fas fa-list me-2"></i>Listes Assiduité</a></li>
                <li class="nav-item mb-3"><a class="nav-link text-light" href="cours.php"><i class="fas fa-list me-2"></i>Listes Cours</a></li>
                <li class="nav-item mb-3"><a class="nav-link text-light" href="emploiTemps.php"><i class="fas fa-calendar me-2"></i>Emploi du Temps</a></li>
                <li class="nav-item mb-3"><a class="nav-link text-light" href="comptabilite.php"><i class="fas fa-list me-2"></i>Listes Paiements</a></li>
                <li class="nav-item mb-3"><a class="nav-link text-light" href="rapport.php"><i class="fas fa-file-alt me-2"></i>Affichage des Rapports</a></li>
                <li class="nav-item mb-3"><a class="nav-link text-light" href="util.php"><i class="fas fa-users me-2"></i>Gestion des Utilisateurs</a></li>
                <li class="nav-item mb-3"><a class="nav-link text-light" href="sauvegarde.php"><i class="fas fa-database me-2"></i>Sauvegarde des Données</a></li>
                <li class="nav-item mb-3"><a class="nav-link text-light" href="appli.php"><i class="fas fa-tools me-2"></i>Paramètres de l'Application</a></li>
            </div>
        </div>

        <div class="content">
        <body class="<?php echo $themeClass; ?>">    
        <div class="container-fluid px-4">
                    <h1 class="mt-4">Dashboard</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                    <div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card bg-primary text-white mb-4">
            <div class="card-body">Apprenants Inscrits</div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <a class="small text-white stretched-link" href="#">Voir Détails</a>
                <div class="small text-white"><?= $apprenantsCount ?></div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card bg-warning text-white mb-4">
            <div class="card-body">Formateurs Actifs</div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <a class="small text-white stretched-link" href="#">Voir Détails</a>
                <div class="small text-white"><?= $formateursCount ?></div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card bg-success text-white mb-4">
            <div class="card-body">Cours Planifiés</div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <a class="small text-white stretched-link" href="#">Voir Détails</a>
                <div class="small text-white"><?= $coursCount ?></div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card bg-danger text-white mb-4">
            <div class="card-body">Total des Paiements</div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <a class="small text-white stretched-link" href="#">Voir Détails</a>
                <div class="small text-white"><?= $paiementsCount ?></div>
            </div>
        </div>
    </div>
</div>
                    <!-- Charts -->
<div class="row">
    <div class="col-xl-6">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-chart-area me-1"></i> Statistiques des Apprenants
            </div>
            <div class="card-body">
                <canvas id="myAreaChart" width="100%" height="40"></canvas>
            </div>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-chart-bar me-1"></i> Cours par Formateur
            </div>
            <div class="card-body">
                <canvas id="myBarChart" width="100%" height="40"></canvas>
            </div>
        </div>
    </div>
</div>
                    <!-- DataTable -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i> Liste des Apprenants
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr class="text-center">
                                        <th>Id</th>
                                        <th>Nom</th>
                                        <th>Prénom</th>
                                        <th>Email</th>
                                        <th>Téléphone</th>
                                        <th>Date d'Inscription</th>
                                        <th>Cours</th>
                                        <th>Photo</th>
                                        
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr class="text-center">
                                        <th>Id</th>
                                        <th>Nom</th>
                                        <th>Prénom</th>
                                        <th>Email</th>
                                        <th>Téléphone</th>
                                        <th>Date d'Inscription</th>
                                        <th>Cours</th>
                                        <th>Photo</th>
                                        
                                    </tr>
                                </tfoot>
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
                               
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>

            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">&copy; 2023 Gestion Apprenants</div>
                        <div>
                            <a href="#">Privacy Policy</a> &middot; <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="assets/demo/chart-area-demo.js"></script>
    <script src="assets/demo/chart-bar-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
    var ctx = document.getElementById('myAreaChart').getContext('2d');
    var myAreaChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [<?php foreach ($statistiquesApprenants as $stat) { echo '"' . $stat['mois'] . '",'; } ?>],
            datasets: [{
                label: 'Nombre d\'apprenants',
                data: [<?php foreach ($statistiquesApprenants as $stat) { echo $stat['total'] . ','; } ?>],
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Mois'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Nombre d\'apprenants'
                    }
                }
            }
        }
    });
</script>
<script>
    var ctx = document.getElementById('myBarChart').getContext('2d');
    var myBarChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [<?php foreach ($coursParFormateur as $cours) { echo '"' . $cours['nomForm'] . '",'; } ?>],
            datasets: [{
                label: 'Nombre de cours',
                data: [<?php foreach ($coursParFormateur as $cours) { echo $cours['totalCours'] . ','; } ?>],
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Formateurs'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Nombre de cours'
                    }
                }
            }
        }
    });
</script>

</body>
</html>
