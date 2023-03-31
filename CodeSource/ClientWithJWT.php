<?php
$urlAuth='http://localhost/ArticleManagerAPI/CodeSource/authAPI.php';
$urlArticle='http://localhost/ArticleManagerAPI/CodeSource/articlesAPI.php';

$username=$_POST['username'];
$data = array("username" => $username, "password" => $_POST['password']);
$data_string = json_encode($data);

///Connexion rapide 
/*
$jsonModerator='{"username":"DarkModerator","password":"DARKMDP"}';
$jsonPublisher='{"username":"Bob","password":"BOBMDP"}';
$jsonPublisher='{"username":"Camille58","password":"58MDP"}';
$data_string=$jsonPublisher;
*/
//echo 'Les données connexion :'.$data_string;
$result = file_get_contents(
    $urlAuth,
     null,
     stream_context_create(array(
    'http' => array('method' => 'POST',
     'content' => $data_string,
     'header' => array('Content-Type: application/json'."\r\n"
     .'Content-Length: '.strlen($data_string)."\r\n"))))
    );
    /// Dans tous les cas, affichage des résultats
    //echo '<pre>' .'Demande de création du JWT'. htmlspecialchars($result) . '</pre>';
 ////////////////// Cas des méthodes GET et DELETE //////////////////
 $result=json_decode($result,true,512,JSON_THROW_ON_ERROR);
 $jwt = $result['data'];

if(isset($_POST['submitBTN'])) {
    switch ($_POST['submitBTN']) {
        case 'Supprimer':
            // Récupération de l'ID de la phrase à supprimer
            $ContenuId = $_GET['Id_article'];
    
            // Définition de l'URL pour la requête DELETE
            $deleteUrl = $urlArticle.'?Id_article='.$ContenuId;
    
            // Envoi de la requête DELETE
            $options = array(
                'http' => array(
                    'header'  => "Authorization: Bearer " . $jwt,
                    'method'  => 'GET'
                )
            );
            
            $context = stream_context_create($options);
            
           $result = file_get_contents($urlArticle, false, $context);
            $result = file_get_contents($deleteUrl,
                                        null,
                                        stream_context_create(array(
                                            'http' => array(
                                                'header'  => "Authorization: Bearer " . $jwt,
                                                'method'  => 'DELETE'
                                            )
                                        )));
    
            break;
        case 'Ajouter':
            // Récupération des données du formulaire
            $Id_article = $_POST['Id_article'];
            $Contenu = $_POST['Contenu'];
    
            // Déclaration des données à envoyer au serveur
            $data = array("Id_article" => $Id_article,"Contenu" => $Contenu);
            $data_string = json_encode($data);
    
            // Envoi de la requête
            $result = file_get_contents($urlArticle,
                                            null,
                                            stream_context_create(array(
                                            'http' => array(
                                            'method' => 'POST',
                                            'content' => $data_string,
                                            'header' => "Authorization: Bearer " . $jwt . "\r\n" .
                                                        "Content-Type: application/json\r\n" .
                                                        "Content-Length: " . strlen($data_string) . "\r\n")))
                                            );
            break;
        case 'Like':
            // Récupération des données du formulaire
            $Id_article = $_GET['Id_article'];
            $Est_like = 1;
            $postUrl = $urlArticle . '?Id_article=' . $Id_article;
    
            // Déclaration des données à envoyer au serveur
            $data = array("Id_article" => $Id_article,"Est_like" => $Est_like);
            $data_string = json_encode($data);
    
            // Envoi de la requête
            $result = file_get_contents($postUrl,
                                            null,
                                            stream_context_create(array(
                                            'http' => array(
                                            'method' => 'PATCH',
                                            'content' => $data_string,
                                            'header' => "Authorization: Bearer " . $jwt . "\r\n" .
                                                        "Content-Type: application/json\r\n" .
                                                        "Content-Length: " . strlen($data_string) . "\r\n")))
                                            );
            break;
        case 'Dislike':
            // Récupération des données du formulaire
            $Id_article = $_GET['Id_article'];
            $Est_like = 0;
            $postUrl = $urlArticle . '?Id_article=' . $Id_article;
    
            // Déclaration des données à envoyer au serveur
            $data = array("Id_article" => $Id_article,"Est_like" => $Est_like);
            $data_string = json_encode($data);
    
            // Envoi de la requête
            $result = file_get_contents($postUrl,
                                            null,
                                            stream_context_create(array(
                                            'http' => array(
                                            'method' => 'PATCH',
                                            'content' => $data_string,
                                            'header' => "Authorization: Bearer " . $jwt . "\r\n" .
                                                        "Content-Type: application/json\r\n" .
                                                        "Content-Length: " . strlen($data_string) . "\r\n")))
                                            );
            break;
        case 'Annuler mon vote':
            // Récupération des données du formulaire
            $Id_article = $_GET['Id_article'];
            $Est_like = NULL;
            $postUrl = $urlArticle . '?Id_article=' . $Id_article;
    
            // Déclaration des données à envoyer au serveur
            $data = array("Id_article" => $Id_article,"Est_like" => $Est_like);
            $data_string = json_encode($data);
    
            // Envoi de la requête
            $result = file_get_contents($postUrl,
                                            null,
                                            stream_context_create(array(
                                            'http' => array(
                                            'method' => 'PATCH',
                                            'content' => $data_string,
                                            'header' => "Authorization: Bearer " . $jwt . "\r\n" .
                                                        "Content-Type: application/json\r\n" .
                                                        "Content-Length: " . strlen($data_string) . "\r\n")))
                                            );
            break;
    }
}

 $options = array(
     'http' => array(
         'header'  => "Authorization: Bearer " . $jwt,
         'method'  => 'GET'
     )
 );
 
 $context = stream_context_create($options);
 
