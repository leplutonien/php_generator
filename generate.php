<?php

/**
 * PhpGenerator generator
 * @Author : PhpGenerator (https://github.com/leplutonien/php_generator)
 */

 require 'lib/autoload.php';
 use lib\JsonReader;
 use lib\BoxFunctions;
 use lib\FileCreator;
 use lib\sql\SqlQuery;
 use lib\FileCreatorWithTemplate;


class generate {
     private static $json = null;

     public static function gen(){
         $update = false;
         //if update is true, we generate the entities and models
         if(isset($_GET["update"]) && $_GET["update"]== 'true')
             $update = true;

         self::$json = new JsonReader(dirname(__FILE__).'/config/app.json');
         $base = dirname(dirname(__FILE__)).'/'.self::$json->getAttribute('destName');
         self::init($base,$update);

         $sql = new SqlQuery('SHOW TABLES');
         $r =   $sql->execute()->getAllRows();
         $tables = self::extractTables($r);

         self::generateEntities($tables,$base);
         self::generateModels($tables,$base);
         if(!$update){
             self::appConfiguration($base);
         }

         echo "<br> <a href='../".self::$json->getAttribute('destName')."'> click here to run the generated application </a>";
     }

    private static function appConfiguration($base){
        $j = new JsonReader(dirname(__FILE__).'/config/routes.json');
        $routes = $j->getAttributes();
        $actions = array();
        $comments = array();

        foreach($routes as $route){
            if(!file_exists($base.'/app/modules/'.$route['module'])){
                mkdir($base.'/app/modules/'.$route['module']);
                mkdir($base.'/app/modules/'.$route['module'].'/views');
            }

            //storage a action value  an comment of this route
            if(!isset($actions[$route['module']])){
                 $actions[$route['module']] = $route['action'];
                 $comments[$route['module']] = array($route['action'] => isset($route['comment'])? $route['comment']: NULL) ;
            }else{
                 $elts = explode(',', $actions[$route['module']]);
                 if(!in_array( $route['action'],$elts)){
                     $actions[$route['module']] = $actions[$route['module']].','.$route['action'];
                     $comments[$route['module']][$route['action']] = isset($route['comment'])? $route['comment']: NULL ;
                 }
             }
        }

        //generate a controller for each module
        if(count($actions) > 0) {
            foreach ($actions as $module => $action) {
                $fcts = "";
                $elts = explode(',', $action);

                foreach ($elts as $elt) {
                    $c = self::getComment($comments,$module,$elt);
                    //set a comment before function
                    if(!is_null($c))
                        $fcts .= "\n\t/**\n\t * ".$c."\n\t */";
                    $fcts .= "\n\tpublic function execute" . ucfirst(BoxFunctions::getCustomizeName($elt)) . "(){"
                        . "\n\t\t\$this->page->addVar('title','title ');"
                        . "\n\t\t\$this->page->setLayout('layout.php');"
                        . "\n\t}\n";
                    //generate a view for each controller
                    $view = new FileCreator();
                    $view->setContent("<div>\n\n</div>");
                    $view->write($base . '/app/modules/' . $module . '/views/' . $elt . '.php');
                }
                $file = new FileCreatorWithTemplate('templates/moduleController.tpl');
                $file->setKey("module", $module);
                $file->setKey("class", ucfirst(BoxFunctions::getCustomizeName($module)));
                $file->setKey("fcts", $fcts);
                $file->write($base . '/app/modules/' . $module . '/' . ucfirst(BoxFunctions::getCustomizeName($module)) . 'Controller.php');
            }
        }

        $f = "<?php\nrequire 'lib/autoload.php';\n";
        $f .= "\$app = new app\\App();\n" ;
        $f .= "\$app->run();\n?>" ;
        $file = new FileCreator();
        $file->setContent($f);
        $file->write($base.'/index.php');

        //htaccess
        $f = "php_flag default_charset utf-8\n";
        $f .= "Options +FollowSymLinks\n" ;
        $f .= "RewriteEngine On\n" ;
        $f .= "RewriteCond %{REQUEST_FILENAME} !-f\n" ;
        $f .= "RewriteRule ^(.*)$ index.php [QSA,L]" ;
        $file = new FileCreator();
        $file->setContent($f);
        $file->write($base.'/.htaccess');


        echo "CONFIGURATION OK ...</br>";

    }

