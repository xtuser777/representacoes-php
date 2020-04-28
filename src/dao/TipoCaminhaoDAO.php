<?php namespace scr\dao;

use mysqli_result;
use mysqli_stmt;
use scr\model\TipoCaminhao;
use scr\util\Banco;

class TipoCaminhaoDAO
{
    public static function insert(string $descricao, int $eixos, float $capacidade): int
    {
        $sql = "
            insert into tipo_caminhao (tip_cam_descricao, tip_cam_eixos, tip_cam_capacidade)
            values (?,?,?);
        ";

        /** @var mysqli_stmt $stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if(!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }

        $stmt->bind_param('sid', $descricao, $eixos, $capacidade);
        $stmt->execute();

        return $stmt->insert_id;
    }

    public static function update(int $id, string $descricao, int $eixos, float $capacidade): int
    {
        $sql = "
            update tipo_caminhao
            set tip_cam_descricao = ?, tip_cam_eixos = ?, tip_cam_capacidade = ?
            where tip_cam_id = ?;
        ";

        /** @var mysqli_stmt $stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if(!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }

        $stmt->bind_param('sidi', $descricao, $eixos, $capacidade, $id);
        $stmt->execute();

        return $stmt->affected_rows;
    }

    public static function delete(int $id): int
    {
        $sql = "
            delete
            from tipo_caminhao
            where tip_cam_id = ?;
        ";

        /** @var mysqli_stmt $stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if(!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }

        $stmt->bind_param('i', $id);
        $stmt->execute();

        return $stmt->affected_rows;
    }

    public static function select() : array
    {
        $sql = "
            select tip_cam_id, tip_cam_descricao, tip_cam_eixos, tip_cam_capacidade
            from tipo_caminhao
            order by tip_cam_id;
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return array();
        }

        $stmt->execute();

        /** @var $result mysqli_result */
        if (!($result = $stmt->get_result()) || $result->num_rows <= 0) {
            echo $stmt->error;
            return array();
        }

        $tipos = array();
        for ($i = 0; $i < $result->num_rows; $i++) {
            $row = $result->fetch_assoc();
            $tipos[] = new TipoCaminhao (
                $row["tip_cam_id"],
                $row["tip_cam_descricao"],
                $row["tip_cam_eixos"],
                $row["tip_cam_capacidade"]
            );
        }

        return $tipos;
    }

    public static function selectDescription(string $descricao): array
    {
        $sql = "
            select tip_cam_id, tip_cam_descricao, tip_cam_eixos, tip_cam_capacidade
            from tipo_caminhao
            where tip_cam_descricao like ?
            order by tip_cam_id;
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return array();
        }

        $key = "%" . $descricao . "%";
        $stmt->bind_param("s", $key);
        $stmt->execute();

        /** @var $result mysqli_result */
        if (!($result = $stmt->get_result()) || $result->num_rows <= 0) {
            echo $stmt->error;
            return array();
        }

        $tipos = array();
        for ($i = 0; $i < $result->num_rows; $i++) {
            $row = $result->fetch_assoc();
            $tipos[] = new TipoCaminhao (
                $row["tip_cam_id"],
                $row["tip_cam_descricao"],
                $row["tip_cam_eixos"],
                $row["tip_cam_capacidade"]
            );
        }

        return $tipos;
    }

    public static function selectId(int $id): ?TipoCaminhao
    {
        $sql = "
            select tip_cam_id, tip_cam_descricao, tip_cam_eixos, tip_cam_capacidade
            from tipo_caminhao
            where tip_cam_id = ?;
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return null;
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();

        /** @var $result mysqli_result */
        if (!($result = $stmt->get_result()) || $result->num_rows <= 0) {
            echo $stmt->error;
            return null;
        }
        $row = $result->fetch_assoc();

        return new TipoCaminhao (
            $row["tip_cam_id"],
            $row["tip_cam_descricao"],
            $row["tip_cam_eixos"],
            $row["tip_cam_capacidade"]
        );
    }

    public static function dependents(int $id): int
    {
        $sql = "
            select count(tip_cam_id) as dependents
            from tipo_caminhao tc
            inner join produto_tipo_caminhao ptc on ptc.tip_cam_id = tc.tip_cam_id
            inner join caminhao cam on cam.tip_cam_id = tc.tip_cam_id
            where tc.tip_cam_id = ?; 
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            //echo Banco::getInstance()->getConnection()->error;
            return 0;
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();

        /** @var $result mysqli_result */
        if (!($result = $stmt->get_result()) || $result->num_rows <= 0) {
            //echo $stmt->error;
            return 0;
        }
        $row = $result->fetch_assoc();

        return $row["dependents"];
    }
}