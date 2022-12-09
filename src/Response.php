<?php

namespace Firaiz\Ufl;

use JsonException;
use Firaiz\Ufl\Traits\SingletonTrait;

class Response
{
    use SingletonTrait;

    /** @var ?Render */
    protected ?Render $render = null;
    /** @var Header */
    private Header $header;

    /**
     * View constructor.
     */
    protected function __construct()
    {
        $this->header = Header::getInstance();
        $this->header->reset();
    }

    protected function initRender(): void
    {
        $this->render = Render::getInstance();
        $this->header->add($this->render->getDefaultHeaders());
    }

    /**
     * @param ?string $templatePath
     */
    public function setLayout(?string $templatePath): void
    {
        $this->render()->setLayout($templatePath);
    }

    /**
     * @param array|string $name
     * @param mixed|null $var
     * @param bool $noCache
     * @return static
     */
    public function assign(array|string $name, mixed $var = null, bool $noCache = false): static
    {
        $this->render()->assign($name, $var, $noCache);
        return $this;
    }

    /**
     * @param string $template
     */
    public function html(string $template): void
    {
        $this->header()->flush();
        echo $this->compileHtml($template);
    }

    /**
     * @return Header
     */
    public function header(): Header
    {
        return $this->header;
    }

    /**
     * @param string $template
     * @return string
     */
    public function compileHtml(string $template): string
    {
        return $this->render()->compile($template);
    }

    /**
     * @param mixed $data
     * @param ?string $charset
     * @throws JsonException
     */
    public function json(mixed $data,?string $charset = 'utf-8'): void
    {
        if (is_null($charset)) {
            $charset = 'utf-8';
        }
        $this->header()->set(array('Content-Type' => 'application/json; charset=' . $charset));
        $this->header()->flush();
        echo json_encode($data, JSON_THROW_ON_ERROR);
    }

    /**
     * @param mixed $contents is filepath or raw contents or template path
     * @param string $downloadFileName is local file name
     * @param string $contentType
     */
    public function download(mixed $contents, string $downloadFileName, string $contentType = 'application/octet-stream'): void
    {
        $isFile = file_exists($contents) && is_readable($contents) && is_file($contents);

        if ($isFile) {
            $size = filesize($contents);
        } else {
            $render = $this->render();
            if ($render->templateExists($contents)) {
                $contents = $render->compile($contents);
            }
            $size = strlen($contents);
        }

        $header = $this->header();
        $encode = mb_detect_encoding($downloadFileName, 'SJIS,SJIS-win,EUC-JP,UTF-8', true);
        if (is_string($encode) && $encode !== 'UTF-8') {
            $downloadFileName = mb_convert_encoding($downloadFileName, 'UTF-8', $encode);
        }
        $header->set(array(
            'Content-Disposition' => 'attachment; filename*=UTF-8' . "''" . rawurlencode($downloadFileName),
            'Content-Length' => $size,
            'Content-Type' => $contentType
        ));
        $header->flush();
        if ($isFile) {
            readfile($contents);
        } else {
            echo $contents;
        }
    }

    /**
     * @return Render
     */
    private function render(): Render
    {
        if (is_null($this->render)) {
            $this->initRender();
        }
        return $this->render;
    }
}