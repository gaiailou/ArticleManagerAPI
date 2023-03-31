# ArticleManagerAPI
Projet de conception et développement d’API REST pour la gestion d’articles

## Informations utiles à l’évaluation de notre projet
(Ex : - URL d’accès à notre back-end)

## Documentation de notre API

### Définition d'un article :
Chaque article est défini par un ```IdArticle```, une ```date_de_publication```, un ```contenu``` et un ```publisher```.
Un article peut être liké ou disliké par les publishers uniquement.
Chaque Like est stocker dans la table Interagir avec un ```username```, ```IdArticlee``` et ```est_like``` egale a ```1``` pour un like et ```0``` pour un dislike.

### Définition d'un utilisateur :
Chaque utilisateur est défini par un ```username```, un ```password``` et un ```role```.

#### Contraintes de rôle :
- Le ```moderator``` ne peut pas publier d'articles mais il peut les supprimer.
- Le ```publisher``` peut publier des articles, modifier ceux qu'il a publier,
et interagir avec les articles des autres publishers (like/dislike).
- Une personne non connectée peut voir les articles mais ne peut pas interagir avec.

### Fonctionnalités des méthodes HTTP:
#### Sur l'API authentification :
- ```POST``` avec un json contenant ```username=[valeur]``` et ```password=[texte]```

#### Sur l'API article :
- ```GET``` : affiche tous les articles avec leur date et leur contenu (avec les rôles publisher et moderator, affiche également le publisher et le nombre de like et dislike)
- ```GET``` avec ```?IdArticle=[IdArticle]``` : Affiche l'article pour IdArticle correspondant s'il existe
- ```POST``` avec un json contenant ```Id_article=[valeur]``` et ```Contenu=[texte]```: publier un nouvel article (disponible avec le rôle publisher uniquement)
- ```PUT``` avec ```?IdArticle=[IdArticle]```  avec un json contenant ```Id_article=[valeur]``` et ```Contenu=[texte]```: Modifier un article
- ```PATCH``` avec ```?IdArticle=[IdArticle]``` avec un json contenant ```est_like=[1(like) ou 0(dislike)]``` : Permet de voter pour l'article pour IdArticle correspondant s'il existe (disponible avec le rôle publisher uniquement)
- ```DELETE``` avec ```?IdArticle=[IdArticle]``` : Supprimer un article (disponible avec les rôles publisher et moderator)

### Spécification des API REST du projet :

#### Sur l'API authentification :
switch ($http_method){
    case "POST" :
        // Récupération des données envoyées par le Client en format JSON et les convertir en tableau PHP
        $data = json_decode(file_get_contents('php://input'),TRUE);
        $username = $data['username'];
        $password = $data['password'];
        if(isset($username) && isset($password)){
            $requeteUsername = $linkpdo->prepare('SELECT * FROM utilisateur WHERE username = :username ');
            $requeteUsername->execute(array('username' => $username ));
            $matchingData = $requeteUsername->fetchALL();
            foreach ($matchingData as $row) {
                $matchingUsername = $row['username'];
                $matchingPassword = $row['password'];
                $matchingRole = $row['role'];
            }

            // Vérification si les identifiants sont corrects
            if($matchingUsername == $username && $matchingPassword == $password){
                $header = array('alg'=>'HS256','typ'=>'JWT');
                $payload = array('username'=>$username,'role'=>$matchingRole , 'exp'=>(time()+60*60)); //expiration (60*60 secondes)
                
                // Génération du JWT
                $jwt = generate_jwt($header, $payload);

                // Vérification si le JWT est valide
                if(is_jwt_valid($jwt)){
                    deliver_response(200, "Clé JWT généré avec succés", $jwt);//OK
                }else{
                    deliver_response(401, "Clé JWT échoué", NULL); //Unauthorized
                }
            }else{
                deliver_response(401, "Erreur : Nom d'utilisateur ou mot de passe incorrect", NULL);//Unauthorized
            }
        }

        // Vérification si le JWT est fourni par le client
        if(!is_null(get_bearer_token())){
            if(is_jwt_valid(get_bearer_token())){
                deliver_response(200, "Clé JWT valide", get_bearer_token());
            }else{
                deliver_response(401, "Erreur : Clé JWT non valide", get_bearer_token());
            }
        }
        break;
    default :
        deliver_response(405, "Méthode non autorisée", NULL); //Method Not Allowed
        break;
}

