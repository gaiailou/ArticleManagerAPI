<?php
    
    // Se connecter à la base de données
    require ('../CodeSource/ConnexionBD.php');

    /// Paramétrage de l'entête HTTP (pour la réponse au Client)
    header("Content-Type:application/json");

    ///Préparation de la requête sans les variables (marqueurs : nominatifs)
    /*$requeteGETbyID = $linkpdo->prepare('   SELECT *
                                            FROM article
                                            WHERE Id_article = :Id_article');*/

    $requeteGETALL = $linkpdo->prepare('    SELECT *
                                            FROM article');
    
    /*$requetePOST = $linkpdo-> prepare("     INSERT INTO article (phrase)
                                            VALUES 	(:p_phrase)");

    $requetePUT = $linkpdo-> prepare("      ");*/

    $request_method = $_SERVER["REQUEST_METHOD"];
    switch($request_method)
	{
		case 'GET':
			/// Récupération des critères de recherche envoyés par le Client
            //FIND BY ID
            /*if (!empty($_GET['Id_article'])){
                $Id_article = $_GET['Id_article'];
                $requeteGETbyID->bindParam(':Id_article',$Id_article);
                $requeteGETbyID->execute();
                $matchingData = $requeteGETbyID->fetchAll();
            }*/

            //GET ALL
            if (empty($_GET['Id_article'])){
                $requeteGETALL->execute();
                $matchingData = $requeteGETALL->fetchAll();
            }
            /// Envoi de la réponse au Client
            deliver_response(200, "Votre message", $matchingData);
		default:
			// Requête invalide
			header("HTTP/1.0 405 Méthode non authorisé");
			break;
	}

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