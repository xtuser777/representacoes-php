<?php namespace scr\control;

use scr\util\Banco;
use scr\model\Funcionario;
use scr\model\Usuario;
use scr\model\Endereco;
use scr\model\Contato;
use scr\model\PessoaFisica;

class FuncionarioControl 
{
    public function getAll()
    {
        $db = Banco::getInstance();
        $db->open();
        $list = Usuario::getAll($db->getConnection());
        $db->getConnection()->close();
        
        $jarray = array();
        for ($i = 0; $i < count($list); $i++) {
            /** @var Usuario $u */
            $u = $list[$i];
            $jarray[] = $u->jsonSerialize();
        }
        
        return json_encode($jarray);
    }
    
    public function getByKey(string $key)
    {
        $db = Banco::getInstance();
        $db->open();
        $array = Usuario::getByKey($db->getConnection(), $key);
        $db->getConnection()->close();
        
        $jarray = array();
        for ($i = 0; $i < count($array); $i++) {
            /** @var Usuario $u */
            $u = $array[$i];
            $jarray[] = $u->jsonSerialize();
        }
        
        return json_encode($jarray);
    }
    
    public function getByAdm(string $adm)
    {
        $db = Banco::getInstance();
        $db->open();
        $array = Usuario::getByAdm($db->getConnection(), $adm);
        $db->getConnection()->close();
        
        $jarray = array();
        for ($i = 0; $i < count($array); $i++) {
            /** @var Usuario $u */
            $u = $array[$i];
            $jarray[] = $u->jsonSerialize();
        }
        
        return json_encode($jarray);
    }
    
    public function getByKeyAdm(string $key, string $adm)
    {
        $db = Banco::getInstance();
        $db->open();
        $array = Usuario::getByKeyAdm($db->getConnection(), $key, $adm);
        $db->getConnection()->close();

        $jarray = array();
        for ($i = 0; $i < count($array); $i++) {
            /** @var Usuario $u */
            $u = $array[$i];
            $jarray[] = $u->jsonSerialize();
        }
        
        return json_encode($jarray);
    }
    
    public function sort(string $col)
    {
        $db = Banco::getInstance();
        $db->open();
        $array = Usuario::getAll($db->getConnection());
        $db->getConnection()->close();
        
        switch ($col) {
            case '1':
                usort($array, function (Usuario $a, Usuario $b) {
                    if ($a->getId() === $b->getId()) return 0;
                    return (($a->getId() < $b->getId()) ? -1 : 1);
                });
                break;
            case '2':
                usort($array, function (Usuario $a, Usuario $b) {
                    if ($a->getId() === $b->getId()) return 0;
                    return (($a->getId() > $b->getId()) ? -1 : 1);
                });
                break;
            case '3':
                usort($array, function (Usuario $a, Usuario $b) {
                    if (strcasecmp($a->getFuncionario()->getPessoa()->getNome(), $b->getFuncionario()->getPessoa()->getNome()) === 0) return 0;
                    return ((strcasecmp($a->getFuncionario()->getPessoa()->getNome(), $b->getFuncionario()->getPessoa()->getNome()) < 0) ? -1 : 1);
                });
                break;
            case '4':
                usort($array, function (Usuario $a, Usuario $b) {
                    if (strcasecmp($a->getFuncionario()->getPessoa()->getNome(), $b->getFuncionario()->getPessoa()->getNome()) === 0) return 0;
                    return ((strcasecmp($a->getFuncionario()->getPessoa()->getNome(), $b->getFuncionario()->getPessoa()->getNome()) > 0) ? -1 : 1);
                });
                break;
            case '5':
                usort($array, function (Usuario $a, Usuario $b) {
                    if (strcasecmp($a->getLogin(), $b->getLogin()) === 0) return 0;
                    return ((strcasecmp($a->getLogin(), $b->getLogin()) < 0) ? -1 : 1);
                });
                break;
            case '6':
                usort($array, function (Usuario $a, Usuario $b) {
                    if ($a->getId() === $b->getId()) return 0;
                    return ((strcasecmp($a->getLogin(), $b->getLogin()) > 0) ? -1 : 1);
                });
                break;
            case '7':
                usort($array, function (Usuario $a, Usuario $b) {
                    if ($a->getNivel()->getId() === $b->getNivel()->getId()) return 0;
                    return (($a->getNivel()->getId() < $b->getNivel()->getId()) ? -1 : 1);
                });
                break;
            case '8':
                usort($array, function (Usuario $a, Usuario $b) {
                    if ($a->getNivel()->getId() === $b->getNivel()->getId()) return 0;
                    return (($a->getNivel()->getId() > $b->getNivel()->getId()) ? -1 : 1);
                });
                break;
            case '9':
                usort($array, function (Usuario $a, Usuario $b) {
                    if (strcmp($a->getFuncionario()->getPessoa()->getCpf(), $b->getFuncionario()->getPessoa()->getCpf()) === 0) return 0;
                    return ((strcmp($a->getFuncionario()->getPessoa()->getCpf(), $b->getFuncionario()->getPessoa()->getCpf()) < 0) ? -1 : 1);
                });
                break;
            case '10':
                usort($array, function (Usuario $a, Usuario $b) {
                    if (strcmp($a->getFuncionario()->getPessoa()->getCpf(), $b->getFuncionario()->getPessoa()->getCpf()) === 0) return 0;
                    return ((strcmp($a->getFuncionario()->getPessoa()->getCpf(), $b->getFuncionario()->getPessoa()->getCpf()) > 0) ? -1 : 1);
                });
                break;
            case '11':
                usort($array, function (Usuario $a, Usuario $b) {
                    if (date($a->getFuncionario()->getAdmissao()) === date($b->getFuncionario()->getAdmissao())) return 0;
                    return ((date($a->getFuncionario()->getAdmissao()) < date($b->getFuncionario()->getAdmissao())) ? -1 : 1);
                });
                break;
            case '12':
                usort($array, function (Usuario $a, Usuario $b) {
                    if (date($a->getFuncionario()->getAdmissao()) === date($b->getFuncionario()->getAdmissao())) return 0;
                    return ((date($a->getFuncionario()->getAdmissao()) > date($b->getFuncionario()->getAdmissao())) ? -1 : 1);
                });
                break;
            case '13':
                usort($array, function (Usuario $a, Usuario $b) {
                    if ($a->getFuncionario()->getTipo() === $b->getFuncionario()->getTipo()) return 0;
                    return (($a->getFuncionario()->getTipo() < $b->getFuncionario()->getTipo()) ? -1 : 1);
                });
                break;
            case '14':
                usort($array, function (Usuario $a, Usuario $b) {
                    if ($a->getFuncionario()->getTipo() === $b->getFuncionario()->getTipo()) return 0;
                    return (($a->getFuncionario()->getTipo() > $b->getFuncionario()->getTipo()) ? -1 : 1);
                });
                break;
            case '15':
                usort($array, function (Usuario $a, Usuario $b) {
                    if ($a->getAtivo() === $b->getAtivo()) return 0;
                    return (($a->getAtivo() < $b->getAtivo()) ? -1 : 1);
                });
                break;
            case '16':
                usort($array, function (Usuario $a, Usuario $b) {
                    if ($a->getAtivo() === $b->getAtivo()) return 0;
                    return (($a->getAtivo() > $b->getAtivo()) ? -1 : 1);
                });
                break;
            case '17':
                usort($array, function (Usuario $a, Usuario $b) {
                    if (strcasecmp($a->getFuncionario()->getPessoa()->getContato()->getEmail(), $b->getFuncionario()->getPessoa()->getContato()->getEmail()) === 0) return 0;
                    return ((strcasecmp($a->getFuncionario()->getPessoa()->getContato()->getEmail(), $b->getFuncionario()->getPessoa()->getContato()->getEmail()) < 0) ? -1 : 1);
                });
                break;
            case '18':
                usort($array, function (Usuario $a, Usuario $b) {
                    if (strcasecmp($a->getFuncionario()->getPessoa()->getContato()->getEmail(), $b->getFuncionario()->getPessoa()->getContato()->getEmail()) === 0) return 0;
                    return ((strcasecmp($a->getFuncionario()->getPessoa()->getContato()->getEmail(), $b->getFuncionario()->getPessoa()->getContato()->getEmail()) > 0) ? -1 : 1);
                });
                break;
        }
        
        for ($i = 0; $i < count($array); $i++) {
            /** @var Usuario $u */
            $u = $array[$i];
            $jarray[] = $u->jsonSerialize();
        }
        
        return json_encode($jarray);
    }
    
