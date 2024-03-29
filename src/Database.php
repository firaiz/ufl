<?php

namespace Firaiz\Ufl;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ConnectionException;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Result;
use PDO;
use Firaiz\Ufl\Traits\SingletonTrait;

/**
 * Class Database
 * @package Firaiz\Ufl
 */
class Database
{
    final public const PARAM_NULL = PDO::PARAM_NULL;
    final public const PARAM_INT = PDO::PARAM_INT;
    final public const PARAM_STR = PDO::PARAM_STR;
    final public const PARAM_LOB = PDO::PARAM_LOB;
    final public const PARAM_BOOL = PDO::PARAM_BOOL;
    final public const PARAM_INT_ARRAY = Connection::PARAM_INT_ARRAY;
    final public const PARAM_STR_ARRAY = Connection::PARAM_STR_ARRAY;

    use SingletonTrait;

    /** @var ?Connection */
    protected ?Connection $connection = null;

    /**
     * Database constructor.
     */
    private function __construct()
    {
        // empty
    }

    /**
     * Creates a connection object based on the specified parameters.
     *
     * @param array|null $options is optional
     * @return bool true is connected
     */
    public function connect(array $options = null): bool
    {
        if (is_null($options)) {
            $config = Config::getInstance();
            if ($config->has('database.dsn')) {
                $options = ['url' => $config->get('database.dsn')];
            } else {
                $options = $config->get('database');
            }
        }

        if ($this->connection instanceof Connection) {
            /** @noinspection PhpInternalEntityUsedInspection */
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
     */
    public function isConnected(): bool
    {
        if (!($this->connection instanceof Connection)) {
            return false;
        }
        return $this->connection->isConnected();
    }

    public function getConnection(): ?Connection
    {
        return $this->connection;
    }

    /**
     * Creates a new instance of a SQL query builder.
     */
    public function builder(): QueryBuilder
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
     * @throws Exception
     */
    public function fetchAll(string $sql, array $params = [], array $types = []): array
    {
        return $this->select($sql, $params, $types)->fetchAllAssociative();
    }

    /**
     * @param $sql
     * @throws Exception
     */
    public function select($sql, array $params = [], array $types = []): Result
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
     * @throws Exception
     */
    public function fetchRow(string $statement, array $params = [], array $types = []): array
    {
        return $this->select($statement, $params, $types)->fetchAssociative();
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
     * @return int|string The number of affected rows.
     *
     * @throws Exception
     */
    public function delete(string $tableExpression, array $identifier, array $types = []): int|string
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
     * @return int|string The number of affected rows.
     * @throws Exception
     */
    public function update(string $tableExpression, array $data, array $identifier, array $types = []): int|string
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
     * @return int|string The number of affected rows.
     * @throws Exception
     */
    public function insert(string $tableExpression, array $data, array $types = []): int|string
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
    public function quote(mixed $input, string $type = null): string
    {
        return $this->connection->quote($input);
    }

    /**
     * Starts a transaction by suspending auto-commit mode.
     *
     * @throws Exception
     */
    public function begin(): void
    {
        $this->connection->beginTransaction();
    }

    /**
     * Commits the current transaction.
     *
     *
     * @throws ConnectionException If the commit failed due to no active transaction or
     * @throws Exception
     *                                            because the transaction was marked for rollback only.
     */
    public function commit(): void
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
     * @throws Exception
     */
    public function rollBack(): void
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
     * @return int|string A string representation of the last inserted ID.
     * @throws Exception
     */
    public function lastInsertId(string $seqName = null): int|string
    {
        return $this->connection->lastInsertId();
    }

    /**
     * Closes the connection.
     */
    public function close(): void
    {
        $this->connection->close();
    }
}