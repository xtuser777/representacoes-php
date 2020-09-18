<?php


namespace scr\model;


use mysqli_result;
use mysqli_stmt;
use scr\util\Banco;

class ContaPagar
{
    /** @var int */
    private $id;

    /** @var int */
    private $conta;

    /** @var string */
    private $data;

    /** @var int */
    private $tipo;

    /** @var string */
    private $descricao;

    /** @var string */
    private $empresa;

    /** @var int */
    private $parcela;

    /** @var float */
    private $valor;

    /** @var int */
    private $situacao;

    /** @var string */
    private $vencimento;

    /** @var string */
    private $dataPagamento;

    /** @var float */
    private $valorPago;

    /** @var ContaPagar|null */
    private $pendencia;

    /** @var FormaPagamento|null */
    private $formaPagamento;

    /** @var Motorista|null */
    private $motorista;

    /** @var Funcionario|null */
    private $vendedor;

    /** @var Categoria|null */
    private $categoria;

    /** @var PedidoFrete|null */
    private $pedidoFrete;

    /** @var PedidoVenda|null */
    private $pedidoVenda;

    /** @var Usuario|null */
    private $autor;

    /**
     * ContaPagar constructor.
     * @param int $id
     * @param int $conta
     * @param string $data
     * @param int $tipo
     * @param string $descricao
     * @param string $empresa
     * @param int $parcela
     * @param float $valor
     * @param int $situacao
     * @param string $vencimento
     * @param string $dataPagamento
     * @param float $valorPago
     * @param ContaPagar|null $pendencia
     * @param FormaPagamento|null $formaPagamento
     * @param Motorista|null $motorista
     * @param Funcionario|null $vendedor
     * @param Categoria|null $categoria
     * @param PedidoFrete|null $pedidoFrete
     * @param PedidoVenda|null $pedidoVenda
     * @param Usuario|null $autor
     */
    public function __construct(int $id = 0, int $conta = 0, string $data = "", int $tipo = 0, string $descricao = "", string $empresa = "", int $parcela = 0, float $valor = 0.0, int $situacao = 0, string $vencimento = "", string $dataPagamento = "", float $valorPago = 0.0, ?ContaPagar $pendencia = null, ?FormaPagamento $formaPagamento = null, ?Motorista $motorista = null, ?Funcionario $vendedor = null, ?Categoria $categoria = null, ?PedidoFrete $pedidoFrete = null, ?PedidoVenda $pedidoVenda = null, ?Usuario $autor = null)
    {
        $this->id = $id;
        $this->conta = $conta;
        $this->data = $data;
        $this->tipo = $tipo;
        $this->descricao = $descricao;
        $this->empresa = $empresa;
        $this->parcela = $parcela;
        $this->valor = $valor;
        $this->situacao = $situacao;
        $this->vencimento = $vencimento;
        $this->dataPagamento = $dataPagamento;
        $this->valorPago = $valorPago;
        $this->pendencia = $pendencia;
        $this->formaPagamento = $formaPagamento;
        $this->motorista = $motorista;
        $this->vendedor = $vendedor;
        $this->categoria = $categoria;
        $this->pedidoFrete = $pedidoFrete;
        $this->pedidoVenda = $pedidoVenda;
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
     * @return int
     */
    public function getConta(): int
    {
        return $this->conta;
    }

    /**
     * @param int $conta
     */
    public function setConta(int $conta): void
    {
        $this->conta = $conta;
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
     * @return int
     */
    public function getTipo(): int
    {
        return $this->tipo;
    }

    /**
     * @param int $tipo
     */
    public function setTipo(int $tipo): void
    {
        $this->tipo = $tipo;
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
     * @return string
     */
    public function getEmpresa(): string
    {
        return $this->empresa;
    }

    /**
     * @param string $empresa
     */
    public function setEmpresa(string $empresa): void
    {
        $this->empresa = $empresa;
    }

    /**
     * @return int
     */
    public function getParcela(): int
    {
        return $this->parcela;
    }

    /**
     * @param int $parcela
     */
    public function setParcela(int $parcela): void
    {
        $this->parcela = $parcela;
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
     * @return int
     */
    public function getSituacao(): int
    {
        return $this->situacao;
    }

    /**
     * @param int $situacao
     */
    public function setSituacao(int $situacao): void
    {
        $this->situacao = $situacao;
    }

    /**
     * @return string
     */
    public function getVencimento(): string
    {
        return $this->vencimento;
    }

    /**
     * @param string $vencimento
     */
    public function setVencimento(string $vencimento): void
    {
        $this->vencimento = $vencimento;
    }

    /**
     * @return string
     */
    public function getDataPagamento(): string
    {
        return $this->dataPagamento;
    }

    /**
     * @param string $dataPagamento
     */
    public function setDataPagamento(string $dataPagamento): void
    {
        $this->dataPagamento = $dataPagamento;
    }

    /**
     * @return float
     */
    public function getValorPago(): float
    {
        return $this->valorPago;
    }

    /**
     * @param float $valorPago
     */
    public function setValorPago(float $valorPago): void
    {
        $this->valorPago = $valorPago;
    }

    /**
     * @return ContaPagar|null
     */
    public function getPendencia(): ?ContaPagar
    {
        return $this->pendencia;
    }

    /**
     * @param ContaPagar|null $pendencia
     */
    public function setPendencia(?ContaPagar $pendencia): void
    {
        $this->pendencia = $pendencia;
    }

    /**
     * @return FormaPagamento|null
     */
    public function getFormaPagamento(): ?FormaPagamento
    {
        return $this->formaPagamento;
    }

    /**
     * @param FormaPagamento|null $formaPagamento
     */
    public function setFormaPagamento(?FormaPagamento $formaPagamento): void
    {
        $this->formaPagamento = $formaPagamento;
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
     * @return Categoria|null
     */
    public function getCategoria(): ?Categoria
    {
        return $this->categoria;
    }

    /**
     * @param Categoria|null $categoria
     */
    public function setCategoria(?Categoria $categoria): void
    {
        $this->categoria = $categoria;
    }

    /**
     * @return PedidoFrete|null
     */
    public function getPedidoFrete(): ?PedidoFrete
    {
        return $this->pedidoFrete;
    }

    /**
     * @param PedidoFrete|null $pedidoFrete
     */
    public function setPedidoFrete(?PedidoFrete $pedidoFrete): void
    {
        $this->pedidoFrete = $pedidoFrete;
    }

    /**
     * @return PedidoVenda|null
     */
    public function getPedidoVenda(): ?PedidoVenda
    {
        return $this->pedidoVenda;
    }

    /**
     * @param PedidoVenda|null $pedidoVenda
     */
    public function setPedidoVenda(?PedidoVenda $pedidoVenda): void
    {
        $this->pedidoVenda = $pedidoVenda;
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

    public function findNewCount(): int
    {
        $sql = "
            SELECT MAX(con_pag_conta) AS CONTA
            FROM conta_pagar;
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if ($stmt === null) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }

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

        $conta = ($row["CONTA"]) ? (int) $row["CONTA"] : 0;

        return $conta + 1;
    }

    public function findRelationsByFP(int $fp): int
    {
        $sql = "
            SELECT COUNT(con_pag_id) as FORMAS 
            FROM conta_pagar 
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

    public function findByCount(int $count): array
    {
        if ($count <= 0)
            return [];

        $sql = "
            SELECT con_pag_id, 
                   con_pag_conta, 
                   con_pag_data, 
                   con_pag_tipo, 
                   con_pag_descricao, 
                   con_pag_empresa,
                   con_pag_parcela, 
                   con_pag_valor, 
                   con_pag_situacao, 
                   con_pag_vencimento,
                   con_pag_data_pagamento, 
                   con_pag_valor_pago,
                   con_pag_pendencia,
                   for_pag_id, 
                   mot_id, 
                   fun_id, 
                   cat_id, 
                   ped_fre_id, 
                   ped_ven_id, 
                   usu_id
            FROM conta_pagar
            WHERE con_pag_conta = ?;
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return [];
        }

        $stmt->bind_param("i", $count);
        if (!$stmt->execute()) {
            echo $stmt->error;
            return [];
        }

        /** @var $result mysqli_result */
        $result = $stmt->get_result();
        if (!$result || $result->num_rows <= 0) {
            echo $stmt->error;
            return [];
        }

        $contas = [];
        while ($row = $result->fetch_assoc()) {
            $conta = new ContaPagar();
            $conta->setId($row["con_pag_id"]);
            $conta->setConta($row["con_pag_conta"]);
            $conta->setData($row["con_pag_data"]);
            $conta->setTipo($row["con_pag_tipo"]);
            $conta->setDescricao($row["con_pag_descricao"]);
            $conta->setEmpresa($row["con_pag_empresa"]);
            $conta->setParcela($row["con_pag_parcela"]);
            $conta->setValor($row["con_pag_valor"]);
            $conta->setSituacao($row["con_pag_situacao"]);
            $conta->setVencimento($row["con_pag_vencimento"]);
            $conta->setDataPagamento((!$row["con_pag_data_pagamento"]) ? "" : $row["con_pag_data_pagamento"]);
            $conta->setValorPago((!$row["con_pag_valor_pago"]) ? 0.0 : $row["con_pag_valor_pago"]);
            $conta->setPendencia(($row["con_pag_pendencia"]) ? (new ContaPagar())->findById($row["con_pag_pendencia"]) : null);
            $conta->setFormaPagamento(($row["for_pag_id"] !== null) ? FormaPagamento::findById($row["for_pag_id"]) : null);
            $conta->setMotorista(($row["mot_id"] !== null) ? Motorista::findById($row["mot_id"]) : null);
            $conta->setVendedor(($row["fun_id"] !== null) ? Funcionario::getById($row["fun_id"]) : null);
            $conta->setCategoria(Categoria::findById($row["cat_id"]));
            $conta->setPedidoFrete(($row["ped_fre_id"] !== null) ? (new PedidoFrete())->findById($row["ped_fre_id"]) : null);
            $conta->setPedidoVenda(($row["ped_ven_id"] !== null) ? (new PedidoVenda())->findById($row["ped_ven_id"]) : null);
            $conta->setAutor(Usuario::getById($row["usu_id"]));

            $contas[] = $conta;
        }

        return $contas;
    }

    public function findById(int $id): ?ContaPagar
    {
        if ($id <= 0) return null;

        $sql = "
            SELECT con_pag_id, 
                   con_pag_conta, 
                   con_pag_data, 
                   con_pag_tipo, 
                   con_pag_descricao, 
                   con_pag_empresa,
                   con_pag_parcela, 
                   con_pag_valor, 
                   con_pag_situacao, 
                   con_pag_vencimento,
                   con_pag_data_pagamento, 
                   con_pag_valor_pago,
                   con_pag_pendencia,
                   for_pag_id, 
                   mot_id, 
                   fun_id, 
                   cat_id, 
                   ped_fre_id, 
                   ped_ven_id, 
                   usu_id
            FROM conta_pagar
            WHERE con_pag_id = ?;
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

        return new ContaPagar(
            $row["con_pag_id"],
            $row["con_pag_conta"],
            $row["con_pag_data"],
            $row["con_pag_tipo"],
            $row["con_pag_descricao"],
            $row["con_pag_empresa"],
            $row["con_pag_parcela"],
            $row["con_pag_valor"],
            $row["con_pag_situacao"],
            $row["con_pag_vencimento"],
            (!$row["con_pag_data_pagamento"]) ? "" : $row["con_pag_data_pagamento"],
            (!$row["con_pag_valor_pago"]) ? 0.0 : $row["con_pag_valor_pago"],
            ($row["con_pag_pendencia"]) ? (new ContaPagar())->findById($row["con_pag_pendencia"]) : null,
            ($row["for_pag_id"] !== null) ? FormaPagamento::findById($row["for_pag_id"]) : null,
            ($row["mot_id"] !== null) ? Motorista::findById($row["mot_id"]) : null,
            ($row["fun_id"] !== null) ? Funcionario::getById($row["fun_id"]) : null,
            Categoria::findById($row["cat_id"]),
            ($row["ped_fre_id"] !== null) ? (new PedidoFrete())->findById($row["ped_fre_id"]) : null,
            ($row["ped_ven_id"] !== null) ? (new PedidoVenda())->findById($row["ped_ven_id"]) : null,
            Usuario::getById($row["usu_id"])
        );
    }

    public function findByDescription(string $description): array
    {
        if ($description === null || strlen($description) <= 0) return [];

        $sql = "
            SELECT con_pag_id, 
                   con_pag_conta, 
                   con_pag_data, 
                   con_pag_tipo, 
                   con_pag_descricao, 
                   con_pag_empresa,
                   con_pag_parcela, 
                   con_pag_valor, 
                   con_pag_situacao, 
                   con_pag_vencimento,
                   con_pag_data_pagamento, 
                   con_pag_valor_pago,
                   con_pag_pendencia,
                   for_pag_id, 
                   mot_id, 
                   fun_id, 
                   cat_id, 
                   ped_fre_id, 
                   ped_ven_id, 
                   usu_id
            FROM conta_pagar
            WHERE con_pag_descricao like ?;
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return [];
        }
        $desc = "%".$description."%";
        $stmt->bind_param("s", $desc);
        if (!$stmt->execute()) {
            echo $stmt->error;
            return [];
        }

        /** @var $result mysqli_result */
        $result = $stmt->get_result();
        if (!$result || $result->num_rows <= 0) {
            echo $stmt->error;
            return [];
        }

        $contas = [];
        while ($row = $result->fetch_assoc()) {
            $contas[] = new ContaPagar(
                $row["con_pag_id"],
                $row["con_pag_conta"],
                $row["con_pag_data"],
                $row["con_pag_tipo"],
                $row["con_pag_descricao"],
                $row["con_pag_empresa"],
                $row["con_pag_parcela"],
                $row["con_pag_valor"],
                $row["con_pag_situacao"],
                $row["con_pag_vencimento"],
                (!$row["con_pag_data_pagamento"]) ? "" : $row["con_pag_data_pagamento"],
                (!$row["con_pag_valor_pago"]) ? 0.0 : $row["con_pag_valor_pago"],
                ($row["con_pag_pendencia"]) ? (new ContaPagar())->findById($row["con_pag_pendencia"]) : null,
                ($row["for_pag_id"] !== null) ? FormaPagamento::findById($row["for_pag_id"]) : null,
                ($row["mot_id"] !== null) ? Motorista::findById($row["mot_id"]) : null,
                ($row["fun_id"] !== null) ? Funcionario::getById($row["fun_id"]) : null,
                Categoria::findById($row["cat_id"]),
                ($row["ped_fre_id"] !== null) ? (new PedidoFrete())->findById($row["ped_fre_id"]) : null,
                ($row["ped_ven_id"] !== null) ? (new PedidoVenda())->findById($row["ped_ven_id"]) : null,
                Usuario::getById($row["usu_id"])
            );
        }

        return $contas;
    }

    public function findByDate(string $date): array
    {
        if ($date === null || strlen($date) <= 0) return [];

        $sql = "
            SELECT con_pag_id, 
                   con_pag_conta, 
                   con_pag_data, 
                   con_pag_tipo, 
                   con_pag_descricao, 
                   con_pag_empresa,
                   con_pag_parcela, 
                   con_pag_valor, 
                   con_pag_situacao, 
                   con_pag_vencimento,
                   con_pag_data_pagamento, 
                   con_pag_valor_pago,
                   con_pag_pendencia,
                   for_pag_id, 
                   mot_id, 
                   fun_id, 
                   cat_id, 
                   ped_fre_id, 
                   ped_ven_id, 
                   usu_id
            FROM conta_pagar
            WHERE con_pag_data = ?;
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return [];
        }

        $stmt->bind_param("s", $date);
        if (!$stmt->execute()) {
            echo $stmt->error;
            return [];
        }

        /** @var $result mysqli_result */
        $result = $stmt->get_result();
        if (!$result || $result->num_rows <= 0) {
            echo $stmt->error;
            return [];
        }

        $contas = [];
        while ($row = $result->fetch_assoc()) {
            $contas[] = new ContaPagar(
                $row["con_pag_id"],
                $row["con_pag_conta"],
                $row["con_pag_data"],
                $row["con_pag_tipo"],
                $row["con_pag_descricao"],
                $row["con_pag_empresa"],
                $row["con_pag_parcela"],
                $row["con_pag_valor"],
                $row["con_pag_situacao"],
                $row["con_pag_vencimento"],
                (!$row["con_pag_data_pagamento"]) ? "" : $row["con_pag_data_pagamento"],
                (!$row["con_pag_valor_pago"]) ? 0.0 : $row["con_pag_valor_pago"],
                ($row["con_pag_pendencia"]) ? (new ContaPagar())->findById($row["con_pag_pendencia"]) : null,
                ($row["for_pag_id"] !== null) ? FormaPagamento::findById($row["for_pag_id"]) : null,
                ($row["mot_id"] !== null) ? Motorista::findById($row["mot_id"]) : null,
                ($row["fun_id"] !== null) ? Funcionario::getById($row["fun_id"]) : null,
                Categoria::findById($row["cat_id"]),
                ($row["ped_fre_id"] !== null) ? (new PedidoFrete())->findById($row["ped_fre_id"]) : null,
                ($row["ped_ven_id"] !== null) ? (new PedidoVenda())->findById($row["ped_ven_id"]) : null,
                Usuario::getById($row["usu_id"])
            );
        }

        return $contas;
    }

    public function findByPeriod(string $date1, string $date2): array
    {
        if ($date1 === null || strlen($date1) <= 0 || $date2 === null || strlen($date2) <= 0)
            return [];

        $sql = "
            SELECT con_pag_id, 
                   con_pag_conta, 
                   con_pag_data, 
                   con_pag_tipo, 
                   con_pag_descricao, 
                   con_pag_empresa,
                   con_pag_parcela, 
                   con_pag_valor, 
                   con_pag_situacao, 
                   con_pag_vencimento,
                   con_pag_data_pagamento, 
                   con_pag_valor_pago,
                   con_pag_pendencia,
                   for_pag_id, 
                   mot_id, 
                   fun_id, 
                   cat_id, 
                   ped_fre_id, 
                   ped_ven_id, 
                   usu_id
            FROM conta_pagar
            WHERE con_pag_data >= ?
            AND con_pag_data <= ?;
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return [];
        }

        $stmt->bind_param("ss", $date1, $date2);
        if (!$stmt->execute()) {
            echo $stmt->error;
            return [];
        }

        /** @var $result mysqli_result */
        $result = $stmt->get_result();
        if (!$result || $result->num_rows <= 0) {
            echo $stmt->error;
            return [];
        }

        $contas = [];
        while ($row = $result->fetch_assoc()) {
            $contas[] = new ContaPagar(
                $row["con_pag_id"],
                $row["con_pag_conta"],
                $row["con_pag_data"],
                $row["con_pag_tipo"],
                $row["con_pag_descricao"],
                $row["con_pag_empresa"],
                $row["con_pag_parcela"],
                $row["con_pag_valor"],
                $row["con_pag_situacao"],
                $row["con_pag_vencimento"],
                (!$row["con_pag_data_pagamento"]) ? "" : $row["con_pag_data_pagamento"],
                (!$row["con_pag_valor_pago"]) ? 0.0 : $row["con_pag_valor_pago"],
                ($row["con_pag_pendencia"]) ? (new ContaPagar())->findById($row["con_pag_pendencia"]) : null,
                ($row["for_pag_id"] !== null) ? FormaPagamento::findById($row["for_pag_id"]) : null,
                ($row["mot_id"] !== null) ? Motorista::findById($row["mot_id"]) : null,
                ($row["fun_id"] !== null) ? Funcionario::getById($row["fun_id"]) : null,
                Categoria::findById($row["cat_id"]),
                ($row["ped_fre_id"] !== null) ? (new PedidoFrete())->findById($row["ped_fre_id"]) : null,
                ($row["ped_ven_id"] !== null) ? (new PedidoVenda())->findById($row["ped_ven_id"]) : null,
                Usuario::getById($row["usu_id"])
            );
        }

        return $contas;
    }

    public function findByDescriptionDate(string $description, string $date): array
    {
        if ($description === null || strlen($description) <= 0 || $date === null || strlen($date) <= 0) return [];

        $sql = "
            SELECT con_pag_id, 
                   con_pag_conta, 
                   con_pag_data, 
                   con_pag_tipo, 
                   con_pag_descricao, 
                   con_pag_empresa,
                   con_pag_parcela, 
                   con_pag_valor, 
                   con_pag_situacao, 
                   con_pag_vencimento,
                   con_pag_data_pagamento, 
                   con_pag_valor_pago,
                   con_pag_pendencia,
                   for_pag_id, 
                   mot_id, 
                   fun_id, 
                   cat_id, 
                   ped_fre_id, 
                   ped_ven_id, 
                   usu_id
            FROM conta_pagar
            WHERE con_pag_descricao like ?
            AND con_pag_data = ?;
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return [];
        }
        $desc = "%".$description."%";
        $stmt->bind_param("ss", $desc, $date);
        if (!$stmt->execute()) {
            echo $stmt->error;
            return [];
        }

        /** @var $result mysqli_result */
        $result = $stmt->get_result();
        if (!$result || $result->num_rows <= 0) {
            echo $stmt->error;
            return [];
        }

        $contas = [];
        while ($row = $result->fetch_assoc()) {
            $contas[] = new ContaPagar(
                $row["con_pag_id"],
                $row["con_pag_conta"],
                $row["con_pag_data"],
                $row["con_pag_tipo"],
                $row["con_pag_descricao"],
                $row["con_pag_empresa"],
                $row["con_pag_parcela"],
                $row["con_pag_valor"],
                $row["con_pag_situacao"],
                $row["con_pag_vencimento"],
                (!$row["con_pag_data_pagamento"]) ? "" : $row["con_pag_data_pagamento"],
                (!$row["con_pag_valor_pago"]) ? 0.0 : $row["con_pag_valor_pago"],
                ($row["con_pag_pendencia"]) ? (new ContaPagar())->findById($row["con_pag_pendencia"]) : null,
                ($row["for_pag_id"] !== null) ? FormaPagamento::findById($row["for_pag_id"]) : null,
                ($row["mot_id"] !== null) ? Motorista::findById($row["mot_id"]) : null,
                ($row["fun_id"] !== null) ? Funcionario::getById($row["fun_id"]) : null,
                Categoria::findById($row["cat_id"]),
                ($row["ped_fre_id"] !== null) ? (new PedidoFrete())->findById($row["ped_fre_id"]) : null,
                ($row["ped_ven_id"] !== null) ? (new PedidoVenda())->findById($row["ped_ven_id"]) : null,
                Usuario::getById($row["usu_id"])
            );
        }

        return $contas;
    }

    public function findByDescriptionPeriod(string $description, string $date1, string $date2): array
    {
        if (
            $description === null || strlen($description) <= 0 ||
            $date1 === null || strlen($date1) <= 0 ||
            $date2 === null || strlen($date2) <= 0
        )
            return [];

        $sql = "
            SELECT con_pag_id, 
                   con_pag_conta, 
                   con_pag_data, 
                   con_pag_tipo, 
                   con_pag_descricao, 
                   con_pag_empresa,
                   con_pag_parcela, 
                   con_pag_valor, 
                   con_pag_situacao, 
                   con_pag_vencimento,
                   con_pag_data_pagamento, 
                   con_pag_valor_pago,
                   con_pag_pendencia,
                   for_pag_id, 
                   mot_id, 
                   fun_id, 
                   cat_id, 
                   ped_fre_id, 
                   ped_ven_id, 
                   usu_id
            FROM conta_pagar
            WHERE con_pag_descricao like ?
            AND con_pag_data >= ?
            AND con_pag_data <= ?;
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return [];
        }
        $desc = "%".$description."%";
        $stmt->bind_param("sss", $desc, $date1, $date2);
        if (!$stmt->execute()) {
            echo $stmt->error;
            return [];
        }

        /** @var $result mysqli_result */
        $result = $stmt->get_result();
        if (!$result || $result->num_rows <= 0) {
            echo $stmt->error;
            return [];
        }

        $contas = [];
        while ($row = $result->fetch_assoc()) {
            $contas[] = new ContaPagar(
                $row["con_pag_id"],
                $row["con_pag_conta"],
                $row["con_pag_data"],
                $row["con_pag_tipo"],
                $row["con_pag_descricao"],
                $row["con_pag_empresa"],
                $row["con_pag_parcela"],
                $row["con_pag_valor"],
                $row["con_pag_situacao"],
                $row["con_pag_vencimento"],
                (!$row["con_pag_data_pagamento"]) ? "" : $row["con_pag_data_pagamento"],
                (!$row["con_pag_valor_pago"]) ? 0.0 : $row["con_pag_valor_pago"],
                ($row["con_pag_pendencia"]) ? (new ContaPagar())->findById($row["con_pag_pendencia"]) : null,
                ($row["for_pag_id"] !== null) ? FormaPagamento::findById($row["for_pag_id"]) : null,
                ($row["mot_id"] !== null) ? Motorista::findById($row["mot_id"]) : null,
                ($row["fun_id"] !== null) ? Funcionario::getById($row["fun_id"]) : null,
                Categoria::findById($row["cat_id"]),
                ($row["ped_fre_id"] !== null) ? (new PedidoFrete())->findById($row["ped_fre_id"]) : null,
                ($row["ped_ven_id"] !== null) ? (new PedidoVenda())->findById($row["ped_ven_id"]) : null,
                Usuario::getById($row["usu_id"])
            );
        }

        return $contas;
    }

