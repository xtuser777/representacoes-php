<?php namespace scr\model;

use scr\dao\FormaPagamentoDAO;

class FormaPagamento
{
    private $id;
    private $descricao;
    private $prazo;

    /**
     * FormaPagamento constructor.
     * @param int $id
     * @param string $descricao
     * @param int $prazo
     */
    public function __construct(int $id, string $descricao, int $prazo)
    {
        $this->id = $id;
        $this->descricao = $descricao;
        $this->prazo = $prazo;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getDescricao(): string
    {
        return $this->descricao;
    }

    public function getPrazo(): int
    {
        return $this->prazo;
    }

    public static function findById(int $id): ?FormaPagamento
    {
        return $id > 0 ? FormaPagamentoDAO::selectId($id) : null;
    }

    public static function findByKey(string $key): array
    {
        return strlen(trim($key)) > 0 ? FormaPagamentoDAO::selectkey($key) : array();
    }

    public static function findAll(): array
    {
        return FormaPagamentoDAO::select();
    }

    public function save(): int
    {
        if ($this->id != 0 || strlen(trim($this->descricao)) <= 0 || $this->prazo <= 0) return -5;

        return FormaPagamentoDAO::insert($this->descricao, $this->prazo);
    }

    public function update(): int
    {
        if ($this->id <= 0 || strlen(trim($this->descricao)) <= 0 || $this->prazo <= 0) return -5;

        return FormaPagamentoDAO::update($this->id, $this->descricao, $this->prazo);
    }

    public function delete(): int
    {
        return $this->id > 0 ? FormaPagamentoDAO::delete($this->id) : -5;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}