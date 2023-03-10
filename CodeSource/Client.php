<?php

 ////////////////// Cas des méthodes GET et DELETE //////////////////
 $url='http://127.0.0.1:3306/ArticleManagerAPI/APIREST/api.php';
 $result = file_get_contents($url,
 false,
 stream_context_create(array('http' => array('method' => 'GET'))) // ou DELETE
 );
 echo '<pre>' . htmlspecialchars($result) . '</pre>';
$result=json_decode($result,true,512,JSON_THROW_ON_ERROR);

?>
<html>
    <table>
    <?php
        echo"
            <tr>
                <th>Id_article</th>
                <th>Date_publication</th>
                <th>Contenu</th>
                <th>Publisher</th>
                <th>Modifier</th>
                <th>Supprimer</th>
            </tr>
        ";
        foreach($result['data'] as $key=>$chuck){
            echo "
            <tr>
                <td>".$chuck['Id_article']."</td>
                <td>".$chuck['Date_publication']."</td>
                <td>".$chuck['Contenu']."</td>
                <td>".$chuck['Publisher']."</td>
                <td><a href='#'>Modifier</a></td>
                <td>
                    <form>
                        <input type='submit' name='supprimer' value='Supprimer' >
                    </form>
                </td>
            </tr>";
        }
    ?>
    </table>
</html>