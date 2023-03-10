<?php

require 'jwt_utils.php';
require 'connexionbd.php';

function isValidUser($username,$password){

        //AVEC PDO
        if ($username !== "" && $password !== "") {
            ///Préparation de la requête sans les variables (marqueurs : nominatifs)
    
            $requete = $linkpdo->prepare('SELECT mot_de_passe FROM utilisateur where nom_utilisateur = :user');
    
            ///Liens entre variables PHP et marqueurs
            $requete->execute([
                'user' => $username
            ]);
    
            $mdphash = $requete->fetch();
    
            if (/*password_verify()*/$password = $mdphash[0]) // nom d'utilisateur et mot de passe correctes
            {
                $_SESSION['connecte'] = $username;
                //header('Location: ../page/accueil.php');
                $data = (array) json_decode(file_get_contents('php://input'), TRUE);
                $username = $data['username'];
                $role = $data['role'];

                $headers = array('alg'=>'HS256','typ'=>'JWT');
                $payload = array('username'=>$username,'role'=>$role,'exp'=>(time()+60));

                $jwt = generate_jwt($headers,$payload)
            } else {
                header('Location: login.php?erreur=1'); // utilisateur ou mot de passe incorrect
            }
        } else {
            header('Location: login.php?erreur=2'); // utilisateur ou mot de passe vide
        }
}

/*
$data = (array) json_decode(file_get_contents('php://input'), TRUE);

if (isValidUser($data['username'], $data['password'])){
    $username = $data['username'];
    $role = $data['role'];

    $headers = array('alg'=>'HS256','typ'=>'JWT');
    $payload = array('username'=>$username,'role'=>$role,'exp'=>(time()+60));

    $jwt = generate_jwt($headers,$payload)
} */
?>