<?php
// home.php
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GestAppS - Accueil</title>
    <link rel="stylesheet" href="styles.css"> <!-- Assurez-vous d'ajouter votre fichier CSS pour la mise en forme -->
    <style>
        /* Vous pouvez également ajouter des styles CSS ici si nécessaire */
        body {
            text-align: center;
            background-color: #f0f0f0;
            font-family: Arial, sans-serif;
        }

        .App-header {
            background-color: #282c34;
            padding: 50px;
            color: white;
        }

        .App-logo {
            width: 150px;
            margin-bottom: 20px;
        }

        .dynamic-text {
            font-size: 1.5em;
            margin: 20px 0;
        }

        h1 {
            font-size: 3em;
            font-weight: bold;
            color: #61dafb;
        }

        .glowing-button {
            padding: 15px 25px;
            font-size: 18px;
            background-color: #61dafb;
            border: none;
            border-radius: 5px;
            color: white;
            cursor: pointer;
            box-shadow: 0 0 15px rgba(97, 218, 251, 0.7);
            transition: all 0.3s ease-in-out;
        }

        .glowing-button:hover {
            background-color: #21a1f1;
            box-shadow: 0 0 25px rgba(33, 161, 241, 0.8);
        }

    </style>
</head>
<body>

    <div class="App">
        <header class="App-header">
            <img src="images/ac1.png" class="App-logo" alt="logo" /> <!-- Assurez-vous que le chemin du logo est correct -->
            <h1>GestAppS</h1>
            <p class="dynamic-text">Bienvenue dans notre plateforme de gestion d'apprenants</p>
            <a href="acceuil.php"> <!-- Redirection vers la page Accueil -->
                <button class="glowing-button">
                    Continuer
                </button>
            </a>
        </header>
    </div>

</body>
</html>
