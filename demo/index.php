<?php

spl_autoload_register(function($className) {
    require_once '../' . $className . '.php';
});

SkinTranslator::$language = isset($_GET['language']) ? $_GET['language'] : 'en';

/*Setting data that will be used in the templates*/
$data = new stdClass();//$data can be an object or an array !

$data->isNameSet = isset($_POST['name']);
$data->name = $_POST['name'];

$data->languages = SkinTranslator::translate('LANGUAGES');
foreach($data->languages as &$language) 
    $language['isSelected'] = $language['value'] === SkinTranslator::$language;

$data->language = SkinTranslator::$language;

/*Initializing Skin*/
$view = new Skin(
    $data, 'views/', 
    SkeletonParser::ERROR_REPORT_NONE, 
    SkeletonParser::OUTPUT_MODE_MINI, true
);

$view->setSamplesDir('./samples/')->setSamplesVariable('Carpenter.Samples')->setTranslationsDir('./translations/');

echo $view->render('content', false)->getContent();//Rendering !