# AppGenerator
AppGenerator est un générateur d'application PHP utilisant une base de données MySQL.*totalement MVC* et est destiné  aux professionnels du développement.

### Objectif principal
* Réduire le temps de développement et optimiser le code;

### Exigences
AppGenerator exige :
* Apache2;
* Activer sur Apache le module mod_rewrite ;
* php >=5.3.2
* PDO PHP Extension

### Installation
 Télécharger le fichier ZIP et l'extraire dans le dossier www/ de votre serveur web

### Configuration
 Allez dans le dossier config/ pour éditer les deux fichiers json:
 * Le fichier app.json contient les paramètres d'accès à la base de données MySQL ainsi que le
 nom du dossier de l'application à générer et la langue à utiliser.
 > Exemple : app.json
   {
     "destName": "sco",
     "lang":"FR",
     "host": "localhost",
     "port": 3306,
     "database": "blog",
     "user": "root",
     "password": "****"
   }

 * Le fichier routes.json contient toutes les routes de l'application.
 Chaque route est déterminée par une URL(paramétrée ou non et ceci en utilisant les expressions régulières)
 les paramètres de l'url, le module dans lequel sera la ressource voulue et l'action à exécuter pour l'obtenir.
  > Exemple : routes.json
  [
    {
      "url": "/posts",
      "module": "post",
      "action": "getAll"
    },
    {
      "url": "/posts/([1-9]+)",
      "vars":["idPost"],
      "module": "post",
      "action": "getPost"
    }
  ]

  * Pour finir, excécuter le fichier generate.php

### Résultat - Fonctionnalités
 le code généré  fournit des fonctionnalités modulables et adaptables qui permettent de faciliter et d’accélérer
 le développement d'une application :

 * Une séparation du code en trois couches, selon le modèle MVC, pour une plus grande maintenabilité et évolutivité;
 * Une gestion des url parlante, permettant à une page d'avoir une URL distincte de sa position dans l'arborescence;
 * Un système de configuration utilisant pleinement le langage json;
 * Une couche de mapping objet-relationnel (ORM) et une d'abstraction de données;
 * une architecture extensible permettant créations et utilisations de plugins.

### A savoir 
Pour regénérer la couche mapping objet-relationnel (ORM) par exmeple à après une modification de la base de données,
lancer : generate.php?update=true