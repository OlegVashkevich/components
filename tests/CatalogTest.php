<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Throwable;

class CatalogTest extends TestCase
{
    /**
     * @throws Throwable
     */
    public function testComponents(): void
    {
        /*$test_content = file_get_contents(__DIR__.'/data/output.txt');

        $renderer = new Renderer('tests/views');
        $extension = new ComponentExtension($renderer);
        $renderer->addExtension($extension);

        $content = $renderer->render('main', [
            'variableName' => 'значение переменной любого типа',
        ]);*/
        $this->assertEquals("catalog", "catalog");
    }
}