     /**
      * To initialize destination's Project folder
      */
     private static function init($base,$update){
         if(file_exists($base) && !$update){
            BoxFunctions::deleteFolder($base);
         }
         if(!$update){
             mkdir($base);
             mkdir($base.'/db');

             mkdir($base.'/lib');
             copy(dirname(__FILE__).'/lib/Application.php',$base.'/lib/Application.php');
             copy(dirname(__FILE__).'/lib/BackController.php',$base.'/lib/BackController.php');
             copy(dirname(__FILE__).'/lib/BoxFunctions.php',$base.'/lib/BoxFunctions.php');
             copy(dirname(__FILE__).'/lib/HTTPRequest.php',$base.'/lib/HTTPRequest.php');
             copy(dirname(__FILE__).'/lib/HTTPResponse.php',$base.'/lib/HTTPResponse.php');
             copy(dirname(__FILE__).'/lib/JsonReader.php',$base.'/lib/JsonReader.php');
             copy(dirname(__FILE__).'/lib/Page.php',$base.'/lib/Page.php');
             copy(dirname(__FILE__).'/lib/Route.php',$base.'/lib/Route.php');
             copy(dirname(__FILE__).'/lib/Router.php',$base.'/lib/Router.php');
             copy(dirname(__FILE__).'/lib/TypeFlash.php',$base.'/lib/TypeFlash.php');
             copy(dirname(__FILE__).'/lib/User.php',$base.'/lib/User.php');
             copy(dirname(__FILE__).'/lib/autoload.php',$base.'/lib/autoload.php');

             mkdir($base.'/lib/sql');
             $file =  new FileCreatorWithTemplate('templates/Connection.tpl');
             $file->write($base.'/lib/sql/Connection.php');

             copy(dirname(__FILE__).'/lib/sql/PDOFactory.php',$base.'/lib/sql/PDOFactory.php');
             copy(dirname(__FILE__).'/lib/sql/ResultSet.php',$base.'/lib/sql/ResultSet.php');
             copy(dirname(__FILE__).'/lib/sql/SqlQuery.php',$base.'/lib/sql/SqlQuery.php');
             copy(dirname(__FILE__).'/lib/sql/FetchStyle.php',$base.'/lib/sql/FetchStyle.php');

             mkdir($base.'/lib/dao');
             copy(dirname(__FILE__).'/lib/dao/CRUD.php',$base.'/lib/dao/CRUD.php');
             copy(dirname(__FILE__).'/lib/dao/Entity.php',$base.'/lib/dao/Entity.php');

             mkdir($base.'/assets');
             mkdir($base.'/assets/plugins');
             mkdir($base.'/assets/css');
             mkdir($base.'/assets/js');
             mkdir($base.'/assets/fonts');
             mkdir($base.'/assets/images');

             mkdir($base.'/additionals');
             if(self::$json->getAttribute('managerUserRole')){
                 mkdir($base.'/additionals/user_role');
                 copy(dirname(__FILE__).'/additionals/user_role/ManagerUserRole.php',$base.'/additionals/user_role/ManagerUserRole.php');
             }

             mkdir($base.'/app');
             $file =  new FileCreatorWithTemplate('templates/AppIndex.tpl');
             $file->write($base.'/app/App.php');

             mkdir($base.'/app/config');

             mkdir($base.'/app/errors');
             $file->setContent("<div><b>This page is not accessible</b></div>");
             $file->write($base.'/app/errors/404.php');

             mkdir($base.'/app/templates');
             $file =  new FileCreatorWithTemplate('templates/layout.tpl');
             $file->setKey("lang",self::$json->getAttribute('lang'));
             $file->setKey("title","404 page");
             $file->write($base.'/app/templates/404-layout.php');

             $file = new FileCreator();
             $file->setContent("<?php echo \$content; ?>");
             $file->write($base.'/app/templates/default-layout.php');

             $file =  new FileCreatorWithTemplate('templates/layout.tpl');
             $file->setKey("lang",self::$json->getAttribute('lang'));
             $file->setKey("title","<?php echo \$title ?>");
             $file->write($base.'/app/templates/layout.php');

             mkdir($base.'/app/modules');

             copy(dirname(__FILE__).'/config/routes.json',$base.'/app/config/routes.json');

             $f = "{";
             $f .= "\n  \"host\": "."\"".self::$json->getAttribute('host')."\"," ;
             $f .= "\n  \"port\": ".self::$json->getAttribute('port')."," ;
             $f .= "\n  \"database\": "."\"".self::$json->getAttribute('database')."\"," ;
             $f .= "\n  \"user\": "."\"".self::$json->getAttribute('user')."\"," ;
             $f .= "\n  \"password\": "."\"".self::$json->getAttribute('password')."\"" ;
             $f .= "\n}";
             $file = new FileCreator();
             $file->setContent($f);
             $file->write($base.'/app/config/app.json');
         }

         if(file_exists($base.'/entities') && file_exists($base.'/models')){
             BoxFunctions::deleteFolder($base.'/models');
             BoxFunctions::deleteFolder($base.'/entities');
         }
         mkdir($base.'/entities');
         mkdir($base.'/models');

         echo "INITIALIZATION OK ...</br>";
     }

