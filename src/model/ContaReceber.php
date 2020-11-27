<?php


namespace scr\model;


use mysqli_result;
use mysqli_stmt;
use scr\util\Banco;

class ContaReceber
{
    /** @var int */
    private $id;

    /** @var string */
    private $data;

    /** @var int */
    private $conta;

    /** @var string */
    private $descricao;

    /** @var string */
    private $pagador;

    /** @var float */
    private $valor;

    /** @var bool */
    private $comissao;

    /** @var int */
    private $situacao;

    /** @var string */
    private $vencimento;

    /** @var string */
    private $dataRecebimento;

    /** @var float */
    private $valorRecebido;

    /** @var ContaReceber|null */
    private $pendencia;

    /** @var FormaPagamento|null */
    private $formaRecebimento;

    /** @var Representacao|null */
    private $representacao;

    /** @var PedidoVenda|null */
    private $pedidoVenda;

    /** @var PedidoFrete|null */
    private $pedidoFrete;

    /** @var Usuario */
    private $autor;

    /**
     * ContaReceber constructor.
     * @param int $id
     * @param string $data
     * @param int $conta
     * @param string $descricao
     * @param string $pagador
     * @param float $valor
     * @param bool $comissao
     * @param int $situacao
     * @param string $vencimento
     * @param string $dataRecebimento
     * @param float $valorRecebido
     * @param ContaReceber|null $pendencia
     * @param FormaPagamento|null $formaRecebimento
     * @param Representacao|null $representacao
     * @param PedidoVenda|null $pedidoVenda
     * @param PedidoFrete|null $pedidoFrete
     * @param Usuario|null $autor
     */
    public function __construct(int $id = 0, string $data = "", int $conta = 0, string $descricao = "", string $pagador = "", float $valor = 0.0, bool $comissao = false, int $situacao = 0, string $vencimento = "", string $dataRecebimento = "", float $valorRecebido = 0.0, ?ContaReceber $pendencia = null, ?FormaPagamento $formaRecebimento = null, ?Representacao $representacao = null, ?PedidoVenda $pedidoVenda = null, ?PedidoFrete $pedidoFrete = null, Usuario $autor = null)
    {
        $this->id = $id;
        $this->data = $data;
        $this->conta = $conta;
        $this->descricao = $descricao;
        $this->pagador = $pagador;
        $this->valor = $valor;
        $this->comissao = $comissao;
        $this->situacao = $situacao;
        $this->vencimento = $vencimento;
        $this->dataRecebimento = $dataRecebimento;
        $this->valorRecebido = $valorRecebido;
        $this->pendencia = $pendencia;
        $this->formaRecebimento = $formaRecebimento;
        $this->representacao = $representacao;
        $this->pedidoVenda = $pedidoVenda;
        $this->pedidoFrete = $pedidoFrete;
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
    public function getPagador(): string
    {
        return $this->pagador;
    }

    /**
     * @param string $pagador
     */
    public function setPagador(string $pagador): void
    {
        $this->pagador = $pagador;
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
     * @return bool
     */
    public function isComissao(): bool
    {
        return $this->comissao;
    }

    /**
     * @param bool $comissao
     */
    public function setComissao(bool $comissao): void
    {
        $this->comissao = $comissao;
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
    public function getDataRecebimento(): string
    {
        return $this->dataRecebimento;
    }

    /**
     * @param string $dataRecebimento
     */
    public function setDataRecebimento(string $dataRecebimento): void
    {
        $this->dataRecebimento = $dataRecebimento;
    }

    /**
     * @return float
     */
    public function getValorRecebido(): float
    {
        return $this->valorRecebido;
    }

    /**
     * @param float $valorRecebido
     */
    public function setValorRecebido(float $valorRecebido): void
    {
        $this->valorRecebido = $valorRecebido;
    }

    /**
     * @return ContaReceber|null
     */
    public function getPendencia(): ?ContaReceber
    {
        return $this->pendencia;
    }

    /**
     * @param ContaReceber|null $pendencia
     */
    public function setPendencia(?ContaReceber $pendencia): void
    {
        $this->pendencia = $pendencia;
    }

    /**
     * @return FormaPagamento|null
     */
    public function getFormaRecebimento(): ?FormaPagamento
    {
        return $this->formaRecebimento;
    }

    /**
     * @param FormaPagamento|null $formaRecebimento
     */
    public function setFormaRecebimento(?FormaPagamento $formaRecebimento): void
    {
        $this->formaRecebimento = $formaRecebimento;
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

    /**
     * @return int
     */
    public function findNewCount(): int
    {
        $sql = "
            SELECT MAX(con_rec_conta) AS CONTA
            FROM conta_receber;
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

    /**
     * @param array $row
     * @return ContaReceber
     */
    public function rowToObject(array $row) : ContaReceber
    {
        $conta = new ContaReceber();

        $conta->setId($row["con_rec_id"]);
        $conta->setConta($row["con_rec_conta"]);
        $conta->setData($row["con_rec_data"]);
        $conta->setDescricao($row["con_rec_descricao"]);
        $conta->setPagador($row["con_rec_pagador"]);
        $conta->setValor($row["con_rec_valor"]);
        $conta->setComissao($row["con_rec_comissao"]);
        $conta->setSituacao($row["con_rec_situacao"]);
        $conta->setVencimento($row["con_rec_vencimento"]);
        $conta->setDataRecebimento(($row["con_rec_data_recebimento"]) ? $row["con_rec_data_recebimento"] : "");
        $conta->setValorRecebido(($row["con_rec_valor_recebido"]) ? $row["con_rec_valor_recebido"] : 0.0);
        $conta->setPendencia(($row["con_rec_pendencia"]) ? (new ContaReceber())->findById($row["con_rec_pendencia"]) : null);
        $conta->setFormaRecebimento(($row["for_pag_id"]) ? FormaPagamento::findById($row["for_pag_id"]) : null);
        $conta->setRepresentacao(($row["rep_id"]) ? Representacao::getById($row["rep_id"]) : null);
        $conta->setPedidoVenda(($row["ped_ven_id"]) ? (new PedidoVenda())->findById($row["ped_ven_id"]) : null);
        $conta->setPedidoFrete(($row["ped_fre_id"]) ? (new PedidoFrete())->findById($row["ped_fre_id"]) : null);
        $conta->setAutor(Usuario::getById($row["usu_id"]));

        return $conta;
    }

    /**
     * @param mysqli_result $result
     * @return ContaReceber
     */
    public function resultToObject(mysqli_result $result) : ContaReceber
    {
        $row = $result->fetch_assoc();

        return $this->rowToObject($row);
    }

    /**
     * @param mysqli_result $result
     * @return array
     */
    public function resultToList(mysqli_result $result): array
    {
        $contas = [];
        while ($row = $result->fetch_assoc()) {
            $contas[] = $this->rowToObject($row);
        }

        return $contas;
    }

    /**
     * @param int $id
     * @return ?ContaReceber
     */
    public function findById(int $id): ?ContaReceber
    {
        if ($id <= 0)
            return null;

        $sql = "
            SELECT * 
            FROM conta_receber 
            WHERE con_rec_id = ?;
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
        if (!$result) {
            echo $stmt->error;
            return null;
        }

        return $this->resultToObject($result);
    }

    public function findByDescription(string $description, string $ordem): array
    {
        if ($description === null || strlen($description) <= 0)
            return [];

        $sql = "
            SELECT *
            FROM conta_receber
            WHERE con_rec_descricao like ? 
            ORDER BY " . $ordem . ";
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

        return $this->resultToList($result);
    }

    public function findByDescriptionComission(string $description, int $comission, string $ordem): array
    {
        if (
            strlen($description) <= 0 ||
            $comission <= 0
        )
            return [];

        $sql = "
            SELECT *
            FROM conta_receber
            WHERE con_rec_comissao = ? AND con_rec_descricao like ? 
            ORDER BY " . $ordem . ";
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return [];
        }

        $comissao = $comission === 1 ? true : false;

        $desc = "%".$description."%";

        $stmt->bind_param("is", $comissao,$desc);

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

        return $this->resultToList($result);
    }

    public function findByDescriptionComissionRepresentation(string $description, int $comission, int $representacao, string $ordem): array
    {
        if (
            strlen($description) <= 0 ||
            $comission <= 0 ||
            $representacao <= 0
        )
            return [];

        $sql = "
            SELECT *
            FROM conta_receber
            WHERE con_rec_comissao = ? AND rep_id = ? AND con_rec_descricao like ? 
            ORDER BY " . $ordem . ";
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return [];
        }

        $comissao = $comission === 1 ? true : false;

        $desc = "%".$description."%";

        $stmt->bind_param("iis", $comissao, $representacao, $desc);

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

        return $this->resultToList($result);
    }

    public function findByDescriptionSituation(string $description, int $situation, string $ordem): array
    {
        if ($description === null || strlen($description) <= 0 || $situation <= 0)
            return [];

        $sql = "
            SELECT *
            FROM conta_receber
            WHERE con_rec_descricao like ? 
            AND con_rec_situacao = ? 
            ORDER BY " . $ordem . ";
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return [];
        }

        $desc = "%".$description."%";
        $stmt->bind_param("si", $desc, $situation);
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

        return $this->resultToList($result);
    }

    public function findByDescriptionComissionSituation(string $description, int $comission, int $situation, string $ordem): array
    {
        if (
            strlen($description) <= 0 ||
            $comission <= 0 ||
            $situation <= 0
        )
            return [];

        $sql = "
            SELECT *
            FROM conta_receber
            WHERE con_rec_descricao like ? AND con_rec_comissao = ? AND con_rec_situacao = ?  
            ORDER BY " . $ordem . ";
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return [];
        }

        $comissao = $comission === 1 ? true : false;

        $desc = "%".$description."%";

        $stmt->bind_param("sii", $desc, $comissao, $situation);

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

        return $this->resultToList($result);
    }

    public function findByDescriptionComissionRepresentationSituation(string $description, int $comission, int $representacao, int $situation, string $ordem): array
    {
        if (
            strlen($description) <= 0 ||
            $comission <= 0 ||
            $representacao <= 0 ||
            $situation <= 0
        )
            return [];

        $sql = "
            SELECT *
            FROM conta_receber
            WHERE con_rec_descricao like ? AND con_rec_comissao = ? AND rep_id = ? AND con_rec_situacao = ?  
            ORDER BY " . $ordem . ";
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return [];
        }

        $comissao = $comission === 1 ? true : false;

        $desc = "%".$description."%";

        $stmt->bind_param("siii", $desc, $comissao, $representacao, $situation);

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

        return $this->resultToList($result);
    }

