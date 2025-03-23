<!DOCTYPE html>
<?php
session_start();
$nom = $_SESSION['nom'];
$prenom = $_SESSION['prenom'];
?>
<?php
include_once './Fonctions/db_connection.php';
require_once 'Fonctions/fonctions.php';
$a = 0;

if (isset($_GET['codeFiliere'])) {
    $a = 1;

        $code = $_GET['codeFiliere'];
         $sql = "SELECT * FROM filiere WHERE code_filiere ='$code'";
         $conn = getConnection();
         $result = $conn->query($sql);
         $conn->close();
         if($result){
            $ligne = $result->fetch_assoc();
         }
        

        }

        if (isset($_POST['update'])) {
            $code = $_POST['code_filiere'];
            $intitule = $_POST['intitule'];
            $groupe = $_POST['groupe'];
        
        $sql = "UPDATE filiere SET intitule='$intitule', groupe='$groupe' WHERE code_filiere = '$code'";
        $conn = getConnection();
        $result = $conn->query($sql);
        $conn->close();
        if($result){
           redirection("listFiliere.php");
        }else{
            echo 'error';
        }

        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   
           
            $action = isset($_POST['action']) ? $_POST['action'] : '';
        
            switch ($action) {

                case 'deleteFiliere':
                    $code = $_POST['code_filiere'];
                         $del = "DELETE FROM filiere WHERE code_filiere = '$code'";
                         $con = getConnection();
                         $delet = $con->query($del);
                         
                         echo json_encode($delet); 
            break;
            
            
        }
    }

