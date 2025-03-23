<?php

    require_once 'configBD.php'; // Inclure le fichier de configuration

    function getConnection() {
        $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
        
        if ($conn->connect_error) {
            die("Échec de la connexion : " . $conn->connect_error);
        }

        return $conn;
    }

    function closeConnection($conn) {
        // Correction ici : ajouter les parenthèses pour appeler la méthode close()
        $conn->close();
    }
?>
