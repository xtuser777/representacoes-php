<?php


namespace scr\model;


use mysqli_result;
use mysqli_stmt;
use scr\util\Banco;

class EtapaCarregamento
{
    /** @var int */
    private $id;

    /** @var int */
    private $ordem;

    /** @var int */
    private $status;

    /** @var float */
    private $carga;

    /** @var Representacao|null */
    private $representacao;

    /**
     * EtapaCarregamento constructor.
     * @param int $id
     * @param int $ordem
     * @param int $status
     * @param float $carga
     * @param Representacao|null $representacao
     */
    public function __construct(int $id = 0, int $ordem = 0, int $status = 0, float $carga = 0.0, ?Representacao $representacao = null)
    {
        $this->id = $id;
        $this->ordem = $ordem;
        $this->status = $status;
        $this->carga = $carga;
        $this->representacao = $representacao;
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
    public function getOrdem(): int
    {
        return $this->ordem;
    }

    /**
     * @param int $ordem
     */
    public function setOrdem(int $ordem): void
    {
        $this->ordem = $ordem;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * @return float
     */
    public function getCarga(): float
    {
        return $this->carga;
    }

    /**
     * @param float $carga
     */
    public function setCarga(float $carga): void
    {
        $this->carga = $carga;
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
     * @param array $row
     * @return EtapaCarregamento
     */
    private function rowToObject(array $row): EtapaCarregamento
    {
        $etapa = new EtapaCarregamento();
        $etapa->setId($row["eta_car_id"]);
        $etapa->setOrdem($row["eta_car_ordem"]);
        $etapa->setStatus($row["eta_car_status"]);
        $etapa->setCarga($row["eta_car_carga"]);
        $etapa->setRepresentacao(Representacao::getById($row["rep_id"]));

        return $etapa;
    }

    /**
     * @param mysqli_result $result
     * @return EtapaCarregamento
     */
    private function resultToObject(mysqli_result $result): EtapaCarregamento
    {
        $row = $result->fetch_assoc();

        return $this->rowToObject($row);
    }

    /**
     * @param mysqli_result $result
     * @return array
     */
    private function resultToList(mysqli_result $result): array
    {
        $etapas = [];

        while ($row = $result->fetch_assoc()) {
            $etapas[] = $this->rowToObject($row);
        }

        return $etapas;
    }

    /**
     * @param int $id
     * @param int $pedido
     * @return EtapaCarregamento|null
     */
    public function findById(int $id, int $pedido): ?EtapaCarregamento
    {
        if ($id <= 0 || $pedido <= 0)
            return null;

        $sql = "
            SELECT * 
            FROM etapa_carregamento 
            WHERE eta_car_id = ? and ped_fre_id = ?;
        ";

        /** @var mysqli_stmt $stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return null;
        }

        $stmt->bind_param("ii", $id, $pedido);

        if (!$stmt->execute()) {
            echo $stmt->error;
            return null;
        }

        /** @var mysqli_result $result */
        $result = $stmt->get_result();
        if (!$result) {
            echo $stmt->error;
            return null;
        }

        return $this->resultToObject($result);
    }

    /**
     * @param int $pedido
     * @return array
     */
    public function findBySale(int $pedido): array
    {
        if ($pedido <= 0)
            return [];

        $sql = "
            SELECT * 
            FROM etapa_carregamento 
            WHERE ped_fre_id = ?;
        ";

        /** @var mysqli_stmt $stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return [];
        }

        $stmt->bind_param("i", $pedido);

        if (!$stmt->execute()) {
            echo $stmt->error;
            return [];
        }

        /** @var mysqli_result $result */
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
    public function save(int $pedido): int
    {
        if (
            $this->id != 0 ||
            $this->ordem <= 0 ||
            $this->status <= 0 ||
            $this->carga <= 0 ||
            $this->representacao === null
        )
            return -5;

        $sql = "
            INSERT 
            INTO etapa_carregamento (ped_fre_id, eta_car_ordem, eta_car_status, eta_car_carga, rep_id) 
            VALUES (?,?,?,?,?);
        ";

        /** @var mysqli_stmt $stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }

        $representacao = $this->representacao->getId();

        $stmt->bind_param(
            "iiidi",
            $pedido,
            $this->ordem,
            $this->status,
            $this->carga,
            $representacao
        );

        if (!$stmt->execute()) {
            echo $stmt->error;
            return -10;
        }

        return $stmt->insert_id;
    }

    /**
     * @return int
     */
    public function autorize(): int
    {
        if ($this->id <= 0)
            return -5;

        $sql = "
            UPDATE etapa_carregamento 
            SET eta_car_status = 2
            WHERE eta_car_id = ?;
        ";

        if (!Banco::getInstance()->prepareStatement($sql))
            return -10;

        if (!Banco::getInstance()->addParameters("i", [ $this->id]))
            return -10;

        if (!Banco::getInstance()->executeStatement())
            return -10;

        return Banco::getInstance()->getAffectedRows();
    }

    /**
     * @return int
     */
    public function delete(): int
    {
        if ($this->id <= 0)
            return -5;

        $sql = "
            DELETE 
            FROM etapa_carregamento 
            WHERE eta_car_id = ?;
        ";

        /** @var mysqli_stmt $stmt */
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

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        $representacao = $this->representacao->jsonSerialize();

        return [
            "id" => $this->id,
            "ordem" => $this->ordem,
            "status" => $this->status,
            "carga" => $this->carga,
            "representacao" => $representacao
        ];
    }
}