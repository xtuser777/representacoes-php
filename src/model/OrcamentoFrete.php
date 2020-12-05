<?php


namespace scr\model;


use mysqli_result;
use mysqli_stmt;
use scr\dao\RepresentacaoDAO;
use scr\dao\TipoCaminhaoDAO;
use scr\dao\UsuarioDAO;
use scr\util\Banco;

class OrcamentoFrete
{
    private $id;
    private $descricao;
    private $data;
    private $distancia;
    private $peso;
    private $valor;
    private $entrega;
    private $validade;
    private $orcamentoVenda;
    private $representacao;
    private $cliente;
    private $tipoCaminhao;
    private $destino;
    private $autor;

    public function __construct(int $id = 0, string $descricao = "", string $data = "", int $distancia = 0, float $peso = 0.0, float $valor = 0.0, string $entrega = "", string $validade = "", ?OrcamentoVenda $orcamentoVenda = null, ?Representacao $representacao = null, Cliente $cliente = null, TipoCaminhao $tipoCaminhao = null, Cidade $destino = null, Usuario $autor = null)
    {
        $this->id = $id;
        $this->descricao = $descricao;
        $this->data = $data;
        $this->distancia = $distancia;
        $this->peso = $peso;
        $this->valor = $valor;
        $this->entrega = $entrega;
        $this->validade = $validade;
        $this->orcamentoVenda = $orcamentoVenda;
        $this->representacao = $representacao;
        $this->cliente = $cliente;
        $this->tipoCaminhao = $tipoCaminhao;
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

    public function getDistancia(): int
    {
        return $this->distancia;
    }

    public function getPeso(): float
    {
        return $this->peso;
    }

    public function getValor(): float
    {
        return $this->valor;
    }

    public function getEntrega(): string
    {
        return $this->entrega;
    }

    public function getValidade(): string
    {
        return $this->validade;
    }

    public function getOrcamentoVenda(): ?OrcamentoVenda
    {
        return $this->orcamentoVenda;
    }

    public function getRepresentacao(): ?Representacao
    {
        return $this->representacao;
    }

    /**
     * @return Cliente|null
     */
    public function getCliente(): ?Cliente
    {
        return $this->cliente;
    }

    public function getTipoCaminhao(): TipoCaminhao
    {
        return $this->tipoCaminhao;
    }

    public function getDestino(): Cidade
    {
        return $this->destino;
    }

    public function getAutor(): Usuario
    {
        return $this->autor;
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

    /**
     * @param array $row
     * @return OrcamentoFrete
     */
    private function rowToObject(array $row): OrcamentoFrete
    {
        return new OrcamentoFrete(
            $row["orc_fre_id"],$row["orc_fre_descricao"],$row["orc_fre_data"],$row["orc_fre_distancia"],
            $row["orc_fre_peso"],$row["orc_fre_valor"],$row["orc_fre_entrega"],$row["orc_fre_validade"],
            ($row["orc_ven_id"]) ? OrcamentoVenda::findById($row["orc_ven_id"]) : null,
            ($row["rep_id"]) ? RepresentacaoDAO::getById($row["rep_id"]) : null,
            Cliente::getById($row["cli_id"]),
            TipoCaminhaoDAO::selectId($row["tip_cam_id"]),
            (new Cidade())->getById($row["cid_id"]),
            UsuarioDAO::getById($row["usu_id"])
        );
    }

    /**
     * @param mysqli_result $result
     * @return OrcamentoFrete|null
     */
    private function resultToObject(mysqli_result $result): ?OrcamentoFrete
    {
        if ($result === null || $result->num_rows === 0)
            return null;

        return $this->rowToObject($result->fetch_assoc());
    }

    /**
     * @param mysqli_result $result
     * @return array
     */
    private function resultToList(mysqli_result $result): array
    {
        if ($result === null || $result->num_rows === 0)
            return [];

        $orcamentos = [];
        while ($row = $result->fetch_assoc()) {
            $orcamentos[] = $this->rowToObject($row);
        }

        return $orcamentos;
    }

    public function findById(int $id): ?OrcamentoFrete
    {
        if ($id <= 0)
            return null;

        $sql = "
            select *
            from orcamento_frete
            where orc_fre_id = ?;
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

        return $this->resultToObject($result);
    }

    public function findByKey(string $key): array
    {
        if (strlen(trim($key)) <= 0)
            return [];

        $sql = "
            select *
            from orcamento_frete
            where orc_fre_descricao like ?
            order by orc_fre_id;
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return [];
        }

        $filter = "%".$key."%";

        $stmt->bind_param("s", $filter);
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

    public function findByDate(string $date): array
    {
        if (strlen(trim($date)) <= 0)
            return [];

        $sql = "
            select *
            from orcamento_frete
            where orc_fre_data = ?
            order by orc_fre_id;
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

    public function findByKeyDate(string $key, string $date): array
    {
        if (strlen(trim($key)) <= 0 || strlen(trim($date)) <= 0)
            return [];

        $sql = "
            select *
            from orcamento_frete
            where orc_fre_descricao like ?
            and orc_fre_data = ?
            order by orc_fre_id;
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return [];
        }

        $filter = "%".$key."%";

        $stmt->bind_param("ss", $filter, $date);
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

    public function findByPrice(int $price): ?OrcamentoFrete
    {
        if ($price <= 0)
            return null;

        $sql = "
            select *
            from orcamento_frete
            where orc_ven_id = ?;
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if($stmt === null) {
            echo Banco::getInstance()->getConnection()->error;
            return null;
        }

        $stmt->bind_param("i", $price);
        if ($stmt->execute() === false) {
            $stmt->error;
            return null;
        }

        /** @var $result mysqli_result */
        $result = $stmt->get_result();
        if ($result === null || $result->num_rows <= 0) {
            echo $stmt->error;
            return null;
        }

        return $this->resultToObject($result);
    }

    public function findAll(): array
    {
        $sql = "
            select *
            from orcamento_frete
            order by orc_fre_id;
        ";

        /** @var $result mysqli_result */
        $result = Banco::getInstance()->getConnection()->query($sql);
        if (!$result || $result->num_rows <= 0) {
            echo Banco::getInstance()->getConnection()->error;
            return [];
        }

        return $this->resultToList($result);
    }

    public function save(): int
    {
        if ($this->id != 0 || strlen($this->descricao) <= 0 || strlen($this->data) <= 0 ||
            $this->distancia <= 0 || $this->valor <= 0 || $this->peso <= 0 || strlen($this->validade) <= 0 ||
            $this->destino == null || $this->tipoCaminhao == null || $this->autor == null
        ) return -5;

        $sql = "
            insert 
            into orcamento_frete(
                                 orc_fre_descricao,
                                 orc_fre_data,
                                 orc_fre_distancia,
                                 orc_fre_peso,
                                 orc_fre_valor,
                                 orc_fre_entrega,
                                 orc_fre_validade,
                                 orc_ven_id,
                                 rep_id,
                                 cli_id,
                                 tip_cam_id,
                                 cid_id,
                                 usu_id
                                 )
            values(?,?,?,?,?,?,?,?,?,?,?,?,?);
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }

        $orcamentoVenda = (!$this->orcamentoVenda) ? null : $this->orcamentoVenda->getId();
        $representacao = (!$this->representacao) ? null : $this->representacao->getId();
        $cliente = $this->cliente->getId();
        $tipoCaminhao = $this->tipoCaminhao->getId();
        $destino = $this->destino->getId();
        $autor = $this->autor->getId();

        $stmt->bind_param(
            "ssiddssiiiiii",
            $this->descricao, $this->data, $this->distancia, $this->peso, $this->valor, $this->entrega,
            $this->validade, $orcamentoVenda, $representacao, $cliente, $tipoCaminhao, $destino, $autor);
        if (!$stmt->execute()) {
            echo $stmt->error;
            return -10;
        }

        return $stmt->insert_id;
    }

    public function update(): int
    {
        if ($this->id <= 0 || strlen($this->descricao) <= 0 || strlen($this->data) <= 0 ||
            $this->distancia <= 0 || $this->valor <= 0 || $this->peso <= 0 || strlen($this->validade) <= 0 ||
            $this->destino == null || $this->tipoCaminhao == null || $this->autor == null
        ) return -5;

        $sql = "
            update orcamento_frete
            set orc_fre_descricao = ?,
                orc_fre_distancia = ?,
                orc_fre_peso = ?,
                orc_fre_valor = ?,
                orc_fre_entrega = ?,
                orc_fre_validade = ?,
                orc_ven_id = ?,
                rep_id = ?,
                cli_id = ?,
                tip_cam_id = ?,
                cid_id = ?
            where orc_fre_id = ?;
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }

        $orcamentoVenda = (!$this->orcamentoVenda) ? null : $this->orcamentoVenda->getId();
        $representacao = (!$this->representacao) ? null : $this->representacao->getId();
        $cliente = $this->cliente->getId();
        $tipoCaminhao = $this->tipoCaminhao->getId();
        $destino = $this->destino->getId();

        $stmt->bind_param(
            "siddssiiiiii",
            $this->descricao, $this->distancia, $this->peso, $this->valor, $this->entrega, $this->validade,
            $orcamentoVenda, $representacao, $cliente, $tipoCaminhao, $destino, $this->id
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
            delete
            from orcamento_frete
            where orc_fre_id = ?;
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
        $orcamentoVenda = (!$this->orcamentoVenda) ? null : $this->orcamentoVenda->jsonSerialize();
        $representacao = (!$this->representacao) ? null : $this->representacao->jsonSerialize();
        $cliente = $this->cliente->jsonSerialize();
        $tipoCaminhao = $this->tipoCaminhao->jsonSerialize();
        $destino = $this->destino->jsonSerialize();
        $autor = $this->autor->jsonSerialize();

        return [
            "id" => $this->id,
            "descricao" => $this->descricao,
            "data" => $this->data,
            "distancia" => $this->distancia,
            "peso" => $this->peso,
            "valor" => $this->valor,
            "entrega" => $this->entrega,
            "validade" => $this->validade,
            "venda" => $orcamentoVenda,
            "representacao" => $representacao,
            "cliente" => $cliente,
            "tipoCaminhao" => $tipoCaminhao,
            "destino" => $destino,
            "autor" => $autor
        ];
    }
}