<?php
namespace lib;

use lib\sql\SqlQuery;

/**
 * contains useful functions
 * @author PhpGenerator (https://github.com/leplutonien/php_generator)
 */
class BoxFunctions
{

    /**
     * Method of obtaining the number of registration order in a table
     * @param $tableName the table or the processing will be done. It must  in the DB
     * @param $primaryKey the primary key of the table. The column where the research will be done
     * @param null $format the format above the automatic number .
     * @param $dimensionPK the lenght of positions on which the holding primaryKey
     * @return null|string the automatic number
     */
    public static function AutomaticNumber($tableName, $primaryKey, $format = null, $dimensionPK)
    {
        if (is_string($tableName) && is_string($primaryKey) && is_integer($dimensionPK)) {
            $ret = '';
            $pos = 0;

            if (!is_null($format)) {
                $pos = strlen($format);
            } else
                $format = '';

            if (!empty($format))
                $sql = 'SELECT MAX(SUBSTRING(' . $primaryKey . ',' . $pos . ')) as n from ' . $tableName;
            else
                $sql = 'SELECT Max(' . $primaryKey . ') as n from ' . $tableName;

            $sqlQuery = new SqlQuery($sql);
            $resultSet = $sqlQuery->execute();
            $pos = 1;

            if (count($resultSet->getAllRows()) > 0)
                $pos = (int)$resultSet->getAllRows()[0]['n'] + 1;

            $rest = $dimensionPK - (strlen($format) + strlen($pos));

            $zeros = '';
            while ($rest > 0) {
                $zeros = $zeros . '0';
                $rest--;
            }
            return $format . $zeros . $pos;
        } else {
            return null;
        }
    }

    /**
     * generate a link
     * @param $currentPage
     * @param $nextPage
     * @return string
     */
    public static function generatePath($currentPage, $nextPage)
    {
        $result = null;
        if ($nextPage == null) {
            $result = "#";
        } else {
            $nextPage = ltrim($nextPage, '/');
            $currentPage = ltrim($currentPage, '/');
            $nb = substr_count($currentPage, "/");
            if ($nb > 0) {
                for ($i = 0; $i < $nb; $i++) {
                    $result = $result . "../";
                }
                $result = $result . $nextPage;
            } else
                $result = $nextPage;
        }
        return $result;
    }

