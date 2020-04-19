<?php namespace scr\model;

use mysqli;
use scr\model\Contato;
use scr\dao\PessoaFisicaDAO;

class PessoaFisica 
{
    private $id;
    private $nome;
    private $rg;
    private $cpf;
    private $nascimento;
    private $contato;
    
    public function __construct(int $id, string $nome, string $rg, string $cpf, string $nascimento, Contato $contato)
    {
        $this->id = $id;
        $this->nome = $nome;
        $this->rg = $rg;
        $this->cpf = $cpf;
        $this->nascimento = $nascimento;
        $this->contato = $contato;
    }

    public function getId() : int
    {
        return $this->id;
    }

    public function getNome() : string
    {
        return $this->nome;
    }

    public function getRg() : string
    {
        return $this->rg;
    }

    public function getCpf() : string
    {
        return $this->cpf;
    }

    public function getNascimento() : string
    {
        return $this->nascimento;
    }

    public function getContato() : Contato
    {
        return $this->contato;
    }
    
    public static function getById(mysqli $conn, int $id) : ?PessoaFisica
    {
        return $id > 0 ? PessoaFisicaDAO::getById($conn, $id) : -5;
    }
    
    public static function verifyCpf(mysqli $conn, string $cpf) : bool
    {
        return strlen(trim($cpf)) > 0 && PessoaFisicaDAO::countCpf($conn, $cpf) > 0;
    }

    public function insert(mysqli $conn) : int
    {
        if ($this->id != 0 || strlen(trim($this->nome)) <= 0 || strlen(trim($this->rg)) <= 0 || strlen(trim($this->cpf)) <= 0 || $this->nascimento == null || $this->contato == null) { return -5; }
        
        return PessoaFisicaDAO::insert($conn, $this->nome, $this->rg, $this->cpf, $this->nascimento, $this->contato->getId());
    }
    
    public function update(mysqli $conn) : int
    {
        if ($this->id <= 0 || strlen(trim($this->nome)) <= 0 || strlen(trim($this->rg)) <= 0 || strlen(trim($this->cpf)) <= 0 || $this->nascimento == null || $this->contato == null) { return -5; }
        
        return PessoaFisicaDAO::update($conn, $this->id, $this->nome, $this->rg, $this->cpf, $this->nascimento, $this->contato->getId());
    }
    
    public static function delete(mysqli $conn, int $id) : int
    {
        return $id > 0 ? PessoaFisicaDAO::delete($conn, $id) : -5;
    }

    public function jsonSerialize() 
    {
        $this->contato = $this->contato->jsonSerialize();
        return get_object_vars($this);
    }
}
