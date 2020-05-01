<?php namespace scr\model;

use ArrayObject;
use scr\dao\ProdutoDAO;

class Produto
{
    private $id;
    private $descricao;
    private $medida;
    private $preco;
    private $precoOut;
    private $representacao;
    private $tiposCaminhao;

    /**
     * Produto constructor.
     * @param int $id
     * @param string $descricao
     * @param string $medida
     * @param float $preco
     * @param float $precoOut
     * @param Representacao $representacao
     * @param array $tipos
     */
    public function __construct(int $id, string $descricao, string $medida, float $preco, float $precoOut, Representacao $representacao, array $tipos)
    {
        $this->id = $id;
        $this->descricao = $descricao;
        $this->medida = $medida;
        $this->preco = $preco;
        $this->precoOut = $precoOut;
        $this->representacao = $representacao;
        $this->tiposCaminhao = $tipos;
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

    public function getTipos(): array
    {
        return $this->tiposCaminhao;
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

    public static function verifyType(int $product, int $type): bool
    {
        return $type > 0 ? ProdutoDAO::verifyType($product, $type) : false;
    }

    public function save(): int
    {
        if ($this->id != 0 || strlen(trim($this->descricao)) <= 0 || strlen(trim($this->medida)) <= 0 || $this->preco <= 0 || $this->representacao == null) return -5;

        return ProdutoDAO::insert($this->descricao, $this->medida, $this->preco, $this->precoOut, $this->representacao->getId());
    }

    public function saveType(int $type): int
    {
        return $type > 0 ? ProdutoDAO::insertType($this->id, $type) : -5;
    }

    public function update(): int
    {
        if ($this->id <= 0 || strlen(trim($this->descricao)) <= 0 || strlen(trim($this->medida)) <= 0 || $this->preco <= 0 || $this->representacao == null) return -5;

        return ProdutoDAO::update($this->id, $this->descricao, $this->medida, $this->preco, $this->precoOut, $this->representacao->getId());
    }

    public function delete(): int
    {
        return $this->id > 0 ? ProdutoDAO::delete($this->id) : -5;
    }

    public function deleteType(int $type): int
    {
        return $type > 0 ? ProdutoDAO::deleteType($this->id, $type) : -5;
    }

    public function jsonSerialize()
    {
        $this->representacao = $this->representacao->jsonSerialize();
        return get_object_vars($this);
    }
}