<?php
namespace AnySys;

/**
 * Class Base
 * @package Ays
 *
 * @property Database $db
 */
abstract class Base
{
    /** @var array overwriting ok */
    protected $singletons = array( 'conf' => 'Config', 'db' => 'Database', 'view' => 'View');
    /** @var array overwriting ok */
    protected $instances = array('request' => 'Request');

    final public function __construct() {
        $this->init();
    }

    /**
     * initialized instances
     */
    protected function init() {
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
    private function initClassName($className) {
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