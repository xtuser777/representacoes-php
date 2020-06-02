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

    public static function validarCNPJ(string $cnpj): bool
    {
        $cnpj = str_replace('.','',$cnpj);
        $cnpj = str_replace('/','',$cnpj);
        $cnpj = str_replace('-','',$cnpj);

        if($cnpj === '') return false;

        if (strlen($cnpj) !== 14)
            return false;

        // Elimina CNPJs invalidos conhecidos
        if (
            $cnpj === "00000000000000" || $cnpj === "11111111111111" || $cnpj === "22222222222222" ||
            $cnpj === "33333333333333" || $cnpj === "44444444444444" || $cnpj === "55555555555555" ||
            $cnpj === "66666666666666" || $cnpj === "77777777777777" || $cnpj === "88888888888888" ||
            $cnpj === "99999999999999"
        ) return false;

        // Valida DVs
        $tamanho = strlen($cnpj) - 2;
        $numeros = substr($cnpj, 0,$tamanho);
        $digitos = substr($cnpj, $tamanho);
        $soma = 0;
        $pos = $tamanho - 7;
        for ($i = $tamanho; $i >= 1; $i--) {
            $soma += $numeros{($tamanho - $i)} * $pos--;
            if ($pos < 2) $pos = 9;
        }
        $resultado = $soma % 11 < 2 ? 0 : 11 - $soma % 11;
        if (("".$resultado){0} !== $digitos{0}) return false;

        $tamanho = $tamanho + 1;
        $numeros = substr($cnpj, 0, $tamanho);
        $soma = 0;
        $pos = $tamanho - 7;
        for ($i = $tamanho; $i >= 1; $i--) {
            $soma += $numeros{($tamanho - $i)} * $pos--;
            if ($pos < 2) $pos = 9;
        }
        $resultado = $soma % 11 < 2 ? 0 : 11 - $soma % 11;
        if (("".$resultado){0} !== $digitos{1}) return false;

        return true;
    }

    public function jsonSerialize()
    {
        $this->contato = $this->contato->jsonSerialize();
        return get_object_vars($this);
    }
}