<?php
namespace UflAs;

/**
 * Class Base
 *
 * @property Database $db
 * @property Config $conf
 * @property Response $view
 * @property Request $request
 * @property SessionContainer $session
 */
abstract class Base
{
    /** @var array allowed overwrite */
    protected $singletons = array('conf' => 'Config', 'db' => 'Database', 'response' => 'Response');
    /** @var array allowed overwrite */
    protected $instances = array('request' => 'Request', 'session' => 'SessionContainer');

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
        if (0 < strpos('\\', $className)) {
            return $className;
        }
        return __NAMESPACE__ . '\\' . $className;
    }

    /**
     * @return void
     */
    abstract public function execute();
}