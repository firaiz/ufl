<?php
namespace Firaiz\Ufl\TestClass;

use JsonSerializable;
use ReturnTypeWillChange;
use Firaiz\Ufl\Container\SessionContainer;

class TestSessionContainer extends SessionContainer implements JsonSerializable
{
    protected function &makeContainer():array {
        static $ary;
        $ary = parent::makeContainer();
        /** @noinspection SuspiciousAssignmentsInspection */
        $ary = [];
        return $ary;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    #[ReturnTypeWillChange] public function jsonSerialize(): mixed
    {
        $values = $this->getContainer();
        if (is_string($this->prefix)) {
            return [$this->prefix => $values];
        }
        return $values;
    }
}