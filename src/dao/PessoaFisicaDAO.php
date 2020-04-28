<?php namespace scr\dao;

use mysqli;
use scr\util\Banco;
use scr\model\Estado;
use scr\model\Cidade;
use scr\model\Endereco;
use scr\model\Contato;
use scr\model\PessoaFisica;

class PessoaFisicaDAO 
{
    public static function insert(string $nome, string $rg, string $cpf, string $nascimento, int $contato) : int
    {
        if (!Banco::getInstance()->getConnection()) return -10;

        $sql = "
            insert into pessoa_fisica(pf_nome,pf_rg,pf_cpf,pf_nascimento,ctt_id) 
            values(?,?,?,?,?);
        ";
        $statement = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$statement) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }

        $statement->bind_param('ssssi', $nome, $rg, $cpf, $nascimento, $contato);
        $statement->execute();

        return $statement->insert_id;
    }
    
    public static function update(int $id, string $nome, string $rg, string $cpf, string $nascimento, int $contato) : int
    {
        if (!Banco::getInstance()->getConnection()) return -10;

        $sql = "
            update pessoa_fisica 
            set pf_nome = ?, pf_rg = ?, pf_cpf = ?, pf_nascimento = ?, ctt_id = ? 
            where pf_id = ?;
        ";
        $statement = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$statement) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }
        
        $statement->bind_param('ssssii', $nome, $rg, $cpf, $nascimento, $contato, $id);
        $statement->execute();

        return $statement->affected_rows;
    }
    
    public static function delete(int $id) : int
    {
        if (!Banco::getInstance()->getConnection()) return -10;

        $sql = "
            delete 
            from pessoa_fisica 
            where pf_id = ?;
        ";
        $statement = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$statement) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }
        
        $statement->bind_param('i', $id);
        $statement->execute();

        return $statement->affected_rows;
    }
    
    public static function getById(int $id) : ?PessoaFisica
    {
        if (!Banco::getInstance()->getConnection()) return null;

        $sql = "
            select e.est_id,e.est_nome,e.est_sigla,
                   c.cid_id,c.cid_nome,c.est_id,
                   en.end_id,en.end_rua,en.end_numero,en.end_bairro,en.end_complemento,en.end_cep,en.cid_id,
                   ct.ctt_id,ct.ctt_telefone,ct.ctt_celular,ct.ctt_email,ct.end_id,
                   pf.pf_id,pf.pf_nome,pf.pf_rg,pf.pf_cpf,pf.pf_nascimento,pf.ctt_id 
            from pessoa_fisica pf 
            inner join contato ct on ct.ctt_id = pf.ctt_id
            inner join endereco en on en.end_id = ct.end_id
            inner join cidade c on c.cid_id = en.cid_id
            inner join estado e on e.est_id = c.est_id
            where pf.pf_id = ?;
        ";
        $st = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$st) {
            echo Banco::getInstance()->getConnection()->error;
            return null;
        }
        
        $st->bind_param('i', $id);
        $st->execute();
        
        if (!($result = $st->get_result()) || $result->num_rows == 0) {
            $st->error;
            return null;
        }
        $row = $st->fetch_assoc();

        $pf = new PessoaFisica (
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
        );
        
        return $pf;
    }
    
    public static function countCpf(string $cpf): int
    {
        if (!Banco::getInstance()->getConnection()) return -10;

        $sql = "
            select count(pf_id) as cnt 
            from pessoa_fisica 
            where pf_cpf = ?;
        ";
        $st = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$st) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }
        
        $st->bind_param('s', $cpf);
        $st->execute();
        
        if (!($result = $st->get_result()) || $result->num_rows == 0) {
            echo $st->error;
            return -10;
        }
        $row = $result->fetch_assoc();
        
        $cnt = (int) $row['cnt'];
        
        return $cnt;
    }
}