<?php namespace scr\util;

use mysqli;

class Banco extends Singleton
{
    /** @var mysqli */
    private $conn = null;
    
    /**
     * The Singleton's constructor should always be private to prevent direct
     * construction calls with the `new` operator.
     */
    protected function __construct() { }

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
