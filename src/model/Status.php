<?php


namespace scr\model;


use mysqli_result;
use mysqli_stmt;
use scr\util\Banco;

class Status
{
    /** @var int */
    private $id;

    /** @var string */
    private $descricao;

    /**
     * Status constructor.
     * @param int $id
     * @param string $descricao
     */
    public function __construct(int $id = 0, string $descricao = "")
    {
        $this->id = $id;
        $this->descricao = $descricao;
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
     * @param array $row
     * @return Status
     */
    private function rowToObject(array $row): Status
    {
        $status = new Status();
        $status->setId($row["sts_id"]);
        $status->setDescricao($row["sts_descricao"]);

        return $status;
    }

    /**
     * @param mysqli_result $result
     * @return Status
     */
    private function resultToObject(mysqli_result $result): Status
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
        $statuses = [];

        while ($row = $result->fetch_assoc()) {
            $statuses[] = $this->rowToObject($row);
        }

        return $statuses;
    }

    /**
     * @param int $id
     * @return Status|null
     */
    public function findById(int $id): ?Status
    {
        if ($id <= 0)
            return null;

        $sql = "
            SELECT * 
            FROM status 
            WHERE sts_id = ?;
        ";

        /** @var mysqli_stmt $stmt */
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

        /** @var mysqli_result $result */
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
    public function findAll(): array
    {
        $sql = "
            SELECT * 
            FROM status 
            ORDER BY sts_id;
        ";

        /** @var mysqli_stmt $stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return [];
        }

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
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            "id" => $this->id,
            "descricao" => $this->descricao
        ];
    }
}