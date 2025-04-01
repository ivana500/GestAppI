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

$sqlFormateur = "SELECT matricule, nomForm, prenomForm, email, telephone, specialite, login FROM Formateur";
$resultFormateur = $conn->query($sqlFormateur);

$sqlApprenant = "SELECT code, nomAp, prenomAp, email, telephone, dateIns, login FROM Apprenant";
$resultApprenant = $conn->query($sqlApprenant);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $typeUtilisateur = $_POST['type'];

    if ($typeUtilisateur == 'formateur') {
        $matricule = $_POST['matricule'];
        $nomForm = $_POST['nomForm'];
        $prenomForm = $_POST['prenomForm'];
        $email = $_POST['email'];
        $telephone = $_POST['telephone'];
        $specialite = $_POST['specialite'];
        $login = $_POST['login'];

        $stmt = $conn->prepare("INSERT INTO Formateur (matricule, nomForm, prenomForm, email, telephone, specialite, login) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $matricule, $nomForm, $prenomForm, $email, $telephone, $specialite, $login);
        $stmt->execute();

    } elseif ($typeUtilisateur == 'apprenant') {
        $code = $_POST['code'];
        $nomAp = $_POST['nomAp'];
        $prenomAp = $_POST['prenomAp'];
        $email = $_POST['email'];
        $telephone = $_POST['telephone'];
        $dateIns = $_POST['dateIns'];
        $login = $_POST['login'];

        $stmt = $conn->prepare("INSERT INTO Apprenant (code, nomAp, prenomAp, email, telephone, dateIns, login) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $code, $nomAp, $prenomAp, $email, $telephone, $dateIns, $login);
        $stmt->execute();
    }

    header("Location: util.php");
    exit; 
}

$search = isset($_GET['search']) ? $_GET['search'] : '';
$typeUtilisateur = isset($_GET['type']) ? $_GET['type'] : '';

$where = [];
$params = [];

$sql = "SELECT matricule, nomForm, prenomForm, email, telephone, specialite, login FROM Formateur
        UNION
        SELECT code, nomAp, prenomAp, email, telephone, dateIns, login FROM Apprenant";

if ($search != '') {
    $where[] = "(nomForm LIKE ? OR prenomForm LIKE ? OR email LIKE ?)";
    $where[] = "(nomAp LIKE ? OR prenomAp LIKE ? OR email LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($typeUtilisateur != '') {
    if ($typeUtilisateur == 'formateur') {
        $sql = "SELECT matricule, nomForm, prenomForm, email, telephone, specialite, login FROM Formateur";
    } elseif ($typeUtilisateur == 'apprenant') {
        $sql = "SELECT code, nomAp, prenomAp, email, telephone, dateIns, login FROM Apprenant";
    }
}

if (count($where) > 0) {
    $sql .= " WHERE " . implode(" OR ", $where);
}

$stmt = $conn->prepare($sql);

if (count($params) > 0) {
    $stmt->bind_param(str_repeat('s', count($params)), ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

$items_per_page = 10;
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;
$start_from = ($current_page - 1) * $items_per_page;


$sql_countFormateur = "SELECT COUNT(*) FROM Formateur";
$sql_countApprenant = "SELECT COUNT(*) FROM Apprenant";
$countResult = $conn->query($sql_countFormateur);
$row = $countResult->fetch_row();
$total_formateurs = $row[0];

$countResult = $conn->query($sql_countApprenant);
$row = $countResult->fetch_row();
$total_apprenants = $row[0];

$total_users = $total_formateurs + $total_apprenants;
$total_pages = ceil($total_users / $items_per_page);


$sql .= " LIMIT ?, ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $start_from, $items_per_page);
$stmt->execute();
$result = $stmt->get_result();

if (isset($_POST['update_role'])) {
    $id = $_POST['id']; 
    $new_role = $_POST['new_role'];

    if ($_POST['type'] == 'formateur') {
        $stmt = $conn->prepare("UPDATE Formateur SET specialite = ? WHERE matricule = ?");
        $stmt->bind_param("ss", $new_role, $id);
    } elseif ($_POST['type'] == 'apprenant') {
        $stmt = $conn->prepare("UPDATE Apprenant SET login = ? WHERE code = ?");
        $stmt->bind_param("ss", $new_role, $id);
    }
    $stmt->execute();
}

$action = isset($_GET['action']) ? $_GET['action'] : ''; 

if ($action == 'add') {
    mail($email, "Nouvel ajout d'utilisateur", "Un nouvel utilisateur a été ajouté.");
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
    <style>
        .sidebar {
            background-color: black;
            color: white;
            height: 100vh; 
            width: 300px;
            position: fixed;
            top: 0;
            left: -300px; 
            padding: 20px;
            z-index: 1;
            transition: left 0.3s ease; 
        }

        .sidebar .nav-link {
            position: relative;
            padding: 10px;
            transition: all 0.3s ease-in-out;
            text-decoration: none;
            color: white;
        }

        .sidebar .nav-link:hover {
            border: 2px solid black;
            color: black;
        }

        .content {
            margin-left: 0;
            padding: 20px;
            transition: margin-left 0.3s ease; 
        }

        .content.open-sidebar {
            margin-left: 300px; 
        }

        .toggle-sidebar-btn {
            position: absolute;
            top: 20px;
            left: 20px;
            font-size: 30px;
            cursor: pointer;
            z-index: 2;
        }

        header {
            background-color: rgb(111, 235, 239);
        }

        .form-fields {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container-fluid">

        <header class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <div class="container-fluid d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center text-light text-bold">
                    <span><?php echo $nom . ' ' . $prenom; ?></span>
                </div>
            </div>
        </header>

        <div class="toggle-sidebar-btn" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i> 
        </div>

        <div class="sidebar">
            <h4 class="text-center mb-4">
                <a href="acceuil.php" style="text-decoration: none; color: white;">ADMINISTRATEUR</a>
            </h4>

            <div class="d-flex justify-content-center mb-3">
                <a class="nav-link" href="dasboard.php">
                    <img src="images/stephan.png" alt="User" class="rounded-circle" style="width: 80px; height: 80px;">
                </a>
            </div>

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
            <div class="container">
                <h1 class="my-4">Ajouter un Utilisateur</h1>

                <form method="GET" action="" class="mb-4">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="search">Rechercher</label>
                                <input type="text" class="form-control" id="search" name="search" placeholder="Nom, e-mail, rôle, statut">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="role">Filtrer par rôle</label>
                                <select class="form-control" id="role" name="role">
                                    <option value="">Tous</option>
                                    <option value="formateur">formateur</option>
                                    <option value="app">apprenant</option>
                                </select>
                            </div>
                        </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Rechercher</button>
                </form>

                <!-- User Type Form -->
                <form action="" method="POST">
                    <div class="form-group">
                        <label for="type">Type d'Utilisateur</label>
                        <select class="form-control" id="type" name="type" onchange="toggleForm()">
                            <option value="formateur">Formateur</option>
                            <option value="apprenant">Apprenant</option>
                        </select>
                    </div>

                    <!-- Formateur Fields -->
                    <div id="formateur_fields" class="form-fields">
                        <div class="form-group">
                            <label for="matricule">Matricule</label>
                            <input type="text" class="form-control" id="matricule" name="matricule" required>
                        </div>
                        <div class="form-group">
                            <label for="nomForm">Nom</label>
                            <input type="text" class="form-control" id="nomForm" name="nomForm" required>
                        </div>
                        <div class="form-group">
                            <label for="prenomForm">Prénom</label>
                            <input type="text" class="form-control" id="prenomForm" name="prenomForm" required>
                        </div>
                        <div class="form-group">
                            <label for="specialite">Spécialité</label>
                            <input type="text" class="form-control" id="specialite" name="specialite" required>
                        </div>
                    </div>

                    <!-- Apprenant Fields -->
                    <div id="apprenant_fields" class="form-fields">
                        <div class="form-group">
                            <label for="code">Code</label>
                            <input type="text" class="form-control" id="code" name="code" required>
                        </div>
                        <div class="form-group">
                            <label for="nomAp">Nom</label>
                            <input type="text" class="form-control" id="nomAp" name="nomAp" required>
                        </div>
                        <div class="form-group">
                            <label for="prenomAp">Prénom</label>
                            <input type="text" class="form-control" id="prenomAp" name="prenomAp" required>
                        </div>
                        <div class="form-group">
                            <label for="dateIns">Date d'inscription</label>
                            <input type="text" class="form-control" id="dateIns" name="dateIns" required>
                        </div>
                    </div>

                    <!-- Common Fields -->
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="telephone">Téléphone</label>
                        <input type="text" class="form-control" id="telephone" name="telephone" required>
                    </div>
                    <div class="form-group">
                        <label for="login">Login</label>
                        <input type="text" class="form-control" id="login" name="login" required>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">Ajouter</button>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript to toggle sidebar -->
    <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const content = document.querySelector('.content');
            
            if (sidebar.style.left === '0px') {
                sidebar.style.left = '-300px'; // Hide sidebar
                content.classList.remove('open-sidebar'); // Adjust content margin
            } else {
                sidebar.style.left = '0'; // Show sidebar
                content.classList.add('open-sidebar'); // Adjust content margin
            }
        }

        function toggleForm() {
            var type = document.getElementById("type").value;
            if (type === "formateur") {
                document.getElementById("formateur_fields").style.display = "block";
                document.getElementById("apprenant_fields").style.display = "none";
            } else {
                document.getElementById("formateur_fields").style.display = "none";
                document.getElementById("apprenant_fields").style.display = "block";
            }
        }
    </script>
</body>
</html>


    <script>
        // Afficher les champs selon le type d'utilisateur sélectionné
        document.getElementById("type").addEventListener("change", function() {
            if (this.value == "formateur") {
                document.getElementById("formateur_fields").style.display = "block";
                document.getElementById("apprenant_fields").style.display = "none";
            } else {
                document.getElementById("formateur_fields").style.display = "none";
                document.getElementById("apprenant_fields").style.display = "block";
            }
        });
    </script>

    <h1>Gestion des Utilisateurs</h1>

    <a href="ajouter_utilisateur.php" class="btn btn-primary mb-3">Ajouter un Utilisateur</a>

    <h3>Formateurs</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Matricule</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Spécialité</th>
                <th>Login</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $resultFormateur->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['matricule']); ?></td>
                    <td><?php echo htmlspecialchars($row['nomForm']); ?></td>
                    <td><?php echo htmlspecialchars($row['prenomForm']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['telephone']); ?></td>
                    <td><?php echo htmlspecialchars($row['specialite']); ?></td>
                    <td><?php echo htmlspecialchars($row['login']); ?></td>
                    <td>
                        <a href="modifier_formateur.php?matricule=<?php echo $row['matricule']; ?>" class="btn btn-warning">Modifier</a>
                        <a href="supprimer_formateur.php?matricule=<?php echo $row['matricule']; ?>" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce formateur ?');">Supprimer</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <h3>Apprenants</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Code</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Date d'inscription</th>
                <th>Login</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $resultApprenant->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['code']); ?></td>
                    <td><?php echo htmlspecialchars($row['nomAp']); ?></td>
                    <td><?php echo htmlspecialchars($row['prenomAp']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['telephone']); ?></td>
                    <td><?php echo htmlspecialchars($row['dateIns']); ?></td>
                    <td><?php echo htmlspecialchars($row['login']); ?></td>
                    <td>
                        <a href="modifier_apprenant.php?code=<?php echo $row['code']; ?>" class="btn btn-warning">Modifier</a>
                        <a href="supprimer_apprenant.php?code=<?php echo $row['code']; ?>" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet apprenant ?');">Supprimer</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div class="pagination">
    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <a href="gestion_utilisateurs.php?page=<?php echo $i; ?>" class="btn btn-link"><?php echo $i; ?></a>
    <?php endfor; ?>
</div>
</body>
</html>
