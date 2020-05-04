<?php namespace scr\dao;

use mysqli_result;
use mysqli_stmt;
use scr\model\FormaPagamento;
use scr\util\Banco;

class FormaPagamentoDAO
{
    public static function insert(string $descricao, int $prazo): int
    {
        $sql = "
            insert into forma_pagamento(for_pag_descricao, for_pag_prazo)
            values (?,?);
        ";
        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }
        $stmt->bind_param("si", $descricao, $prazo);
        if (!$stmt->execute()) {
            echo $stmt->error;
            return -10;
        }

        return $stmt->insert_id;
    }

    public static function update(int $id, string $descricao, int $prazo): int
    {
        $sql = "
            update forma_pagamento
            set for_pag_descricao = ?, for_pag_prazo = ?
            where for_pag_id = ?;
        ";
        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }
        $stmt->bind_param("sii", $descricao, $prazo, $id);
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
            from forma_pagamento
            where for_pag_id = ?;
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

    public static function select(): array
    {
        $sql = "
            select for_pag_id, for_pag_descricao, for_pag_prazo
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
            $formas[] = new FormaPagamento($row["for_pag_id"], $row["for_pag_descricao"], $row["for_pag_prazo"]);
        }

        return $formas;
    }

    public static function selectkey(string $key): array
    {
        $sql = "
            select for_pag_id, for_pag_descricao, for_pag_prazo
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
            $formas[] = new FormaPagamento($row["for_pag_id"], $row["for_pag_descricao"], $row["for_pag_prazo"]);
        }

        return $formas;
    }

    public static function selectId(int $id): ?FormaPagamento
    {
        $sql = "
            select for_pag_id, for_pag_descricao, for_pag_prazo
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

        return new FormaPagamento($row["for_pag_id"], $row["for_pag_descricao"], $row["for_pag_prazo"]);
    }
}