    public function findAll(): array
    {
        $sql = "
            SELECT con_pag_id, 
                   con_pag_conta, 
                   con_pag_data, 
                   con_pag_tipo, 
                   con_pag_descricao, 
                   con_pag_empresa,
                   con_pag_parcela, 
                   con_pag_valor, 
                   con_pag_situacao, 
                   con_pag_vencimento,
                   con_pag_data_pagamento, 
                   con_pag_valor_pago,
                   con_pag_pendencia,
                   for_pag_id, 
                   mot_id, 
                   fun_id, 
                   cat_id, 
                   ped_fre_id, 
                   ped_ven_id, 
                   usu_id
            FROM conta_pagar
            ORDER BY con_pag_descricao;
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return [];
        }

        if (!$stmt->execute()) {
            echo $stmt->error;
            return [];
        }

        /** @var $result mysqli_result */
        $result = $stmt->get_result();
        if (!$result || $result->num_rows <= 0) {
            echo $stmt->error;
            return [];
        }

        $contas = [];
        while ($row = $result->fetch_assoc()) {
            $conta = new ContaPagar();
            $conta->setId($row["con_pag_id"]);
            $conta->setConta($row["con_pag_conta"]);
            $conta->setData($row["con_pag_data"]);
            $conta->setTipo($row["con_pag_tipo"]);
            $conta->setDescricao($row["con_pag_descricao"]);
            $conta->setEmpresa($row["con_pag_empresa"]);
            $conta->setParcela($row["con_pag_parcela"]);
            $conta->setValor($row["con_pag_valor"]);
            $conta->setSituacao($row["con_pag_situacao"]);
            $conta->setVencimento($row["con_pag_vencimento"]);
            $conta->setDataPagamento((!$row["con_pag_data_pagamento"]) ? "" : $row["con_pag_data_pagamento"]);
            $conta->setValorPago((!$row["con_pag_valor_pago"]) ? 0.0 : $row["con_pag_valor_pago"]);
            $conta->setPendencia(($row["con_pag_pendencia"]) ? (new ContaPagar())->findById($row["con_pag_pendencia"]) : null);
            $conta->setFormaPagamento(($row["for_pag_id"] !== null) ? FormaPagamento::findById($row["for_pag_id"]) : null);
            $conta->setMotorista(($row["mot_id"] !== null) ? Motorista::findById($row["mot_id"]) : null);
            $conta->setVendedor(($row["fun_id"] !== null) ? Funcionario::getById($row["fun_id"]) : null);
            $conta->setCategoria(Categoria::findById($row["cat_id"]));
            $conta->setPedidoFrete(($row["ped_fre_id"] !== null) ? (new PedidoFrete())->findById($row["ped_fre_id"]) : null);
            $conta->setPedidoVenda(($row["ped_ven_id"] !== null) ? (new PedidoVenda())->findById($row["ped_ven_id"]) : null);
            $conta->setAutor(Usuario::getById($row["usu_id"]));

            $contas[] = $conta;
        }

