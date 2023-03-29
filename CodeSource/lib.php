<?php
require('jwt_utils.php');
require('connexionBD.php');

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

// fonction pour extraire le role de l'utilisateur du JWT
function extract_user_role($jwt) {
	$secret = 'secret';

    if (!$jwt) {
        return null;
    }

    // check si JWT valide
    $is_valid = is_jwt_valid($jwt, $secret);
    if ($is_valid) {
        // decode le payload
        $jwt_parts = explode('.', $jwt);
        $payload = base64_decode($jwt_parts[1]);
        $decoded_payload = json_decode($payload);

        // extrait le role
        $user_role = $decoded_payload->role;

        return $user_role;
    }else{
        return null;
    }
}

// fonction pour extraire username de l'utilisateur du JWT
function extract_username($jwt) {
    $secret = 'secret';

    if (!$jwt) {
        return null;
    }

    // vérifie si le JWT est valide
    $is_valid = is_jwt_valid($jwt, $secret);
    if (!$is_valid) {
        return null;
    }

    // décode le payload
    $jwt_parts = explode('.', $jwt);
    $payload = base64_decode($jwt_parts[1]);
    $decoded_payload = json_decode($payload);

    // extrait le nom d'utilisateur
    $username = $decoded_payload->username;

    return $username;
}
?>