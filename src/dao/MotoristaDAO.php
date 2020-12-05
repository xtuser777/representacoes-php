<?php


namespace scr\dao;


use mysqli_result;
use scr\model\Cidade;
use scr\model\Contato;
use scr\model\DadosBancarios;
use scr\model\Endereco;
use scr\model\Estado;
use scr\model\Motorista;
use scr\model\PessoaFisica;
use scr\util\Banco;

class MotoristaDAO
{
    public static function insert(string $cadastro, string $cnh, int $pessoa, int $dadosBancarios): int
    {
        $sql = "
            insert into motorista (mot_cadastro, mot_cnh, pf_id, dad_ban_id)
            values (?,?,?,?);
        ";

        if (!Banco::getInstance()->prepareStatement($sql))
            return -10;

        if (!Banco::getInstance()->addParameters("ssii", [ $cadastro, $cnh, $pessoa, $dadosBancarios ]))
            return -10;

        if (!Banco::getInstance()->executeStatement())
            return -10;

        return Banco::getInstance()->getLastInsertId();
    }

    public static function update(int $id, string $cnh): int
    {
        $sql = "
            update motorista 
            set mot_cnh = ?
            where mot_id = ?;
        ";

        if (!Banco::getInstance()->prepareStatement($sql))
            return -10;

        if (!Banco::getInstance()->addParameters("siii", [ $cnh, $id ]))
            return -10;

        if (!Banco::getInstance()->executeStatement())
            return -10;

        return Banco::getInstance()->getAffectedRows();
    }

    public static function delete(int $id): int
    {
        $sql = "
            delete
            from motorista 
            where mot_id = ?;
        ";

        if (!Banco::getInstance()->prepareStatement($sql))
            return -10;

        if (!Banco::getInstance()->addParameters("i", [ $id ]))
            return -10;

        if (!Banco::getInstance()->executeStatement())
            return -10;

        return Banco::getInstance()->getAffectedRows();
    }

    private static function rowToObject(array $row): Motorista
    {
        return new Motorista(
            $row["mot_id"], $row["mot_cadastro"], $row["mot_cnh"],
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
        );
    }

    private static function resultToObject(mysqli_result $result): ?Motorista
    {
        if (!$result || $result->num_rows === 0)
            return null;

        $row = $result->fetch_assoc();

        return self::rowToObject($row);
    }

    private static function resultToList(mysqli_result $result): array
    {
        if (!$result || $result->num_rows === 0)
            return [];

        $motoristas = [];
        while ($row = $result->fetch_assoc()) {
            $motoristas[] = self::rowToObject($row);
        }

        return $motoristas;
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
                   m.mot_id,m.mot_cadastro,m.mot_cnh
            from motorista m 
            inner join dados_bancarios db on db.dad_ban_id = m.dad_ban_id
            inner join pessoa_fisica pf on pf.pf_id = m.pf_id
            inner join contato ct on ct.ctt_id = pf.ctt_id
            inner join endereco en on en.end_id = ct.end_id
            inner join cidade c on c.cid_id = en.cid_id
            inner join estado e on e.est_id = c.est_id
            order by m.mot_id;
        ";

        return self::resultToList(Banco::getInstance()->getResultQuery($sql));
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
                   m.mot_id,m.mot_cadastro,m.mot_cnh
            from motorista m 
            inner join dados_bancarios db on db.dad_ban_id = m.dad_ban_id
            inner join pessoa_fisica pf on pf.pf_id = m.pf_id
            inner join contato ct on ct.ctt_id = pf.ctt_id
            inner join endereco en on en.end_id = ct.end_id
            inner join cidade c on c.cid_id = en.cid_id
            inner join estado e on e.est_id = c.est_id
            where pf.pf_nome like ?
            or ct.ctt_email like ?
            order by m.mot_id;
        ";

        if (!Banco::getInstance()->prepareStatement($sql))
            return [];

        $filtro = "%$key%";
        if (!Banco::getInstance()->addParameters("ss", [ $filtro, $filtro ]))
            return [];

        if (!Banco::getInstance()->executeStatement())
            return [];

        return self::resultToList(Banco::getInstance()->getResult());
    }

