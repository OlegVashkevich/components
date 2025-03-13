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
    <link rel="stylesheet" href="components/content/style.css?v=1741875141">'.PHP_EOL.'<link rel="stylesheet" href="components/content2/style.css?v=1741875141">'.PHP_EOL.'<link rel="stylesheet" href="components/content3/style.css?v=1741875141">'.PHP_EOL.'</head>
<body class="app">

    <p>Page Content</p>
    
<p>Page Content1tttt1</p>    
<p>Page Content1</p>    <p>Page Content2</p>

<p>Page Content1</p>
<p>Page Content1tttt11</p>
<p>Page Content1</p>
<p>Page Content1tttt22</p>    <p>Page Content3</p>

<p>Page Content1</p><p>Page Content2</p>

<p>Page Content1</p>
<p>Page Content1tttt11</p>
<p>Page Content1</p>
<p>Page Content1tttt22</p>    <div>значение переменной любого типа</div>
'.PHP_EOL.'<script src="components/content/script.js?v=1741875141"></script>'.PHP_EOL.'<script src="components/content2/script.js?v=1741875141"></script>'.PHP_EOL.'<script src="components/content3/script.js?v=1741875141"></script>'.PHP_EOL.'</body>
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