<?php
namespace AnySys;

use ArrayAccess;

class CommandLine implements ArrayAccess
{
    protected $argv = array();
    protected $argc = 0;

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
     * @param int|string $offset
     * @return array
     */
    protected function getOpt($offset) {
        if (is_int($offset)) {
            return $this->argv;
        }
        $key = $offset.'::';
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
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset)
    {
        $opt = $this->getOpt($offset);
        return isset($opt[$offset]);
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
    public function offsetGet($offset)
    {
        $opt = $this->getOpt($offset);
        return isset($opt[$offset]) ? $opt[$offset] : null;
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
    public function offsetSet($offset, $value)
    {
        return;
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
    public function offsetUnset($offset)
    {
        return;
    }
}