<?php namespace scr\control;

use scr\util\Banco;
use scr\model\Cidade;
use scr\model\Contato;
use scr\model\Endereco;
use scr\model\Estado;
use scr\model\Funcionario;
use scr\model\Nivel;
use scr\model\PessoaFisica;
use scr\model\Usuario;

class ConfiguracoesDadosControl
{
    public function obterDados()
    {
        $user_id = $_SESSION['USER_ID'];
        $id = $user_id !== null && $user_id !== '' ? (int)$user_id : 0;

        $db = Banco::getInstance();
        $db->open();
        $res = Usuario::getById($db->getConnection(), $id)->jsonSerialize();
        $db->getConnection()->close();

        return json_encode($res);
    }

    public function verificarCpf(string $cpf)
    {
        $db = Banco::getInstance();
        $db->open();
        $res = PessoaFisica::verifyCpf($db->getConnection(), $cpf);
        $db->getConnection()->close();

        return json_encode($res);
    }

    public function verificarLogin(string $login)
    {
        $db = Banco::getInstance();
        $db->open();
        $res = Usuario::verificarLogin($db->getConnection(), $login);
        $db->getConnection()->close();

        return json_encode($res);
    }

    public function isLastAdmin()
    {
        $db = Banco::getInstance();
        $db->open();
        $res = Usuario::isLastAdmin($db->getConnection(), $login);
        $db->getConnection()->close();

        return json_encode($res);
    }

    public function alterar(int $end, int $ctt, int $pf, int $fun, int $usu, bool $ativo, string $nome, string $rg, string $cpf, string $nascimento, string $admissao, int $tipo, string $rua, string $numero, string $bairro, string $complemento, string $cep, int $cidade, string $telefone, string $celular, string $email, int $nivel, string $senha, string $login)
    {
        $db = Banco::getInstance();
        $db->open();
        if ($db->getConnection() == null) return -10;
        $db->getConnection()->begin_transaction();

        $endereco = new Endereco($end, $rua, $numero, $bairro, $complemento, $cep, new Cidade($cidade, '', new Estado(0,'','')));
        $res_end = $endereco->update($db->getConnection());

        if ($res_end == -10) {
            $db->getConnection()->close();
            return json_encode('Erro ao executar o SQL de alteração do endereço.');
        } 
        if ($res_end == -5) {
            $db->getConnection()->close();
            return json_encode('Um ou mais campos do endereço inválidos.');
        } 

        $contato = new Contato($ctt, $telefone, $celular, $email, new Endereco($end, $rua, $numero, $bairro, $complemento, $cep, new Cidade($cidade, '', new Estado(0,'',''))));
        $res_ctt = $contato->update($db->getConnection());

        if ($res_ctt == -10) {
            $db->getConnection()->rollback();
            $db->getConnection()->close();
            return json_encode('Erro ao executar o SQL de alteração do contato.');
        }
        if ($res_ctt == -5) {
            $db->getConnection()->rollback();
            $db->getConnection()->close();
            return json_encode('Um ou mais campos do contato inválidos.');
        }

        $pessoa = new PessoaFisica($pf, $nome, $rg, $cpf, $nascimento, new Contato($ctt, $telefone, $celular, $email, new Endereco($end, $rua, $numero, $bairro, $complemento, $cep, new Cidade($cidade, '', new Estado(0,'','')))));
        $res_pes = $pessoa->update($db->getConnection());

        if ($res_pes == -10) {
            $db->getConnection()->rollback();
            $db->getConnection()->close();
            return json_encode('Erro ao executar o SQL de alteração da pessoa.');
        }
        if ($res_pes == -5) {
            $db->getConnection()->rollback();
            $db->getConnection()->close();
            return json_encode('Um ou mais campos da pessoa inválidos.');
        }

        $funcionario = new Funcionario($fun, $tipo, $admissao, "", new PessoaFisica($pf, $nome, $rg, $cpf, $nascimento, new Contato($ctt, $telefone, $celular, $email, new Endereco($end, $rua, $numero, $bairro, $complemento, $cep, new Cidade($cidade, '', new Estado(0,'',''))))));
        $res_fun = $funcionario->update($db->getConnection());

        if ($res_fun == -10) {
            $db->getConnection()->rollback();
            $db->getConnection()->close();
            return json_encode('Erro ao executar o SQL de alteração do funcionário.');
        }
        if ($res_fun == -5) {
            $db->getConnection()->rollback();
            $db->getConnection()->close();
            return json_encode('Um ou mais campos do funcionário inválidos.');
        }

        if ($tipo == 1) {
            $usuario = new Usuario($usu, $login, $senha, $ativo, new Funcionario($fun, $tipo, $admissao, "", new PessoaFisica($pf, $nome, $rg, $cpf, $nascimento, new Contato($ctt, $telefone, $celular, $email, new Endereco($end, $rua, $numero, $bairro, $complemento, $cep, new Cidade($cidade, '', new Estado(0,'','')))))), new Nivel($nivel, ""));
            $res_usu = $usuario->update($db->getConnection());

            if ($res_usu == -10) {
                $db->getConnection()->rollback();
                $db->getConnection()->close();
                return json_encode('Erro ao executar o SQL de alteração do usuário.');
            }
            if ($res_usu == -5) {
                $db->getConnection()->rollback();
                $db->getConnection()->close();
                return json_encode('Um ou mais campos do usuário inválidos.');
            }
        } else {
            $usuario = new Usuario($usu, "", "", false, new Funcionario($fun, $tipo, $admissao, "", new PessoaFisica($pf, $nome, $rg, $cpf, $nascimento, new Contato($ctt, $telefone, $celular, $email, new Endereco($end, $rua, $numero, $bairro, $complemento, $cep, new Cidade($cidade, '', new Estado(0,'','')))))), new Nivel(3, ""));
            $res_usu = $usuario->update($db->getConnection());

            if ($res_usu == -10) {
                $db->getConnection()->rollback();
                $db->getConnection()->close();
                return json_encode('Erro ao executar o SQL de alteração do usuário.');
            }
            if ($res_usu == -5) {
                $db->getConnection()->rollback();
                $db->getConnection()->close();
                return json_encode('Um ou mais campos do usuário inválidos.');
            }
        }

        $db->getConnection()->commit();
        $db->getConnection()->close();

        return json_encode('');
    }
}