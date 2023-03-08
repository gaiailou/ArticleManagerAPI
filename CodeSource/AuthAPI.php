<?php

include 'jwt_utils.php';
    
$data = (array) json_decode(file_get_contents('php://input'), TRUE);

if (isValidUser($data['username'], $data['password'])){
    $username = $data['username'];
    $role = $data['role'];

    $headers = array('alg'=>'HS256','typ'=>'JWT');
    $payload = array('username'=>$username,'role'=>$role,'exp'=>(time()+60));

    $jwt = generate_jwt($headers,$payload)
}
?>