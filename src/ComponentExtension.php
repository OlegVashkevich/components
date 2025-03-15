<?php

declare(strict_types=1);

namespace OlegV\Components;

use Devanych\View\Extension\ExtensionInterface;
use Devanych\View\Renderer;
use ReflectionProperty;
use RuntimeException;
use Throwable;

final class ComponentExtension implements ExtensionInterface
{
    /**
     * @var string directory storing components relatively viewDirectory of renderer.
     */
    private string $componentsPath;

    /**
     * @var array<string, string>
     */
    private array $cssContent = [];

    /**
     * @var array<string, string>
     */
    private array $jsContent = [];

    private Renderer $localRenderer;

    private string $viewDirectory;

    /**
     * @param  string  $componentsPath  directory storing components relatively viewDirectory of renderer.
     * @param  Renderer  $renderer
     * @throws RuntimeException
     */
    public function __construct(
        string $componentsPath,
        private readonly Renderer $renderer,
    ) {
        $this->componentsPath = rtrim($componentsPath, '\/');

        //dirty... but need find renderer path to viewDirectory
        $property = new ReflectionProperty(Renderer::class, "viewDirectory");
        $property = $property->getValue($renderer);
        if (is_string($property)) {
            $this->viewDirectory = $property;
        } else {
            throw new RuntimeException('Cannot determine view directory.');
        }

        //need for render component without layout
        $this->localRenderer = clone $renderer;
    }

    /**
     * {@inheritDoc}
     */
    public function getFunctions(): array
    {
        return [
            'component' => [$this, 'component'],
            'componentsCss' => [$this, 'componentsCss'],
            'componentsJs' => [$this, 'componentsJs'],
        ];
    }

    /**
     * Includes the directory.
     *
     * @param  string  $directory
     * @param  array<array-key, mixed>  $params
     * @return string
     * @throws Throwable
     */
    public function component(string $directory, array $params = []): string
    {
        $component_path = $this->viewDirectory.DIRECTORY_SEPARATOR.$this->componentsPath.DIRECTORY_SEPARATOR.$directory;
        $template_path = $component_path.DIRECTORY_SEPARATOR.'template.php';
        if (!file_exists($template_path)) {
            throw new RuntimeException(
                sprintf(
                    'Template file "%s" does not exist.',
                    $template_path,
                ),
            );
        }

        /*ob_start();
        extract(['data' => $params], EXTR_OVERWRITE);
        require $template_path;
        $content = ob_get_clean();*/

        $content = $this->localRenderer->render(
            $this->componentsPath.DIRECTORY_SEPARATOR.$directory.DIRECTORY_SEPARATOR.'template.php',
            ['data' => $params],
        );

        //$content = '<!-- start '.$directory.'-->'.PHP_EOL.$content.PHP_EOL.'<!-- end '.$directory.'-->'.PHP_EOL;

        $css_path = $component_path.DIRECTORY_SEPARATOR.'style.css';
        if (file_exists($css_path)) {
            $this->cssContent[$css_path] = '<link rel="stylesheet" href="'.$this->componentsPath.DIRECTORY_SEPARATOR.$directory.DIRECTORY_SEPARATOR.'style.css'.'?v='.filemtime(
                    $css_path,
                ).'">';
        }
        $js_path = $component_path.DIRECTORY_SEPARATOR.'script.js';
        if (file_exists($js_path)) {
            $this->jsContent[$js_path] = '<script src="'.$this->componentsPath.DIRECTORY_SEPARATOR.$directory.DIRECTORY_SEPARATOR.'script.js'.'?v='.filemtime(
                    $js_path,
                ).'"></script>';
        }

        $this->renderer->block(md5($template_path.serialize($params)), $content);
        return $this->renderer->renderBlock(md5($template_path.serialize($params)));
    }

    public function componentsCss(): string
    {
        return implode(PHP_EOL, $this->cssContent).PHP_EOL;
    }

    public function componentsJs(): string
    {
        return PHP_EOL.implode(PHP_EOL, $this->jsContent).PHP_EOL;
    }
}