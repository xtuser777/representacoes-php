<?php namespace scr\model;

use mysqli_result;
use mysqli_stmt;
use scr\util\Banco;

class OrcamentoVenda
{
    private $id;
    private $descricao;
    private $data;
    private $nomeCliente;
    private $documentoCliente;
    private $telefoneCliente;
    private $celularCliente;
    private $emailCliente;
    private $peso;
    private $valor;
    private $validade;
    private $vendedor;
    private $cliente;
    private $destino;
    private $autor;

    public function __construct(int $id, string $descricao, string $data, string $nomeCliente, string $documentoCliente, string $telefoneCliente, string $celularCliente, string $emailCliente, float $peso, float $valor, string $validade, ?Funcionario $vendedor, ?Cliente $cliente, Cidade $destino, Usuario $autor)
    {
        $this->id = $id;
        $this->descricao = $descricao;
        $this->data = $data;
        $this->nomeCliente = $nomeCliente;
        $this->documentoCliente = $documentoCliente;
        $this->telefoneCliente = $telefoneCliente;
        $this->celularCliente = $celularCliente;
        $this->emailCliente = $emailCliente;
        $this->peso = $peso;
        $this->valor = $valor;
        $this->validade = $validade;
        $this->vendedor = $vendedor;
        $this->cliente = $cliente;
        $this->destino = $destino;
        $this->autor = $autor;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getDescricao(): string
    {
        return $this->descricao;
    }

    public function getData(): string
    {
        return $this->data;
    }

    public function getNomeCliente(): string
    {
        return $this->nomeCliente;
    }

    public function getDocumentoCliente(): string
    {
        return $this->documentoCliente;
    }

    public function getTelefoneCliente(): string
    {
        return $this->telefoneCliente;
    }

    public function getCelularCliente(): string
    {
        return $this->celularCliente;
    }

    public function getEmailCliente(): string
    {
        return $this->emailCliente;
    }

    public function getPeso(): float
    {
        return $this->peso;
    }

    public function getValor(): float
    {
        return $this->valor;
    }

    public function getValidade(): string
    {
        return $this->validade;
    }

    public function getVendedor(): ?Funcionario
    {
        return $this->vendedor;
    }

    public function getCliente(): ?Cliente
    {
        return $this->cliente;
    }

    public function getDestino(): Cidade
    {
        return $this->destino;
    }

    public function getAutor(): Usuario
    {
        return $this->autor;
    }

    public static function findById(int $id): ?OrcamentoVenda
    {
        if ($id <= 0) return null;
        $sql = "
            select orc_ven_id,orc_ven_descricao,orc_ven_data,
                   orc_ven_nome_cliente,orc_ven_documento_cliente,orc_ven_telefone_cliente,orc_ven_celular_cliente,orc_ven_email_cliente,
                   orc_ven_peso,orc_ven_valor,orc_ven_validade,
                   fun_id,cli_id,cid_id,usu_id
            from orcamento_venda
            where orc_ven_id = ?;
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

        return new OrcamentoVenda(
            $row["orc_ven_id"],$row["orc_ven_descricao"],$row["orc_ven_data"],$row["orc_ven_nome_cliente"],$row["orc_ven_documento_cliente"],$row["orc_ven_telefone_cliente"],$row["orc_ven_celular_cliente"],$row["orc_ven_email_cliente"],$row["orc_ven_peso"],$row["orc_ven_valor"],$row["orc_ven_validade"],
            Funcionario::getById($row["fun_id"]),
            Cliente::getById($row["cli_id"]),
            Cidade::getById($row["cid_id"]),
            Usuario::getById($row["usu_id"])
        );
    }

    public static function findByKey(string $key): array
    {
        if (strlen(trim($key)) === 0) return array();

        $sql = "
            select orc_ven_id,orc_ven_descricao,orc_ven_data,
                   orc_ven_nome_cliente,orc_ven_documento_cliente,orc_ven_telefone_cliente,orc_ven_celular_cliente,orc_ven_email_cliente,
                   orc_ven_peso,orc_ven_valor,orc_ven_validade,
                   fun_id,cli_id,cid_id,usu_id
            from orcamento_venda ov
            where ov.orc_ven_descricao like ?
            or ov.orc_ven_nome_cliente like ?
            order by ov.orc_ven_id;
        ";
        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return array();
        }
        $filter = "%".$key."%";
        $stmt->bind_param("sss", $filter, $filter, $filter);
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
        $orcamentos = [];
        while ($row = $result->fetch_assoc()) {
            $orcamentos[] = new OrcamentoVenda(
                $row["orc_ven_id"],$row["orc_ven_descricao"],$row["orc_ven_data"],$row["orc_ven_nome_cliente"],$row["orc_ven_documento_cliente"],$row["orc_ven_telefone_cliente"],$row["orc_ven_celular_cliente"],$row["orc_ven_email_cliente"],$row["orc_ven_peso"],$row["orc_ven_valor"],$row["orc_ven_validade"],
                Funcionario::getById($row["fun_id"]),
                Cliente::getById($row["cli_id"]),
                Cidade::getById($row["cid_id"]),
                Usuario::getById($row["usu_id"])
            );
        }

        return $orcamentos;
    }

