<?php namespace scr\dao;

use mysqli_result;
use mysqli_stmt;
use scr\model\OrcamentoFrete;
use scr\model\OrcamentoVenda;
use scr\util\Banco;

class OrcamentoFreteDAO
{
    public static function insert(string $descricao, string $data, int $distancia, float $peso, float $valor, string $entrega, string $validade, int $orcamentoVenda, int $representacao, int $tipoCaminhao, int $destino, int $autor): int
    {
        $sql = "
            insert 
            into orcamento_frete(orc_fre_descricao,orc_fre_data,orc_fre_distancia,orc_fre_peso,orc_fre_valor,orc_fre_entrega,orc_fre_validade,orc_ven_id,rep_id,tip_cam_id,cid_id,usu_id)
            values(?,?,?,?,?,?,?,?,?,?,?,?);
        ";
        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }
        $stmt->bind_param("ssiffssiiiii", $descricao, $data, $distancia, $peso, $valor, $entrega, $validade, $orcamentoVenda, $representacao, $tipoCaminhao, $destino, $autor);
        if (!$stmt->execute()) {
            echo $stmt->error;
            return -10;
        }

        return $stmt->insert_id;
    }

    public static function update(int $id, string $descricao, string $data, int $distancia, float $peso, float $valor, string $entrega, string $validade, int $orcamentoVenda, int $representacao, int $tipoCaminhao, int $destino, int $autor): int
    {
        $sql = "
            update orcamento_frete
            set orc_fre_descricao = ?,orc_fre_data = ?,orc_fre_distancia = ?,orc_fre_peso = ?,orc_fre_valor = ?,orc_fre_entrega = ?,orc_fre_validade = ?,orc_ven_id = ?,rep_id = ?,tip_cam_id = ?,cid_id = ?,usu_id = ?
            where orc_fre_id = ?;
        ";
        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }
        $stmt->bind_param("ssiffssiiiiii", $descricao, $data, $distancia, $peso, $valor, $entrega, $validade, $orcamentoVenda, $representacao, $tipoCaminhao, $destino, $autor, $id);
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
            from orcamento_frete
            where orc_fre_id = ?;
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
            select orc_fre_id,orc_fre_descricao,orc_fre_data,orc_fre_distancia,orc_fre_peso,orc_fre_valor,
                   orc_fre_entrega,orc_fre_validade,orc_ven_id,rep_id,tip_cam_id,cid_id,usu_id
            from orcamento_frete
            order by orc_fre_id;
        ";
        /** @var $result mysqli_result */
        $result = Banco::getInstance()->getConnection()->query($sql);
        if (!$result || $result->num_rows <= 0) {
            echo Banco::getInstance()->getConnection()->error;
            return [];
        }
        $orcamentos = [];
        while ($row = $result->fetch_assoc()) {
            $orcamentos[] = new OrcamentoFrete(
                $row["orc_fre_id"],$row["orc_fre_descricao"],$row["orc_fre_data"],$row["orc_fre_distancia"],$row["orc_fre_peso"],$row["orc_fre_valor"],$row["orc_fre_entrega"],$row["orc_fre_validade"],
                OrcamentoVenda::findById($row["orc_ven_id"]),
                RepresentacaoDAO::getById($row["rep_id"]),
                TipoCaminhaoDAO::selectId($row["tip_cam_id"]),
                CidadeDAO::getById($row["cid_id"]),
                UsuarioDAO::getById($row["usu_id"])
            );
        }

        return $orcamentos;
    }

    public static function selectKey(string $key): array
    {
        $sql = "
            select orc_fre_id,orc_fre_descricao,orc_fre_data,orc_fre_distancia,orc_fre_peso,orc_fre_valor,
                   orc_fre_entrega,orc_fre_validade,orc_ven_id,rep_id,tip_cam_id,cid_id,usu_id
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
        $orcamentos = [];
        while ($row = $result->fetch_assoc()) {
            $orcamentos[] = new OrcamentoFrete(
                $row["orc_fre_id"],$row["orc_fre_descricao"],$row["orc_fre_data"],$row["orc_fre_distancia"],$row["orc_fre_peso"],$row["orc_fre_valor"],$row["orc_fre_entrega"],$row["orc_fre_validade"],
                OrcamentoVendaDAO::selectId($row["orc_ven_id"]),
                RepresentacaoDAO::getById($row["rep_id"]),
                TipoCaminhaoDAO::selectId($row["tip_cam_id"]),
                CidadeDAO::getById($row["cid_id"]),
                UsuarioDAO::getById($row["usu_id"])
            );
        }

        return $orcamentos;
    }

    public static function selectDate(string $date): array
    {
        $sql = "
            select orc_fre_id,orc_fre_descricao,orc_fre_data,orc_fre_distancia,orc_fre_peso,orc_fre_valor,
                   orc_fre_entrega,orc_fre_validade,orc_ven_id,rep_id,tip_cam_id,cid_id,usu_id
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
        $orcamentos = [];
        while ($row = $result->fetch_assoc()) {
            $orcamentos[] = new OrcamentoFrete(
                $row["orc_fre_id"],$row["orc_fre_descricao"],$row["orc_fre_data"],$row["orc_fre_distancia"],$row["orc_fre_peso"],$row["orc_fre_valor"],$row["orc_fre_entrega"],$row["orc_fre_validade"],
                OrcamentoVendaDAO::selectId($row["orc_ven_id"]),
                RepresentacaoDAO::getById($row["rep_id"]),
                TipoCaminhaoDAO::selectId($row["tip_cam_id"]),
                CidadeDAO::getById($row["cid_id"]),
                UsuarioDAO::getById($row["usu_id"])
            );
        }

        return $orcamentos;
    }

    public static function selectKeyDate(string $key, string $date): array
    {
        $sql = "
            select orc_fre_id,orc_fre_descricao,orc_fre_data,orc_fre_distancia,orc_fre_peso,orc_fre_valor,
                   orc_fre_entrega,orc_fre_validade,orc_ven_id,rep_id,tip_cam_id,cid_id,usu_id
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
        $orcamentos = [];
        while ($row = $result->fetch_assoc()) {
            $orcamentos[] = new OrcamentoFrete(
                $row["orc_fre_id"],$row["orc_fre_descricao"],$row["orc_fre_data"],$row["orc_fre_distancia"],$row["orc_fre_peso"],$row["orc_fre_valor"],$row["orc_fre_entrega"],$row["orc_fre_validade"],
                OrcamentoVendaDAO::selectId($row["orc_ven_id"]),
                RepresentacaoDAO::getById($row["rep_id"]),
                TipoCaminhaoDAO::selectId($row["tip_cam_id"]),
                CidadeDAO::getById($row["cid_id"]),
                UsuarioDAO::getById($row["usu_id"])
            );
        }

        return $orcamentos;
    }

    public static function selectId(int $id): ?OrcamentoFrete
    {
        $sql = "
            select orc_fre_id,orc_fre_descricao,orc_fre_data,orc_fre_distancia,orc_fre_peso,orc_fre_valor,
                   orc_fre_entrega,orc_fre_validade,orc_ven_id,rep_id,tip_cam_id,cid_id,usu_id
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
        $row = $result->fetch_assoc();

        return new OrcamentoFrete(
            $row["orc_fre_id"],$row["orc_fre_descricao"],$row["orc_fre_data"],$row["orc_fre_distancia"],$row["orc_fre_peso"],$row["orc_fre_valor"],$row["orc_fre_entrega"],$row["orc_fre_validade"],
            OrcamentoVendaDAO::selectId($row["orc_ven_id"]),
            RepresentacaoDAO::getById($row["rep_id"]),
            TipoCaminhaoDAO::selectId($row["tip_cam_id"]),
            CidadeDAO::getById($row["cid_id"]),
            UsuarioDAO::getById($row["usu_id"])
        );
    }
}