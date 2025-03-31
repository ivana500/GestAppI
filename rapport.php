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
$assiduiteData = [
    ['name' => 'Jean Dupont', 'presence' => 20, 'absence' => 5],
    ['name' => 'Marie Lemoine', 'presence' => 18, 'absence' => 7],
    ['name' => 'Pierre Martin', 'presence' => 22, 'absence' => 3],
];

$performancesData = [
    ['name' => 'Jean Dupont', 'performance' => 85],
    ['name' => 'Marie Lemoine', 'performance' => 75],
    ['name' => 'Pierre Martin', 'performance' => 90],
];

$financierData = [
    ['name' => 'Jean Dupont', 'montant' => 150],
    ['name' => 'Marie Lemoine', 'montant' => 200],
    ['name' => 'Pierre Martin', 'montant' => 120],
];

// Set the report type and data based on the selection
$rapportType = isset($_POST['rapportType']) ? $_POST['rapportType'] : '';
$reportData = null;

if ($rapportType === 'assiduite') {
    $reportData = $assiduiteData;
} elseif ($rapportType === 'performances') {
    $reportData = $performancesData;
} elseif ($rapportType === 'financier') {
    $reportData = $financierData;
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
        <img src="images/stephan.png" alt="User" class="rounded-circle" style="width: 60px; height: 60px;">
    </div>

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

        <!-- Render Report Data -->
        <?php if ($reportData): ?>
            <?php if ($rapportType === 'assiduite'): ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Présences</th>
                            <th>Absences</th>
                            <th>% Présence</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reportData as $item): ?>
                            <tr>
                                <td><?php echo $item['name']; ?></td>
                                <td><?php echo $item['presence']; ?></td>
                                <td><?php echo $item['absence']; ?></td>
                                <td><?php echo number_format(($item['presence'] / ($item['presence'] + $item['absence'])) * 100, 2); ?>%</td>
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
                        <?php foreach ($reportData as $item): ?>
                            <tr>
                                <td><?php echo $item['name']; ?></td>
                                <td><?php echo $item['performance']; ?>%</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- Chart for performances -->
                <canvas id="performanceChart"></canvas>
                <script>
                    const performanceData = <?php echo json_encode($performancesData); ?>;
                    const ctx = document.getElementById('performanceChart').getContext('2d');
                    const performanceChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: performanceData.map(item => item.name),
                            datasets: [{
                                label: 'Performance (%)',
                                data: performanceData.map(item => item.performance),
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
                                <td><?php echo $item['name']; ?></td>
                                <td><?php echo $item['montant']; ?> FCFA</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        <?php elseif ($rapportType): ?>
            <p>Aucun rapport à afficher. Veuillez sélectionner un rapport.</p>
        <?php endif; ?>
    </div>
</body>
</html>