    public static function findByDate(string $date): array
    {
        if (strlen(trim($date)) === 0) return array();
        $sql = "
            select orc_ven_id,orc_ven_descricao,orc_ven_data,
                   orc_ven_nome_cliente,orc_ven_documento_cliente,orc_ven_telefone_cliente,orc_ven_celular_cliente,orc_ven_email_cliente,
                   orc_ven_peso,orc_ven_valor,orc_ven_validade,
                   fun_id,cli_id,cid_id,usu_id
            from orcamento_venda ov
            where ov.orc_ven_data = ?
            order by ov.orc_ven_id;
        ";
        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return array();
        }
        $stmt->bind_param("s", $date);
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
        $orcamentos = [];
        while ($row = $result->fetch_assoc()) {
            $orcamentos[] = new OrcamentoVenda(
                $row["orc_ven_id"],$row["orc_ven_descricao"],$row["orc_ven_data"],$row["orc_ven_nome_cliente"],$row["orc_ven_documento_cliente"],$row["orc_ven_telefone_cliente"],$row["orc_ven_celular_cliente"],$row["orc_ven_email_cliente"],$row["orc_ven_peso"],$row["orc_ven_valor"],$row["orc_ven_validade"],
                Funcionario::getById($row["fun_id"]),
                Cliente::getById($row["cli_id"]),
                Cidade::getById($row["cid_id"]),
                Usuario::getById($row["usu_id"])
            );
        }

