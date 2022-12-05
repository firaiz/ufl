<?php
namespace Ufl;

/**
 * Class Base
 *
 * @property Database $db
 * @property Config $conf
 * @property Response $response
 * @property Request $request
 * @property Container\SessionContainer $session
 * @property Header $header
 */
abstract class Base
{
    /** @var array allowed to overwrite */
    protected array $singletons = ['conf' => 'Config', 'db' => 'Database', 'response' => 'Response', 'header' => 'Header'];
    /** @var array allowed to overwrite */
    protected array $instances = ['request' => 'Request', 'session' => \Ufl\Container\SessionContainer::class];

    /**
     * Base constructor.
     */
    final public function __construct()
    {
        $this->init();
    }

    /**
     * initialized instances
     */
    protected function init()
    {
        foreach ($this->singletons as $prop => $cls) {
            $clsName = $this->initClassName($cls);
            /** @noinspection PhpUndefinedMethodInspection */
            $this->{$prop} = $clsName::getInstance();
        }

        foreach ($this->instances as $prop => $cls) {
            $clsName = $this->initClassName($cls);
            $this->{$prop} = new $clsName;
        }
    }

    /**
     * @param $className
     * @return string
     */
    private function initClassName($className)
    {
        if (str_contains($className, '\\')) {
            return $className;
        }
        return __NAMESPACE__ . '\\' . $className;
    }

    /**
     * @return void
     */
    abstract public function execute(): void;
}