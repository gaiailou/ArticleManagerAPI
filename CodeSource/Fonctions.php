<?php
    require ('ConnexionBD.php');

    function genPasswordHash($mdpClair){
        return password_hash($mdpClair, PASSWORD_DEFAULT, ["cost" => 12]);
    }

    function newUser($user,$mdp){
        $linkpdo = connexionDB();
        if(getUser($user)['User'][0] == NULL){
            $new = $linkpdo->prepare('INSERT INTO User(User,passwordKey) VALUES (:User,:Mdp)');
            return($new->execute(array('User' => $user, 'Mdp' => genPasswordHash($mdp))));
        }
    }

    function getUser($User){
        $linkpdo = connexionDB();
        $getUser  = $linkpdo->prepare('SELECT * FROM User WHERE User = :User');
        $getUser->execute(array('User' => $User));
        $User = $getUser->fetchALL();
        return $User;
    }

    function getById_article($Id_article){
        $linkpdo = connexionDB();
        $recupId_article = $linkpdo->prepare('SELECT * FROM article WHERE Id_article = :Id_article');
        $recupId_article->execute(array('Id_article' => $Id_article));
        $chuck = $recupId_article->fetchALL();
        return $chuck;
    }

    function getAll(){
        $linkpdo = connexionDB();

        $recupall = $linkpdo->prepare('SELECT * FROM article');
        if($recupall->execute()){
            $chuck = $recupall->fetchALL();
            return $chuck;

        } else {
            return "Erreur d'execution de la fonction getAll";
        }  
    }

    function edit($Id_article, $phrase){
        $linkpdo = connexionDB();
        $edit = $linkpdo->prepare('UPDATE article SET phrase = :phrase WHERE Id_article=:Id_article');
        return($edit->execute(array('phrase' => $phrase, 'Id_article' => $Id_article)));
    }

    function addPhrase($phrase){
        $linkpdo = connexionDB();
        $edit = $linkpdo->prepare('insert article SET phrase = :phrase WHERE Id_article=:Id_article');

    }
?>
