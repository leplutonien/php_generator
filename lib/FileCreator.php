<?php
/**
 * simple file creator
 * @author PhpGenerator (https://github.com/leplutonien/php_generator)
 */

namespace lib;


class FileCreator
{
    protected $content;

    public function __construct()
    {

    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function write($fileName)
    {
        $fd = fopen($fileName, "w");
        fwrite($fd, utf8_encode($this->content));
        fclose($fd);
    }

}