$result = file_get_contents($urlArticle, false, $context);
//echo '<pre>' .'Resultat du GET :'. htmlspecialchars($result) . '</pre>';
try{
    $result=json_decode($result,true);
} catch (JsonException $e) {
    // Gestion des erreurs de décodage JSON
    echo "Erreur de décodage JSON : " . $e->getMessage();
}
$result=json_decode($result['data'],true);

?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="style.css" media="screen" type="text/css" />
        <title>Client avec JWT</title>
    </head>
    <body>
    <header>
        <h1>Bonjour <?php echo $username; ?></h1>
    </header>
    <h2>Joli tableau de moderateur ou publisher</h2>
    <table>
            <tr>
                <th>Id_article</th>
                <th>Date_publication</th>
                <th>Contenu</th>
                <th>Publisher</th>
                <th>Liste des likes</th>
                <th>Liste des dislikes</th>
                <th>Nombre_likes</th>
                <th>Nombre_dislikes</th>
                <th>Vote</th>
                <th>Modifier</th>
                <th>Supprimer</th>
            </tr>
        <?php foreach($result as $key=>$article) { ?>
            <tr>
                <td><?php echo $article['Id_article']; ?></td>
                <td><?php echo $article['Date_publication']; ?></td>
                <td><?php echo $article['Contenu']; ?></td>
                <td><?php echo $article['Publisher']; ?></td>
                <td><?php echo $article['Likes']; ?></td>
                <td><?php echo $article['Dislikes']; ?></td>
                <td><?php echo $article['Nombre_likes']; ?></td>
                <td><?php echo $article['Nombre_dislikes']; ?></td>
                <td>
                    <form method="POST" action="?Id_article=<?php echo $article['Id_article']; ?>">
                        <input type='submit' name='submitBTN' value='Like' >
                        <input type='submit' name='submitBTN' value='Dislike' >
                        <input type='submit' name='submitBTN' value='Annuler mon vote' >
                    </form>
                </td>
                <td>Option indisponible</td>
                <td>
                    <form method="POST" action="?Id_article=<?php echo $article['Id_article']; ?>">
                        <input type='submit' name='submitBTN' value='Supprimer' >
                    </form>
                </td>
            </tr>
        <?php } ?>
    </table>
    <h2>Ajouter un nouvel article</h2>

    <form method="POST" action="">
        <label for="Id_article">Id_article:</label>
        <input type="text" id="Id_article" name="Id_article">
        <label for="Contenu">Contenu:</label>
        <input type="text" id="Contenu" name="Contenu">
        <input type="submit" name="submitBTN" value="Ajouter">
    </form>
    <footer>
        <p>Site réalisé par Gaïa et Chloé.</p>
    </footer>
    </body>
</html>