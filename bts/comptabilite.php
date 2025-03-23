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

$sqlApprenants = "SELECT * FROM Apprenant";
$apprenantResult = $conn->query($sqlApprenants);


$apprenants = [];
while ($row = $apprenantResult->fetch_assoc()) {
    $apprenants[$row['code']] = $row;  
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['apprenantId'], $_POST['montant'], $_POST['typeTransaction'], $_POST['date'])) {
    $apprenantId = $_POST['apprenantId'];
    $montant = $_POST['montant'];
    $typeTransaction = $_POST['typeTransaction'];
    $date = $_POST['date'];


    $sqlCheckApprenant = "SELECT * FROM Apprenant WHERE code = ?";
    $stmt = $conn->prepare($sqlCheckApprenant);
    $stmt->bind_param("s", $apprenantId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      
        $sql = "INSERT INTO Comptabilite (idAp, montant, typeTrans, date) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("siss", $apprenantId, $montant, $typeTransaction, $date);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Transaction ajoutée avec succès!";
        } else {
            $_SESSION['error_message'] = "Erreur lors de l'ajout de la transaction.";
        }
    } else {
        $_SESSION['error_message'] = "L'apprenant n'existe pas.";
    }

   
    header('Location: index.php');
    exit;
}


if (isset($_GET['delete'])) {
    $index = $_GET['delete'];


    $sql = "SELECT numero FROM Comptabilite LIMIT 1 OFFSET ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $index);
    $stmt->execute();
    $stmt->bind_result($transactionId);
    $stmt->fetch();
    $stmt->close();

    
    $sqlDelete = "DELETE FROM Comptabilite WHERE numero = ?";
    $stmt = $conn->prepare($sqlDelete);
    $stmt->bind_param("i", $transactionId);
    $stmt->execute();

    $stmt->close();
    $conn->close();

    
    header('Location: index.php');
    exit;
}


$sql = "SELECT C.numero, C.idAp, C.montant, C.typeTrans, C.date, A.nomAp, A.prenomAp
        FROM Comptabilite C
        JOIN Apprenant A ON C.idAp = A.code";
$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Transactions</title>
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
        <h1 class="text-center text-bold">Liste des Transactions</h1>
        <div class="card border-primary mb-3 rounded-3">
            <div class="card-header d-flex justify-content-between align-items-center bg-secondary-subtle text-success rounded-3">
                <h3 class="mb-0"><i class="fas fa-wallet me-2"></i>Transactions</h3>
            </div>
            <div class="card-body shadow">
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-bordered rounded-3 align-middle mt-4">
                        <thead class="table-primary">
                            <tr class="text-center fw-bold">
                                <th scope="col">ID</th>
                                <th scope="col">Apprenant</th>
                                <th scope="col">Montant</th>
                                <th scope="col">Type de Transaction</th>
                                <th scope="col">Date</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result->num_rows > 0): ?>
                                <?php while ($transaction = $result->fetch_assoc()): ?>
                                    <tr class="text-center">
                                        <td><?= $transaction['numero'] ?></td>
                                        <td>
                                            <?= $transaction['nomAp'] . ' ' . $transaction['prenomAp'] ?>
                                        </td>
                                        <td><?= $transaction['montant'] ?>€</td>
                                        <td><?= $transaction['typeTrans'] === 'paiement' ? 'Paiement' : 'Remboursement' ?></td>
                                        <td><?= $transaction['date'] ?></td>
                                        <a class="btn btn-warning btn-edit me-2" href="traiteApp.php?code=<?=$apprenant['code']?>">
        <i class="fas fa-edit"></i>
    </a>
                                        <td>
                                            <a href="index.php?delete=<?= $transaction['numero'] ?>" class="btn btn-danger btn-sm ms-2">Supprimer</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center">Aucune transaction trouvée.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
