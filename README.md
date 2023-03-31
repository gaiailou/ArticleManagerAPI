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



#### Sur l'API article :

##### Cas de la méthode POST
   

## Auteurs
- Gaïa Ducournau
- Sechi Chloé