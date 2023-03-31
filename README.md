# ArticleManagerAPI
Projet de conception et développement d’API REST pour la gestion d’articles

# Informations utiles à l’évaluation de notre projet :

## MCD de la base de données pas à jour :
<img src="https://github.com/gaiailou/ArticleManagerAPI/blob/main/DocsCompteRendu/MCD.PNG" alt="MCD">

## Documentation de notre API

### Définition d'un article :
Chaque article est défini par un ```IdArticle```, une ```date_de_publication```, un ```contenu``` et un ```publisher```.
Un article peut être liké ou disliké par les publishers uniquement.
Chaque Like est stocker dans la table Interagir avec un ```username```, ```IdArticlee``` et ```est_like``` egale a ```1``` pour un like et ```0``` pour un dislike.

### Définition d'un utilisateur :
Chaque utilisateur est défini par un ```username```, un ```password``` et un ```role```.

#### Contraintes de rôle :
- Le ```moderator``` ne peut pas publier d'articles ni interagir avec mais il peut les supprimer.
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
- ```PATCH``` avec ```?IdArticle=[IdArticle]``` avec un json contenant ```est_like=[1(like) ou 0(dislike)]``` : Permet de voter pour l'article pour IdArticle correspondant s'il existe (disponible avec le rôle publisher uniquement) Nous avons consideré qu'en dehors de la création le like ne changer que de valeur, d'où l'utilisation du PATCH.
- ```DELETE``` avec ```?IdArticle=[IdArticle]``` : Supprimer un article (disponible avec les rôles publisher et moderator)

### Utilisation sur Postman :

#### ```POST``` sur ```http://localhost/ArticleManagerAPI/CodeSource/authAPI.php```
- Moderateur : ```{"username":"DarkModerator","password":"DARKMDP"}```
- Publisher : ```{"username":"Bob","password":"BOBMDP"}```
- Publisher :```{"username":"Camille58","password":"58MDP"}```

#### ```GET``` 
- ```http://localhost/api/ArticleManagerAPI/CodeSource/articlesAPI.php```
- avec ou sans ```Authorization: Bearer [JWT]``` (affichage différent selon le role)
#### ```GET``` avec id_article 
- ```http://localhost/api/ArticleManagerAPI/CodeSource/articlesAPI.php?Id_article=[valeur]```
- avec ou sans ```Authorization: Bearer [JWT]``` (affichage différent selon le role)
#### ```POST```
- ```http://localhost/api/ArticleManagerAPI/CodeSource/articlesAPI.php```
- avec ```Authorization: Bearer [JWT]``` d'un publisher sinon erreur
- et ```{"Id_article":"[valeur]","Contenu":"[valeur]"}```
#### ```PUT```
- ```http://localhost/api/ArticleManagerAPI/CodeSource/articlesAPI.php?Id_article=[valeur]```
- avec ```Authorization: Bearer [JWT]``` du publisher de l'article sinon erreur
- et ```{"Id_article":"[valeur]","Contenu":"[valeur]"}```
#### ```PATCH```
- ```http://localhost/api/ArticleManagerAPI/CodeSource/articlesAPI.php?Id_article=[valeur]```
- avec ```Authorization: Bearer [JWT]``` d'un publisher différent de celui de l'article sinon erreur
- et ```{"Est_like":"[ 0(like) ou 1(dislike) ou NULL(annulé)]"}```
- Nous avons consideré qu'en dehors de la création le like ne changer que de valeur, d'où l'utilisation du PATCH
#### ```DELETE```
- ```http://localhost/api/ArticleManagerAPI/CodeSource/articlesAPI.php?Id_article=[valeur]```
- avec ou sans ```Authorization: Bearer [JWT]``` moderator ou publisher de l'article sinon erreur

### Spécification des API REST du projet :

#### Sur l'API authentification :

```
Identification du type de méthode HTTP envoyée par le client
si la methode est{
    cas "POST" :
        Récupération des données envoyées par le Client en format JSON 
        convertir en tableau PHP
        s'il y a des données dans username et password{
            Recherche de l'utilisateur dans la base de données
            si le usernmae et le password correspondent a ceux de la BD{
                créer le Header JWT = array('alg'=>'HS256','typ'=>'JWT');
                créer le payload JWT avec 'username', 'role' et temps d'expiration 1 heure
                Génération du JWT

                Si le JWT est valide{
                     Reponse 200, "Clé JWT généré avec succés"
                }Sinon{
                    Erreur 401, "Clé JWT échoué"
                }
            }Sinon{
                Erreur 401, "Erreur : Nom d'utilisateur ou mot de passe incorrect"
            }
        }Sinon{
            Erreur 400, "Nom d'utilisateur ou mot de passe manquant"
        }

        si JWT fourni par le client{
            Si JWT valide{
                Reponse 200, "Clé JWT valide"
            }Sinon{
                Erreur 401, "Clé JWT non valide"
            }
        }
        fin cas POST

    cas si une autre méthode est envoyer :
        erreur 405, "Méthode non autorisée"
        Fin
}
```

#### Sur l'API article :

