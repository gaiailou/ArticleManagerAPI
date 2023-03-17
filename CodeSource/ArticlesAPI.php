<?php
require('lib.php');


/// Paramétrage de l'entête HTTP (pour la réponse au Client)
header("Content-Type:application/json");

/// Identification du type de méthode HTTP envoyée par le client
$http_method = $_SERVER['REQUEST_METHOD'];
switch ($http_method){
    /// Cas de la méthode GET
    case "GET" :
        /// Récupération des critères de recherche envoyés par le Client
        if (!empty($_GET['Id_article'])){
            $requeteId_article = $linkpdo->prepare('SELECT * FROM article WHERE Id_article = :Id_article');
            $requeteId_article->execute(array('Id_article' => $Id_article));
            $matchingData = $requeteId_article->fetchALL();
            $blob=json_encode($matchingData);
        }else{
            echo('je suis la');
            $requeteArticles = $linkpdo->prepare('SELECT * FROM article');
            $requeteArticles->execute();
            $matchingData = $requeteArticles->fetchALL();
            //var_dump($matchingData);
            $blob=array();
            $blob=json_encode($matchingData,true);
            //var_dump($blob);
        }
        /// Envoi de la réponse au Client
        if ($blob){
            deliver_response(200, "OK : données récupérées", $blob);
        }else{
            deliver_response(400, "Error : vide", $blob);
        }
        break;

    /// Cas de la méthode POST
    case "POST" :
        /// Récupération des données envoyées par le Client
        $postedData = file_get_contents('php://input');

        /// Traitement
        /// Envoi de la réponse au Client
        deliver_response(201, "Votre message", NULL);
        break;

    /// Cas de la méthode PUT
    case "PUT" :
        /// Récupération des données envoyées par le Client
        $postedData = file_get_contents('php://input');

        /// Traitement
        /// Envoi de la réponse au Client
        deliver_response(200, "Votre message", NULL);
        break;

    /// Cas de la méthode DELETE
    default :
        /// Récupération de l'identifiant de la ressource envoyé par le Client
        if (!empty($_GET['mon_id'])){
        /// Traitement
        }
        /// Envoi de la réponse au Client
        deliver_response(200, "Votre message", NULL);
        break;
}

?>