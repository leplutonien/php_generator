<?php
namespace models;
use entities\${entityName};
use lib\sql\SqlQuery;

/**
 * Class that operate on table '${table_name}'. Database Mysql.
 *@author: PhpGenerator (https://github.com/leplutonien/php_generator)
 * @date: ${date}
*/

class ${entityName}Dao implements \lib\dao\CRUD{

	/**
     * Insert record to table
     * @param $${fctParam}
     * @return $status
    */
    public function insert($${fctParam}){
		if(get_class($${fctParam}) == 'entities\${entityName}'){
			$sql = 'INSERT INTO ${table_name} (${insertFields}) VALUES (${insertParams})';
			$sqlQuery = new SqlQuery($sql);${params}
			$resultSet = $sqlQuery->execute();
            return $resultSet->getStatus();
        }else
            throw new \RuntimeException('The input variable is not an object of class ${entityName}');
    }

    /**
     * Update record in table
     * @param $${fctParam}
     * @return $status
    */
    public function update($${fctParam}){
        if(get_class($${fctParam}) == 'entities\${entityName}'){
            $sql = 'UPDATE ${table_name} SET ${updateFields} WHERE ${majConds}';
            $sqlQuery = new SqlQuery($sql);${params}
            $resultSet = $sqlQuery->execute();
            return $resultSet->getStatus();
        }else
            throw new \RuntimeException('The input variable is not an object of class ${entityName}');
    }

    /**
     * Delete record in table
     * @param $${fctParam}
     * @return $status
   */
    public function delete($${fctParam}){
        if(get_class($${fctParam}) == 'entities\${entityName}'){
            $sql = 'DELETE FROM ${table_name} WHERE WHERE ${majConds}';
            $sqlQuery = new SqlQuery($sql);${params}
            $resultSet = $sqlQuery->execute();
            return $resultSet->getStatus();
        }else
            throw new \RuntimeException('The input variable is not an object of class ${entityName}');
    }

    /**
     *Delete record in table
     * @param array of conditions
     * @return $status
     */
    public function delete(array $conds){
        $sql = 'DELETE FROM ${table_name} WHERE ';
        if (is_array($conds) && !empty($conds)){
            //complete the sql query
            $count = count($conds);
            foreach ($conds as $attribute => $value){
                $sql.= $attribute.' = :'.strtolower($attribute);
                $count --;
                if($count != 0){
                    $sql.=' AND ';
                }
            }
            $sqlQuery = new SqlQuery($sql);
            foreach ($conds as $attribute => $value)
                $sqlQuery->setParams(strtolower($attribute), $value);

            $resultSet = $sqlQuery->execute();
            return $resultSet->getStatus();
        }else
            return false;
    }

    /**
     * @param int $begin
     * @param int $end
     * @return Get all records from table
    */
    public function findAll($begin = -1, $end = -1){
        $rows = null;
        $sql = 'SELECT * FROM ${table_name}';

        if ($begin != -1 || $end != -1)
            $sql .= ' LIMIT '.(int) $end.' OFFSET '.(int) $begin;

        $sqlQuery = new SqlQuery($sql);
        $resultSet = $sqlQuery->execute();
        $resultSet->getAllRows();

        while ($r = $resultSet->nextRow())
                $rows [] = new ${entityName}($r);

        return $rows;
    }

    /**
     * @return Count row
    */
    public function countRows(){
        $sql = 'SELECT COUNT(*) as n FROM ${table_name}';
        $sqlQuery = new SqlQuery($sql);
        $resultSet = $sqlQuery->execute();
        if ( count($resultSet->getAllRows()) > 0 )
            return (int)$resultSet->getAllRows()[0]['n'];
        else
            return -1;
    }

    /**
     * Query for one column
     * @param array $conditions Example: array("id_post" => 1, "id_user" => 125)
     * @param int $begin
     * @param int $end
     * @return All instances of entity that match to $conditions.
     */
     public function findByAttribute(array $conditions, $begin = -1, $end = -1){
        $rows = null;
        $sql = 'SELECT * FROM ${table_name} WHERE ';
        if (is_array($conditions) && !empty($conditions)){
            //complete the sql query
            $count = count($conditions);
            foreach ($conditions as $attribute => $value){
                $sql.= $attribute.' = :'.strtolower($attribute);
                $count --;
                if($count != 0){
                    $sql.=' AND ';
                }
            }

            if ($begin != -1 || $end != -1)
                $sql .= ' LIMIT '.(int) $end.' OFFSET '.(int) $begin;

            $sqlQuery = new SqlQuery($sql);

            foreach ($conditions as $attribute => $value)
                $sqlQuery->setParams(strtolower($attribute), $value);

            $resultSet = $sqlQuery->execute();
            $resultSet->getAllRows();

            while ($r = $resultSet->nextRow())
                $rows [] = new ${entityName}($r);
        }
        return $rows;
     }

}
?>