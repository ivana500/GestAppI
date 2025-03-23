<?php
// Inclure le fichier de connexion à la base de données
require_once 'Fonctions/db_connection.php';
require_once 'Fonctions/fonctions.php';
// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Récupérer les données du formulaire
    $code = $_POST['code'];
    $nomAp = $_POST['nomAp'];
    $prenomAp = $_POST['prenomAp'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];
    $dateIns = date("Y-m-d");  // Date actuelle
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
    $stmt = $conn->prepare("INSERT INTO Apprenant (code, nomAp, prenomAp, email, telephone, dateIns, photo) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $code, $nomAp, $prenomAp, $email, $telephone, $dateIns, $photo);

    // Exécuter la requête et vérifier si l'insertion a réussi
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Inscription réussie !</div>";
    } else {
        echo "<div class='alert alert-danger'>Erreur lors de l'inscription. Veuillez réessayer.</div>";
    }

    // Fermer la connexion
    $stmt->close();
    closeConnection($conn); ;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Apprenant</title>
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
        <h2>Inscription Apprenant</h2>
        
        <!-- Formulaire d'inscription -->
        <form method="POST" action="" enctype="multipart/form-data">
    <div class="form-group">
        <label for="code">Code Apprenant</label>
        <input type="text" class="form-control" id="code" name="code" required placeholder="Votre code d'apprenant">
    </div>

    <div class="form-group">
        <label for="nomAp">Nom</label>
        <input type="text" class="form-control" id="nomAp" name="nomAp" required placeholder="Votre nom">
    </div>

    <div class="form-group">
        <label for="prenomAp">Prénom</label>
        <input type="text" class="form-control" id="prenomAp" name="prenomAp" required placeholder="Votre prénom">
    </div>

    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" class="form-control" id="email" name="email" placeholder="Votre adresse email">
    </div>

    <div class="form-group">
        <label for="telephone">Téléphone</label>
        <input type="text" class="form-control" id="telephone" name="telephone" placeholder="Votre numéro de téléphone">
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
