<?php
// Define variables and set to empty values
$formateur = $apprenant = $commentaire = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $formateur = $_POST['formateur'];
    $apprenant = $_POST['apprenant'];
    $commentaire = $_POST['commentaire'];

    // Here, you would typically handle form data (e.g., save it to a database)
    // For now, just show the data in the console
    if (!empty($formateur) && !empty($apprenant) && !empty($commentaire)) {
        echo "<script>alert('Rapport envoyé avec succès!');</script>";
        // Optionally, reset the fields after submission
        $formateur = $apprenant = $commentaire = "";
    } else {
        echo "<script>alert('Veuillez remplir tous les champs.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire de Rapport</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
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
