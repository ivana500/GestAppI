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
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");  
    exit();
}
?>

<?php
require_once 'Fonctions/db_connection.php';
require_once 'Fonctions/fonctions.php';
$conn = getConnection();
$sql = "SELECT * FROM EmploiTemps WHERE idE = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $idE);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $emploiTemps = $result->fetch_assoc();  
    } else {
        echo "Aucun emploi du temps trouvé avec cet ID.";
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modifier'])) {
        $coursId = $_POST['cours'];
        $heureDebut = $_POST['heureDebut'];
        $heureFin = $_POST['heureFin'];
        $jour = $_POST['jour'];

        $updateSql = "UPDATE EmploiTemps SET heureDebut = ?, heureFin = ?, jour = ? WHERE idE = ?";
        $stmt = $conn->prepare($updateSql);
        $stmt->bind_param('sssi', $heureDebut, $heureFin, $jour, $idE);

        if ($stmt->execute()) {
            // Mettre à jour la table 'refer' (association entre emploiTemps et Cours)
            $updateReferSql = "UPDATE refer SET idC = ? WHERE idE = ?";
            $stmt = $conn->prepare($updateReferSql);
            $stmt->bind_param('si', $coursId, $idE);

            if ($stmt->execute()) {
                $_SESSION['success_message'] = "Emploi du temps et cours associés mis à jour avec succès!";
                header("Location: emploiTemps.php");
                exit;
            } else {
                echo "Erreur lors de la mise à jour de l'association entre l'emploi du temps et le cours.";
            }
        } else {
            echo "Erreur lors de la mise à jour de l'emploi du temps.";
        }

    $stmt->close();
    $conn->close();
} else {
    header("Location: emploiTemps.php");
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

    <form action="" method="POST">
    <div class="mb-3">
        <label for="cours" class="form-label">Sélectionner un Cours</label>
        <select class="form-select" id="cours" name="cours" required>
            <option value="">Sélectionner un Cours</option>
            <?php 
            $conn = getConnection();
            $query = "SELECT * FROM Cours";
            $result = $conn->query($query);
            while ($cours_item = $result->fetch_assoc()) {
                $selected = ($cours_item['idC'] == $emploiTemps['idC']) ? 'selected' : ''; 
                echo "<option value='{$cours_item['idC']}' $selected>{$cours_item['titreC']}</option>";
            }
            ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="heureDebut" class="form-label">Heure de Début</label>
        <input type="time" class="form-control" id="heureDebut" name="heureDebut" value="<?= $emploiTemps['heureDebut'] ?>" required>
    </div>

    <div class="mb-3">
        <label for="heureFin" class="form-label">Heure de Fin</label>
        <input type="time" class="form-control" id="heureFin" name="heureFin" value="<?= $emploiTemps['heureFin'] ?>" required>
    </div>

    <div class="mb-3">
        <label for="jour" class="form-label">Jour</label>
        <input type="date" class="form-control" id="jour" name="jour" value="<?= $emploiTemps['jour'] ?>" required>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
        <button type="submit" class="btn btn-warning" name="modifier">Modifier Emploi du Temps</button>
    </div>
</form>

</div>

<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
