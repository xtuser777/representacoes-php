<?php namespace scr\dao;

use mysqli;
use scr\model\Estado;
use scr\model\Cidade;

class CidadeDAO 
{
    public static function getById(mysqli $conn, int $id) : ?Cidade
    {
        $sql = "
            select e.est_id,e.est_nome,e.est_sigla,
                   c.cid_id,c.cid_nome 
            from cidade c 
            inner join estado e on e.est_id = c.est_id
            where c.cid_id = ?;
        ";
        $st = $conn->prepare($sql);
        if (!$st) {
            return null;
        }
        
        $st->bind_param('i', $id);
        $st->execute();
        
        if (!($result = $st->get_result()) || $result->num_rows == 0) {
            $st->close();
            return null;
        }

        $row = $result->fetch_assoc();
        $c = new Cidade(
            $row['cid_id'], $row['cid_nome'],
            new Estado(
                $row['est_id'],$row['est_nome'],$row['est_sigla']
            )
        );

        $st->close();
        
        return $c;
    }
    
    public static function getByEstado(mysqli $conn, int $estado) : array
    {
        $sql = "
            select e.est_id,e.est_nome,e.est_sigla,
                   c.cid_id,c.cid_nome
            from cidade c 
            inner join estado e on e.est_id = c.est_id
            where c.est_id = ?;
        ";
        $st = $conn->prepare($sql);
        if (!$st) {
            return array();
        }
        
        $st->bind_param('i', $estado);
        $st->execute();
        
        if (!($result = $st->get_result()) || $result->num_rows == 0) {
            $st->close();
            return array();
        }

        $cidades = [];
        for ($i = 0; $i < $result->num_rows; $i++) {
            $row = $result->fetch_assoc();
            $cidades[] = new Cidade(
                $row['cid_id'], $row['cid_nome'],
                new Estado(
                    $row['est_id'], $row['est_nome'], $row['est_sigla']
                )
            );
        }
        
        $st->close();
        
        return $cidades;
    }
}
