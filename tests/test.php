<?php

use Devanych\View\Renderer;
use OlegV\Components\ComponentExtension;

error_reporting(E_ALL);
ini_set('display_errors', '1');

require __DIR__.'/../vendor/autoload.php';

$renderer = new Renderer(__DIR__.'/views');
$extension = new ComponentExtension($renderer, 'components/tests/views/components', debug: false);
$renderer->addExtension($extension);
try {
    $content = $renderer->render('catalog', [
        'variableName' => 'значение переменной любого типа',
    ]);
    echo $content;
} catch (Throwable $e) {
}