```
Recuperation du JWT
Extraction du role de l'utilisateur

Identification du type de méthode HTTP envoyée par le client
si la methode est{

    Cas "POST" :
        si le role est 'publisher' alors{
            Récupération des données envoyées par le Client
            Decodage du json
            si Id_article dans le lien n'est pas vide{
                $Date_publication = date du jour
                s'il n'y a pas Id_article dans le JSON{
                    Erreur 400, " JSON incomplet, Id_article manquant"
                }sinon{
                    s'il n'y a pas Contenu dans le JSON{
                         Erreur 400, " JSON incomplet, Contenu manquant"
                    }sinon{
                        extraire le publisher du JWT
                        Si id article n'est pas déjà attribuer alors{
                            inserer dans la base de données ce nouvel article avec toutes les informations   
                            Reponse 200, "les données ont été ajoutées"
                        }sinon{
                             Erreur 409, "Identifiant déjà existant"
                        }
                    }
                }
            }sinon{
                 Erreur 405, "Cette methode n'autorise pas de variable GET"
            }
        }sinon{
            Erreur 403, "Vous n'avez pas le role necessaire pour cette méthode"
        }
        fin cas POST

    Cas "PUT" :
        si le role est 'publisher' alors{
            Récupération des données envoyées par le Client
            Decodage du json
            extraire le publisher du JWT
            Si id article est attribuer alors{
                Si le publisher de l'article est le meme que celui qui PUT alors{
                    modifier dans la base de données cet article avec toutes les informations   
                    Reponse 200, "les données ont été mise a jour"
                }sinon{
                    Erreur 403, "Vous n'etes pas le pulisher de l'article, vous ne pouvez pas le modifier"
                }
            }sinon{
                Erreur 404, "Pas d'article trouvé pour l'id donné"
            }
        }sinon{
            Erreur 403, "Vous n'avez pas le role necessaire pour cette méthode"
        }
        fin cas PUT

    cas "DELETE" : 
        si le role est 'publisher' ou 'moderator' alors {
            si l'id_article est bien dans le lien alors{
                si le role est 'moderator' alors{
                    S'il existe un article pour l'id donnée alors{
                        Supprimer l'article 
                        Reponse 200, "article supprimé"
                    }sinon{
                        Erreur 404, "Pas d'article trouvé pour l'id donné"
                    }
                }sinon{
                    Extraire le publisher du JWT
                    S'il existe un article pour l'id donnée alors{
                        Si le publisher de l'article est le meme que celui qui DELETE alors{
                            Effacer l'article
                            Reponse 200, "OK : article supprimé"
                        }sinon{
                            Erreur 403, "Vous n'etes pas le pulisher de l'article, vous ne pouvez pas le supprimer"
                        }
                    }sinon{
                        Erreur 404, "Pas d'article trouvé pour l'id donné"
                    }
                }
            }sinon{
                Erreur 400, "DELETE ne fonctionne pas sans identifiant article"
            }
        }sinon{
            Erreur 403, "Vous n'avez pas le role necessaire pour cette méthode"
        }
        fin cas DELETE
    
    case 'PATCH' :
        Si le role est publisher{
            Récupération des données envoyées par le Client
            Decode JSON
            s'il n'y a pas d'id_article dans le lien{
                extraire le publisher du JWT
                Si l'article existe bien alors{
                    Si le publisher n'est pas celui qui a publier l'article{
                        Si le publisher a deja interagis avec l'article alors{
                            Mettre a jour avec le nouveau vote   
                            Reponse 200, "vote mis à jour"
                        }sinon{
                            Ajouter le vote dans la base de données   
                            Reponse 200, "vote ajouté"
                        }
                    }sinon{
                        Erreur 403, "Vous etes le pulisher de l'article, vous ne pouvez pas voter"
                    }
                }sinon{
                    Erreur 404, "Identifiant article introuvable"
                }
            }sinon{
                Erreur 400, "PATCH ne fonctionne pas sans identifiant article"
            }
        }sinon{
            Erreur 403, "Vous n'avez pas le role necessaire pour cette méthode"
        }
        Fin cas PATCH
        
    Si ce n'est aucune des autres méthode alors c'est GET:
        Récupération des critères de recherche envoyés par le Client
        s'il y a id_article dans le lien alors{
            si le role est 'moderator' {
                Afficher pour l'article la liste des personnes qui ont like, ceux qui ont dislike,le nombre de like, de dislike, et toute les informations de article
            }sinon {
                Si le role est publisher{
                    Afficher pour l'article le nombre de like, de dislike, et toute les informations de article
                }sinon{
                    Afficher pour l'article la Date_publication, Contenu et le Publisher 
                }
            }
            Encode le resultat en JSON

        }sinon{
                si le role est 'moderator' {
                    Afficher pour chaque article la liste des personnes qui ont like, ceux qui ont dislike,le nombre de like, de dislike, et toute les informations de article
                }sinon {
                    Si le role est publisher{
                        Afficher pour chaque article le nombre de like, de dislike, et toute les informations de article
                    }sinon{
                        Afficher pour chaque article la Date_publication, Contenu et le Publisher 
                    }
                }
                Encode le resultat en JSON
                
            
        }
        Envoi du resultat au Client
        si le resultat n'est pas vide{
            Reponse 200, "données récupérées"
        }sinon{
            Reponse 204, "Pas de résultat pour la requete demandé"
        }
        fin
}
```
## Les User story

- Moderateur : En tant que moderateur, je peux consulter les différents article avec la methode get, je peux aussi les supprimer peu importe le publisher à l'aide de la methode delete

- Publisher : En tant que publisher, je peux utiliser la methode Post pour créer un article. Je peux aussi modifier un article de ma création à l'aide de la méthode put. Je peux supprimer mes articles avec la méthode Delete et je peux voter pour les articles des autres avec la methode patch

- Anonyme : En tant que personne non connecté, je peux seulement consulter les articles.

## Auteurs
- Gaïa Ducournau
- Sechi Chloé
