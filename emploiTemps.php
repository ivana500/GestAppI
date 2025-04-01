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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cours'], $_POST['heureDebut'], $_POST['heureFin'], $_POST['jour'])) {
    $cours = $_POST['cours'];
    $hd = $_POST['heureDebut'];
    $hf = $_POST['heureFin'];
    $jour = $_POST['jour'];

    $sqlCheck = "SELECT * FROM emploiTemps WHERE heureDebut = ? AND heureFin = ? AND jour = ?";
    $stmt = $conn->prepare($sqlCheck);
    $stmt->bind_param("sss", $hd, $hf, $jour);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $sqlInsert = "INSERT INTO emploiTemps (heureDebut, heureFin, jour) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sqlInsert);
        $stmt->bind_param("sss", $hd, $hf, $jour);

        if ($stmt->execute()) {
            $idE = $stmt->insert_id;  

            $sqlReferInsert = "INSERT INTO refer (idE, idC) VALUES (?, ?)";
            $stmtRefer = $conn->prepare($sqlReferInsert);
            $stmtRefer->bind_param("is", $idE, $cours);

            if ($stmtRefer->execute()) {
                $_SESSION['success_message'] = "Emploi de temps ajouté avec succès!";
            } else {
                $_SESSION['error_message'] = "Erreur lors de l'association du cours avec l'emploi de temps.";
            }

        } else {
            $_SESSION['error_message'] = "Erreur lors de l'ajout de l'emploi de temps.";
        }
    } else {
        $_SESSION['error_message'] = "Cet emploi de temps existe déjà.";
    }

    header('Location: emploiTemps.php');
    exit;
}

$sqlCours = "SELECT * FROM Cours";
$coursResult = $conn->query($sqlCours);
$cours = [];
if ($coursResult->num_rows > 0) {
    while ($row = $coursResult->fetch_assoc()) {
        $cours[] = $row;
    }
}

$sql = "SELECT A.idE, A.heureDebut, A.heureFin, A.jour, C.titreC
        FROM emploiTemps A
        JOIN refer R ON A.idE = R.idE
        JOIN Cours C ON R.idC = C.idC";

$result = $conn->query($sql);

if (!$result) {
    die("Erreur SQL : " . $conn->error);
}

