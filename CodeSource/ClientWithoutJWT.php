<?php
$urlAuth='http://localhost/ArticleManagerAPI/CodeSource/authAPI.php';
$urlArticle='http://localhost/ArticleManagerAPI/CodeSource/articlesAPI.php';


 $options = array(
     'http' => array(
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
        <title>Client sans JWT</title>
    </head>
    <body>
    <header>
        <a href="./ClientConnexion.php" target="_blank">Se connecter</a>
    </header>
        <h2>Liste des articles</h2>
        <table>
                <tr>
                    <th>Date_publication</th>
                    <th>Contenu</th>
                    <th>Publisher</th>
                    <th>Nombre_likes</th>
                    <th>Nombre_dislikes</th>
                </tr>
            <?php foreach($result as $key=>$article) { ?>
                <tr>
                    <td><?php echo $article['Date_publication']; ?></td>
                    <td><?php echo $article['Contenu']; ?></td>
                    <td><?php echo $article['Publisher']; ?></td>
                    <td><?php echo $article['Nombre_likes']; ?></td>
                    <td><?php echo $article['Nombre_dislikes']; ?></td>
                </tr>
            <?php } ?>
        </table>
    </body>
    <footer>
        <p>Site réalisé par Gaïa et Chloé.</p>
    </footer>
</html>