     private static function extractTables($rs){
         $tables = array();
         $p = "Tables_in_".self::$json->getAttribute('database');

         if(count($rs) > 0)
          foreach($rs as $r)
            $tables[] = $r[$p];

         return $tables;
     }

     private static function generateEntities($tables,$base){
         foreach($tables as $table){
             if(!self::doesTableContainPK($table))
                 continue;

             $entityName = BoxFunctions::getCustomizeName($table);
             $entityFile = new FileCreatorWithTemplate('templates/entity.tpl');
             $entityFile->setKey('entityName', $entityName);
             $entityFile->setKey('table_name', $table);
             $entityFile->setKey('date', date("Y-m-d H:i"));
             $entityFile->setKey('import',"use models\\".$entityName.'Dao;');

             $tableFields = self::getTableFields($table);

             $variables = "\r\n";
             $getters_setters="";
             $a='$this->';

             foreach($tableFields as $tf){
                 $attribute = lcfirst(BoxFunctions::getCustomizeName($tf["Field"]));
                 $variables .= "\tprivate $".$attribute.";\n";
                 $getters_setters.="\n";

                 // getter
                 $comment="\t/**\n\t * @return mixed\n\t */";
                 $getters_setters.=$comment."\n\tpublic function get".ucfirst($attribute)."(){";
                 $getters_setters.="\n\t\treturn ".$a.$attribute.";\n\t}\n";
                 // setter
                 $getters_setters.="\n";
                 $comment="\t/**\n\t * @param $".$attribute."\n\t */";
                 $getters_setters.=$comment."\n\tpublic function set".ucfirst($attribute)."($".$attribute."){";
                 $getters_setters.="\n\t\t".$a.$attribute." = $".$attribute.";\n\t}\n";

             }

             $variables .= "\tprivate static $".lcfirst($entityName).'Dao'.";\n";


             $entityFile->setKey('variables', $variables);
             $entityFile->setKey('getters_setters', $getters_setters);

             $entityFile->setKey('entity_dao_call', "self::$".lcfirst($entityName).'Dao'." = new ".$entityName."Dao();");
             $entityFile->setKey('insert', "return self::$".lcfirst($entityName)."Dao->insert(\$this);");
             $entityFile->setKey('update', "return self::$".lcfirst($entityName)."Dao->update(\$this);");
             $entityFile->setKey('_delete', "return self::$".lcfirst($entityName)."Dao->delete(\$this);");
             $entityFile->setKey('delete', "return self::$".lcfirst($entityName)."Dao->delete(\$primaryKeys_entity);");
             $entityFile->setKey('findAll', "return self::$".lcfirst($entityName)."Dao->findAll(\$begin , \$end);");
             $entityFile->setKey('countRows', "return self::$".lcfirst($entityName)."Dao->countRows();");
             $entityFile->setKey('findByAttribute', "return self::$".lcfirst($entityName)."Dao->findByAttribute(\$conditions, \$begin , \$end);");
             $entityFile->setKey('daoVariable', "$".lcfirst($entityName).'Dao');


             $entityFile->write($base.'/entities/'.$entityName.'.php');
         }
         echo "GENERATE ENTITIES OK ...</br>";

     }

