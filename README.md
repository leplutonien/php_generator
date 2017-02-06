# PhpGenerator
PhpGenerator generates applications using PHP and MySQL database.

## Primary objective
* Reduce development time and optimize code;

## Requirements
Php Generator requires :
* Apache2;
* Activate mod_rewrite on  Apache ;
* php >=5.3.2
* PDO PHP Extension

## Installation
 Download the ZIP file and extract it in the folder "www/"  on your web server

## Configuration
 Go to the folder "config/" to edit two files:
 * The file "app.json" contains the access parameters to the MySQL database, the
    name of the application to be generated and the language to be used.
 > Example : app.json

        {
          "destName": "blog",
          "managerUserRole":false,
          "lang":"FR",
          "host": "localhost",
          "port": 3306,
          "database": "blog",
          "user": "root",
          "password": ""
        }

 * The file "routes.json" contains all application routes,each route is determined by a URL(using regular expressions),
     the url parameters, the module of the resource and the action to get it.
  > Example : routes.json

        [
         {
           "url": "/posts",
           "module": "posts",
           "action": "get_all"
         }
         ,
         {
           "url": "/posts/([1-9]+)",
           "vars":["id_post"],
           "module": "posts",
           "action": "get_post"
         }
        ]

 * Finally, run the file **generate.php**
## Result - Features
 the generated code provides flexible and adaptable features that accelerate the development of an application:

   * Separation of code into three layers, according to the MVC model, for greater scalability and maintainability;
   * Management urls allow a page to have a separate URL of its position in the tree of this application;
   * A configuration system in  the json language;
   * A layer of object-relational mapping (ORM) and a data abstraction;
   * An extensible architecture allows creations and uses plugins.
## To know
To regenerate the object-relational mapping layer (ORM), for example after a change in the database, run:

      <?php
      php generate.php?update=true;
      ?>
## Documentation

- [Generated Application Structure](docs/01-doc.md)
- [Use of Access Management](docs/02-doc.md)
- [Tips](docs/03-doc.md)