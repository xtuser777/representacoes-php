<?php namespace scr\dao;

use scr\util\Banco;
use scr\model\Estado;
use scr\model\Cidade;
use scr\model\Endereco;
use mysqli;

class EnderecoDAO
{
    public static function insert(string $rua, string $numero, string $bairro, string $complemento, string $cep, int $cidade) : int
    {
        if (!Banco::getInstance()->getConnection()) return -10;

        $sql = "
            insert into endereco(end_rua,end_numero,end_bairro,end_complemento,end_cep,cid_id) 
            values(?,?,?,?,?,?);
        ";
        $statement = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$statement) return -10;
        
        $statement->bind_param('sssssi', $rua, $numero, $bairro, $complemento, $cep, $cidade);
        $statement->execute();

        return $statement->insert_id;
    }
    
    public static function update(int $id, string $rua, string $numero, string $bairro, string $complemento, string $cep, int $cidade) : int
    {
        if(!Banco::getInstance()->getConnection()) return -10;

        $sql = "
            update endereco 
            set end_rua = ?,end_numero = ?,end_bairro = ?,end_complemento = ?,end_cep = ?,cid_id = ? 
            where end_id = ?;
        ";
        $statement = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$statement) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }
        
        $statement->bind_param('sssssii', $rua, $numero, $bairro, $complemento, $cep, $cidade, $id);
        $statement->execute();
        
        return $statement->affected_rows;
    }
    
    public static function delete(int $id) : int
    {
        if (!Banco::getInstance()->getConnection()) return -10;

        $sql = "
            delete 
            from endereco
            where end_id = ?;
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
    
    public static function getById(int $id) : ?Endereco
    {
        if (!Banco::getInstance()->getConnection()) return null;

        $sql = "
            select e.est_id,e.est_nome,e.est_sigla,
                   c.cid_id,c.cid_nome,
                   en.end_id,en.end_rua,en.end_numero,en.end_bairro,en.end_complemento,en.end_cep 
            from endereco en
            inner join cidade c on c.cid_id = en.cid_id
            inner join estato e on e.est_id = c.est_id
            where en.end_id = ?;
        ";
        $st = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$st) {
            echo Banco::getInstance()->getConnection()->error;
            return null;
        }
        
        $st->bind_param('i', $id);
        $st->execute();
        
        if (!($result = $st->get_result()) || $result->num_rows == 0) {
            echo $st->error;
            return null;
        }
        $row = $result->fetch_assoc();

        $e = new Endereco(
            $row['end_id'], $row['end_rua'], $row['end_numero'], $row['end_bairro'], $row['end_complemento'], $row['end_cep'],
            new Cidade(
                $row['cid_id'], $row['cid_nome'],
                new Estado(
                    $row['est_id'], $row['est_nome'], $row['est_sigla']
                )
            )
        );

        return $e;
    }
}
