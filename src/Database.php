<?php

namespace Ufl;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ConnectionException;
use Doctrine\DBAL\Driver\Statement;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception\InvalidArgumentException;
use Doctrine\DBAL\Query\QueryBuilder;
use PDO;

/**
 * Class Database
 * @package Ufl
 */
class Database
{
    const PARAM_NULL = PDO::PARAM_NULL;
    const PARAM_INT = PDO::PARAM_INT;
    const PARAM_STR = PDO::PARAM_STR;
    const PARAM_LOB = PDO::PARAM_LOB;
    const PARAM_BOOL = PDO::PARAM_BOOL;
    const PARAM_INT_ARRAY = Connection::PARAM_INT_ARRAY;
    const PARAM_STR_ARRAY = Connection::PARAM_STR_ARRAY;

    /** @var static */
    protected static $_instance;
    /** @var Connection */
    protected $connection;

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
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Creates a connection object based on the specified parameters.
     *
     * @param array $options is optional
     * @return bool true is connected
     * @throws DBALException
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
                (isset($options['url']) && $options['url'] === $params['url']) ||
                $options === $params
            ) {
                return $this->isConnected();
            }
        }

        $this->connection = DriverManager::getConnection($options);
        return $this->isConnected();
    }

    /**
     * Whether an actual connection to the database is established.
     *
     * @return bool
     */
    public function isConnected()
    {
        if (!($this->connection instanceof Connection)) {
            return false;
        }
        return $this->connection->isConnected();
    }

    /**
     * @return Connection
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * Creates a new instance of a SQL query builder.
     *
     * @return QueryBuilder
     */
    public function builder()
    {
        return $this->connection->createQueryBuilder();
    }

    /**
     * Prepares and executes an SQL query and returns the result as an associative array.
     *
     * @param string $sql The SQL query.
     * @param array $params The query parameters.
     * @param array $types The query parameter types.
     *
     * @return array
     * @throws DBALException
     */
    public function fetchAll($sql, array $params = array(), $types = array())
    {
        return $this->select($sql, $params, $types)->fetchAll();
    }

    /**
     * @param $sql
     * @param array $params
     * @param array $types
     * @return Statement
     * @throws DBALException
     */
    public function select($sql, array $params = array(), array $types = array())
    {
        return $this->connection->executeQuery($sql, $params, $types);
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
     * @throws DBALException
     */
    public function fetchRow($statement, array $params = array(), array $types = array())
    {
        return $this->select($statement, $params, $types)->fetch(PDO::FETCH_ASSOC);
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
     * @return int The number of affected rows.
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
     * @return int The number of affected rows.
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
     * @return int The number of affected rows.
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
     * @throws ConnectionException If the commit failed due to no active transaction or
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
     * @throws ConnectionException If the rollback operation failed.
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
}