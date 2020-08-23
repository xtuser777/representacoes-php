<?php


namespace scr\model;


use mysqli_result;
use mysqli_stmt;
use scr\util\Banco;

class PedidoFrete
{
    /** @var int */
    private $id;

    /** @var string */
    private $data;

    /** @var string */
    private $descricao;

    /** @var int */
    private $distancia;

    /** @var float */
    private $peso;

    /** @var float */
    private $valor;

    /** @var float */
    private $valorMotorista;

    /** @var float */
    private $entradaMotorista;

    /** @var string */
    private $entrega;

    /** @var ?OrcamentoFrete */
    private $orcamento;

    /** @var ?PedidoVenda */
    private $venda;

    /** @var ?Representacao */
    private $representacao;

    /** @var Cidade */
    private $destino;

    /** @var TipoCaminhao */
    private $tipoCaminhao;

    /** @var Motorista */
    private $motorista;

    /** @var Caminhao */
    private $caminhao;

    /** @var FormaPagamento */
    private $formaPagamentoFrete;

    /** @var FormaPagamento */
    private $formaPagamentoMotorista;

    /** @var Usuario */
    private $autor;

    public function __construct(int $id = 0, string $data = "", string $descricao = "", int $distancia = 0, float $peso = 0.0, float $valor = 0.0, float $valorMotorista = 0.0, float $entradaMotorista = 0.0, string $entrega = "", ?OrcamentoFrete $orcamento = null, ?PedidoVenda $venda = null, ?Representacao $representacao = null, Cidade $destino = null, TipoCaminhao $tipoCaminhao = null, Motorista $motorista = null, Caminhao $caminhao = null, FormaPagamento $formaPagamentoFrete = null, FormaPagamento $formaPagamentoMotorista = null, Usuario $autor = null)
    {
        $this->id = $id;
        $this->data = $data;
        $this->descricao = $descricao;
        $this->distancia = $distancia;
        $this->peso = $peso;
        $this->valor = $valor;
        $this->valorMotorista = $valorMotorista;
        $this->entradaMotorista = $entradaMotorista;
        $this->entrega = $entrega;
        $this->orcamento = $orcamento;
        $this->venda = $venda;
        $this->representacao = $representacao;
        $this->destino = $destino;
        $this->tipoCaminhao = $tipoCaminhao;
        $this->motorista = $motorista;
        $this->caminhao = $caminhao;
        $this->formaPagamentoFrete = $formaPagamentoFrete;
        $this->formaPagamentoMotorista = $formaPagamentoMotorista;
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
     * @return int
     */
    public function getDistancia(): int
    {
        return $this->distancia;
    }

    /**
     * @param int $distancia
     */
    public function setDistancia(int $distancia): void
    {
        $this->distancia = $distancia;
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
     * @return float
     */
    public function getValorMotorista(): float
    {
        return $this->valorMotorista;
    }

    /**
     * @param float $valorMotorista
     */
    public function setValorMotorista(float $valorMotorista): void
    {
        $this->valorMotorista = $valorMotorista;
    }

    /**
     * @return float
     */
    public function getEntradaMotorista(): float
    {
        return $this->entradaMotorista;
    }

    /**
     * @param float $entradaMotorista
     */
    public function setEntradaMotorista(float $entradaMotorista): void
    {
        $this->entradaMotorista = $entradaMotorista;
    }

    /**
     * @return string
     */
    public function getEntrega(): string
    {
        return $this->entrega;
    }

    /**
     * @param string $entrega
     */
    public function setEntrega(string $entrega): void
    {
        $this->entrega = $entrega;
    }

    /**
     * @return OrcamentoFrete|null
     */
    public function getOrcamento(): ?OrcamentoFrete
    {
        return $this->orcamento;
    }

    /**
     * @param OrcamentoFrete|null $orcamento
     */
    public function setOrcamento(?OrcamentoFrete $orcamento): void
    {
        $this->orcamento = $orcamento;
    }

    /**
     * @return PedidoVenda|null
     */
    public function getVenda(): ?PedidoVenda
    {
        return $this->venda;
    }

    /**
     * @param PedidoVenda|null $venda
     */
    public function setVenda(?PedidoVenda $venda): void
    {
        $this->venda = $venda;
    }

    /**
     * @return Representacao|null
     */
    public function getRepresentacao(): ?Representacao
    {
        return $this->representacao;
    }

    /**
     * @param Representacao|null $representacao
     */
    public function setRepresentacao(?Representacao $representacao): void
    {
        $this->representacao = $representacao;
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
     * @return Motorista
     */
    public function getMotorista(): Motorista
    {
        return $this->motorista;
    }

    /**
     * @param Motorista $motorista
     */
    public function setMotorista(Motorista $motorista): void
    {
        $this->motorista = $motorista;
    }

    /**
     * @return Caminhao
     */
    public function getCaminhao(): Caminhao
    {
        return $this->caminhao;
    }

    /**
     * @param Caminhao $caminhao
     */
    public function setCaminhao(Caminhao $caminhao): void
    {
        $this->caminhao = $caminhao;
    }

    /**
     * @return FormaPagamento
     */
    public function getFormaPagamentoFrete(): FormaPagamento
    {
        return $this->formaPagamentoFrete;
    }

    /**
     * @param FormaPagamento $formaPagamentoFrete
     */
    public function setFormaPagamentoFrete(FormaPagamento $formaPagamentoFrete): void
    {
        $this->formaPagamentoFrete = $formaPagamentoFrete;
    }

    /**
     * @return FormaPagamento
     */
    public function getFormaPagamentoMotorista(): FormaPagamento
    {
        return $this->formaPagamentoMotorista;
    }

    /**
     * @param FormaPagamento $formaPagamentoMotorista
     */
    public function setFormaPagamentoMotorista(FormaPagamento $formaPagamentoMotorista): void
    {
        $this->formaPagamentoMotorista = $formaPagamentoMotorista;
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

    public function findById(int $id): ?PedidoFrete
    {
        if ($id <= 0) return null;

        $sql = "
            SELECT ped_fre_id, ped_fre_data, ped_fre_descricao, 
                   ped_fre_destino, ped_fre_peso, ped_fre_valor, 
                   ped_fre_valor_motorista, ped_fre_entrada_motorista, ped_fre_entrega,
                   orc_fre_id, ped_ven_id, rep_id, cid_id, tip_cam_id, cam_id, mot_id, 
                   for_pag_fre, for_pag_mot, usu_id
            FROM pedido_frete
            WHERE ped_fre_id = ?;
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

        return new PedidoFrete(
            $row["ped_fre_id"], $row["ped_fre_data"], $row["ped_fre_descricao"],
            $row["ped_fre_distancia"], $row["ped_fre_peso"], $row["ped_fre_valor"],
            $row["ped_fre_valor_motorista"], $row["ped_fre_entrada_motorista"], $row["ped_fre_entrega"],
            ($row["orc_fre_id"] !== null) ? (new OrcamentoFrete())->findById($row["orc_fre_id"]) : null,
            ($row["ped_ven_id"] !== null) ? (new PedidoVenda())->findById($row["ped_ven_id"]) : null,
            ($row["rep_id"] !== null) ? Representacao::getById($row["rep_id"]) : null,
            (new Cidade())->getById($row["cid_id"]),
            TipoCaminhao::findById($row["tip_cam_id"]),
            Motorista::findById($row["mot_id"]),
            Caminhao::findById($row["cam_id"]),
            FormaPagamento::findById($row["for_pag_fre"]),
            FormaPagamento::findById($row["for_pag_mot"]),
            Usuario::getById($row["usu_id"])
        );
    }

    public function jsonSerialize(): array
    {
        $orcamento = ($this->orcamento !== null) ? $this->orcamento->jsonSerialize() : null;
        $venda = ($this->venda !== null) ? $this->venda->jsonSerialize() : null;
        $representacao = ($this->representacao !== null) ? $this->representacao->jsonSerialize() : null;
        $destino = $this->destino->jsonSerialize();
        $tipoCaminhao = $this->tipoCaminhao->jsonSerialize();
        $motorista = $this->motorista->jsonSerialize();
        $caminhao = $this->caminhao->jsonSerialize();
        $formaPagamentoFrete = $this->formaPagamentoFrete->jsonSerialize();
        $formaPagamentoMotorista = $this->formaPagamentoMotorista->jsonSerialize();
        $autor = $this->autor->jsonSerialize();

        return [
            "id" => $this->id,
            "data" => $this->data,
            "descricao" => $this->descricao,
            "distancia" => $this->distancia,
            "peso" => $this->peso,
            "valor" => $this->valor,
            "valorMotorista" => $this->valorMotorista,
            "entradaMotorista" => $this->entradaMotorista,
            "entrega" => $this->entrega,
            "orcamento" => $orcamento,
            "venda" => $venda,
            "representacao" => $representacao,
            "destino" => $destino,
            "tipoCaminhao" => $tipoCaminhao,
            "motorista" => $motorista,
            "caminhao" => $caminhao,
            "formaPagamentoFrete" => $formaPagamentoFrete,
            "formaPagamentoMotorista" => $formaPagamentoMotorista,
            "autor" => $autor
        ];
    }
}