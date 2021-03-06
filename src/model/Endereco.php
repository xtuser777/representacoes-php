<?php namespace scr\model;

use mysqli;
use scr\model\Cidade;
use scr\dao\EnderecoDAO;

class Endereco 
{
    private $id;
    private $rua;
    private $numero;
    private $bairro;
    private $complemento;
    private $cep;
    private $cidade;
    
    public function __construct(int $id, string $rua, string $numero, string $bairro, string $complemento, string $cep, Cidade $cidade) 
    {
        $this->id = $id;
        $this->rua = $rua;
        $this->numero = $numero;
        $this->bairro = $bairro;
        $this->complemento = $complemento;
        $this->cep = $cep;
        $this->cidade = $cidade;
    }

    public function getId() : int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getRua() : string
    {
        return $this->rua;
    }

    public function getNumero() : string
    {
        return $this->numero;
    }

    public function getBairro() : string
    {
        return $this->bairro;
    }

    public function getComplemento() : string
    {
        return $this->complemento;
    }

    public function getCep() : string
    {
        return $this->cep;
    }

    public function getCidade() : Cidade
    {
        return $this->cidade;
    }
    
    public static function getById(int $id) : ?Endereco
    {
        return $id > 0 ? EnderecoDAO::getById($id) : null;
    }
    
    public function insert() : int
    {
        if ($this->id != 0 || strlen(trim($this->rua)) <= 0 || strlen(trim($this->numero)) <= 0 || strlen(trim($this->bairro)) <= 0 || strlen(trim($this->cep)) <= 0 || $this->cidade == null) { return -5; }
        
        return EnderecoDAO::insert($this->rua, $this->numero, $this->bairro, $this->complemento, $this->cep, $this->cidade->getId());
    }
    
    public function update() : int
    {
        if ($this->id <= 0 || strlen(trim($this->rua)) <= 0 || strlen(trim($this->numero)) <= 0 || strlen(trim($this->bairro)) <= 0 || strlen(trim($this->cep)) <= 0 || $this->cidade == null) { return -5; }
        
        return EnderecoDAO::update($this->id, $this->rua, $this->numero, $this->bairro, $this->complemento, $this->cep, $this->cidade->getId());
    }
    
    public static function delete(int $id) : int
    {
        return $id > 0 ? EnderecoDAO::delete($id) : -5;
    }

    public function jsonSerialize() 
    {
        $this->cidade = $this->cidade->jsonSerialize();
        return get_object_vars($this);
    }
}
