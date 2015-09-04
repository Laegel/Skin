<?php

/* namespace SkinTemplate; */

class SkeletonOutput
{
    static function getOutputNode(SkinElement $node)
    {
        $skinAttributes = SkeletonParser::manageSkinAttributes($node); //Get Skin attributes (skif, skloop and sksample)
        $innerContent = '';

        

        self::convertCondition($node, $innerContent);

        self::convertVariable($node, $innerContent);

        self::convertTranslation($node, $innerContent);
    }

    private static function convertCondition($node, $innerContent)
    {
        if ($skinAttributes->hasKey('skif'))
            $innerContent .= '<?php if(isset(self::$data[\'' . $skinAttributes['skif'] . '\']) && self::$data[\'' . $skinAttributes['skif'] . '\']) { ?>';
        elseif ($skinAttributes->hasKey('skelse'))
            $node->start = '<?php if(isset(self::$data[\'' . $skinAttributes['skelse'] . '\']) && !self::$data[\'' . $skinAttributes['skelse'] . '\']) { ?>' . PHP_EOL . $node->start;

        self::convertLoop($node, $innerContent);

        if ($skinAttributes->hasKey('skif') || $skinAttributes->hasKey('skelse'))
            $node->end .= '<?php } ?>' . PHP_EOL;
    }

    private static function convertLoop($param)
    {
        
    }

    private static function convertVariable($variable, $functions)
    {
        $parsedProperty = $variable->explode('.');
        if (!$parsedProperty->hasKey(1)) {
            $data = SkeletonParser::$data;
            $property = 'self::$data, \'' . $parsedProperty[0] . '\'';
        } else {
            $data = [];
            foreach (SkeletonParser::$loopData as $loopVars) {
                if ($parsedProperty[0] === $loopVars['name']) {
                    $data = $loopVars['data'];
                    break;
                }
            }

            $property = SkeletonParser::$contexts->contains($parsedProperty[0]) ? 
                '$' . $parsedProperty[0] . ', \'' . $parsedProperty[1] . '\'' : 
                'self::$data[\'' . $parsedProperty[0] . '\'], \'' . $parsedProperty[1] . '\'';
        }



        $string = '<?php SkinFunctions::output(';
        $treatments = '';
        if (!$functions[0]->isEmpty()) {

            $countFunctions = $functions->count();
            $treatments .= ', [';
            for ($i = 0; $i < $countFunctions; ++$i)
                $treatments .= $i + 1 === $countFunctions ? '\'' . $functions[$i] . '\'' : '\'' . $functions[$i] . '\', ';
            $treatments .= ']';
        }

        $string .= $property . $treatments . ');?>';
        return $string;
    }

}
