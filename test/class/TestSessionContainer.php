<?php
namespace Ufl\TestClass;

use JsonSerializable;
use Ufl\Container\SessionContainer;

class TestSessionContainer extends SessionContainer implements JsonSerializable
{
    protected function &makeContainer() {
        static $ary;
        $ary = parent::makeContainer();
        /** @noinspection SuspiciousAssignmentsInspection */
        $ary = array();
        return $ary;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        $values = $this->getContainer();
        if (is_string($this->prefix)) {
            return array(
                $this->prefix => $values,
            );
        }
        return $values;
    }
}