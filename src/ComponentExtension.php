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
     * @param  Renderer  $renderer
     * @param  string  $webRootPath
     * @param  string  $componentsPath  directory storing components relatively viewDirectory of renderer.
     * @param  bool  $debug  - ignore array offset warnings
     */
    public function __construct(
        private readonly Renderer $renderer,
        private readonly string $webRootPath = "/",
        string $componentsPath = "components/",
        private readonly bool $debug = false,
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
     * @param  array<array-key, mixed>  $data
     * @return string
     */
    public function component(string $directory, array $data = []): string
    {
        $component_path = $this->viewDirectory.DIRECTORY_SEPARATOR.$this->componentsPath.DIRECTORY_SEPARATOR.$directory;
        $template_path = $component_path.DIRECTORY_SEPARATOR.'template.php';
        try {
            if (!file_exists($template_path)) {
                if ($this->debug) {
                    $path = $template_path;
                } else {
                    $path = $directory;
                }
                throw new RuntimeException(
                    sprintf(
                        'Component "%s" does not exist.',
                        $path,
                    ),
                );
            }
        } catch (RuntimeException $e) {
            return $this->getError($e->getMessage());
        }

        /*ob_start();
        extract(['data' => $params], EXTR_OVERWRITE);
        require $template_path;
        $content = ob_get_clean();*/

        $data = new Data($data, $this->debug);

        try {
            $content = $this->localRenderer->render(
                $this->componentsPath.DIRECTORY_SEPARATOR.$directory.DIRECTORY_SEPARATOR.'template.php',
                ['data' => $data],
            );
        } catch (Throwable $e) {
            return $this->getError($e->getMessage());
        }

        //$content = '<!-- start '.$directory.'-->'.PHP_EOL.$content.PHP_EOL.'<!-- end '.$directory.'-->'.PHP_EOL;
        //todo copy to assert folder
        $css_path = $component_path.DIRECTORY_SEPARATOR.'style.css';
        if (file_exists($css_path)) {
            $this->cssContent[$css_path] = '<link rel="stylesheet" href="/'.$this->webRootPath.'/'.$directory.'/'.'style.css'.'?v='.filemtime(
                    $css_path,
                ).'">';
        }
        //todo copy to assert folder
        $js_path = $component_path.DIRECTORY_SEPARATOR.'script.js';
        if (file_exists($js_path)) {
            $this->jsContent[$js_path] = '<script src="/'.$this->webRootPath.'/'.$directory.'/'.'script.js'.'?v='.filemtime(
                    $js_path,
                ).'"></script>';
        }

        $this->renderer->block(md5($template_path.serialize($data)), $content);
        return $this->renderer->renderBlock(md5($template_path.serialize($data)));
    }

    private function getError(string $massage): string
    {
        return '<span style="border: 1px solid red;
            max-width: 200px;
            display: inline-block;
            overflow: hidden;
            word-break: break-all;
            padding: 10px 20px;
            vertical-align: middle;"
        >'.$massage.'</span>';
    }

    public function componentsCss(): string
    {
        //todo merge into one file
        return implode(PHP_EOL, $this->cssContent).PHP_EOL;
    }

    public function componentsJs(): string
    {
        //todo merge into one file
        return PHP_EOL.implode(PHP_EOL, $this->jsContent).PHP_EOL;
    }
}