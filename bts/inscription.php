<?php
// Inclure le fichier de connexion à la base de données
require_once 'Fonctions/db_connection.php';
require_once 'Fonctions/fonctions.php';
// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Récupérer les données du formulaire
    $matricule = $_POST['matricule'];
    $nomForm = $_POST['nomForm'];
    $prenomForm = $_POST['prenomForm'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];
    $specialite = $_POST['specialite'];
    $photo = '';
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        // Dossier de destination pour les photos
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["photo"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Vérifier si le fichier est une image
        $check = getimagesize($_FILES["photo"]["tmp_name"]);
        if ($check !== false) {
            // Vérifier si l'extension est correcte
            if ($imageFileType == "jpg" || $imageFileType == "jpeg" || $imageFileType == "png" || $imageFileType == "gif") {
                // Déplacer le fichier téléchargé
                if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
                    $photo = basename($_FILES["photo"]["name"]);
                } else {
                    echo "<div class='alert alert-danger'>Désolé, une erreur est survenue lors de l'upload de l'image.</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>Seules les images JPG, JPEG, PNG et GIF sont autorisées.</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Ce fichier n'est pas une image valide.</div>";
        }
    }

    // Connexion à la base de données
    $conn = getConnection();

// Préparer la requête d'insertion
$stmt = $conn->prepare("INSERT INTO Formateur (matricule, nomForm, prenomForm, email, telephone, specialite,photo) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $matricule, $nomForm, $prenomForm, $email, $telephone, $specialite, $photo);

// Exécuter la requête et vérifier si l'insertion a réussi
if ($stmt->execute()) {
    echo "<div class='alert alert-success'>Inscription réussie !</div>";
    redirection("connexion.php");
} else {
    echo "<div class='alert alert-danger'>Erreur lors de l'inscription. Veuillez réessayer.</div>";
}

// Fermer la connexion
$stmt->close();
closeConnection($conn);  
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription Formateur</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #e0f7fa;
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 600px;
            margin-top: 50px;
        }

        .form-container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .form-container h2 {
            font-size: 1.8rem;
            color: #00796b;
            text-align: center;
        }

        .form-group label {
            font-weight: bold;
        }

        .btn-primary {
            background-color: #00796b;
            border-color: #00796b;
        }

        .btn-primary:hover {
            background-color: #004d40;
            border-color: #004d40;
        }

        .alert {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="form-container">
        <h2>Inscription Formateur</h2>
        
        <form method="POST" action="" enctype="multipart/form-data">
    <div class="form-group">
        <label for="matricule">Matricule</label>
        <input type="text" class="form-control" id="matricule" name="matricule" required placeholder="Votre matricule">
    </div>

    <div class="form-group">
        <label for="nomForm">Nom</label>
        <input type="text" class="form-control" id="nomForm" name="nomForm" required placeholder="Votre nom">
    </div>

    <div class="form-group">
        <label for="prenomForm">Prénom</label>
        <input type="text" class="form-control" id="prenomForm" name="prenomForm" required placeholder="Votre prénom">
    </div>

    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" class="form-control" id="email" name="email" placeholder="Votre adresse email">
    </div>

    <div class="form-group">
        <label for="telephone">Téléphone</label>
        <input type="text" class="form-control" id="telephone" name="telephone" placeholder="Votre numéro de téléphone">
    </div>

    <div class="form-group">
        <label for="specialite">Spécialité</label>
        <input type="text" class="form-control" id="specialite" name="specialite" required placeholder="Votre spécialité">
    </div>

    <!-- Champ pour télécharger la photo -->
    <div class="form-group">
        <label for="photo">Photo (facultatif)</label>
        <input type="file" class="form-control" id="photo" name="photo">
    </div>

    <button type="submit" class="btn btn-primary w-100">S'inscrire</button>
</form>

    </div>
</div>

<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