    /**
     * delete all files in a folder
     */
    public static function deleteFolder($dir)
    {
        $state = false;
        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false) {
                    if ($file == '.' or $file == '..')
                        continue;
                    $name = $dir . '/' . $file;

                    if (is_dir($name))
                        self::deleteFolder($name);
                    else
                        unlink($name);

                }
                closedir($dh);
                rmdir($dir);
                $state = true;
            }

        }
        return $state;
    }


    /**
     * return all files in a directory
     * @param $dir
     * @return array
     */
    public static function getFiles($dir)
    {
        $result = array();
        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                $i = 0;
                while (($file = readdir($dh)) !== false) {
                    if ($file == '.' or $file == '..')
                        continue;
                    $result[$i] = $file;
                    $i++;
                }

                closedir($dh);
            }
        }
        return $result;
    }

    /**
     * get customized name
     * @param $name
     * @return string
     */
    public static function getCustomizeName($name)
    {
        $name = strtoupper($name[0]) . substr($name, 1);
        for ($i = 0; $i < strlen($name); $i++) {
            if ($name[$i] == '_') {
                $name = substr($name, 0, $i) . strtoupper($name[$i + 1]) . substr($name, $i + 2);
            }
        }
        //avoid "class" as the  name
        if (strcmp($name, 'class') == 0)
            $name = substr($name, 0, strlen($name) - 1);

        return $name;
    }

    /**
     * Convert special characters into HTML entities
     * @param $str
     * @return encoded $str
     */
    public static function htmlEncode($str)
    {
        $correspondence_table = array(
            '"' => '&quot;',
            '&' => '&amp;',
            '€' => '&euro;',
            '<' => '&lt;',
            '>' => '&gt;',
            '¢' => '&cent;',
            '£' => '&pound;',
            '¤' => '&curren;',
            '¥' => '&yen',
            '§' => '&sect;',
            '©' => '&copy;',
            '«' => '&laquo;',
            '®' => '&reg;',
            '±' => '&plusmn;',
            'µ' => '&micro;',
            '»' => '&raquo;',
            '¿' => '&iquest;',
            'À' => '&Agrave;',
            'Á' => '&Aacute;',
            'Â' => '&Acirc;',
            'Ã' => '&Atilde;',
            'Ä' => '&Auml;',
            'Æ' => '&Aelig',
            'Ç' => '&Ccedil;',
            'È' => '&Egrave;',
            'É' => '&Eacute;',
            'Ê' => '&Ecirc;',
            'Ë' => '&Euml;',
            'Ì' => '&Igrave;',
            'Í' => '&Iacute;',
            'Î' => '&Icirc;',
            'Ï' => '&Iuml;',
            'Ð' => '&eth;',
            'Ñ' => '&Ntilde;',
            'Ò' => '&Ograve;',
            'Ó' => '&Oacute;',
            'Ô' => '&Ocirc;',
            'Õ' => '&Otilde;',
            'Ö' => '&Ouml;',
            '×' => '&times;',
            'Ø' => '&Oslash;',
            'Ù' => '&Ugrave;',
            'Ú' => '&Uacute;',
            'Û' => '&Ucirc;',
            'Ü' => '&Uuml;',
            'Ý' => '&Yacute;',
            'Þ' => '&thorn;',
            'ß' => '&szlig;',
            'à' => '&agrave;',
            'á' => '&aacute;',
            'â' => '&acirc;',
            'ã' => '&atilde;',
            'ä' => '&auml;',
            'å' => '&aring;',
            'æ' => '&aelig;',
            'ç' => '&ccedil;',
            'è' => '&egrave;',
            'é' => '&eacute;',
            'ê' => '&ecirc;',
            'ë' => '&euml;',
            'ì' => '&igrave;',
            'í' => '&iacute;',
            'î' => '&icirc;',
            'ï' => '&iuml;',
            'ð' => '&eth;',
            'ñ' => '&ntilde;',
            'ò' => '&ograve;',
            'ó' => '&oacute;',
            'ô' => '&ocirc;',
            'õ' => '&otilde;',
            'ö' => '&ouml;',
            '÷' => '&divide;',
            'ø' => '&oslash;',
            'ù' => '&ugrave;',
            'ú' => '&uacute;',
            'û' => '&ucirc;',
            'ü' => '&uuml;',
            'ý' => '&yacute;',
            'þ' => '&thorn;',
            'ÿ' => '&yuml;'
        );

        if (!empty($str)) {
            $ret = '';
            $tab = self::strSplitUnicode($str, 1);
            $tabCount = count($tab);
            $i = 0;
            while ($i <= $tabCount - 1) {
                if (self::isArrayKey($tab[$i], $correspondence_table)) {
                    $ret .= $correspondence_table[$tab[$i]];
                } else {
                    $ret .= $tab[$i];
                }
                $i++;
            }
            return $ret;
        }
        return $str;

    }

    /**
     * unicode string split;
     * @author :
     * @param $str
     * @param int $l
     * @return array
     */
    public static function strSplitUnicode($str, $l = 0)
    {
        if ($l > 0) {
            $ret = array();
            $len = mb_strlen($str, "UTF-8");
            for ($i = 0; $i < $len; $i += $l) {
                $ret[] = mb_substr($str, $i, $l, "UTF-8");
            }
            return $ret;
        }
        return preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY);
    }

    public static function isArrayKey($key, $array)
    {
        if (!empty($array)) {
            foreach ($array as $k => $v) {
                if ($k == $key)
                    return true;
            }
        }
        return false;
    }

    /**
     * Separate a string
     * @param $str
     * @param $number_character_to_separate
     * @param $separator
     * @param string $begin (right or left)
     * @return string
     */
    public static function strSeparate($str, $number_character_to_separate, $separator, $begin = 'right')
    {
        $ret = '';
        $tab = str_split($str, 1);
        $tabCount = count($tab);
        $i = 0;

        if (is_numeric($number_character_to_separate) || $number_character_to_separate >= $tabCount) {
            if ($begin == 'right') {
                $i = $tabCount - 1;
                $j = 0;
                $tmp = '';
                while ($i >= 0) {
                    $tmp .= $tab[$i];

                    if ($j == $number_character_to_separate - 1) {
                        if ($i == 0)
                            $ret = self::strInverse($tmp) . $ret;
                        else
                            $ret = $separator . self::strInverse($tmp) . $ret;
                        $j = 0;
                        $tmp = '';
                    } else {
                        $j++;
                    }

                    $i--;
                }
                $ret = self::strInverse($tmp) . $ret;
            }
            if ($begin == 'left') {
                $i = 0;
                $j = 0;
                $tmp = '';
                while ($i < $tabCount) {
                    $tmp .= $tab[$i];

                    if ($j == $number_character_to_separate - 1) {
                        if ($i == 0)
                            $ret .= $tmp;
                        else
                            $ret .= $tmp . $separator;
                        $j = 0;
                        $tmp = '';
                    } else {
                        $j++;
                    }

                    $i++;
                }
                //echo 'rest tmp '.$tmp.'/';
                $ret .= $tmp;
            }

        } else {
            return $str;
        }
        return $ret;
    }

    public static function strInverse($str)
    {
        $tab = str_split($str, 1);
        $ret = '';
        for ($i = count($tab) - 1; $i >= 0; $i--) {
            $ret .= $tab[$i];
        }
        return $ret;
    }
}

?>