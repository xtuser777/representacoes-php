<?php namespace scr\dao;

use mysqli;
use scr\util\Banco;
use scr\model\Estado;
use scr\model\Cidade;
use scr\model\Endereco;
use scr\model\Contato;
use scr\model\PessoaJuridica;

class PessoaJuridicaDAO
{
    public static function insert(string $razaoSocial, string $nomeFantasia, string $cnpj, int $contato) : int
    {
        if (!Banco::getInstance()->getConnection()) return -10;

        $sql = '
            insert into pessoa_juridica(pj_razao_social,pj_nome_fantasia,pj_cnpj,ctt_id) 
            values(?,?,?,?);
        ';
        $statement = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$statement) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }

        $statement->bind_param('sssi', $razaoSocial, $nomeFantasia, $cnpj, $contato);
        $statement->execute();

        return $statement->insert_id;
    }

    public static function update(int $id, string $razaoSocial, string $nomeFantasia, string $cnpj, int $contato) : int
    {
        if (!Banco::getInstance()->getConnection()) return -10;

        $sql = '
            update pessoa_juridica 
            set pj_razao_social = ?,
                pj_nome_fantasia = ?,
                pj_cnpj = ?,
                ctt_id = ?
            where pj_id = ?;
        ';
        $statement = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$statement) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }

        $statement->bind_param('sssii', $razaoSocial, $nomeFantasia, $cnpj, $contato, $id);
        $statement->execute();

        return $statement->affected_rows;
    }

    public static function delete(int $id) : int
    {
        if (!Banco::getInstance()->getConnection()) return -10;

        $sql = '
            delete 
            from pessoa_juridica 
            where pj_id = ?;
        ';
        $statement = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$statement) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }

        $statement->bind_param('i', $id);
        $statement->execute();

        return $statement->affected_rows;
    }

    public static function getById(int $id) : ?PessoaJuridica
    {
        if (!Banco::getInstance()->getConnection()) return null;

        $sql = '
            select e.est_id, e.est_nome, e.est_sigla,
                   c.cid_id, c.cid_nome, c.est_id,
                   en.end_id, en.end_rua, en.end_numero, en.end_bairro, en.end_complemento, en.end_cep, en.cid_id,
                   ct.ctt_id, ct.ctt_telefone, ct.ctt_celular, ct.ctt_email, ct.end_id,
                   p.pj_id, p.pj_razao_social, p.pj_nome_fantasia, p.pj_cnpj, p.ctt_id
            from pessoa_juridica p 
            inner join contato ct on ct.ctt_id = p.ctt_id
            inner join endereco en on en.end_id = ct.end_id
            inner join cidade c on c.cid_id = en.cid_id
            inner join estado e on e.est_id = c.est_id
            where p.pj_id = ?;
        ';
        $statement = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$statement) {
            echo Banco::getInstance()->getConnection()->error;
            return null;
        }

        $statement->bind_param('i', $id);
        $statement->execute();

        if (!($result = $statement->get_result()) || $result->num_rows == 0) {
            echo $statement->error;
            return null;
        }
        $row = $result->fetch_assoc();

        $pj = new PessoaJuridica (
            $row['pj_id'], $row['pj_razao_social'], $row['pj_nome_fantasia'], $row['pj_cnpj'],
            new Contato (
                $row['ctt_id'], $row['ctt_telefone'], $row['ctt_celular'], $row['ctt_email'],
                new Endereco (
                    $row['end_id'], $row['end_rua'], $row['end_numero'], $row['end_bairro'], $row['end_complemento'], $row['end_cep'],
                    new Cidade (
                        $row['cid_id'], $row['cid_nome'],
                        new Estado (
                            $row['est_id'], $row['est_nome'], $row['est_sigla']
                        )
                    )
                )
            )
        );

        return $pj;
    }

    public static function countCnpj(string $cnpj) : int
    {
        if (!Banco::getInstance()->getConnection()) return -10;

        $sql = '
            select count(pj_id) as cnt 
            from pessoa_juridica 
            where pj_cnpj = ?;
        ';
        $statement = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$statement) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }

        $statement->bind_param('s', $cnpj);
        $statement->execute();

        $cnt = 0;
        $statement->bind_result($cnt);

        if (!$statement->fetch()) {
            echo $statement->error;
            return -10;
        }

        return $cnt;
    }
}