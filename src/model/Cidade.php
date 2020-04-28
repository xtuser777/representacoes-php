<?php namespace scr\model;

use mysqli;
use scr\model\Estado;
use scr\dao\CidadeDAO;

class Cidade 
{
    private $id;
    private $nome;
    private $estado;
    
    public function __construct(int $id, string $nome, Estado $estado) 
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
    
    public static function getById(int $id)
    {
        return $id > 0 ? CidadeDAO::getById($id) : null;
    }
    
    public static function getByEstado(int $estado)
    {
        return $estado > 0 ? CidadeDAO::getByEstado($estado) : null;
    }

    public function jsonSerialize() 
    {
        $this->estado = $this->estado->jsonSerialize();
        return get_object_vars($this);
    }
}
