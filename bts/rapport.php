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
    <title>Rapports</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="fontawesome/css/all.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .sidebar {
    background-color: rgb(111, 235, 239); 
}


.accordion-button {
    background-color: rgb(111, 235, 239); 
    color: #004085; 
}


.accordion-button:not(.collapsed) {
    background-color: #8de1e3; 
    color: #004085; 
}

.accordion-button::after {
    filter: brightness(0) invert(1); 
}


.accordion-button:focus {
    border-color: #80bfff; 
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
     <header class="text-white p-3">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center search-bar">
                <input type="text" class="form-control me-2" placeholder="Recherche..." aria-label="Recherche">
            </div>
            <div class="d-flex align-items-center">
                <span><?php echo $nom . ' ' . $prenom; ?></span>
               
            </div>
        </div>
    </header>

    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar flex-shrink-0 p-3 bg-light">
            <h4 class="text-center mb-4" style="color:rgb(67, 211, 247);">
                <a href="acceuil.php" style="text-decoration: none; color: inherit;">ADMINISTRATEUR</a>
            </h4>

            <div class="d-flex justify-content-center mb-3">
                <img src="images/laperle.png" alt="User" class="rounded-circle" style="width: 60px; height: 60px;">
            </div>

            <div class="accordion" id="accordionExample">
                <!-- Gestion des Apprenants -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#apprenants" aria-expanded="false" aria-controls="apprenants">
                            <i class="fas fa-user-graduate me-2"></i>Gestion des Apprenants
                        </button>
                    </h2>
                    <div id="apprenants" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <ul class="nav flex-column">
                               
                                <li class="nav-item">
                                    <a class="nav-link text-dark" href="apprenant.php">
                                        <i class="fas fa-list me-2"></i>Listes Apprenants
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Gestion des Formateurs -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingTwo">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#enseignants" aria-expanded="false" aria-controls="enseignants">
                            <i class="fas fa-chalkboard-teacher me-2"></i>Gestion des Formateurs
                        </button>
                    </h2>
                    <div id="enseignants" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link text-primary active" href="inscription.php" id="ajoutFormateur"><i class="fas fa-plus me-2"></i>Ajout Formateur</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-dark" href="formateur.php">
                                        <i class="fas fa-list me-2"></i>Listes Formateurs
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Gestion de l'Assiduité -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingThree">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#assiduite" aria-expanded="false" aria-controls="assiduite">
                            <i class="fas fa-user-check me-2"></i>Gestion de l'Assiduité
                        </button>
                    </h2>
                    <div id="assiduite" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <ul class="nav flex-column">
                                
                                <li class="nav-item">
                                    <a class="nav-link text-dark" href="assiduite.php">
                                        <i class="fas fa-list me-2"></i>Listes Assiduité
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Gestion des Cours -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingFour">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#cours" aria-expanded="false" aria-controls="cours">
                            <i class="fas fa-book me-2"></i>Gestion des Cours
                        </button>
                    </h2>
                    <div id="cours" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <ul class="nav flex-column">
                                
                                <li class="nav-item">
                                    <a class="nav-link text-dark" href="cours.php">
                                        <i class="fas fa-list me-2"></i>Listes Cours
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-dark" href="emploiTemps.php">
                                        <i class="fas fa-calendar me-2"></i>Emploi du Temps
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Gestion de la Comptabilité -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingFive">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#comptabilite" aria-expanded="false" aria-controls="comptabilite">
                            <i class="fas fa-money-bill-wave me-2"></i>Gestion de la Comptabilité
                        </button>
                    </h2>
                    <div id="comptabilite" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <ul class="nav flex-column">
                               
                                <li class="nav-item">
                                    <a class="nav-link text-dark" href="comptabilite.php">
                                        <i class="fas fa-list me-2"></i>Listes Paiements
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Rapport -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingSix">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#rapport" aria-expanded="false" aria-controls="rapport">
                            <i class="fas fa-file-alt me-2"></i>Rapport
                        </button>
                    </h2>
                    <div id="rapport" class="accordion-collapse collapse show" aria-labelledby="headingSix" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link text-primary active" href="rapport.php" id="optionsRapports"><i class="fas fa-cogs me-2"></i>Options de Rapports</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-dark" href="rapport.php">
                                        <i class="fas fa-file-alt me-2"></i>Affichage des Rapports
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Paramètres -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingSeven">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#parametres" aria-expanded="false" aria-controls="parametres">
                            <i class="fas fa-cogs me-2"></i>Paramètres
                        </button>
                    </h2>
                    <div id="parametres" class="accordion-collapse collapse" aria-labelledby="headingSeven" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link text-primary active" href="option.php" id="optionsConfiguration"><i class="fas fa-cogs me-2"></i>Options de Configuration</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-dark" href="util.php">
                                        <i class="fas fa-users me-2"></i>Gestion des Utilisateurs
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-dark" href="sauvegarde.php">
                                        <i class="fas fa-database me-2"></i>Sauvegarde des Données
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-dark" href="appli.php">
                                        <i class="fas fa-tools me-2"></i>Paramètres de l'Application
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
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
