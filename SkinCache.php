<?php

/* namespace SkinTemplate;
  use \SkinTemplate\SkeletonToElement as SkeletonElement; */

class SkinCache
{
    static $cacheDir, $cacheView, $cacheExpiry = 25200;

    /**
     * Initialize cache.
     * @param string $path
     * @param integer $duration
     */
    static function setCacheStructure($path = 'cache/', $duration = 25200)
    {
        self::$cacheDir = $path;
        self::$cacheExpiry = $duration;
        self::buildCacheStructure();
    }
    
    /**
     * Create a cache name from view name.
     * @param string $name
     */
    static function setCacheViewName($name)
    {
        self::$cacheView = md5($name);
    }

    /**
     * Build cache structure by creating a cache folder and a log file.
     */
    static private function buildCacheStructure()
    {
        if(!is_dir(self::$cacheDir)) {
            mkdir(self::$cacheDir);
            file_put_contents(self::$cacheDir . 'cacheManager.txt', '');
        } elseif(!file_exists(self::$cacheDir . 'cacheManager.txt'))
            file_put_contents(self::$cacheDir . 'cacheManager.txt', '');
    }
    
    /**
     * If view is in cache.
     * @return boolean
     */
    static function cacheViewExists()
    {
        return file_exists(self::$cacheDir . self::$cacheView . '.txt');
    }
    
    /**
     * Get cached view.
     * @return string
     */
    static function getCacheView()
    {
        return gzdecode(file_get_contents(self::$cacheDir . self::$cacheView . '.txt'));
    }
    
    /**
     * Set view in cache.
     * @param string $code
     * @param boolean $forceExpiry
     * @return boolean
     */
    static function setCacheView($code, $forceExpiry = false)
    {
        if($forceExpiry || !self::cacheViewExists()/* || self::getCacheViewExpiryDate() <= time()*/) {
            file_put_contents(self::$cacheDir . self::$cacheView . '.txt', gzencode($code));
            self::addCacheView();
            return true;
        }
        return false;
    }
    
    /**
     * Add a line in log file.
     */
    static function addCacheView()
    {
        $cacheManager = new SplFileObject(self::$cacheDir . 'cacheManager.txt', 'w');
        $cacheManager->fwrite(self::$cacheView . ';' . (time() + self::$cacheExpiry) . "\n");
    }
    
    #Not managed
    static function matchCacheView()
    {
        $cacheManager = new SplFileObject(self::$cacheDir . 'cacheManager.txt');
        $content = $cacheManager->fgetcsv(';', "\n");
        echo self::$cacheView;
        foreach($content as $lineNumber => $lineValues) {
            
            if(self::$cacheView === $lineValues[0])//If the cache name matches a line
                return $lineNumber;//Returns expiry date
        }
        return -1;
    }
    
    #Not managed
    static function getCacheViewExpiryDate()
    {
        $line = self::matchCacheView();
        if($line > -1)//If the cache name matches a line
            return explode($cacheManager->fseek($line), ';')[1];//Returns expiry date
        return time();//If there's no match, returns current timestamp
    }
}
