<?php

require_once 'Fonctions/db_connection.php';
require_once 'Fonctions/fonctions.php';

if (isset($_POST['username']) && isset($_POST['password'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];

 
    $conn = getConnection();

    $sqlAdmin = "SELECT * FROM administrateur WHERE login = '$username' AND password = '$password'";
    $resultAdmin = $conn->query($sqlAdmin);

    // Vérifier si existance de l'administrateur
    if ($resultAdmin && $resultAdmin->num_rows > 0) {
        $ligneAdmin = $resultAdmin->fetch_assoc();
        session_start();
        $_SESSION['id'] = $ligneAdmin['id'];
        $_SESSION['nom'] = $ligneAdmin['nomAd'];
        $_SESSION['prenom'] = $ligneAdmin['prenomAd'];
        var_dump($_SESSION); 
        redirection("apprenant.php");
    }
   
    else {
        $sqlFormateur = "SELECT * FROM formateur WHERE login = '$username' AND matricule = '$password'";
        $resultFormateur = $conn->query($sqlFormateur);

        // Vérifier si existance du formateur
        if ($resultFormateur && $resultFormateur->num_rows > 0) {
            $ligneFormateur = $resultFormateur->fetch_assoc();
            session_start();
            $_SESSION['matricule'] = $ligneFormateur['matricule'];
            $_SESSION['nom'] = $ligneFormateur['nomForm'];
            $_SESSION['prenom'] = $ligneFormateur['prenomForm'];
            $_SESSION['specialite'] = $ligneFormateur['specialite'];
            $_SESSION['email'] = $ligneFormateur['email'];
            $_SESSION['telephone'] = $ligneFormateur['telephone'];
            $_SESSION['photo'] = $ligneFormateur['photo'];
            redirection("Formateur/profil.php");
        }
       
        else {
            $sqlApprenant = "SELECT * FROM apprenant WHERE login = '$username' AND code = '$password'";
            $resultApprenant = $conn->query($sqlApprenant);

            // Vérifier si existance de l'apprenant
            if ($resultApprenant && $resultApprenant->num_rows > 0) {
                $ligneApprenant = $resultApprenant->fetch_assoc();
                session_start();
                $_SESSION['matricule'] = $ligneApprenant['code'];
                $_SESSION['nom'] = $ligneApprenant['nomAp'];
                $_SESSION['prenom'] = $ligneApprenant['prenomAp'];
                $_SESSION['email'] = $ligneApprenant['email'];
                $_SESSION['sexe'] = $ligneApprenant['sexe'];
                $_SESSION['dateIns'] = $ligneApprenant['dateIns'];
                $_SESSION['telephone'] = $ligneApprenant['telephone'];
                $_SESSION['photo'] = $ligneApprenant['photo'];
                redirection("Apprenant/profilA.php");
            } else {
               
                redirection("index.php?error=0");
            }
        }
    }

    closeConnection();
}
?>
