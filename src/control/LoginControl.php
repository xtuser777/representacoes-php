<?php namespace scr\control;

use scr\util\Banco;
use scr\model\Usuario;

class LoginControl
{
    public function autenticar(string $login, string $senha) 
    {
        $db = Banco::getInstance();
        $db->open();
        /** @var Usuario $u */
        $u = Usuario::autenticar($db->getConnection(), $login, $senha);
        $db->getConnection()->close();

        if ($u == null) { return json_encode(null); }
        
        $_SESSION['USER_ID'] = ''.$u->getId();
        $_SESSION['USER_LOGIN'] = $login;
        $_SESSION['USER_LEVEL'] = ''.$u->getNivel()->getId();
        
        return json_encode($u->jsonSerialize());
    }
}
