<?php
namespace Firaiz\Ufl;

use Firaiz\Ufl\Container\SessionContainer;
use Firaiz\Ufl\Traits\GetSetPropertiesTrait;

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
    use GetSetPropertiesTrait;

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
        if (str_contains((string) $className, '\\')) {
            return $className;
        }
        return __NAMESPACE__ . '\\' . $className;
    }

    abstract public function execute(): void;
}