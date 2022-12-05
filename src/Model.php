<?php

namespace Ufl;

use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\Inflector\Inflector;
use Doctrine\Inflector\InflectorFactory;
use JsonSerializable;
use Serializable;

class Model implements Serializable, JsonSerializable
{
    private string $_findKeyName = 'id';
    private array $_initValues = array();
    private static Inflector $_inflector;

    /**
     * Model constructor.
     * @param ?mixed $findKey
     * @param ?string $findKeyName
     * @throws Exception
     */
    public function __construct(mixed $findKey = null, ?string $findKeyName = null)
    {
        if (is_string($findKeyName)) {
            $this->setFindKeyName($findKeyName);
        }

        if ($findKey !== null) {
            $this->init($findKey);
        }
    }

    /**
     * @return Inflector
     */
    private static function inflector(): Inflector
    {
        if (static::$_inflector instanceof Inflector ) {
            return static::$_inflector;
        }
        static::$_inflector = InflectorFactory::create()->build();
        return static::$_inflector;
    }

    /**
     * @param mixed $findKey
     * @return QueryBuilder
     */
    protected function initQuery(mixed $findKey): QueryBuilder
    {
        return static::builder()
            ->select('*')
            ->from(static::tableName())
            ->where(static::quoteIdentifier($this->getFindKeyName()) . ' = :findKey')
            ->setParameter('findKey', $findKey);
    }

    /**
     * @param mixed $findKey
     * @throws Exception
     */
    protected function init(mixed $findKey): void
    {
        $row = $this->initQuery($findKey)->executeQuery()->fetchAssociative();
        if (!is_array($row)) {
            $this->{$this->getFindKeyName()} = $findKey;
            return;
        }
        $this->initFields($row);
    }

    /**
     * @return Database
     */
    public static function db(): Database
    {
        return Database::getInstance();
    }

    /**
     * @param string $name
     * @return string
     */
    public static function quoteIdentifier(string $name): string
    {
        return static::db()->getConnection()->quoteIdentifier($name);
    }

    /**
     * @return QueryBuilder
     */
    protected static function builder(): QueryBuilder
    {
        return static::db()->builder();
    }

    /**
     * @return string
     */
    public static function tableName(): string
    {
        $ary = array_reverse(explode('\\', static::class));
        return static::tableize(static::inflector()->pluralize(reset($ary)));
    }

    /**
     * @param string $word
     * @return string
     */
    protected static function tableize(string $word): string
    {
        return static::inflector()->tableize($word);
    }

    /**
     * @param array $row
     */
    private function initFields(array $row): void
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
    public static function toField(string $filed): string
    {
        return static::inflector()->camelize($filed);
    }

    /**
     * @param array $row
     * @return static
     */
    protected static function import(array $row): static
    {
        $obj = new static();
        $obj->initFields($row);
        return $obj;
    }

    /**
     * @return int
     * @throws Exception
     */
    public function delete(): int
    {
        return static::builder()
            ->delete(static::tableName())
            ->where(static::quoteIdentifier($this->getFindKeyName()) . ' = :id')
            ->setParameter('id', $this->{$this->getFindKeyName()})
            ->executeQuery()->rowCount();
    }

    /**
     * @param bool $isCreating
     * @return static
     * @throws Exception
     */
    public function save(bool $isCreating = false): static
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
            return $this;
        }

        $qb = static::builder()
            ->update(static::tableName())
            ->where(static::quoteIdentifier($this->getFindKeyName()) . ' = :id')
            ->setParameter('id', $this->getIndex());

        if ($hasUpdated) {
            $updateRow['updated_at'] = Date::nowString();
        }

        foreach ($updateRow as $key => $value) {
            $qb->set(static::quoteIdentifier($key), ':'. $key);
            $qb->setParameter($key, $value);
        }

        if ($qb->executeQuery()->rowCount() === 1) {
            $this->initFields($updateRow);
            return $this;
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isExists(): bool
    {
        return 0 < count($this->_initValues);
    }

    /**
     * @param $array
     * @param string|null $findKeyName unused parameter. Compatibility only implementation
     * @return static is new created object
     * @throws Exception
     */
    public static function create($array, string $findKeyName = null): static
    {
        $qb = static::builder()
            ->insert(static::tableName());

        foreach ($array as $key => $value) {
            $qb->setValue(static::quoteIdentifier($key), ':' . $key)
                ->setParameter($key, $value);
        }
        $qb->executeQuery();

        $newId = static::db()->lastInsertId();
        $newInstance = new static();
        if (is_string($findKeyName) && $newInstance->getFindKeyName() !== $findKeyName) {
            $newInstance->setFindKeyName($findKeyName);
        }
        $newInstance->init(0 < $newId ? $newId : $array[$newInstance->getFindKeyName()]);
        return $newInstance;
    }

    /**
     * @param array $array
     * @return array
     */
    public static function toTableizeArray(array $array): array
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
    public function toArray(): array
    {
        $row = get_object_vars($this);
        unset($row['_initValues'], $row['_findKeyName']);
        return $row;
    }

    /**
     * @param ?array $row
     * @return array
     */
    public function diff(?array $row = null): array
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
    public static function toFiledArray(array $array): array
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
    public function getIndex(): mixed
    {
        return $this->{self::toField($this->getFindKeyName())};
    }

    /**
     * @param static $obj
     * @param string $key
     * @param bool $isStrict
     * @return bool
     */
    public function eqByKey(mixed $obj, string $key, bool $isStrict = true): bool
    {
        $filed = static::toField($key);
        if (!property_exists($this, $filed) || !property_exists($obj, $filed)) {
            return false;
        }

        if ($isStrict) {
            return $this->{$filed} === $obj->{$filed};
        }
        /** @noinspection TypeUnsafeComparisonInspection */
        return $this->{$filed} == $obj->{$filed};
    }

    /**
     * @param array $row
     */
    public function overwrite(array $row): void
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
    public function initOverwrite(array $row): void
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
    public function getOldValue(string $key): mixed
    {
        return $this->_initValues[$key] ?? null;
    }

    /**
     * @return string[]
     */
    public function getOldValues(): array
    {
        return $this->_initValues;
    }

    /**
     * @param string $findKeyName
     */
    protected function setFindKeyName(string $findKeyName): void
    {
        $this->_findKeyName = $findKeyName;
    }

    /**
     * @return string
     */
    protected function getFindKeyName(): string
    {
        return $this->_findKeyName;
    }

    public function serialize()
    {
        // empty
    }

    public function unserialize($serialized)
    {
        // empty
    }

    public function __serialize(): array
    {
        return [
            'row' => self::toTableizeArray($this->toArray()),
            'key' => $this->getFindKeyName()
        ];
    }

    public function __unserialize(array $data): void
    {
        $this->setFindKeyName($data['key']);
        $this->initFields($data['row']);
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }
}