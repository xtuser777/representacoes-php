<?php namespace scr\dao;

use scr\dao\Banco;
use mysqli;
use scr\model\Estado;
use scr\model\Cidade;
use scr\model\Endereco;
use scr\model\Contato;
use scr\model\Parametrizacao;
use scr\model\PessoaJuridica;

class ParametrizacaoDAO
{
    public static function insert(mysqli $conn, string $logotipo, int $pessoa) : int
    {
        $sql = '
            insert into parametrizacao(par_id, par_logotipo, pj_id)
            values(1,?,?);
        ';
        $statement = $conn->prepare($sql);
        if (!$statement) {
            echo $conn->error;
            return -10;
        }

        $statement->bind_param('si', $logotipo, $pessoa);
        $statement->execute();

        $id = $statement->insert_id;

        return $id;
    }

    public static function update(mysqli $conn, string $logotipo, int $pessoa) : int
    {
        $sql = '
            update parametrizacao 
            set par_logotipo = ?, pj_id = ?
            where par_id = 1;
        ';
        $statement = $conn->prepare($sql);
        if (!$statement) {
            echo $conn->error;
            return -10;
        }

        $statement->bind_param('si', $logotipo, $pessoa);
        $statement->execute();

        $res = $statement->affected_rows;

        return $res;
    }

    public static function get(mysqli $conn) : ?Parametrizacao
    {
        $sql = '
            select e.est_id, e.est_nome, e.est_sigla,
                   c.cid_id, c.cid_nome, c.est_id,
                   en.end_id, en.end_rua, en.end_numero, en.end_bairro, en.end_complemento, en.end_cep, en.cid_id,
                   ct.ctt_id, ct.ctt_telefone, ct.ctt_celular, ct.ctt_email, ct.end_id,
                   pj.pj_id, pj.pj_razao_social, pj.pj_nome_fantasia, pj.pj_cnpj, pj.ctt_id,
                   p.par_id, p.par_logotipo, p.pj_id
            from parametrizacao p 
            inner join pessoa_juridica pj on pj.pj_id = p.pj_id
            inner join contato ct on ct.ctt_id = pj.ctt_id
            inner join endereco en on en.end_id = ct.end_id
            inner join cidade c on c.cid_id = en.cid_id
            inner join estado e on e.est_id = c.est_id
            where p.par_id = 1;
        ';
        $statement = $conn->prepare($sql);
        if (!$statement) {
            echo $conn->error;
            return null;
        }

        $statement->execute();

        if (!($result = $statement->get_result()) || $result->num_rows == 0) {
            echo $statement->error;
            return null;
        }
        $row = $result->fetch_assoc();
        $p = new Parametrizacao(
            $row['par_id'], $row['par_logotipo'],
            new PessoaJuridica (
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
            )
        );

        return $p;
    }
}