    public function isLastAdmin()
    {
        $db = Banco::getInstance();
        $db->open();
        $res = Usuario::isLastAdmin($db->getConnection());
        $db->getConnection()->close();

        return json_encode($res);
    }
    
    public function delete(int $id)
    {
        $db = Banco::getInstance();
        $db->open();
        if ($db->getConnection() == null) return json_encode('Erro ao conectar-se ao banco de dados.');

        $db->getConnection()->begin_transaction();
        
        /** @var Usuario $usuario */
        $usuario = Usuario::getById($db->getConnection(), $id);
        
        $res_usu = Usuario::delete($db->getConnection(), $id);
        if ($res_usu <= 0) { 
            $db->getConnection()->close();
            return json_encode('Erro ao excluir o usuário.'); 
        }
        
        $res_fun = Funcionario::delete($db->getConnection(), $id);
        if ($res_fun <= 0) {
            $db->getConnection()->rollback();
            $db->getConnection()->close();
            return json_encode('Erro ao excluir o funcionário.');
        }
        
        $res_pes = PessoaFisica::delete($db->getConnection(), $usuario->getFuncionario()->getPessoa()->getId());
        if ($res_pes <= 0) {
            $db->getConnection()->rollback();
            $db->getConnection()->close();
            return json_encode('Erro ao excluir a pessoa.');
        }
        
        $res_ctt = Contato::delete($db->getConnection(), $usuario->getFuncionario()->getPessoa()->getContato()->getId());
        if ($res_ctt <= 0) {
            $db->getConnection()->rollback();
            $db->getConnection()->close();
            return json_encode('Erro ao excluir o contato.');
        }
        
        $res_end = Endereco::delete($db->getConnection(), $usuario->getFuncionario()->getPessoa()->getContato()->getEndereco()->getId());
        if ($res_end <= 0) {
            $db->getConnection()->rollback();
            $db->getConnection()->close();
            return json_encode('Erro ao excluir o endereço.');
        }

        $db->getConnection()->commit();
        $db->getConnection()->close();
        
        return json_encode('');
    }
    
    public function desativar(int $id) 
    {
        $db = Banco::getInstance();
        $db->open();
        $res = Funcionario::desativar($db->getConnection(), $id);
        $db->getconnection()->close();

        return $res <= 0 ? json_encode('Erro ao desativar o funcionário.') : json_encode('');
    }
    
    public function reativar(int $id) 
    {
        $db = Banco::getInstance();
        $db->open();
        $res = Funcionario::reativar($db->getConnection(), $id);
        $db->getconnection()->close();
        
        return $res <= 0 ? json_encode('Erro ao reativar o funcionário.') : json_encode('');
    }
    
    public function enviar(int $id)
    {
        if ($id <= 0) { return json_encode('Parâmetro inválido.'); }
        
        $_SESSION['FUNC'] = $id;
        
        return json_encode('');
    }
}
