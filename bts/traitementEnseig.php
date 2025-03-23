<?php

include_once './Fonctions/db_connection.php';
// Vérifier que la requête est bien en POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   
    // Récupérer l'action demandée
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    // Traiter les différentes actions possibles
    switch ($action) {
        
        case 'editEnseig':
            $mat = $_POST['matricule'];
            $sql = "SELECT * FROM enseignants WHERE matricule='$mat'";
            $con = getConnection();
            $result = $con->query($sql);
            $data = $result->fetch_assoc();
            echo json_encode($data);            
        break;
    
        case 'updateEnseig':
            $matricule= $_POST['matricule'];
            $nom= $_POST['nom'];
            $prenom=$_POST['prenom']; 
            $specialite= $_POST['specialite'];
            $fonction= $_POST['fonction'];
            $login= $_POST['login'];
            $passWord= $_POST['passWord'];
            
            $sql = "UPDATE Enseignants "
                    . "set fonction='$fonction' ,nom='$nom' ,prenom='$prenom' , specialite='$specialite' , "
                    . "login='$login',passWord='$passWord' WHERE matricule = $matricule";
            $con = getConnection();
            $result = $con->query($sql);
//            $result = true;
              if ($result) {
                    $response = array(
                        'success' => true,
                        'message' => 'Niveau mis à jour avec succès'
                    );
                } else {
                    $response = array(
                        'success' => false,
                        'message' => 'Erreur lors de la mise à jour du niveau'
                    );
                }                
                echo json_encode($response);
        break;

    default:
            // Action inconnue
            http_response_code(400);
            $response = array(
                'success' => false,
                'message' => 'Action inconnue'
            );
            echo json_encode($response);
            break;

            case 'deleteEnseig':
                $matricule= $_POST['matricule'];
                     $del = "DELETE FROM enseignants WHERE matricule = '$matricule'";
                     $con = getConnection();
                     $delet = $con->query($del);
                     
                     echo json_encode($delet);     
            break;
        
    }
}
