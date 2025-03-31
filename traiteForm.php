<?php

require_once 'Fonctions/db_connection.php';
require_once 'Fonctions/fonctions.php';

if (isset($_GET['matricule'])) {
    $code = $_GET['matricule'];
    $conn = getConnection();
    $sql = "SELECT * FROM formateur WHERE matricule = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $code);  // Assurez-vous que c'est 's' pour string
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $formateurs = $result->fetch_assoc();  
    } else {
        echo "Aucun formateur trouvé avec ce matricule.";
        exit;
    }



    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modifier'])) {
        $nomAp = $_POST['nomAp'];
        $prenomAp = $_POST['prenomAp'];
        $email = $_POST['email'];
        $telephone = $_POST['telephone'];
        $specialite = $_POST['specialite'];
        $code = $_POST['matricule'];   

        $photo = $formateurs['photo'];  

        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $targetDir = "uploads/";
            $fileName = basename($_FILES['photo']['name']);
            $targetFile = $targetDir . $fileName;
            move_uploaded_file($_FILES['photo']['tmp_name'], $targetFile);
            $photo = $fileName; 
        }

       
        $updateSql = "UPDATE formateur SET nomForm = ?, prenomForm = ?, email = ?, telephone = ?, specialite = ?, photo = ? WHERE matricule = ?";
        $stmt = $conn->prepare($updateSql);
        $stmt->bind_param('ssssssi', $nomAp, $prenomAp, $email, $telephone, $specialite, $photo, $code);

        if ($stmt->execute()) {
            header("Location: formateur.php");  
            exit;
        } else {
            echo "Erreur lors de la mise à jour du formateur.";
        }

        $stmt->close();
    }
    $conn->close();
} else {
    header("Location: formateur.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modification Formateur</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2>Modifier le Formateur</h2>

    <form action="traiteForm.php>" method="POST" enctype="multipart/form-data">
        
        <div class="mb-3">
            <label for="nomAp" class="form-label">Nom</label>
            <input type="text" class="form-control" id="nomAp" name="nomAp" value="<?= $formateurs['nomForm'] ?>" required>
        </div>

        <div class="mb-3">
            <label for="prenomAp" class="form-label">Prénom</label>
            <input type="text" class="form-control" id="prenomAp" name="prenomAp" value="<?= $formateurs['prenomForm'] ?>" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= $formateurs['email'] ?>" required>
        </div>

        <div class="mb-3">
            <label for="telephone" class="form-label">Téléphone</label>
            <input type="tel" class="form-control" id="telephone" name="telephone" value="<?= $formateurs['telephone'] ?>" required>
        </div>

        <div class="mb-3">
            <label for="specialite" class="form-label">Specialite</label>
            <input type="text" class="form-control" id="specialite" name="specialite" value="<?= $formateurs['specialite'] ?>" required>
        </div>

        <div class="mb-3">
            <label for="photo" class="form-label">Photo</label>
            <input type="file" class="form-control" id="photo" name="photo">
            <small>Actuelle: <img src="uploads/<?= $formateurs['photo'] ?>" alt="Photo" style="width: 50px; height: 50px;"></small>
        </div>

        <button type="submit" name="modifier" class="btn btn-primary">Sauvegarder les modifications</button>
    </form>
</div>

<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
