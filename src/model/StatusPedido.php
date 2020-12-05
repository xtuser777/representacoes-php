<?php


namespace scr\model;


use mysqli_result;
use mysqli_stmt;
use scr\util\Banco;

class StatusPedido
{
    /** @var Status|null */
    private $status;

    /** @var string */
    private $data;

    /** @var string */
    private $hora;

    /** @var string */
    private $observacoes;

    /** @var bool */
    private $atual;

    /** @var Usuario|null */
    private $autor;

    /**
     * StatusPedido constructor.
     * @param Status|null $status
     * @param string $data
     * @param string $hora
     * @param string $observacoes
     * @param bool $atual
     * @param Usuario|null $autor
     */
    public function __construct(?Status $status = null, string $data = "", string $hora = "", string $observacoes = "", bool $atual = false, ?Usuario $autor = null)
    {
        $this->status = $status;
        $this->data = $data;
        $this->hora = $hora;
        $this->observacoes = $observacoes;
        $this->atual = $atual;
        $this->autor = $autor;
    }

    /**
     * @return Status|null
     */
    public function getStatus(): ?Status
    {
        return $this->status;
    }

    /**
     * @param Status|null $status
     */
    public function setStatus(?Status $status): void
    {
        $this->status = $status;
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
    public function getHora(): string
    {
        return $this->hora;
    }

    /**
     * @param string $hora
     */
    public function setHora(string $hora): void
    {
        $this->hora = $hora;
    }

    /**
     * @return string
     */
    public function getObservacoes(): string
    {
        return $this->observacoes;
    }

    /**
     * @param string $observacoes
     */
    public function setObservacoes(string $observacoes): void
    {
        $this->observacoes = $observacoes;
    }

    /**
     * @return bool
     */
    public function isAtual(): bool
    {
        return $this->atual;
    }

    /**
     * @param bool $atual
     */
    public function setAtual(bool $atual): void
    {
        $this->atual = $atual;
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
     * @param array $row
     * @return StatusPedido
     */
    private function rowToObject(array $row): StatusPedido
    {
        $sp = new StatusPedido();
        $sp->setStatus((new Status())->findById($row["sts_id"]));
        $sp->setData($row["ped_fre_sts_data"]);
        $sp->setHora($row["ped_fre_sts_hora"]);
        $sp->setObservacoes($row["ped_fre_sts_observacoes"]);
        $sp->setAtual($row["ped_fre_sts_atual"]);
        $sp->setAutor(Usuario::getById($row["usu_id"]));

        return $sp;
    }

    /**
     * @param mysqli_result $result
     * @return StatusPedido
     */
    private function resultToObject(mysqli_result $result): StatusPedido
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
        $sps = [];

        while ($row = $result->fetch_assoc()) {
            $sps[] = $this->rowToObject($row);
        }

        return $sps;
    }

    /**
     * @param int $pedido
     * @param int $status
     * @return StatusPedido|null
     */
    public function findById(int $pedido, int $status): ?StatusPedido
    {
        if ($pedido <= 0 || $status <= 0)
            return null;

        $sql = "
            SELECT * 
            FROM pedido_frete_status 
            WHERE ped_fre_id = ? AND sts_id = ?;
        ";

        /** @var mysqli_stmt $stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return null;
        }

        $stmt->bind_param("ii", $pedido, $status);

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
            FROM pedido_frete_status 
            WHERE ped_fre_id = ?
            ORDER BY ped_fre_sts_hora;
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
     * @param int $pedido
     * @return int
     */
    public function save(int $pedido): int
    {
        if (
            $pedido <= 0 ||
            $this->status === null ||
            strlen($this->data) === 0 ||
            $this->autor === null
        )
            return -5;

        $sql = "
            INSERT 
            INTO pedido_frete_status (ped_fre_id, sts_id, ped_fre_sts_data, ped_fre_sts_hora, ped_fre_sts_observacoes, ped_fre_sts_atual, usu_id) 
            VALUES (?,?,?,CURRENT_TIME(),?,?,?);
        ";

        /** @var mysqli_stmt $stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }

        $status = $this->status->getId();
        $autor = $this->autor->getId();

        $stmt->bind_param(
            "iissii",
            $pedido,
            $status,
            $this->data,
            $this->observacoes,
            $this->atual,
            $autor
        );

        if (!$stmt->execute()) {
            echo $stmt->error;
            return -10;
        }

        return $stmt->insert_id;
    }

    /**
     * @param int $pedido
     * @param int $status
     * @return int
     */
    public function desatualizar(int $pedido, int $status): int
    {
        $sql = "
            update pedido_frete_status 
            set ped_fre_sts_atual = false
            where ped_fre_id = ? and sts_id = ?
        ";

        if (!Banco::getInstance()->prepareStatement($sql))
            return -10;

        if (!Banco::getInstance()->addParameters("ii", [ $pedido, $status ]))
            return -10;

        if (!Banco::getInstance()->executeStatement())
            return -10;

        return Banco::getInstance()->getAffectedRows();
    }

    /**
     * @param int $pedido
     * @return int
     */
    public function delete(int $pedido): int
    {
        if ($pedido <= 0)
            return -5;

        $sql = "
            DELETE 
            FROM pedido_frete_status 
            WHERE ped_fre_id = ?;
        ";

        if (!Banco::getInstance()->prepareStatement($sql))
            return -10;

        if (!Banco::getInstance()->addParameters("i", [ $pedido ]))
            return -10;

        if (!Banco::getInstance()->executeStatement())
            return -10;

        return Banco::getInstance()->getAffectedRows();
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        $status = $this->status->jsonSerialize();
        $autor = $this->autor->jsonSerialize();

        return [
            "status" => $status,
            "data" => $this->data,
            "hora" => $this->hora,
            "observacoes" => $this->observacoes,
            "atual" => $this->atual,
            "autor" => $autor
        ];
    }
}