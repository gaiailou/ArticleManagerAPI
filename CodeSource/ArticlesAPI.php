<?php
require('lib.php');


/// Paramétrage de l'entête HTTP (pour la réponse au Client)
header("Content-Type:application/json");

/// Identification du type de méthode HTTP envoyée par le client
$http_method = $_SERVER['REQUEST_METHOD'];
switch ($http_method){

    /// Cas de la méthode POST
    case "POST" :
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
        if(!$matchingData){
            $req = $linkpdo->prepare('INSERT INTO article (Id_article,Date_publication,Contenu,Publisher) 
            VALUES (:Id_article,:Date_publication,:Contenu,:Publisher)');
            $req->execute(array('Id_article' => $Id_article,'Date_publication' => $Date_publication,'Contenu' => $Contenu, 'Publisher' => $Publisher));    
             deliver_response(200, "OK : données ajoutées", NULL);
        }else{
            deliver_response(401, "Erreur : Identifiant déjà existant", NULL);
        }

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
            $req = $linkpdo->prepare('UPDATE article set Date_publication = :Date_publication, Contenu = :Contenu, 
            Publisher = :Publisher where Id_article = :Id_article');
            $req->execute(array('Date_publication' => $Date_publication,'Contenu' => $Contenu,
             'Publisher' => $Publisher,'Id_article' => $Id_article));
             deliver_response(200, "OK : données mises à jour", NULL);
        }else{
            deliver_response(401, "Erreur : Pas d'article trouvé pour l'id donné", NULL);
        }
        break;

    case "DELETE" : 
        /// Récupération des critères de recherche envoyés par le Client
        if (!empty($_GET['Id_article'])){
            $Id_article=$_GET['Id_article'];
            $requeteId_article = $linkpdo->prepare('SELECT * FROM article WHERE Id_article = :Id_article');
            $requeteId_article->execute(array(':Id_article' => $Id_article));
            deliver_response(200, "OK : article supprimé", NULL);
        }else{
            deliver_response(400, "Error : DELETE ne fonctionne pas sans identifiant", NULL);
        }
        break;

    /// Cas de la méthode GET
    default :
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
}

?>