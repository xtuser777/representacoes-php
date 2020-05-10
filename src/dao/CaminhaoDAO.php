<?php namespace scr\dao;

use mysqli_result;
use mysqli_stmt;
use scr\model\Caminhao;
use scr\model\Cidade;
use scr\model\Contato;
use scr\model\DadosBancarios;
use scr\model\Endereco;
use scr\model\Estado;
use scr\model\Motorista;
use scr\model\PessoaFisica;
use scr\model\TipoCaminhao;
use scr\util\Banco;

class CaminhaoDAO
{
    public static function insert(string $placa, string $marca,string $modelo, string $anoFabricacao, string $anoModelo, int $tipo, int $proprietario): int
    {
        $sql = "
            insert into caminhao(cam_placa,cam_marca,cam_modelo,cam_ano_fabricacao,cam_ano_modelo,tip_cam_id,mot_id)
            values(?,?,?,?,?,?,?);
        ";
        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }
        $stmt->bind_param("sssssii", $placa, $marca, $modelo, $anoFabricacao, $anoModelo, $tipo, $proprietario);
        if (!$stmt->execute()) {
            echo $stmt->error;
            return -10;
        }

        return $stmt->insert_id;
    }

    public static function update(int $id, string $placa, string $marca,string $modelo, string $anoFabricacao, string $anoModelo, int $tipo, int $proprietario): int
    {
        $sql = "
            update caminhao
            set cam_placa = ?,cam_marca = ?,cam_modelo = ?,cam_ano_fabricacao = ?,cam_ano_modelo = ?,tip_cam_id = ?,mot_id = ?
            where cam_id = ?;
        ";
        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }
        $stmt->bind_param("sssssiii", $placa, $marca, $modelo, $anoFabricacao, $anoModelo, $tipo, $proprietario, $id);
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
            from caminhao
            where cam_id = ?;
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
            select e.est_id,e.est_nome,e.est_sigla,
                   c.cid_id,c.cid_nome,c.est_id,
                   en.end_id,en.end_rua,en.end_numero,en.end_bairro,en.end_complemento,en.end_cep,en.cid_id,
                   ct.ctt_id,ct.ctt_telefone,ct.ctt_celular,ct.ctt_email,ct.end_id,
                   pf.pf_id,pf.pf_nome,pf.pf_rg,pf.pf_cpf,pf.pf_nascimento,
                   db.dad_ban_id,db.dad_ban_banco,db.dad_ban_agencia,db.dad_ban_conta,db.dad_ban_tipo,
                   m.mot_id,m.mot_cadastro,
                   tc.tip_cam_id,tc.tip_cam_descricao,tc.tip_cam_eixos,tc.tip_cam_capacidade,
                   cm.cam_id,cm.cam_placa,cm.cam_marca,cm.cam_modelo,cm.cam_ano_fabricacao,cm.cam_ano_modelo
            from caminhao cm 
            inner join tipo_caminhao tc on tc.tip_cam_id = cm.tip_cam_id
            inner join motorista m on m.mot_id = cm.mot_id
            inner join dados_bancarios db on db.dad_ban_id = m.dad_ban_id
            inner join pessoa_fisica pf on pf.pf_id = m.pf_id
            inner join contato ct on ct.ctt_id = pf.ctt_id
            inner join endereco en on en.end_id = ct.end_id
            inner join cidade c on c.cid_id = en.cid_id
            inner join estado e on e.est_id = c.est_id
            order by cm.cam_id;
        ";
        /** @var $result mysqli_result */
        $result = Banco::getInstance()->getConnection()->query($sql);
        if (!$result || $result->num_rows <= 0) {
            echo Banco::getInstance()->getConnection()->error;
            return array();
        }
        $caminhoes = [];
        while ($row = $result->fetch_assoc()) {
            $caminhoes[] = new Caminhao(
                $row["cam_id"], $row["cam_placa"], $row["cam_marca"], $row["cam_modelo"], $row["cam_ano_fabricacao"], $row["cam_ano_modelo"],
                new TipoCaminhao (
                    $row["tip_cam_id"], $row["tip_cam_descricao"], $row["tip_cam_eixos"], $row["tip_cam_capacidade"]
                ),
                new Motorista(
                    $row["mot_id"], $row["mot_cadastro"],
                    new PessoaFisica (
                        $row['pf_id'], $row['pf_nome'], $row['pf_rg'], $row['pf_cpf'], $row['pf_nascimento'],
                        new Contato (
                            $row['ctt_id'], $row['ctt_telefone'], $row['ctt_celular'], $row['ctt_email'],
                            new Endereco (
                                $row['end_id'], $row['end_rua'], $row['end_numero'], $row['end_bairro'], $row['end_complemento'], $row['end_cep'],
                                new Cidade (
                                    $row['cid_id'],$row['cid_nome'],
                                    new Estado (
                                        $row['est_id'],$row['est_nome'],$row['est_sigla']
                                    )
                                )
                            )
                        )
                    ),
                    new DadosBancarios(
                        $row["dad_ban_id"], $row["dad_ban_banco"], $row["dad_ban_agencia"], $row["dad_ban_conta"], $row["dad_ban_tipo"]
                    )
                )
            );
        }

        return $caminhoes;
    }

    public static function selectKey(string $key): array
    {
        $sql = "
            select e.est_id,e.est_nome,e.est_sigla,
                   c.cid_id,c.cid_nome,c.est_id,
                   en.end_id,en.end_rua,en.end_numero,en.end_bairro,en.end_complemento,en.end_cep,en.cid_id,
                   ct.ctt_id,ct.ctt_telefone,ct.ctt_celular,ct.ctt_email,ct.end_id,
                   pf.pf_id,pf.pf_nome,pf.pf_rg,pf.pf_cpf,pf.pf_nascimento,
                   db.dad_ban_id,db.dad_ban_banco,db.dad_ban_agencia,db.dad_ban_conta,db.dad_ban_tipo,
                   m.mot_id,m.mot_cadastro,
                   tc.tip_cam_id,tc.tip_cam_descricao,tc.tip_cam_eixos,tc.tip_cam_capacidade,
                   cm.cam_id,cm.cam_placa,cm.cam_marca,cm.cam_modelo,cm.cam_ano_fabricacao,cm.cam_ano_modelo
            from caminhao cm 
            inner join tipo_caminhao tc on tc.tip_cam_id = cm.tip_cam_id
            inner join motorista m on m.mot_id = cm.mot_id
            inner join dados_bancarios db on db.dad_ban_id = m.dad_ban_id
            inner join pessoa_fisica pf on pf.pf_id = m.pf_id
            inner join contato ct on ct.ctt_id = pf.ctt_id
            inner join endereco en on en.end_id = ct.end_id
            inner join cidade c on c.cid_id = en.cid_id
            inner join estado e on e.est_id = c.est_id
            where cm.cam_marca like ?
            or cm.cam_modelo like ?
            order by cm.cam_id;
        ";
        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return array();
        }
        $filter = "%".$key."%";
        $stmt->bind_param("ss", $filter, $filter);
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
        $caminhoes = [];
        while ($row = $result->fetch_assoc()) {
            $caminhoes[] = new Caminhao(
                $row["cam_id"], $row["cam_placa"], $row["cam_marca"], $row["cam_modelo"], $row["cam_ano_fabricacao"], $row["cam_ano_modelo"],
                new TipoCaminhao (
                    $row["tip_cam_id"], $row["tip_cam_descricao"], $row["tip_cam_eixos"], $row["tip_cam_capacidade"]
                ),
                new Motorista(
                    $row["mot_id"], $row["mot_cadastro"],
                    new PessoaFisica (
                        $row['pf_id'], $row['pf_nome'], $row['pf_rg'], $row['pf_cpf'], $row['pf_nascimento'],
                        new Contato (
                            $row['ctt_id'], $row['ctt_telefone'], $row['ctt_celular'], $row['ctt_email'],
                            new Endereco (
                                $row['end_id'], $row['end_rua'], $row['end_numero'], $row['end_bairro'], $row['end_complemento'], $row['end_cep'],
                                new Cidade (
                                    $row['cid_id'],$row['cid_nome'],
                                    new Estado (
                                        $row['est_id'],$row['est_nome'],$row['est_sigla']
                                    )
                                )
                            )
                        )
                    ),
                    new DadosBancarios(
                        $row["dad_ban_id"], $row["dad_ban_banco"], $row["dad_ban_agencia"], $row["dad_ban_conta"], $row["dad_ban_tipo"]
                    )
                )
            );
        }

        return $caminhoes;
    }

    public static function selectId(int $id): ?Caminhao
    {
        $sql = "
            select e.est_id,e.est_nome,e.est_sigla,
                   c.cid_id,c.cid_nome,c.est_id,
                   en.end_id,en.end_rua,en.end_numero,en.end_bairro,en.end_complemento,en.end_cep,en.cid_id,
                   ct.ctt_id,ct.ctt_telefone,ct.ctt_celular,ct.ctt_email,ct.end_id,
                   pf.pf_id,pf.pf_nome,pf.pf_rg,pf.pf_cpf,pf.pf_nascimento,
                   db.dad_ban_id,db.dad_ban_banco,db.dad_ban_agencia,db.dad_ban_conta,db.dad_ban_tipo,
                   m.mot_id,m.mot_cadastro,
                   tc.tip_cam_id,tc.tip_cam_descricao,tc.tip_cam_eixos,tc.tip_cam_capacidade,
                   cm.cam_id,cm.cam_placa,cm.cam_marca,cm.cam_modelo,cm.cam_ano_fabricacao,cm.cam_ano_modelo
            from caminhao cm 
            inner join tipo_caminhao tc on tc.tip_cam_id = cm.tip_cam_id
            inner join motorista m on m.mot_id = cm.mot_id
            inner join dados_bancarios db on db.dad_ban_id = m.dad_ban_id
            inner join pessoa_fisica pf on pf.pf_id = m.pf_id
            inner join contato ct on ct.ctt_id = pf.ctt_id
            inner join endereco en on en.end_id = ct.end_id
            inner join cidade c on c.cid_id = en.cid_id
            inner join estado e on e.est_id = c.est_id
            where cm.cam_id = ?;
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

        return new Caminhao(
            $row["cam_id"], $row["cam_placa"], $row["cam_marca"], $row["cam_modelo"], $row["cam_ano_fabricacao"], $row["cam_ano_modelo"],
            new TipoCaminhao (
                $row["tip_cam_id"], $row["tip_cam_descricao"], $row["tip_cam_eixos"], $row["tip_cam_capacidade"]
            ),
            new Motorista(
                $row["mot_id"], $row["mot_cadastro"],
                new PessoaFisica (
                    $row['pf_id'], $row['pf_nome'], $row['pf_rg'], $row['pf_cpf'], $row['pf_nascimento'],
                    new Contato (
                        $row['ctt_id'], $row['ctt_telefone'], $row['ctt_celular'], $row['ctt_email'],
                        new Endereco (
                            $row['end_id'], $row['end_rua'], $row['end_numero'], $row['end_bairro'], $row['end_complemento'], $row['end_cep'],
                            new Cidade (
                                $row['cid_id'],$row['cid_nome'],
                                new Estado (
                                    $row['est_id'],$row['est_nome'],$row['est_sigla']
                                )
                            )
                        )
                    )
                ),
                new DadosBancarios(
                    $row["dad_ban_id"], $row["dad_ban_banco"], $row["dad_ban_agencia"], $row["dad_ban_conta"], $row["dad_ban_tipo"]
                )
            )
        );
    }
}