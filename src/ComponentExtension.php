<?php

declare(strict_types=1);

namespace OlegV\Components;

use Devanych\View\Extension\ExtensionInterface;
use Devanych\View\Renderer;

use RuntimeException;
use Throwable;

final class ComponentExtension implements ExtensionInterface
{
    /**
     * @var string root directory storing components.
     */
    private string $componentsPath;

    private array $css_content = [];
    private array $js_content = [];

    private Renderer $local_renderer;

    /**
     * @param string $componentsPath root directory storing components.
     */
    public function __construct(string $componentsPath, private readonly Renderer $renderer)
    {
        $this->componentsPath = rtrim($componentsPath, '\/');
        $this->local_renderer = new Renderer($componentsPath);
        $this->local_renderer->addExtension($this);
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
     * @param  array  $params
     * @return string
     * @throws Throwable
     */
    public function component(string $directory, array $params = [] ): string
    {
        $component_path = $this->componentsPath . DIRECTORY_SEPARATOR . $directory;
        $template_path = $component_path.DIRECTORY_SEPARATOR . 'template.php';
        if (!file_exists($template_path)) {
            throw new RuntimeException(sprintf(
                'Template file "%s" does not exist.',
                $template_path
            ));
        }

        /*ob_start();
        //print_r($params);
        extract(['data' => $params], EXTR_OVERWRITE);
        require $template_path;
        $content = ob_get_clean();*/
        $content = $this->local_renderer->render($directory.DIRECTORY_SEPARATOR . 'template.php', ['data' => $params]);

        //$content = '<!-- start '.$directory.'-->'.PHP_EOL.$content.PHP_EOL.'<!-- end '.$directory.'-->'.PHP_EOL;

        $css_path = $component_path.DIRECTORY_SEPARATOR.'style.css';
        if (file_exists($css_path)) {
            $this->css_content[$css_path] = '<link rel="stylesheet" href="'.basename($this->componentsPath).DIRECTORY_SEPARATOR.$directory.DIRECTORY_SEPARATOR.'style.css'. '?v=' . filemtime($css_path).'">';
        }
        $js_path = $component_path.DIRECTORY_SEPARATOR.'script.js';
        if (file_exists($js_path)) {
            $this->js_content[$js_path] = '<script src="'.basename($this->componentsPath).DIRECTORY_SEPARATOR.$directory.DIRECTORY_SEPARATOR.'script.js'. '?v=' . filemtime($js_path).'"></script>';
        }

        $this->renderer->block(md5($template_path.serialize($params)), $content);
        return $this->renderer->renderBlock(md5($template_path.serialize($params)));
    }

    public function componentsCss(): string
    {
        return implode(PHP_EOL, $this->css_content).PHP_EOL;
    }
    public function componentsJs(): string
    {
        return PHP_EOL.implode(PHP_EOL, $this->js_content).PHP_EOL;
    }
}