<?php namespace scr\model;


use scr\dao\DadosBancariosDAO;

class DadosBancarios
{
    private $id;
    private $banco;
    private $agencia;
    private $conta;
    private $tipo;

    public function __construct(int $id, string $banco, string $agencia, string $conta, int $tipo)
    {
        $this->id = $id;
        $this->banco = $banco;
        $this->agencia = $agencia;
        $this->conta = $conta;
        $this->tipo = $tipo;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getBanco(): string
    {
        return $this->banco;
    }

    public function getAgencia(): string
    {
        return $this->agencia;
    }

    public function getConta(): string
    {
        return $this->conta;
    }

    public function getTipo(): int
    {
        return $this->tipo;
    }

    public static function findById(int $id): ?DadosBancarios
    {
        return $id > 0 ? DadosBancariosDAO::selectId($id) : null;
    }

    public function save(): int
    {
        if ($this->id != 0 || strlen(trim($this->banco)) <= 0 || strlen(trim($this->agencia)) <= 0 || strlen(trim($this->conta)) <= 0 || $this->tipo <= 0) return -5;

        return DadosBancariosDAO::insert($this->banco, $this->agencia, $this->conta, $this->tipo);
    }

    public function update(): int
    {
        if ($this->id <= 0 || strlen(trim($this->banco)) <= 0 || strlen(trim($this->agencia)) <= 0 || strlen(trim($this->conta)) <= 0 || $this->tipo <= 0) return -5;

        return DadosBancariosDAO::update($this->id, $this->banco, $this->agencia, $this->conta, $this->tipo);
    }

    public function delete(): int
    {
        return $this->id > 0 ? DadosBancariosDAO::delete($this->id) : -5;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}