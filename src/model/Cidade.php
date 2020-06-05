<?php


namespace scr\model;


use mysqli;
use scr\model\Estado;
use scr\util\Banco;

class Cidade 
{
    private $id;
    private $nome;
    private $estado;
    
    public function __construct(int $id = 0, string $nome = "", Estado $estado = null)
    {
        $this->id = $id;
        $this->nome = $nome;
        $this->estado = $estado;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function getEstado(): Estado
    {
        return $this->estado;
    }
    
    public function getById(int $id)
    {
        if ($id <= 0) return null;

        $sql = "
            select e.est_id,e.est_nome,e.est_sigla,
                   c.cid_id,c.cid_nome 
            from cidade c 
            inner join estado e on e.est_id = c.est_id
            where c.cid_id = ?;
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
        $c = new Cidade(
            $row['cid_id'], $row['cid_nome'],
            new Estado(
                $row['est_id'],$row['est_nome'],$row['est_sigla']
            )
        );

        return $c;
    }
    
    public function getByEstado(int $estado)
    {
        if ($estado <= 0) return null;

        $sql = "
            select e.est_id,e.est_nome,e.est_sigla,
                   c.cid_id,c.cid_nome
            from cidade c 
            inner join estado e on e.est_id = c.est_id
            where c.est_id = ?;
        ";
        $st = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$st) {
            echo Banco::getInstance()->getConnection()->error;
            return array();
        }
        $st->bind_param('i', $estado);
        $st->execute();
        if (!($result = $st->get_result()) || $result->num_rows == 0) {
            echo $st->error;
            return array();
        }
        $cidades = [];
        for ($i = 0; $i < $result->num_rows; $i++) {
            $row = $result->fetch_assoc();
            $cidades[] = new Cidade(
                $row['cid_id'], $row['cid_nome'],
                new Estado(
                    $row['est_id'], $row['est_nome'], $row['est_sigla']
                )
            );
        }

        return $cidades;
    }

    public function jsonSerialize() 
    {
        $this->estado = $this->estado->jsonSerialize();
        return get_object_vars($this);
    }
}
