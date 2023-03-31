# requete POST sur http://localhost/ArticleManagerAPI/CodeSource/authAPI.php
- Moderateur : {"username":"DarkModerator","password":"DARKMDP"}
- Publisher : {"username":"Bob","password":"BOBMDP"}
- Publisher :{"username":"Camille58","password":"58MDP"}

# requetes sur http://localhost/ArticleManagerAPI/CodeSource/articlesAPI.php

## GET 
- http://localhost/api/ArticleManagerAPI/CodeSource/articlesAPI.php
avec ou sans Authorization: Bearer [JWT] (affichage différent selon le role)
## GET avec articles id 
- http://localhost/api/ArticleManagerAPI/CodeSource/articlesAPI.php?Id_article=[valeur]
avec ou sans Authorization: Bearer [JWT] (affichage différent selon le role)
## POST
- http://localhost/api/ArticleManagerAPI/CodeSource/articlesAPI.php
avec Authorization: Bearer [JWT] d'un publisher sinon erreur
et {"Id_article":"[valeur]","Contenu":"[valeur]"}
## PUT
- http://localhost/api/ArticleManagerAPI/CodeSource/articlesAPI.php?Id_article=[valeur]
avec Authorization: Bearer [JWT] du publisher de l'article sinon erreur
et {"Id_article":"[valeur]","Contenu":"[valeur]"}
## PATCH
- http://localhost/api/ArticleManagerAPI/CodeSource/articlesAPI.php?Id_article=[valeur]
avec Authorization: Bearer [JWT] d'un publisher différent de celui de l'article sinon erreur
et {"Est_like":"[ 0(like) ou 1(dislike) ou NULL(annulé)]"}
## DELETE
- http://localhost/api/ArticleManagerAPI/CodeSource/articlesAPI.php?Id_article=[valeur]
avec ou sans Authorization: Bearer [JWT] moderateur ou publisher de l'article sinon erreur