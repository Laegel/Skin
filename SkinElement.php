<?php

/* namespace SkinTemplate;*/

class SkinElement
{
    public $layer, $tag, $line, $input,
            $start, $end, $parent,
            $attributes = [], $type, 
            $loop, $condition,
            $variables = [], $translations = [],
            $level = 0, $children = [], $toParse = TRUE;

    /**
     * SkinElement constructor.
     * @param string $layer
     * @param integer $line
     * @param string $input
     */
    public function __construct($layer, $line, $input)
    {
        $this->line = $line;
        $this->layer = $layer;
        $this->input = str_replace("\t", '    ', $input);
        $this->setTag(SkeletonParser::parseTag(trim($this->input)));
        $this->setAttributes(SkeletonParser::parseAttributes($this->input));
        $this->setLevel();
        $this->input = trim($this->input);
    }
    
    /**
     * Add a parsed variable to current node.
     * @param string $input
     * @param array $variable
     * @param array $functions
     */
    function addVariable($input, array $variable, array $functions = [])
    {
        $variable['functions'] = $functions;
        $variable['input'] = $input;
        array_push($this->variables, $variable);
    }
    
    /**
     * Add a parsed translation to current node.
     * @param array $translation
     */
    function addTranslation(array $translation)
    {
        array_push($this->translations, $translation);
    }
            
    /**
     * Set the parsed condition to current node.
     * @param array $condition
     * @param boolean $type
     */
    function setCondition(array $condition, $type)
    {
        $condition['conditionType'] = $type;
        $this->condition = $condition;
    }
    
    /**
     * Set the parsed loop to current node.
     * @param array $loop
     * @param string $alias
     */
    function setLoop(array $loop, $alias)
    {
        $loop['alias'] = $alias;
        $this->loop = $loop;
    }

    /**
     * Set the node level from the indent in skeleton.
     */
    private function setLevel()
    {
        preg_match('~[\s]*~', $this->input, $match);
        $this->level = strlen($match[0]);
    }

    /**
     * Replace node format by attributes.
     * @param string $format
     * @param string $name
     * @param string $value
     * @return string
     */
    private function associate($format, $name, $value)
    {
        if ($name != 'style')
            $value = str_replace(';', ' ', $value);
        return str_replace(['NAME', 'VALUE'], [$name, preg_replace('~\s+~', ' ', $value)], $format);
    }

    private function injectClass($className)
    {
        $this->attributes;
    }

    /**
     * Set node structure from passed tag.
     * @param string $tag
     */
    private function setTag($tag)
    {
        $skinLayer = $this->layer;
        $elements = $skinLayer::getElements();
        $elementsAlias = $skinLayer::getElementsAlias();
        $snippets = $skinLayer::getSnippets();
        if (isset($elements[$tag])) {
            if (isset($elements[$tag]['output'])) {//If the output is already defined
                $tags = $skinLayer::getTagStructure();
                if (!$elements[$tag]['selfClosing']) {
                    $format = $tags['doubleClosing'];
                    $this->end = str_replace('TAG', $tag, $format['end']);
                }
                $this->start = $elements[$tag]['output'];
            } else {
                $tags = $skinLayer::getTagStructure();
                if ($elements[$tag]['selfClosing'])
                    $format = $tags['selfClosing'];
                else {
                    $format = $tags['doubleClosing'];
                    $this->end = str_replace('TAG', $tag, $format['end']);
                }
                $this->start = str_replace('TAG', $tag, $format['start']);
            }
        } elseif (isset($elementsAlias[$tag])) {
            if (isset($elementsAlias[$tag]['output']))//If the output is already defined
                $this->start = $elementsAlias[$tag]['output'];
            else {
                $this->end = isset($elementsAlias[$tag]['end']) ? $elementsAlias[$tag]['end'] : '';
                $this->start = $elementsAlias[$tag]['start'];
            }
            $this->dataInject = $tag;
        } elseif (isset($snippets[$tag])) {
            
        } else {
            $tag = 'PLAINTEXT';
            $this->toParse = FALSE;
            $this->start = '';
        }
        $this->tag = $tag;
    }

    /**
     * Set node attributes from passed attributes.
     * @param array $attributes
     */
    private function setAttributes(array $attributes)
    {
        $skinLayer = $this->layer;
        $this->attributes = $attributes;
        if (!empty($attributes)) {
            $globalAttributes = $skinLayer::getGlobalAttributes();
            $elements = $skinLayer::getElements();

            $attributesOutput = '';
            $countAttributes = count($attributes[0]);
            if (isset($this->dataInject) && !in_array('class', $attributes[0])) {
                array_push($attributes[0], 'class');
                array_push($attributes[1], $this->dataInject);
            }

            for ($i = 0; $i < $countAttributes; ++$i) {
                if (!in_array($attributes[0][$i], SkeletonParser::$skinAttributes)) {
                    if (isset($this->dataInject) && 'class' === $attributes[0][$i])
                        $attributes[1][$i] = $this->dataInject . ';' . $attributes[1][$i];

                    if ('PLAINTEXT' !== $this->tag && !in_array($attributes[0][$i], $globalAttributes) && !(isset($elements[$this->tag]['attributes']) && in_array($attributes[0][$i], $elements[$this->tag]['attributes']))) {
                        if(method_exists($skinLayer, 'invalidAttribute')) {//Adapt an invalid attribute
                            $adaptedAttribute = $skinLayer::invalidAttribute($attributes[0][$i]);
                            SkinExceptionHandler::addWarning(SkinExceptionHandler::W_ATTRIBUTE_ADAPTED, ['ATTRIBUTE' => $attributes[0][$i], 'ADAPTED' => $adaptedAttribute, 'ELEMENT' => $this->tag, 'LINE' => SkeletonParser::$currentLine]);
                            $attributes[0][$i] = $adaptedAttribute;
                        } else {//Remove invalid attribute
                            SkinExceptionHandler::addWarning(SkinExceptionHandler::W_ATTRIBUTE_INVALID, ['ATTRIBUTE' => $attributes[0][$i], 'ELEMENT' => $this->tag, 'LINE' => SkeletonParser::$currentLine]);
                            continue;
                        }
                    }
                    $attributesOutput .= $i + 1 == $countAttributes ? $this->associate($skinLayer::$format, $attributes[0][$i], $attributes[1][$i]) : $this->associate($skinLayer::$format, $attributes[0][$i], $attributes[1][$i]) . ' ';
                }
            }
            $this->start = str_replace('ATTR', $attributesOutput, $this->start);
        } else
            $this->start = str_replace(' ATTR', '', $this->start);
    }
}
