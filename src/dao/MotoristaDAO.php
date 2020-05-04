<?php namespace scr\dao;


use mysqli_result;
use mysqli_stmt;
use scr\model\Motorista;
use scr\util\Banco;

class MotoristaDAO
{
    public static function insert(string $cadastro, int $pessoa, int $dadosBancarios): int
    {
        $sql = "
            insert into motorista (mot_cadastro, pf_id, dad_ban_id)
            values (?,?,?);
        ";
        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }
        $stmt->bind_param("sii", $cadastro, $pessoa, $dadosBancarios);
        if (!$stmt->execute()) {
            echo $stmt->error;
            return -10;
        }

        return $stmt->insert_id;
    }

    public static function update(int $id, string $cadastro, int $pessoa, int $dadosBancarios): int
    {
        $sql = "
            update motorista 
            set mot_cadastro = ?, pf_id = ?, dad_ban_id = ?
            where mot_id = ?;
        ";
        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }
        $stmt->bind_param("siii", $cadastro, $pessoa, $dadosBancarios, $id);
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
            from motorista 
            where mot_id = ?;
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
                   m.mot_id,m.mot_cadastro
            from motorista m 
            inner join dados_bancarios db on db.dad_ban_id = m.dad_ban_id
            inner join pessoa_fisica pf on pf.pf_id = m.pf_id
            inner join contato ct on ct.ctt_id = pf.ctt_id
            inner join endereco en on en.end_id = ct.end_id
            inner join cidade c on c.cid_id = en.cid_id
            inner join estado e on e.est_id = c.est_id
            order by m.mot_id;
        ";
        /** @var $result mysqli_result */
        $result = Banco::getInstance()->getConnection()->query($sql);
        if (!$result || $result->num_rows <= 0) {

        }
        $motoristas = [];
        while ($row = $result->fetch_assoc()) {
            $motoristas[] = new Motorista();
        }

        return $motoristas;
    }

    public static function selectKey(string $key): array
    {

    }

    public static function selectCad(string $cad): array
    {

    }

    public static function selectKeyCad(string $key, string $cad): array
    {

    }

    public static function selectId(int $id): ?Motorista
    {

    }
}