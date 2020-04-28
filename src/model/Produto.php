<?php namespace scr\model;

use scr\dao\ProdutoDAO;

class Produto
{
    /** @var int */
    private $id;
    /** @var string */
    private $descricao;
    /** @var string */
    private $medida;
    /** @var double */
    private $preco;
    /** @var double */
    private $precoOut;
    /** @var Representacao */
    private $representacao;

    /**
     * Produto constructor.
     * @param int $id
     * @param string $descricao
     * @param string $medida
     * @param float $preco
     * @param float $precoOut
     * @param Representacao $representacao
     */
    public function __construct(int $id, string $descricao, string $medida, float $preco, float $precoOut, Representacao $representacao)
    {
        $this->id = $id;
        $this->descricao = $descricao;
        $this->medida = $medida;
        $this->preco = $preco;
        $this->precoOut = $precoOut;
        $this->representacao = $representacao;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getDescricao(): string
    {
        return $this->descricao;
    }

    public function getMedida(): string
    {
        return $this->medida;
    }

    public function getPreco(): float
    {
        return $this->preco;
    }

    public function getPrecoOut(): float
    {
        return $this->precoOut;
    }

    public function getRepresentacao(): Representacao
    {
        return $this->representacao;
    }

    public static function findById(int $id): ?Produto
    {
        return $id > 0 ? ProdutoDAO::selectId($id) : null;
    }

    public static function findByKeyRepresentation(string $key, int $representacao): array
    {
        return strlen(trim($key)) > 0 && $representacao > 0 ? ProdutoDAO::selectKeyRepresentation($key, $representacao) : array();
    }

    public static function findByKey(string $key): array
    {
        return strlen(trim($key)) > 0 ? ProdutoDAO::selectKey($key) : array();
    }

    public static function findByRepresentation(int $representacao): array
    {
        return $representacao > 0 ? ProdutoDAO::selectRepresentation($representacao) : array();
    }

    public static function findAll(): array
    {
        return ProdutoDAO::select();
    }

    public function save(): int
    {
        if ($this->id != 0 || strlen(trim($this->descricao)) <= 0 || strlen(trim($this->medida)) <= 0 || $this->preco <= 0 || $this->representacao == null) return -5;

        return ProdutoDAO::insert($this->descricao, $this->medida, $this->preco, $this->precoOut, $this->representacao->getId());
    }

    public function update(): int
    {
        if ($this->id <= 0 || strlen(trim($this->descricao)) <= 0 || strlen(trim($this->medida)) <= 0 || $this->preco <= 0 || $this->representacao == null) return -5;

        return ProdutoDAO::update($this->id, $this->descricao, $this->medida, $this->preco, $this->precoOut);
    }

    public function delete(): int
    {
        return $this->id > 0 ? ProdutoDAO::delete($this->id) : -5;
    }

    public function jsonSerialize()
    {
        $this->representacao = $this->representacao->jsonSerialize();
        return get_object_vars($this);
    }
}