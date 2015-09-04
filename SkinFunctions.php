<?php
//namespace SkinTemplate;

class SkinFunctions 
{
    /*
     * Once skeleton template is converted to PHP, it uses those methods.
     */

    /**
     * Output data.
     * @param array $context
     * @param string $index
     * @param array $treatment
     */
    static function output($context, $index, $treatment = [])
    {
        $escape = TRUE;
        $output = isset($context[$index]) ? $context[$index] : '';
        if(!empty($treatment)) {
            $countTreatment = count($treatment);
            for($i = 0; $i < $countTreatment; ++$i) {
                if(method_exists(__CLASS__, $treatment[$i]))
                    $output = call_user_func([__CLASS__, $treatment[$i]], $output);
            }
            if(in_array('ue', $treatment) || in_array('unescape', $treatment))
                $escape = FALSE;
        }
        echo $escape ? SkinFunctions::escape($output) : $output;
    }

    /**
     * Convert any data to its boolean equivalent.
     * @param mixed $data
     * @return boolean
     */
    static function toBoolean($data)
    {
        if(!is_string($data))
            return (bool) $data;
        switch(strtolower($data)) {
            case '1':
            case 'TRUE':
            case 'on':
            case 'yes':
            case 'y':
                return TRUE;
            default:
                return FALSE;
        }
    }

    /**
     * Translate a string.
     * @param string $index
     * @param array $variables
     */
    static function translate($index, $variables = [])
    {
        echo SkinTranslator::translate($index, $variables);
    }
    
    /*Treatment functions*/
    private static function addCurrency($price)
    {
        $country = 'us';
        switch ($country)
        {
            case 'fr':
                return $price . '€';
            case 'en':
                return '£' . $price;
            case 'us':
                return '$' . $price;
            default:
                return $price . '€';
        }
    }
    
    private static function capitalize($string)
    {
        return ucwords($string);
    }

    private static function dateFormat($date)
    {
        $dateObject = new \DateTime($date);
        return $dateObject->format('d/m/Y');
    }
    
    private static function escape($string = '')
    {
        return htmlentities($string);
    }
    
    private static function linkIn($link) 
    {
        return strtolower('http://' . SITE_DOMAIN . '/' . self::stripAccents($link));
    }

    private static function linkOut($link)
    {
        return 'http://' . $link;
    }

    private static function lowercase($string)
    {
        return strtolower($string);
    }
    
    private static function minimize($string)
    {
        return strtolower(str_replace(
            array(
                'à', 'á', 'â', 'ã', 'ä', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ú', 'û',
                'ü', 'ý', 'ÿ', 'À', 'Á', 'Â', 'Ã', 'Ä', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ú', 'Û', 'Ü', 'Ý'
            ), 
            array(
                'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u',
                'u', 'y', 'y', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'N', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y'
            ), 
            $string
        ));
    }

    private static function moneyFormat($price)
    {
        //Formater en fonction de locale
        return addCurrency(number_format($price, 2, ',', ' '));
    }
    
    private static function numberFormat($number)
    {
        //Formater en fonction de locale
        if (!is_string($number))
            return number_format($number, 2, ',', ' ');
        else
            return $number;
    }
    
    
    private static function romanicNumber($integer) 
    { 
        $values = [
            'M' => 1000, 'CM' => 900, 'D' => 500, 
            'CD' => 400, 'C' => 100, 'XC' => 90, 
            'L' => 50, 'XL' => 40, 'X' => 10, 
            'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1
        ]; 
        $romanic = ''; 
        while($integer > 0) { 
            foreach($values as $key => $value) { 
                if($integer >= $value) { 
                    $integer -= $value; 
                    $romanic .= $key; 
                    break; 
                } 
            } 
        } 
        return $romanic; 
    } 
    
    private static function stripAccents($string)
    {
        return str_replace(
            array(
                'à', 'á', 'â', 'ã', 'ä', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ú', 'û',
                'ü', 'ý', 'ÿ', 'À', 'Á', 'Â', 'Ã', 'Ä', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ú', 'Û', 'Ü', 'Ý'
            ), 
            array(
                'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u',
                'u', 'y', 'y', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'N', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y'
            ), 
            $string
        );
    }
    
    private static function truncate($string)
    {
        $maxLength = 30;
        $subString = substr($string, 0, $maxLength);
        return strlen($string) > $maxLength ? $subString . '...' : $subString;
    }

    private static function unescape($string)
    {
        return $string;
    }

    private static function uppercase($string)
    {
        return $string->toUppercase();
    }

    /*
      ALIASING - SHORTCUTS
     */
    
    private static function ac($price)
    {
        return self::addCurrency($price);
    }
    
    private static function c($string)
    {
        return self::capitalize($string);
    }

    private static function df($date)
    {
        return self::dateFormat($date);
    }
    
    private static function e($string)
    {
        return self::escape($string);
    }

    private static function li($link)
    {
        return self::linkIn($link);
    }

    private static function lo($link)
    {
        return self::linkOut($link);
    }

    private static function l($string)
    {
        return self::lowercase($string);
    }
    
    private static function m($string)
    {
        return self::minimize($string);
    }

    private static function mf($price)
    {
        return self::moneyFormat($price);
    }

    private static function nf($number)
    {
        return self::numberFormat($number);
    }
    
    private static function rn($integer)
    {
        return self::romanicNumber($integer);
    }
    
    private static function sa($string)
    {
        return self::stripAccents($string);
    }
    
    private static function t($string)
    {
        return self::truncate($string);
    }

    private static function ue($string)
    {
        return $string;
    }

    private static function u($string)
    {
        return self::uppercase($string);
    }
}
