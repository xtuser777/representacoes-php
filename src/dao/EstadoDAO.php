<?php namespace scr\dao;

use mysqli;
use scr\util\Banco;
use scr\model\Estado;

class EstadoDAO 
{
    public static function getById(int $id)
    {
        if (!Banco::getInstance()->getConnection()) return -10;

        $sql = "
            select est_id,est_nome,est_sigla 
            from estado 
            where est_id = ?;
        ";
        $st = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$st) {
            return null;
        }
        
        $st->bind_param('i', $id);
        $st->execute();
        
        if (!($result = $st->get_result()) || $result->num_rows == 0) {
            echo $st->error;
            return null;
        }
        $row = $result->fetch_assoc();

        $e = new Estado(
            $row['est_id'], $row['est_nome'], $row['est_sigla']
        );
        
        return $e;
    }
    
    public static function getAll()
    {
        if (!Banco::getInstance()->getConnection()) return -10;

        $sql = "
            SELECT est_id,est_nome,est_sigla 
            FROM estado;
        ";
        $st = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$st) {
            echo Banco::getInstance()->getConnection()->error;
            return array();
        }
        
        $st->execute();

        if (!($result = $st->get_result()) || $result->num_rows == 0) {
            echo $st->error;
            return array();
        }
        
        $estados = [];
        for ($i = 0; $i < $result->num_rows; $i++)
        {
            $row = $result->fetch_assoc();
            $estados[] = new Estado(
                $row['est_id'],
                $row['est_nome'],
                $row['est_sigla']
            );
        }
        
        return $estados;
    }
}
