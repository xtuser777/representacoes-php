<?php namespace scr\dao;

use mysqli_result;
use mysqli_stmt;
use scr\model\Categoria;
use scr\util\Banco;

class CategoriaDAO
{
    public static function insert(string $descricao): int
    {
        $sql = "
            insert into categoria (cat_descricao)
            values (?);
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getconnection()->error;
            return -10;
        }

        $stmt->bind_param("s", $descricao);
        if (!$stmt->execute()) {
            echo $stmt->error;
            return -10;
        }

        return $stmt->insert_id;
    }

    public static function update(int $id, string $descricao): int
    {
        $sql = "
            update categoria
            set cat_descricao = ?
            where cat_id = ?;
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

    public static function delete(int $id): int
    {
        $sql = "
            delete
            from categoria
            where cat_id = ?;
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

    public static function select(): array
    {
        $sql = "
            select cat_id, cat_descricao
            from categoria
            order by cat_id;
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt || !$stmt->execute()) {
            echo Banco::getInstance()->getconnection()->error;
            return array();
        }

        /** @var $result mysqli_result */
        if (!($result = $stmt->get_result()) || $result->num_rows <= 0) {
            echo $stmt->error;
            return array();
        }

        $categorias = [];
        while ($row = $result->fetch_assoc()) {
            $categorias[] = new Categoria($row["cat_id"], $row["cat_descricao"]);
        }

        return $categorias;
    }

    public static function selectkey(string $key): array
    {
        $sql = "
            select cat_id, cat_descricao
            from categoria
            where cat_descricao like ?
            order by cat_id;
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

        $categorias = [];
        while ($row = $result->fetch_assoc()) {
            $categorias[] = new Categoria($row["cat_id"], $row["cat_descricao"]);
        }

        return $categorias;
    }

    public static function selectId(int $id): ?Categoria
    {
        $sql = "
            select cat_id, cat_descricao
            from categoria
            where cat_id = ?;
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
        $row = $result->fetch_assoc();

        return new Categoria($row["cat_id"], $row["cat_descricao"]);
    }
}