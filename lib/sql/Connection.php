<?php
namespace lib\sql;
use lib\JsonReader;

/**
 * Get connection to database
 *@author PhpGenerator (https://github.com/leplutonien/php_generator)
 */

class Connection{

	private static $connection = null;
	private static $databaseConfig = "/app/config/app.json";

	private function __construct(){
		$configFile = dirname(dirname(dirname(__FILE__))).self::$databaseConfig;
        $config = new JsonReader($configFile);
		self::$connection =PDOFactory::getMysqlConnexion(
			$config->getAttribute('host'),
			$config->getAttribute('port'),
			$config->getAttribute('database'),
			$config->getAttribute('user'),
			$config->getAttribute('password')
		);
	}

    /**
	 * get one instance of connection
     * @return connection
     */
    public static function getInstance() {
		if(is_null(self::$connection)){
			new Connection();
		}
    	return self::$connection;
    }

	/**
	 * get database config file
	 * @return string
	 */
	public static function getDatabaseConfigFile(){
		return self::$databaseConfig;
	}
}
?>