<?php

namespace Firaiz\Ufl;

use Firaiz\Ufl\Render\IRender;
use Firaiz\Ufl\Traits\SingletonTrait;

class Render implements IRender
{
    use SingletonTrait;

    /** @var ?IRender */
    protected ?IRender $render = null;
    /** @var array */
    protected array $headers = [];

    /**
     * View constructor.
     */
    protected function __construct()
    {
        $config = Config::getInstance();
        $render = $config->get("render");

        if (isset($render['engine']) === false) {
            $render['engine'] = 'Smarty';
        }
        $this->initRender($render['engine']);
        $this->setDefaultHeaders(isset($render['headers']) && is_array($render['headers']) ? $render['headers'] : []);
        $this->setConfigs(isset($render['config']) && is_array($render['config']) ? $render['config'] : []);
    }

    /**
     * @param string $engine is class name. Have a implemented Render interface
     */
    protected function initRender(string $engine): void
    {
        $render = __NAMESPACE__ . '\\Render\\' . $engine . 'Render';
        $this->render = new $render();
    }

    private function setDefaultHeaders($headers): void
    {
        $this->headers = $headers;
    }

    public function setConfigs(array $configs): void
    {
        $this->getRender()->setConfigs($configs);
    }

    private function getRender(): IRender
    {
        return $this->render;
    }

    public function getDefaultHeaders(): array
    {
        return $this->headers;
    }

    public function setLayout(?string $templatePath): void
    {
        $this->getRender()->setLayoutPath($templatePath);
    }

    /**
     * @param mixed|null $var
     */
    public function assign(array|string $name, mixed $var = null, bool $noCache = false): static
    {
        $this->getRender()->assign($name, $var, $noCache);
        return $this;
    }

    public function compile(string $template): string
    {
        return $this->getRender()->compile($template);
    }

    /**
     * @param $template
     */
    public function templateExists($template): bool
    {
        return $this->getRender()->templateExists($template);
    }

    /**
     * @param string|null $layout is template path
     */
    public function setLayoutPath(?string $layout): void
    {
        $this->getRender()->setLayoutPath($layout);
    }

    public function getLayoutPath(): string
    {
        return $this->getRender()->getLayoutPath();
    }

    public function isLayoutMode(): bool
    {
        return $this->getRender()->isLayoutMode();
    }

    /**
     * @param $contentName
     */
    public function setContentName($contentName): void
    {
        $this->getRender()->setContentName($contentName);
    }

    public function getContentName(): string
    {
        return $this->getRender()->getContentName();
    }
}