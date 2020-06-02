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
    
    public static function getById(int $id) : ?PessoaFisica
    {
        return $id > 0 ? PessoaFisicaDAO::getById( $id) : -5;
    }
    
    public static function verifyCpf(string $cpf) : bool
    {
        return strlen(trim($cpf)) > 0 && PessoaFisicaDAO::countCpf($cpf) > 0;
    }

    public function insert() : int
    {
        if ($this->id != 0 || strlen(trim($this->nome)) <= 0 || strlen(trim($this->rg)) <= 0 || strlen(trim($this->cpf)) <= 0 || $this->nascimento == null || $this->contato == null) { return -5; }
        
        return PessoaFisicaDAO::insert($this->nome, $this->rg, $this->cpf, $this->nascimento, $this->contato->getId());
    }
    
    public function update() : int
    {
        if ($this->id <= 0 || strlen(trim($this->nome)) <= 0 || strlen(trim($this->rg)) <= 0 || strlen(trim($this->cpf)) <= 0 || $this->nascimento == null || $this->contato == null) { return -5; }
        
        return PessoaFisicaDAO::update($this->id, $this->nome, $this->rg, $this->cpf, $this->nascimento, $this->contato->getId());
    }
    
    public static function delete(int $id) : int
    {
        return $id > 0 ? PessoaFisicaDAO::delete($id) : -5;
    }

    public static function validarCPF(string $cpf): bool
    {
        $cpf = str_replace('.', '', $cpf);
        $cpf = str_replace('-', '', $cpf);
        if ($cpf === '') return false;

        // Elimina CPFs invalidos conhecidos
        if (
            strlen($cpf) != 11 || $cpf == "00000000000" || $cpf == "11111111111" || $cpf == "22222222222" ||
            $cpf == "33333333333" || $cpf == "44444444444" || $cpf == "55555555555" || $cpf == "66666666666" ||
            $cpf == "77777777777" || $cpf == "88888888888" || $cpf == "99999999999"
        ) return false;

        // Valida 1o digito
        $add = 0;
        for ($i = 0; $i < 9; $i++) {
            $add += $cpf{$i} * (10 - $i);
        }
        $rev = 11 - ($add % 11);
        if ($rev == 10 || $rev == 11) {
            $rev = 0;
        }
        if ($rev != $cpf{9}) {
            return false;
        }

        // Valida 2o digito
        $add = 0;
        for ($i = 0; $i < 10; $i++) {
            $add += $cpf{$i} * (11 - $i);
        }
        $rev = 11 - ($add % 11);
        if ($rev == 10 || $rev == 11) {
            $rev = 0;
        }
        if ($rev != $cpf{10}) {
            return false;
        }

        return true;
    }

    public function jsonSerialize() 
    {
        $this->contato = $this->contato->jsonSerialize();
        return get_object_vars($this);
    }
}
