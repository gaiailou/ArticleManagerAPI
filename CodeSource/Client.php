<?php
$urlAuth='http://localhost/ArticleManagerAPI/CodeSource/authAPI.php';
$urlArticle='http://localhost/ArticleManagerAPI/CodeSource/articlesAPI.php';

$jsonModerator='{"username":"DarkModerator","password":"DARKMDP"}';
$jsonPublisher='{"username":"Bob","password":"BOBMDP"}';
$data_string=$jsonModerator;
echo 'Les données connexion :'.$data_string;

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
    echo '<pre>' .'Demande de création du JWT'. htmlspecialchars($result) . '</pre>';
 ////////////////// Cas des méthodes GET et DELETE //////////////////
 $result=json_decode($result,true,512,JSON_THROW_ON_ERROR);
 $jwt = $result['data']; 

if(!isset($_POST['submitBTN'])) {
    switch ($_POST['submitBTN']) {
        case 'Supprimer':
            // Récupération de l'ID de la phrase à supprimer
            $ContenuId = $_GET['Id_article'];
    
            // Définition de l'URL pour la requête DELETE
            $deleteUrl = $urlArticle . '?Id_article=' . $ContenuId;
    
            // Envoi de la requête DELETE
            $result = file_get_contents($deleteUrl,
                                        null,
                                        stream_context_create(array(
                                            'http' => array('method' => 'DELETE'))));
    
            break;
        case 'Ajouter':
            // Récupération des données du formulaire
            $Id_article = $_POST['Id_article'];
            $Contenu = $_POST['Contenu'];
    
            // Déclaration des données à envoyer au serveur
            $data = array("phrase" => $Contenu);
            $data_string = json_encode($data);
    
            // Envoi de la requête
            $result = file_get_contents($urlArticle,
                                            null,
                                            stream_context_create(array(
                                            'http' => array('method' => 'POST',
                                            'content' => $data_string,
                                            'header' => array('Content-Type: application/json'."\r\n"
                                            .'Content-Length: '.strlen($data_string)."\r\n"))))
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
echo '<pre>' .'Resultat du GET :'. htmlspecialchars($result) . '</pre>';
try{
    $result=json_decode($result,true);
} catch (JsonException $e) {
    // Gestion des erreurs de décodage JSON
    echo "Erreur de décodage JSON : " . $e->getMessage();
}
$result=json_decode($result['data'],true);

?>
<html>
    <h2>Joli tableau de moderateur</h2>
    <table>
            <tr>
                <th>Id_article</th>
                <th>Date_publication</th>
                <th>Contenu</th>
                <th>Publisher</th>
                <th>Nombre_like</th>
                <th>Modifier</th>
                <th>Supprimer</th>
            </tr>
        <?php foreach($result as $key=>$article) { ?>
            <tr>
                <td><?php echo $article['Id_article']; ?></td>
                <td><?php echo $article['Date_publication']; ?></td>
                <td><?php echo $article['Contenu']; ?></td>
                <td><?php echo $article['Publisher']; ?></td>
                <td><?php echo $article['Nombre_like']; ?></td>
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
</html>
<?php echo 'fin';?>