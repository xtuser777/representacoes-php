<?php namespace scr\dao;

use mysqli;
use scr\dao\Banco;
use scr\model\Estado;
use scr\model\Cidade;
use scr\model\Endereco;
use scr\model\Contato;
use scr\model\PessoaFisica;
use scr\model\Funcionario;

class FuncionarioDAO
{
    public static function insert(mysqli $conn, int $tipo, string $admissao, string $demissao, int $pessoa) : int
    {
        $sql = "
            insert into funcionario(fun_tipo,fun_admissao,pf_id) 
            values(?,?,?);
        ";
        $statement = $conn->prepare($sql);
        if (!$statement) {
            echo $conn->error;
            return -10;
        }
        
        $statement->bind_param('isi', $tipo,$admissao,$pessoa);
        $statement->execute();
        
        $ins_id = $statement->insert_id;
        
        return $ins_id;
    }
    
    public static function update(mysqli $conn, int $id, int $tipo, string $admissao, string $demissao, int $pessoa) : int
    {
        $sql = "
            update funcionario 
            set fun_tipo = ?, fun_admissao = ?, pf_id = ? 
            where fun_id = ?;
        ";
        $statement = $conn->prepare($sql);
        if (!$statement) {
            echo $conn->error;
            return -10;
        }
        
        $statement->bind_param('isii', $tipo, $admissao, $pessoa, $id);
        $statement->execute();
        
        $res = $statement->affected_rows;
        
        return $res;
    }
    
    public static function delete(mysqli $conn, int $id) : int
    {
        $sql = "
            delete 
            from funcionario 
            where fun_id = ?;
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
    
    public static function desativar(mysqli $conn, int $id) : int
    {
        $sql = '
            update usuario u 
            inner join funcionario f on f.fun_id = u.fun_id 
            set u.usu_ativo = false, f.fun_demissao = now() 
            where u.usu_id = ?;
        ';
        $statement = $conn->prepare($sql);
        if (!$statement) {
            echo $conn->error;
            return -10;
        }
        
        $statement->bind_param('i', $id);
        $statement->execute();
        
        $res = mysqli_stmt_affected_rows($statement);
        
        return $res;
    }
    
    public static function reativar(mysqli $conn, int $id) : int
    {
        $sql = '
            update usuario u 
            inner join funcionario f on f.fun_id = u.fun_id 
            set u.usu_ativo = true, f.fun_demissao = null 
            where u.usu_id = ?;
        ';
        $statement = $conn->prepare($sql);
        if (!$statement) {
            echo $conn->error;
            return -10;
        }
        
        $statement->bind_param('i', $id);
        $statement->execute();
        
        $res = mysqli_stmt_affected_rows($statement);
        
        return $res;
    }

    public static function getById(mysqli $conn, int $id) : Funcionario
    {
        $sql = "
            select e.est_id,e.est_nome,e.est_sigla,
                   c.cid_id,c.cid_nome,
                   en.end_id,en.end_rua,en.end_numero,en.end_bairro,en.end_complemento,en.end_cep,
                   ct.ctt_id,ct.ctt_telefone,ct.ctt_celular,ct.ctt_email,
                   pf.pf_id,pf.pf_nome,pf.pf_rg,pf.pf_cpf,pf.pf_nascimento,
                   f.fun_id,f.fun_tipo,f.fun_admissao,f.fun_demissao
            from funcionario f 
            inner join pessoa_fisica pf on pf.pf_id = f.pf_id
            inner join contato ct on ct.ctt_id = pf.ctt_id
            inner join endereco en on en.end_id = ct.end_id
            inner join cidade c on c.cid_id = en.cid_id
            inner join estado e on e.est_id = c.est_id
            where f.fun_id = ?;
        ";
        $st = $conn->prepare($sql);
        if (!$st) {
            echo $conn->error;
            return null;
        }
        
        $st->bind_param('i', $id);
        $st->execute();
        
        if (!($result = $st->get_result()) || $result->num_rows == 0) {
            echo $conn->error;
            return null;
        }
        $row = $result->fetch_assoc();
        $f = new Funcionario(
            $row['fun_id'], $row['fun_tipo'], $row['fun_admissao'], $row['fun_demissao'] != null ? $row['fun_demissao'] : "",
            new PessoaFisica(
                $row['pf_id'], $row['pf_nome'], $row['pf_rg'], $row['pf_cpf'], $row['pf_nascimento'],
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
        
        return $f;
    }
}