     private static function generateModels($tables, $base){
         foreach($tables as $table){
             if(!self::doesTableContainPK($table))
                 continue;

             $modelFile = new FileCreatorWithTemplate('templates/model.tpl');
             $entityName = BoxFunctions::getCustomizeName($table);
             $modelFile->setKey('entityName', $entityName);
             $modelFile->setKey('table_name', $table);
             $modelFile->setKey('date', date("Y-m-d H:i"));

             $tableFields = self::getTableFields($table);
             $majConds="";
             $insertFields = "";
             $params = "";
             $updateFields = "";
             $insertParams = "";
             $pKs = array();

             foreach($tableFields as $tf){
                 $attribute = $tf["Field"];
                 $fctParam = lcfirst($entityName).'Entity';
                 $modelFile->setKey('fctParam', $fctParam);

                 $insertFields .= $attribute.', ';
                 $insertParams .= ':'.strtolower($attribute).', ';

                 $params .= "\n\t\t\t\$sqlQuery->setParams('".strtolower($attribute)
                     ."', \$".$fctParam."->get".ucfirst(BoxFunctions::getCustomizeName($attribute))."());";

                 if($tf["Key"] == "PRI"){
                     $pKs[count($pKs)] = $attribute;

                 }else{
                     $updateFields .= $attribute.' = :'.strtolower($attribute).', ';
                 }
             }
             $i = 1;
             foreach($pKs as $pk){
                 if($i == 1)
                     $majConds .= $pk.' = :'.strtolower($pk).' ';
                 else
                     $majConds .= 'AND '.$pk.' = :'.strtolower($pk).' ';
                 $i++;
             }

             $insertFields = substr($insertFields,0, strlen($insertFields)-2);
             $insertParams = substr($insertParams,0, strlen($insertParams)-2);
             $updateFields = substr($updateFields,0, strlen($updateFields)-2);
             $majConds = substr($majConds,0, strlen($majConds)-1);
             $modelFile->setKey('insertFields', $insertFields);
             $modelFile->setKey('insertParams', $insertParams);
             $modelFile->setKey('params', $params);
             $modelFile->setKey('updateFields', $updateFields);
             $modelFile->setKey('majConds', $majConds);

             $modelFile->write($base.'/models/'.$entityName.'Dao.php');
         }
         echo "GENERATE MODELS OK ...</br>";

     }

     private static function getTableFields($table){
         $sql = new SqlQuery('DESC '.$table);
         return $sql->execute()->getAllRows();
     }

     private static function doesTableContainPK($table){
         $tableDesc = self::getTableFields($table);
         foreach($tableDesc as $td){
             if($td["Key"] == "PRI")
                 return true;
         }
         return false;
     }

    /**
     * Get a comment of an action in a route
     * @param array $comments
     * @param $module
     * @param $action
     * @return null
     */
    private static function getComment(array $comments, $module, $action){

        foreach($comments as $mod => $actArray){
            if($mod == $module){
                foreach($comments[$module] as $act => $comment){
                   if($act ==  $action){
                       return $comment;
                   }
                }
            }
        }
        return null;
    }
 }

generate::gen();
