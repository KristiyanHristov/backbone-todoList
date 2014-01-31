<?php
require_once __DIR__ . '/../vendor/autoload.php';

// Import PHP Classes
require_once __DIR__ . '/../php/Todo.php';
require_once __DIR__ . '/../php/Notebook.php';
require_once __DIR__ . '/../php/DataStorage.php';

$app = new Silex\Application();

// Register Twig
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
));

// Register assetic and set up CSSMin
$app->register(new SilexAssetic\AsseticServiceProvider(), array(
    'assetic.path_to_web' => __DIR__.'/../web',
    'assetic.options' => array(
        'debug' => false,
        'auto_dump_assets' => false
    ),
    'assetic.filters' => $app->protect(function($fm) {
            $fm->set('yui_css', new Assetic\Filter\Yui\CssCompressorFilter(
                __DIR__ . '/../yuicompressor-2.4.7.jar'
            ));

            $fm->set('yui_js', new Assetic\Filter\Yui\JsCompressorFilter(
                __DIR__ . '/../yuicompressor-2.4.7.jar'
            ));
        })
));

// Load Notebook Storage File
$dataStorage = DataStorage::load();

return $app;
