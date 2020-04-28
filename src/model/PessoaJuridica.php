<?php namespace scr\model;

use mysqli;
use scr\model\Contato;
use scr\dao\PessoaJuridicaDAO;

class PessoaJuridica
{
    private $id;
    private $razaoSocial;
    private $nomeFantasia;
    private $cnpj;
    private $contato;

    public function __construct(int $id, string $razaoSocial, string $nomeFantasia, string $cnpj, Contato $contato)
    {
        $this->id = $id;
        $this->razaoSocial = $razaoSocial;
        $this->nomeFantasia = $nomeFantasia;
        $this->cnpj = $cnpj;
        $this->contato = $contato;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getRazaoSocial(): string
    {
        return $this->razaoSocial;
    }

    public function getNomeFantasia(): string
    {
        return $this->nomeFantasia;
    }

    public function getCnpj(): string
    {
        return $this->cnpj;
    }

    public function getContato(): Contato
    {
        return $this->contato;
    }

    public static function getById(int $id) : ?PessoaJuridica
    {
        return $id > 0 ? PessoaJuridicaDAO::getById($id) : null;
    }

    public static function verifyCnpj(string $cnpj) : bool
    {
        return strlen(trim($cnpj)) > 0 && PessoaJuridicaDAO::countCnpj($cnpj) > 0;
    }

    public function insert() : int
    {
        if ($this->id != 0 || strlen(trim($this->razaoSocial)) <= 0 || strlen(trim($this->nomeFantasia)) <= 0 || strlen(trim($this->cnpj)) < 18 || $this->contato == null) return -5;

        return PessoaJuridicaDAO::insert($this->razaoSocial, $this->nomeFantasia, $this->cnpj, $this->contato->getId());
    }

    public function update() : int
    {
        if ($this->id <= 0 || strlen(trim($this->razaoSocial)) <= 0 || strlen(trim($this->nomeFantasia)) <= 0 || strlen(trim($this->cnpj)) < 18 || $this->contato == null) return -5;

        return PessoaJuridicaDAO::update($this->id, $this->razaoSocial, $this->nomeFantasia, $this->cnpj, $this->contato->getId());
    }

    public static function delete(int $id) : int
    {
        return $id > 0 ? PessoaJuridicaDAO::delete($id) : -5;
    }

    public function jsonSerialize()
    {
        $this->contato = $this->contato->jsonSerialize();
        return get_object_vars($this);
    }
}