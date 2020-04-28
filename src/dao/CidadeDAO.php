<?php namespace scr\dao;

use mysqli;
use scr\model\Estado;
use scr\model\Cidade;
use scr\util\Banco;

class CidadeDAO 
{
    public static function getById(int $id) : ?Cidade
    {
        if (!Banco::getInstance()->getConnection()) return null;

        $sql = "
            select e.est_id,e.est_nome,e.est_sigla,
                   c.cid_id,c.cid_nome 
            from cidade c 
            inner join estado e on e.est_id = c.est_id
            where c.cid_id = ?;
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
        $c = new Cidade(
            $row['cid_id'], $row['cid_nome'],
            new Estado(
                $row['est_id'],$row['est_nome'],$row['est_sigla']
            )
        );
        
        return $c;
    }
    
    public static function getByEstado(int $estado) : array
    {
        if (!Banco::getInstance()->getConnection()) return array();

        $sql = "
            select e.est_id,e.est_nome,e.est_sigla,
                   c.cid_id,c.cid_nome
            from cidade c 
            inner join estado e on e.est_id = c.est_id
            where c.est_id = ?;
        ";
        $st = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$st) {
            echo Banco::getInstance()->getConnection()->error;
            return array();
        }
        
        $st->bind_param('i', $estado);
        $st->execute();
        
        if (!($result = $st->get_result()) || $result->num_rows == 0) {
            echo $st->error;
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
        
        return $cidades;
    }
}
