<?php
namespace UflAs;

use Doctrine\Common\Inflector\Inflector;
use Doctrine\DBAL\Query\QueryBuilder;

class Model
{
    private $_findKeyName = '';
    private $_initValues = array();

    /**
     * Model constructor.
     * @param mixed $findKey
     * @param string $findKeyName
     */
    public function __construct($findKey = null, $findKeyName = 'id')
    {
        $this->setFindKeyName($findKeyName);
        if (!is_null($findKey)) {
            $this->init($findKey);
        }
    }

    /**
     * @param $findKey
     * @return QueryBuilder
     */
    protected function initQuery($findKey) {
        return static::builder()
            ->select('*')
            ->from(static::tableName())
            ->where(static::quoteIdentifier($this->getFindKeyName()) . ' = :findKey')
            ->setParameter('findKey', $findKey);
    }

    /**
     * @param mixed $findKey
     */
    protected function init($findKey)
    {
        $row = $this->initQuery($findKey)->execute()->fetch();
        if (!is_array($row)) {
            $this->{$this->getFindKeyName()} = $findKey;
            return;
        }
        $this->initFields($row);
    }

    /**
     * @return Database
     */
    public static function db()
    {
        return Database::getInstance();
    }

    public static function quoteIdentifier($name)
    {
        return static::builder()->getConnection()->quoteIdentifier($name);
    }

    /**
     * @return QueryBuilder
     */
    protected static function builder()
    {
        return static::db()->builder();
    }

    /**
     * @return string
     */
    public static function tableName()
    {
        $ary = array_reverse(explode('\\', get_called_class()));
        return static::tableize(Inflector::pluralize(reset($ary)));
    }

    /**
     * @param string $word
     * @return string
     */
    protected static function tableize($word)
    {
        return Inflector::tableize($word);
    }

    /**
     * @param array $row
     */
    private function initFields($row)
    {
        foreach ($row as $name => $value) {
            $this->{static::toField($name)} = $value;
            $this->_initValues[$name] = $value;
        }
    }

    /**
     * @param string $filed
     * @return string
     */
    public static function toField($filed)
    {
        return Inflector::camelize($filed);
    }

    /**
     * @param array $row
     * @return static
     */
    protected static function import($row)
    {
        $obj = new static();
        $obj->initFields($row);
        return $obj;
    }

    public function delete()
    {
        static::builder()
            ->delete(static::tableName())
            ->where(static::quoteIdentifier($this->getFindKeyName()) . ' = :id')
            ->setParameter(':id', $this->{$this->getFindKeyName()});
    }

    /**
     * @param bool $isCreating
     * @return bool
     */
    public function save($isCreating = false)
    {
        if ($isCreating && !$this->isExists()) {
            return static::create(static::toTableizeArray($this->toArray()));
        }

        $updateRow = $this->diff();
        $hasUpdated = array_key_exists('updated_at', $this->_initValues);

        if ($hasUpdated && array_key_exists('updated_at', $updateRow)) {
            unset($updateRow['updated_at']);
        }

        if (count($updateRow) <= 0) {
            return true;
        }

        $qb = static::builder()
            ->update(static::tableName())
            ->where(static::quoteIdentifier($this->getFindKeyName()) . ' = :id')
            ->setParameter(':id', $this->getIndex());

        if ($hasUpdated) {
            $updateRow['updated_at'] = Date::nowString();
        }

        foreach ($updateRow as $key => $value) {
            $paramKey = ':' . $key;
            $qb->set(static::quoteIdentifier($key), $paramKey);
            $qb->setParameter($paramKey, $value);
        }

        if ($qb->execute() === 1) {
            $this->initFields($updateRow);
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isExists()
    {
        return 0 < count($this->_initValues);
    }

    /**
     * @param $array
     * @param string $findKeyName
     * @return static is create object
     */
    public static function create($array, $findKeyName = 'id')
    {
        $qb = static::builder()
            ->insert(static::tableName());

        foreach ($array as $key => $value) {
            $qb->setValue(static::quoteIdentifier($key), ':' . $key)
                ->setParameter(':' . $key, $value);
        }
        $qb->execute();

        return new static($qb->getConnection()->lastInsertId(), $findKeyName);
    }

    /**
     * @param array $array
     * @return array
     */
    public static function toTableizeArray($array)
    {
        $result = array();
        foreach ($array as $key => $value) {
            $result[static::tableize($key)] = $value;
        }
        return $result;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $row = get_object_vars($this);
        unset($row['_initValues'], $row['_findKeyName']);
        return $row;
    }

    /**
     * @param array $row
     * @return array
     */
    public function diff($row = null)
    {
        if (is_null($row)) {
            $row = $this->toArray();
        } else {
            $row = static::toFiledArray($row);
        }

        if (count($row) <= 0) {
            return array();
        }

        $result = array();
        foreach ($this->_initValues as $filedKey => $value) {
            $filed = static::toField($filedKey);
            if (array_key_exists($filed, $row) && $value != $row[$filed]) {
                $result[$filedKey] = $row[$filed];
            }
        }

        return $result;
    }

    /**
     * @param array $array
     * @return array
     */
    public static function toFiledArray($array)
    {
        $result = array();
        foreach ($array as $key => $value) {
            $result[static::toField($key)] = $value;
        }
        return $result;
    }

    /**
     * @return mixed
     */
    public function getIndex()
    {
        return $this->{$this->toField($this->getFindKeyName())};
    }

    /**
     * @param static $obj
     * @param string $key
     * @param bool $isStrict
     * @return bool
     */
    public function eqByKey($obj, $key, $isStrict = true)
    {
        $filed = static::toField($key);
        if (!property_exists($this, $filed) || !property_exists($obj, $filed)) {
            return false;
        }

        if ($isStrict) {
            return $this->{$filed} === $obj->{$filed};
        }
        return $this->{$filed} == $obj->{$filed};
    }

    /**
     * @param array $row
     */
    public function overwrite($row)
    {
        foreach ($row as $filedKey => $value) {
            if (array_key_exists($filedKey, $this->_initValues)) {
                $this->{static::toField($filedKey)} = $value;
            }
        }
    }

    /**
     * @param array $row
     */
    public function initOverwrite($row)
    {
        $checkers = array();
        foreach ($row as $key => $v) {
            if (array_key_exists($key, $this->_initValues)) {
                $checkers[] = true;
            }
        }
        if (count($checkers) === count($row)) {
            $this->initFields($row);
        }
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function getOldValue($key) {
        return array_key_exists($key, $this->_initValues) ? $this->_initValues[$key] : null;
    }

    /**
     * @return string[]
     */
    public function getOldValues() {
        return $this->_initValues;
    }

    /**
     * @param string $findKeyName
     */
    protected function setFindKeyName($findKeyName)
    {
        $this->_findKeyName = $findKeyName;
    }

    /**
     * @return string
     */
    protected function getFindKeyName() {
        return $this->_findKeyName;
    }
}