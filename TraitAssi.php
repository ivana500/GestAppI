<?php

include_once './Fonctions/db_connection.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   
    // Récupérer l'action demandée
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    // Traiter les différentes actions possibles
    switch ($action) {
        

            case 'deleteEnseig':
                $idAp = $_POST['idAp'];
    $idC = $_POST['idC'];
    
    $sql = "DELETE FROM suivieCours WHERE idAp='$idAp' AND idC='$idC'";
    $con = getConnection();
    $result = $con->query($sql);

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Enregistrement supprimé avec succès.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression.']);
    }
            break;
        
    }
}
