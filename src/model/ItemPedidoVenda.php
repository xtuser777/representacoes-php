<?php


namespace scr\model;


use mysqli_stmt;
use mysqli_result;
use scr\util\Banco;

class ItemPedidoVenda
{
    /** @var Produto|null */
    private $produto;

    /** @var int */
    private $quantidade;

    /** @var float */
    private $valor;

    /** @var float */
    private $peso;

    /**
     * ItemPedidoVenda constructor.
     * @param Produto|null $produto
     * @param int $quantidade
     * @param float $valor
     * @param float $peso
     */
    public function __construct(?Produto $produto = null, int $quantidade = 0, float $valor = 0.0, float $peso = 0.0)
    {
        $this->produto = $produto;
        $this->quantidade = $quantidade;
        $this->valor = $valor;
        $this->peso = $peso;
    }

    /**
     * @return Produto|null
     */
    public function getProduto(): ?Produto
    {
        return $this->produto;
    }

    /**
     * @param Produto|null $produto
     */
    public function setProduto(?Produto $produto): void
    {
        $this->produto = $produto;
    }

    /**
     * @return int
     */
    public function getQuantidade(): int
    {
        return $this->quantidade;
    }

    /**
     * @param int $quantidade
     */
    public function setQuantidade(int $quantidade): void
    {
        $this->quantidade = $quantidade;
    }

    /**
     * @return float
     */
    public function getValor(): float
    {
        return $this->valor;
    }

    /**
     * @param float $valor
     */
    public function setValor(float $valor): void
    {
        $this->valor = $valor;
    }

    /**
     * @return float
     */
    public function getPeso(): float
    {
        return $this->peso;
    }

    /**
     * @param float $peso
     */
    public function setPeso(float $peso): void
    {
        $this->peso = $peso;
    }

    /**
     * @param array $row
     * @return ItemPedidoVenda
     */
    private function rowToObject(array $row): ItemPedidoVenda
    {
        $item = new ItemPedidoVenda();
        $item->setProduto(Produto::findById($row["pro_id"]));
        $item->setQuantidade($row["ped_ven_pro_quantidade"]);
        $item->setValor($row["ped_ven_pro_valor"]);
        $item->setPeso($row["ped_ven_pro_peso"]);

        return $item;
    }

    /**
     * @param mysqli_result $result
     * @return ItemPedidoVenda
     */
    private function resultToObject(mysqli_result $result): ItemPedidoVenda
    {
        $row = $result->fetch_assoc();

        return $this->rowToObject($row);
    }

    /**
     * @param mysqli_result $result
     * @return array
     */
    private function resultToList(mysqli_result $result): array
    {
        $itens = [];
        while ($row = $result->fetch_assoc()) {
            $itens[] = $this->rowToObject($row);
        }

        return $itens;
    }

    /**
     * @param int $pedido
     * @param int $produto
     * @return ItemPedidoVenda|null
     */
    public function findById(int $pedido, int $produto): ?ItemPedidoVenda
    {
        if ($pedido <= 0 || $produto <= 0)
            return null;

        $sql = "
            SELECT *
            FROM pedido_venda_produto 
            WHERE ped_ven_id = ? AND pro_id = ?;
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return null;
        }

        $stmt->bind_param("ii", $pedido, $produto);
        if (!$stmt->execute()) {
            echo $stmt->error;
            return null;
        }

        /** @var $result mysqli_result */
        $result = $stmt->get_result();
        if (!$result) {
            echo $stmt->error;
            return null;
        }

        return $this->resultToObject($result);
    }

    /**
     * @param int $pedido
     * @return array
     */
    public function findByPrice(int $pedido): array
    {
        if ($pedido <= 0)
            return [];

        $sql = "
            SELECT *
            FROM pedido_venda_produto 
            WHERE ped_ven_id = ?;
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return [];
        }

        $stmt->bind_param("i", $pedido);
        if (!$stmt->execute()) {
            echo $stmt->error;
            return [];
        }

        /** @var $result mysqli_result */
        $result = $stmt->get_result();
        if (!$result) {
            echo $stmt->error;
            return [];
        }

        return $this->resultToList($result);
    }

    /**
     * @param int $pedido
     * @return int
     */
    public function save(int $pedido): int
    {
        if (
            $pedido <= 0 ||
            $this->produto === null ||
            $this->quantidade <= 0 ||
            $this->valor <= 0 ||
            $this->peso <= 0
        )
            return -5;

        $sql = "
            INSERT
            INTO pedido_venda_produto(
                ped_ven_id,
                pro_id,
                ped_ven_pro_quantidade,
                ped_ven_pro_valor,
                ped_ven_pro_peso
            )
            VALUES (?,?,?,?,?);
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }

        $produto = $this->produto->getId();

        $stmt->bind_param(
            "iiidd",
            $pedido,
            $produto,
            $this->quantidade,
            $this->valor,
            $this->peso
        );

        if (!$stmt->execute()) {
            echo $stmt->error;
            return -10;
        }

        return $stmt->affected_rows;
    }

    /**
     * @param int $pedido
     * @return int
     */
    public function delete(int $pedido): int
    {
        if (
            $pedido <= 0 ||
            $this->produto === null
        )
            return -5;

        $sql = "
            DELETE 
            FROM pedido_venda_produto 
            WHERE ped_ven_id = ? AND pro_id = ?;
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }

        $produto = $this->produto->getId();

        $stmt->bind_param(
            "ii",
            $pedido,
            $produto
        );

        if (!$stmt->execute()) {
            echo $stmt->error;
            return -10;
        }

        return $stmt->affected_rows;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        $produto = $this->produto->jsonSerialize();

        return [
            "produto" => $produto,
            "quantidade" => $this->quantidade,
            "valor" => $this->valor,
            "peso" => $this->peso
        ];
    }
}