?>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - Gestion des Étudiants</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="fontawesome/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="d-flex bg-white">
<div class="sidebar flex-shrink-0 p-3 bg-light" >
    <h4 class="text-center mb-4" style="color: #439af7;">
        <a href="acceuil.php" style="text-decoration: none; color: inherit;">ADMINISTRATEUR</a>
    </h4>
    <div class="accordion" id="accordionExample">
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#etudiants" aria-expanded="false" aria-controls="etudiants">
                    <i class="fas fa-user-graduate me-2"></i>Gestion des Étudiants
                </button>
            </h2>
            <div id="etudiants" class="accordion-collapse collapse " aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link text-dark" href="addEtud.php" id="ajoutEtudiant"><i class="fas fa-plus me-2"></i>Ajout Étudiant</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-dark" href="listEtud.php">
                                <i class="fas fa-users me-2"></i> Listes Étudiants
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingTwo">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#enseignants" aria-expanded="false" aria-controls="enseignants">
                    <i class="fas fa-chalkboard-teacher me-2"></i>Gestion des Enseignants
                </button>
            </h2>
            <div id="enseignants" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link text-dark" href="#"><i class="fas fa-plus me-2"></i>Ajout Enseignant</a>
                        </li>                        
                        <li class="nav-item">
                            <a class="nav-link text-dark" href="ListEnseignant.php">
                                <i class="fas fa-chalkboard-teacher me-2"></i> Listes Enseignants
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingThree">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#filieres" aria-expanded="false" aria-controls="filieres">
                    <i class="fas fa-book-open me-2"></i>Gestion des Filières
                </button>
            </h2>
            <div id="filieres" class="accordion-collapse collapse show" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link text-primary active" href="addFiliere.php"><i class="fas fa-plus me-2"></i>Ajout Filière</a>
                        </li>                        
                        <li class="nav-item">
                            <a class="nav-link text-dark" href="listFiliere.php">
                                <i class="fas fa-book me-2"></i> Listes Filières
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingFour">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#matieres" aria-expanded="false" aria-controls="matieres">
                    <i class="fas fa-book me-2"></i>Gestion des Matières
                </button>
            </h2>
            <div id="matieres" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link text-dark" href="#"><i class="fas fa-plus me-2"></i>Ajout Matière</a>
                        </li>                        
                        <li class="nav-item">
                            <a class="nav-link text-dark" href="listMatiere.php">
                                <i class="fas fa-folder-open me-2"></i> Listes Matières
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingFive">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#cours" aria-expanded="false" aria-controls="cours">
                    <i class="fas fa-clipboard-list me-2"></i>Gestion des Cours
                </button>
            </h2>
            <div id="cours" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link text-dark" href="#"><i class="fas fa-plus me-2"></i>Ajout Cours</a>
                        <li class="nav-item">
                            <a class="nav-link text-dark" href="#"><i class="fas fa-user-tag me-2"></i>Affectation Cours</a>
                        </li>
                       <li class="nav-item">
                            <a class="nav-link text-dark" href="listCours.php">
                                <i class="fas fa-chalkboard me-2"></i> Listes Cours
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingSix">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#notes" aria-expanded="false" aria-controls="notes">
                    <i class="fas fa-pencil-alt me-2"></i>Saisie des Notes
                </button>
            </h2>
            <div id="notes" class="accordion-collapse collapse" aria-labelledby="headingSix" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link text-dark" href="saisiNotes.php"><i class="fas fa-file-alt me-2"></i>Saisie Notes CC</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-dark" href="saisiNotes.php"><i class="fas fa-file-alt me-2"></i>Saisie Notes Examen</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingSeven">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#consultationNotes" aria-expanded="false" aria-controls="consultationNotes">
                    <i class="fas fa-eye me-2"></i>Consultation des Notes
                </button>
            </h2>
            <div id="consultationNotes" class="accordion-collapse collapse" aria-labelledby="headingSeven" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link text-dark" href="#"><i class="fas fa-eye me-2"></i>Affichage Notes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-dark" href="#"><i class="fas fa-file-download me-2"></i>Génération Relevés</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingEight">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#auth" aria-expanded="false" aria-controls="auth">
                    <i class="fas fa-lock me-2"></i>Authentification et Autorisation
                </button>
            </h2>
            <div id="auth" class="accordion-collapse collapse" aria-labelledby="headingEight" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link text-dark" href="#"><i class="fas fa-sign-in-alt me-2"></i>Connexion</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-dark" href="#"><i class="fas fa-user-shield me-2"></i>Gestion des Rôles</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingNine">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#statistiques" aria-expanded="false" aria-controls="statistiques">
                    <i class="fas fa-chart-line me-2"></i>Statistiques et Rapports
                </button>
            </h2>
            <div id="statistiques" class="accordion-collapse collapse" aria-labelledby="headingNine" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link text-dark" href="#"><i class="fas fa-chart-bar me-2"></i>Rapports Performances</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-dark" href="#"><i class="fas fa-chart-pie me-2"></i>Statistiques Matières</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="content flex-grow-1 p-4">
    <header class="bg-primary text-white text-center p-3">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <h1 class="h4">Bienvenue sur le Tableau de Bord</h1>
            <div class="d-flex align-items-center">
                <input type="text" class="form-control me-2" placeholder="Recherche..." aria-label="Recherche">
                <div class="position-relative me-3">
                    <button class="btn btn-primary position-relative ">
                        <i class="fa-solid fa-bell"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">5</span>
                    </button>
                </div>
                <div class="d-flex align-items-center">
                    <img src="images/laperle.png" alt="User" class="rounded-circle me-2" style="width: 30px; height: 30px;">
                    <span><?php echo $nom.' '.$prenom?></span>
                </div>
            </div>
        </div>
    </header>
    <main>
    <div class="container my-5">
        <h1 class="text-center text-bold"><?php if($a == 1) echo 'Modifier'; else echo 'Ajouter'?> une Filiere</h1>
        <div class="card border-primary mb-4 rounded-3">
            <div class="card-header d-flex justify-content-between align-items-center bg-secondary-subtle text-success rounded-3">
                <h3 class="mb-0"><i class="fas fa-times-plus mb-1"></i><?php if($a == 1) echo 'Modifier'; else echo 'Ajouter'?> une Filiere<i class="fas fa-plus-circle"></i></h3>             
            </div>
            <div class="card-body shadow">
            <form id="ajoutEtudiantForm" method="post">
                    <div class="mb-3">
                        <label for="matricule" class="form-label">
                            <i class="fas fa-id-card"></i> Code Filiere
                        </label>
                        <input type="text" name="code_filiere" class="form-control" id="code_filiere" value="<?php if($a == 1) echo $ligne['code_filiere']?>" placeholder="Entrez le code de la filiere" required maxlength="6" pattern="[A-Za-z0-9]{1,6}" title="Le code doit contenir 1 à 6 caractères alphanumériques.">
                       
                    </div>
                    <div class="mb-3">
                        <label for="nom" class="form-label">
                            <i class="fas fa-user"></i> Intitule
                        </label>
                        <input type="text" name="intitule'"class="form-control" id="intitule" value="<?php if($a == 1) echo $ligne['intitule']?>"placeholder="Entrez l'intitule" required pattern="[A-Za-zÀ-ÿ '-]+" title="Veuillez entrer un intitule valide.">
                    </div>
                    <div class="mb-3">
                                <label for="groupeFiliere" class="form-label">
                                    <i class="fas fa-tags"></i> Groupe
                                </label>
                                <select name='groupe' class="form-select" id="groupeFiliere" required>
                                    <option value="INDUSTRIELLE" <?php if($a==1 && $ligne['groupe'] == 'INDUSTRIELLE') echo 'SELECTED'?>>INDUSTRIELLE</option>
                                    <option value="COMMERCIALE" <?php if($a==1 && $ligne['groupe'] == 'COMMERCIALE') echo 'SELECTED'?>>COMMERCIALE</option>
                                </select>
                            </div>
                   
                  
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times-circle me-2"></i> Annuler
                        </button>
                        <button type="submit" name="enreg" class="btn btn-success <?php if($a == 1) echo 'd-none'?>">
                            <i  class="fas fa-check-circle me-2"></i> Enregistrer
                        </button>
                        <button type="submit" name="update" class="btn btn-warning <?php if($a == 0) echo 'd-none'?>">
                            <i  class="fas fa-check-circle me-2"></i> Modifier
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    </main>
    <footer class="text-center p-2">
        <p>&copy; 2023 Institut Supérieur La Perle</p>
    </footer>
</div>


    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="js/bootstrap.min.js" type="text/javascript"></script>
    <script src="js/jquery-3.7.1.min.js" type="text/javascript"></script>
    <script src="js/popper.min.js" type="text/javascript"></script>
</body>
</html>