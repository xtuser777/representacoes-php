<?php namespace scr\model;

use scr\dao\CaminhaoDAO;

class Caminhao
{
    private $id;
    private $placa;
    private $marca;
    private $modelo;
    private $anoFabricacao;
    private $anoModelo;
    private $tipo;
    private $proprietario;

    public function __construct(int $id, string $placa, string $marca, string $modelo, string $anoFabricacao, string $anoModelo, TipoCaminhao $tipo, Motorista $proprietario)
    {
        $this->id = $id;
        $this->placa = $placa;
        $this->marca = $marca;
        $this->modelo = $modelo;
        $this->anoFabricacao = $anoFabricacao;
        $this->anoModelo = $anoModelo;
        $this->tipo = $tipo;
        $this->proprietario = $proprietario;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPlaca(): string
    {
        return $this->placa;
    }

    public function getMarca(): string
    {
        return $this->marca;
    }

    public function getModelo(): string
    {
        return $this->modelo;
    }

    public function getAnoFabricacao(): string
    {
        return $this->anoFabricacao;
    }

    public function getAnoModelo(): string
    {
        return $this->anoModelo;
    }

    public function getTipo(): TipoCaminhao
    {
        return $this->tipo;
    }

    public function getProprietario(): Motorista
    {
        return $this->proprietario;
    }

    public static function findById(int $id): ?Caminhao
    {
        return $id > 0 ? CaminhaoDAO::selectId($id) : null;
    }

    public static function findByKey(string $key): array
    {
        return strlen(trim($key)) > 0 ? CaminhaoDAO::selectKey($key) : array();
    }

    public static function findAll(): array
    {
        return CaminhaoDAO::select();
    }

    public function save(): int
    {
        if ($this->id != 0 || strlen($this->placa) <= 0 || strlen($this->marca) <= 0 || strlen($this->modelo) <= 0 || strlen($this->anoFabricacao) <= 0 || strlen($this->anoModelo) <= 0 || $this->tipo == null || $this->proprietario == null) return -5;

        return CaminhaoDAO::insert($this->placa, $this->marca, $this->modelo, $this->anoFabricacao, $this->anoModelo, $this->tipo->getId(), $this->proprietario->getId());
    }

    public function update(): int
    {
        if ($this->id <= 0 || strlen($this->placa) <= 0 || strlen($this->marca) <= 0 || strlen($this->modelo) <= 0 || strlen($this->anoFabricacao) <= 0 || strlen($this->anoModelo) <= 0 || $this->tipo == null || $this->proprietario == null) return -5;

        return CaminhaoDAO::update($this->id, $this->placa, $this->marca, $this->modelo, $this->anoFabricacao, $this->anoModelo, $this->tipo->getId(), $this->proprietario->getId());
    }

    public function delete(): int
    {
        return $this->id > 0 ? CaminhaoDAO::delete($this->id) : -5;
    }

    public function jsonSerialize()
    {
        $this->tipo = $this->tipo->jsonSerialize();
        $this->proprietario = $this->proprietario->jsonSerialize();

        return get_object_vars($this);
    }
}