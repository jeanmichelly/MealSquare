
<img alt="MealSquare logo" width="300px" src="http://image.noelshack.com/fichiers/2018/39/6/1538241417-logo.png"><img src="http://dab1nmslvvntp.cloudfront.net/wp-content/uploads/2014/12/1418948033symfony-logo.png" alt="Logo Foodsquare" width="40px"/>
========================

MealSquare est une application web de gestion de recette MARMITON like. Développé avec le framework PHP [Symphony2](http://symfony.com/), il permettra d'apporter en plus une multitude de fonctionnalités.


## I. Présentation du service

MealSquare est une application web de gestion de recette permettant le stockage et la gestion de recettes de cuisines qui offrira plusieurs fonctionnalités parmi lesquelles : 
- Trouver une recette selon la nature du plat, sa complexité, etc. 
- Trouver différentes recettes réalisant un plat donné. 
- Modifier une recette et mémoriser les différentes versions d'une recette en offrant la possibilité d'éliminer de la base une recette donnée.
- etc. pour ne citer que celles-la

### Contexte du projet
	
   Ce projet est né du simple constat qu’il est de nos jours difficile, pour les amateurs de cuisine, de préserver un ensemble de recettes, et cela aussi bien en format papier que numérique. Même si il existe des services numériques de consultation de recettes, ceux-ci se concentrent essentiellement sur un ensemble de recettes préfaites et mises à la disposition des internautes sans pour autant vraiment donner la possibilité aux utilisateurs d’éditer leurs recettes et les partager avec les autres utilisateurs des plateformes. 

   Ce service ambitionne d’y remédier en s’appuyant sur les nouvelles technologies et la généralisation des supports mobiles afin de créer un système documentaire qui permettra aux différents utilisateurs de créer, chercher et /ou gérer des recettes de cuisine sur un support numérique. 

### Utilisateurs Cibles

Le service qui sera développé s’adressera au grand public avec pour condition d’être intéressé par la cuisine. Cela peut aller du simple cuisinier occasionnel au chef cuisinier.
Cette population comprendra deux principales catégories de personnes :
- Les simples visiteurs : Utilisateurs non actifs du service qui le visiteront pour consulter les différentes recettes.
- Les éditeurs : Utilisateurs qui participeront activement sur le service en éditant des recettes et autres

## II. Guide d'installation

Vous souhaitez cloner le projet, l'installer et y apporter toutes les modifications que vous désirez, suivez attentivement les instructions contenues dans ce guide pour effectuer l’installation de MealSquare dans les meilleures conditions.

Avant de commencer, rassurez vous d'avoir cloner ce repository. Si ce n'est pas encore fait, executez la commande suivante dans le Terminal
``` linux
$ git clone git@github.com:jeanmichelly/MealSquare.git
``` 

Ensuite créez un fichier parameters.yml dans le repertoire app/config/ que vous adapterez à vos besoin
```yml
# app/config/parameters.yml
parameters:
    database_driver: pdo_mysql
    database_host: localhost
    database_port: null
    database_name: mealsquare
    database_user: root
    database_password: root
    mailer_transport: smtp
    mailer_host: 127.0.0.1
    mailer_user: null
    mailer_password: null
    locale: fr
    secret: #votre token symfony
    debug_toolbar: true
    debug_redirects: false
    use_assetic_controller: true
    unix_socket: /Applications/MAMP/tmp/mysql/mysql.sock
    uri_media: /uploads/media
````

Un update de composer (qui va se charger de télécharger toutes les dépendances), puis un assets:install pour installer l'ensemble des ressources publiques des bundles.

Puis en fonction de vos besoins, cliquez sur le mode de déploiement voulu.

- [Déploiement en local](https://github.com/jeanmichelly/MealSquare/wiki/Deploiement-en-local)
- [Déploiement en production](https://github.com/jeanmichelly/MealSquare/wiki/D%C3%A9ploiement-en-Production)



## III. Guide d'utilisation

Ce petit guide va vous permettre de vous
familiariser avec toutes les fonctionnalités de la plateforme. 

***NB:*** Il suffit de cliquer pour être redirigé vers le guide d'utilisation concerné

- [L'accueil](https://github.com/jeanmichelly/MealSquare/wiki/L'accueil)
- [Le catalogue de recette](https://github.com/jeanmichelly/MealSquare/wiki/Le-catalogue-de-recette)
- [Les raccourcis](https://github.com/jeanmichelly/MealSquare/wiki/Les-raccourcis)
- [Les ingrédients](https://github.com/jeanmichelly/MealSquare/wiki/Les-ingr%C3%A9dients)
- [Les actualités](https://github.com/jeanmichelly/MealSquare/wiki/Les-actualit%C3%A9s)
- [Le profil](https://github.com/jeanmichelly/MealSquare/wiki/Le-profil)
- [La recherche](https://github.com/jeanmichelly/MealSquare/wiki/La-recherche)
- [La messagerie](https://github.com/jeanmichelly/MealSquare/wiki/La-messagerie)

## License

* [Apache Version 2.0](http://www.apache.org/licenses/LICENSE-2.0.html)
