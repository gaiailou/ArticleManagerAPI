<?php
/// Librairies éventuelles (pour la connexion à la BDD, etc.)
require('Fonctions.php');
require('jwt_utils.php');
 

 /// Paramétrage de l'entête HTTP (pour la réponse au Client)
 header("Content-Type:application/json");


 /// Identification du type de méthode HTTP envoyée par le client
 $http_method = $_SERVER['REQUEST_METHOD'];
 switch ($http_method){
    /// Cas de la méthode GET
    case "GET" :
        /// Récupération des critères de recherche envoyés par le Client
        if (!empty($_GET['id'])){
            $chuck = getById($_GET['id']);
            if(!empty($chuck)){
                deliver_response(201, "getByID réussit", $chuck);
            } else{
                deliver_response(202, "ID Innexistant", $chuck);
            }
        } else {
            $chuck = getAll();
            deliver_response(200, "getAll réussit", $chuck);
        }
        /// Envoi de la réponse au Client
        
        break;
    /// Cas de la méthode POST
    case "POST" :
        /*
        /// Récupération des données envoyées par le Client
        if (!empty($_GET['phrase'])){

        }
        $postedData = file_get_contents('php://input');

        /// Traitement
        /// Envoi de la réponse au Client
        deliver_response(201, "Votre message", NULL);*/
        break;
    /// Cas de la méthode PATCH
    case "PATCH" :
        /*if (!empty($_GET['id']) && !empty($_GET['phrase'])){
            if(edit($_GET['id'], $_GET['phrase'])){
                deliver_response(201, "Patch réussit", getById($_GET['id']));
            } else {
                deliver_response(203, "Patch Echoué (niveau de la requete)", getById($_GET['id']));
            }
            
           
        } else {
            deliver_response(202, "Patch Echec (OPTION MANQUANTE) -> (ID/PHRASE)", NULL);
        }
        /// Récupération des données envoyées par le Client
        $postedData = file_get_contents('php://input');

        /// Traitement
        /// Envoi de la réponse au Client
        deliver_response(200, "Votre message", NULL);*/
        break;
    /// Cas de la méthode GET ALL
    default :
        /// Récupération de l'identifiant de la ressource envoyé par le Client
        if (empty($_GET['id'])){
        /// Traitement
            $chuck = getAll();
            deliver_response(200, "Get All Réussit", $chuck);
        }
        /// Envoi de la réponse au Client
        
        break;
}

/// Envoi de la réponse au Client
function deliver_response($status, $status_message, $data){
    /// Paramétrage de l'entête HTTP, suite
    header("HTTP/1.1 $status $status_message");
    /// Paramétrage de la réponse retournée
    $response['status'] = $status;
    $response['status_message'] = $status_message;
    $response['data'] = $data;
    /// Mapping de la réponse au format JSON
    $json_response = json_encode($response);
    echo $json_response;
}
?>