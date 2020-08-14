<?php


namespace scr\control;


use scr\model\Estado;
use scr\model\Cidade;
use scr\model\Endereco;
use scr\model\Contato;
use scr\model\PessoaFisica;
use scr\model\Funcionario;
use scr\model\Nivel;
use scr\model\Usuario;
use scr\util\Banco;

class FuncionarioDetalhesControl
{
    public function getDetails()
    {
        if (!Banco::getInstance()->open()) return json_encode(null);
        $usuario = Usuario::getById($_COOKIE['FUNC'])->jsonSerialize();
        Banco::getInstance()->getConnection()->close();

        return json_encode($usuario);
    }
    
    public function verificarCpf(string $cpf)
    {
        if (!Banco::getInstance()->open()) return json_encode(true);
        $res = PessoaFisica::verifyCpf($cpf);
        Banco::getInstance()->getConnection()->close();

        return json_encode($res);
    }

    public function verificarLogin(string $login)
    {
        if (!Banco::getInstance()->open()) return json_encode(true);
        $res = Usuario::verificarLogin($login);
        Banco::getInstance()->getConnection()->close();

        return json_encode($res);
    }
    
    public function isLastAdmin()
    {
        if (!Banco::getInstance()->open()) return json_encode(true);
        $res = Usuario::isLastAdmin();
        Banco::getInstance()->getConnection()->close();

        return json_encode($res);
    }

    public function alterar(int $end, int $ctt, int $pf, int $fun, int $usu, bool $ativo, string $nome, string $rg, string $cpf, string $nascimento, string $admissao, int $tipo, string $rua, string $numero, string $bairro, string $complemento, string $cep, int $cidade, string $telefone, string $celular, string $email, int $nivel, string $senha, string $login)
    {
        if (!Banco::getInstance()->open()) return json_encode('Erro ao conectar-se ao banco de dados.');

        $cid = (new Cidade())->getById($cidade);
        Banco::getInstance()->getConnection()->begin_transaction();

        $endereco = new Endereco($end, $rua, $numero, $bairro, $complemento, $cep, $cid);
        $res_end = $endereco->update();
        if ($res_end == -10) {
            Banco::getInstance()->getConnection()->close();
            return json_encode('Erro ao executar o SQL de alteração do endereço.');
        }
        if ($res_end == -5) {
            Banco::getInstance()->getConnection()->close();
            return json_encode('Um ou mais campos do endereço inválidos.');
        }

        $endereco = new Endereco($end, $rua, $numero, $bairro, $complemento, $cep, $cid);
        $contato = new Contato($ctt, $telefone, $celular, $email, $endereco);
        $res_ctt = $contato->update();
        if ($res_ctt == -10) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode('Erro ao executar o SQL de alteração do contato.');
        }
        if ($res_ctt == -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode('Um ou mais campos do contato inválidos.');
        }

        $contato = new Contato($ctt, $telefone, $celular, $email, $endereco);
        $pessoa = new PessoaFisica($pf, $nome, $rg, $cpf, $nascimento, $contato);
        $res_pes = $pessoa->update();
        if ($res_pes == -10) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode('Erro ao executar o SQL de alteração da pessoa.');
        }
        if ($res_pes == -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode('Um ou mais campos da pessoa inválidos.');
        }

        $pessoa = new PessoaFisica($pf, $nome, $rg, $cpf, $nascimento, $contato);
        $funcionario = new Funcionario($fun, $tipo, $admissao, "", $pessoa);
        $res_fun = $funcionario->update();
        if ($res_fun == -10) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode('Erro ao executar o SQL de alteração do funcionário.');
        }
        if ($res_fun == -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode('Um ou mais campos do funcionário inválidos.');
        }

        if ($tipo == 1) {
            $usuario = new Usuario($usu, $login, $senha, $ativo, new Funcionario($fun, $tipo, $admissao, "", new PessoaFisica($pf, $nome, $rg, $cpf, $nascimento, new Contato($ctt, $telefone, $celular, $email, new Endereco($end, $rua, $numero, $bairro, $complemento, $cep, new Cidade($cidade, '', new Estado(0,'','')))))), new Nivel($nivel, ""));
            $res_usu = $usuario->update();
            if ($res_usu == -10) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return json_encode('Erro ao executar o SQL de alteração do usuário.');
            }
            if ($res_usu == -5) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return json_encode('Um ou mais campos do usuário inválidos.');
            }
        } else {
            $usuario = new Usuario($usu, "", "", false, new Funcionario($fun, $tipo, $admissao, "", new PessoaFisica($pf, $nome, $rg, $cpf, $nascimento, new Contato($ctt, $telefone, $celular, $email, new Endereco($end, $rua, $numero, $bairro, $complemento, $cep, new Cidade($cidade, '', new Estado(0,'','')))))), new Nivel(3, ""));
            $res_usu = $usuario->update();
            if ($res_usu == -10) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return json_encode('Erro ao executar o SQL de alteração do usuário.');
            }
            if ($res_usu == -5) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return json_encode('Um ou mais campos do usuário inválidos.');
            }
        }

        Banco::getInstance()->getConnection()->commit();
        Banco::getInstance()->getConnection()->close();

        return json_encode('');
    }
}