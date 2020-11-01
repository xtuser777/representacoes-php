<?php


namespace scr\util;


use mysqli;

class Banco
{
    /** @var Banco|null  */
    private static $instance = null;

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
        if (!isset(self::$instance) || self::$instance === null) {
            self::$instance = new Banco();
        }

        return self::$instance;
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
