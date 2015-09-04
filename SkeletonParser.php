<?php

/* namespace SkinTemplate;*/

class SkeletonParser
{
    private static $attributeAlias = [
            '.' => 'class', '#' => 'id',
            '?' => 'skif', '%' => 'skloop', '$' => 'sksample',
            ':' => 'skelse'
        ];
    static $skinAttributes = [
            'skif', 'skloop', 'sksample', 'skelse'
        ], $content, $viewsDir = 'views/',
        $outputType, $outputMode,
        $errorReport, $currentLine = 0, $contexts = [];

    const PARSE_ATTR_START = '~([\w-]*|[', PARSE_ATTR_END = '])\(([\w-;{|}=>.^/ ]*?)\)~S',
        PARSE_ATTR_SHORT_START = '~(?<!\w)([', PARSE_ATTR_SHORT_END = '])([\w-;{|}=>./]+)~S',
        PARSE_COMMENTS = '~(?://)[^\r\n]*|/\*.*?\*/~s',
        REGEX_VAR_BLOCK = '~{{([\w .$#]*)\|?([\w ,]*)}}~S', REGEX_TRANSLATE_BLOCK = '~{=([\w .]*)\|?([\w ,.]*)=}~S',
        PARSE_PARTIAL = '~@partial ([^\r\n\s]*)~', REGEX_PARTIAL_INDENT = '~([\h]*)@partial~',
        PARSE_EXTENDS = '~@extends ([^\r\n\s]*)~', REGEX_EXTENDS_INDENT = '~([\h]*)@content~',
        FILE_EXTENSION = '.skln', OUTPUT_MODE_MINI = 0, OUTPUT_MODE_FULL = 1,
        ERROR_REPORT_NONE = 0, ERROR_REPORT_WARNINGS = 1, ERROR_REPORT_ERRORS = 2, ERROR_REPORT_ALL = 3,
        VAR_GLOBAL_CONTEXT = 0, VAR_LOOP_CONTEXT = 1, VAR_LOOP_PROPERTY_CONTEXT = 2;

    /**
     * Launching function.
     * @param string $fileName
     * @param string $type
     * @param integer $mode
     * @param integer $report
     * @return string
     */
    static function parseContent($fileName, $type, $mode = 0, $report = 0)
    {
        self::$outputType = $type;
        self::$outputMode = $mode;
        self::$errorReport = $report;
        $structure = self::getStructure($fileName);
        $template = self::executeSkin($structure);
        echo SkinExceptionHandler::getReport(self::$errorReport, $structure);
        return $template;
    }

    /* SETTERS */
    /**
     * Set output type.
     * @param string $type
     */
    static function setOutputType($type)
    {
        self::$outputType = $type;
    }

    /**
     * Set output mode.
     * @param string $mode
     */
    static function setOutputMode($mode)
    {
        self::$outputMode = $mode;
    }

    /* UTILS */
    /**
     * Get skeleton file content if it exists.
     * @param string $file
     * @return string
     */
    private static function extractContent($file)
    {
        if(file_exists(self::$viewsDir . $file . self::FILE_EXTENSION))
            return preg_replace('~(?://)[^\r\n]*|/\*.*?\*/~s', '', file_get_contents(self::$viewsDir . $file . self::FILE_EXTENSION));
        else {
            SkinExceptionHandler::addError(SkinExceptionHandler::E_MAIN_TEMPLATE_404, ['TEMPLATE' => $file]);
            return '';
        }
    }

    /**
     * Get attribute alias if it exists.
     * @param string $attribute
     * @return string
     */
    private static function getAttributeAlias($attribute)
    {
        return isset(self::$attributeAlias[$attribute]) ? self::$attributeAlias[$attribute] : $attribute;
    }

    /**
     * Return alias list "self::$attributeAlias" as a string with all indexes (used in regex).
     * @return string
     */
    private static function aliasesAsString()
    {
        $aliases = array_keys(self::$attributeAlias);
        $countAliases = count($aliases);
        $string = '';
        for($i = 0; $i < $countAliases; ++$i)
            $string .= $aliases[$i];
        return $string;
    }

    /**
     * Get output layer.
     * @return string
     */
    private static function getSkinLayer()
    {
        return '\Skin' . self::$outputType;
    }

    /**
     * Get Skin attributes (skif, skelse, skloop, sksample)
     * @param SkinElement $node
     * @return array
     */
    private static function manageSkinAttributes(SkinElement $node)
    {
        $skinAttributes = [];
        if(!empty($node->attributes)) {
            $countAttr = count($node->attributes[0]);
            for($attr = 0; $attr < $countAttr; ++$attr)
                if(in_array($node->attributes[0][$attr], self::$skinAttributes))
                    $skinAttributes[$node->attributes[0][$attr]] = $node->attributes[1][$attr];
        }
        return $skinAttributes;
    }

