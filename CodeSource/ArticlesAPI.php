<?php
//Verifier le role dans le token avant
$bearer_token='';
//recherche token
$bearer_token=get_bearer_token();

//si token valid, traitement requete
if(is_jwt_valid($bearer_token)){
    ...
}

?>