<?php namespace scr\dao;

use mysqli;
use scr\dao\Banco;
use scr\model\Estado;
use scr\model\Cidade;
use scr\model\Endereco;
use scr\model\Contato;
use scr\model\PessoaFisica;

class PessoaFisicaDAO 
{
    public static function insert(mysqli $conn, string $nome, string $rg, string $cpf, string $nascimento, int $contato) : int
    {
        $sql = "
            insert into pessoa_fisica(pf_nome,pf_rg,pf_cpf,pf_nascimento,ctt_id) 
            values(?,?,?,?,?);
        ";
        $statement = $conn->prepare($sql);
        if (!$statement) {
            echo $conn->error;
            return -10;
        }

        $statement->bind_param('ssssi', $nome, $rg, $cpf, $nascimento, $contato);
        $statement->execute();
        
        $ins_id = $statement->insert_id;

        return $ins_id;
    }
    
    public static function update(mysqli $conn, int $id, string $nome, string $rg, string $cpf, string $nascimento, int $contato) : int
    {
        $sql = "
            update pessoa_fisica 
            set pf_nome = ?, pf_rg = ?, pf_cpf = ?, pf_nascimento = ?, ctt_id = ? 
            where pf_id = ?;
        ";
        $statement = $conn->prepare($sql);
        if (!$statement) {
            echo $conn->error;
            return -10;
        }
        
        $statement->bind_param('ssssii', $nome, $rg, $cpf, $nascimento, $contato, $id);
        $statement->execute();
        
        $res = $statement->affected_rows;

        return $res;
    }
    
    public static function delete(mysqli $conn, int $id) : int
    {
        $sql = "
            delete 
            from pessoa_fisica 
            where pf_id = ?;
        ";
        $statement = $conn->prepare($sql);
        if (!$statement) {
            echo $conn->error;
            return -10;
        }
        
        $statement->bind_param('i', $id);
        $statement->execute();
        
        $res = $statement->affected_rows;
        
        return $res;
    }
    
    public static function getById(mysqli $conn, int $id) : ?PessoaFisica
    {
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
        $st = $conn->prepare($sql);
        if (!$st) {
            echo $conn->error;
            return null;
        }
        
        $st->bind_param('i', $id);
        $st->execute();
        
        if (!($result = $st->get_result()) || $result->num_rows == 0) {
            $conn->error;
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
    
    public static function countCpf(mysqli $conn, string $cpf) 
    {
        $sql = "
            select count(pf_id) as cnt 
            from pessoa_fisica 
            where pf_cpf = ?;
        ";
        $st = $conn->prepare($sql);
        if (!$st) {
            echo $conn->error;
            return -10;
        }
        
        $st->bind_param('s', $cpf);
        $st->execute();
        
        if (!($result = $st->get_result()) || $result->num_rows == 0) {
            echo $conn->error;
            return -10;
        }
        $row = $result->fetch_assoc();
        
        $cnt = (int) $row['cnt'];
        
        return $cnt;
    }
}