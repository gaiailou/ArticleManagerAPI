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
            $Id_article=$_GET['Id_article'];
            $requeteId_article = $linkpdo->prepare('SELECT * FROM article WHERE Id_article = :Id_article');
            $requeteId_article->execute(array(':Id_article' => $Id_article));
            $matchingData = $requeteId_article->fetchALL();
            $blob=array();
            $blob=json_encode($matchingData);
        }else{
            $requeteArticles = $linkpdo->prepare('SELECT * FROM article');
            $requeteArticles->execute();
            $matchingData = $requeteArticles->fetchALL();
            //var_dump($matchingData);
            $blob=array();
            $blob=json_encode($matchingData,TRUE);
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
        // Vérification si le JWT est fourni par le client
        if(!is_null(get_bearer_token())){
            if(is_jwt_valid(get_bearer_token())){
                deliver_response(200, "Clé JWT valide", get_bearer_token());
            }else{
                deliver_response(401, "Erreur : Clé JWT non valide", get_bearer_token());
            }
        }
        /// Récupération des données envoyées par le Client
        $postedData = file_get_contents('php://input');
        $blob=json_decode($postedData,true);
        $Id_article = $blob['Id_article'];
        $Date_publication = $blob['Date_publication'];
        $Contenu = $blob['Contenu'];
        $Publisher = $blob['Publisher'];
        $req = $linkpdo->prepare('INSERT INTO article (Id_article,Date_publication,Contenu,Publisher) 
        VALUES (:Id_article,:Date_publication,:Contenu,:Publisher)');
        $req->execute(array('Id_article' => $Id_article,'Date_publication' => $Date_publication,'Contenu' => $Contenu, 'Publisher' => $Publisher));
        
        /// Envoi de la réponse au Client
        deliver_response(201, "Askip ca marche", NULL);
        break;

    /// Cas de la méthode PUT
    case "PUT" :
        /// Récupération des données envoyées par le Client
        $postedData = file_get_contents('php://input');

        /// Traitement
        $blob=json_decode($postedData,true);
        $Id_article = $blob['Id_article'];
        $Date_publication = $blob['Date_publication'];
        $Contenu = $blob['Contenu'];
        $Publisher = $blob['Publisher'];
        $requeteId_article = $linkpdo->prepare('SELECT * FROM article WHERE Id_article = :Id_article');
        $requeteId_article->execute(array(':Id_article' => $Id_article));
        $matchingData = $requeteId_article->fetchALL();
        if($matchingData){
            $req = $linkpdo->prepare('UPDATE article set Id_article = :Id_article,
            Date_publication = :Date_publication,Contenu = :Contenu,Publisher = :Publisher ');
            $req->execute(array('Id_article' => $Id_article,'Date_publication' => $Date_publication,'Contenu' => $Contenu,
             'Publisher' => $Publisher));
             deliver_response(200, "Askip ca marche", NULL);
        }else{
            deliver_response(401, "Erreur : Pas d'article trouvé pour l\'id donné", NULL);
        }
        /// Envoi de la réponse au Client
        deliver_response(200, "Askip ca marche", NULL);
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