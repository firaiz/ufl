<?php
namespace UflAs\Session;
use DateTime;
use PDO;
use PDOStatement;
use UflAs\StringUtility;

class PDOSessionHandler implements SessionHandlerInterface
{
    /** @var PDO */
    private $pdo;
    /** @var string */
    private $savePath;
    
    public function __construct()
    {
        if (!defined('IS_LEGACY_PHP') && IS_LEGACY_PHP) {
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
    protected function getTime()
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
    protected function connect()
    {
        if ($this->pdo instanceof PDO) {
            return $this->pdo;
        }
        $this->pdo = new PDO('sqlite:' . $this->savePath);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $this->pdo;
    }

    /**
     * @return PDOStatement
     */
    protected function createPrepare()
    {
        $db = $this->connect();
        return $db->prepare('CREATE TABLE IF NOT EXISTS sessions ("sid" TEXT(64) NOT NULL,"data" TEXT,"expire_date" INTEGER NOT NULL,PRIMARY KEY ("sid" ASC));');
    }

    /**
     * @return PDOStatement
     */
    protected function writePrepare()
    {
        $db = $this->connect();
        return $db->prepare('REPLACE INTO sessions ("sid", "data", "expire_date") VALUES (:sid, :data, :expire);');
    }

    /**
     * @return PDOStatement
     */
    protected function readPrepare()
    {
        $db = $this->connect();
        return $db->prepare('SELECT * FROM sessions WHERE sid = :sid');
    }

    /**
     * @return PDOStatement
     */
    protected function deletePrepare()
    {
        $db = $this->connect();
        return $db->prepare('DELETE FROM sessions WHERE sid = :sid');
    }

    protected function gcPrepare()
    {
        $db = $this->connect();
        return $db->prepare('DELETE FROM sessions WHERE expire_date <= :lifetime');
    }

    /**
     * @param PDOStatement $stmt
     * @param array $params
     * @return bool
     */
    protected function exec($stmt, $params = null) {
        return $stmt->execute($params);
    }

    public function sid() {
        return $this->create_sid();
    }

    public function v4()
    {
        return StringUtility::uuid('');
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
    public function close()
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
    public function destroy($session_id)
    {
        return $this->exec($this->deletePrepare(), array(':sid' => $session_id));
    }

    /**
     * Cleanup old sessions
     * @link http://php.net/manual/en/sessionhandlerinterface.gc.php
     * @param int $maxlifetime <p>
     * Sessions that have not updated for
     * the last maxlifetime seconds will be removed.
     * </p>
     * @return bool <p>
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     * </p>
     * @since 5.4.0
     */
    public function gc($maxlifetime)
    {
        return $this->exec($this->gcPrepare(), array(':lifetime' => $this->getTime() - $maxlifetime));
    }

    /**
     * Initialize session
     * @link http://php.net/manual/en/sessionhandlerinterface.open.php
     * @param string $save_path The path where to store/retrieve the session.
     * @param string $name The session name.
     * @return bool <p>
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     * </p>
     * @since 5.4.0
     */
    public function open($save_path, $name)
    {
        $this->savePath = $save_path;
        return $this->exec($this->createPrepare());
    }

    /**
     * Read session data
     * @link http://php.net/manual/en/sessionhandlerinterface.read.php
     * @param string $session_id The session id to read data for.
     * @return string <p>
     * Returns an encoded string of the read data.
     * If nothing was read, it must return an empty string.
     * Note this value is returned internally to PHP for processing.
     * </p>
     * @since 5.4.0
     */
    public function read($session_id)
    {
        $stmt = $this->readPrepare();
        $stmt->execute(array(':sid' => $session_id));
        $row = $stmt->fetch();
        $stmt->closeCursor();
        return is_array($row) ? $row['data'] : '';
    }

    /**
     * Write session data
     * @link http://php.net/manual/en/sessionhandlerinterface.write.php
     * @param string $session_id The session id.
     * @param string $session_data <p>
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
    public function write($session_id, $session_data)
    {
        return $this->exec(
            $this->writePrepare(),
            array(
                ':sid' => $session_id,
                ':data' => $session_data,
                ':expire' => $this->getTime()
            )
        );
    }

    public function create_sid()
    {
        return $this->v4() . $this->v4();
    }
}
