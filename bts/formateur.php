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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nomForm'], $_POST['prenomForm'], $_POST['email'], $_POST['telephone'], $_POST['specialite'])) {
    
    $nomForm = $_POST['nomForm'];
    $prenomForm = $_POST['prenomForm'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];
    $specialite = $_POST['specialite'];
    $photo = '';
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["photo"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if ($imageFileType == "jpg" || $imageFileType == "jpeg" || $imageFileType == "png" || $imageFileType == "gif") {
            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
                $photo = basename($_FILES["photo"]["name"]);
            }
        }
    }

    $conn = getConnection();

    $stmt = $conn->prepare("INSERT INTO Formateur (matricule, nomForm, prenomForm, email, telephone, specialite, photo) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $matricule, $nomForm, $prenomForm, $email, $telephone, $specialite, $photo);

    if ($stmt->execute()) {
        
        header('Location: formateur.php');
        exit;
    } else {
        echo "Erreur : " . $stmt->error;
    }

    $stmt->close();
    closeConnection($conn);
}

if (isset($_GET['delete'])) {
    $index = $_GET['delete'];

    $conn = getConnection();
    $stmt = $conn->prepare("DELETE FROM Formateur WHERE matricule = ?");
    $stmt->bind_param("s", $_GET['delete']);
    $stmt->execute();
    $stmt->close();
    closeConnection($conn);
    header('Location: formateur.php');
    exit;
}
$conn = getConnection();
$query = "SELECT * FROM Formateur";
$result = $conn->query($query);
$formateurs = [];
while ($row = $result->fetch_assoc()) {
    $formateurs[] = $row;
}
closeConnection($conn);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Formateurs</title>
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
       
        <div class="sidebar flex-shrink-0 p-3 bg-light">
            <h4 class="text-center mb-4" style="color:rgb(67, 211, 247);">
                <a href="acceuil.php" style="text-decoration: none; color: inherit;">ADMINISTRATEUR</a>
            </h4>

            <div class="d-flex justify-content-center mb-3">
                <img src="images/laperle.png" alt="User" class="rounded-circle" style="width: 60px; height: 60px;">
            </div>

            <div class="accordion" id="accordionExample">
             
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
        <h1 class="text-center text-bold">Liste des Formateurs</h1>
        <div class="card border-primary mb-3 rounded-3">
            <div class="card-header d-flex justify-content-between align-items-center bg-secondary-subtle text-success rounded-3">
                <h3 class="mb-0"><i class="fas fa-chalkboard-teacher me-2"></i>Formateurs</h3>
                <button class="btn btn-success rounded-5 shadow" data-bs-toggle="modal" data-bs-target="#addFormateurModal">
                    <i class="fas fa-plus m-lg-1"></i>Ajouter
                </button>
            </div>
            <div class="card-body shadow">
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-bordered rounded-3 align-middle mt-4">
                        <thead class="table-primary">
                            <tr class="text-center fw-bold">
                                <th scope="col">ID</th>
                                <th scope="col">Nom</th>
                                <th scope="col">Prénom</th>
                                <th scope="col">Email</th>
                                <th scope="col">Téléphone</th>
                                <th scope="col">Spécialité</th>
                                <th scope="col">Photo</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($formateurs as $index => $formateur): ?>
                                <tr class="text-center">
                                    <td><?= $formateur['matricule'] ?></td>
                                    <td><?= $formateur['nomForm'] ?></td>
                                    <td><?= $formateur['prenomForm'] ?></td>
                                    <td><?= $formateur['email'] ?></td>
                                    <td><?= $formateur['telephone'] ?></td>
                                    <td><?= $formateur['specialite'] ?></td>
                                    <td>
                                        <?php if ($formateur['photo']): ?>
                                            <img src="<?= 'uploads/' . $formateur['photo'] ?>" alt="Formateur" style="width: 50px; height: 50px; object-fit: cover;" />
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                    <a class="btn btn-warning btn-edit me-2" href="traiteApp.php?code=<?=$apprenant['code']?>">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                        <a href="formateur.php?delete=<?= $formateur['matricule'] ?>" class="btn btn-danger btn-sm">Supprimer</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal pour ajouter un formateur -->
        <div class="modal fade" id="addFormateurModal" tabindex="-1" aria-labelledby="addFormateurModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-3 shadow">
                    <div class="modal-header bg-dark-subtle">
                        <h5 class="modal-title text-success" id="addFormateurModalLabel"><i class="fas fa-chalkboard-teacher me-2"></i>Ajouter un Formateur</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="index.php" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="nomForm" class="form-label">Nom</label>
                                <input type="text" class="form-control" id="nomForm" name="nomForm" required />
                            </div>
                            <div class="mb-3">
                                <label for="prenomForm" class="form-label">Prénom</label>
                                <input type="text" class="form-control" id="prenomForm" name="prenomForm" required />
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required />
                            </div>
                            <div class="mb-3">
                                <label for="telephone" class="form-label">Téléphone</label>
                                <input type="text" class="form-control" id="telephone" name="telephone" />
                            </div>
                            <div class="mb-3">
                                <label for="specialite" class="form-label">Spécialité</label>
                                <input type="text" class="form-control" id="specialite" name="specialite" required />
                            </div>
                            <div class="mb-3">
                                <label for="photo" class="form-label">Photo</label>
                                <input type="file" class="form-control" id="photo" name="photo" />
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

    <!-- Scripts de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
