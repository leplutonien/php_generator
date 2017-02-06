<?php

namespace lib\dao;
use lib\BoxFunctions;

/**
* abstract class useful for entities
* @author PhpGenerator (https://github.com/leplutonien/php_generator)
*/

abstract class Entity{
    
    public function __construct(array $data = array()) {
        if (!empty($data)) {
            $this->hydrate($data);
        }
    }
    
    private function hydrate(array $data){
        foreach ($data as $attribute => $value){
            $method = 'set'.ucfirst(BoxFunctions::getCustomizeName($attribute));
            if (is_callable(array($this, $method))){
                $this->$method($value);
            }
        }
    }
}

?>