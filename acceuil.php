<?php
// Vous pouvez ajouter votre logique PHP ici si nécessaire
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Apprenants</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="style.css"> <!-- Assurez-vous d'ajouter votre fichier CSS ici -->
</head>

<body>
    <header>
        <h1 class="text-center mt-4">Gestion des Apprenants</h1>
    </header>

    <main>
        <!-- Carousel (Diaporama) -->
        <section id="hero" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="images/accueil.jpg" class="d-block w-100" alt="accueil" style="height: 400px; object-fit: cover;">
                </div>
                <div class="carousel-item">
                    <img src="images/accueil2.jpg" class="d-block w-100" alt="accueil2" style="height: 400px; object-fit: cover;">
                </div>
                <div class="carousel-item">
                    <img src="images/accueil3.avif" class="d-block w-100" alt="accueil3" style="height: 400px; object-fit: cover;">
                </div>
                <div class="carousel-item">
                    <img src="images/accueil4.avif" class="d-block w-100" alt="accueil4" style="height: 400px; object-fit: cover;">
                </div>
            </div>

            <!-- Contrôles du carrousel -->
            <button class="carousel-control-prev" type="button" data-bs-target="#hero" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Précédent</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#hero" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Suivant</span>
            </button>

            <div class="carousel-caption d-none d-md-block">
                <p>Facilitez le suivi et la gestion de vos apprenants avec notre solution intuitive.</p>
                <a href="connexion.php" class="btn btn-primary">Commencez Maintenant</a>
            </div>
        </section>

        <!-- Formulaire de contact -->
        <section id="contact" class="mt-5">
            <h2>Contactez-nous</h2>
            <form action="submit_form.php" method="POST">
                <div class="mb-3">
                    <label for="name" class="form-label">Nom</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="message" class="form-label">Message</label>
                    <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Envoyer</button>
            </form>
        </section>
    </main>

    <footer class="text-center mt-4">
        <p>&copy; 2025 Application de Gestion d'Apprenants. Tous droits réservés.</p>
    </footer>

    <!-- Scripts nécessaires pour Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
