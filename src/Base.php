<?php
namespace Firaiz\Ufl;

use \Firaiz\Ufl\Container\SessionContainer;
/**
 * Class Base
 *
 * @property Database $db
 * @property Config $conf
 * @property Response $response
 * @property Request $request
 * @property SessionContainer $session
 * @property Header $header
 */
abstract class Base
{
    /** @var array allowed to overwrite */
    protected array $singletons = ['conf' => 'Config', 'db' => 'Database', 'response' => 'Response', 'header' => 'Header'];
    /** @var array allowed to overwrite */
    protected array $instances = ['request' => 'Request', 'session' => SessionContainer::class];

    private array $properties = [];

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
    protected function init(): void
    {
        foreach ($this->singletons as $prop => $cls) {
            $clsName = $this->initClassName($cls);
            $this->{$prop} = forward_static_call([$clsName, 'getInstance']);
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
    private function initClassName($className): string
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

    public function __get(string $name)
    {
        return $this->properties[$name] ?? null;
    }

    public function __set(string $name, $value): void
    {
        $this->properties[$name] = $value;
    }

    public function __isset(string $name): bool
    {
        return isset($this->properties[$name]);
    }
}