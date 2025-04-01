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

$conn = getConnection();

$searchQuery = "";
if (isset($_POST['search'])) {
    $searchQuery = $_POST['search'];
}

$sql = "SELECT A.idAp, A.idC, A.date, A.present, P.nomAp AS nomAp, P.prenomAp AS prenomAp, C.titreC
        FROM suivieCours A
        JOIN Apprenant P ON A.idAp = P.code
        JOIN Cours C ON A.idC = C.idC";

if ($searchQuery != "") {
    $sql .= " WHERE (P.nomAp LIKE ? OR P.prenomAp LIKE ? OR C.titreC LIKE ? OR A.date LIKE ?)";
}

$stmt = $conn->prepare($sql);

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

$stmt->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['apprenant'], $_POST['cours'], $_POST['present'], $_POST['date'])) {
    $apprenantId = $_POST['apprenant'];
    $coursId = $_POST['cours'];
    $present = $_POST['present'];
    $date = $_POST['date'];

    $sqlCheck = "SELECT * FROM suivieCours WHERE idAp = ? AND idC = ? AND date = ?";
    $stmt = $conn->prepare($sqlCheck);
    $stmt->bind_param("sss", $apprenantId, $coursId, $date);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $sqlInsert = "INSERT INTO suivieCours (idAp, idC, date, present) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sqlInsert);
        $stmt->bind_param("ssss", $apprenantId, $coursId, $date, $present);

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

$sqlApprenants = "SELECT * FROM Apprenant";
$apprenantResult = $conn->query($sqlApprenants);
$apprenants = [];
if ($apprenantResult->num_rows > 0) {
    while ($row = $apprenantResult->fetch_assoc()) {
        $apprenants[] = $row;
    }
}

$sqlCours = "SELECT * FROM Cours";
$coursResult = $conn->query($sqlCours);
$cours = [];
if ($coursResult->num_rows > 0) {
    while ($row = $coursResult->fetch_assoc()) {
        $cours[] = $row;
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
    </style>
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

    <div class="d-flex">
        
        <div class="sidebar flex-shrink-0 p-3 ">
            

            
    <h4 class="text-center mb-4 y" >
        <a href="acceuil.php" style="text-decoration: none; color: white;">ADMINISTRATEUR</a>
    </h4>

    <div class="d-flex justify-content-center mb-3">
    <a class="nav-link" href="dasboard.php">
    <img src="images/stephan.png" alt="User" class="rounded-circle" style="width: 80px; height: 80px;">
</a>          </div>

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
<body class="<?php echo $themeClass; ?>">    
    
        
            <div class="card-header d-flex justify-content-between align-items-center bg-secondary-subtle text-primary rounded-3">
              
                <button class="btn btn-success rounded-5 shadow" data-bs-toggle="modal" data-bs-target="#addAssiduiteModal">
                    <i class="fas fa-plus m-lg-1"></i>Ajouter une Assiduité
                </button>
            </div>
            <div class="container my-5">
        <h1 class="text-center text-bold text-dark">Suivi de l'Assiduité </h1>
        
           
            <div class="card-body  ">
                <div class="table-responsive ">
                    <table class="table table-striped table-hover table-bordered rounded-3 align-middle mt-4 ">
                        <thead class="y">
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
        <?php if (count($assiduites) > 0): ?>
            <?php foreach ($assiduites as $index => $assiduite): ?>
                <tr class="text-center">
                    <td><?= $index + 1 ?></td>
                    <td><?= $assiduite['nomAp'] . ' ' . $assiduite['prenomAp'] ?></td>
                    <td><?= $assiduite['titreC'] ?></td>
                    <td><?= $assiduite['present'] ? 'Oui' : 'Non' ?></td>
                    <td><?= $assiduite['date'] ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" class="text-center">Aucun résultat trouvé</td>
            </tr>
        <?php endif; ?>
    </tbody>
                    </table>
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
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function() {
            const [idAp, idC] = this.id.split('-').slice(1);  
            const formData = new FormData();
            formData.append('action', 'deleteAssiduite');
            formData.append('idAp', idAp);
            formData.append('idC', idC);

           
            fetch('assiduite.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Assiduité supprimée avec succès');
                    location.reload();  
                } else {
                    alert('Erreur lors de la suppression de l\'assiduité');
                }
            });
        });
    });
</script>
</body>
</html>
