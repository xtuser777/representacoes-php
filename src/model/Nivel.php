<?php namespace scr\model;

use mysqli;
use scr\dao\NivelDAO;

class Nivel 
{
    private $id;
    private $descricao;
    
    public function __construct(int $id, string $descricao)
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
    
    public static function getById(mysqli $conn, int $id) : ?Nivel
    {
        return $id > 0 ? NivelDAO::getById($conn, $id) : null;
    }
    
    public static function getAll(mysqli $conn) : array
    {
        return NivelDAO::getAll($conn);
    }

    public function jsonSerialize() 
    {
        return get_object_vars($this);
    }
}
