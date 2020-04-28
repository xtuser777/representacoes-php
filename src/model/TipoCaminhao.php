<?php namespace scr\model;

use scr\dao\TipoCaminhaoDAO;

class TipoCaminhao
{
    /** @var int */
    private $id;
    /** @var string */
    private $descricao;
    /** @var int */
    private $eixos;
    /** @var float */
    private $capacidade;

    /**
     * TipoCaminhao constructor.
     * @param int $id
     * @param string $descricao
     * @param int $eixos
     * @param float $capacidade
     */
    public function __construct(int $id, string $descricao, int $eixos, float $capacidade)
    {
        $this->id = $id;
        $this->descricao = $descricao;
        $this->eixos = $eixos;
        $this->capacidade = $capacidade;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getDescricao(): string
    {
        return $this->descricao;
    }

    public function getEixos(): int
    {
        return $this->eixos;
    }

    public function getCapacidade(): float
    {
        return $this->capacidade;
    }

    public static function findById(int $id): ?TipoCaminhao
    {
        return $id > 0 ? TipoCaminhaoDAO::selectId($id) : null;
    }

    public static function findByDescription(string $descricao): array
    {
        return $descricao != null && strlen(trim($descricao)) > 0 ? TipoCaminhaoDAO::selectDescription($descricao) : array();
    }

    public static function findAll(): array
    {
        return TipoCaminhaoDAO::select();
    }

    public function dependents(): int
    {
        return $this->id > 0 ? TipoCaminhaoDAO::dependents($this->id) : -5;
    }

    public function save(): int
    {
        if ($this->id != 0 || $this->descricao == null || strlen(trim($this->descricao)) <= 0 || $this->eixos <= 0 || $this->capacidade <= 0) return -5;

        return TipoCaminhaoDAO::insert($this->descricao, $this->eixos, $this->capacidade);
    }

    public function update(): int
    {
        if ($this->id <= 0 || $this->descricao == null || strlen(trim($this->descricao)) <= 0 || $this->eixos <= 0 || $this->capacidade <= 0) return -5;

        return TipoCaminhaoDAO::update($this->id, $this->descricao, $this->eixos, $this->capacidade);
    }

    public function delete(): int
    {
        return $this->id > 0 ? TipoCaminhaoDAO::delete($this->id) : -5;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}