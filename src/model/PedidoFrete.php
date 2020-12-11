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

    /** @var OrcamentoFrete|null */
    private $orcamento;

    /** @var PedidoVenda|null */
    private $venda;

    /** @var Representacao|null */
    private $representacao;

    /** @var Cliente|null */
    private $cliente;

    /** @var Cidade|null */
    private $destino;

    /** @var TipoCaminhao|null */
    private $tipoCaminhao;

    /** @var Proprietario|null */
    private $proprietario;

    /** @var Motorista|null */
    private $motorista;

    /** @var Caminhao|null */
    private $caminhao;

    /** @var StatusPedido|null */
    private $status;

    /** @var FormaPagamento|null */
    private $formaPagamentoFrete;

    /** @var FormaPagamento|null */
    private $formaPagamentoMotorista;

    /** @var Usuario|null */
    private $autor;

    /** @var array */
    private $itens;

    /** @var array */
    private $etapas;

    /**
     * PedidoFrete constructor.
     * @param int $id
     * @param string $data
     * @param string $descricao
     * @param int $distancia
     * @param float $peso
     * @param float $valor
     * @param float $valorMotorista
     * @param float $entradaMotorista
     * @param string $entrega
     * @param OrcamentoFrete|null $orcamento
     * @param PedidoVenda|null $venda
     * @param Representacao|null $representacao
     * @param Cliente|null $cliente
     * @param Cidade|null $destino
     * @param TipoCaminhao|null $tipoCaminhao
     * @param Proprietario|null $proprietario
     * @param Motorista|null $motorista
     * @param Caminhao|null $caminhao
     * @param StatusPedido|null $status
     * @param FormaPagamento|null $formaPagamentoFrete
     * @param FormaPagamento|null $formaPagamentoMotorista
     * @param Usuario|null $autor
     * @param array $etapas
     */
    public function __construct(
        int $id = 0,
        string $data = "",
        string $descricao = "",
        int $distancia = 0,
        float $peso = 0.0,
        float $valor = 0.0,
        float $valorMotorista = 0.0,
        float $entradaMotorista = 0.0,
        string $entrega = "",
        ?OrcamentoFrete $orcamento = null,
        ?PedidoVenda $venda = null,
        ?Representacao $representacao = null,
        ?Cliente $cliente = null,
        ?Cidade $destino = null,
        ?TipoCaminhao $tipoCaminhao = null,
        ?Proprietario $proprietario = null,
        ?Motorista $motorista = null,
        ?Caminhao $caminhao = null,
        ?StatusPedido $status = null,
        ?FormaPagamento $formaPagamentoFrete = null,
        ?FormaPagamento $formaPagamentoMotorista = null,
        ?Usuario $autor = null,
        array $itens = [],
        array $etapas = []
    )
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
        $this->cliente = $cliente;
        $this->destino = $destino;
        $this->tipoCaminhao = $tipoCaminhao;
        $this->proprietario = $proprietario;
        $this->motorista = $motorista;
        $this->caminhao = $caminhao;
        $this->status = $status;
        $this->formaPagamentoFrete = $formaPagamentoFrete;
        $this->formaPagamentoMotorista = $formaPagamentoMotorista;
        $this->autor = $autor;
        $this->itens = $itens;
        $this->etapas = $etapas;
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
     * @return Cidade|null
     */
    public function getDestino(): ?Cidade
    {
        return $this->destino;
    }

    /**
     * @param Cidade|null $destino
     */
    public function setDestino(?Cidade $destino): void
    {
        $this->destino = $destino;
    }

    /**
     * @return TipoCaminhao|null
     */
    public function getTipoCaminhao(): ?TipoCaminhao
    {
        return $this->tipoCaminhao;
    }

    /**
     * @param TipoCaminhao|null $tipoCaminhao
     */
    public function setTipoCaminhao(?TipoCaminhao $tipoCaminhao): void
    {
        $this->tipoCaminhao = $tipoCaminhao;
    }

    /**
     * @return Proprietario|null
     */
    public function getProprietario(): ?Proprietario
    {
        return $this->proprietario;
    }

    /**
     * @param Proprietario|null $proprietario
     */
    public function setProprietario(?Proprietario $proprietario): void
    {
        $this->proprietario = $proprietario;
    }

    /**
     * @return Caminhao|null
     */
    public function getCaminhao(): ?Caminhao
    {
        return $this->caminhao;
    }

    /**
     * @param Caminhao|null $caminhao
     */
    public function setCaminhao(?Caminhao $caminhao): void
    {
        $this->caminhao = $caminhao;
    }

    /**
     * @return StatusPedido|null
     */
    public function getStatus(): ?StatusPedido
    {
        return $this->status;
    }

    /**
     * @param StatusPedido|null $status
     */
    public function setStatus(?StatusPedido $status): void
    {
        $this->status = $status;
    }

    /**
     * @return FormaPagamento|null
     */
    public function getFormaPagamentoFrete(): ?FormaPagamento
    {
        return $this->formaPagamentoFrete;
    }

    /**
     * @param FormaPagamento|null $formaPagamentoFrete
     */
    public function setFormaPagamentoFrete(?FormaPagamento $formaPagamentoFrete): void
    {
        $this->formaPagamentoFrete = $formaPagamentoFrete;
    }

    /**
     * @return FormaPagamento|null
     */
    public function getFormaPagamentoMotorista(): ?FormaPagamento
    {
        return $this->formaPagamentoMotorista;
    }

    /**
     * @param FormaPagamento|null $formaPagamentoMotorista
     */
    public function setFormaPagamentoMotorista(?FormaPagamento $formaPagamentoMotorista): void
    {
        $this->formaPagamentoMotorista = $formaPagamentoMotorista;
    }

    /**
     * @return Usuario|null
     */
    public function getAutor(): ?Usuario
    {
        return $this->autor;
    }

    /**
     * @param Usuario|null $autor
     */
    public function setAutor(?Usuario $autor): void
    {
        $this->autor = $autor;
    }

    /**
     * @return Cliente|null
     */
    public function getCliente(): ?Cliente
    {
        return $this->cliente;
    }

    /**
     * @param Cliente|null $cliente
     */
    public function setCliente(?Cliente $cliente): void
    {
        $this->cliente = $cliente;
    }

    /**
     * @return Motorista|null
     */
    public function getMotorista(): ?Motorista
    {
        return $this->motorista;
    }

    /**
     * @param Motorista|null $motorista
     */
    public function setMotorista(?Motorista $motorista): void
    {
        $this->motorista = $motorista;
    }

    /**
     * @return array
     */
    public function getItens(): array
    {
        return $this->itens;
    }

    /**
     * @param array $itens
     */
    public function setItens(array $itens): void
    {
        $this->itens = $itens;
    }

    /**
     * @return array
     */
    public function getEtapas(): array
    {
        return $this->etapas;
    }

    /**
     * @param array $etapas
     */
    public function setEtapas(array $etapas): void
    {
        $this->etapas = $etapas;
    }

    public function calcularPisoMinimo(float $km, int $eixos): float
    {
        $piso = 0.0;

        if ($km <= 0.0 || $eixos <= 0)
            return $piso;

        switch ($eixos) {
            case 4:
                $piso = $km * 2.3041;
                break;
            case 5:
                $piso = $km * 2.7446;
                break;
            case 6:
                $piso = $km * 3.1938;
                break;
            case 7:
                $piso = $km * 3.3095;
                break;
            case 9:
                $piso = $km * 3.6542;
                break;
        }

        return $piso;
    }

    public function findRelationsByFP(int $fp) : int
    {
        $sql = "
            SELECT COUNT(ped_fre_id) as FORMAS 
            FROM pedido_frete 
            WHERE for_pag_fre = ?;
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

    public function findRelationsByFPM(int $fpm) : int
    {
        $sql = "
            SELECT COUNT(ped_fre_id) as FORMAS 
            FROM pedido_frete 
            WHERE for_pag_mot = ?;
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if ($stmt === null) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }

        $stmt->bind_param("i", $fpm);
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

    /**
     * @param array $row
     * @return PedidoFrete
     */
    private function rowToObject(array $row): PedidoFrete
    {
        $pedido = new PedidoFrete();
        $pedido->setId($row["ped_fre_id"]);
        $pedido->setData($row["ped_fre_data"]);
        $pedido->setDescricao($row["ped_fre_descricao"]);
        $pedido->setDistancia($row["ped_fre_distancia"]);
        $pedido->setPeso($row["ped_fre_peso"]);
        $pedido->setValor($row["ped_fre_valor"]);
        $pedido->setValorMotorista($row["ped_fre_valor_motorista"]);
        $pedido->setEntradaMotorista($row["ped_fre_entrada_motorista"]);
        $pedido->setEntrega($row["ped_fre_entrega"]);
        $pedido->setOrcamento(($row["orc_fre_id"] !== null) ? (new OrcamentoFrete())->findById($row["orc_fre_id"]) : null);
        $pedido->setVenda(($row["ped_ven_id"] !== null) ? (new PedidoVenda())->findById($row["ped_ven_id"]) : null);
        $pedido->setRepresentacao(($row["rep_id"] !== null) ? Representacao::getById($row["rep_id"]) : null);
        $pedido->setCliente(Cliente::getById($row["cli_id"]));
        $pedido->setDestino((new Cidade())->getById($row["cid_id"]));
        $pedido->setTipoCaminhao(TipoCaminhao::findById($row["tip_cam_id"]));
        $pedido->setProprietario((new Proprietario())->findById($row["prp_id"]));
        $pedido->setMotorista(Motorista::findById($row["mot_id"]));
        $pedido->setCaminhao(Caminhao::findById($row["cam_id"]));
        $pedido->setFormaPagamentoFrete(FormaPagamento::findById($row["for_pag_fre"]));
        $pedido->setFormaPagamentoMotorista(($row["for_pag_mot"] !== null) ? FormaPagamento::findById($row["for_pag_mot"]) : null);
        $pedido->setAutor(Usuario::getById($row["usu_id"]));

        $pedido->setItens((new ItemPedidoFrete())->findBySale($pedido->getId()));

        $pedido->setEtapas((new EtapaCarregamento())->findBySale($pedido->getId()));

        $status = (new StatusPedido())->findBySale($pedido->getId());
        $pedido->setStatus($status[(count($status)-1)]);

        return $pedido;
    }

    /**
     * @param mysqli_result $result
     * @return PedidoFrete|null
     */
    private function resultToObject(mysqli_result $result): ?PedidoFrete
    {
        if ($result->num_rows <= 0)
            return null;

        $row = $result->fetch_assoc();

        return $this->rowToObject($row);
    }

    /**
     * @param mysqli_result $result
     * @return array
     */
    private function resultToList(mysqli_result $result): array
    {
        if (!$result)
            return [];

        $pedidos = [];

        while ($row = $result->fetch_assoc()) {
            $pedidos[] = $this->rowToObject($row);
        }

        return $pedidos;
    }

    public function findById(int $id): ?PedidoFrete
    {
        if ($id <= 0)
            return null;

        $sql = "
            SELECT *
            FROM pedido_frete
            WHERE ped_fre_id = ?;
        ";

        if (!Banco::getInstance()->prepareStatement($sql))
            return null;

        if (!Banco::getInstance()->addParameters("i", [ $id ]))
            return null;

        if (!Banco::getInstance()->executeStatement())
            return null;

        return $this->resultToObject(Banco::getInstance()->getResult());
    }

    public function findByOrder(int $order): ?PedidoFrete
    {
        if ($order <= 0)
            return null;

        $sql = "
            SELECT *
            FROM pedido_frete
            WHERE orc_fre_id = ?;
        ";

        if (!Banco::getInstance()->prepareStatement($sql))
            return null;

        if (!Banco::getInstance()->addParameters("i", [ $order ]))
            return null;

        if (!Banco::getInstance()->executeStatement())
            return null;

        return $this->resultToObject(Banco::getInstance()->getResult());
    }

    public function findByPrice(int $price)
    {
        if ($price <= 0)
            return null;

        $sql = "
            SELECT *
            FROM pedido_frete
            WHERE ped_ven_id = ?;
        ";

        if (!Banco::getInstance()->prepareStatement($sql))
            return null;

        if(!Banco::getInstance()->addParameters("i", [ $price ]))
            return null;

        if (!Banco::getInstance()->executeStatement())
            return null;

        return $this->resultToObject(Banco::getInstance()->getResult());
    }

    /**
     * @param int $client
     * @param string $order
     * @return array
     */
    public function findByClient(int $client, string $order): array
    {
        if ($client <= 0)
            return [];

        $sql = "
            SELECT * 
            FROM pedido_frete pfr
            INNER JOIN usuario autor ON autor.usu_id = pfr.usu_id
            INNER JOIN funcionario autor_fun ON autor_fun.fun_id = autor.fun_id
            INNER JOIN pessoa_fisica autor_pf ON autor_pf.pf_id = autor_fun.pf_id
            INNER JOIN forma_pagamento fp ON fp.for_pag_id = pfr.for_pag_fre 
            INNER JOIN pedido_frete_status pfs ON pfr.ped_fre_id = pfs.ped_fre_id 
            INNER JOIN status st on pfs.sts_id = st.sts_id
            WHERE pfr.cli_id = ? 
            ORDER BY $order;
        ";

        if (!Banco::getInstance()->prepareStatement($sql))
            return [];

        if (!Banco::getInstance()->addParameters("i", [ $client ]))
            return [];

        if (!Banco::getInstance()->executeStatement())
            return [];

        return $this->resultToList(Banco::getInstance()->getResult());
    }

    /**
     * @param int $status
     * @param string $order
     * @return array
     */
    public function findByStatus(int $status, string $order): array
    {
        if ($status <= 0)
            return [];

        $sql = "
            SELECT * 
            FROM pedido_frete pfr
            INNER JOIN usuario autor ON autor.usu_id = pfr.usu_id
            INNER JOIN funcionario autor_fun ON autor_fun.fun_id = autor.fun_id
            INNER JOIN pessoa_fisica autor_pf ON autor_pf.pf_id = autor_fun.pf_id
            INNER JOIN forma_pagamento fp ON fp.for_pag_id = pfr.for_pag_fre 
            INNER JOIN pedido_frete_status pfs ON pfr.ped_fre_id = pfs.ped_fre_id 
            INNER JOIN status st on pfs.sts_id = st.sts_id
            WHERE st.sts_id = ? AND pfs.ped_fre_sts_atual IS TRUE 
            ORDER BY $order;
        ";

        if (!Banco::getInstance()->prepareStatement($sql))
            return [];

        if (!Banco::getInstance()->addParameters("i", [ $status ]))
            return [];

        if (!Banco::getInstance()->executeStatement())
            return [];

        return $this->resultToList(Banco::getInstance()->getResult());
    }

    /**
     * @param int $status
     * @param int $client
     * @param string $order
     * @return array
     */
    public function findByStatusClient(int $status, int $client, string $order): array
    {
        if ($status <= 0 || $client <= 0)
            return [];

        $sql = "
            SELECT * 
            FROM pedido_frete pfr
            INNER JOIN usuario autor ON autor.usu_id = pfr.usu_id
            INNER JOIN funcionario autor_fun ON autor_fun.fun_id = autor.fun_id
            INNER JOIN pessoa_fisica autor_pf ON autor_pf.pf_id = autor_fun.pf_id
            INNER JOIN forma_pagamento fp ON fp.for_pag_id = pfr.for_pag_fre 
            INNER JOIN pedido_frete_status pfs ON pfr.ped_fre_id = pfs.ped_fre_id 
            INNER JOIN status st on pfs.sts_id = st.sts_id
            WHERE st.sts_id = ? AND pfs.ped_fre_sts_atual IS TRUE AND pfr.cli_id = ? 
            ORDER BY $order;
        ";

        if (!Banco::getInstance()->prepareStatement($sql))
            return [];

        if (!Banco::getInstance()->addParameters("ii", [ $status, $client ]))
            return [];

        if (!Banco::getInstance()->executeStatement())
            return [];

        return $this->resultToList(Banco::getInstance()->getResult());
    }

    /**
     * @param string $filter
     * @param string $order
     * @return array
     */
    public function findByFilter(string $filter, string $order): array
    {
        if (strlen(trim($filter)) === 0)
            return [];

        $sql = "
            SELECT * 
            FROM pedido_frete pfr
            INNER JOIN usuario autor ON autor.usu_id = pfr.usu_id
            INNER JOIN funcionario autor_fun ON autor_fun.fun_id = autor.fun_id
            INNER JOIN pessoa_fisica autor_pf ON autor_pf.pf_id = autor_fun.pf_id
            INNER JOIN forma_pagamento fp ON fp.for_pag_id = pfr.for_pag_fre 
            WHERE ped_fre_descricao LIKE ? 
            ORDER BY $order;
        ";

        if (!Banco::getInstance()->prepareStatement($sql))
            return [];

        $filtro = "%$filter%";

        if (!Banco::getInstance()->addParameters("s", [ $filtro ]))
            return [];

        if (!Banco::getInstance()->executeStatement())
            return [];

        return $this->resultToList(Banco::getInstance()->getResult());
    }

    /**
     * @param string $filter
     * @param int $client
     * @param string $order
     * @return array
     */
    public function findByFilterClient(string $filter, int $client, string $order): array
    {
        if (strlen(trim($filter)) === 0 || $client <= 0)
            return [];

        $sql = "
            SELECT * 
            FROM pedido_frete pfr
            INNER JOIN usuario autor ON autor.usu_id = pfr.usu_id
            INNER JOIN funcionario autor_fun ON autor_fun.fun_id = autor.fun_id
            INNER JOIN pessoa_fisica autor_pf ON autor_pf.pf_id = autor_fun.pf_id
            INNER JOIN forma_pagamento fp ON fp.for_pag_id = pfr.for_pag_fre 
            WHERE ped_fre_descricao LIKE ? AND pfr.cli_id = ? 
            ORDER BY $order;
        ";

        if (!Banco::getInstance()->prepareStatement($sql))
            return [];

        $filtro = "%$filter%";

        if (!Banco::getInstance()->addParameters("si", [ $filtro, $client ]))
            return [];

        if (!Banco::getInstance()->executeStatement())
            return [];

        return $this->resultToList(Banco::getInstance()->getResult());
    }

    /**
     * @param string $filter
     * @param int $status
     * @param string $order
     * @return array
     */
    public function findByFilterStatus(string $filter, int $status, string $order): array
    {
        if (strlen(trim($filter)) === 0)
            return [];

        $sql = "
            SELECT * 
            FROM pedido_frete pfr
            INNER JOIN usuario autor ON autor.usu_id = pfr.usu_id
            INNER JOIN funcionario autor_fun ON autor_fun.fun_id = autor.fun_id
            INNER JOIN pessoa_fisica autor_pf ON autor_pf.pf_id = autor_fun.pf_id
            INNER JOIN forma_pagamento fp ON fp.for_pag_id = pfr.for_pag_fre 
            INNER JOIN pedido_frete_status pfs ON pfr.ped_fre_id = pfs.ped_fre_id 
            INNER JOIN status st on pfs.sts_id = st.sts_id
            WHERE pfr.ped_fre_descricao LIKE ? AND st.sts_id = ? AND pfs.ped_fre_sts_atual IS TRUE 
            ORDER BY $order;
        ";

        if (!Banco::getInstance()->prepareStatement($sql))
            return [];

        $filtro = "%$filter%";

        if (!Banco::getInstance()->addParameters("si", [ $filtro, $status ]))
            return [];

        if (!Banco::getInstance()->executeStatement())
            return [];

        return $this->resultToList(Banco::getInstance()->getResult());
    }

    /**
     * @param string $filter
     * @param int $status
     * @param int $client
     * @param string $order
     * @return array
     */
    public function findByFilterStatusClient(string $filter, int $status, int $client, string $order): array
    {
        if (strlen(trim($filter)) === 0 || $status <= 0 || $client <= 0)
            return [];

        $sql = "
            SELECT * 
            FROM pedido_frete pfr
            INNER JOIN usuario autor ON autor.usu_id = pfr.usu_id
            INNER JOIN funcionario autor_fun ON autor_fun.fun_id = autor.fun_id
            INNER JOIN pessoa_fisica autor_pf ON autor_pf.pf_id = autor_fun.pf_id
            INNER JOIN forma_pagamento fp ON fp.for_pag_id = pfr.for_pag_fre 
            INNER JOIN pedido_frete_status pfs ON pfr.ped_fre_id = pfs.ped_fre_id 
            INNER JOIN status st on pfs.sts_id = st.sts_id
            WHERE pfr.ped_fre_descricao LIKE ? AND st.sts_id = ? AND pfs.ped_fre_sts_atual IS TRUE AND pfr.cli_id = ? 
            ORDER BY $order;
        ";

        if (!Banco::getInstance()->prepareStatement($sql))
            return [];

        $filtro = "%$filter%";

        if (!Banco::getInstance()->addParameters("sii", [ $filtro, $status, $client ]))
            return [];

        if (!Banco::getInstance()->executeStatement())
            return [];

        return $this->resultToList(Banco::getInstance()->getResult());
    }

    /**
     * @param string $inicio
     * @param string $fim
     * @param string $order
     * @return array
     */
    public function findByPeriod(string $inicio, string $fim, string $order): array
    {
        if (strlen($inicio) === 0 || strlen($fim) === 0)
            return [];

        $sql = "
            SELECT * 
            FROM pedido_frete pfr
            INNER JOIN usuario autor ON autor.usu_id = pfr.usu_id
            INNER JOIN funcionario autor_fun ON autor_fun.fun_id = autor.fun_id
            INNER JOIN pessoa_fisica autor_pf ON autor_pf.pf_id = autor_fun.pf_id
            INNER JOIN forma_pagamento fp ON fp.for_pag_id = pfr.for_pag_fre 
            WHERE pfr.ped_fre_data >= ? AND pfr.ped_fre_data <= ?
            ORDER BY $order;
        ";

        if (!Banco::getInstance()->prepareStatement($sql))
            return [];

        if (!Banco::getInstance()->addParameters("ss", [ $inicio, $fim ]))
            return [];

        if (!Banco::getInstance()->executeStatement())
            return [];

        return $this->resultToList(Banco::getInstance()->getResult());
    }

    /**
     * @param string $inicio
     * @param string $fim
     * @param int $client
     * @param string $order
     * @return array
     */
    public function findByPeriodClient(string $inicio, string $fim, int $client, string $order): array
    {
        if (strlen($inicio) === 0 || strlen($fim) === 0 || $client <= 0)
            return [];

        $sql = "
            SELECT * 
            FROM pedido_frete pfr
            INNER JOIN usuario autor ON autor.usu_id = pfr.usu_id
            INNER JOIN funcionario autor_fun ON autor_fun.fun_id = autor.fun_id
            INNER JOIN pessoa_fisica autor_pf ON autor_pf.pf_id = autor_fun.pf_id
            INNER JOIN forma_pagamento fp ON fp.for_pag_id = pfr.for_pag_fre 
            WHERE (pfr.ped_fre_data >= ? AND pfr.ped_fre_data <= ?) AND pfr.cli_id = ? 
            ORDER BY $order;
        ";

        if (!Banco::getInstance()->prepareStatement($sql))
            return [];

        if (!Banco::getInstance()->addParameters("ssi", [ $inicio, $fim, $client ]))
            return [];

        if (!Banco::getInstance()->executeStatement())
            return [];

        return $this->resultToList(Banco::getInstance()->getResult());
    }

    /**
     * @param string $inicio
     * @param string $fim
     * @param int $status
     * @param string $order
     * @return array
     */
    public function findByPeriodStatus(string $inicio, string $fim, int $status, string $order): array
    {
        if (strlen($inicio) === 0 || strlen($fim) === 0 || $status <= 0)
            return [];

        $sql = "
            SELECT * 
            FROM pedido_frete pfr
            INNER JOIN usuario autor ON autor.usu_id = pfr.usu_id
            INNER JOIN funcionario autor_fun ON autor_fun.fun_id = autor.fun_id
            INNER JOIN pessoa_fisica autor_pf ON autor_pf.pf_id = autor_fun.pf_id
            INNER JOIN forma_pagamento fp ON fp.for_pag_id = pfr.for_pag_fre 
            INNER JOIN pedido_frete_status pfs ON pfr.ped_fre_id = pfs.ped_fre_id 
            INNER JOIN status st on pfs.sts_id = st.sts_id 
            WHERE pfr.ped_fre_data >= ? AND pfr.ped_fre_data <= ? AND st.sts_id = ? AND pfs.ped_fre_sts_atual IS TRUE 
            ORDER BY $order;
        ";

        if (!Banco::getInstance()->prepareStatement($sql))
            return [];

        if (!Banco::getInstance()->addParameters("ssi", [ $inicio, $fim, $status ]))
            return [];

        if (!Banco::getInstance()->executeStatement())
            return [];

        return $this->resultToList(Banco::getInstance()->getResult());
    }

    /**
     * @param string $inicio
     * @param string $fim
     * @param int $status
     * @param int $client
     * @param string $order
     * @return array
     */
    public function findByPeriodStatusClient(string $inicio, string $fim, int $status, int $client, string $order): array
    {
        if (strlen($inicio) === 0 || strlen($fim) === 0 || $status <= 0 || $client <= 0)
            return [];

        $sql = "
            SELECT * 
            FROM pedido_frete pfr
            INNER JOIN usuario autor ON autor.usu_id = pfr.usu_id
            INNER JOIN funcionario autor_fun ON autor_fun.fun_id = autor.fun_id
            INNER JOIN pessoa_fisica autor_pf ON autor_pf.pf_id = autor_fun.pf_id
            INNER JOIN forma_pagamento fp ON fp.for_pag_id = pfr.for_pag_fre 
            INNER JOIN pedido_frete_status pfs ON pfr.ped_fre_id = pfs.ped_fre_id 
            INNER JOIN status st on pfs.sts_id = st.sts_id 
            WHERE (pfr.ped_fre_data >= ? AND pfr.ped_fre_data <= ?) AND st.sts_id = ? AND pfs.ped_fre_sts_atual IS TRUE AND pfr.cli_id = ? 
            ORDER BY $order;
        ";

        if (!Banco::getInstance()->prepareStatement($sql))
            return [];

        if (!Banco::getInstance()->addParameters("ssiI", [ $inicio, $fim, $status, $client ]))
            return [];

        if (!Banco::getInstance()->executeStatement())
            return [];

        return $this->resultToList(Banco::getInstance()->getResult());
    }

    /**
     * @param string $filter
     * @param string $inicio
     * @param string $fim
     * @param string $order
     * @return array
     */
    public function findByFilterPeriod(string $filter, string $inicio, string $fim, string $order): array
    {
        if (strlen(trim($filter)) === 0 || strlen($inicio) === 0 || strlen($fim) === 0)
            return [];

        $sql = "
            SELECT * 
            FROM pedido_frete pfr
            INNER JOIN usuario autor ON autor.usu_id = pfr.usu_id
            INNER JOIN funcionario autor_fun ON autor_fun.fun_id = autor.fun_id
            INNER JOIN pessoa_fisica autor_pf ON autor_pf.pf_id = autor_fun.pf_id
            INNER JOIN forma_pagamento fp ON fp.for_pag_id = pfr.for_pag_fre 
            WHERE pfr.ped_fre_descricao LIKE ? AND pfr.ped_fre_data >= ? AND pfr.ped_fre_data <= ?
            ORDER BY $order;
        ";

        if (!Banco::getInstance()->prepareStatement($sql))
            return [];

        $filtro = "%$filter%";

        if (!Banco::getInstance()->addParameters("sss", [ $filtro, $inicio, $fim ]))
            return [];

        if (!Banco::getInstance()->executeStatement())
            return [];

        return $this->resultToList(Banco::getInstance()->getResult());
    }

    /**
     * @param string $filter
     * @param string $inicio
     * @param string $fim
     * @param string $order
     * @return array
     */
    public function findByFilterPeriodClient(string $filter, string $inicio, string $fim, int $client, string $order): array
    {
        if (strlen(trim($filter)) === 0 || strlen($inicio) === 0 || strlen($fim) === 0 || $client <= 0)
            return [];

        $sql = "
            SELECT * 
            FROM pedido_frete pfr
            INNER JOIN usuario autor ON autor.usu_id = pfr.usu_id
            INNER JOIN funcionario autor_fun ON autor_fun.fun_id = autor.fun_id
            INNER JOIN pessoa_fisica autor_pf ON autor_pf.pf_id = autor_fun.pf_id
            INNER JOIN forma_pagamento fp ON fp.for_pag_id = pfr.for_pag_fre 
            WHERE pfr.ped_fre_descricao LIKE ? AND (pfr.ped_fre_data >= ? AND pfr.ped_fre_data <= ?) AND pfr.cli_id = ? 
            ORDER BY $order;
        ";

        if (!Banco::getInstance()->prepareStatement($sql))
            return [];

        $filtro = "%$filter%";

        if (!Banco::getInstance()->addParameters("sssi", [ $filtro, $inicio, $fim, $client ]))
            return [];

        if (!Banco::getInstance()->executeStatement())
            return [];

        return $this->resultToList(Banco::getInstance()->getResult());
    }

    /**
     * @param string $filter
     * @param string $inicio
     * @param string $fim
     * @param int $status
     * @param string $order
     * @return array
     */
    public function findByFilterPeriodStatus(string $filter, string $inicio, string $fim, int $status, string $order): array
    {
        if (strlen(trim($filter)) === 0 || strlen($inicio) === 0 || strlen($fim) === 0 || $status <= 0)
            return [];

        $sql = "
            SELECT * 
            FROM pedido_frete pfr
            INNER JOIN usuario autor ON autor.usu_id = pfr.usu_id
            INNER JOIN funcionario autor_fun ON autor_fun.fun_id = autor.fun_id
            INNER JOIN pessoa_fisica autor_pf ON autor_pf.pf_id = autor_fun.pf_id
            INNER JOIN forma_pagamento fp ON fp.for_pag_id = pfr.for_pag_fre 
            INNER JOIN pedido_frete_status pfs ON pfr.ped_fre_id = pfs.ped_fre_id 
            INNER JOIN status st on pfs.sts_id = st.sts_id 
            WHERE pfr.ped_fre_descricao LIKE ? AND pfr.ped_fre_data >= ? AND pfr.ped_fre_data <= ? AND st.sts_id = ? AND pfs.ped_fre_sts_atual IS TRUE 
            ORDER BY $order;
        ";

        if (!Banco::getInstance()->prepareStatement($sql))
            return [];

        $filtro = "%$filter%";

        if (!Banco::getInstance()->addParameters("sssi", [ $filtro, $inicio, $fim, $status ]))
            return [];

        if (!Banco::getInstance()->executeStatement())
            return [];

        return $this->resultToList(Banco::getInstance()->getResult());
    }

    /**
     * @param string $filter
     * @param string $inicio
     * @param string $fim
     * @param int $status
     * @param int $client
     * @param string $order
     * @return array
     */
    public function findByFilterPeriodStatusClient(string $filter, string $inicio, string $fim, int $status, int $client, string $order): array
    {
        if (strlen(trim($filter)) === 0 || strlen($inicio) === 0 || strlen($fim) === 0 || $status <= 0 || $client <= 0)
            return [];

        $sql = "
            SELECT * 
            FROM pedido_frete pfr
            INNER JOIN usuario autor ON autor.usu_id = pfr.usu_id
            INNER JOIN funcionario autor_fun ON autor_fun.fun_id = autor.fun_id
            INNER JOIN pessoa_fisica autor_pf ON autor_pf.pf_id = autor_fun.pf_id
            INNER JOIN forma_pagamento fp ON fp.for_pag_id = pfr.for_pag_fre 
            INNER JOIN pedido_frete_status pfs ON pfr.ped_fre_id = pfs.ped_fre_id 
            INNER JOIN status st on pfs.sts_id = st.sts_id 
            WHERE pfr.ped_fre_descricao LIKE ? AND (pfr.ped_fre_data >= ? AND pfr.ped_fre_data <= ?) AND st.sts_id = ? AND pfs.ped_fre_sts_atual IS TRUE AND pfr.cli_id = ? 
            ORDER BY $order;
        ";

        if (!Banco::getInstance()->prepareStatement($sql))
            return [];

        $filtro = "%$filter%";

        if (!Banco::getInstance()->addParameters("sssii", [ $filtro, $inicio, $fim, $status, $client ]))
            return [];

        if (!Banco::getInstance()->executeStatement())
            return [];

        return $this->resultToList(Banco::getInstance()->getResult());
    }

    /**
     * @param string $ordem
     * @return array
     */
    public function findAll(string $ordem = "pfr.ped_fre_descricao"): array
    {
        $sql = "
            SELECT * 
            FROM pedido_frete pfr
            INNER JOIN usuario autor ON autor.usu_id = pfr.usu_id
            INNER JOIN funcionario autor_fun ON autor_fun.fun_id = autor.fun_id
            INNER JOIN pessoa_fisica autor_pf ON autor_pf.pf_id = autor_fun.pf_id
            INNER JOIN forma_pagamento fp ON fp.for_pag_id = pfr.for_pag_fre
            ORDER BY $ordem;
        ";

        return $this->resultToList(Banco::getInstance()->getResultQuery($sql));
    }

    /**
     * @return int
     */
    public function save(): int
    {
        if (
            $this->id != 0 ||
            strlen($this->data) === 0 ||
            strlen(trim($this->descricao)) === 0 ||
            $this->distancia <= 0 ||
            $this->peso <= 0 ||
            $this->valor <= 0 ||
            $this->valorMotorista <= 0 ||
            strlen($this->entrega) <= 0 ||
            $this->cliente === null ||
            $this->destino === null ||
            $this->tipoCaminhao === null ||
            $this->proprietario === null ||
            $this->motorista === null ||
            $this->caminhao === null ||
            $this->formaPagamentoFrete === null ||
            $this->autor === null
        )
            return -5;

        $sql = "
            INSERT 
            INTO pedido_frete (
                               ped_fre_data, 
                               ped_fre_descricao, 
                               ped_fre_distancia, 
                               ped_fre_peso, 
                               ped_fre_valor, 
                               ped_fre_valor_motorista, 
                               ped_fre_entrada_motorista, 
                               ped_fre_entrega, 
                               orc_fre_id, 
                               ped_ven_id, 
                               rep_id, 
                               cli_id, 
                               cid_id, 
                               tip_cam_id, 
                               cam_id,
                               prp_id,
                               mot_id, 
                               for_pag_fre, 
                               for_pag_mot, 
                               usu_id
                               ) 
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);
        ";

        if (!Banco::getInstance()->prepareStatement($sql))
            return -10;

        $orcamento = $this->orcamento ? $this->orcamento->getId() : null;
        $venda = $this->venda ? $this->venda->getId() : null;
        $representacao = $this->representacao ? $this->representacao->getId() : null;
        $cliente = $this->cliente->getId();
        $destino = $this->destino->getId();
        $tipo = $this->tipoCaminhao->getId();
        $caminhao = $this->caminhao->getId();
        $proprietario = $this->proprietario->getId();
        $motorista = $this->motorista->getId();
        $formaFrete = $this->formaPagamentoFrete->getId();
        $formaMotorista = $this->formaPagamentoMotorista ? $this->formaPagamentoMotorista->getId() : null;
        $autor = $this->autor->getId();

        if (!Banco::getInstance()->addParameters(
            "ssiddddsiiiiiiiiiiii",
            [
                $this->data,
                $this->descricao,
                $this->distancia,
                $this->peso,
                $this->valor,
                $this->valorMotorista,
                $this->entradaMotorista,
                $this->entrega,
                $orcamento,
                $venda,
                $representacao,
                $cliente,
                $destino,
                $tipo,
                $caminhao,
                $proprietario,
                $motorista,
                $formaFrete,
                $formaMotorista,
                $autor
            ]
        ))
            return -10;

        if (!Banco::getInstance()->executeStatement())
            return -10;

        return Banco::getInstance()->getLastInsertId();
    }

    public function delete(): int
    {
        if ($this->id <= 0)
            return -5;

        $sql = "
            DELETE 
            FROM pedido_frete 
            WHERE ped_fre_id = ?;
        ";

        /** @var mysqli_stmt $stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }

        $stmt->bind_param(
            "i",
            $this->id
        );

        if (!$stmt->execute()) {
            echo $stmt->error;
            return -10;
        }

        return $stmt->affected_rows;
    }

    public function jsonSerialize(): array
    {
        $orcamento = ($this->orcamento !== null) ? $this->orcamento->jsonSerialize() : null;
        $venda = ($this->venda !== null) ? $this->venda->jsonSerialize() : null;
        $representacao = ($this->representacao !== null) ? $this->representacao->jsonSerialize() : null;
        $cliente = $this->cliente->jsonSerialize();
        $destino = $this->destino->jsonSerialize();
        $tipoCaminhao = $this->tipoCaminhao->jsonSerialize();
        $proprietario = $this->proprietario->jsonSerialize();
        $motorista = $this->motorista->jsonSerialize();
        $caminhao = $this->caminhao->jsonSerialize();
        $status = $this->status->jsonSerialize();
        $formaPagamentoFrete = $this->formaPagamentoFrete->jsonSerialize();
        $formaPagamentoMotorista = ($this->formaPagamentoMotorista) ? $this->formaPagamentoMotorista->jsonSerialize() : null;
        $autor = $this->autor->jsonSerialize();

        $itens = [];
        /** @var ItemPedidoFrete $item */
        foreach ($this->itens as $item) {
            $itens[] = $item->jsonSerialize();
        }

        $etapas = [];
        /** @var EtapaCarregamento $etapa */
        foreach ($this->etapas as $etapa)
            $etapas[] = $etapa->jsonSerialize();

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
            "cliente" => $cliente,
            "destino" => $destino,
            "tipoCaminhao" => $tipoCaminhao,
            "proprietario" => $proprietario,
            "motorista" => $motorista,
            "caminhao" => $caminhao,
            "status" => $status,
            "formaPagamentoFrete" => $formaPagamentoFrete,
            "formaPagamentoMotorista" => $formaPagamentoMotorista,
            "autor" => $autor,
            "itens" => $itens,
            "etapas" => $etapas
        ];
    }
}