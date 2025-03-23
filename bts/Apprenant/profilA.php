<?php
// Start the session to manage user data
session_start();

// Simulating the user data for the purpose of this example (replace this with actual data from your database or session)
$user = [
    'apprenant' => [
        'nom' => 'John',
        'prenom' => 'Doe',
        'email' => 'john.doe@example.com',
        'telephone' => '123-456-7890',
        'dateins' => '2022-03-15',  // Date of registration
        'photo' => 'https://via.placeholder.com/150'  // Placeholder photo (can be a real photo URL)
    ]
];

// Extracting the apprenant's data from the session or user object
$apprenant = isset($user['apprenant']) ? $user['apprenant'] : null;

// If the data is not available, show a loading message
if (!$apprenant) {
    echo "<div>Chargement...</div>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil de l'Apprenant</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</head>
<body>
    <div class="container my-5">
        <h1 class="text-center text-bold">Profil de l'Apprenant</h1>
        <div class="card border-primary mb-3 rounded-3 shadow">
            <div class="card-header bg-secondary-subtle text-success rounded-3 d-flex justify-content-center align-items-center">
                <h3 class="mb-0"><i class="fas fa-user-graduate me-2"></i> Informations de l'Apprenant</h3>
            </div>
            <div class="card-body shadow">
                <div class="row">
                    <!-- Photo de l'apprenant -->
                    <div class="col-md-4 text-center">
                        <img
                            src="<?php echo htmlspecialchars($apprenant['photo']); ?>"
                            alt="Apprenant"
                            class="img-fluid rounded-circle"
                            style="width: 150px; height: 150px; object-fit: cover;"
                        />
                    </div>

                    <!-- Informations de l'apprenant -->
                    <div class="col-md-8">
                        <h4 class="text-success"><?php echo htmlspecialchars($apprenant['nom']) . ' ' . htmlspecialchars($apprenant['prenom']); ?></h4>
                        <p class="text-muted">Email: <?php echo htmlspecialchars($apprenant['email']); ?></p>
                        <hr />
                        <div>
                            <h5>Téléphone</h5>
                            <p><?php echo htmlspecialchars($apprenant['telephone']); ?></p>
                        </div>
                        <div>
                            <h5>Date d'inscription</h5>
                            <p><?php echo date('d/m/Y', strtotime($apprenant['dateins'])); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Button to modify the profile -->
        <div class="text-center">
            <button
                class="btn btn-warning rounded-5 shadow"
                onclick="alert('Modifier fonctionnalité non implémentée')"
            >
                <i class="fas fa-edit me-2"></i>Modifier Profil
            </button>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