    public function findBySituation(int $situation, string $ordem): array
    {
        if ($situation <= 0)
            return [];

        $sql = "
            SELECT *
            FROM conta_receber 
            WHERE con_rec_situacao = ? 
            ORDER BY " . $ordem . ";
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return [];
        }

        $stmt->bind_param("i", $situation);
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

        return $this->resultToList($result);
    }

    public function findByComissionSituation(int $comission, int $situation, string $ordem): array
    {
        if ($comission <= 0 || $situation <= 0)
            return [];

        $sql = "
            SELECT *
            FROM conta_receber 
            WHERE con_rec_comissao = ? AND con_rec_situacao = ? 
            ORDER BY " . $ordem . ";
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return [];
        }

        $comissao = $comission === 1 ? true : false;

        $stmt->bind_param("ii", $comissao, $situation);

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

        return $this->resultToList($result);
    }

    public function findByComissionRepresentationSituation(int $comission, int $representation, int $situation, string $ordem): array
    {
        if (
            $comission <= 0 ||
            $representation <= 0 ||
            $situation <= 0
        )
            return [];

        $sql = "
            SELECT *
            FROM conta_receber 
            WHERE con_rec_comissao = ? AND rep_id = ? AND con_rec_situacao = ? 
            ORDER BY " . $ordem . ";
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return [];
        }

        $comissao = $comission === 1 ? true : false;

        $stmt->bind_param("iii", $comissao, $representation, $situation);

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

        return $this->resultToList($result);
    }

    public function findByDate(string $date, string $ordem): array
    {
        if ($date === null || strlen($date) <= 0) return [];

        $sql = "
            SELECT *
            FROM conta_receber
            WHERE con_rec_vencimento = ? 
            ORDER BY " . $ordem . ";
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

        return $this->resultToList($result);
    }

    public function findByDateComission(string $date, int $comission, string $ordem): array
    {
        if (
            $comission <= 0 ||
            strlen($date) <= 0
        )
            return [];

        $sql = "
            SELECT *
            FROM conta_receber
            WHERE con_rec_vencimento = ? AND con_rec_comissao = ?
            ORDER BY " . $ordem . ";
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return [];
        }

        $comissao = $comission === 1 ? true : false;

        $stmt->bind_param("si", $date, $comissao);

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

        return $this->resultToList($result);
    }

    public function findByDateComissionRepresentation(string $date, int $comission, int $representation, string $ordem): array
    {
        if ($comission <= 0 ||
            $representation <= 0 ||
            strlen($date) <= 0
        )
            return [];

        $sql = "
            SELECT *
            FROM conta_receber
            WHERE con_rec_vencimento = ? AND con_rec_comissao = ? AND rep_id = ? 
            ORDER BY " . $ordem . ";
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return [];
        }

        $comissao = $comission === 1 ? true : false;

        $stmt->bind_param("sii", $date, $comissao, $representation);

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

        return $this->resultToList($result);
    }

    public function findByDateSituation(string $date, int $situation, string $ordem): array
    {
        if ($date === null || strlen($date) <= 0 || $situation <= 0)
            return [];

        $sql = "
            SELECT *
            FROM conta_receber
            WHERE con_rec_vencimento = ? 
            AND con_rec_situacao = ? 
            ORDER BY " . $ordem . ";
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return [];
        }

        $stmt->bind_param("si", $date, $situation);
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

        return $this->resultToList($result);
    }

    public function findByDateComissionSituation(string $date, int $comission, int $situation, string $ordem): array
    {
        if (
            strlen($date) <= 0 ||
            $comission <= 0 ||
            $situation <= 0
        )
            return [];

        $sql = "
            SELECT *
            FROM conta_receber
            WHERE con_rec_vencimento = ? AND con_rec_comissao = ? AND con_rec_situacao = ? 
            ORDER BY " . $ordem . ";
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return [];
        }

        $comissao = $comission === 1 ? true : false;

        $stmt->bind_param("sii", $date, $comissao, $situation);

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

        return $this->resultToList($result);
    }

    public function findByDateComissionRepresentationSituation(string $date, int $comission, int $representation, int $situation, string $ordem): array
    {
        if (
            strlen($date) <= 0 ||
            $comission <= 0 ||
            $representation <= 0 ||
            $situation <= 0
        )
            return [];

        $sql = "
            SELECT *
            FROM conta_receber
            WHERE con_rec_vencimento = ? AND con_rec_comissao = ? AND rep_id = ? AND con_rec_situacao = ? 
            ORDER BY " . $ordem . ";
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return [];
        }

        $comissao = $comission === 1 ? true : false;

        $stmt->bind_param("siii", $date, $comissao, $representation, $situation);

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

        return $this->resultToList($result);
    }

    public function findByPeriod(string $date1, string $date2, string $ordem): array
    {
        if ($date1 === null || strlen($date1) <= 0 || $date2 === null || strlen($date2) <= 0)
            return [];

        $sql = "
            SELECT *
            FROM conta_receber
            WHERE con_rec_vencimento >= ?
            AND con_rec_vencimento <= ? 
            ORDER BY " . $ordem . ";
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

        return $this->resultToList($result);
    }

    public function findByPeriodComission(string $date1, string $date2, int $comission, string $ordem): array
    {
        if (
            strlen($date1) <= 0 ||
            $comission <= 0 ||
            strlen($date2) <= 0
        )
            return [];

        $sql = "
            SELECT *
            FROM conta_receber
            WHERE con_rec_vencimento >= ? AND con_rec_vencimento <= ? AND con_rec_comissao = ?
            ORDER BY " . $ordem . ";
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return [];
        }

        $comissao = $comission === 1 ? true : false;

        $stmt->bind_param("ssi", $date1, $date2, $comissao);

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

        return $this->resultToList($result);
    }

    public function findByPeriodComissionRepresentation(string $date1, string $date2, int $comission, int $representation, string $ordem): array
    {
        if (
            strlen($date1) <= 0 ||
            $comission <= 0 ||
            $representation <= 0 ||
            strlen($date2) <= 0
        )
            return [];

        $sql = "
            SELECT *
            FROM conta_receber
            WHERE con_rec_vencimento >= ? AND con_rec_vencimento <= ? AND con_rec_comissao = ? AND rep_id = ?
            ORDER BY " . $ordem . ";
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return [];
        }

        $comissao = $comission === 1 ? true : false;

        $stmt->bind_param("ssii", $date1, $date2, $comissao, $representation);

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

        return $this->resultToList($result);
    }

    public function findByPeriodSituation(string $date1, string $date2, int $situation, string $ordem): array
    {
        if ($date1 === null || strlen($date1) <= 0 || $date2 === null || strlen($date2) <= 0 || $situation <= 0)
            return [];

        $sql = "
            SELECT *
            FROM conta_receber
            WHERE con_rec_vencimento >= ?
            AND con_rec_vencimento <= ? 
            AND con_rec_situacao = ? 
            ORDER BY " . $ordem . ";
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return [];
        }

        $stmt->bind_param("ssi", $date1, $date2, $situation);
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

        return $this->resultToList($result);
    }

    public function findByPeriodComissionSituation(string $date1, string $date2, int $comission, int $situation, string $ordem): array
    {
        if (
            strlen($date1) <= 0 ||
            strlen($date2) <= 0 ||
            $comission <= 0 ||
            $situation <= 0
        )
            return [];

        $sql = "
            SELECT *
            FROM conta_receber
            WHERE con_rec_vencimento >= ? AND con_rec_vencimento <= ? AND con_rec_comissao = ? AND con_rec_situacao = ? 
            ORDER BY " . $ordem . ";
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return [];
        }

        $comissao = $comission === 1 ? true : false;

        $stmt->bind_param("ssii", $date1, $date2, $comissao, $situation);

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

        return $this->resultToList($result);
    }

    public function findByPeriodComissionRepresentationSituation(string $date1, string $date2, int $comission, int $representation, int $situation, string $ordem): array
    {
        if (
            strlen($date1) <= 0 ||
            strlen($date2) <= 0 ||
            $comission <= 0 ||
            $representation <= 0 ||
            $situation <= 0
        )
            return [];

        $sql = "
            SELECT *
            FROM conta_receber
            WHERE con_rec_vencimento >= ? AND con_rec_vencimento <= ? AND con_rec_comissao = ? AND rep_id = ? AND con_rec_situacao = ? 
            ORDER BY " . $ordem . ";
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return [];
        }

        $comissao = $comission === 1 ? true : false;

        $stmt->bind_param(
            "ssiii",
            $date1,
            $date2,
            $comissao,
            $representation,
            $situation
        );

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

        return $this->resultToList($result);
    }

    public function findByDescriptionDate(string $description, string $date, string $ordem): array
    {
        if ($description === null || strlen($description) <= 0 || $date === null || strlen($date) <= 0)
            return [];

        $sql = "
            SELECT *
            FROM conta_receber
            WHERE con_rec_descricao like ?
            AND con_rec_vencimento = ?
            ORDER BY " . $ordem . ";
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

        return $this->resultToList($result);
    }

    public function findByDescriptionDateComission(string $description, string $date, int $comission, string $ordem): array
    {
        if (
            strlen($description) <= 0 ||
            $comission <= 0 ||
            strlen($date) <= 0
        )
            return [];

        $sql = "
            SELECT *
            FROM conta_receber
            WHERE con_rec_descricao like ? AND con_rec_vencimento = ? AND con_rec_comissao = ?
            ORDER BY " . $ordem . ";
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return [];
        }

        $comissao = $comission === 1 ? true : false;

        $desc = "%".$description."%";

        $stmt->bind_param("ssi", $desc, $date, $comissao);

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

        return $this->resultToList($result);
    }

    public function findByDescriptionDateComissionRepresentation(string $description, string $date, int $comission, int $representation, string $ordem): array
    {
        if (
            strlen($description) <= 0 ||
            $comission <= 0 ||
            $representation <= 0 ||
            strlen($date) <= 0
        )
            return [];

        $sql = "
            SELECT *
            FROM conta_receber
            WHERE con_rec_descricao like ? AND con_rec_vencimento = ? AND con_rec_comissao = ? AND rep_id = ? 
            ORDER BY " . $ordem . ";
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return [];
        }

        $comissao = $comission === 1 ? true : false;

        $desc = "%".$description."%";

        $stmt->bind_param(
            "ssii",
            $desc,
            $date,
            $comissao,
            $representation
        );

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

        return $this->resultToList($result);
    }

    public function findByDescriptionDateSituation(string $description, string $date, int $situation, string $ordem): array
    {
        if (
            strlen($description) <= 0 ||
            strlen($date) <= 0 ||
            $situation <= 0
        )
            return [];

        $sql = "
            SELECT *
            FROM conta_receber
            WHERE con_rec_descricao like ?
            AND con_rec_vencimento = ? 
            AND con_rec_situacao = ? 
            ORDER BY " . $ordem . ";
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return [];
        }

        $desc = "%".$description."%";
        $stmt->bind_param("ssi", $desc, $date, $situation);
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

        return $this->resultToList($result);
    }

    public function findByDescriptionDateComissionSituation(string $description, string $date, int $comission, int $situation, string $ordem): array
    {
        if (
            strlen($description) <= 0 ||
            strlen($date) <= 0 ||
            $comission <= 0 ||
            $situation <= 0
        )
            return [];

        $sql = "
            SELECT *
            FROM conta_receber
            WHERE con_rec_descricao like ? AND con_rec_vencimento = ? AND con_rec_comissao = ? AND con_rec_situacao = ? 
            ORDER BY " . $ordem . ";
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return [];
        }

        $comissao = $comission === 1 ? true : false;

        $desc = "%".$description."%";

        $stmt->bind_param(
            "ssii",
            $desc,
            $date,
            $comissao,
            $situation
        );

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

        return $this->resultToList($result);
    }

    public function findByDescriptionDateComissionRepresentationSituation(string $description, string $date, int $comission, int $representation, int $situation, string $ordem): array
    {
        if (
            strlen($description) <= 0 ||
            strlen($date) <= 0 ||
            $comission <= 0 ||
            $representation <= 0 ||
            $situation <= 0
        )
            return [];

        $sql = "
            SELECT *
            FROM conta_receber
            WHERE con_rec_descricao like ? AND con_rec_vencimento = ? AND con_rec_comissao = ? AND rep_id = ? AND con_rec_situacao = ? 
            ORDER BY " . $ordem . ";
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return [];
        }

        $comissao = $comission === 1 ? true : false;

        $desc = "%".$description."%";

        $stmt->bind_param(
            "ssiii",
            $desc,
            $date,
            $comissao,
            $representation,
            $situation
        );

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

        return $this->resultToList($result);
    }

    public function findByDescriptionPeriod(string $description, string $date1, string $date2, string $ordem): array
    {
        if (
            $description === null || strlen($description) <= 0 ||
            $date1 === null || strlen($date1) <= 0 ||
            $date2 === null || strlen($date2) <= 0
        )
            return [];

        $sql = "
            SELECT *
            FROM conta_receber
            WHERE con_rec_descricao like ?
            AND con_rec_vencimento >= ?
            AND con_rec_vencimento <= ? 
            ORDER BY " . $ordem . ";
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

        return $this->resultToList($result);
    }

    public function findByDescriptionPeriodComission(string $description, string $date1, string $date2, int $comission, string $ordem): array
    {
        if (
            $description === null || strlen($description) <= 0 ||
            $date1 === null || strlen($date1) <= 0 ||
            $date2 === null || strlen($date2) <= 0 ||
            $comission <= 0
        )
            return [];

        $sql = "
            SELECT *
            FROM conta_receber
            WHERE con_rec_descricao like ? AND con_rec_vencimento >= ? AND con_rec_vencimento <= ? AND con_rec_comissao = ?
            ORDER BY " . $ordem . ";
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return [];
        }

        $comissao = $comission === 1 ? true : false;

        $desc = "%".$description."%";

        $stmt->bind_param(
            "sssi",
            $desc,
            $date1,
            $date2,
            $comissao
        );

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

        return $this->resultToList($result);
    }

    public function findByDescriptionPeriodComissionRepresentation(string $description, string $date1, string $date2, int $comission, int $representation, string $ordem): array
    {
        if (
            $description === null || strlen($description) <= 0 ||
            $date1 === null || strlen($date1) <= 0 ||
            $date2 === null || strlen($date2) <= 0 ||
            $comission <= 0 || $representation <= 0
        )
            return [];

        $sql = "
            SELECT *
            FROM conta_receber
            WHERE con_rec_descricao like ? AND con_rec_vencimento >= ? AND con_rec_vencimento <= ? AND con_rec_comissao = ? AND rep_id = ? 
            ORDER BY " . $ordem . ";
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return [];
        }

        $comissao = $comission === 1 ? true : false;

        $desc = "%".$description."%";

        $stmt->bind_param(
            "sssii",
            $desc,
            $date1,
            $date2,
            $comissao,
            $representation
        );

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

        return $this->resultToList($result);
    }

    public function findByDescriptionPeriodSituation(string $description, string $date1, string $date2, int $situation, string $ordem): array
    {
        if (
            $description === null || strlen($description) <= 0 ||
            $date1 === null || strlen($date1) <= 0 ||
            $date2 === null || strlen($date2) <= 0 || $situation <= 0
        )
            return [];

        $sql = "
            SELECT *
            FROM conta_receber
            WHERE con_rec_descricao like ?
            AND con_rec_vencimento >= ?
            AND con_rec_vencimento <= ? 
            AND con_rec_situacao = ? 
            ORDER BY " . $ordem . ";
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return [];
        }

        $desc = "%".$description."%";
        $stmt->bind_param("sssi", $desc, $date1, $date2, $situation);
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

        return $this->resultToList($result);
    }

    public function findByDescriptionPeriodComissionSituation(string $description, string $date1, string $date2, int $comission, int $situation, string $ordem): array
    {
        if (
            $description === null || strlen($description) <= 0 ||
            $date1 === null || strlen($date1) <= 0 ||
            $date2 === null || strlen($date2) <= 0 ||
            $situation <= 0 || $comission <= 0
        )
            return [];

        $sql = "
            SELECT *
            FROM conta_receber
            WHERE con_rec_descricao like ? AND con_rec_vencimento >= ? AND con_rec_vencimento <= ? AND con_rec_comissao = ? AND con_rec_situacao = ? 
            ORDER BY " . $ordem . ";
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return [];
        }

        $comissao = $comission === 1 ? true : false;

        $desc = "%".$description."%";

        $stmt->bind_param(
            "sssii",
            $desc,
            $date1,
            $date2,
            $comissao,
            $situation
        );

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

        return $this->resultToList($result);
    }

    public function findByDescriptionPeriodComissionRepresentationSituation(string $description, string $date1, string $date2, int $comission, int $representation, int $situation, string $ordem): array
    {
        if (
            $description === null || strlen($description) <= 0 ||
            $date1 === null || strlen($date1) <= 0 ||
            $date2 === null || strlen($date2) <= 0 ||
            $situation <= 0 || $comission <= 0 || $representation <= 0
        )
            return [];

        $sql = "
            SELECT *
            FROM conta_receber
            WHERE con_rec_descricao like ? AND con_rec_vencimento >= ? AND con_rec_vencimento <= ? AND con_rec_comissao = ? AND rep_id = ? AND con_rec_situacao = ? 
            ORDER BY " . $ordem . ";
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return [];
        }

        $comissao = $comission === 1 ? true : false;

        $desc = "%".$description."%";

        $stmt->bind_param(
            "sssiii",
            $desc,
            $date1,
            $date2,
            $comissao,
            $representation,
            $situation
        );

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

        return $this->resultToList($result);
    }

    public function findByComission(int $comission, string $ordem): array
    {
        if ($comission <= 0)
            return [];

        $sql = "
            SELECT *
            FROM conta_receber 
            WHERE con_rec_comissao = ? 
            ORDER BY " . $ordem . ";
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return [];
        }

        $comissao = $comission === 1 ? true : false;

        $stmt->bind_param("i", $comissao);
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

        return $this->resultToList($result);
    }

    public function findByComissionRepresentation(int $comission, int $representation, string $ordem): array
    {
        if ($comission <= 0 || $representation <= 0)
            return [];

        $sql = "
            SELECT *
            FROM conta_receber 
            WHERE con_rec_comissao = ? AND rep_id = ? 
            ORDER BY " . $ordem . ";
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return [];
        }

        $comissao = $comission === 1 ? true : false;

        $stmt->bind_param("ii", $comissao, $representation);
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

        return $this->resultToList($result);
    }

    /**
     * @param int $delivery
     * @return ContaReceber|null
     */
    public function findByDelivery(int $delivery): ?ContaReceber
    {
        if ($delivery <= 0)
            return null;

        $sql = "
            SELECT * 
            FROM conta_receber 
            WHERE ped_fre_id = ?;
        ";

        if (!Banco::getInstance()->prepareStatement($sql))
            return null;

        if (!Banco::getInstance()->addParameters("i", [ $delivery ]))
            return null;

        if (!Banco::getInstance()->executeStatement())
            return null;

        return $this->resultToObject(Banco::getInstance()->getResult());
    }

    /**
     * @param int $sale
     * @return array
     */
    public function findComissionsBySale(int $sale): array
    {
        if ($sale <= 0)
            return [];

        $sql = "
            SELECT * 
            FROM conta_receber 
            WHERE con_rec_comissao = TRUE AND rep_id IS NOT NULL AND ped_ven_id = ?;
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return [];
        }

        $stmt->bind_param("i", $sale);
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
     * @param int $sale
     * @return ContaReceber|null
     */
    public function findReceiveBySale(int $sale): ?ContaReceber
    {
        if ($sale <= 0)
            return null;

        $sql = "
            SELECT * 
            FROM conta_receber 
            WHERE con_rec_comissao = FALSE AND ped_ven_id = ?;
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return null;
        }

        $stmt->bind_param("i", $sale);
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
     * @return array
     */
    public function findAll(string $ordem = "con_rec_conta"): array
    {
        $sql = "
            SELECT * 
            FROM conta_receber 
            ORDER BY ". $ordem .";
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
        if (!$result) {
            echo $stmt->error;
            return [];
        }

        return $this->resultToList($result);
    }

    /**
     * @return int
     */
    public function save(): int
    {
        if (
            $this->id != 0 ||
            $this->conta <= 0 ||
            strlen($this->data) === 0 ||
            strlen(trim($this->descricao)) === 0 ||
            strlen(trim($this->pagador)) === 0 ||
            $this->valor <= 0 ||
            $this->situacao <= 0 ||
            strlen($this->vencimento) === 0 ||
            $this->autor === null
        )
            return -5;

        $sql = "
            INSERT 
            INTO conta_receber (
                con_rec_conta,
                con_rec_data,
                con_rec_descricao,
                con_rec_pagador,
                con_rec_valor,
                con_rec_comissao,                
                con_rec_situacao,
                con_rec_vencimento,
                rep_id,
                ped_ven_id,
                ped_fre_id,
                usu_id
            )
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?);
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }

        $representacao = ($this->representacao) ? $this->representacao->getId() : null;
        $pedidoVenda = ($this->pedidoVenda) ? $this->pedidoVenda->getId() : null;
        $pedidoFrete = ($this->pedidoFrete) ? $this->pedidoFrete->getId() : null;
        $autor = $this->autor->getId();

        $stmt->bind_param(
            "isssdiisiiii",
            $this->conta,
            $this->data,
            $this->descricao,
            $this->pagador,
            $this->valor,
            $this->comissao,
            $this->situacao,
            $this->vencimento,
            $representacao,
            $pedidoVenda,
            $pedidoFrete,
            $autor
        );

        if (!$stmt->execute()) {
            echo $stmt->error;
            return -10;
        }

        return $stmt->insert_id;
    }

    /**
     * @param int $forma
     * @param float $valor
     * @param string $data
     * @param int $situacao
     * @param int $pendencia
     * @return int
     */
    public function receber(int $forma, float $valor, string $data, int $situacao, int $pendencia): int
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
            UPDATE conta_receber
            SET con_rec_valor_recebido = ?,
            con_rec_data_recebimento = ?,
            con_rec_situacao = ?,
            con_rec_pendencia = ?, 
            for_pag_id = ?
            WHERE con_rec_id = ?;
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

    /**
     * @return int
     */
    public function estornar(): int
    {
        if ($this->id <= 0)
            return -5;

        $sql = "
            UPDATE conta_receber
            SET con_rec_valor_recebido = null,
            con_rec_data_recebimento = null,
            con_rec_situacao = 1,
            con_rec_pendencia = null, 
            for_pag_id = null
            WHERE con_rec_id = ?;
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
            FROM conta_receber
            WHERE con_rec_id = ?;
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

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        $pendencia = ($this->pendencia) ? $this->pendencia->jsonSerialize() : null;
        $formaRecebimento = ($this->formaRecebimento) ? $this->formaRecebimento->jsonSerialize() : null;
        $representacao = ($this->representacao) ? $this->representacao->jsonSerialize() : null;
        $pedidoVenda = ($this->pedidoVenda) ? $this->pedidoVenda->jsonSerialize() : null;
        $pedidoFrete = ($this->pedidoFrete) ? $this->pedidoFrete->jsonSerialize() : null;
        $autor = $this->autor->jsonSerialize();

        return [
            "id" => $this->id,
            "conta" => $this->conta,
            "data" => $this->data,
            "descricao" => $this->descricao,
            "pagador" => $this->pagador,
            "valor" => $this->valor,
            "comissao" => $this->comissao,
            "situacao" => $this->situacao,
            "vencimento" => $this->vencimento,
            "dataRecebimento" => $this->dataRecebimento,
            "valorRecebido" => $this->valorRecebido,
            "pendencia" => $pendencia,
            "formaRecebimento" => $formaRecebimento,
            "representacao" => $representacao,
            "pedidoVenda" => $pedidoVenda,
            "pedidoFrete" => $pedidoFrete,
            "autor" => $autor
        ];
    }
}