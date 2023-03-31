# Brouillon Compte-rendu

## Consignes
Un compte-rendu de 4 pages maximum au format PDF indiquant l’URL d’accès de votre dépôt Git ainsi que les éléments de conception suivants :
- MCD de la base de données.
- Spécification de la ou des API REST du projet, en particulier :
    - les méthodes proposées ;
    - pour chaque méthode, les données éventuellement attendues en entrée ainsi que
celles retournées ;
    - pour chaque méthode, les traitements associés (en langage naturel ou pseudo-code par exemple) ;
    - pour chaque méthode, les types d’erreur pris en compte.
- Au moins une user story pour chacun des types d’utilisateur exploitant votre solution 
(utilisateur non authentifié ; utilisateur authentifié avec le rôle moderator ; utilisateur
authentifié avec le rôle publisher).

## Notre projet


## Liste de chose a faire avant le 31/03

- [x] Faire l'API de génération de JWT avec authentification
    - [x] Méthode POST avec BD
- [ ] Faire l'API de gestion des articles
    - [x] Méthode GET avec BD - visu des articles
    - [x] Méthode POST avec BD
    - [x] Méthode PUT avec BD
    - [x] Méthode DELETE avec BD
    - [ ] Méthode DELETE avec BD avec suppression des likes associé si besoin
    - [x] Gestion des roles et JWT
    - [x] gerer les likes 
- [ ] Uniformiser les codes (variables)
- [ ] verifier la sécurité (htmlspecialchart,...)
- [ ] Commenter les codes
- [ ] Faire la spé des méthodes proposées (voir consigne)
- [ ] Faire les user story
- [ ] Faire le compte rendu au propre
- [x] verifier la derniere vesion d'export de la BD
- [x] verifier le MCD par rapport à la BD
- [ ] Faire le client 
    - [x] Client sans connexion
    - [x] Page connexion client
    - [x] Client en mode connecter
    - [ ] Page de modification d'un article