<?php

namespace Tests;

use Devanych\View\Renderer;
use Exception;
use OlegV\Components\ComponentExtension;
use PHPUnit\Framework\TestCase;
use Throwable;

class ComponentTest extends TestCase
{
    /**
     * @throws Exception
     * @throws Throwable
     */
    public function testConstructor(): void
    {
        $test_content = file_get_contents(__DIR__.'/data/output.txt');

        $renderer = new Renderer('tests/views');
        $extension = new ComponentExtension('components', $renderer);
        $renderer->addExtension($extension);

        $content = $renderer->render('main', [
            'variableName' => 'значение переменной любого типа',
        ]);
        $this->assertEquals($this->clearOutput($test_content), $this->clearOutput($content));
    }

    private function clearOutput(mixed $data): ?string
    {
        if (is_string($data)) {
            return preg_replace("/\s+/u", '', $data);
        }
        return null;
    }
}