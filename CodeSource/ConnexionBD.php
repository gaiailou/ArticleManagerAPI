<?php
$db_username = 'root';
$db_password = '';
$db_name = 'gestionarticles';
$db_host = '127.0.0.1:3306';

try {
    $linkpdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_username, $db_password);
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage()); 
}
?>