    /**
     * Sort the template lines by indent and new lines hierarchy as a multidimensional array by node => children.
     * @param array $dataset
     * @return array
     */
    private static function mapTree(array $dataset)
    {
        $tree = [];
        foreach($dataset as $id => &$node) {
            if(NULL === $node->parent)
                $tree[$id] = &$node;
            else {
                if(!isset($dataset[$node->parent]->children))
                    $dataset[$node->parent]->children = [];
                $dataset[$node->parent]->children[$id] = &$node;
            }
        }
        return $tree;
    }

    /**
     * Create spaces depending on the node level.
     * @param integer $level
     * @param string $space
     * @return string
     */
    static function setSpacesByLevel($level, $space = ' ')
    {
        $tabs = '';
        while($level > 0) {
            $tabs .= $space;
            --$level;
        }
        return $tabs;
    }



    /**
     * Get the template structure, line by line.
     * @param string $fileName
     * @return array
     */
    private static function getStructure($fileName)
    {
        $temp = array_values(array_filter(explode("\n", str_replace("\r\n", "\n", self::parseSpecial(self::extractContent($fileName)))), (function($input) {
            if(0 !== strlen(trim($input)))
                return $input; //Filter blank lines
        })));
        array_unshift($temp, '');
        return self::parseLines($temp);
    }


    /**
     * Get skeleton file content (the partial template) if it exists.
     * @param string $content
     * @return string
     */
    private static function fetchPartial($content)
    {
        preg_match(self::REGEX_PARTIAL_INDENT, $content, $tabs);
        $tabs = empty($tabs) ? '' : $tabs[1];
        preg_match_all(self::PARSE_PARTIAL, $content, $matchPartial);
        $countPartial = count($matchPartial[0]);
        for($partials = 0; $partials < $countPartial; ++$partials) {
            if(file_exists(self::$viewsDir . $matchPartial[1][$partials] . self::FILE_EXTENSION)) {
                $partialView = preg_replace('~(?://)[^\r\n]*|/\*.*?\*/~s', '', file_get_contents(self::$viewsDir . $matchPartial[1][$partials] . self::FILE_EXTENSION));
                $partialViewExploded = explode("\n", $partialView);
                $countPartialExplode = count($partialViewExploded);
                for($i = 1; $i < $countPartialExplode; ++$i)
                    $partialViewExploded[$i] = $tabs . $partialViewExploded[$i];
                $partialView = implode("\n", $partialViewExploded);
            } else {
                SkinExceptionHandler::addError(SkinExceptionHandler::E_PARTIAL_TEMPLATE_404, ['TEMPLATE' => $matchPartial[1][$partials]]);
                $partialView = '';
            }
            $content = self::fetchPartial(str_replace($matchPartial[0][$partials], $partialView, $content));
        }
        return $content;
    }

    /**
     * Get skeleton file content (the parent template) if it exists.
     * @param string $content
     * @return string
     */
    private static function fetchParent($content)
    {
        preg_match(self::PARSE_EXTENDS, $content, $matchParent);
        if(isset($matchParent[1])) {
            if(file_exists(self::$viewsDir . $matchParent[1] . self::FILE_EXTENSION)) {
                $parentView = preg_replace('~(?://)[^\r\n]*|/\*.*?\*/~s', '', file_get_contents(self::$viewsDir . $matchParent[1] . self::FILE_EXTENSION));
                preg_match(self::REGEX_EXTENDS_INDENT, $parentView, $tabs);

                if(!isset($tabs)) {
                    SkinExceptionHandler::addError(SkinExceptionHandler::E_CONTENT_TOKEN_MISSING, ['TEMPLATE' => $matchParent[1]]);
                    $tabs = '';
                } else
                    $tabs = $tabs[1];
            } else {
                SkinExceptionHandler::addError(SkinExceptionHandler::E_PARENT_TEMPLATE_404, ['TEMPLATE' => $matchParent[1]]);
                $parentView = '';
                $tabs = '';
            }
        }

        return !empty($matchParent) ? str_replace('@content', str_replace([$matchParent[0], "\n"], ['', "\n" . $tabs], $content), $parentView) : $content;
    }



    /* PARSING */

    /**
     * Get the tag.
     * @param string $input
     * @return string
     */
    static function parseTag($input)
    {
        return strtolower(explode(' ', $input)[0]);
    }

