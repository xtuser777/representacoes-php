<?php namespace scr\model;

use mysqli;
use scr\model\Endereco;
use scr\dao\ContatoDAO;

class Contato 
{
    private $id;
    private $telefone;
    private $celular;
    private $email;
    private $endereco;
    
    public function __construct(int $id, string $telefone, string $celular, string $email, Endereco $endereco) 
    {
        $this->id = $id;
        $this->telefone = $telefone;
        $this->celular = $celular;
        $this->email = $email;
        $this->endereco = $endereco;
    }

    public function getId() : int
    {
        return $this->id;
    }

    public function getTelefone() : string
    {
        return $this->telefone;
    }

    public function getCelular() : string
    {
        return $this->celular;
    }

    public function getEmail() : string
    {
        return $this->email;
    }

    public function getEndereco() : Endereco
    {
        return $this->endereco;
    }

    public static function getById(mysqli $conn, int $id) : ?Contato
    {
        return $id > 0 ? ContatoDAO::getById($conn, $id) : null;
    }
    
    public function insert(mysqli $conn) : int
    {
        if ($this->id != 0 || strlen(trim($this->telefone)) <= 0 || strlen(trim($this->celular)) <= 0 || strlen(trim($this->email)) <= 0 || $this->endereco == null) { return -5; }
        
        return ContatoDAO::insert($conn, $this->telefone, $this->celular, $this->email, $this->endereco->getId());
    }
    
    public function update(mysqli $conn) : int
    {
        if ($this->id <= 0 || strlen(trim($this->telefone)) <= 0 || strlen(trim($this->celular)) <= 0 || strlen(trim($this->email)) <= 0 || $this->endereco == null) { return -5; }
        
        return ContatoDAO::update($conn, $this->id, $this->telefone, $this->celular, $this->email, $this->endereco->getId());
    }
    
    public static function delete(mysqli $conn, int $id) : int
    {
        return $id > 0 ? ContatoDAO::delete($conn, $id) : -5;
    }
    
    public function jsonSerialize()
    {
        $this->endereco = $this->endereco->jsonSerialize();
        return get_object_vars($this);
    }
}
