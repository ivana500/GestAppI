<?php
require_once 'Fonctions/db_connection.php';
require_once 'Fonctions/fonctions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $code = $_POST['code'];
    $nomAp = $_POST['nomAp'];
    $prenomAp = $_POST['prenomAp'];
    $email = $_POST['email'];
    $login = $_POST['login'];
    $telephone = $_POST['telephone'];
    $cours = $_POST['cours'];
    $dateIns = date("Y-m-d");
    $photo = '';  

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["photo"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["photo"]["tmp_name"]);
        if ($check !== false) {
            if (in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
                if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
                    $photo = basename($_FILES["photo"]["name"]);
                } else {
                    echo "<div class='alert alert-danger'>Erreur lors du téléchargement de l'image.</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>Seules les images JPG, JPEG, PNG et GIF sont autorisées.</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Ce fichier n'est pas une image valide.</div>";
        }
    }

    $conn = getConnection();

   
    $stmt = $conn->prepare("INSERT INTO Apprenant (code, nomAp, prenomAp, email, telephone, dateIns, login, cours, photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $code, $nomAp, $prenomAp, $email, $telephone, $dateIns, $login, $cours, $photo);
    
    if ($stmt->execute()) {

        header("Location: succes.php?code=$code&nomAp=$nomAp&prenomAp=$prenomAp&email=$email&telephone=$telephone&cours=$cours");
        exit;
    } else {
        echo "<div class='alert alert-danger'>Erreur lors de l'inscription de l'apprenant.</div>";
    }

    $stmt->close();
    closeConnection($conn);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription Apprenant</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="form-container">
        <h2>Inscription Apprenant</h2>
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label for="code">Code Apprenant</label>
                <input type="text" class="form-control" id="code" name="code" required placeholder="Code Apprenant">
            </div>
            <div class="form-group">
                <label for="nomAp">Nom</label>
                <input type="text" class="form-control" id="nomAp" name="nomAp" required placeholder="Nom">
            </div>
            <div class="form-group">
                <label for="prenomAp">Prénom</label>
                <input type="text" class="form-control" id="prenomAp" name="prenomAp" required placeholder="Prénom">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" required placeholder="Email">
            </div>
            <div class="form-group">
                <label for="telephone">Téléphone</label>
                <input type="text" class="form-control" id="telephone" name="telephone" required placeholder="Téléphone">
            </div>
            <div class="form-group">
                <label for="login">Login</label>
                <input type="text" class="form-control" id="login" name="login" required placeholder="login">
            </div>
            <div class="form-group">
                <label for="login">cours</label>
                <input type="text" class="form-control" id="cours" name="cours" required placeholder="cours">
            </div>
            <div class="form-group">
                <label for="photo">Photo</label>
                <input type="file" class="form-control" id="photo" name="photo">
            </div>
            <button type="submit" class="btn btn-primary">Valider l'Inscription</button>
        </form>
    </div>
</div>
<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>

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