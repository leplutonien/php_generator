<?php
namespace lib\sql;

/**
 * Control how the lines of a PDO results will be returned
 * @author PhpGenerator (https://github.com/leplutonien/php_generator)
 */
class FetchStyle{

    /**
     * returns an anonymous object with property names that correspond
     * to the column names returned in the result set
     */
    public static $FETCH_OBJ = '\PDO::FETCH_OBJ';

    /**
     * returns an array indexed by column name as returned
     * in the result set
     */
    public static $FETCH_ASSOC = '\PDO::FETCH_ASSOC';

}