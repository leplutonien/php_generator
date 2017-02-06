<?php
namespace lib;

/**
 * Read a json file
 * @author PhpGenerator (https://github.com/leplutonien/php_generator)
 */

class JsonReader
{
    private $attributes = array();

    function __construct($jsonFile)
    {
        if (file_exists($jsonFile)) {
            $handle = fopen($jsonFile, "r");
            $content = fread($handle, filesize($jsonFile));
            fclose($handle);
            $this->attributes = json_decode($content, true);

        } else {
            throw new \RuntimeException($jsonFile . ' not found ');
        }
    }

    public function getAttribute($attribute)
    {
        if (isset($this->attributes[$attribute])) {
            return $this->attributes[$attribute];
        }
        return null;
    }

    /**
     * @return array|mixed
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    public function hasAttribute($attribute)
    {
        return isset($this->attributes[$attribute]);
    }
}

?>