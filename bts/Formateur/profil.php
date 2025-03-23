<?php
// Start the session to manage user data
session_start();

// Simulating the user data for the purpose of this example (replace this with actual data from your session or database)
$user = [
    'formateur' => [
        'nomForm' => 'Jean',
        'prenomForm' => 'Dupont',
        'email' => 'jean.dupont@example.com',
        'telephone' => '123-456-7890',
        'specialite' => 'Informatique',
        'photo' => 'https://via.placeholder.com/150'  // Placeholder photo URL (can be a real photo URL)
    ]
];

// Extracting the formateur's data from the session or user object
$formateur = isset($user['formateur']) ? $user['formateur'] : null;

// If the data is not available, show a loading message
if (!$formateur) {
    echo "<div>Chargement...</div>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil du Formateur</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</head>
<body>
    <div class="container my-5">
        <h1 class="text-center text-bold">Profil du Formateur</h1>
        
        <!-- Error message (if any) -->
        <?php if (isset($error) && $error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <div class="card border-primary mb-3 rounded-3 shadow">
            <div class="card-header bg-secondary-subtle text-success rounded-3 d-flex justify-content-center align-items-center">
                <h3 class="mb-0"><i class="fas fa-user-circle me-2"></i>Informations du Formateur</h3>
            </div>
            <div class="card-body shadow">
                <div class="row">
                    <!-- Photo du formateur -->
                    <div class="col-md-4 text-center">
                        <img
                            src="<?php echo htmlspecialchars($formateur['photo']); ?>"
                            alt="Formateur"
                            class="img-fluid rounded-circle"
                            style="width: 150px; height: 150px; object-fit: cover;"
                        />
                    </div>

                    <!-- Informations du formateur -->
                    <div class="col-md-8">
                        <h5>Noms et Prénoms</h5>
                        <h4 class="text-success"><?php echo htmlspecialchars($formateur['nomForm']) . ' ' . htmlspecialchars($formateur['prenomForm']); ?></h4>
                        <hr />
                        <div>
                            <h5>Email</h5>
                            <p><?php echo htmlspecialchars($formateur['email']); ?></p>
                        </div>
                        <div>
                            <h5>Téléphone</h5>
                            <p><?php echo htmlspecialchars($formateur['telephone']); ?></p>
                        </div>
                        <div>
                            <h5>Spécialité</h5>
                            <p><?php echo htmlspecialchars($formateur['specialite']); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