$emploi = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $emploi[] = $row;
    }
} else {
    echo "Aucun emploi de temps trouvé dans la base de données.<br>";
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
        <!-- Main Content -->
        <div class="card-header d-flex justify-content-between align-items-center bg-secondary-subtle text-primary rounded-3">
              
        <button class="btn btn-success rounded-5 shadow" data-bs-toggle="modal" data-bs-target="#addEmploiModal">
                        <i class="fas fa-plus m-lg-1"></i>Ajouter un Emploi
                    </button>
                        </div>
                        <div class="container my-5">
                    <h1 class="text-center text-bold text-dark">Emploi de Temps </h1>
                    
                       
                        <div class="card-body  ">
                            <div class="table-responsive ">
                                <table class="table table-striped table-hover table-bordered rounded-3 align-middle mt-4 ">
                                    <thead class="y">
                                <tr class="text-center fw-bold">
                                    <th scope="col">ID</th>
                                    <th scope="col">Cours</th>
                                    <th scope="col">Heure de Début</th>
                                    <th scope="col">Heure de Fin</th>
                                    <th scope="col">Jour</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
    <?php
    if (isset($emploi) && is_array($emploi)) {
        foreach ($emploi as $index => $emploiItem) {
            ?>
            <tr class="text-center">
                <td><?= $index + 1 ?></td>
                <td><?= $emploiItem['titreC'] ?></td>
                <td><?= $emploiItem['heureDebut'] ?></td>
                <td><?= $emploiItem['heureFin'] ?></td>
                <td><?= $emploiItem['jour'] ?></td>
                <td>
                    <a href="emploiTemps.php?delete=<?= $emploiItem['idE'] ?>" class="btn btn-danger btn-sm">Supprimer</a>
                </td>
            </tr>
            <?php
        }
    } else {
        echo "Aucun emploi de temps disponible.";
    }
    ?>
</tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal pour ajouter un emploi du temps -->
    <div class="modal fade" id="addEmploiModal" tabindex="-1" aria-labelledby="addEmploiModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-3 shadow">
                <div class="modal-header bg-dark-subtle">
                    <h5 class="modal-title text-success" id="addEmploiModalLabel"><i class="fas fa-calendar me-2"></i>Ajouter un Emploi du Temps</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <form action="emploiTemps.php" method="POST">
    <select class="form-select" id="cours" name="cours" required>
        <option value="">Sélectionner un Cours</option>
        <?php foreach ($cours as $cours_item): ?>
            <option value="<?= $cours_item['idC'] ?>"><?= $cours_item['titreC'] ?></option>
        <?php endforeach; ?>
    </select>
    <div class="mb-3">
        <label for="heureDebut" class="form-label">Heure de Début</label>
        <input type="time" class="form-control" id="heureDebut" name="heureDebut" required>
    </div>
    <div class="mb-3">
        <label for="heureFin" class="form-label">Heure de Fin</label>
        <input type="time" class="form-control" id="heureFin" name="heureFin" required>
    </div>
    <div class="mb-3">
        <label for="jour" class="form-label">Jour</label>
        <input type="text" class="form-control" id="jour" name="jour" required>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
        <button type="submit" class="btn btn-success">Ajouter Emploi du Temps</button>
    </div>
</form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>

$(document).on('click', '.btn-edit', function() {
        var id = $(this).attr('id');
        $.ajax({
            url: 'TraitAss.php',
            type: 'POST',
            data: { 
                id: id,
                action: 'editEnseig'
            },
            dataType: 'json',           
            success: function(data) {
                $('#id').val(data.id);
                $('#apprenant').val(data.apprenant);
                $('#cours').val(data.cours);
                $('#present').val(data.present);
                $('#date').val(data.date);
                updateBtn= document.getElementById('updateButton');
                savebtn = document.getElementById('saveButton');
                updateBtn.classList.remove('d-none');
                savebtn.classList.add('d-none');
                $('#addAssiduiteModal').modal('show');
            },
            error: function() {
                alert("Une erreur est survenue lors de la récupération des détails de l'enseignat.");
                console.error("Une erreur est survenue lors de la récupération des détails de l'enseignat..");
            }
        });
    });
    
    $('#openaddAssiduiteModal').click(function() {
        $('#resetButton').click();
        updateBtn= document.getElementById('updateButton');
        savebtn = document.getElementById('saveButton');
        savebtn.classList.remove('d-none');
        updateBtn.classList.add('d-none');
    });
    
    $('#updateButton').click(function() {
        var id =  $('#id').val();
        var apprenant= $('#apprenant').val();
        var cours= $('#cours').val();
        var present= $('#present').val();
        var date= $('#date').val();
        $.ajax({
            url: 'TraitAss.php',
            type: 'POST',
            data: { 
                id: id,
                apprenant: apprenant,
                cours: cours,
                present: present,
                date: date,
                action: 'updateEnseig'
            },
            dataType: 'json',           
            success: function(data) {
                $('#addAssiduiteModal').modal('hide');
                location.reload();
            },
            error: function() {
                alert("Une erreur est survenue lors de la modification des détails de l'enseignat.");
                console.error("Une erreur est survenue lors de la récupération des détails de l'enseignat..");
            }
        });
    });

    $(document).on('click', '.btn-delete', function() {
        var matricule = $(this).attr('id');
        $.ajax({
    url: 'TraitAss.php',
    type: 'POST',
    data: { 
        id: id,
        action: 'deleteEnseig'
    },
    success: function() {
        location.reload(); 
    },
    error: function() { 
        alert("Erreur de suppression");
    }
});
    });
</script>

</body>

</html>
