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

    /** @var array */
    private $itens;

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

    /**
     * @param mysqli_result $result
     * @return PedidoVenda
     */
    public function resultToObject(mysqli_result $result): PedidoVenda
    {
        $row = $result->fetch_row();

        $pedido = new PedidoVenda();
        $pedido->setId($row["ped_ven_id"]);
        $pedido->setData($row["ped_ven_data"]);
        $pedido->setDescricao($row["ped_ven_descricao"]);
        $pedido->setPeso($row["ped_ven_peso"]);
        $pedido->setValor($row["ped_ven_valor"]);
        $pedido->setVendedor(($row["fun_id"] !== null) ? Funcionario::getById($row["fun_id"]) : null);
        $pedido->setDestino((new Cidade())->getById($row["cid_id"]));
        $pedido->setOrcamento(($row["orc_ven_id"] !== null) ? OrcamentoVenda::findById($row["orc_ven_id"]) : null);
        $pedido->setTipoCaminhao(TipoCaminhao::findById($row["tip_cam_id"]));
        $pedido->setCliente(Cliente::getById($row["cli_id"]));
        $pedido->setFormaPagamento(FormaPagamento::findById($row["for_pag_id"]));
        $pedido->setAutor(Usuario::getById($row["usu_id"]));
        $pedido->setItens((new ItemPedidoVenda())->findByPrice($pedido->getId()));

        return $pedido;
    }

    /**
     * @param mysqli_result $result
     * @return array
     */
    public function resultToList(mysqli_result $result): array
    {
        $pedidos = [];

        while ($row = $result->fetch_row()) {
            $pedido = new PedidoVenda();
            $pedido->setId($row["ped_ven_id"]);
            $pedido->setData($row["ped_ven_data"]);
            $pedido->setDescricao($row["ped_ven_descricao"]);
            $pedido->setPeso($row["ped_ven_peso"]);
            $pedido->setValor($row["ped_ven_valor"]);
            $pedido->setVendedor(($row["fun_id"] !== null) ? Funcionario::getById($row["fun_id"]) : null);
            $pedido->setDestino((new Cidade())->getById($row["cid_id"]));
            $pedido->setOrcamento(($row["orc_ven_id"] !== null) ? OrcamentoVenda::findById($row["orc_ven_id"]) : null);
            $pedido->setTipoCaminhao(TipoCaminhao::findById($row["tip_cam_id"]));
            $pedido->setCliente(Cliente::getById($row["cli_id"]));
            $pedido->setFormaPagamento(FormaPagamento::findById($row["for_pag_id"]));
            $pedido->setAutor(Usuario::getById($row["usu_id"]));
            $pedido->setItens((new ItemPedidoVenda())->findByPrice($pedido->getId()));

            $pedidos[] = $pedido;
        }

        return $pedidos;
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

        return $this->resultToObject($result);
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

        return $this->resultToObject($result);
    }

    public function findByFilter(string $filter, string $ordem): array
    {
      if (strlen(trim($filter)) === 0)
          return [];

      $sql = "
          SELECT pv.ped_ven_id, pv.ped_ven_data, pv.ped_ven_descricao, pv.ped_ven_peso, pv.ped_ven_valor,
                 pv.fun_id, pv.cid_id, pv.orc_ven_id, pv.tip_cam_id, pv.cli_id, pv.for_pag_id, pv.usu_id
          FROM pedido_venda pv
          INNER JOIN cliente cli ON cli.cli_id = pv.cli_id
          left join cliente_pessoa_fisica cpf on cli.cli_id = cpf.cli_id
          left join cliente_pessoa_juridica cpj on cli.cli_id = cpj.cli_id
          left join pessoa_fisica pf on cpf.pf_id = pf.pf_id
          left join pessoa_juridica pj on cpj.pj_id = pj.pj_id
          INNER JOIN usuario autor ON autor.usu_id = pv.usu_id
          INNER JOIN funcionario autor_fun ON autor_fun.fun_id = autor.fun_id
          INNER JOIN pessoa_fisica autor_pf ON autor_pf.pf_id = autor_fun.pf_id
          INNER JOIN forma_pagamento fp ON fp.for_pag_id = pv.for_pag_id
          WHERE ped_ven_descricao LIKE ?
          OR pf.pf_nome LIKE ?
          OR pj.pj_nome_fantasia LIKE ?
          ORDER BY " . $ordem . ";
      ";

      /** @var $stmt mysqli_stmt */
      $stmt = Banco::getInstance()->getConnection()->prepare($sql);
      if (!$stmt) {
          echo Banco::getInstance()->getConnection()->error;
          return [];
      }

      $filtro = "%" . $filter . "%";
      $stmt->bind_param("sss", $filtro, $filtro, $filtro);
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
      if (strlen(trim($date)) === 0)
          return [];

      $sql = "
          SELECT ped_ven_id, ped_ven_data, ped_ven_descricao, ped_ven_peso, ped_ven_valor,
                 pv.fun_id, cid_id, orc_ven_id, tip_cam_id, cli_id, pv.for_pag_id, pv.usu_id
          FROM pedido_venda pv
          INNER JOIN usuario autor ON autor.usu_id = pv.usu_id
          INNER JOIN funcionario autor_fun ON autor_fun.fun_id = autor.fun_id
          INNER JOIN pessoa_fisica autor_pf ON autor_pf.pf_id = autor_fun.pf_id
          INNER JOIN forma_pagamento fp ON fp.for_pag_id = pv.for_pag_id
          WHERE ped_ven_data = ?
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

    public function findByFilterDate(string $filter, string $date, string $ordem): array
    {
      if (strlen(trim($filter)) === 0 || strlen(trim($date)) === 0)
          return [];

      $sql = "
          SELECT pv.ped_ven_id, pv.ped_ven_data, pv.ped_ven_descricao, pv.ped_ven_peso, pv.ped_ven_valor,
                 pv.fun_id, pv.cid_id, pv.orc_ven_id, pv.tip_cam_id, pv.cli_id, pv.for_pag_id, pv.usu_id
          FROM pedido_venda pv
          INNER JOIN cliente cli ON cli.cli_id = pv.cli_id
          left join cliente_pessoa_fisica cpf on cli.cli_id = cpf.cli_id
          left join cliente_pessoa_juridica cpj on cli.cli_id = cpj.cli_id
          left join pessoa_fisica pf on cpf.pf_id = pf.pf_id
          left join pessoa_juridica pj on cpj.pj_id = pj.pj_id
          INNER JOIN usuario autor ON autor.usu_id = pv.usu_id
          INNER JOIN funcionario autor_fun ON autor_fun.fun_id = autor.fun_id
          INNER JOIN pessoa_fisica autor_pf ON autor_pf.pf_id = autor_fun.pf_id
          INNER JOIN forma_pagamento fp ON fp.for_pag_id = pv.for_pag_id
          WHERE ped_ven_data = ?
          AND (ped_ven_descricao LIKE ?
          OR pf.pf_nome LIKE ?
          OR pj.pj_nome_fantasia LIKE ?)
          ORDER BY " . $ordem . ";
      ";

      /** @var $stmt mysqli_stmt */
      $stmt = Banco::getInstance()->getConnection()->prepare($sql);
      if (!$stmt) {
          echo Banco::getInstance()->getConnection()->error;
          return [];
      }

      $filtro = "%" . $filter . "%";
      $stmt->bind_param("ssss", $date, $filtro, $filtro, $filtro);
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
     * @return array
     */
    public function findAll(): array
    {
        $sql = "
            SELECT *
            FROM pedido_venda 
            ORDER BY ped_ven_descricao;
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

        return $this->resultToList($result);
    }

    public function save(): int
    {
      if (
        $this->id != 0 ||
        strlen(trim($this->data)) === 0 ||
        strlen(trim($this->descricao)) === 0 ||
        $this->peso <= 0 ||
        $this->valor <= 0 ||
        $this->destino === null ||
        $this->tipoCaminhao === null ||
        $this->cliente === null ||
        $this->formaPagamento === null ||
        $this->autor === null
      )
        return -5;

        $sql = "
          INSERT
          INTO pedido_venda(
            ped_ven_data,
            ped_ven_descricao,
            ped_ven_peso,
            ped_ven_valor,
            fun_id,
            cid_id,
            orc_ven_id,
            tip_cam_id,
            cli_id,
            for_pag_id,
            usu_id
            )
          VALUES (?,?,?,?,?,?,?,?,?,?,?);
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
          echo Banco::getInstance()->getConnection()->error;
          return -10;
        }

        $vdd = ($this->vendedor) ? $this->vendedor->getId() : null;
        $dest = $this->destino->getId();
        $orc = ($this->orcamento) ? $this->orcamento->getId() : null;
        $tip = $this->tipoCaminhao->getId();
        $cli = $this->cliente->getId();
        $fp = $this->formaPagamento->getId();
        $autor = $this->autor->getId();

        $stmt->bind_param(
          "ssddiiiiiii",
          $this->data,
          $this->descricao,
          $this->peso,
          $this->valor,
          $vdd,
          $dest,
          $orc,
          $tip,
          $cli,
          $fp,
          $autor
        );

        if (!$stmt->execute()) {
          echo $stmt->error;
          return -10;
        }

        return $stmt->insert_id;
    }

    public function update(): int
    {
      if (
        $this->id <= 0 ||
        strlen(trim($this->data)) === 0 ||
        strlen(trim($this->descricao)) === 0 ||
        $this->peso <= 0 ||
        $this->valor <= 0 ||
        $this->destino === null ||
        $this->tipoCaminhao === null ||
        $this->cliente === null ||
        $this->formaPagamento === null ||
        $this->autor === null
        )
        return -5;

        $sql = "
          UPDATE pedido_venda
          SET  ped_ven_data = ?,
               ped_ven_descricao = ?,
               ped_ven_peso = ?,
               ped_ven_valor = ?,
               fun_id = ?,
               cid_id = ?,
               orc_ven_id = ?,
               tip_cam_id = ?,
               cli_id = ?,
               for_pag_id = ?,
               usu_id = ?
          WHERE ped_ven_id = ?;
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
          echo Banco::getInstance()->getConnection()->error;
          return -10;
        }

        $vdd = ($this->vendedor) ? $this->vendedor->getId() : null;
        $dest = $this->destino->getId();
        $orc = ($this->orcamento) ? $this->orcamento->getId() : null;
        $tip = $this->tipoCaminhao->getId();
        $cli = $this->cliente->getId();
        $fp = $this->formaPagamento->getId();
        $autor = $this->autor->getId();

        $stmt->bind_param(
          "ssddiiiiiiii",
          $this->data,
          $this->descricao,
          $this->peso,
          $this->valor,
          $vdd,
          $dest,
          $orc,
          $tip,
          $cli,
          $fp,
          $autor,
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
          FROM pedido_venda
          WHERE ped_ven_id = ?;
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

    public function jsonSerialize(): array
    {
        $vendedor = ($this->vendedor !== null) ? $this->vendedor->jsonSerialize() : null;
        $destino = $this->destino->jsonSerialize();
        $orcamento = ($this->orcamento !== null) ? $this->orcamento->jsonSerialize() : null;
        $tipoCaminhao = $this->tipoCaminhao->jsonSerialize();
        $cliente = $this->cliente->jsonSerialize();
        $formaPagamento = $this->formaPagamento->jsonSerialize();
        $autor = $this->autor->jsonSerialize();

        $itens = [];
        for ($i = 0; $i < count($this->itens); $i++) {
            $itens[] = $this->itens[$i]->jsonSerialize();
        }

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
            "autor" => $autor,
            "itens" => $itens
        ];
    }
}
