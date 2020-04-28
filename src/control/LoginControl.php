<?php namespace scr\control;

use scr\util\Banco;
use scr\model\Usuario;

class LoginControl
{
    public function autenticar(string $login, string $senha) 
    {
        $user = null;
        if (Banco::getInstance()->open())
        {
            $u = Usuario::autenticar($login, $senha);
            Banco::getInstance()->getConnection()->close();
            if (!$u) return json_encode(null);

            $_SESSION['USER_ID'] = ''.$u->getId();
            $_SESSION['USER_LOGIN'] = $login;
            $_SESSION['USER_LEVEL'] = ''.$u->getNivel()->getId();

            $user = $u->jsonSerialize();
        }
        
        return json_encode($user);
    }
}
