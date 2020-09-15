<?php


namespace scr\model;


use mysqli_result;
use mysqli_stmt;
use scr\util\Banco;

class PedidoVenda
{
    /** @var int */
    private $id;

    /** @var string */
    private $data;

    /** @var string */
    private $descricao;

    /** @var float */
    private $peso;

    /** @var float */
    private $valor;

    /** @var ?Funcionario */
    private $vendedor;

    /** @var Cidade */
    private $destino;

    /** @var ?OrcamentoVenda */
    private $orcamento;

    /** @var TipoCaminhao */
    private $tipoCaminhao;

    /** @var Cliente */
    private $cliente;

    /** @var FormaPagamento */
    private $formaPagamento;

    /** @var Usuario */
    private $autor;

    /**
     * PedidoVenda constructor.
     * @param int $id
     * @param string $data
     * @param string $descricao
     * @param float $peso
     * @param float $valor
     * @param ?Funcionario $vendedor
     * @param Cidade|null $destino
     * @param ?OrcamentoVenda $orcamento
     * @param TipoCaminhao|null $tipoCaminhao
     * @param Cliente|null $cliente
     * @param FormaPagamento|null $formaPagamento
     * @param Usuario|null $autor
     */
    public function __construct(int $id = 0, string $data = "", string $descricao = "", float $peso = 0.0, float $valor = 0.0, ?Funcionario $vendedor = null, Cidade $destino = null, ?OrcamentoVenda $orcamento = null, TipoCaminhao $tipoCaminhao = null, Cliente $cliente = null, FormaPagamento $formaPagamento = null, Usuario $autor = null)
    {
        $this->id = $id;
        $this->data = $data;
        $this->descricao = $descricao;
        $this->peso = $peso;
        $this->valor = $valor;
        $this->vendedor = $vendedor;
        $this->destino = $destino;
        $this->orcamento = $orcamento;
        $this->tipoCaminhao = $tipoCaminhao;
        $this->cliente = $cliente;
        $this->formaPagamento = $formaPagamento;
        $this->autor = $autor;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getData(): string
    {
        return $this->data;
    }

    /**
     * @param string $data
     */
    public function setData(string $data): void
    {
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getDescricao(): string
    {
        return $this->descricao;
    }

    /**
     * @param string $descricao
     */
    public function setDescricao(string $descricao): void
    {
        $this->descricao = $descricao;
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
     * @return Funcionario|null
     */
    public function getVendedor(): ?Funcionario
    {
        return $this->vendedor;
    }

    /**
     * @param Funcionario|null $vendedor
     */
    public function setVendedor(?Funcionario $vendedor): void
    {
        $this->vendedor = $vendedor;
    }

    /**
     * @return Cidade
     */
    public function getDestino(): Cidade
    {
        return $this->destino;
    }

    /**
     * @param Cidade $destino
     */
    public function setDestino(Cidade $destino): void
    {
        $this->destino = $destino;
    }

    /**
     * @return OrcamentoVenda|null
     */
    public function getOrcamento(): ?OrcamentoVenda
    {
        return $this->orcamento;
    }

    /**
     * @param OrcamentoVenda|null $orcamento
     */
    public function setOrcamento(?OrcamentoVenda $orcamento): void
    {
        $this->orcamento = $orcamento;
    }

    /**
     * @return TipoCaminhao
     */
    public function getTipoCaminhao(): TipoCaminhao
    {
        return $this->tipoCaminhao;
    }

    /**
     * @param TipoCaminhao $tipoCaminhao
     */
    public function setTipoCaminhao(TipoCaminhao $tipoCaminhao): void
    {
        $this->tipoCaminhao = $tipoCaminhao;
    }

    /**
     * @return Cliente
     */
    public function getCliente(): Cliente
    {
        return $this->cliente;
    }

    /**
     * @param Cliente $cliente
     */
    public function setCliente(Cliente $cliente): void
    {
        $this->cliente = $cliente;
    }

    /**
     * @return FormaPagamento
     */
    public function getFormaPagamento(): FormaPagamento
    {
        return $this->formaPagamento;
    }

    /**
     * @param FormaPagamento $formaPagamento
     */
    public function setFormaPagamento(FormaPagamento $formaPagamento): void
    {
        $this->formaPagamento = $formaPagamento;
    }

    /**
     * @return Usuario
     */
    public function getAutor(): Usuario
    {
        return $this->autor;
    }

    /**
     * @param Usuario $autor
     */
    public function setAutor(Usuario $autor): void
    {
        $this->autor = $autor;
    }

    public function findRelationsByFP(int $fp): int
    {
        $sql = "
            SELECT COUNT(ped_ven_id) as FORMAS 
            FROM pedido_venda 
            WHERE for_pag_id = ?;
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if ($stmt === null) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }

        $stmt->bind_param("i", $fp);
        if (!$stmt->execute()) {
            echo $stmt->error;
            return -10;
        }

        /** @var $result mysqli_result */
        $result = $stmt->get_result();
        if ($result === null || $result->num_rows === 0) {
            echo $stmt->error;
            return -10;
        }

        $row = $result->fetch_assoc();

        return (int) $row["FORMAS"];
    }

    public function findById(int $id): ?PedidoVenda
    {
        if ($id <= 0) return null;

        $sql = "
            SELECT ped_ven_id, ped_ven_data, ped_ven_descricao, ped_ven_peso, ped_ven_valor, 
                   fun_id, cid_id, orc_ven_id, tip_cam_id, cli_id, for_pag_id, usu_id 
            FROM pedido_venda
            WHERE ped_ven_id = ?;
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return null;
        }

        $stmt->bind_param("i", $id);
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

        return new PedidoVenda(
            $row["ped_ven_id"], $row["ped_ven_data"], $row["ped_ven_descricao"],
            $row["ped_ven_peso"], $row["ped_ven_valor"],
            ($row["fun_id"] !== null) ? Funcionario::getById($row["fun_id"]) : null,
            (new Cidade())->getById($row["cid_id"]),
            ($row["orc_ven_id"] !== null) ? OrcamentoVenda::findById($row["orc_ven_id"]) : null,
            TipoCaminhao::findById($row["tip_cam_id"]),
            Cliente::getById($row["cli_id"]),
            FormaPagamento::findById($row["for_pag_id"]),
            Usuario::getById($row["usu_id"])
        );
    }

    public function findByOrder(int $order): ?PedidoVenda
    {
        if ($order <= 0)
            return null;

        $sql = "
            SELECT ped_ven_id, ped_ven_data, ped_ven_descricao, ped_ven_peso, ped_ven_valor, 
                   fun_id, cid_id, orc_ven_id, tip_cam_id, cli_id, for_pag_id, usu_id 
            FROM pedido_venda
            WHERE orc_ven_id = ?;
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return null;
        }

        $stmt->bind_param("i", $order);
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

        return new PedidoVenda(
            $row["ped_ven_id"], $row["ped_ven_data"], $row["ped_ven_descricao"],
            $row["ped_ven_peso"], $row["ped_ven_valor"],
            ($row["fun_id"] !== null) ? Funcionario::getById($row["fun_id"]) : null,
            (new Cidade())->getById($row["cid_id"]),
            ($row["orc_ven_id"] !== null) ? OrcamentoVenda::findById($row["orc_ven_id"]) : null,
            TipoCaminhao::findById($row["tip_cam_id"]),
            Cliente::getById($row["cli_id"]),
            FormaPagamento::findById($row["for_pag_id"]),
            Usuario::getById($row["usu_id"])
        );
    }

    public function jsonSerialize(): array
    {
        $vendedor = ($this->vendedor !== null) ? $this->vendedor->jsonSerialize() : null;
        $destino = $this->destino->jsonSerialize();
        $orcamento = ($this->orcamento !== null) ? $this->orcamento->jsonSerialize() : null;
        $tipoCaminhao = $this->tipoCaminhao->jsonSerialize();
        $cliente = $this->cliente->jsonSerialize();
        $formaPagamento = $this->formaPagamento->jsonSerialize();
        $autor = $this->autor->jsonSerialize();

        return [
            "id" => $this->id,
            "data" => $this->data,
            "descricao" => $this->descricao,
            "peso" => $this->peso,
            "valor" => $this->valor,
            "vendedor" => $vendedor,
            "destino" => $destino,
            "orcamento" => $orcamento,
            "tipoCaminhao" => $tipoCaminhao,
            "cliente" => $cliente,
            "formaPagamento" => $formaPagamento,
            "autor" => $autor
        ];
    }
}