<?php 

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="fontawesome/css/all.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #e0f7fa; 
        }

        .card-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .card {
            display: flex;
            flex-direction: row;
            width: 700px;
        }

        .card-body {
            flex: 1;
            padding: 30px;
        }

        .image-container {
            flex: 1;
            background: url('images/logo.png') no-repeat center center;
            background-size: cover;
            height: 100%;
            border-radius: 10px 0 0 10px;
        }

        .card-body .text-center img {
            max-width: 80px;
            max-height: 80px;
            margin-bottom: 10px;
        }

        .card-body .text-center h5 {
            font-size: 1.5rem;
            margin-top: 10px;
        }

        .form-control {
            margin-bottom: 10px;
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
            display: none;
            margin-top: 20px;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>

<div class="card-container">
    <div class="card rounded-5">
       
        <div class="image-container">
                <img src="images/inscription.avif" alt="Inscription" class="img-fluid">
            </div>

        <div class="card-body">
            <div class="text-center mb-4">
                <div class="rounded-circle mx-auto" style="overflow: hidden; width: 80px; height: 80px;">
                    <img src="images/logo.png" alt="Logo" class="img-fluid">
                </div>
                <h5 class="card-title mt-2 display-6">Connexion</h5>
            </div>

            <form id="loginForm" action="TraitementAuth.php" method="POST">
                <div class="mb-4">
                    <label for="username" class="form-label">
                        <i class="fas fa-user"></i> Nom d'utilisateur
                    </label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-4">
                    <label for="password" class="form-label">
                        <i class="fas fa-lock"></i> Mot de passe
                    </label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-sign-in-alt"></i> Se connecter
                </button>
            </form>

            <div id="notification" class="alert alert-danger mt-3 d-none" role="alert"></div>

            <!-- Liens supplémentaires -->
            <div class="text-center mt-4">
                <a href="/mot-de-passe-oublie.php" class="text-muted">Mot de passe oublié ?</a> |
                <a href="/inscription.php" class="text-muted">S'inscrire</a>
            </div>
        </div>
    </div>
</div>

<script>
    function showNotification(message, isSuccess) {
        const notification = document.getElementById('notification');
        notification.textContent = message;					
        notification.classList.toggle('alert-success', isSuccess);
        notification.classList.toggle('alert-danger', !isSuccess);
        notification.classList.remove('d-none');

        // Hide the notification after 3 seconds
        setTimeout(() => {
            notification.classList.add('d-none');
        }, 3000);
    }

    // Notification en cas d'erreur d'authentification
    <?php if (isset($_GET['error'])): ?>
        const errorMessage = "Échec de l'authentification. Vérifiez vos identifiants.";
        showNotification(errorMessage, false);
    <?php endif; ?>
</script>

<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
