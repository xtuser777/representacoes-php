<?php


namespace scr\model;


use scr\util\Banco;

class FormaPagamento
{
    /** @var int */
    private $id;

    /** @var string  */
    private $descricao;

    /** @var int */
    private $vinculo;

    /** @var int  */
    private $prazo;

    /**
     * FormaPagamento constructor.
     * @param int $id
     * @param string $descricao
     * @param int $vinculo
     * @param int $prazo
     */
    public function __construct(int $id = 0, string $descricao = "", int $vinculo = 0, int $prazo = 0)
    {
        $this->id = $id;
        $this->descricao = $descricao;
        $this->vinculo = $vinculo;
        $this->prazo = $prazo;
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
     * @return int
     */
    public function getVinculo(): int
    {
        return $this->vinculo;
    }

    /**
     * @param int $vinculo
     */
    public function setVinculo(int $vinculo): void
    {
        $this->vinculo = $vinculo;
    }

    /**
     * @return int
     */
    public function getPrazo(): int
    {
        return $this->prazo;
    }

    /**
     * @param int $prazo
     */
    public function setPrazo(int $prazo): void
    {
        $this->prazo = $prazo;
    }

    public static function findById(int $id): ?FormaPagamento
    {
        if ($id <= 0)
            return null;

        $sql = "
            select for_pag_id, for_pag_descricao, for_pag_vinculo, for_pag_prazo
            from forma_pagamento
            where for_pag_id = ?;
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

        return new FormaPagamento(
            $row["for_pag_id"],
            $row["for_pag_descricao"],
            $row["for_pag_vinculo"],
            $row["for_pag_prazo"]
        );
    }

    public static function findByKey(string $key): array
    {
        if (strlen(trim($key)) === 0)
            return array();

        $sql = "
            select for_pag_id, for_pag_descricao, for_pag_vinculo, for_pag_prazo
            from forma_pagamento
            where for_pag_descricao like ?
            order by for_pag_id;
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return array();
        }

        $filtro = "%" . $key . "%";
        $stmt->bind_param("s", $filtro);
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

        $formas = [];
        while ($row = $result->fetch_assoc()) {
            $formas[] = new FormaPagamento(
                $row["for_pag_id"],
                $row["for_pag_descricao"],
                $row["for_pag_vinculo"],
                $row["for_pag_prazo"]
            );
        }

        return $formas;
    }

    public static function findAll(): array
    {
        $sql = "
            select for_pag_id, for_pag_descricao, for_pag_vinculo, for_pag_prazo
            from forma_pagamento
            order by for_pag_id;
        ";

        /** @var $result mysqli_result */
        $result = Banco::getInstance()->getConnection()->query($sql);
        if (!$result || $result->num_rows <= 0) {
            echo Banco::getInstance()->getConnection()->error;
            return array();
        }

        $formas = [];
        while ($row = $result->fetch_assoc()) {
            $formas[] = new FormaPagamento(
                $row["for_pag_id"],
                $row["for_pag_descricao"],
                $row["for_pag_vinculo"],
                $row["for_pag_prazo"]
            );
        }

        return $formas;
    }

    public static function findByPayment(): array
    {
        $sql = "
            select for_pag_id, for_pag_descricao, for_pag_vinculo, for_pag_prazo
            from forma_pagamento 
            where for_pag_vinculo = 1 
            order by for_pag_id;
        ";

        /** @var $result mysqli_result */
        $result = Banco::getInstance()->getConnection()->query($sql);
        if (!$result || $result->num_rows <= 0) {
            echo Banco::getInstance()->getConnection()->error;
            return array();
        }

        $formas = [];
        while ($row = $result->fetch_assoc()) {
            $formas[] = new FormaPagamento(
                $row["for_pag_id"],
                $row["for_pag_descricao"],
                $row["for_pag_vinculo"],
                $row["for_pag_prazo"]
            );
        }

        return $formas;
    }

    public static function findByReceive(): array
    {
        $sql = "
            select for_pag_id, for_pag_descricao, for_pag_vinculo, for_pag_prazo
            from forma_pagamento 
            where for_pag_vinculo = 2 
            order by for_pag_id;
        ";

        /** @var $result mysqli_result */
        $result = Banco::getInstance()->getConnection()->query($sql);
        if (!$result || $result->num_rows <= 0) {
            echo Banco::getInstance()->getConnection()->error;
            return array();
        }

        $formas = [];
        while ($row = $result->fetch_assoc()) {
            $formas[] = new FormaPagamento(
                $row["for_pag_id"],
                $row["for_pag_descricao"],
                $row["for_pag_vinculo"],
                $row["for_pag_prazo"]
            );
        }

        return $formas;
    }

    public function save(): int
    {
        if ($this->id != 0 || strlen(trim($this->descricao)) <= 0 || $this->vinculo <= 0 || $this->prazo <= 0)
            return -5;

        $sql = "
            insert into forma_pagamento(for_pag_descricao, for_pag_vinculo, for_pag_prazo)
            values (?,?,?);
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }

        $stmt->bind_param("sii", $this->descricao, $this->vinculo, $this->prazo);
        if (!$stmt->execute()) {
            echo $stmt->error;
            return -10;
        }

        return $stmt->insert_id;
    }

    public function update(): int
    {
        if ($this->id <= 0 || strlen(trim($this->descricao)) <= 0 || $this->vinculo <= 0 || $this->prazo <= 0)
            return -5;

        $sql = "
            update forma_pagamento
            set for_pag_descricao = ?, for_pag_vinculo = ?, for_pag_prazo = ?
            where for_pag_id = ?;
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }

        $stmt->bind_param("siii", $this->descricao, $this->vinculo, $this->prazo, $this->id);
        if (!$stmt->execute()) {
            echo $stmt->error;
            return -10;
        }

        return $stmt->affected_rows;
    }

    public function delete(): int
    {
        $sql = "
            delete
            from forma_pagamento
            where for_pag_id = ?;
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
        return [
            "id" => $this->id,
            "descricao" => $this->descricao,
            "vinculo" => $this->vinculo,
            "prazo" => $this->prazo
        ];
    }
}