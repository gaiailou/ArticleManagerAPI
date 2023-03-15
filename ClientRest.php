<?php

$url='http://www.kilya.biz/api/chuckn_facts.php';

////////////////// Cas des méthodes POST et PUT //////////////////
if(isset($_POST['submitBTN'])) {
switch ($_POST['submitBTN']) {
    case 'Supprimer':
        // Récupération de l'ID de la phrase à supprimer
        $factId = $_GET['id'];

        // Définition de l'URL pour la requête DELETE
        $deleteUrl = $url . '?id=' . $factId;

        // Envoi de la requête DELETE
        $result = file_get_contents($deleteUrl,
                                    null,
                                    stream_context_create(array(
                                        'http' => array('method' => 'DELETE'))));

        break;
    case 'Ajouter':
        // Récupération des données du formulaire
        $fact = $_POST['fact'];

        // Déclaration des données à envoyer au serveur
        $data = array("phrase" => $fact);
        $data_string = json_encode($data);

        // Envoi de la requête
        $result = file_get_contents($url,
                                        null,
                                        stream_context_create(array(
                                        'http' => array('method' => 'POST', // ou PUT
                                        'content' => $data_string,
                                        'header' => array('Content-Type: application/json'."\r\n"
                                        .'Content-Length: '.strlen($data_string)."\r\n"))))
                                        );
        break;
    case 'Modifier':
        // Récupération des données du formulaire
        $factId = $_GET['id'];
        $fact = $_POST['fact'];

        // Déclaration des données à envoyer au serveur
        $data = array("phrase" => $fact);
        $data_string = json_encode($data);

        // Définition de l'URL pour la requête PUT
        $putUrl = $url . '?id=' . $factId;

        // Envoi de la requête PUT
        $result = file_get_contents($putUrl,
                                        null,
                                        stream_context_create(array(
                                        'http' => array('method' => 'PUT',
                                        'content' => $data_string,
                                        'header' => array('Content-Type: application/json'."\r\n"
                                        .'Content-Length: '.strlen($data_string)."\r\n"))))
                                        );
        break;
    case 'Enregistrer':
        echo 'oui';
}
}
////////////////// Cas des méthodes GET et DELETE //////////////////
$result = file_get_contents($url,
                    false,
                    stream_context_create(array('http' => array('method' => 'GET'))) // ou DELETE
                    );

/// Dans tous les cas, affichage des résultats
/*echo '<pre>' . htmlspecialchars($result) . '</pre>';*/

$data = json_decode($result, true);

if ($data['status'] == 200) {
    $facts = $data['data'];
?>
<h1>Chuck Norris Facts</h1>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Phrase</th>
            <th>Vote</th>
            <th>Date d'ajout</th>
            <th>Date de modification</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($facts as $fact) { ?>
            <tr>
                <td><?php echo $fact['id']; ?></td>
                <td>
                    <?php if(isset($_GET['edit']) && $_GET['edit'] == $fact['id']) { ?>
                        <form method="POST" action="">
                            <input type="text" id="fact" name="fact" value="<?php echo $fact['phrase']; ?>">
                            <input type="submit" name="submitBTN" value="Enregistrer">
                            <a href="?">Annuler</a>
                        </form>
                    <?php } else { ?>
                        <?php echo $fact['phrase']; ?>
                    <?php } ?>
                </td>
                <td><?php echo $fact['vote']; ?></td>
                <td><?php echo $fact['date_ajout']; ?></td>
                <td><?php echo $fact['date_modif']; ?></td>
                <td>
                    <?php if(isset($_GET['edit']) && $_GET['edit'] == $fact['id']) { ?>
                        <a href="?">Annuler</a>
                    <?php } else { ?>
                        <a href="?id=<?php echo $fact['id']; ?>&edit=<?php echo $fact['id']; ?>">Modifier</a>
                        <form method="POST" action="?id=<?php echo $fact['id']; ?>">
                            <input type="submit" name="submitBTN" value="Supprimer">
                        </form>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<h2>Ajouter un nouveau fait</h2>

    <form method="POST" action="">
      <label for="fact">Fait :</label>
      <input type="text" id="fact" name="fact">
      <input type="submit" name="submitBTN" value="Ajouter">
    </form>

<?php
} else {
    echo "Erreur lors de la récupération des données.";
}
?>