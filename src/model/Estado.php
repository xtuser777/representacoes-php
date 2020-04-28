<?php namespace scr\model;

use mysqli;
use scr\dao\EstadoDAO;

class Estado 
{
    private $id;
    private $nome;
    private $sigla;
    
    public function __construct(int $id, string $nome, string $sigla)
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
    
    public static function getById(int $id) : ?Estado
    {
        return $id > 0 ? EstadoDAO::getById($id) : null;
    }
    
    public static function getAll()
    {
        return EstadoDAO::getAll();
    }

    public function jsonSerialize() 
    {
        return get_object_vars($this);
    }
}
