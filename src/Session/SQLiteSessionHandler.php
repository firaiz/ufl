<?php /** @noinspection SpellCheckingInspection */

namespace Ufl\Session;

use DateTime;
use Exception;
use PDO;
use PDOStatement;
use ReturnTypeWillChange;
use Ufl\StringUtility;

/**
 * Class SQLiteSessionHandler
 * @package Ufl\Session
 */
class SQLiteSessionHandler implements SessionHandlerInterface
{
    /** @var ?PDO */
    private ?PDO $pdo;
    /** @var string */
    private string $savePath;

    public function __construct()
    {
        if (PHP_VERSION_ID < 504000) {
            session_set_save_handler(
                array($this, "open"),
                array($this, "close"),
                array($this, "read"),
                array($this, "write"),
                array($this, "destroy"),
                array($this, "gc"),
                array($this, "create_sid")
            );
            register_shutdown_function('session_write_close');
        } else {
            session_set_save_handler($this, true);
        }
    }

    /**
     * @return int
     */
    protected function getTime(): int
    {
        $oldTZ = @date_default_timezone_get();
        date_default_timezone_set('UTC');
        $dateTime = new DateTime();
        date_default_timezone_set($oldTZ);
        return $dateTime->format('YmdHis');
    }

    /**
     * @return PDO
     */
    protected function connect(): PDO
    {
        if (!($this->pdo instanceof PDO)) {
            $this->pdo = new PDO('sqlite:' . $this->savePath);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        }
        return $this->pdo;
    }

    /**
     * @return PDOStatement
     */
    protected function createPrepare(): PDOStatement
    {
        return $this->connect()->prepare('CREATE TABLE IF NOT EXISTS sessions ("sid" TEXT(64) NOT NULL,"data" TEXT,"expire_date" INTEGER NOT NULL,PRIMARY KEY ("sid" ASC));');
    }

    /**
     * @return PDOStatement
     */
    protected function writePrepare(): PDOStatement
    {
        return $this->connect()->prepare('REPLACE INTO sessions ("sid", "data", "expire_date") VALUES (:sid, :data, :expire);');
    }

    /**
     * @return PDOStatement
     */
    protected function readPrepare(): PDOStatement
    {
        return $this->connect()->prepare('SELECT * FROM sessions WHERE sid = :sid');
    }

    /**
     * @return PDOStatement
     */
    protected function deletePrepare(): PDOStatement
    {
        return $this->connect()->prepare('DELETE FROM sessions WHERE sid = :sid');
    }

    protected function gcPrepare(): bool|PDOStatement
    {
        return $this->connect()->prepare('DELETE FROM sessions WHERE expire_date <= :lifetime');
    }

    /**
     * @param PDOStatement $stmt
     * @param array|null $params
     * @return bool
     */
    protected function exec(PDOStatement $stmt, array $params = null): bool
    {
        return $stmt->execute($params);
    }

    /**
     * @return string
     * @throws Exception
     */
    public function sid(): string
    {
        return $this->create_sid();
    }

    /**
     * @return string
     * @throws Exception
     */
    public function create_sid(): string
    {
        return StringUtility::random(64, false);
    }
    // 以下 implements

    /**
     * Close the session
     * @link http://php.net/manual/en/sessionhandlerinterface.close.php
     * @return bool <p>
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     * </p>
     * @since 5.4.0
     */
    #[ReturnTypeWillChange] public function close(): bool
    {
        $this->pdo = null;
        return true;
    }

    /**
     * Destroy a session
     * @link http://php.net/manual/en/sessionhandlerinterface.destroy.php
     * @param string $session_id The session ID being destroyed.
     * @return bool <p>
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     * </p>
     * @since 5.4.0
     */
    #[ReturnTypeWillChange] public function destroy(string $session_id): bool
    {
        return $this->exec($this->deletePrepare(), array(':sid' => $session_id));
    }

    /**
     * Cleanup old sessions
     * @link http://php.net/manual/en/sessionhandlerinterface.gc.php
     * @param int $max_lifetime <p>
     * Sessions that have not updated for
     * the last maxlifetime seconds will be removed.
     * </p>
     * @return bool <p>
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     * </p>
     * @since 5.4.0
     */
    #[ReturnTypeWillChange] public function gc(int $max_lifetime): bool
    {
        return $this->exec($this->gcPrepare(), array(':lifetime' => $this->getTime() - $max_lifetime));
    }

    /**
     * Initialize session
     * @link http://php.net/manual/en/sessionhandlerinterface.open.php
     * @param string $path The path where to store/retrieve the session.
     * @param string $name The session name.
     * @return bool <p>
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     * </p>
     * @since 5.4.0
     */
    #[ReturnTypeWillChange] public function open(string $path, string $name): bool
    {
        $this->savePath = $path;
        return $this->exec($this->createPrepare());
    }

    /**
     * Read session data
     * @link http://php.net/manual/en/sessionhandlerinterface.read.php
     * @param string $id The session id to read data for.
     * @return string <p>
     * Returns an encoded string of the read data.
     * If nothing was read, it must return an empty string.
     * Note this value is returned internally to PHP for processing.
     * </p>
     * @since 5.4.0
     */
    #[ReturnTypeWillChange] public function read(string $id): string
    {
        $stmt = $this->readPrepare();
        $stmt->execute(array(':sid' => $id));
        $row = $stmt->fetch();
        $stmt->closeCursor();
        return is_array($row) ? $row['data'] : '';
    }

    /**
     * Write session data
     * @link http://php.net/manual/en/sessionhandlerinterface.write.php
     * @param string $id The session id.
     * @param string $data <p>
     * The encoded session data. This data is the
     * result of the PHP internally encoding
     * the $_SESSION superglobal to a serialized
     * string and passing it as this parameter.
     * Please note sessions use an alternative serialization method.
     * </p>
     * @return bool <p>
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     * </p>
     * @since 5.4.0
     */
    #[ReturnTypeWillChange] public function write(string $id, string $data): bool
    {
        return $this->exec(
            $this->writePrepare(),
            array(
                ':sid' => $id,
                ':data' => $data,
                ':expire' => $this->getTime()
            )
        );
    }
}