        return $contas;
    }

    public function save(): int
    {
        if(
            $this->id !== 0 ||
            $this->conta <= 0 ||
            strlen($this->data) === 0 ||
            strlen($this->descricao) === 0 ||
            strlen($this->empresa) === 0 ||
            $this->parcela <= 0 ||
            $this->valor <= 0 ||
            strlen($this->vencimento) === 0 ||
            $this->situacao === 0 ||
            $this->categoria === null ||
            $this->autor === null
        )
            return -5;

        $sql = "
            INSERT
            INTO conta_pagar(
                con_pag_conta, 
                con_pag_data, 
                con_pag_tipo,
                con_pag_descricao, 
                con_pag_empresa, 
                con_pag_parcela, 
                con_pag_valor, 
                con_pag_vencimento, 
                con_pag_situacao, 
                mot_id, 
                fun_id, 
                cat_id, 
                ped_fre_id, 
                ped_ven_id, 
                usu_id
            )
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }

        $mot = ($this->motorista) ? $this->motorista->getId() : null;
        $vdd = ($this->vendedor) ? $this->vendedor->getId() : null;
        $cat = $this->categoria->getId();
        $fre = ($this->pedidoFrete) ? $this->pedidoFrete->getId() : null;
        $ven = ($this->pedidoVenda) ? $this->pedidoVenda->getId() : null;
        $autor = $this->autor->getId();

        $stmt->bind_param(
            "isissidsiiiiiii",
            $this->conta,
            $this->data,
            $this->tipo,
            $this->descricao,
            $this->empresa,
            $this->parcela,
            $this->valor,
            $this->vencimento,
            $this->situacao,
            $mot,
            $vdd,
            $cat,
            $fre,
            $ven,
            $autor
        );

        if (!$stmt->execute()) {
            echo $stmt->error;
            return -10;
        }

        return $stmt->insert_id;
    }

    public function quitar(int $forma, float $valor, string $data, int $situacao, int $pendencia): int
    {
        if (
            $this->id <= 0 ||
            $valor <= 0 ||
            strlen($data) === 0 ||
            $situacao === 0 ||
            $forma === 0
        )
            return -5;

        $sql = "
            UPDATE conta_pagar
            SET con_pag_valor_pago = ?,
            con_pag_data_pagamento = ?,
            con_pag_situacao = ?,
            con_pag_pendencia = ?, 
            for_pag_id = ?
            WHERE con_pag_id = ?;
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }

        $pen = ($pendencia > 0) ? $pendencia : null;
        $stmt->bind_param(
            "dsiiii",
            $valor,
            $data,
            $situacao,
            $pen,
            $forma,
            $this->id
        );

        if (!$stmt->execute()) {
            echo $stmt->error;
            return -10;
        }

        return $stmt->affected_rows;
    }

    public function estornar(): int
    {
        if ($this->id <= 0)
            return -5;

        $sql = "
            UPDATE conta_pagar
            SET con_pag_valor_pago = null,
            con_pag_data_pagamento = null,
            con_pag_situacao = 1,
            con_pag_pendencia = null, 
            for_pag_id = null
            WHERE con_pag_id = ?;
        ";

        /** @var $stmt mysqli_stmt */
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

    public function delete(): int
    {
        if ($this->id <= 0)
            return -5;

        $sql = "
            DELETE
            FROM conta_pagar
            WHERE con_pag_id = ?;
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if ($stmt === null) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }

        $stmt->bind_param("i", $this->id);
        if ($stmt->execute() === false) {
            echo $stmt->error;
            return -10;
        }

        return $stmt->affected_rows;
    }

    public function jsonSerialize(): array
    {
        $pendencia = ($this->pendencia) ? $this->pendencia->jsonSerialize() : null;
        $formaPagamento = ($this->formaPagamento !== null) ? $this->formaPagamento->jsonSerialize() : null;
        $motorista = ($this->motorista !== null) ? $this->motorista->jsonSerialize() : null;
        $vendedor = ($this->vendedor !== null) ? $this->vendedor->jsonSerialize() : null;
        $categoria = $this->categoria->jsonSerialize();
        $pedidoFrete = ($this->pedidoFrete !== null) ? $this->pedidoFrete->jsonSerialize() : null;
        $pedidoVenda = ($this->pedidoVenda !== null) ? $this->pedidoVenda->jsonSerialize() : null;
        $autor = $this->autor->jsonSerialize();

        return [
            "id" => $this->id,
            "conta" => $this->conta,
            "data" => $this->data,
            "tipo" => $this->tipo,
            "descricao" => $this->descricao,
            "empresa" => $this->empresa,
            "parcela" => $this->parcela,
            "valor" => $this->valor,
            "situacao" => $this->situacao,
            "vencimento" => $this->vencimento,
            "dataPagamento" => $this->dataPagamento,
            "valorPago" => $this->valorPago,
            "pendencia" => $pendencia,
            "formaPagamento" => $formaPagamento,
            "motorista" => $motorista,
            "vendedor" => $vendedor,
            "categoria" => $categoria,
            "pedidoFrete" => $pedidoFrete,
            "pedidoVenda" => $pedidoVenda,
            "autor" => $autor
        ];
    }
}
