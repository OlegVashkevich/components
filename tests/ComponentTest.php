<?php

namespace Tests;

use Exception;
use OlegV\Components\ComponentExtension;
use PHPUnit\Framework\TestCase;
use Devanych\View\Renderer;

class ComponentTest extends TestCase
{

    /**
     * @throws Exception
     * @throws \Throwable
     */
    public function testConstructor(): void {
        $test_content = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Page Title</title>
        <meta name="description" content="Page Description">
    <link rel="stylesheet" href="content/style.css?v=1741869139">'.PHP_EOL.'<link rel="stylesheet" href="content2/style.css?v=1741869139">'.PHP_EOL.'</head>
<body class="app">

    <p>Page Content</p>
    
<p>Page Content2tttt1</p>    
<p>Page Content2</p>    <p>Page Content3</p>

<p>Page Content2</p>
<p>Page Content2tttt11</p>
<p>Page Content2</p>
<p>Page Content2tttt22</p>    <div>значение переменной любого типа</div>
'.PHP_EOL.'<script src="content/script.js?v=1741869367"></script>'.PHP_EOL.'<script src="content2/script.js?v=1741869367"></script>'.PHP_EOL.'</body>
</html>';

        $renderer = new Renderer('tests/views');
        $extension = new ComponentExtension('tests/components',$renderer);
        $renderer->addExtension($extension);

        $content = $renderer->render('main', [
            'variableName' => 'значение переменной любого типа',
        ]);
        $this->assertEquals($test_content, $content);
    }
}