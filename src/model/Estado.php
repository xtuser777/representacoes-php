<?php


namespace scr\model;


use mysqli;
use scr\util\Banco;

class Estado 
{
    private $id;
    private $nome;
    private $sigla;
    
    public function __construct(int $id = 0, string $nome = "", string $sigla = "")
    {
        $this->id = $id;
        $this->nome = $nome;
        $this->sigla = $sigla;
    }

    public function getId() : int
    {
        return $this->id;
    }

    public function getNome() : string
    {
        return $this->nome;
    }

    public function getSigla() : string
    {
        return $this->sigla;
    }
    
    public function getById(int $id) : ?Estado
    {
        if ($id <= 0) return null;

        $sql = "
            select est_id,est_nome,est_sigla 
            from estado 
            where est_id = ?;
        ";
        $st = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$st) {
            return null;
        }
        $st->bind_param('i', $id);
        $st->execute();
        if (!($result = $st->get_result()) || $result->num_rows == 0) {
            echo $st->error;
            return null;
        }
        $row = $result->fetch_assoc();
        $e = new Estado(
            $row['est_id'], $row['est_nome'], $row['est_sigla']
        );

        return $e;
    }
    
    public function getAll()
    {
        $sql = "
            SELECT est_id,est_nome,est_sigla 
            FROM estado;
        ";
        $st = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$st) {
            echo Banco::getInstance()->getConnection()->error;
            return array();
        }
        $st->execute();
        if (!($result = $st->get_result()) || $result->num_rows == 0) {
            echo $st->error;
            return array();
        }
        $estados = [];
        for ($i = 0; $i < $result->num_rows; $i++)
        {
            $row = $result->fetch_assoc();
            $estados[] = new Estado(
                $row['est_id'], $row['est_nome'], $row['est_sigla']
            );
        }

        return $estados;
    }

    public function jsonSerialize() 
    {
        return get_object_vars($this);
    }
}
