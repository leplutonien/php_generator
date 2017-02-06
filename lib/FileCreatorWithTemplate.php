<?php
/**
 * Template file creator
 * @author PhpGenerator (https://github.com/leplutonien/php_generator)
 */


namespace lib;

class FileCreatorWithTemplate extends FileCreator
{
    private $template;

    public function __construct($template)
    {
        parent::__construct();
        $this->template = $template;
        parent::setContent($this->extractTemplate());
    }

    private function extractTemplate()
    {
        $ret = '';
        $handle = fopen($this->template, "r");
        while (!feof($handle)) {
            $buffer = fgets($handle, 4096);
            $ret .= $buffer;
        }
        fclose($handle);
        return $ret;
    }

    public function setKey($key, $value)
    {
        $this->content = str_replace('${' . $key . '}', $value, $this->content);
    }

}