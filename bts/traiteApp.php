<?php
require_once 'Fonctions/db_connection.php';
require_once 'Fonctions/fonctions.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   
           
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    switch ($action) {

        case 'deleteApp':
            $code = $_POST['code'];
                 $del = "DELETE FROM apprenant WHERE code = '$code'";
                 $con = getConnection();
                 $delet = $con->query($del);
                 
                 echo json_encode($delet); 
    break;
    
}
}

// Vérifier si l'ID (code) est passé en paramètre dans l'URL
if (isset($_GET['code'])) {
    $code = $_GET['code'];

    // Créer une connexion à la base de données
    $conn = getConnection();

    // Requête pour récupérer les informations de l'apprenant
    $sql = "SELECT * FROM Apprenant WHERE code = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $code);
    $stmt->execute();
    $result = $stmt->get_result();

    // Vérifier si un apprenant est trouvé
    if ($result->num_rows > 0) {
        $apprenant = $result->fetch_assoc();  // Récupérer les données
    } else {
        // Si l'apprenant n'est pas trouvé, afficher un message d'erreur
        echo "Aucun apprenant trouvé avec ce code.";
        exit;
    }

    // Si le formulaire est soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modifier'])) {
        // Récupérer les valeurs du formulaire
        $nomAp = $_POST['nomAp'];
        $prenomAp = $_POST['prenomAp'];
        $email = $_POST['email'];
        $telephone = $_POST['telephone'];
        $code = $_POST['code'];  // Assurez-vous que le code est toujours présent (même s'il ne change pas)

        // Gérer l'upload de la photo si l'utilisateur en a choisi une nouvelle
        $photo = $apprenant['photo'];  // Par défaut, garder la photo existante

        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            // Nouveau fichier photo téléchargé
            $targetDir = "uploads/";
            $fileName = basename($_FILES['photo']['name']);
            $targetFile = $targetDir . $fileName;
            move_uploaded_file($_FILES['photo']['tmp_name'], $targetFile);
            $photo = $fileName;  // Mettre à jour le nom de la photo
        }

        // Préparer la requête de mise à jour
        $updateSql = "UPDATE Apprenant SET nomAp = ?, prenomAp = ?, email = ?, telephone = ?, photo = ? WHERE code = ?";
        $stmt = $conn->prepare($updateSql);
        $stmt->bind_param('sssssi', $nomAp, $prenomAp, $email, $telephone, $photo, $code);

        // Exécuter la requête
        if ($stmt->execute()) {
            // Si la mise à jour est réussie, rediriger vers la page des apprenants
            header("Location: apprenant.php");
            exit;
        } else {
            // Si une erreur survient lors de la mise à jour
            echo "Erreur lors de la mise à jour de l'apprenant.";
        }

        // Fermer la déclaration
        $stmt->close();
    }

    // Fermer la connexion à la base de données
    $conn->close();
} else {
    // Si le code n'est pas spécifié, rediriger vers la page apprenant.php
    header("Location: apprenant.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modification Apprenant</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2>Modifier l'Apprenant</h2>

    <form action="traiteApp.php?code=<?= $apprenant['code'] ?>" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="code" class="form-label">Code</label>
            <input type="text" class="form-control" id="code" name="code" value="<?= $apprenant['code'] ?>" readonly required>
        </div>
        <div class="mb-3">
            <label for="nomAp" class="form-label">Nom</label>
            <input type="text" class="form-control" id="nomAp" name="nomAp" value="<?= $apprenant['nomAp'] ?>" required>
        </div>

        <div class="mb-3">
            <label for="prenomAp" class="form-label">Prénom</label>
            <input type="text" class="form-control" id="prenomAp" name="prenomAp" value="<?= $apprenant['prenomAp'] ?>" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= $apprenant['email'] ?>" required>
        </div>

        <div class="mb-3">
            <label for="telephone" class="form-label">Téléphone</label>
            <input type="tel" class="form-control" id="telephone" name="telephone" value="<?= $apprenant['telephone'] ?>" required>
        </div>

        <div class="mb-3">
            <label for="photo" class="form-label">Photo</label>
            <input type="file" class="form-control" id="photo" name="photo">
            <small>Actuelle: <img src="uploads/<?= $apprenant['photo'] ?>" alt="Photo" style="width: 50px; height: 50px;"></small>
        </div>

        <button type="submit" name="modifier" class="btn btn-primary">Sauvegarder les modifications</button>
    </form>
</div>

<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