#### Sur l'API article :

##### Cas de la méthode POST
    case "POST" :
        si le role de l'utilisateur est 'publisher'
            /// Récupération des données envoyées par le Client
            $postedData = file_get_contents('php://input');
            $blob=json_decode($postedData,true);
            if (!empty($_GET['Id_article'])){
            /// Ajout d'un like ou dislike
                $Id_article = $_GET['Id_article'];
                $Publisher = extract_username($token);
                $Est_like = $blob['Est_like'];
                $requeteLike = $linkpdo->prepare('SELECT * FROM interagir WHERE Id_article = :Id_article AND Publisher=:Publisher');
                $requeteLike->execute(array(':Id_article' => $Id_article, 'Publisher' => $Publisher));
                $matchingData = $requeteLike->fetchALL();
                if(!$matchingData){
                    $requeteId_article = $linkpdo->prepare('SELECT * FROM article WHERE Id_article = :Id_article');
                    $requeteId_article->execute(array(':Id_article' => $Id_article));
                    $matchingData = $requeteId_article->fetchALL();
                    if($matchingData){
                        $req = $linkpdo->prepare('INSERT INTO interagir (Username,Id_article,Est_like) 
                        VALUES (:Username,:Id_article,:Est_like)');
                        $req->execute(array('Username' => $Publisher,'Id_article' => $Id_article,'Est_like' => $Est_like));    
                        deliver_response(200, "OK : vote ajouté", NULL);
                    }else{
                        deliver_response(401, "Error : Identifiant inexistant", NULL);
                    }
                }else{
                    deliver_response(401, "Error : Vous avez déjà interagis avec cet article", NULL);
                }
            }else{
                /// Traitement
                $Id_article = $blob['Id_article'];
                $Date_publication = date("Y-m-d");
                $Contenu = $blob['Contenu'];
                $Publisher = extract_username($token);
                $requeteId_article = $linkpdo->prepare('SELECT * FROM article WHERE Id_article = :Id_article');
                $requeteId_article->execute(array(':Id_article' => $Id_article));
                $matchingData = $requeteId_article->fetchALL();
                if(!$matchingData){
                    $req = $linkpdo->prepare('INSERT INTO article (Id_article,Date_publication,Contenu,Publisher) 
                    VALUES (:Id_article,:Date_publication,:Contenu,:Publisher)');
                    $req->execute(array('Id_article' => $Id_article,'Date_publication' => $Date_publication,'Contenu' => $Contenu, 'Publisher' => $Publisher));    
                    deliver_response(200, "OK : données ajoutées", NULL);
                }else{
                    deliver_response(401, "Error : Identifiant déjà existant", NULL);
                }
            }
        Sinon
            Renvoie l'erreur 401, "Error : Vous n'avez pas le role necessaire pour cette méthode"
        }
        Fin de la méthode

    /// Cas de la méthode PUT
    case "PUT" :
        if ($role == 'publisher') {
            /// Récupération des données envoyées par le Client
            $postedData = file_get_contents('php://input');

            /// Traitement
            $blob=json_decode($postedData,true);
            $Id_article = $blob['Id_article'];
            $Date_publication = date("Y-m-d");//on considere que la date est mise a jour avec le PUT
            $Contenu = $blob['Contenu'];
            $Publisher = extract_username($token);
            $requeteId_article = $linkpdo->prepare('SELECT * FROM article WHERE Id_article = :Id_article and publisher=:publisher');
            $requeteId_article->execute(array(':Id_article' => $Id_article,':publisher'=>$Publisher));
            $matchingData = $requeteId_article->fetchALL();
            if($matchingData){
                $req = $linkpdo->prepare('UPDATE article set Date_publication = :Date_publication, Contenu = :Contenu, 
                Publisher = :Publisher where Id_article = :Id_article');
                $req->execute(array('Date_publication' => $Date_publication,'Contenu' => $Contenu,
                'Publisher' => $Publisher,'Id_article' => $Id_article));
                deliver_response(200, "OK : données mises à jour", NULL);
            }else{
                deliver_response(401, "Error : Pas d'article trouvé pour l'id donné", NULL);
            }
        }else{
            deliver_response(401, "Error : Vous n'avez pas le role necessaire pour cette méthode", NULL);
        }
        break;

    case "DELETE" : 
        if ($role == 'publisher' or $role == 'moderator' ) {
            /// Récupération des critères de recherche envoyés par le Client
            if (!empty($_GET['Id_article'])){
                $Id_article=$_GET['Id_article'];
                if ($role == 'moderator'){
                    $requeteId_article = $linkpdo->prepare('SELECT * FROM article WHERE Id_article = :Id_article');
                    $requeteId_article->execute(array(':Id_article' => $Id_article));
                    $matchingData = $requeteId_article->fetchALL();
                    if($matchingData){
                        $requeteId_article = $linkpdo->prepare('DELETE FROM article WHERE Id_article = :Id_article');
                        $requeteId_article->execute(array(':Id_article' => $Id_article));
                        deliver_response(200, "OK : article supprimé", NULL);
                    }else{
                        deliver_response(401, "Error : Pas d'article trouvé pour l'id donné", NULL);
                    }
                }else{
                    $Publisher = extract_username($token);
                    $requeteId_article = $linkpdo->prepare('SELECT * FROM article WHERE Id_article = :Id_article AND Publisher = :Publisher');
                    $requeteId_article->execute(array(':Id_article' => $Id_article,':Publisher'=>$Publisher));
                    $matchingData = $requeteId_article->fetchALL();
                    if($matchingData){
                        $requeteId_article = $linkpdo->prepare('DELETE FROM article WHERE Id_article = :Id_article');
                        $requeteId_article->execute(array(':Id_article' => $Id_article));
                        deliver_response(200, "OK : article supprimé", NULL);
                    }else{
                        deliver_response(401, "Error : Pas d'article trouvé pour l'id donné ou votre pseudo", NULL);
                    }
                }
            }else{
                deliver_response(400, "Error : DELETE ne fonctionne pas sans identifiant article", NULL);
            }
        }else{
            deliver_response(401, "Error : Vous n'avez pas le role necessaire pour cette méthode", NULL);
        }
        break;
    
    case 'PATCH' :
        if ($role == 'publisher') {
            /// Récupération des données envoyées par le Client
            $postedData = file_get_contents('php://input');
            $blob=json_decode($postedData,true);
            if (!empty($_GET['Id_article'])){
            /// Ajout d'un like ou dislike
                $Id_article = $_GET['Id_article'];
                $Publisher = extract_username($token);
                $Est_like = $blob['Est_like'];
                $requeteId_article = $linkpdo->prepare('SELECT * FROM article WHERE Id_article = :Id_article');
                $requeteId_article->execute(array(':Id_article' => $Id_article));
                $matchingData = $requeteId_article->fetchALL();
                if($matchingData){
                    $requeteLike = $linkpdo->prepare('SELECT * FROM interagir WHERE Id_article = :Id_article AND Username=:Username');
                    $requeteLike->execute(array(':Id_article' => $Id_article, 'Username' => $Publisher));
                    $matchingData = $requeteLike->fetchALL();
                    if($matchingData){
                        $req = $linkpdo->prepare('UPDATE interagir set Est_like = :Est_like
                                                WHERE Id_article = :Id_article and Username=:Username');
                        $req->execute(array('Est_like' => $Est_like,'Id_article' => $Id_article,'Username' => $Publisher));    
                        deliver_response(200, "OK : vote mis à jour", NULL);
                    }else{
                        $req = $linkpdo->prepare('INSERT INTO interagir (Username,Id_article,Est_like) 
                        VALUES (:Username,:Id_article,:Est_like)');
                        $req->execute(array('Username' => $Publisher,'Id_article' => $Id_article,'Est_like' => $Est_like));    
                        deliver_response(200, "OK : vote ajouté", NULL);
                    }
                }else{
                    deliver_response(401, "Error : Identifiant article inexistant", NULL);
                }
            }else{
                deliver_response(401, "Error : PATCH ne fonctionne pas sans identifiant article", NULL);
            }
        }else{
            deliver_response(401, "Error : Vous n'avez pas le role necessaire pour cette méthode", NULL);
        }
        break;
    
            
        
    /// Cas de la méthode GET
    default :
        /// Récupération des critères de recherche envoyés par le Client
        if (!empty($_GET['Id_article'])){
            $Id_article=$_GET['Id_article'];
            if ($role == 'moderator') {
                $requeteArticles = $linkpdo->prepare('SELECT GROUP_CONCAT(CASE WHEN Est_like = 1 THEN interagir.Username END) AS Likes,
                                                            GROUP_CONCAT(CASE WHEN Est_like = 0 THEN interagir.Username END) AS Dislikes,
                                                            COUNT(CASE WHEN Est_like = 1 THEN 1 END) AS Nombre_likes, 
                                                            COUNT(CASE WHEN Est_like = 0 THEN 1 END) AS Nombre_dislikes, 
                                                            article.*
                                                            FROM article
                                                            LEFT JOIN interagir ON article.Id_article = interagir.Id_article
                                                            where Id_article = :Id_article');
            }elsif ($role == 'publisher') {
                $requeteArticles = $linkpdo->prepare('SELECT COUNT(CASE WHEN Est_like = 1 THEN 1 END) AS Nombre_likes, 
                                                            COUNT(CASE WHEN Est_like = 0 THEN 1 END) AS Nombre_dislikes, 
                                                            article.*
                                                            FROM article
                                                            LEFT JOIN interagir ON article.Id_article = interagir.Id_article
                                                            where Id_article = :Id_article');
            }else{
                $requeteId_article = $linkpdo->prepare('SELECT Date_publication, Contenu, Publisher 
                                                            FROM article
                                                            LEFT JOIN interagir ON article.Id_article = interagir.Id_article 
                                                            WHERE Id_article = :Id_article');
                }
            $requeteId_article->execute(array(':Id_article' => $Id_article));
            $matchingData = $requeteId_article->fetchALL();
            $blob=array();
            $blob=json_encode($matchingData,true);

        }else{
                if ($role == 'publisher' or $role == 'moderator') {
                    $requeteArticles = $linkpdo->prepare('SELECT GROUP_CONCAT(CASE WHEN Est_like = 1 THEN interagir.Username END) AS Likes,
                                                                GROUP_CONCAT(CASE WHEN Est_like = 0 THEN interagir.Username END) AS Dislikes, 
                                                                COUNT(CASE WHEN Est_like = 1 THEN 1 END) AS Nombre_likes, 
                                                                COUNT(CASE WHEN Est_like = 0 THEN 1 END) AS Nombre_dislikes,
                                                                article.*
                                                                FROM article
                                                                LEFT JOIN interagir ON article.Id_article = interagir.Id_article
                                                                GROUP BY article.Id_article');
                }else{
                    $requeteArticles = $linkpdo->prepare('SELECT COUNT(CASE WHEN Est_like = 1 THEN 1 END) AS Nombre_likes, 
                                                                COUNT(CASE WHEN Est_like = 0 THEN 1 END) AS Nombre_dislikes,
                                                                Date_publication, Contenu, Publisher 
                                                                FROM article
                                                                LEFT JOIN interagir ON article.Id_article = interagir.Id_article
                                                                GROUP BY article.Id_article');
                }
                $requeteArticles->execute();
                $matchingData = $requeteArticles->fetchALL();
                $blob=array();
                $blob=json_encode($matchingData,true);
            
        }
        /// Envoi de la réponse au Client
        if ($blob){
            deliver_response(200, "OK : données récupérées", $blob);
        }else{
            deliver_response(400, "Error : vide", $blob);
        }
        break;
}

## Auteurs
- Gaïa Ducournau
- Sechi Chloé