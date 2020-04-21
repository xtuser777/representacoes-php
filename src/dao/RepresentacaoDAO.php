<?php namespace scr\dao;

use mysqli;
use scr\util\Banco;
use scr\model\Cidade;
use scr\model\Contato;
use scr\model\Endereco;
use scr\model\Estado;
use scr\model\PessoaJuridica;
use scr\model\Representacao;

class RepresentacaoDAO
{
    public static function insert(string $cadastro, string $unidade, int $pessoa): int
    {
        if (Banco::getInstance()->getConnection() == null) return -10;

        $sql = '
            insert into representacao(rep_cadastro, rep_unidade, pj_id)
            values(?,?,?);
        ';
        $statement = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$statement) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }

        $statement->bind_param('ssi', $cadastro, $unidade, $pessoa);
        $statement->execute();

        $id = $statement->insert_id;

        return $id;
    }

    public static function update(int $id, string $unidade): int
    {
        if (Banco::getInstance()->getConnection() == null) return -10;

        $sql = '
            update representacao
            set rep_unidade = ? 
            where rep_id = ?;
        ';

        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt)
        {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }

        $stmt->bind_param('si', $unidade, $id);
        $stmt->execute();

        return $stmt->affected_rows;
    }
    
    public static function delete(int $id): int
    {
        if (Banco::getInstance()->getConnection() == null) return -10;

        $sql = '
            delete
            from representacao
            where rep_id = ?;
        ';
        $statement = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$statement) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }

        $statement->bind_param('i', $id);
        $statement->execute();

        $res = $statement->affected_rows;

        return $res;
    }

    public static function getById(int $id): ?Representacao
    {
        if (Banco::getInstance()->getConnection() == null) return null;

        $sql = '
            select e.est_id, e.est_nome, e.est_sigla,
                   c.cid_id, c.cid_nome,
                   en.end_id, en.end_rua, en.end_numero, en.end_bairro, en.end_complemento, en.end_cep,
                   ct.ctt_id, ct.ctt_telefone, ct.ctt_celular, ct.ctt_email,
                   p.pj_id, p.pj_razao_social, p.pj_nome_fantasia, p.pj_cnpj,
                   r.rep_id, r.rep_cadastro, r.rep_unidade
            from representacao r
            inner join pessoa_juridica p on r.pj_id = p.pj_id
            inner join contato ct on p.ctt_id = ct.ctt_id
            inner join endereco en on ct.end_id = en.end_id
            inner join cidade c on en.cid_id = c.cid_id
            inner join estado e on c.est_id = e.est_id
            where r.rep_id = ?;
        ';
        $statement = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$statement) {
            echo Banco::getInstance()->getConnection()->error;
            return null;
        }

        $statement->bind_param('i', $id);
        $statement->execute();

        if (!($result = $statement->get_result()) || $result->num_rows <= 0) {
            echo $statement->error;
            return null;
        }
        $row = $result->fetch_assoc();

        $rep = new Representacao(
            $row['rep_id'], $row['rep_cadastro'], $row['rep_unidade'],
            new PessoaJuridica(
                $row['pj_id'], $row['pj_razao_social'], $row['pj_nome_fantasia'], $row['pj_cnpj'],
                new Contato(
                    $row['ctt_id'], $row['ctt_telefone'], $row['ctt_celular'], $row['ctt_email'],
                    new Endereco(
                        $row['end_id'], $row['end_rua'], $row['end_numero'], $row['end_bairro'], $row['end_complemento'], $row['end_cep'],
                        new Cidade(
                            $row['cid_id'], $row['cid_nome'],
                            new Estado(
                                $row['est_id'], $row['est_nome'], $row['est_sigla']
                            )
                        )
                    )
                )
            )
        );
        
        return $rep;
    }

    public static function getByKey(string $key): array
    {
        if (Banco::getInstance()->getConnection() == null) return array();

        $sql = '
            select e.est_id, e.est_nome, e.est_sigla,
                   c.cid_id, c.cid_nome,
                   en.end_id, en.end_rua, en.end_numero, en.end_bairro, en.end_complemento, en.end_cep,
                   ct.ctt_id, ct.ctt_telefone, ct.ctt_celular, ct.ctt_email,
                   p.pj_id, p.pj_razao_social, p.pj_nome_fantasia, p.pj_cnpj,
                   r.rep_id, r.rep_cadastro, r.rep_unidade
            from representacao r
            inner join pessoa_juridica p on r.pj_id = p.pj_id
            inner join contato ct on p.ctt_id = ct.ctt_id
            inner join endereco en on ct.end_id = en.end_id
            inner join cidade c on en.cid_id = c.cid_id
            inner join estado e on c.est_id = e.est_id
            where p.pj_nome_fantasia like ? or ct.ctt_email like ?;
        ';
        $statement = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$statement) {
            echo Banco::getInstance()->getConnection()->error;
            return array();
        }

        $pkey = '%' . $key . '%';
        $statement->bind_param('ss', $pkey, $pkey);
        $statement->execute();

        if (!($result = $statement->get_result()) || $result->num_rows <= 0) {
            echo $statement->error;
            return array();
        }

        $representacoes = array();
        for ($i = 0; $i < $result->num_rows; $i++) {
            $row = $result->fetch_assoc();
            $representacoes[] = new Representacao(
                $row['rep_id'], $row['rep_cadastro'], $row['rep_unidade'],
                new PessoaJuridica(
                    $row['pj_id'], $row['pj_razao_social'], $row['pj_nome_fantasia'], $row['pj_cnpj'],
                    new Contato(
                        $row['ctt_id'], $row['ctt_telefone'], $row['ctt_celular'], $row['ctt_email'],
                        new Endereco(
                            $row['end_id'], $row['end_rua'], $row['end_numero'], $row['end_bairro'], $row['end_complemento'], $row['end_cep'],
                            new Cidade(
                                $row['cid_id'], $row['cid_nome'],
                                new Estado(
                                    $row['est_id'], $row['est_nome'], $row['est_sigla']
                                )
                            )
                        )
                    )
                )
            );
        }

        return $representacoes;
    }

    public static function getByCad(string $cad): array
    {
        if (Banco::getInstance()->getConnection() == null) return array();

        $sql = '
            select e.est_id, e.est_nome, e.est_sigla,
                   c.cid_id, c.cid_nome,
                   en.end_id, en.end_rua, en.end_numero, en.end_bairro, en.end_complemento, en.end_cep,
                   ct.ctt_id, ct.ctt_telefone, ct.ctt_celular, ct.ctt_email,
                   p.pj_id, p.pj_razao_social, p.pj_nome_fantasia, p.pj_cnpj,
                   r.rep_id, r.rep_cadastro, r.rep_unidade
            from representacao r
            inner join pessoa_juridica p on r.pj_id = p.pj_id
            inner join contato ct on p.ctt_id = ct.ctt_id
            inner join endereco en on ct.end_id = en.end_id
            inner join cidade c on en.cid_id = c.cid_id
            inner join estado e on c.est_id = e.est_id
            where r.rep_cadastro = ?;
        ';
        $statement = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$statement) {
            echo Banco::getInstance()->getConnection()->error;
            return array();
        }

        $statement->bind_param('s', $cad);
        $statement->execute();

        if (!($result = $statement->get_result()) || $result->num_rows <= 0) {
            echo $statement->error;
            return array();
        }

        $representacoes = array();
        for ($i = 0; $i < $result->num_rows; $i++) {
            $row = $result->fetch_assoc();
            $representacoes[] = new Representacao(
                $row['rep_id'], $row['rep_cadastro'], $row['rep_unidade'],
                new PessoaJuridica(
                    $row['pj_id'], $row['pj_razao_social'], $row['pj_nome_fantasia'], $row['pj_cnpj'],
                    new Contato(
                        $row['ctt_id'], $row['ctt_telefone'], $row['ctt_celular'], $row['ctt_email'],
                        new Endereco(
                            $row['end_id'], $row['end_rua'], $row['end_numero'], $row['end_bairro'], $row['end_complemento'], $row['end_cep'],
                            new Cidade(
                                $row['cid_id'], $row['cid_nome'],
                                new Estado(
                                    $row['est_id'], $row['est_nome'], $row['est_sigla']
                                )
                            )
                        )
                    )
                )
            );
        }

        return $representacoes;
    }

    public static function getByKeyCad(string $key, string $cad): array
    {
        if (Banco::getInstance()->getConnection() == null) return array();

        $sql = '
            select e.est_id, e.est_nome, e.est_sigla,
                   c.cid_id, c.cid_nome,
                   en.end_id, en.end_rua, en.end_numero, en.end_bairro, en.end_complemento, en.end_cep,
                   ct.ctt_id, ct.ctt_telefone, ct.ctt_celular, ct.ctt_email,
                   p.pj_id, p.pj_razao_social, p.pj_nome_fantasia, p.pj_cnpj,
                   r.rep_id, r.rep_cadastro, r.rep_unidade
            from representacao r
            inner join pessoa_juridica p on r.pj_id = p.pj_id
            inner join contato ct on p.ctt_id = ct.ctt_id
            inner join endereco en on ct.end_id = en.end_id
            inner join cidade c on en.cid_id = c.cid_id
            inner join estado e on c.est_id = e.est_id
            where (p.pj_nome_fantasia like ? or ct.ctt_email like ?)
            and r.rep_cadastro = ?;
        ';
        $statement = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$statement) {
            echo Banco::getInstance()->getConnection()->error;
            return array();
        }

        $pkey = '%'+$key+'%';
        $statement->bind_param('sss', $pkey, $pkey, $cad);
        $statement->execute();

        if (!($result = $statement->get_result()) || $result->num_rows <= 0) {
            echo $statement->error;
            return array();
        }

        $representacoes = array();
        for ($i = 0; $i < $result->num_rows; $i++) {
            $row = $result->fetch_assoc();
            $representacoes[] = new Representacao(
                $row['rep_id'], $row['rep_cadastro'], $row['rep_unidade'],
                new PessoaJuridica(
                    $row['pj_id'], $row['pj_razao_social'], $row['pj_nome_fantasia'], $row['pj_cnpj'],
                    new Contato(
                        $row['ctt_id'], $row['ctt_telefone'], $row['ctt_celular'], $row['ctt_email'],
                        new Endereco(
                            $row['end_id'], $row['end_rua'], $row['end_numero'], $row['end_bairro'], $row['end_complemento'], $row['end_cep'],
                            new Cidade(
                                $row['cid_id'], $row['cid_nome'],
                                new Estado(
                                    $row['est_id'], $row['est_nome'], $row['est_sigla']
                                )
                            )
                        )
                    )
                )
            );
        }

        return $representacoes;
    }

    public static function getAll(): array
    {
        if (Banco::getInstance()->getConnection() == null) return array();

        $sql = '
            select e.est_id, e.est_nome, e.est_sigla,
                   c.cid_id, c.cid_nome,
                   en.end_id, en.end_rua, en.end_numero, en.end_bairro, en.end_complemento, en.end_cep,
                   ct.ctt_id, ct.ctt_telefone, ct.ctt_celular, ct.ctt_email,
                   p.pj_id, p.pj_razao_social, p.pj_nome_fantasia, p.pj_cnpj,
                   r.rep_id, r.rep_cadastro, r.rep_unidade
            from representacao r
            inner join pessoa_juridica p on r.pj_id = p.pj_id
            inner join contato ct on p.ctt_id = ct.ctt_id
            inner join endereco en on ct.end_id = en.end_id
            inner join cidade c on en.cid_id = c.cid_id
            inner join estado e on c.est_id = e.est_id;
        ';
        $statement = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$statement) {
            echo Banco::getInstance()->getConnection()->error;
            return array();
        }

        $statement->execute();

        if (!($result = $statement->get_result()) || $result->num_rows <= 0) {
            echo $statement->error;
            return array();
        }

        $representacoes = array();
        for ($i = 0; $i < $result->num_rows; $i++) {
            $row = $result->fetch_assoc();
            $representacoes[] = new Representacao(
                $row['rep_id'], $row['rep_cadastro'], $row['rep_unidade'],
                new PessoaJuridica(
                    $row['pj_id'], $row['pj_razao_social'], $row['pj_nome_fantasia'], $row['pj_cnpj'],
                    new Contato(
                        $row['ctt_id'], $row['ctt_telefone'], $row['ctt_celular'], $row['ctt_email'],
                        new Endereco(
                            $row['end_id'], $row['end_rua'], $row['end_numero'], $row['end_bairro'], $row['end_complemento'], $row['end_cep'],
                            new Cidade(
                                $row['cid_id'], $row['cid_nome'],
                                new Estado(
                                    $row['est_id'], $row['est_nome'], $row['est_sigla']
                                )
                            )
                        )
                    )
                )
            );
        }

        return $representacoes;
    }
}