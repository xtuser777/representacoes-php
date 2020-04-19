<?php namespace scr\model;

use mysqli;
use scr\dao\ParametrizacaoDAO;
use scr\model\PessoaJuridica;

class Parametrizacao
{
    private $id;
    private $logotipo;
    private $pessoa;

    public function __construct(int $id, string $logotipo, PessoaJuridica $pessoa)
    {
        $this->id = $id;
        $this->logotipo = $logotipo;
        $this->pessoa = $pessoa;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getLogotipo(): string
    {
        return $this->logotipo;
    }

    public function getPessoa(): ?PessoaJuridica
    {
        return $this->pessoa;
    }

    public static function get(mysqli $conn) : ?Parametrizacao
    {
        return ParametrizacaoDAO::get($conn);
    }

    public function insert(mysqli $conn) : int
    {
        if ($this->id != 0 || $this->pessoa == null) return -5;

        return ParametrizacaoDAO::insert($conn, $this->logotipo, $this->pessoa->getId());
    }

    public function update(mysqli $conn) : int
    {
        if ($this->id <= 0 || $this->pessoa == null) return -5;

        return ParametrizacaoDAO::update($conn, $this->logotipo, $this->pessoa->getId());
    }

    public function jsonSerialize()
    {
        $this->pessoa = $this->pessoa->jsonSerialize();
        return get_object_vars($this);
    }
}