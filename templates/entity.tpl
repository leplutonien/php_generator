<?php
namespace entities;
${import}

/**
 * Object represents table '${table_name}'
 * @author: PhpGenerator (https://github.com/leplutonien/php_generator)
 * @date: ${date}
 */
class ${entityName} extends \lib\dao\Entity{ ${variables}
    public function __construct(array $data){
        parent::__construct($data);
        self::loadDao();
    }${getters_setters}

    public function insert(){
        ${insert}
    }

    public function update(){
        ${update}
    }

    public static function delete(array conds){
         self::loadDao();
         ${delete}
    }

    public static function delete(){
         ${_delete}
    }

    public static function findAll($begin = -1, $end = -1){
        self::loadDao();
        ${findAll}
    }

    public static function countRows(){
        self::loadDao();
        ${countRows}
    }

    public static function findByAttribute(array $conditions, $begin = -1, $end = -1){
        self::loadDao();
        ${findByAttribute}
    }

    private static function loadDao(){
        if(is_null(self::${daoVariable}))
            ${entity_dao_call}
    }
}
?>