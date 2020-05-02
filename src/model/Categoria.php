<?php namespace scr\model;

use scr\dao\CategoriaDAO;

class Categoria
{
    private $id;
    private $descricao;

    /**
     * Categoria constructor.
     * @param int $id
     * @param string $descricao
     */
    public function __construct(int $id, string $descricao)
    {
        $this->id = $id;
        $this->descricao = $descricao;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getDescricao(): string
    {
        return $this->descricao;
    }

    public static function findById(int $id): ?Categoria
    {
        return $id > 0 ? CategoriaDAO::selectId($id) : null;
    }

    public static function findByKey(string $key): array
    {
        return strlen(trim($key)) > 0 ? CategoriaDAO::selectkey($key) : array();
    }

    public static function findAll(): array
    {
        return CategoriaDAO::select();
    }

    public function save()
    {
        if ($this->id != 0 || strlen(trim($this->descricao)) <= 0) return -5;

        return CategoriaDAO::insert($this->descricao);
    }

    public function update()
    {
        if ($this->id <= 0 || strlen(trim($this->descricao)) <= 0) return -5;

        return CategoriaDAO::update($this->id, $this->descricao);
    }

    public function delete()
    {
        return $this->id > 0 ? CategoriaDAO::delete($this->id) : -5;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}