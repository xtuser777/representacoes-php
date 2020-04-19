<?php namespace scr\model;

use mysqli;
use scr\dao\RepresentacaoDAO;
use scr\model\PessoaJuridica;

class Representacao
{
    private $id;
    private $cadastro;
    private $unidade;
    private $pessoa;

    public function __construct(int $id, string $cadastro, string $unidade, ?PessoaJuridica $pessoa)
    {
        $this->id = $id;
        $this->cadastro = $cadastro;
        $this->unidade = $unidade;
        $this->pessoa = $pessoa;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCadastro(): string
    {
        return $this->cadastro;
    }
    
    public function getUnidade(): string
    {
        return $this->unidade;
    }

    public function getPessoa(): ?PessoaJuridica
    {
        return $this->pessoa;
    }

    public static function getById(int $id): ?Representacao
    {
        return $id > 0 ? RepresentacaoDAO::getById($id) : null;
    }

    public static function getByKey(string $key): array
    {
        return strlen(trim($key)) > 0 ? RepresentacaoDAO::getByKey($key) : array();
    }

    public static function getByCad(string $cad): array
    {
        return strlen(trim($cad)) > 0 ? RepresentacaoDAO::getByCad($cad) : array();
    }

    public static function getByKeyCad(string $key, string $cad): array
    {
        return strlen(trim($key)) > 0 && strlen(trim($cad)) > 0 ? RepresentacaoDAO::getByKeyCad($key, $cad) : array();
    }

    public static function getAll(): array
    {
        return RepresentacaoDAO::getAll();
    }

    public function jsonSerialize()
    {
        $this->pessoa = $this->pessoa->jsonSerialize();
        return get_object_vars($this);
    }
}