    /**
     * Get all the attributes from a node.
     * @param string $input
     * @return array
     */
    static function parseAttributes($input)
    {
        $attributeAliases = self::aliasesAsString();
        preg_match_all(self::PARSE_ATTR_START . $attributeAliases . self::PARSE_ATTR_END, $input, $attributes);
        preg_match_all(self::PARSE_ATTR_SHORT_START . $attributeAliases . self::PARSE_ATTR_SHORT_END, $input, $shortHandAttributes);
        $countAttributes = count($attributes[1]);
        for($i = 0; $i < $countAttributes; ++$i)
            $attributes[1][$i] = self::getAttributeAlias($attributes[1][$i]);

        $countShortHandAttributes = count($shortHandAttributes[1]);
        for($i = 0; $i < $countShortHandAttributes; ++$i)
            $shortHandAttributes[1][$i] = self::getAttributeAlias($shortHandAttributes[1][$i]);
        if(!empty($attributes) && !empty($shortHandAttributes)) {
            $deduplicate = array_unique(array_merge($shortHandAttributes[1], $attributes[1]));
            $attributeList = [
                array_values($deduplicate),
                array_values(array_intersect_key(array_merge($shortHandAttributes[2], $attributes[2]), $deduplicate))
            ];
        } elseif(empty($attributes) && !empty($shortHandAttributes)) {
            $deduplicate = array_unique($shortHandAttributes[1]);
            $attributeList = [
                array_values($deduplicate),
                array_values(array_intersect_key($shortHandAttributes[2], $deduplicate))
            ];
        } elseif(!empty($attributes) && empty($shortHandAttributes)) {
            $deduplicate = array_unique($attributes[1]);
            $attributeList = [
                array_values($deduplicate),
                array_values(array_intersect_key($attributes[2], $deduplicate))
            ];
        } else
            $attributeList = [];
        return $attributeList;
    }



    /**
     * Make objects from the templates lines.
     * @param array $inputs
     * @return array of SkinElement
     */
    private static function parseLines(array $inputs)
    {
        $skinLayer = self::getSkinLayer();
        $countInputs = count($inputs);
        for($i = 1; $i < $countInputs; ++$i) { //Parcours des lignes
            self::$currentLine = $i;
            $structure[$i] = new SkinElement($skinLayer, $i, $inputs[$i]);
            $structureCount = count($structure); //récupération parent
            for($j = $structureCount; $j > 0; --$j)
                if($structure[$j]->level < $structure[$i]->level) {
                    $structure[$i]->parent = $j;
                    $structure[$i]->toParse = $structure[$j]->toParse && !in_array($structure[$j]->tag, $skinLayer::$specialContainers);
                    if(!$structure[$i]->toParse)
                        $structure[$i]->tag = 'PLAINTEXT';
                    if('PLAINTEXT' === $structure[$i]->tag)
                        $structure[$i]->toParse = FALSE;
                    break;
                }
        }
        return $structure;
    }

    /**
     * Execute special parsing : fetch parent and partial templates.
     * @param string $content
     * @return string
     */
    private static function parseSpecial($content)
    {
        return self::fetchPartial(self::fetchParent($content));
    }

    /**
     * Parse properties in conditions, loops, variables, and translations.
     * @param type $propertyString
     * @return type
     */
    private static function parseProperty($propertyString)
    {
        if(!empty($propertyString)) {
            $variable = str_replace(' ', '', $propertyString);
            $parsedProperty = explode('.', $variable);

            if(!isset($parsedProperty[1])) {
                $type = self::VAR_GLOBAL_CONTEXT;
                $context = '$this->data';
                $property = $parsedProperty[0];
            } else {
                if(in_array($parsedProperty[0], self::$contexts)) {
                    $type = self::VAR_LOOP_CONTEXT;
                    $context = '$' . $parsedProperty[0];
                    $property = $parsedProperty[1];
                } else {
                    $type = self::VAR_LOOP_PROPERTY_CONTEXT;
                    $context = '$this->data[\'' . $parsedProperty[0] . '\']';
                    $property = $parsedProperty[1];
                }
            }
            return ['type' => $type, 'context' => $context, 'property' => $property, 'inputParsed' => $parsedProperty];
        }
        return [];
    }

    /**
     * Parse functions in variable blocks.
     * @param type $functionString
     * @return type
     */
    private static function parseFunctions($functionString)
    {
        $functions = explode(',', str_replace(' ', '', $functionString));
        $countFunctions = count($functions);
        $returnedFunctions = [];
        for($i = 0; $i < $countFunctions; ++$i) {
            if(method_exists('SkinFunctions', $functions[$i]))
                array_push($returnedFunctions, $functions[$i]);
            elseif(!empty($functions[$i]))
                SkinExceptionHandler::addWarning(SkinExceptionHandler::W_FUNCTION_UNDEFINED, ['FUNCTION' => $functions[$i], 'LINE' => self::$currentLine]);
        }
        return $returnedFunctions;
    }

