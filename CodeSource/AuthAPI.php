<?php 
require('jwt_utils.php');


 /// Paramétrage de l'entête HTTP (pour la réponse au Client)
 header("Content-Type:application/json");


 /// Identification du type de méthode HTTP envoyée par le client
 $http_method = $_SERVER['REQUEST_METHOD'];
 switch ($http_method){
    // Cas de la méthode GET
    // Cas de la méthode POST
    case "POST" :
        // Récupération des données envoyées par le Client en format JSON et les convertir en tableau PHP
        $postedData = (array) json_decode(file_get_contents('php://input'),TRUE);

        // Vérification si les données envoyées par le client contiennent les champs 'user' et 'mdp'
        if(isset($postedData['user']) && isset($postedData['mdp'])){
            // Création du payload du JWT avec l'utilisateur et l'expiration (60 secondes)
            $payload = array('user'=>$postedData['user'], 'exp'=>(time()+60));
            // Création de l'en-tête du JWT avec l'algorithme de signature HS256 et le type de JWT
            $header = array('alg'=>'HS256','typ'=>'JWT');
            // Définition du nom d'utilisateur et mot de passe
            $mdp = "admin";
            $user = "admin";
            // Vérification si les identifiants sont corrects
            if($user == $postedData['user'] && $mdp == $postedData['mdp']){
                // Génération du JWT avec les informations de l'en-tête et du payload
                $jwt = generate_jwt($header, $payload);
                // Vérification si le JWT est valide
                if(is_jwt_valid($jwt)){
                    // Envoi de la réponse 200 (OK) avec le message de succès et le JWT généré
                    deliver_response(200, "Clé JWT créer avec succés", $jwt);
                }else{
                    // Envoi de la réponse 201 (Created) avec le message d'erreur si le JWT n'est pas valide
                    deliver_response(201, "Clé JWT échoué", NULL);
                }
            }else{
                // Envoi de la réponse 202 (Accepted) avec le message d'erreur si la combinaison utilisateur/mot de passe est incorrecte
                deliver_response(202, "Erreur mauvaise combinaison USER/MDP", NULL);
            }
        }

        // Vérification si le JWT est fourni par le client
        if(!is_null(get_bearer_token())){
            // Vérification si le JWT est valide
            if(is_jwt_valid(get_bearer_token())){
                // Envoi de la réponse 200 (OK) avec le message de succès et le JWT fourni
                deliver_response(200, "Clé JWT valide", get_bearer_token());
            }else{
                // Envoi de la réponse 201 (Created) avec le message d'erreur si le JWT fourni n'est pas valide
                deliver_response(201, "Clé JWT non valide", get_bearer_token());
            }
        }

        // Traitement des données envoyées par le client

        break;
    default :
    /// Récupération de l'identifiant de la ressource envoyé par le Client

        deliver_response(202, "Manque user / mdp ou token pour verifier", NULL);
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