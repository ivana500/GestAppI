<?php
require_once 'Fonctions/db_connection.php';
require_once 'Fonctions/fonctions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin_id = $_POST['admin_id'];
    $montant = $_POST['montant'];
    $code = $_POST['code']; 
    $nomAp = $_POST['nomAp'];
    $prenomAp = $_POST['prenomAp'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];

    $conn = getConnection();

    $sqlAdmin = "SELECT * FROM administrateur WHERE id = ?";
    $stmtAdmin = $conn->prepare($sqlAdmin);
    $stmtAdmin->bind_param("s", $admin_id);
    $stmtAdmin->execute();
    $resultAdmin = $stmtAdmin->get_result();

    if ($resultAdmin->num_rows > 0) {
        if (!empty($montant)) {
            $sqlInscription = "INSERT INTO inscription (numero, idAP, montant) VALUES (NULL, ?, ?)";
            $stmtInscription = $conn->prepare($sqlInscription);
            $stmtInscription->bind_param("ss", $code, $montant);

            if ($stmtInscription->execute()) {
                echo "<div class='alert alert-success'>Inscription réussie et paiement validé !</div>";
            } else {
                echo "<div class='alert alert-danger'>Erreur lors de l'enregistrement du paiement. Veuillez réessayer.</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Le montant est obligatoire.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>ID Administrateur incorrect.</div>";
    }

    $stmtAdmin->close();
    closeConnection($conn);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Option de Paiement</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <div class="payment-option">
        <h3>Option de Paiement</h3>
        <form method="POST" action="succes.php">
            <div class="form-group">
                <label for="admin_id">ID Administrateur :</label>
                <input type="text" name="admin_id" id="admin_id" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="montant">Montant :</label>
                <input type="number" name="montant" id="montant" class="form-control" required>
            </div>

            <input type="hidden" name="code" value="<?php echo isset($_GET['code']) ? $_GET['code'] : ''; ?>">
            <input type="hidden" name="nomAp" value="<?php echo isset($_GET['nomAp']) ? $_GET['nomAp'] : ''; ?>">
            <input type="hidden" name="prenomAp" value="<?php echo isset($_GET['prenomAp']) ? $_GET['prenomAp'] : ''; ?>">
            <input type="hidden" name="email" value="<?php echo isset($_GET['email']) ? $_GET['email'] : ''; ?>">
            <input type="hidden" name="telephone" value="<?php echo isset($_GET['telephone']) ? $_GET['telephone'] : ''; ?>">

            <button type="submit" class="btn btn-primary mt-3">Valider le paiement</button>
        </form>
    </div>
</div>
<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
