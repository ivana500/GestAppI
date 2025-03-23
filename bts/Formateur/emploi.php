<?php
// Start the session to manage the emplois du temps across page reloads
session_start();

// Initialize the emplois du temps if not already set
if (!isset($_SESSION['emploisDuTemps'])) {
    $_SESSION['emploisDuTemps'] = [];
}

// Handle form submission to add a new emploi du temps
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $newEmploi = [
        'heureDebut' => $_POST['heureDebut'],
        'heureFin' => $_POST['heureFin'],
        'jour' => $_POST['jour'],
        'cours' => $_POST['cours']
    ];
    $_SESSION['emploisDuTemps'][] = $newEmploi; // Add the new emploi to the session

    // Redirect to avoid resubmission after refresh
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Handle deletion of a specific emploi du temps
if (isset($_GET['delete'])) {
    $indexToDelete = $_GET['delete'];
    unset($_SESSION['emploisDuTemps'][$indexToDelete]); // Remove the emploi
    $_SESSION['emploisDuTemps'] = array_values($_SESSION['emploisDuTemps']); // Reindex the array

    // Redirect to avoid URL manipulation
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emploi du Temps</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</head>
<body>
    <div class="container my-5">
        <h1 class="text-center text-bold">Emploi du Temps</h1>
        <div class="card border-primary mb-3 rounded-3">
            <div class="card-header d-flex justify-content-between align-items-center bg-secondary-subtle text-success rounded-3">
                <h3 class="mb-0"><i class="fas fa-calendar-alt me-2"></i> Emploi du Temps</h3>
                <button class="btn btn-primary" data-toggle="modal" data-target="#addEmploiModal">Ajouter un Emploi</button>
            </div>
            <div class="card-body shadow">
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-bordered rounded-3 align-middle mt-4">
                        <thead class="table-primary">
                            <tr class="text-center fw-bold">
                                <th scope="col">ID</th>
                                <th scope="col">Heure de Début</th>
                                <th scope="col">Heure de Fin</th>
                                <th scope="col">Jour</th>
                                <th scope="col">Cours</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($_SESSION['emploisDuTemps'] as $index => $emploi): ?>
                                <tr>
                                    <td class="text-center"><?= $index + 1 ?></td>
                                    <td class="text-center"><?= htmlspecialchars($emploi['heureDebut']) ?></td>
                                    <td class="text-center"><?= htmlspecialchars($emploi['heureFin']) ?></td>
                                    <td class="text-center"><?= htmlspecialchars($emploi['jour']) ?></td>
                                    <td class="text-center"><?= htmlspecialchars($emploi['cours']) ?></td>
                                    <td class="text-center">
                                        <a href="#" class="btn btn-warning btn-sm me-2">Modifier</a>
                                        <a href="?delete=<?= $index ?>" class="btn btn-danger btn-sm">Supprimer</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal to add new emploi -->
    <div class="modal fade" id="addEmploiModal" tabindex="-1" aria-labelledby="addEmploiModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addEmploiModalLabel">Ajouter un Emploi du Temps</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <div class="form-group">
                            <label for="heureDebut">Heure de Début</label>
                            <input type="time" class="form-control" id="heureDebut" name="heureDebut" required>
                        </div>
                        <div class="form-group">
                            <label for="heureFin">Heure de Fin</label>
                            <input type="time" class="form-control" id="heureFin" name="heureFin" required>
                        </div>
                        <div class="form-group">
                            <label for="jour">Jour</label>
                            <input type="text" class="form-control" id="jour" name="jour" required>
                        </div>
                        <div class="form-group">
                            <label for="cours">Cours</label>
                            <input type="text" class="form-control" id="cours" name="cours" required>
                        </div>
                        <button type="submit" name="submit" class="btn btn-primary">Ajouter</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
