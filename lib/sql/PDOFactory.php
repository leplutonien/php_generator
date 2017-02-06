<?php

namespace lib\sql;
/**
 * Class PDOFactory
 * @author PhpGenerator (https://github.com/leplutonien/php_generator)
 */
class PDOFactory{

    public static function getMysqlConnexion($host='localhost',$port='3306', $db='test', $user = 'root', $pwd = ''){
        try{
           $connection = new \PDO('mysql:host='.$host.';port='.$port.';dbname='.$db,$user,$pwd,array(\PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES UTF8'));
           $connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }
        catch(\Exception $e) {
            exit('<b>Exception to line '. $e->getLine() .' :</b> '. $e->getMessage());
        }

        return $connection;
    }
}

?>