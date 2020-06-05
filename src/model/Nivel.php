<?php


namespace scr\model;


use scr\util\Banco;

class Nivel 
{
    private $id;
    private $descricao;
    
    public function __construct(int $id = 0, string $descricao = "")
    {
        $this->id = $id;
        $this->descricao = $descricao;
    }

    public function getId() : int
    {
        return $this->id;
    }

    public function getDescricao() : string
    {
        return $this->descricao;
    }
    
    public function getById(int $id) : ?Nivel
    {
        if ($id <= 0) return null;

        $sql = "
            select niv_id,niv_descricao
            from nivel 
            where niv_id = ?;
        ";
        $st = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$st) {
            echo Banco::getInstance()->getConnection()->error;
            return null;
        }
        $st->bind_param('i', $id);
        $st->execute();
        if (!($result = $st->get_result()) || $result->num_rows == 0) {
            echo $st->error;
            return null;
        }
        $row = $result->fetch_assoc();
        $n = new Nivel($row['niv_id'], $row['niv_descricao']);

        return $n;
    }
    
    public function getAll() : array
    {
        $sql = "
            select niv_id,niv_descricao 
            from nivel;
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
        $niveis = [];
        for ($i = 0; $i < $result->num_rows; $i++) {
            $row = $result->fetch_assoc();
            $niveis[] = new Nivel($row['niv_id'], $row['niv_descricao']);
        }

        return $niveis;
    }

    public function jsonSerialize() 
    {
        return get_object_vars($this);
    }
}
