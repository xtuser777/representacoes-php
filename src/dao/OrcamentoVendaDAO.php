<?php namespace scr\dao;

use mysqli_result;
use mysqli_stmt;
use scr\model\OrcamentoVenda;
use scr\util\Banco;

class OrcamentoVendaDAO
{
    public static function insert(string $desc,string $data,string $nomcli,string $doccli,string $telcli,string $celcli,string $emailcli,float $peso,float $valor,string $validade,int $vendedor,int $cliente,int $destino,int $tc,int $autor): int
    {
        $sql = "
            insert into orcamento_venda(orc_ven_descricao,orc_ven_data,orc_ven_nome_cliente,orc_ven_documento_cliente,orc_ven_telefone_cliente,orc_ven_celular_cliente,orc_ven_email_cliente,orc_ven_peso,orc_ven_valor,orc_ven_validade,fun_id,cli_id,cid_id,tip_cam_id,usu_id)
            values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);
        ";
        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }
        $stmt->bind_param("sssssssffsiiiii", $desc,$data,$nomcli,$doccli,$telcli,$celcli,$emailcli,$peso,$valor,$validade,$vendedor,$cliente,$destino,$tc,$autor);
        if (!$stmt->execute()) {
            echo $stmt->error;
            return -10;
        }

        return $stmt->insert_id;
    }

    public static function update(int $id,string $desc,string $data,string $nomcli,string $doccli,string $telcli,string $celcli,string $emailcli,float $peso,float $valor,string $validade,int $vendedor,int $cliente,int $destino,int $tc,int $autor): int
    {
        $sql = "
            update orcamento_venda
            set orc_ven_descricao = ?,orc_ven_data = ?,orc_ven_nome_cliente = ?,orc_ven_documento_cliente = ?,orc_ven_telefone_cliente = ?,orc_ven_celular_cliente = ?,orc_ven_email_cliente = ?,orc_ven_peso = ?,orc_ven_valor = ?,orc_ven_validade = ?,fun_id = ?,cli_id = ?,cid_id = ?,tip_cam_id = ?,usu_id = ?)
            where orc_ven_id = ?;
        ";
        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }
        $stmt->bind_param("sssssssffsiiiiii", $desc,$data,$nomcli,$doccli,$telcli,$celcli,$emailcli,$peso,$valor,$validade,$vendedor,$cliente,$destino,$tc,$autor,$id);
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
            from orcamento_venda
            where orc_ven_id = ?;
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
            select orc_ven_id,orc_ven_descricao,orc_ven_data,
                   orc_ven_nome_cliente,orc_ven_documento_cliente,orc_ven_telefone_cliente,orc_ven_celular_cliente,orc_ven_email_cliente,
                   orc_ven_peso,orc_ven_valor,orc_ven_validade,
                   fun_id,cli_id,cid_id,tip_cam_id,usu_id
            from orcamento_venda
            order by orc_ven_id;
        ";
        /** @var $result mysqli_result */
        $result = Banco::getInstance()->getConnection()->query($sql);
        if (!$result || $result->num_rows <= 0) {
            echo Banco::getInstance()->getConnection()->error;
            return array();
        }
        $orcamentos = [];
        while ($row = $result->fetch_assoc()) {
            $orcamentos[] = new OrcamentoVenda(
                $row["orc_ven_id"],$row["orc_ven_descricao"],$row["orc_ven_data"],$row["orc_ven_nome_cliente"],$row["orc_ven_documento_cliente"],$row["orc_ven_telefone_cliente"],$row["orc_ven_celular_cliente"],$row["orc_ven_email_cliente"],$row["orc_ven_peso"],$row["orc_ven_valor"],$row["orc_ven_validade"],
                FuncionarioDAO::getById($row["fun_id"]),
                ClienteDAO::getById($row["cli_id"]),
                CidadeDAO::getById($row["cid_id"]),
                TipoCaminhaoDAO::selectId($row["tip_cam_id"]),
                UsuarioDAO::getById($row["usu_id"])
            );
        }

        return $orcamentos;
    }

    public static function selectKey(string $key): array
    {
        $sql = "
            select orc_ven_id,orc_ven_descricao,orc_ven_data,
                   orc_ven_nome_cliente,orc_ven_documento_cliente,orc_ven_telefone_cliente,orc_ven_celular_cliente,orc_ven_email_cliente,
                   orc_ven_peso,orc_ven_valor,orc_ven_validade,
                   fun_id,cli_id,cid_id,tip_cam_id,usu_id
            from orcamento_venda ov
            inner join cliente c on c.cli_id = ov.cli_id
            left join cliente_pessoa_fisica cpf on c.cli_id = cpf.cli_id
            left join cliente_pessoa_juridica cpj on c.cli_id = cpj.cli_id
            left join pessoa_fisica pf on cpf.pf_id = pf.pf_id
            left join pessoa_juridica pj on cpj.pj_id = pj.pj_id
            where ov.orc_ven_descricao like ?
            or pf.pf_nome like ?
            or pj.pj_nome_fantasia like ?
            order by ov.orc_ven_id;
        ";
        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return array();
        }
        $filter = "%".$key."%";
        $stmt->bind_param("sss", $filter, $filter, $filter);
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
        $orcamentos = [];
        while ($row = $result->fetch_assoc()) {
            $orcamentos[] = new OrcamentoVenda(
                $row["orc_ven_id"],$row["orc_ven_descricao"],$row["orc_ven_data"],$row["orc_ven_nome_cliente"],$row["orc_ven_documento_cliente"],$row["orc_ven_telefone_cliente"],$row["orc_ven_celular_cliente"],$row["orc_ven_email_cliente"],$row["orc_ven_peso"],$row["orc_ven_valor"],$row["orc_ven_validade"],
                FuncionarioDAO::getById($row["fun_id"]),
                ClienteDAO::getById($row["cli_id"]),
                CidadeDAO::getById($row["cid_id"]),
                TipoCaminhaoDAO::selectId($row["tip_cam_id"]),
                UsuarioDAO::getById($row["usu_id"])
            );
        }

        return $orcamentos;
    }

    public static function selectDate(string $date): array
    {
        $sql = "
            select orc_ven_id,orc_ven_descricao,orc_ven_data,
                   orc_ven_nome_cliente,orc_ven_documento_cliente,orc_ven_telefone_cliente,orc_ven_celular_cliente,orc_ven_email_cliente,
                   orc_ven_peso,orc_ven_valor,orc_ven_validade,
                   fun_id,cli_id,cid_id,tip_cam_id,usu_id
            from orcamento_venda ov
            where ov.orc_ven_data = ?
            order by ov.orc_ven_id;
        ";
        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return array();
        }
        $stmt->bind_param("s", $date);
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
        $orcamentos = [];
        while ($row = $result->fetch_assoc()) {
            $orcamentos[] = new OrcamentoVenda(
                $row["orc_ven_id"],$row["orc_ven_descricao"],$row["orc_ven_data"],$row["orc_ven_nome_cliente"],$row["orc_ven_documento_cliente"],$row["orc_ven_telefone_cliente"],$row["orc_ven_celular_cliente"],$row["orc_ven_email_cliente"],$row["orc_ven_peso"],$row["orc_ven_valor"],$row["orc_ven_validade"],
                FuncionarioDAO::getById($row["fun_id"]),
                ClienteDAO::getById($row["cli_id"]),
                CidadeDAO::getById($row["cid_id"]),
                TipoCaminhaoDAO::selectId($row["tip_cam_id"]),
                UsuarioDAO::getById($row["usu_id"])
            );
        }

        return $orcamentos;
    }

    public static function selectKeyDate(string $key, string $date): array
    {
        $sql = "
            select orc_ven_id,orc_ven_descricao,orc_ven_data,
                   orc_ven_nome_cliente,orc_ven_documento_cliente,orc_ven_telefone_cliente,orc_ven_celular_cliente,orc_ven_email_cliente,
                   orc_ven_peso,orc_ven_valor,orc_ven_validade,
                   fun_id,cli_id,cid_id,tip_cam_id,usu_id
            from orcamento_venda ov
            inner join cliente c on c.cli_id = ov.cli_id
            left join cliente_pessoa_fisica cpf on c.cli_id = cpf.cli_id
            left join cliente_pessoa_juridica cpj on c.cli_id = cpj.cli_id
            left join pessoa_fisica pf on cpf.pf_id = pf.pf_id
            left join pessoa_juridica pj on cpj.pj_id = pj.pj_id
            where (ov.orc_ven_descricao like ?
            or pf.pf_nome like ?
            or pj.pj_nome_fantasia like ?)
            and ov.orc_ven_data = ?
            order by orc_ven_id;
        ";
        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return array();
        }
        $filter = "%".$key."%";
        $stmt->bind_param("ssss", $filter, $filter, $filter, $date);
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
        $orcamentos = [];
        while ($row = $result->fetch_assoc()) {
            $orcamentos[] = new OrcamentoVenda(
                $row["orc_ven_id"],$row["orc_ven_descricao"],$row["orc_ven_data"],$row["orc_ven_nome_cliente"],$row["orc_ven_documento_cliente"],$row["orc_ven_telefone_cliente"],$row["orc_ven_celular_cliente"],$row["orc_ven_email_cliente"],$row["orc_ven_peso"],$row["orc_ven_valor"],$row["orc_ven_validade"],
                FuncionarioDAO::getById($row["fun_id"]),
                ClienteDAO::getById($row["cli_id"]),
                CidadeDAO::getById($row["cid_id"]),
                TipoCaminhaoDAO::selectId($row["tip_cam_id"]),
                UsuarioDAO::getById($row["usu_id"])
            );
        }

        return $orcamentos;
    }

    public static function selectId(int $id): ?OrcamentoVenda
    {
        $sql = "
            select orc_ven_id,orc_ven_descricao,orc_ven_data,
                   orc_ven_nome_cliente,orc_ven_documento_cliente,orc_ven_telefone_cliente,orc_ven_celular_cliente,orc_ven_email_cliente,
                   orc_ven_peso,orc_ven_valor,orc_ven_validade,
                   fun_id,cli_id,cid_id,tip_cam_id,usu_id
            from orcamento_venda
            where orc_ven_id = ?;
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

        return new OrcamentoVenda(
            $row["orc_ven_id"],$row["orc_ven_descricao"],$row["orc_ven_data"],$row["orc_ven_nome_cliente"],$row["orc_ven_documento_cliente"],$row["orc_ven_telefone_cliente"],$row["orc_ven_celular_cliente"],$row["orc_ven_email_cliente"],$row["orc_ven_peso"],$row["orc_ven_valor"],$row["orc_ven_validade"],
            FuncionarioDAO::getById($row["fun_id"]),
            ClienteDAO::getById($row["cli_id"]),
            CidadeDAO::getById($row["cid_id"]),
            TipoCaminhaoDAO::selectId($row["tip_cam_id"]),
            UsuarioDAO::getById($row["usu_id"])
        );
    }
}