    /* PREPARING DATA FOR REPLACEMENT */
    /**
     * Convert the default structure to node => children hierarchy.
     * @param array $structure
     * @return array
     */
    private static function executeSkin(array $structure)
    {
        $tree = self::mapTree($structure);
        foreach($tree as $node)
            self::buildTemplate($node);
        return $tree;
    }

    /**
     * Prepare the nodes.
     * @param SkinElement $node
     */
    static function buildTemplate(SkinElement $node)
    {
        self::$currentLine = $node->line;
        if(1 === self::$outputMode)
            $tabs = self::setSpacesByLevel($node->level);
        if(!$node->toParse || $node->tag === 'PLAINTEXT') {
            $node->start = 1 === self::$outputMode ? $tabs . $node->input . PHP_EOL : $node->input;
            $node->end = '';
        } elseif(1 === self::$outputMode) {
            $node->start = $tabs . $node->start . PHP_EOL;
            if(!empty($node->end))
                $node->end = $tabs . $node->end . PHP_EOL;
        }

        self::prepareOutputNode($node);
        foreach($node->children as $child)
            self::buildTemplate($child);
    }

    /**
     * Prepare node.
     * @param SkinElement $node
     * @return string
     */
    private static function prepareOutputNode(\SkinElement $node)
    {
        $skinAttributes = self::manageSkinAttributes($node); //Get Skin attributes (skif, skloop and sksample)
        $content = '';

        if(isset($skinAttributes['skif']))
            $node->setCondition(self::parseProperty($skinAttributes['skif']), FALSE);
        elseif(isset($skinAttributes['skelse']))
            $node->setCondition(self::parseProperty($skinAttributes['skelse']), TRUE);

        if(isset($node->children) && !empty($node->children)) {
            if(isset($skinAttributes['skloop'])) {//If element has skloop, data context is set inside the corresponding array
                $explodedLoopAttribute = explode('=>', $skinAttributes['skloop']);
                $parsedProperty = self::parseProperty($explodedLoopAttribute[0]);
                $node->setLoop($parsedProperty, $explodedLoopAttribute[1]);

                $context = 0 === $parsedProperty['type'] ? 'GLOBAL' : $parsedProperty['context'];
                SkeletonReplacer::addDataContext($context, [
                    'index' => '$' . $explodedLoopAttribute[1],
                    'data' => $parsedProperty['context'] . '[\'' . $parsedProperty['property'] . '\']'
                ]);
                if(!in_array($explodedLoopAttribute[1], self::$contexts))
                    array_push(self::$contexts, $explodedLoopAttribute[1]);
            }
        }
        self::prepareVariableBlocks($node, $node->start . $content . $node->end);
        self::prepareTranslateBlocks($node, $node->start . $content . $node->end);
    }
    
    /**
     * Replace variable blocks from the current line.
     * @param string $code
     * @return string
     */
    private static function prepareVariableBlocks($node, $code)//RENAME
    {
        preg_match_all(self::REGEX_VAR_BLOCK, $code, $matchVariable);
        $countVariable = count($matchVariable[1]);
        if($countVariable > 0) {
            for($variables = 0; $variables < $countVariable; ++$variables) {
                $parsedVariable = self::parseProperty($matchVariable[1][$variables]);
                $parsedFunctions = self::parseFunctions($matchVariable[2][$variables]);
                $node->addVariable($matchVariable[0][$variables], $parsedVariable, $parsedFunctions);
            }
        }
        return $code;
    }

    /**
     * Replace translation block from the current line.
     * @param string $code
     * @return string
     */
    private static function prepareTranslateBlocks(\SkinElement $node, $code)//RENAME
    {
        preg_match_all(self::REGEX_TRANSLATE_BLOCK, $code, $matchTranslate);
        $countTranslate = count($matchTranslate[1]);
        if($countTranslate > 0) {
            for($translates = 0; $translates < $countTranslate; ++$translates) {
                $explodeTranslate = explode(',', str_replace(' ', '', $matchTranslate[2][$translates]));
                $countVariables = count($explodeTranslate);
                $variables = [];
                for($i = 0; $i < $countVariables; ++$i) {
                    $parsedVariable = self::parseProperty($explodeTranslate[$i]);
                    if(!empty($parsedVariable))
                        array_push($variables, $parsedVariable);
                }
                $node->addTranslation(['input' => $matchTranslate[0][$translates], 'index' => str_replace(' ', '', $matchTranslate[1][$translates]), 'variables' => $variables]);
            }
        }
        return $code;
    }
}
