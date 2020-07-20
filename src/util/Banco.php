<?php


namespace scr\util;


use mysqli;

class Banco
{
    private static $instances = [];
    /** @var mysqli */
    private $conn = null;

    private function __construct() { }

    private function __clone() { }

    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize singleton");
    }

    public static function getInstance()
    {
        $session_id = session_id();
        if (!isset(self::$instances[$session_id])) {
            self::$instances[$session_id] = new Banco();
        }

        return self::$instances[$session_id];
    }

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

    public function getConnection(): ?mysqli
    {
        return $this->conn;
    }
}
