<?php

/* namespace SkinTemplate; */

abstract class SkinExceptionHandler
{

    const   E_MAIN_TEMPLATE_404 = 0, E_PARENT_TEMPLATE_404 = 1, E_PARTIAL_TEMPLATE_404 = 2, E_CONTENT_TOKEN_MISSING = 3, E_ALIAS_TOKEN_MISSING = 4, E_CLASS_LAYER_MISSING = 5,
            W_INDEX_CONFLICT = 0, W_ATTRIBUTE_CONFLICT = 1, W_INDEX_UNDEFINED = 2, W_FUNCTION_UNDEFINED = 3, W_TAG_INVALID = 4, W_ATTRIBUTE_INVALID = 5, W_VARIABLE_INVALID = 6, W_LOOP_INVALID = 7,
            W_ATTRIBUTE_ADAPTED = 8;

    static
            $errors = [
                'Template "TEMPLATE" not found.', 'Parent template "TEMPLATE" not found.',
                'Partial template "TEMPLATE" not found.', '@content not found in "TEMPLATE" parent template.',
                'Missing loop alias (line LINE)', 'Layer class "CLASS" is not defined.'
            ],
            $warnings = [
                'Index "INDEX" is already defined, overriding (line LINE)', 'Attribute "ATTRIBUTE" is already existing in "ELEMENT" element (line LINE)',
                'Index "INDEX" is not defined in view data (line LINE)', 'Undefined function "FUNCTION" (line LINE)',
                'Tag "TAG" is invalid (line LINE)', 'Attribute "ATTRIBUTE" has been removed from "ELEMENT" element (line LINE)', 
                'Element used as variable must not be an array ("INDEX" on line LINE)', 'Element used as loop must be a numerical indexed list ("INDEX" on line LINE)', 
                'Attribute "ATTRIBUTE" has been adapted into "ADAPTED" in "ELEMENT" element (line LINE)',
                
            ],
            $errorList = [], $warningList = [];

    static function addError($string, array $args)
    {
        if(2 === SkeletonParser::$errorReport || 3 === SkeletonParser::$errorReport) {
            $error = self::$errors[$string];
            foreach ($args as $key => $value)
                $error = str_replace($key, $value, $error);
            array_push(self::$errorList, $error);
        }
    }

    static function addWarning($string, array $args)
    {
        if(1 === SkeletonParser::$errorReport || 3 === SkeletonParser::$errorReport) {
            $warning = self::$warnings[$string];
            foreach ($args as $key => $value)
                $warning = str_replace($key, $value, $warning);
            array_push(self::$warningList, $warning);
        }
    }

    private static function getWarnings()
    {
        $warnings = '';
        $countWarnings = count(self::$warningList);
        for ($i = 0; $i < $countWarnings; ++$i)
            $warnings .= '<div class="skin-report-warnings">' . self::$warningList[$i] . '</div>' . PHP_EOL;
        return $warnings;
    }

    private static function getErrors()
    {
        $errors = '';
        $countErrors = count(self::$errorList);
        for ($i = 0; $i < $countErrors; ++$i)
            $errors .= '<div class="skin-report-errors">' . self::$errorList[$i] . '</div>' . PHP_EOL;
        return $errors;
    }

    static function getReport($reportLevel, array $skeleton)
    {
        switch ($reportLevel) {
            case 1:
                echo '<style></style><div class="skin-report">' . self::getWarnings() . '</div>';
            case 2:
                echo '<div class="skin-report">' . self::getErrors() . '</div>';
            case 3:
                echo '<div class="skin-report">' . self::getWarnings() . self::getErrors() . '</div>';
            case 0:
            default:
                echo '';
        }
        
        echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>';
        echo '<style>.skin-exception-row{display:table-row}.skin-exception-line-element,.skin-exception-line-number,.skin-exception-line-warning{display:table-cell;padding:5px}.skin-exception-line-number{background:#000;color:#fff;font-weight:700;font-family:Arial}.skin-exception-line-warning{background:#000;width:40px}.skin-exception-line-element{color:#fff;font-family:Verdana}.skin-exception-line-element.display-even{background:#333}.skin-exception-line-element.display-odd{background:#444}.skin-exception-line-element-tag{color:#0F8}.skin-exception-line-element-braces{color:#ff0}.skin-exception-line-element-attribute{color:#fcc}.skin-exception-line-element-value{color:#ccf}.skin-exception-line-element-function{color:#cfc}</style>';
        echo '<script>$(function(){function glitchIt(string){var glitch=\'\';for(var i=0;i<string.length;++i){glitch+=string[i]+\'<span></span>\';}return glitch;}function LineOutput(){this.addTabs=function(){var tabs=\'\';while(this.level>0){tabs+=\'&nbsp;\';--this.level;}this.input=tabs+this.input;return this;};this.highlightTag=function(){this.input=this.input.replace(this.tag,\'<span class="skin-exception-line-element-tag">\'+this.tag+\'</span>\')
            return this.input;};this.highlightAttributes=function(){if(this.attributes[0]){for(var i=0;i<this.attributes[0].length;++i){if(\'\'!==this.attributes[1][i]){this.input=this.input.replace(this.attributes[1][i],\'<span class="skin-exception-line-element-value">\'+glitchIt(this.attributes[1][i])+\'</span>\');}}}return this;};this.highlightFunction=function(){return this;};this.highlightBraces=function(){this.input=this.input.replace(/{{/g,\'<span class="skin-exception-line-element-braces">{{</span>\').replace(/}}/g,\'<span class="skin-exception-line-element-braces">}}</span>\');return this;};}function castObject(object){var newObject=new LineOutput();$.each(object,function(key,value){newObject[key]=value;});return newObject;}var skeleton=' . json_encode($skeleton) . ';var output=\'<div class="skin-exception-template">\';$.each(skeleton,function(key,value){var line=castObject(value);output+=\'<div class="skin-exception-row"><span class="skin-exception-line-number">\'+key+\'</span><span class="skin-exception-line-warning"></span>\';output+=0===key%2?\'<span class="skin-exception-line-element display-even">\'+line.addTabs().highlightBraces().highlightAttributes().highlightTag()+\'</span>\':\'<span class="skin-exception-line-element display-odd">\'+line.addTabs().highlightBraces().highlightAttributes().highlightTag()+\'</span>\';output+=\'</div>\';});output+=\'</div>\';$(\'body\').append(output);});</script>';

    }

}
