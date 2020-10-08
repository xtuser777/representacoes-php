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

    /** @var Categoria */
    private $categoria;

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
     * @param int $situacao
     * @param string $vencimento
     * @param string $dataRecebimento
     * @param float $valorRecebido
     * @param ContaReceber|null $pendencia
     * @param FormaPagamento|null $formaRecebimento
     * @param Categoria|null $categoria
     * @param Representacao|null $representacao
     * @param PedidoVenda|null $pedidoVenda
     * @param PedidoFrete|null $pedidoFrete
     * @param Usuario|null $autor
     */
    public function __construct(int $id = 0, string $data = "", int $conta = 0, string $descricao = "", string $pagador = "", float $valor = 0.0, int $situacao = 0, string $vencimento = "", string $dataRecebimento = "", float $valorRecebido = 0.0, ?ContaReceber $pendencia = null, ?FormaPagamento $formaRecebimento = null, Categoria $categoria = null, ?Representacao $representacao = null, ?PedidoVenda $pedidoVenda = null, ?PedidoFrete $pedidoFrete = null, Usuario $autor = null)
    {
        $this->id = $id;
        $this->data = $data;
        $this->conta = $conta;
        $this->descricao = $descricao;
        $this->pagador = $pagador;
        $this->valor = $valor;
        $this->situacao = $situacao;
        $this->vencimento = $vencimento;
        $this->dataRecebimento = $dataRecebimento;
        $this->valorRecebido = $valorRecebido;
        $this->pendencia = $pendencia;
        $this->formaRecebimento = $formaRecebimento;
        $this->categoria = $categoria;
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
     * @return Categoria
     */
    public function getCategoria(): Categoria
    {
        return $this->categoria;
    }

    /**
     * @param Categoria $categoria
     */
    public function setCategoria(Categoria $categoria): void
    {
        $this->categoria = $categoria;
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
     * @param mysqli_result $result
     * @return ContaReceber
     */
    public function resultToObject(mysqli_result $result) : ContaReceber
    {
        $row = $result->fetch_row();

        $conta = new ContaReceber();
        $conta->setId($row["con_rec_id"]);
        $conta->setConta($row["con_rec_conta"]);
        $conta->setData($row["con_rec_data"]);
        $conta->setDescricao($row["con_rec_descricao"]);
        $conta->setPagador($row["con_rec_pagador"]);
        $conta->setValor($row["con_rec_valor"]);
        $conta->setSituacao($row["con_rec_situacao"]);
        $conta->setVencimento($row["con_rec_vencimento"]);
        $conta->setDataRecebimento(($row["con_rec_data_recebimento"]) ? $row["con_rec_data_recebimento"] : "");
        $conta->setValorRecebido(($row["con_rec_valor_recebido"]) ? $row["con_rec_valor_recebido"] : "");
        $conta->setPendencia(($row["con_rec_pendencia"]) ? (new ContaReceber())->findById($row["con_rec_pendencia"]) : null);
        $conta->setFormaRecebimento(($row["for_pag_id"]) ? FormaPagamento::findById($row["for_pag_id"]) : null);
        $conta->setCategoria(Categoria::findById($row["cat_id"]));
        $conta->setRepresentacao(($row["rep_id"]) ? Representacao::getById($row["rep_id"]) : null);
        $conta->setPedidoVenda(($row["ped_ven_id"]) ? (new PedidoVenda())->findById($row["ped_ven_id"]) : null);
        $conta->setPedidoFrete(($row["ped_fre_id"]) ? (new PedidoFrete())->findById($row["ped_fre_id"]) : null);
        $conta->setAutor(Usuario::getById($row["usu_id"]));

        return $conta;
    }

    /**
     * @param mysqli_result $result
     * @return array
     */
    public function resultToList(mysqli_result $result): array
    {
        $contas = [];
        while ($row = $result->fetch_row()) {
            $conta = new ContaReceber();
            $conta->setId($row["con_rec_id"]);
            $conta->setConta($row["con_rec_conta"]);
            $conta->setData($row["con_rec_data"]);
            $conta->setDescricao($row["con_rec_descricao"]);
            $conta->setPagador($row["con_rec_pagador"]);
            $conta->setValor($row["con_rec_valor"]);
            $conta->setSituacao($row["con_rec_situacao"]);
            $conta->setVencimento($row["con_rec_vencimento"]);
            $conta->setDataRecebimento(($row["con_rec_data_recebimento"]) ? $row["con_rec_data_recebimento"] : "");
            $conta->setValorRecebido(($row["con_rec_valor_recebido"]) ? $row["con_rec_valor_recebido"] : "");
            $conta->setPendencia(($row["con_rec_pendencia"]) ? (new ContaReceber())->findById($row["con_rec_pendencia"]) : null);
            $conta->setFormaRecebimento(($row["for_pag_id"]) ? FormaPagamento::findById($row["for_pag_id"]) : null);
            $conta->setCategoria(Categoria::findById($row["cat_id"]));
            $conta->setRepresentacao(($row["rep_id"]) ? Representacao::getById($row["rep_id"]) : null);
            $conta->setPedidoVenda(($row["ped_ven_id"]) ? (new PedidoVenda())->findById($row["ped_ven_id"]) : null);
            $conta->setPedidoFrete(($row["ped_fre_id"]) ? (new PedidoFrete())->findById($row["ped_fre_id"]) : null);
            $conta->setAutor(Usuario::getById($row["usu_id"]));

            $contas[] = $conta;
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
            WHERE con_rec_id = ?
            ORDER BY con_rec_conta;
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

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        $pendencia = ($this->pendencia) ? $this->pendencia->jsonSerialize() : null;
        $formaRecebimento = ($this->formaRecebimento) ? $this->formaRecebimento->jsonSerialize() : null;
        $categoria = $this->categoria->jsonSerialize();
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
            "situacao" => $this->situacao,
            "vencimento" => $this->vencimento,
            "dataRecebimento" => $this->dataRecebimento,
            "valorRecebido" => $this->valorRecebido,
            "pendencia" => $pendencia,
            "formaRecebimento" => $formaRecebimento,
            "categoria" => $categoria,
            "representacao" => $representacao,
            "pedidoVenda" => $pedidoVenda,
            "pedidoFrete" => $pedidoFrete,
            "autor" => $autor
        ];
    }
}