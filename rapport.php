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

// Connexion à la base de données
$conn = getConnection();

// Initialiser les variables
$rapportType = "";
$reportData = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['rapportType']) && !empty($_POST['rapportType'])) {
        $rapportType = $_POST['rapportType'];
    }
}

// Cas pour le rapport "assiduité"
if ($rapportType === 'assiduite') {
    $searchQuery = "";
    if (isset($_POST['search'])) {
        $searchQuery = $_POST['search'];
    }

    $sql = "SELECT A.idAp, A.idC, A.date, A.present, 
                P.nomAp AS nomAp, P.prenomAp AS prenomAp, 
                C.titreC,
                I.montant AS montant
            FROM suivieCours A
            JOIN Apprenant P ON A.idAp = P.code
            JOIN Cours C ON A.idC = C.idC
            LEFT JOIN inscription I ON A.idAp = I.idAp";

    if ($searchQuery != "") {
        $sql .= " WHERE (P.nomAp LIKE ? OR P.prenomAp LIKE ? OR C.titreC LIKE ? OR A.date LIKE ?)";
    }

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Erreur dans la préparation de la requête SQL.");
    }

    if ($searchQuery != "") {
        $searchTerm = "%" . $searchQuery . "%";
        $stmt->bind_param("ssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $assiduites = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $assiduites[] = $row;
        }
    }

    if (isset($stmt)) {
        $stmt->close();
    }

    $reportData = $assiduites;
}

// Cas pour le rapport "performance"
if ($rapportType === 'performances') {
    $sql = "SELECT P.nomAp, P.prenomAp, COUNT(A.present) AS presentCount, COUNT(C.idC) AS totalCours
            FROM suivieCours A
            JOIN Apprenant P ON A.idAp = P.code
            JOIN Cours C ON A.idC = C.idC
            GROUP BY P.code";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Erreur dans la préparation de la requête SQL.");
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $performances = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $totalCours = $row['totalCours'];
            $presentCount = $row['presentCount'];

            $performance = $totalCours > 0 ? ($presentCount / $totalCours) * 100 : 0;
            $row['performance'] = $performance;

            $performances[] = $row;
        }
    }

    if (isset($stmt)) {
        $stmt->close();
    }

    $reportData = $performances;
}

// Cas pour le rapport "financier"
if ($rapportType === 'financier') {
    $sql = "SELECT P.nomAp, P.prenomAp, I.montant
            FROM Apprenant P
            LEFT JOIN inscription I ON P.code = I.idAp";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Erreur dans la préparation de la requête SQL.");
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $financierData = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $financierData[] = $row;
        }
    }

    if (isset($stmt)) {
        $stmt->close();
    }

    $reportData = $financierData;
}

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
    <link href="css/styles.css" rel="stylesheet" />
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
        
        <div class="sidebar flex-shrink-0 p-3 ">
            

            
    <h4 class="text-center mb-4 y" >
        <a href="acceuil.php" style="text-decoration: none; color: white;">ADMINISTRATEUR</a>
    </h4>

    <div class="d-flex justify-content-center mb-3  ">
    <a class="nav-link" href="dasboard.php">
    <img src="images/stephan.png" alt="User" class="rounded-circle" style="width: 80px; height: 80px;">
</a>      </div>

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
    <div class="container my-5">
        <h1 class="text-center text-bold">Options de Rapport</h1>

        <!-- Report Selection Form -->
        <form method="POST" action="rapport.php">
    <div class="mb-4">
        <label for="rapportType" class="form-label">Choisir un Rapport</label>
        <select class="form-select" id="rapportType" name="rapportType">
            <option value="">Sélectionner un type de rapport</option>
            <option value="assiduite" <?php echo $rapportType === 'assiduite' ? 'selected' : ''; ?>>Assiduité</option>
            <option value="performances" <?php echo $rapportType === 'performances' ? 'selected' : ''; ?>>Performances</option>
            <option value="financier" <?php echo $rapportType === 'financier' ? 'selected' : ''; ?>>Financier</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary mb-4">Générer Rapport</button>
</form>

<?php if ($rapportType): ?>
    <?php if ($rapportType === 'assiduite'): ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Cours</th>
                    <th>Date</th>
                    <th>Présence</th>
                    <th>Montant</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reportData as $assiduite): ?>
                    <tr>
                        <td><?php echo $assiduite['nomAp']; ?></td>
                        <td><?php echo $assiduite['prenomAp']; ?></td>
                        <td><?php echo $assiduite['titreC']; ?></td>
                        <td><?php echo $assiduite['date']; ?></td>
                        <td><?php echo $assiduite['present'] == 1 ? 'Présent' : 'Absent'; ?></td>
                        <td><?php echo $assiduite['montant']; ?> FCFA</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php elseif ($rapportType === 'performances'): ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Performance (%)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reportData as $performance): ?>
                    <tr>
                        <td><?php echo $performance['nomAp']; ?></td>
                        <td><?php echo number_format($performance['performance'], 2); ?>%</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Chart for performances -->
        <canvas id="performanceChart"></canvas>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const performanceData = <?php echo json_encode($reportData); ?>;
            const ctx = document.getElementById('performanceChart').getContext('2d');
            const performanceChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: performanceData.map(item => item.nomAp), // Récupère les noms des apprenants
                    datasets: [{
                        label: 'Performance (%)',
                        data: performanceData.map(item => item.performance), // Récupère la performance de chaque apprenant
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            enabled: true,
                        }
                    }
                }
            });
        </script>
    <?php elseif ($rapportType === 'financier'): ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Montant (FCFA)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reportData as $item): ?>
                    <tr>
                        <td><?php echo $item['nomAp']; ?></td>
                        <td><?php echo $item['montant']; ?> FCFA</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
<?php else: ?>
    <p>Aucun rapport à afficher. Veuillez sélectionner un rapport.</p>
<?php endif; ?>


    </div>
</body>
</html>
