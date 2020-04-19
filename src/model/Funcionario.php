<?php namespace scr\model;

use mysqli;
use scr\model\PessoaFisica;
use scr\dao\FuncionarioDAO;

class Funcionario 
{
    private $id;
    private $tipo;
    private $admissao;
    private $demissao;
    private $pessoa;
    
    public function __construct(int $id, int $tipo, string $admissao, string $demissao, PessoaFisica $pessoa) 
    {
        $this->id = $id;
        $this->tipo = $tipo;
        $this->admissao = $admissao;
        $this->demissao = $demissao;
        $this->pessoa = $pessoa;
    }

    public function getId() : int
    {
        return $this->id;
    }

    public function getTipo() : int
    {
        return $this->tipo;
    }

    public function getAdmissao() : string
    {
        return $this->admissao;
    }

    public function getDemissao() : string
    {
        return $this->demissao;
    }

    public function getPessoa() : PessoaFisica
    {
        return $this->pessoa;
    }
    
    public static function getById(mysqli $conn, int $id) : ?Funcionario
    {
        return $id > 0 ? FuncionarioDAO::getById($conn, $id) : -5;
    }
    
    public function insert(mysqli $conn) : int
    {
        if ($this->id != 0 || $this->tipo <= 0 || $this->admissao == null || $this->pessoa == null) { return -5; }
        
        return FuncionarioDAO::insert($conn, $this->tipo, $this->admissao, $this->demissao, $this->pessoa->getId());
    }
    
    public function update(mysqli $conn) : int
    {
        if ($this->id <= 0 || $this->tipo <= 0 || $this->admissao == null || $this->pessoa == null) { return -5; }
        
        return FuncionarioDAO::update($conn, $this->id, $this->tipo, $this->admissao, $this->demissao, $this->pessoa->getId());
    }
    
    public static function delete(mysqli $conn, int $id) : int
    {
        return $id > 0 ? FuncionarioDAO::delete($conn, $id) : -5;
    }
    
    public static function desativar(mysqli $conn, int $id) : int
    {
        return $id > 0 ? FuncionarioDAO::desativar($conn, $id) : -5;
    }
    
    public static function reativar(mysqli $conn, int $id) : int
    {
        return $id > 0 ? FuncionarioDAO::reativar($conn, $id) : -5;
    }

    public function jsonSerialize() 
    {
        $this->pessoa = $this->pessoa->jsonSerialize();
        return get_object_vars($this);
    }
}
