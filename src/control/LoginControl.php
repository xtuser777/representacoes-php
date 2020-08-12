<?php


namespace scr\control;


use scr\util\Banco;
use scr\model\Usuario;

class LoginControl
{
    public function autenticar(string $login, string $senha) 
    {
        $user = null;
        if (Banco::getInstance()->open()) {
            $u = Usuario::autenticar($login, $senha);
            Banco::getInstance()->getConnection()->close();
            if (!$u) return json_encode(null);

            $user = $u->jsonSerialize();
        }
        
        return $user;
    }
}
