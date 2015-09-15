<?php
//namespace SkinTemplate;


/**
 * Generates an output from Skeleton
 */
class Skin/* extends \Phalcon\Mvc\View\Engine*/
{

    private $view, $viewsDir, $data, $samplesDir, $translationsDir, $samplesVariable,
        $cache, $errorReport, $outputType;

    /**
     * Skin constructor.
     * @param string $viewsDir
     * @param mixed $data
     * @param integer $report
     * @param integer $outputType
     * @param boolean $toggleCache
     * @param string $layer
     * @param string $samplesDir
     */
    function __construct($data = NULL, $viewsDir = 'views/', $report = 0, $outputType = 0, $toggleCache = TRUE, $layer = 'HTML5', $samplesDir = 'samples/', $translationsDir = 'translations/')
    {
        $this->errorReport = $report;
        $this->outputType = $outputType;
        $this->cache = $toggleCache;
        $this->viewsDir = $viewsDir;
        $this->data = json_decode(json_encode($data), TRUE);
        $this->layer = $layer;
        $this->samplesDir = $samplesDir;
        $this->translationsDir = $translationsDir;
    }
    
    /**
     * Set report level.
     * @param integer $level
     * @return this
     */
    function setReportLevel($level)
    {
        $this->errorReport = $level;
        return $this;
    }
    
    /**
     * Set output type.
     * @param integer $type
     * @return this
     */
    function setOutputType($type)
    {
        $this->outputType = $type;
        return $this;
    }
    
    /**
     * Toggle cache.
     * @param boolean $value
     * @return this
     */
    function setToggleCache($value)
    {
        $this->cache = $value;
        return $this;
    }
    
    /**
     * Set output layer type.
     * @param string $layer
     * @return this
     */
    function setLayer($layer)
    {
        $this->layer = $layer;
        return $this;
    }
    
    /**
     * Set public sample directory.
     * @param string $directory
     * @return this
     */
    function setSamplesDir($directory)
    {
        $this->samplesDir = $directory;
        return $this;
    }

    /**
     * Set JavaScript sample variable.
     * @param string $variable
     * @return this
     */
    function setSamplesVariable($variable)
    {
        $this->samplesVariable = $variable;
        return $this;
    }

    /**
     * Set translations directory.
     * @param string $directory
     * @return this
     */
    function setTranslationsDir($directory)
    {
        $this->translationsDir = $directory;
        return $this;
    }
    
    /**
     * Get view as string.
     * @return string
     */
    function getContent()
    {
        return $this->view;
    }

    function partial($partialPath)
    {
        //echo $partialPath;
    }

    /**
     * Parsing and replacement.
     * @param string $path
     * @return string
     */
    private function getRender($path)
    {
        SkeletonParser::setViewsDir($this->viewsDir);
        SkeletonParser::setSamplesDir($this->samplesDir);
        SkeletonParser::setSamplesVariable($this->samplesVariable);
        SkinTranslator::setTranslationsDir($this->translationsDir);
        $structure = SkeletonParser::parseContent(
            $path, $this->layer,
            $this->outputType,
            $this->errorReport
        );
        return SkeletonReplacer::getOutput($structure);
    }

    /**
     * Render skeleton from path.
     * @param string $path
     * @param boolean $forceExpiry
     */
    function render($path = NULL, $forceExpiry = FALSE)
    {
        if($this->cache) {
            SkinCache::setCacheStructure();
            SkinCache::setCacheViewName($path);

            if(!SkinCache::cacheViewExists() || $forceExpiry) {
                $file = $this->getRender($path);
                SkinCache::setCacheView($file, $forceExpiry);
            } else
                $file = SkinCache::getCacheView();
        } else
            $file = $this->getRender($path);

        ob_start();
        eval('$skinLocalVars = [];?>' . $file);
        $this->view = ob_get_clean();
        return $this;
    }
}
