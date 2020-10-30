<?php


namespace scr\model;


use mysqli_result;
use mysqli_stmt;
use scr\util\Banco;

class CategoriaContaPagar
{
    private $id;
    private $descricao;

    /**
     * CategoriaContaPagar constructor.
     * @param int $id
     * @param string $descricao
     */
    public function __construct(int $id = 0, string $descricao = "")
    {
        $this->id = $id;
        $this->descricao = $descricao;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getDescricao(): string
    {
        return $this->descricao;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
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
     * @return CategoriaContaPagar
     */
    private static function rowToObject(array $row): CategoriaContaPagar
    {
        $categoria = new CategoriaContaPagar();
        $categoria->setId($row["cat_con_pag_id"]);
        $categoria->setDescricao($row["cat_con_pag_descricao"]);

        return $categoria;
    }

    /**
     * @param mysqli_result $result
     * @return CategoriaContaPagar
     */
    private static function resultToObject(mysqli_result $result): CategoriaContaPagar
    {
        $row = $result->fetch_assoc();

        return self::rowToObject($row);
    }

    /**
     * @param mysqli_result $result
     * @return array
     */
    private static function resultToList(mysqli_result $result): array
    {
        $categorias = [];
        while($row = $result->fetch_assoc()) {
            $categorias[] = self::rowToObject($row);
        }

        return $categorias;
    }

    public static function findById(int $id): ?CategoriaContaPagar
    {
        if ($id <= 0)
            return null;

        $sql = "
            select cat_con_pag_id, cat_con_pag_descricao
            from categoria_conta_pagar
            where cat_con_pag_id = ?;
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getconnection()->error;
            return null;
        }

        $stmt->bind_param("i", $id);
        if (!$stmt->execute()) {
            echo $stmt->error;
            return null;
        }

        /** @var $result mysqli_result */
        if (!($result = $stmt->get_result()) || $result->num_rows <= 0) {
            echo $stmt->error;
            return null;
        }

        return self::resultToObject($result);
    }

    public static function findByKey(string $key): array
    {
        if (strlen(trim($key)) === 0)
            return array();

        $sql = "
            select cat_con_pag_id, cat_con_pag_descricao
            from categoria_conta_pagar
            where cat_con_pag_descricao like ?
            order by cat_con_pag_descricao;
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getconnection()->error;
            return array();
        }

        $chave = "%" . $key . "%";
        $stmt->bind_param("s", $chave);
        if (!$stmt->execute()) {
            echo $stmt->error;
            return array();
        }

        /** @var $result mysqli_result */
        if (!($result = $stmt->get_result()) || $result->num_rows <= 0) {
            echo $stmt->error;
            return array();
        }

        return self::resultToList($result);
    }

    public function findAll(): array
    {
        $sql = "
            select cat_con_pag_id, cat_con_pag_descricao
            from categoria_conta_pagar
            order by cat_con_pag_descricao;
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt || !$stmt->execute()) {
            echo Banco::getInstance()->getConnection()->error;
            return array();
        }

        /** @var $result mysqli_result */
        if (!($result = $stmt->get_result()) || $result->num_rows <= 0) {
            echo $stmt->error;
            return array();
        }

        return self::resultToList($result);
    }

    public function save()
    {
        if ($this->id != 0 || strlen(trim($this->descricao)) <= 0)
            return -5;

        $sql = "
            insert into categoria_conta_pagar (cat_con_pag_descricao)
            values (?);
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getconnection()->error;
            return -10;
        }

        $stmt->bind_param("s", $this->descricao);
        if (!$stmt->execute()) {
            echo $stmt->error;
            return -10;
        }

        return $stmt->insert_id;
    }

    public function update()
    {
        if ($this->id <= 0 || strlen(trim($this->descricao)) <= 0)
            return -5;

        $sql = "
            update categoria_conta_pagar
            set cat_con_pag_descricao = ?
            where cat_con_pag_id = ?;
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getconnection()->error;
            return -10;
        }

        $stmt->bind_param("si", $descricao, $id);
        if (!$stmt->execute()) {
            echo $stmt->error;
            return -10;
        }

        return $stmt->affected_rows;
    }

    public function delete()
    {
        if ($this->id <= 0)
            return -5;

        $sql = "
            delete
            from categoria_conta_pagar
            where cat_con_pag_id = ?;
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getconnection()->error;
            return -10;
        }

        $stmt->bind_param("i", $id);
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
            "descricao" => $this->descricao
        ];
    }
}