    public static function selectCad(string $cad): array
    {
        $sql = "
            select e.est_id,e.est_nome,e.est_sigla,
                   c.cid_id,c.cid_nome,c.est_id,
                   en.end_id,en.end_rua,en.end_numero,en.end_bairro,en.end_complemento,en.end_cep,en.cid_id,
                   ct.ctt_id,ct.ctt_telefone,ct.ctt_celular,ct.ctt_email,ct.end_id,
                   pf.pf_id,pf.pf_nome,pf.pf_rg,pf.pf_cpf,pf.pf_nascimento,
                   db.dad_ban_id,db.dad_ban_banco,db.dad_ban_agencia,db.dad_ban_conta,db.dad_ban_tipo,
                   m.mot_id,m.mot_cadastro,m.mot_cnh
            from motorista m 
            inner join dados_bancarios db on db.dad_ban_id = m.dad_ban_id
            inner join pessoa_fisica pf on pf.pf_id = m.pf_id
            inner join contato ct on ct.ctt_id = pf.ctt_id
            inner join endereco en on en.end_id = ct.end_id
            inner join cidade c on c.cid_id = en.cid_id
            inner join estado e on e.est_id = c.est_id
            where m.mot_cadastro = ?
            order by m.mot_id;
        ";

        if (!Banco::getInstance()->prepareStatement($sql))
            return [];

        if (!Banco::getInstance()->addParameters("s", [ $cad ]))
            return [];

        if (!Banco::getInstance()->executeStatement())
            return [];

        return self::resultToList(Banco::getInstance()->getResult());
    }

    public static function selectKeyCad(string $key, string $cad): array
    {
        $sql = "
            select e.est_id,e.est_nome,e.est_sigla,
                   c.cid_id,c.cid_nome,c.est_id,
                   en.end_id,en.end_rua,en.end_numero,en.end_bairro,en.end_complemento,en.end_cep,en.cid_id,
                   ct.ctt_id,ct.ctt_telefone,ct.ctt_celular,ct.ctt_email,ct.end_id,
                   pf.pf_id,pf.pf_nome,pf.pf_rg,pf.pf_cpf,pf.pf_nascimento,
                   db.dad_ban_id,db.dad_ban_banco,db.dad_ban_agencia,db.dad_ban_conta,db.dad_ban_tipo,
                   m.mot_id,m.mot_cadastro,m.mot_cnh
            from motorista m 
            inner join dados_bancarios db on db.dad_ban_id = m.dad_ban_id
            inner join pessoa_fisica pf on pf.pf_id = m.pf_id
            inner join contato ct on ct.ctt_id = pf.ctt_id
            inner join endereco en on en.end_id = ct.end_id
            inner join cidade c on c.cid_id = en.cid_id
            inner join estado e on e.est_id = c.est_id
            where (pf.pf_nome like ?
            or ct.ctt_email like ?)
            and m.mot_cadastro = ?
            order by m.mot_id;
        ";

        if (!Banco::getInstance()->prepareStatement($sql))
            return [];

        $filtro = "%$key%";
        if (!Banco::getInstance()->addParameters("sss", [ $filtro, $filtro, $cad ]))
            return [];

        if (!Banco::getInstance()->executeStatement())
            return [];

        return self::resultToList(Banco::getInstance()->getResult());
    }

    public static function selectId(int $id): ?Motorista
    {
        $sql = "
            select e.est_id,e.est_nome,e.est_sigla,
                   c.cid_id,c.cid_nome,c.est_id,
                   en.end_id,en.end_rua,en.end_numero,en.end_bairro,en.end_complemento,en.end_cep,en.cid_id,
                   ct.ctt_id,ct.ctt_telefone,ct.ctt_celular,ct.ctt_email,ct.end_id,
                   pf.pf_id,pf.pf_nome,pf.pf_rg,pf.pf_cpf,pf.pf_nascimento,
                   db.dad_ban_id,db.dad_ban_banco,db.dad_ban_agencia,db.dad_ban_conta,db.dad_ban_tipo,
                   m.mot_id,m.mot_cadastro,m.mot_cnh
            from motorista m 
            inner join dados_bancarios db on db.dad_ban_id = m.dad_ban_id
            inner join pessoa_fisica pf on pf.pf_id = m.pf_id
            inner join contato ct on ct.ctt_id = pf.ctt_id
            inner join endereco en on en.end_id = ct.end_id
            inner join cidade c on c.cid_id = en.cid_id
            inner join estado e on e.est_id = c.est_id
            where m.mot_id = ?;
        ";

        if (!Banco::getInstance()->prepareStatement($sql))
            return null;

        if (!Banco::getInstance()->addParameters("i", [ $id ]))
            return null;

        if (!Banco::getInstance()->executeStatement())
            return null;

        return self::resultToObject(Banco::getInstance()->getResult());
    }
}