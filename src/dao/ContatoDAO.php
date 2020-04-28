<?php namespace scr\dao;

use mysqli;
use scr\util\Banco;
use scr\model\Estado;
use scr\model\Cidade;
use scr\model\Endereco;
use scr\model\Contato;

class ContatoDAO 
{
    public static function insert(string $telefone, string $celular, string $email, int $endereco) : int
    {
        if (!Banco::getInstance()->getConnection()) return -10;

        $sql = "
            insert into contato(ctt_telefone,ctt_celular,ctt_email,end_id) 
            values(?,?,?,?);
        ";
        $statement = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$statement) return -10;
        
        $statement->bind_param('sssi', $telefone, $celular, $email, $endereco);
        $statement->execute();

        return $statement->insert_id;
    }
    
    public static function update(int $id, string $telefone, string $celular, string $email, int $endereco) : int
    {
        if (!Banco::getInstance()->getConnection()) return -10;

        $sql = "
            update contato 
            set ctt_telefone = ?, ctt_celular = ?, ctt_email = ?, end_id = ? 
            where ctt_id = ?;
        ";
        $statement = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$statement) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }
        
        $statement->bind_param('sssii', $telefone, $celular, $email, $endereco, $id);
        $statement->execute();

        return $statement->affected_rows;
    }
    
    public static function delete(int $id) : int
    {
        if (!Banco::getInstance()->getConnection()) return -10;

        $sql = "
            delete 
            from contato 
            where ctt_id = ?;
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
    
    public static function getById(int $id) : ?Contato
    {
        if (!Banco::getInstance()->getConnection()) return null;

        $sql = "
            select e.est_id,e.est_nome,e.est_sigla,
                   c.cid_id,c.cid_nome,
                   en.end_id,en.end_rua,en.end_numero,en.end_bairro,en.end_complemento,en.end_cep,
                   ct.ctt_id,ct.ctt_telefone,ct.ctt_celular,ct.ctt_email 
            from contato ct 
            inner join endereco en on en.end_id = ct.end_id
            inner join cidade c on c.cid_id = en.cid_id
            inner join estado e on e.est_id = c.est_id
            where ct.ctt_id = ?;
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

        $ct = new Contato(
            $row['ctt_id'], $row['ctt_telefone'], $row['ctt_celular'], $row['ctt_email'],
            new Endereco(
                $row['end_id'], $row['end_rua'], $row['end_numero'], $row['end_bairro'], $row['end_complemento'], $row['end_cep'],
                new Cidade(
                    $row['cid_id'],$row['cid_nome'],
                    new Estado(
                        $row['est_id'],$row['est_nome'],$row['est_sigla']
                    )
                )
            )
        );
        
        return $ct;
    }
}
