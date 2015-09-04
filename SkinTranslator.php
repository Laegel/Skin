<?php

class SkinTranslator
{
    static $translations = [], $language = 'en', $translationsDir = 'translations/';
    
    static function setTranslations()
    {
        if(empty(self::$translations))
            self::$translations = require self::$translationsDir . self::$language . '.php';
    }

    static function translate($index, array $params = [])
    {
        self::setTranslations();
        if(!empty($params)) {
            array_unshift($params, self::$translations[$index]);
            return call_user_func_array('sprintf', $params);
        } else
            return self::$translations[$index];
    }
    
    static function t($index, array $params = [])
    {
        return self::translate($index, $params);
    }
}
