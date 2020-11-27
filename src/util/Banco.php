<?php


namespace scr\util;


use Exception;
use mysqli;
use mysqli_result;
use mysqli_stmt;

class Banco
{
    /** @var Banco|null  */
    private static $instance = null;

    /** @var mysqli */
    private $conn = null;

    /** @var mysqli_stmt */
    private $stmt = null;

    /** @var mysqli_result */
    private $result = null;

    private function __construct() { }

    private function __clone() { }

    public function __wakeup()
    {
        throw new Exception("Cannot unserialize singleton");
    }

    public static function getInstance()
    {
        if (!isset(self::$instance) || self::$instance === null) {
            self::$instance = new Banco();
        }

        return self::$instance;
    }

    /**]
     * @return bool
     */
    public function open(): bool
    {
        if (($this->conn != null && $this->conn->server_info == null) || $this->conn == null)
        {
            $this->conn = new mysqli('localhost', 'scr', 'scr123globo', 'scr');
            $this->conn->set_charset('utf8');

            if ($this->conn->connect_errno) {
                echo "Erro ao conectar-se ao banco: " . $this->conn->connect_error;
                $this->conn = null;

                return false;
            }
        }

        return true;
    }

    /**
     * @return mysqli|null
     */
    public function getConnection(): ?mysqli
    {
        return $this->conn;
    }

    /**
     * @param string $sql
     * @return bool
     */
    public function prepareStatement(string $sql): bool
    {
        if ($this->conn === null || strlen(trim($sql)) === 0)
            return false;

        $this->stmt = $this->conn->prepare($sql);

        if (!$this->stmt) {
            echo $this->conn->error;
            return false;
        }

        return true;
    }

    /**
     * @param string $types
     * @param array $params
     * @return bool
     */
    public function addParameters(string $types, array $params): bool
    {
        if ($this->conn === null || $this->stmt === null || strlen(trim($types)) === 0 || count($params) === 0)
            return false;

        if (!$this->stmt->bind_param($types,...$params)) {
            echo $this->stmt->error;
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function executeStatement(): bool
    {
        if ($this->conn === null || $this->stmt === null)
            return false;

        if (!$this->stmt->execute()) {
            echo $this->stmt->error;
            return false;
        }

        return true;
    }

    /**
     * @return int
     */
    public function getLastInsertId(): int
    {
        if ($this->conn === null || $this->stmt === null)
            return -10;
        
        return $this->stmt->insert_id;
    }

    /**
     * @return int
     */
    public function getAffectedRows(): int
    {
        if ($this->conn === null || $this->stmt === null)
            return -10;
        
        return $this->stmt->affected_rows;
    }

    /**
     * @param string $sql
     * @return mysqli_result|null
     */
    public function getResultQuery(string $sql): ?mysqli_result
    {
        if ($this->conn === null || strlen(trim($sql)) === 0)
            return null;

        $result = $this->conn->query($sql);

        if (!$result) {
            echo $this->conn->error;
            return null;
        }

        return $result;
    }

    /**
     * @return mysqli_result|null
     */
    public function getResult(): ?mysqli_result
    {
        if ($this->conn === null || $this->stmt === null)
            return null;

        $result = $this->stmt->get_result();

        if (!$result) {
            echo $this->stmt->error;
            return null;
        }

        return $result;
    }
}
