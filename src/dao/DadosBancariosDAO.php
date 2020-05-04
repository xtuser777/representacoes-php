<?php namespace scr\dao;

use mysqli_result;
use mysqli_stmt;
use scr\model\DadosBancarios;
use scr\util\Banco;

class DadosBancariosDAO
{
    public static function insert(string $banco, string $agencia, string $conta, int $tipo): int
    {
        $sql = "
            insert into dados_bancarios (dad_ban_banco, dad_ban_agencia, dad_ban_conta, dad_ban_tipo)
            values (?,?,?,?);
        ";
        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }
        $stmt->bind_param("sssi", $banco, $agencia, $conta, $tipo);
        if (!$stmt->execute()) {
            echo $stmt->error;
            return -10;
        }

        return $stmt->insert_id;
    }

    public static function update(int $id, string $banco, string $agencia, string $conta, int $tipo): int
    {
        $sql = "
            update dados_bancarios
            set dad_ban_banco = ?, dad_ban_agencia = ?, dad_ban_conta = ?, dad_ban_tipo = ?
            where dad_ban_id = ?;
        ";
        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }
        $stmt->bind_param("sssii", $banco, $agencia, $conta, $tipo, $id);
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
            from dados_bancarios
            where dad_ban_id = ?;
        ";
        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }
        $stmt->bind_param("i", $id);
        if (!$stmt->execute()) {
            echo $stmt->error;
            return -10;
        }

        return $stmt->affected_rows;
    }

    public static function selectId(int $id): ?DadosBancarios
    {
        $sql = "
            select dad_ban_id, dad_ban_banco, dad_ban_agencia, dad_ban_conta, dad_ban_tipo
            from dados_bancarios
            where dad_ban_id = ?;
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

        return new DadosBancarios(
            $row["dad_ban_id"], $row["dad_ban_banco"], $row["dad_ban_agencia"], $row["dad_ban_conta"], $row["dad_ban_tipo"]
        );
    }
}