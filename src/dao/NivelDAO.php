<?php namespace scr\dao;

use mysqli;
use scr\model\Nivel;

class NivelDAO
{
    public static function getById(mysqli $conn, int $id) : ?Nivel
    {
        $sql = "
            select niv_id,niv_descricao
            from nivel 
            where niv_id = ?;
        ";
        $st = $conn->prepare($sql);
        if (!$st) {
            echo $conn->error;
            return null;
        }
        
        $st->bind_param('i', $id);
        $st->execute();
        
        if (!($result = $st->get_result()) || $result->num_rows == 0) {
            return null;
        }
        $row = $result->fetch_assoc();
        $n = new Nivel($row['niv_id'], $row['niv_descricao']);
        
        return $n;
    }
    
    public static function getAll(mysqli $conn) : array
    {
        $sql = "
            select niv_id,niv_descricao 
            from nivel;
         ";
        $st = $conn->prepare($sql);
        if (!$st) {
            echo $conn->error;
            return array();
        }
        
        $st->execute();
        
        if (!($result = $st->get_result()) || $result->num_rows == 0) {
            echo $conn->error;
            return array();
        }
        $niveis = [];
        for ($i = 0; $i < $result->num_rows; $i++) {
            $row = $result->fetch_assoc();
            $niveis[] = new Nivel($row['niv_id'], $row['niv_descricao']);
        }

        return $niveis;
    }
}
