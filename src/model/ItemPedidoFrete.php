<?php


namespace scr\model;


use mysqli_result;
use mysqli_stmt;
use scr\util\Banco;

class ItemPedidoFrete
{
    /** @var Produto|null */
    private $produto;

    /** @var int */
    private $quantidade;

    /** @var float */
    private $peso;

    /**
     * ItemPedidoFrete constructor.
     * @param Produto|null $produto
     * @param int $quantidade
     * @param float $peso
     */
    public function __construct(?Produto $produto = null, int $quantidade = 0, float $peso = 0.0)
    {
        $this->produto = $produto;
        $this->quantidade = $quantidade;
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
     * @return ItemPedidoFrete
     */
    private function rowToObject(array $row): ItemPedidoFrete
    {
        $item = new ItemPedidoFrete();
        $item->setProduto(Produto::findById($row["pro_id"]));
        $item->setQuantidade($row["ped_fre_pro_quantidade"]);
        $item->setPeso($row["ped_fre_pro_peso"]);

        return $item;
    }

    /**
     * @param mysqli_result $result
     * @return ItemPedidoFrete
     */
    private function resultToObject(mysqli_result $result): ItemPedidoFrete
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
     * @return ItemPedidoFrete|null
     */
    public function findById(int $pedido, int $produto): ?ItemPedidoFrete
    {
        if ($pedido <= 0 || $produto <= 0)
            return null;

        $sql = "
            SELECT *
            FROM pedido_frete_produto 
            WHERE ped_fre_id = ? AND pro_id = ?
        ";

        /** @var mysqli_stmt $stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return null;
        }

        $stmt->bind_param("ii", $pedido, $produto);

        if(!$stmt->execute()) {
            echo $stmt->error;
            return null;
        }

        /** @var mysqli_result $result */
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
    public function findBySale(int $pedido): array
    {
        if ($pedido <= 0)
            return [];

        $sql = "
            SELECT *
            FROM pedido_frete_produto 
            WHERE ped_fre_id = ? 
        ";

        /** @var mysqli_stmt $stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return [];
        }

        $stmt->bind_param("i", $pedido);

        if(!$stmt->execute()) {
            echo $stmt->error;
            return [];
        }

        /** @var mysqli_result $result */
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
            $this->peso <= 0
        )
            return -5;

        $sql = "
            INSERT 
            INTO pedido_frete_produto (ped_fre_id, pro_id, ped_fre_pro_quantidade, ped_fre_pro_peso) 
            VALUES (?,?,?,?);
        ";

        /** @var mysqli_stmt $stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }

        $produto = $this->produto->getId();

        $stmt->bind_param(
            "iiid",
            $pedido,
            $produto,
            $this->quantidade,
            $this->peso
        );

        if (!$stmt->execute()) {
            echo $stmt->error;
            return -10;
        }

        return $stmt->insert_id;
    }

    /**
     * @param int $pedido
     * @param int $produto
     * @return int
     */
    public function delete(int $pedido, int $produto): int
    {
        if ($pedido <= 0 || $produto <= 0)
            return -5;

        $sql = "
            DELETE 
            FROM pedido_frete_produto 
            WHERE ped_fre_id = ? AND pro_id = ?;
        ";

        /** @var mysqli_stmt $stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo $stmt->error;
            return -10;
        }

        $stmt->bind_param("ii", $pedido, $produto);

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
            "peso" => $this->peso
        ];
    }
}