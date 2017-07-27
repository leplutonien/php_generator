<?php

/**
 * interface which includes handling methods of the database
 * @author PhpGenerator (https://github.com/leplutonien/php_generator)
 */

namespace lib\dao;


interface Crud{

     public function insert($entity);

     public function update($entity);

     public  function remove($entity);

     public  function delete(array $conds);

     public  function findAll($begin = -1, $end = -1);

     public  function countRows();

     public  function findByAttribute(array $conditions, $begin = -1, $end = -1);


}