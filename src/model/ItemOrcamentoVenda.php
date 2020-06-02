<?php namespace scr\model;

use mysqli_result;
use mysqli_stmt;
use scr\util\Banco;

class ItemOrcamentoVenda
{
    private $orcamento;
    private $produto;
    private $quantidade;
    private $valor;
    private $peso;

    public function __construct(OrcamentoVenda $orcamento, Produto $produto, int $quantidade, float $valor, float $peso)
    {
        $this->orcamento = $orcamento;
        $this->produto = $produto;
        $this->quantidade = $quantidade;
        $this->valor = $valor;
        $this->peso = $peso;
    }

    public function getOrcamento(): OrcamentoVenda
    {
        return $this->orcamento;
    }

    public function getProduto(): Produto
    {
        return $this->produto;
    }

    public function getQuantidade(): int
    {
        return $this->quantidade;
    }

    public function getValor(): float
    {
        return $this->valor;
    }

    public function getPeso(): float
    {
        return $this->peso;
    }

    public static function findById(int $orcamento, int $produto) : ?ItemOrcamentoVenda
    {
        if ($orcamento <= 0 || $produto <= 0) return null;
        $sql = "
            select orc_ven_id, pro_id, orc_ven_pro_quantidade, orc_ven_pro_valor, orc_ven_pro_peso
            from orcamento_venda_produto
            where orc_ven_id = ? and pro_id = ?;
        ";
        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return null;
        }
        $stmt->bind_param("ii", $orcamento, $produto);
        if (!$stmt->execute()) {
            echo $stmt->error;
            return null;
        }
        /** @var $result mysqli_result */
        $result = $stmt->get_result();
        if (!$result || $result->num_rows <= 0) {
            echo $stmt->error;
            return null;
        }
        $row = $result->fetch_assoc();

        return new ItemOrcamentoVenda(
            OrcamentoVenda::findById($row["orc_ven_id"]),
            Produto::findById($row["pro_id"]),
            $row["orc_ven_pro_quantidade"], $row["orc_ven_pro_valor"], $row["orc_ven_pro_peso"]
        );
    }

    public static function findAllItems(int $orcamento): array
    {
        $sql = "
            select orc_ven_id, pro_id, orc_ven_pro_quantidade, orc_ven_pro_valor, orc_ven_pro_peso
            from orcamento_venda_produto
            where orc_ven_id = ?;
        ";
        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return array();
        }
        $stmt->bind_param("i", $orcamento);
        if (!$stmt->execute()) {
            echo $stmt->error;
            return array();
        }
        /** @var $result mysqli_result */
        $result = $stmt->get_result();
        if (!$result || $result->num_rows <= 0) {
            echo $stmt->error;
            return array();
        }
        $itens = [];
        while ($row = $result->fetch_assoc()) {
            $itens[] = new ItemOrcamentoVenda(
                OrcamentoVenda::findById($row["orc_ven_id"]),
                Produto::findById($row["pro_id"]),
                $row["orc_ven_pro_quantidade"], $row["orc_ven_pro_valor"], $row["orc_ven_pro_peso"]
            );
        }

        return $itens;
    }

    public function save(): int
    {
        if ($this->orcamento == null || $this->produto == null || $this->quantidade <= 0 || $this->valor <= 0 || $this->peso <= 0) return -5;
        $sql = "
            insert 
            into orcamento_venda_produto (orc_ven_id,pro_id,orc_ven_pro_quantidade,orc_ven_pro_valor,orc_ven_pro_peso)
            values (?,?,?,?,?);
        ";
        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }
        $orcamento = $this->orcamento->getId();
        $produto = $this->produto->getId();
        $stmt->bind_param("iiidd", $orcamento, $produto, $this->quantidade, $this->valor, $this->peso);
        if (!$stmt->execute()) {
            echo $stmt->error;
            return -10;
        }

        return $stmt->insert_id;
    }

    public function update(): int
    {
        if ($this->orcamento == null || $this->produto == null || $this->quantidade <= 0 || $this->valor <= 0 || $this->peso <= 0) return -5;
        $sql = "
            update orcamento_venda_produto 
            set orc_ven_pro_quantidade = ?,orc_ven_pro_valor = ?,orc_ven_pro_peso = ?
            where orc_ven_id = ? and pro_id = ?;
        ";
        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }
        $orcamento = $this->orcamento->getId();
        $produto = $this->produto->getId();
        $stmt->bind_param("iddii", $this->quantidade, $this->valor, $this->peso, $orcamento, $produto);
        if (!$stmt->execute()) {
            echo $stmt->error;
            return -10;
        }

        return $stmt->affected_rows;
    }

    public function delete(): int
    {
        if ($this->orcamento == null || $this->produto == null) return -5;
        $sql = "
            delete
            from orcamento_venda_produto 
            where orc_ven_id = ? and pro_id = ?;
        ";
        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }
        $orcamento = $this->orcamento->getId();
        $produto = $this->produto->getId();
        $stmt->bind_param("ii", $orcamento, $produto);
        if (!$stmt->execute()) {
            echo $stmt->error;
            return -10;
        }

        return $stmt->affected_rows;
    }

    public function jsonSerialize()
    {
        $this->orcamento = $this->orcamento->jsonSerialize();
        $this->produto = $this->produto->jsonSerialize();

        return get_object_vars($this);
    }
}