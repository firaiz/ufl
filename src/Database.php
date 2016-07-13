<?php
namespace AnySys;

use AnySys\Inherits\QueryCacheBuilder;
use Doctrine\DBAL\Cache\QueryCacheProfile;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception\InvalidArgumentException;
use PDO;

class Database
{
    /** @var Connection */
    protected $connection;

    protected static $_instance = null;

    protected $hasCache = null;

    /**
     * Database constructor.
     */
    private function __construct()
    {
        // empty
    }

    /**
     * @return Database
     */
    public static function getInstance()
    {
        if (!(self::$_instance instanceof Database)) {
            self::$_instance = new Database();
        }
        return self::$_instance;
    }

    /**
     * Creates a connection object based on the specified parameters.
     *
     * @param array $options is optional
     * @return bool true is connected
     * @throws \Doctrine\DBAL\DBALException
     */
    public function connect($options = null)
    {
        if (is_null($options)) {
            $config = Config::getInstance();
            if ($config->has('database.dsn')) {
                $options = array('url' => $config->get('database.dsn'));
            } else {
                $options = $config->get('database');
            }
        }

        if (is_object($this->connection) && $this->connection instanceof Connection) {
            $params = $this->connection->getParams();
            if (
                isset($options['url']) && $options['url'] === $params['url'] ||
                $options === $params
            ) {
                return $this->isConnected();
            }
        }

        $this->connection = DriverManager::getConnection($options);
        return $this->isConnected();
    }

    /**
     * @return QueryCacheProfile|null
     */
    public function getCacheProfile() {
        if (!$this->hasCacheImpl()) {
            return null;
        }
        return new QueryCacheProfile();
    }

    /**
     * @return bool
     */
    public function hasCacheImpl() {
        if ($this->hasCache === false) {
            return false;
        }
        $cacheImpl = $this->connection->getConfiguration()->getResultCacheImpl();
        $this->hasCache = !is_null($cacheImpl);
        return $this->hasCache;
    }

    /**
     * Creates a new instance of a SQL query builder.
     *
     * @return \AnySys\Inherits\QueryCacheBuilder
     */
    public function builder()
    {
        return new QueryCacheBuilder($this->connection);
    }

    /**
     * Prepares and executes an SQL query and returns the result as an associative array.
     *
     * @param string $sql The SQL query.
     * @param array $params The query parameters.
     * @param array $types The query parameter types.
     *
     * @return array
     */
    public function fetchAll($sql, array $params = array(), $types = array())
    {
        return $this->connection->executeQuery($sql, $params, $types, $this->getCacheProfile())->fetchAll();
    }

    /**
     * Prepares and executes an SQL query and returns the first row of the result
     * as an associative array.
     *
     * @param string $statement The SQL query.
     * @param array $params The query parameters.
     * @param array $types The query parameter types.
     *
     * @return array
     */
    public function fetchRow($statement, array $params = array(), array $types = array())
    {
        return $this->connection->executeQuery($statement, $params, $types, $this->getCacheProfile())->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Executes an SQL DELETE statement on a table.
     *
     * Table expression and columns are not escaped and are not safe for user-input.
     *
     * @param string $tableExpression The expression of the table on which to delete.
     * @param array $identifier The deletion criteria. An associative array containing column-value pairs.
     * @param array $types The types of identifiers.
     *
     * @return integer The number of affected rows.
     *
     * @throws InvalidArgumentException
     */
    public function delete($tableExpression, array $identifier, array $types = array())
    {
        return $this->connection->delete($tableExpression, $identifier, $types);
    }

    /**
     * Executes an SQL UPDATE statement on a table.
     *
     * Table expression and columns are not escaped and are not safe for user-input.
     *
     * @param string $tableExpression The expression of the table to update quoted or unquoted.
     * @param array $data An associative array containing column-value pairs.
     * @param array $identifier The update criteria. An associative array containing column-value pairs.
     * @param array $types Types of the merged $data and $identifier arrays in that order.
     *
     * @return integer The number of affected rows.
     */
    public function update($tableExpression, array $data, array $identifier, array $types = array())
    {
        return $this->connection->update($tableExpression, $data, $identifier, $types);
    }

    /**
     * Inserts a table row with specified data.
     *
     * Table expression and columns are not escaped and are not safe for user-input.
     *
     * @param string $tableExpression The expression of the table to insert data into, quoted or unquoted.
     * @param array $data An associative array containing column-value pairs.
     * @param array $types Types of the inserted data.
     *
     * @return integer The number of affected rows.
     */
    public function insert($tableExpression, array $data, array $types = array())
    {
        return $this->connection->insert($tableExpression, $data, $types);
    }

    /**
     * Quotes a given input parameter.
     *
     * @param mixed $input The parameter to be quoted.
     * @param string|null $type The type of the parameter.
     *
     * @return string The quoted parameter.
     */
    public function quote($input, $type = null)
    {
        return $this->connection->quote($input, $type);
    }

    /**
     * Starts a transaction by suspending auto-commit mode.
     *
     * @return void
     */
    public function begin()
    {
        $this->connection->beginTransaction();
    }


    /**
     * Commits the current transaction.
     *
     * @return void
     *
     * @throws \Doctrine\DBAL\ConnectionException If the commit failed due to no active transaction or
     *                                            because the transaction was marked for rollback only.
     */
    public function commit()
    {
        $this->connection->commit();
    }


    /**
     * Cancels any database changes done during the current transaction.
     *
     * This method can be listened with onPreTransactionRollback and onTransactionRollback
     * eventlistener methods.
     *
     * @throws \Doctrine\DBAL\ConnectionException If the rollback operation failed.
     */
    public function rollBack()
    {
        $this->connection->rollBack();
    }

    /**
     * Returns the ID of the last inserted row, or the last value from a sequence object,
     * depending on the underlying driver.
     *
     * Note: This method may not return a meaningful or consistent result across different drivers,
     * because the underlying database may not even support the notion of AUTO_INCREMENT/IDENTITY
     * columns or sequences.
     *
     * @param string|null $seqName Name of the sequence object from which the ID should be returned.
     *
     * @return string A string representation of the last inserted ID.
     */
    public function lastInsertId($seqName = null)
    {
        return $this->connection->lastInsertId($seqName);
    }

    /**
     * Closes the connection.
     *
     * @return void
     */
    public function close()
    {
        $this->connection->close();
    }

    /**
     * Whether an actual connection to the database is established.
     *
     * @return boolean
     */
    public function isConnected()
    {
        return $this->connection->isConnected();
    }

    /**
     * Fetches extended error information associated with the last database operation.
     *
     * @return array The last error information.
     */
    public function errorInfo()
    {
        return $this->connection->errorInfo();
    }

    /**
     * Fetches the SQLSTATE associated with the last database operation.
     *
     * @return integer The last error code.
     */
    public function errorCode()
    {
        return $this->connection->errorCode();
    }
}