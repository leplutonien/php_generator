<?php

/**
 * This class represents sql query with the eventual parameters
 * @author PhpGenerator (https://github.com/leplutonien/php_generator)
 */
namespace lib\sql;

class SqlQuery{
	private  $txt;
	private  $params = array();
	private  $paramsValues = array();
    private $excludedCharacters = array(" ",",",")");


    /**
     * @param String $txt  sql. The parameter is define by :paramName.
     * Example : SELECT * FROM post WHERE idpost = :idpost
     */
	public function __construct($txt){
        $this->txt = $txt;
        $this->params = $this->extractSqlParameters();
	}

	/**
	 * Extract from $txt all params name
	 * @return array
	 */
	private function extractSqlParameters()
	{
		$params = array();
		$tab = str_split($this->txt, 1);
		$tabCount = count($tab);
		$i = 0;
		$j = 0;
		while ($i <= $tabCount - 1) {
			if ($tab[$i] == ":") {
				$param = "";
				$i++;
				while (($i <= $tabCount - 1) && (!in_array($tab[$i], $this->excludedCharacters))) {
					$param .= $tab[$i];
					$i++;
				}

				if ($param != "") {
					$params[$j] = $param;
					$j++;
					continue;
				}
			}
			$i++;
		}
		return $params;
	}

    /**
     * Execute the query
     * @param FetchStyle $fetchStyle
     * @return ResultSet
     * @throws \Exception
     */
	public function execute($fetchStyle = null)
	{
		if(self::isvalidate()){
            $connection = Connection::getInstance();
			$statement = $connection->prepare($this->getQuery());

            if (!empty($this->params)) {
                foreach ($this->params as $param){
                    $value = $this->getValuebyParameter($param);
                    if(is_numeric($value)){
                        $statement->bindValue(":".$param,$value,\PDO::PARAM_INT);
                    }else{
                        if(is_null($value)){
                            $statement->bindValue(":".$param,NULL,\PDO::PARAM_NULL);
                        }else
                            $statement->bindValue(":".$param,$value,\PDO::PARAM_STR);
                    }
                }
            }

            return new ResultSet($statement, $fetchStyle);
		}else{
			throw new \Exception("The sql query is not validate");
		}
	}

	/**
	 * validate this SqlQuery
	 * @return bool
	 */
	public function isvalidate()
	{
		return (count($this->params) == count($this->paramsValues) && !is_null($this->txt)) ? true : false;
	}

	/**
	 * return the set sql
	 * @return String
	 */
	public function getQuery()
	{
		return $this->txt;
	}

	/**
	 * get value by parameter
	 * @param $param
	 * @return String|null
	 */
	public function getValuebyParameter($param)
	{
		return isset($this->paramsValues[$param]) ? $this->paramsValues[$param] : null;
	}

	/**
	 * get the sql params
	 * @return array
	 */
	public function getSqlParameters()
	{
		return $this->params;
	}

	/**
	 * Set  param into SQL
	 * @param $param
	 * @param $value
	 */
	public function setParams($param, $value)
	{
		if ($this->isSqlParameter($param))
			$this->paramsValues[$param] = $value;
		else
			throw new \RuntimeException($param . ' is not in sql parameters.');
	}

	/**
	 * @param $param
	 * @return bool
	 */
	private function isSqlParameter($param)
	{
		return in_array($param, $this->params);
	}

	/**
	 * Get sql query
	 * @return String
	 */
	public function getSQLText(){
		$sql = '';

		if($this->isvalidate()){
			$sql = $this->txt;
			foreach($this->paramsValues as $key => $value ){
				if(is_null($value)){
					$sql = str_replace(":".$key,"NULL",$sql);
				}else{
					if(is_string($value)){
						$sql = str_replace(":".$key,
							"\"".str_replace("\"","\\\"",$value)."\"",
							$sql);
					}else{
						$sql = str_replace(":".$key,$value,$sql);
					}
				}
			}
		}
		return $sql;
	}
}
?>