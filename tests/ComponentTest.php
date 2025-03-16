<?php

namespace Tests;

use Devanych\View\Renderer;
use OlegV\Components\ComponentExtension;
use PHPUnit\Framework\TestCase;
use Throwable;

class ComponentTest extends TestCase
{
    /**
     * @throws Throwable
     */
    public function testComponents(): void
    {
        $test_content = file_get_contents(__DIR__.'/data/output.txt');

        $renderer = new Renderer('tests/views');
        $extension = new ComponentExtension($renderer, "components", "components");
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

    /**
     * @throws Throwable
     */
    public function testComponentPathException(): void
    {
        $this->expectExceptionMessage(
            'Template file "tests/views/components/bad_component/template.php" does not exist.',
        );
        $renderer = new Renderer('tests/views');
        $extension = new ComponentExtension($renderer, "components", "components");
        $renderer->addExtension($extension);
        $content = $renderer->render('bad_component');
        echo $content;
    }
}