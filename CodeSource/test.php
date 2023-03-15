<?php

// Cas des méthodes GET et DELETE
$result = file_get_contents('http://localhost/ArticleManagerAPI/CodeSource/ArticlesAPI.php',
    false,
    stream_context_create(array('http' => array('method' => 'GET')))
);

// Cas des méthodes POST et PUT
$data = array("title" => "Nouvel article", "content" => "Contenu de l'article");
$data_string = json_encode($data);
$result = file_get_contents(
    'http://localhost/ArticleManagerAPI/CodeSource/ArticlesAPI.php',
    null,
    stream_context_create(array(
        'http' => array(
            'method' => 'POST',
            'content' => $data_string,
            'header' => array(
                'Content-Type: application/json'."\r\n".
                'Content-Length: '.strlen($data_string)."\r\n"
            )
        )
    ))
);

// Affichage des résultats
echo '<pre>' . htmlspecialchars($result) . '</pre>';