        return $orcamentos;
    }

    public static function findByKeyDate(string $key, string $date): array
    {
        if(strlen(trim($key)) === 0 || strlen(trim($date)) === 0) return array();
        $sql = "
            select ov.orc_ven_id,ov.orc_ven_descricao,ov.orc_ven_data,
                   ov.orc_ven_nome_cliente,ov.orc_ven_documento_cliente,ov.orc_ven_telefone_cliente,ov.orc_ven_celular_cliente,ov.orc_ven_email_cliente,
                   ov.orc_ven_peso,ov.orc_ven_valor,ov.orc_ven_validade,
                   ov.fun_id,ov.cli_id,ov.cid_id,ov.usu_id
            from orcamento_venda ov
            where (ov.orc_ven_descricao like ?
            or ov.orc_ven_nome_cliente like ?)
            and ov.orc_ven_data = ?
            order by ov.orc_ven_id;
        ";
        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return array();
        }
        $filter = "%".$key."%";
        $stmt->bind_param("ssss", $filter, $filter, $filter, $date);
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
        $orcamentos = [];
        while ($row = $result->fetch_assoc()) {
            $orcamentos[] = new OrcamentoVenda(
                $row["orc_ven_id"],$row["orc_ven_descricao"],$row["orc_ven_data"],$row["orc_ven_nome_cliente"],$row["orc_ven_documento_cliente"],$row["orc_ven_telefone_cliente"],$row["orc_ven_celular_cliente"],$row["orc_ven_email_cliente"],$row["orc_ven_peso"],$row["orc_ven_valor"],$row["orc_ven_validade"],
                Funcionario::getById($row["fun_id"]),
                Cliente::getById($row["cli_id"]),
                Cidade::getById($row["cid_id"]),
                Usuario::getById($row["usu_id"])
            );
        }

        return $orcamentos;
    }

    public static function findAll(): array
    {
        $sql = "
            select orc_ven_id,orc_ven_descricao,orc_ven_data,
                   orc_ven_nome_cliente,orc_ven_documento_cliente,orc_ven_telefone_cliente,orc_ven_celular_cliente,orc_ven_email_cliente,
                   orc_ven_peso,orc_ven_valor,orc_ven_validade,
                   fun_id,cli_id,cid_id,usu_id
            from orcamento_venda
            order by orc_ven_id;
        ";
        /** @var $result mysqli_result */
        $result = Banco::getInstance()->getConnection()->query($sql);
        if (!$result || $result->num_rows <= 0) {
            echo Banco::getInstance()->getConnection()->error;
            return array();
        }
        $orcamentos = [];
        while ($row = $result->fetch_assoc()) {
            $orcamentos[] = new OrcamentoVenda(
                $row["orc_ven_id"],$row["orc_ven_descricao"],$row["orc_ven_data"],$row["orc_ven_nome_cliente"],$row["orc_ven_documento_cliente"],$row["orc_ven_telefone_cliente"],$row["orc_ven_celular_cliente"],$row["orc_ven_email_cliente"],$row["orc_ven_peso"],$row["orc_ven_valor"],$row["orc_ven_validade"],
                Funcionario::getById($row["fun_id"]),
                Cliente::getById($row["cli_id"]),
                Cidade::getById($row["cid_id"]),
                Usuario::getById($row["usu_id"])
            );
        }

        return $orcamentos;
    }

    public function save(): int
    {
        if ($this->id != 0 || strlen($this->descricao) <= 0 || strlen($this->data) <= 0 ||
            strlen($this->nomeCliente) <= 0 || strlen($this->documentoCliente) <= 0 ||
            strlen($this->telefoneCliente) <= 0 || strlen($this->celularCliente) <= 0 ||
            strlen($this->emailCliente) <= 0 || $this->valor <= 0 || $this->peso <= 0 ||
            strlen($this->validade) <= 0 || $this->destino == null || $this->autor == null
        ) return -5;

        $sql = "
            insert into orcamento_venda(
                orc_ven_descricao,orc_ven_data,orc_ven_nome_cliente,orc_ven_documento_cliente,orc_ven_telefone_cliente,
                orc_ven_celular_cliente,orc_ven_email_cliente,orc_ven_peso,orc_ven_valor,orc_ven_validade,
                fun_id,cli_id,cid_id,usu_id
            )
            values(?,?,?,?,?,?,?,?,?,?,?,?,?,?);
        ";
        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }
        $vdd = ($this->vendedor !== null) ? $this->vendedor->getId() : 0;
        $cli = ($this->cliente !== null) ? $this->cliente->getId() : 0;
        $dest = $this->destino->getId();
        $autor = $this->autor->getId();
        $stmt->bind_param(
            "sssssssddsiiii",
            $this->descricao,$this->data,
            $this->nomeCliente,$this->documentoCliente,$this->telefoneCliente,$this->celularCliente,$this->emailCliente,
            $this->peso,$this->valor,$this->validade,
            $vdd,$cli,$dest,$autor
        );
        if (!$stmt->execute()) {
            echo $stmt->error;
            return -10;
        }

        return $stmt->insert_id;
    }

    public function update(): int
    {
        if ($this->id <= 0 || strlen($this->descricao) <= 0 || strlen($this->data) <= 0 ||
            strlen($this->nomeCliente) <= 0 || strlen($this->documentoCliente) <= 0 ||
            strlen($this->telefoneCliente) <= 0 || strlen($this->celularCliente) <= 0 ||
            strlen($this->emailCliente) <= 0 || $this->valor <= 0 || $this->peso <= 0 ||
            strlen($this->validade) <= 0 || $this->destino == null || $this->autor == null
        ) return -5;

        $sql = "
            update orcamento_venda
            set orc_ven_descricao = ?,orc_ven_data = ?,orc_ven_nome_cliente = ?,orc_ven_documento_cliente = ?,orc_ven_telefone_cliente = ?,orc_ven_celular_cliente = ?,orc_ven_email_cliente = ?,orc_ven_peso = ?,orc_ven_valor = ?,orc_ven_validade = ?,fun_id = ?,cli_id = ?,cid_id = ?,usu_id = ?
            where orc_ven_id = ?;
        ";
        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }
        $vdd = ($this->vendedor !== null) ? $this->vendedor->getId() : 0;
        $cli = ($this->cliente !== null) ? $this->cliente->getId() : 0;
        $dest = $this->destino->getId();
        $autor = $this->autor->getId();
        $stmt->bind_param(
            "sssssssddsiiiii",
            $this->descricao,$this->data,
            $this->nomeCliente,$this->documentoCliente,$this->telefoneCliente,$this->celularCliente,$this->emailCliente,
            $this->peso,$this->valor,$this->validade,
            $vdd,$cli,$dest,$autor,$this->id
        );
        if (!$stmt->execute()) {
            echo $stmt->error;
            return -10;
        }

        return $stmt->affected_rows;
    }

    public function delete(): int
    {
        if ($this->id <= 0) return -5;

        $sql = "
            delete
            from orcamento_venda
            where orc_ven_id = ?;
        ";
        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }
        $stmt->bind_param("i", $this->id);
        if (!$stmt->execute()) {
            echo $stmt->error;
            return -10;
        }

        return $stmt->affected_rows;
    }

    public function jsonSerialize()
    {
        $this->vendedor = ($this->vendedor !== null) ? $this->vendedor->jsonSerialize() : null;
        $this->cliente = ($this->cliente !== null) ? $this->cliente->jsonSerialize() : null;
        $this->destino = $this->destino->jsonSerialize();
        $this->autor = $this->autor->jsonSerialize();

        return get_object_vars($this);
    }
}