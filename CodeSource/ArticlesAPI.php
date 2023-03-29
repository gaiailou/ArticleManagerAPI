<?php
require('lib.php');

/// Paramétrage de l'entête HTTP (pour la réponse au Client)
header("Content-Type:application/json");

$role=extract_user_role(get_bearer_token());
if ($role = 'publisher') {
    $publisher=extract_username(get_bearer_token());
}

/// Identification du type de méthode HTTP envoyée par le client
$http_method = $_SERVER['REQUEST_METHOD'];
switch ($http_method){

    /// Cas de la méthode POST
    case "POST" :
        if ($role = 'publisher') {
            /// Récupération des données envoyées par le Client
            $postedData = file_get_contents('php://input');
            if (!empty($_GET['Id_article'])){

            }
            /// Traitement
            $blob=json_decode($postedData,true);
            $Id_article = $blob['Id_article'];
            $Date_publication = $blob['Date_publication'];
            $Contenu = $blob['Contenu'];
            $requeteId_article = $linkpdo->prepare('SELECT * FROM article WHERE Id_article = :Id_article');
            $requeteId_article->execute(array(':Id_article' => $Id_article));
            $matchingData = $requeteId_article->fetchALL();
            if(!$matchingData){
                $req = $linkpdo->prepare('INSERT INTO article (Id_article,Date_publication,Contenu,Publisher) 
                VALUES (:Id_article,:Date_publication,:Contenu,:Publisher)');
                $req->execute(array('Id_article' => $Id_article,'Date_publication' => $Date_publication,'Contenu' => $Contenu, 'Publisher' => $Publisher));    
                deliver_response(200, "OK : données ajoutées", NULL);
            }else{
                deliver_response(401, "Error : Identifiant déjà existant", NULL);
            }
        }else{
            deliver_response(401, "Error : Vous n'avez pas le role necessaire pour cette méthode", NULL);
        }
        break;

    /// Cas de la méthode PUT
    case "PUT" :
        if ($role = 'publisher') {
            /// Récupération des données envoyées par le Client
            $postedData = file_get_contents('php://input');

            /// Traitement
            $blob=json_decode($postedData,true);
            $Id_article = $blob['Id_article'];
            $Date_publication = $blob['Date_publication'];
            $Contenu = $blob['Contenu'];
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
                deliver_response(401, "Error : Pas d'article trouvé pour l'id donné", NULL);
            }
        }else{
            deliver_response(401, "Error : Vous n'avez pas le role necessaire pour cette méthode", NULL);
        }
        break;

    case "DELETE" : 
        if ($role = 'publisher' or $role = 'moderator' ) {
            /// Récupération des critères de recherche envoyés par le Client
            if (!empty($_GET['Id_article'])){
                $Id_article=$_GET['Id_article'];
                $requeteId_article = $linkpdo->prepare('SELECT * FROM article WHERE Id_article = :Id_article');
                $requeteId_article->execute(array(':Id_article' => $Id_article));
                $matchingData = $requeteId_article->fetchALL();
                if($matchingData){
                    $requeteId_article = $linkpdo->prepare('DELETE FROM article WHERE Id_article = :Id_article');
                    $requeteId_article->execute(array(':Id_article' => $Id_article));
                    deliver_response(200, "OK : article supprimé", NULL);
                }else{
                    deliver_response(401, "Error : Pas d'article trouvé pour l'id donné", NULL);
                }
            }else{
                deliver_response(400, "Error : DELETE ne fonctionne pas sans identifiant", NULL);
            }
        }else{
            deliver_response(401, "Error : Vous n'avez pas le role necessaire pour cette méthode", NULL);
        }
        break;

    /// Cas de la méthode GET
    default :
        /// Récupération des critères de recherche envoyés par le Client
        if (!empty($_GET['Id_article'])){
            $Id_article=$_GET['Id_article'];
            if ($role = 'publisher' or $role = 'moderator') {
                $requeteId_article = $linkpdo->prepare('select COUNT(Est_like) as Nombre_like, article.* 
                                                        FROM interagir , article 
                                                        where interagir.Id_article = article.Id_article 
                                                        and est_like = 1');
            }else{
                $requeteId_article = $linkpdo->prepare('SELECT date_publication, contenu, publisher FROM article WHERE Id_article = :Id_article');
            }
            $requeteId_article->execute(array(':Id_article' => $Id_article));
            $matchingData = $requeteId_article->fetchALL();
            $blob=array();
            $blob=json_encode($matchingData,true);
        }else{
            if ($role = 'publisher' or $role = 'moderator') {
                $requeteId_article = $linkpdo->prepare('select COUNT(Est_like) as Nombre_like, article.* 
                                                        FROM interagir , article 
                                                        where est_like = 1');
            }else{
                $requeteId_article = $linkpdo->prepare('SELECT date_publication, contenu, publisher FROM article');
            }
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