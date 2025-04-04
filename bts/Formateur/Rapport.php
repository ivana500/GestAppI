<?php
session_start();  
if (!isset($_SESSION['nom']) || !isset($_SESSION['prenom'])) {
   
    header("Location: ../connexion.php");
    exit();
}

$nom = $_SESSION['nom'];
$prenom = $_SESSION['prenom'];
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'formateur') {
    header("Location: ../index.php");  
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../fontawesome/css/all.css" rel="stylesheet">
    
    <style>
       
.sidebar {
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

.sidebar .nav-link:focus {
    outline: none;
    box-shadow: red; 
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
     
     <header class="text-white p-3 bg-light">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center search-bar">
                <input type="text" class="form-control me-2" placeholder="Recherche..." aria-label="Recherche">
            </div>
            <div class="d-flex align-items-center text-dark text-bold">
                <span><?php echo $nom . ' ' . $prenom; ?></span>
                
            </div>
        </div>
    </header>

    <div class="d-flex">
        
        <div class="sidebar flex-shrink-0 p-3 bg-light">
            

            
    <h4 class="text-center mb-4 y" >
        <a href="acceuil.php" style="text-decoration: none; color: blue;">Formateur</a>
    </h4>

    <div class="d-flex justify-content-center mb-3 ">
        <img src="" alt="User" class="rounded-circle" style="width: 60px; height: 60px;">
    </div>

    <div class="nav flex-column">
        <li class="nav-item mb-3">
            <a class="nav-link text-dark " href="profil.php">
                <i class="fas fa-list me-2 bg-light"></i>Profil
            </a>
        </li>
    
        <li class="nav-item mb-3">
            <a class="nav-link text-dark" href="emploi.php">
                <i class="fas fa-calendar me-2"></i>Emploi du Temps
            </a>
        </li>
        <li class="nav-item mb-3">
            <a class="nav-link text-dark" href="Rapport.php">
                <i class="fas fa-file-alt me-2"></i> Rapport
            </a>
        </li>
       
    </div>
</div>
        
</head>
          

<body>
    <div class="container my-5">
        <h1 class="text-center text-bold">Formulaire de Rapport</h1>

        <!-- Formulaire de saisie -->
        <form method="POST" action="rapport1.php">
            <!-- Nom du formateur -->
            <div class="mb-4">
                <label for="formateur" class="form-label">Nom du Formateur</label>
                <input
                    type="text"
                    id="formateur"
                    name="formateur"
                    class="form-control"
                    value="<?php echo htmlspecialchars($formateur); ?>"
                    placeholder="Entrez le nom du formateur"
                    required
                />
            </div>

            <!-- Sélecteur d'apprenant -->
            <div class="mb-4">
                <label for="apprenant" class="form-label">Choisir un Apprenant</label>
                <select
                    class="form-select"
                    id="apprenant"
                    name="apprenant"
                    required
                >
                    <option value="">Sélectionner un apprenant</option>
                    <option value="Jean Dupont" <?php echo $apprenant == 'Jean Dupont' ? 'selected' : ''; ?>>Jean Dupont</option>
                    <option value="Marie Lemoine" <?php echo $apprenant == 'Marie Lemoine' ? 'selected' : ''; ?>>Marie Lemoine</option>
                    <option value="Pierre Martin" <?php echo $apprenant == 'Pierre Martin' ? 'selected' : ''; ?>>Pierre Martin</option>
                </select>
            </div>

            <!-- Commentaire -->
            <div class="mb-4">
                <label for="commentaire" class="form-label">Commentaire du Formateur</label>
                <textarea
                    id="commentaire"
                    name="commentaire"
                    class="form-control"
                    rows="4"
                    placeholder="Entrez votre commentaire"
                    required
                ><?php echo htmlspecialchars($commentaire); ?></textarea>
            </div>

            <!-- Bouton envoyer -->
            <button type="submit" class="btn btn-primary">Envoyer</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
