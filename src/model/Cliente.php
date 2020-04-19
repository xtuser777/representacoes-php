<?php namespace scr\model;

use mysqli;
use scr\model\PessoaFisica;
use scr\model\PessoaJuridica;
use scr\dao\ClienteDAO;

class Cliente
{
    private $id;
    private $cadastro;
    private $tipo;
    private $pessoaFisica;
    private $pessoaJuridica;

    public function __construct(int $id, string $cadastro, int $tipo , ?PessoaFisica $pessoaFisica, ?PessoaJuridica $pessoaJuridica)
    {
        $this->id = $id;
        $this->cadastro = $cadastro;
        $this->tipo = $tipo;
        $this->pessoaFisica = $pessoaFisica;
        $this->pessoaJuridica = $pessoaJuridica;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCadastro(): string
    {
        return $this->cadastro;
    }

    public function getTipo(): int
    {
        return $this->tipo;
    }

    public function getPessoaFisica(): ?PessoaFisica
    {
        return $this->pessoaFisica;
    }

    public function setPessoaFisica(PessoaFisica $pf) : void
    {
        $this->pessoaFisica = $pf;
    }

    public function getPessoaJuridica(): ?PessoaJuridica
    {
        return $this->pessoaJuridica;
    }

    public function setPessoaJuridica(PessoaJuridica $pj) : void
    {
        $this->pessoaJuridica = $pj;
    }

    public static function getById(mysqli $conn, int $id): ?Cliente
    {
        return $id > 0 ? ClienteDAO::getById($conn, $id) : null;
    }

    public static function getByKey(mysqli $conn, string $key): array
    {
        return $key !== null && strlen(trim($key)) > 0 ? ClienteDAO::getByKey($conn, $key) : array();
    }

    public static function getByCad(mysqli $conn, string $cad): array
    {
        return $cad !== null && strlen(trim($cad)) > 0 ? ClienteDAO::getByCad($conn, $cad) : array();
    }

    public static function getByKeyCad(mysqli $conn, string $key, string $cad): array
    {
        return ($key !== null && strlen(trim($key)) > 0) && ($cad !== null && strlen(trim($cad)) > 0) ? ClienteDAO::getByKeyCad($conn, $key, $cad) : array();
    }

    public static function getAll(mysqli $conn): array
    {
        return ClienteDAO::getAll($conn);
    }

    public function insert(mysqli $conn): int
    {
        if ($this->id != 0 || strlen(trim($this->cadastro)) <= 0 || $this->tipo <= 0) return -5;

        return ClienteDAO::insert($conn, $this->cadastro, $this->tipo, ($this->tipo == 1) ? $this->pessoaFisica->getId() : 0, ($this->tipo == 2) ? $this->pessoaJuridica->getId() : 0);
    }

    public function update(mysqli $conn): int
    {
        if ($this->id <= 0 || strlen(trim($this->cadastro)) <= 0 || $this->tipo <= 0) return -5;

        return ClienteDAO::update($conn, $this->id, $this->cadastro, $this->tipo);
    }

    public static function delete(mysqli $conn, int $tipo, int $id): int
    {
        return $id > 0 ? ClienteDAO::delete($conn, $tipo, $id) : -5;
    }

    public function jsonSerialize()
    {
        if ($this->tipo == 1) {
            $this->pessoaFisica = $this->pessoaFisica->jsonSerialize();
        } else {
            $this->pessoaJuridica = $this->pessoaJuridica->jsonSerialize();
        }

        return get_object_vars($this);
    }
}