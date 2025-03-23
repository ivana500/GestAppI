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

$conn = getConnection();


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['apprenant'], $_POST['cours'], $_POST['present'], $_POST['date'])) {
    $apprenantId = $_POST['apprenant'];
    $coursId = $_POST['cours'];
    $present = $_POST['present'];
    $date = $_POST['date'];

    $sqlCheck = "SELECT * FROM suivieCours WHERE idAp = ? AND idC = ? AND date = ?";
    $stmt = $conn->prepare($sqlCheck);
    $stmt->bind_param("sandra", $apprenantId, $coursId, $date);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
       
        $sqlInsert = "INSERT INTO suivieCours (idAp, idC, date, present) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sqlInsert);
        $stmt->bind_param("sand", $apprenantId, $coursId, $date, $present);
        
        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Assiduité ajoutée avec succès!";
        } else {
            $_SESSION['error_message'] = "Erreur lors de l'ajout de l'assiduité.";
        }
    } else {
        $_SESSION['error_message'] = "Cette assiduité existe déjà.";
    }

    header('Location: assiduite.php');
    exit;
}


$conn = getConnection();

$sqlApprenants = "SELECT * FROM Apprenant";
$apprenantResult = $conn->query($sqlApprenants);
$apprenants = [];
if ($apprenantResult->num_rows > 0) {
    while ($row = $apprenantResult->fetch_assoc()) {
        $apprenants[] = $row;
    }
}

$sqlApprenants = "SELECT * FROM Apprenant";
$apprenantResult = $conn->query($sqlApprenants);
$apprenants = [];
if ($apprenantResult->num_rows > 0) {
    while ($row = $apprenantResult->fetch_assoc()) {
        $apprenants[] = $row;
    }
}


$sql = "SELECT A.idAp, A.idC, A.date, A.present, P.nomAp AS nomAp, P.prenomAp AS prenomAp, C.titreC
        FROM suivieCours A
        JOIN Apprenant P ON A.idAp = P.code
        JOIN Cours C ON A.idC = C.idC";
$result = $conn->query($sql);

$assiduites = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $assiduites[] = $row;
    }
}


if (isset($_GET['delete'])) {
    $idAp = $_GET['apprenant'];
    $idC = $_GET['cours'];
    $date = $_GET['date'];

    $sqlDelete = "DELETE FROM Assiduite WHERE idAp = ? AND idC = ? AND date = ?";
    $stmt = $conn->prepare($sqlDelete);
    $stmt->bind_param("sis", $idAp, $idC, $date);
    $stmt->execute();

    $_SESSION['success_message'] = "Assiduité supprimée avec succès.";
    header('Location: assiduite.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suivi d'Assiduité</title>

    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="fontawesome/css/all.css" rel="stylesheet">
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
                    <div id="rapport" class="accordion-collapse collapse" aria-labelledby="headingSix" data-bs-parent="#accordionExample">
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
        <h1 class="text-center text-bold">Suivi de l'Assiduité</h1>
        <div class="card border-primary mb-3 rounded-3">
            <div class="card-header d-flex justify-content-between align-items-center bg-secondary-subtle text-success rounded-3">
                <h3 class="mb-0"><i class="fas fa-check-circle me-2"></i>Assiduités</h3>
                <button class="btn btn-success rounded-5 shadow" data-bs-toggle="modal" data-bs-target="#addAssiduiteModal">
                    <i class="fas fa-plus m-lg-1"></i>Ajouter une Assiduité
                </button>
            </div>
            <div class="card-body shadow">
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-bordered rounded-3 align-middle mt-4">
                        <thead class="table-primary">
                            <tr class="text-center fw-bold">
                                <th scope="col">ID</th>
                                <th scope="col">Apprenant</th>
                                <th scope="col">Cours</th>
                                <th scope="col">Présent</th>
                                <th scope="col">Date</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($assiduites as $index => $assiduite): ?>
                            <tr class="text-center">
                                <td><?= $index + 1 ?></td>
                                <td><?= $assiduite['apprenant_nom'] . ' ' . $assiduite['apprenant_prenom'] ?></td>
                                <td><?= $assiduite['nomCours'] ?></td>
                                <td><?= $assiduite['present'] ? 'Oui' : 'Non' ?></td>
                                <td><?= $assiduite['date'] ?></td>
                                <td>
                                    <a href="assiduite.php?delete=true&apprenant=<?= $assiduite['idAp'] ?>&cours=<?= $assiduite['idC'] ?>&date=<?= $assiduite['date'] ?>" class="btn btn-danger btn-sm ms-2">Supprimer</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="modal fade" id="addAssiduiteModal" tabindex="-1" aria-labelledby="addAssiduiteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-3 shadow">
                    <div class="modal-header bg-dark-subtle">
                        <h5 class="modal-title text-success" id="addAssiduiteModalLabel"><i class="fas fa-check-circle me-2"></i>Ajouter une Assiduité</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="POST">
                            <div class="mb-3">
                                <label for="apprenant" class="form-label">Apprenant</label>
                                <select class="form-select" id="apprenant" name="apprenant" required>
                                    <option value="">Sélectionner un Apprenant</option>
                                    <?php foreach ($apprenants as $apprenant): ?>
                                        <option value="<?= $apprenant['code'] ?>"><?= $apprenant['nomAp'] . ' ' . $apprenant['prenomAp'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="cours" class="form-label">Cours</label>
                                <select class="form-select" id="cours" name="cours" required>
                                    <option value="">Sélectionner un Cours</option>
                                    <?php foreach ($cours as $cours_item): ?>
                                        <option value="<?= $cours_item['idC'] ?>"><?= $cours_item['titreC'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="present" class="form-label">Présent</label>
                                <select class="form-select" id="present" name="present" required>
                                    <option value="1">Oui</option>
                                    <option value="0">Non</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="date" class="form-label">Date du Cours</label>
                                <input type="date" class="form-control" id="date" name="date" required />
                            </div>
                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                <button type="submit" class="btn btn-success">Enregistrer</button>
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
