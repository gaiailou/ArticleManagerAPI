<?php 
require('lib.php');

/// Paramétrage de l'entête HTTP
header("Content-Type:application/json");


/// Identification du type de méthode HTTP envoyée par le client
$http_method = $_SERVER['REQUEST_METHOD'];

switch ($http_method){
    case "POST" :
        // Récupération des données envoyées par le Client en format JSON et les convertir en tableau PHP
        $data = json_decode(file_get_contents('php://input'),TRUE);
        $username = $data['username'];
        $password = $data['password'];
        if(isset($username) && isset($password)){
            $requeteUsername = $linkpdo->prepare('SELECT * FROM utilisateur WHERE username = :username ');
            $requeteUsername->execute(array('username' => $username ));
            $matchingData = $requeteUsername->fetchALL();
            foreach ($matchingData as $row) {
                $matchingUsername = $row['username'];
                $matchingPassword = $row['password'];
                $matchingRole = $row['role'];
            }

            // Vérification si les identifiants sont corrects
            if($matchingUsername == $username && $matchingPassword == $password){
                $header = array('alg'=>'HS256','typ'=>'JWT');
                $payload = array('username'=>$username,'role'=>$matchingRole 'exp'=>(time()+60)); //expiration (60 secondes)
                
                // Génération du JWT
                $jwt = generate_jwt($header, $payload);

                // Vérification si le JWT est valide
                if(is_jwt_valid($jwt)){
                    deliver_response(200, "Clé JWT généré avec succés", $jwt);//OK
                }else{
                    deliver_response(401, "Clé JWT échoué", NULL); //Unauthorized
                }
            }else{
                deliver_response(401, "Erreur : Nom d'utilisateur ou mot de passe incorrect", NULL);//Unauthorized
            }
        }

        // Vérification si le JWT est fourni par le client
        if(!is_null(get_bearer_token())){
            if(is_jwt_valid(get_bearer_token())){
                deliver_response(200, "Clé JWT valide", get_bearer_token());
            }else{
                deliver_response(401, "Erreur : Clé JWT non valide", get_bearer_token());
            }
        }
        break;
    default :
        deliver_response(405, "Méthode non autorisée", NULL); //Method Not Allowed
        break;
}
?>