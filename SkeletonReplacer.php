<?php

/* namespace SkinTemplate;*/

class SkeletonReplacer
{
    private static $contexts = [], $dataContext = [], 
        $specialProperties = [
            '$' => '\'SKIN_LOOP_COUNT\'', '#' => '\'SKIN_LOOP_INDEX\''
        ];

    /**
     * Walk tree of nodes.
     * @param array $tree
     * @return string
     */
    static function getOutput(array $tree)
    {
        $output = '';
        foreach($tree as $node)
            $output .= self::getOutputNode($node);
        return $output;
    }
    
    /**
     * Creates the output from the current node.
     * @param SkinElement $node
     * @return string
     */
    private static function getOutputNode(SkinElement $node)
    {
        $content = self::replaceDataContext($node);
        self::replaceConditionBlock($node);
        if(isset($node->children) && !empty($node->children)) {
            if(!empty($node->loop)) {
                $content = self::replaceLoopBlock($node);
            } else
                foreach($node->children as $child)
                    $content .= self::getOutputNode($child);
        }
        return self::replaceTranslationBlocks($node, self::replaceVariableBlocks($node, $node->start . $content . $node->end));
    }
    
    /**
     * Binds data to initialize in special contexts (loops).
     * @param type $context
     * @param array $data
     */
    static function addDataContext($context, array $data)
    {
        if(!isset(self::$dataContext[$context]))
            self::$dataContext[$context] = [$data];
        else
            array_push(self::$dataContext[$context], $data);
    }

    
    /**
     * Inject internal data into the template.
     * @param SkinElement $node
     */
    private static function replaceDataContext(SkinElement $node)
    {
        $context = empty(self::$contexts) ? 'GLOBAL' : end(self::$contexts);
        if(isset(self::$dataContext[$context])) {
            $dataContext = '<?php ';
            $countData = count(self::$dataContext[$context]);
            for($i = 0; $i < $countData; ++$i)
                $dataContext .= '$skinLocalVars[\'' . self::$dataContext[$context][$i]['index'] . '\'] = [\'SKIN_LOOP_INDEX\' => 1, \'SKIN_LOOP_COUNT\' => isset(' . self::$dataContext[$context][$i]['data'] . ') ? count(' . self::$dataContext[$context][$i]['data'] . ') : 0];';
            $dataContext .= '?>';
            unset(self::$dataContext[$context]);
            $node->start = $dataContext . $node->start;
        }
    }

    /**
     * Replace variable blocks by PHP code.
     * @param SkinElement $node
     * @param type $code
     * @return type
     */
    private static function replaceVariableBlocks(SkinElement $node, $code)
    {
        $countVariables = count($node->variables);
        for($i = 0; $i < $countVariables; ++$i) {
            $property = '(isset(' . $node->variables[$i]['context'] . ') ? ' . $node->variables[$i]['context'] . ' : NULL), \'' . $node->variables[$i]['property'] . '\'';

            $string = '<?php SkinFunctions::output(';
            $treatments = '';

            if(!empty($node->variables[$i]['functions'])) {
                $countFunctions = count($node->variables[$i]['functions']);
                $treatments .= ', [';
                for($j = 0; $j < $countFunctions; ++$j)
                    $treatments .= $j + 1 === $countFunctions ? '\'' . $node->variables[$i]['functions'][$j] . '\'' : '\'' . $node->variables[$i]['functions'][$j] . '\', ';
                $treatments .= ']';
            }

            if(isset($node->variables[$i]['property']) && isset(self::$specialProperties[$node->variables[$i]['property']])) {
                $property = '$skinLocalVars[\'$' . $node->variables[$i]['inputParsed'][0] . '\'], ' . self::$specialProperties[$node->variables[$i]['property']];
            }
                
            $string .= $property . $treatments . ');?>';

            $code = str_replace($node->variables[$i]['input'], $string, $code);
        }
        return $code;
    }

    /**
     * Replace translation blocks by PHP code.
     * @param \SkinElement $node
     * @param type $code
     * @return type
     */
    private static function replaceTranslationBlocks(SkinElement $node, $code)//TODO : allow possibility to call a property from property => {= TEST | test.test =}
    {
        $countTranslations = count($node->translations);
        for($i = 0; $i < $countTranslations; ++$i) {
            $countArguments = count($node->translations[$i]['variables']);
            $variables = '[';
            for($j = 0; $j < $countArguments; ++$j)
                $variables .= $j + 1 === $countArguments ?
                        $node->translations[$i]['variables'][$j]['context'] . '[\'' . $node->translations[$i]['variables'][$j]['property'] . '\']' :
                        $node->translations[$i]['variables'][$j]['context'] . '[\'' . $node->translations[$i]['variables'][$j]['property'] . '\'], ';
            $variables .= ']';
            $code = str_replace($node->translations[$i]['input'], '<?php SkinFunctions::translate(\'' . $node->translations[$i]['index'] . '\', ' . $variables . ');?>', $code);
        }
        return $code;
    }

    /**
     * Add PHP loops.
     * @param \SkinElement $node
     * @param type $content
     * @return type
     */
    private static function replaceLoopBlock(SkinElement $node, $content = '')
    {
        array_push(self::$contexts, '$' . $node->loop['alias']);
        $property = $node->loop['context'] . '[\'' . $node->loop['property'] . '\']';
        $content .= '<?php if(isset(' . $property . '))foreach(' . $property . ' as $' . $node->loop['alias'] . ') { ?>' . PHP_EOL;
        
        foreach($node->children as $child)
        
            $content .= self::getOutputNode($child);
        array_pop(self::$contexts);
        return $content .= '<?php ++$skinLocalVars[\'$' . $node->loop['alias'] . '\'][\'SKIN_LOOP_INDEX\']; } ?>' . PHP_EOL;
    }

    /**
     * Add PHP conditions.
     * @param \SkinElement $node
     */
    private static function replaceConditionBlock(SkinElement $node)
    {
        if(!empty($node->condition)) {
            $property = $node->condition['context'] . '[\'' . $node->condition['property'] . '\']';
            $operator = $node->condition['conditionType'] ? '!' : '';
            $node->start = '<?php if(isset(' . $property . ') && ' . $operator . 'SkinFunctions::toBoolean(' . $property . ')) { ?>' . PHP_EOL . $node->start;
            $node->end .= '<?php } ?>' . PHP_EOL;
        }
    }
}
