<?php
//namespace SkinTemplate;


/**
 * Generates an output from Skeleton
 */
class Skin/* extends \Phalcon\Mvc\View\Engine*/
{

    private $viewPath, $view, $data, $skinSamples = [], $outputJS = [],
        $cache, $errorReport, $outputType;

    /**
     * Skin constructor.
     * @param string $viewsDir
     * @param mixed $data
     * @param integer $report
     * @param integer $outputType
     * @param boolean $toggleCache
     * @param string $layer
     */
    function __construct($viewsDir = '/views/', $data = NULL, $report = 0, $outputType = 0, $toggleCache = TRUE, $layer = 'HTML5')
    {
        $this->errorReport = $report;
        $this->outputType = $outputType;
        $this->cache = $toggleCache;
        $this->viewsDir = $viewsDir;
        $this->data = json_decode(json_encode($data), TRUE);
        $this->layer = $layer;
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
        $filePath = $this->viewPath . $path;
        $structure = SkeletonParser::parseContent(
            $filePath, $this->layer,
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
