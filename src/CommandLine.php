<?php
namespace Firaiz\Ufl;

use ArrayAccess;
use ReturnTypeWillChange;

/**
 * Class CommandLine
 * @package Firaiz\Ufl
 */
class CommandLine implements ArrayAccess
{
    protected array $argv = array();
    protected int $argc = 0;

    /**
     * CommandLine constructor.
     * @TODO Intellectual parse example : --test=abc --ads -d 1 -v --d "aa values"
     */
    public function __construct()
    {
        if (isset($_SERVER['argv']) && is_array($_SERVER['argv'])) {
            $this->argv = array_slice($_SERVER['argv'], 1);
            $this->argc = count($this->argv);
        }
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     */
    #[ReturnTypeWillChange] public function offsetGet(mixed $offset): mixed
    {
        $opt = $this->getOpt($offset);
        return $this->offsetExists($offset) ? $opt[$offset] : null;
    }

    /**
     * @param int|string $offset
     * @return array
     */
    protected function getOpt(int|string $offset): array
    {
        if (is_int($offset)) {
            return $this->argv;
        }
        $key = $offset . '::';
        $short = '';
        $longs = array();
        if (1 < strlen($offset)) {
            $longs[] = $key;
        } else {
            $short = $key;
        }
        return getopt($short, $longs);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return bool true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     */
    #[ReturnTypeWillChange] public function offsetExists(mixed $offset): bool
    {
        $opt = $this->getOpt($offset);
        return array_key_exists($offset, $opt);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     */
    #[ReturnTypeWillChange] public function offsetSet(mixed $offset, mixed $value): void
    {
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     */
    #[ReturnTypeWillChange] public function offsetUnset(mixed $offset): void
    {
    }
}