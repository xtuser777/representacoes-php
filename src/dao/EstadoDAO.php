<?php namespace scr\dao;

use mysqli;
use scr\dao\Banco;
use scr\model\Estado;

class EstadoDAO 
{
    public static function getById(mysqli $conn, int $id)
    {
        $sql = "
            select est_id,est_nome,est_sigla 
            from estado 
            where est_id = ?;
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

        $e = new Estado(
            $row['est_id'], $row['est_nome'], $row['est_sigla']
        );
        
        $st->close();
        
        return $e;
    }
    
    public static function getAll(mysqli $conn) 
    {
        $sql = "
            SELECT est_id,est_nome,est_sigla 
            FROM estado;
        ";
        $st = $conn->prepare($sql);
        if (!$st) {
            return array();
        }
        
        $st->execute();

        if (!($result = $st->get_result()) || $result->num_rows == 0) {
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
