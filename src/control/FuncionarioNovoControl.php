<?php namespace scr\control;

use scr\util\Banco;
use scr\model\Estado;
use scr\model\Cidade;
use scr\model\Endereco;
use scr\model\Contato;
use scr\model\PessoaFisica;
use scr\model\Funcionario;
use scr\model\Nivel;
use scr\model\Usuario;

class FuncionarioNovoControl 
{
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
    
    public function gravar(string $nome, string $rg, string $cpf, string $nascimento, string $admissao, int $tipo, string $rua, string $numero, string $bairro, string $complemento, string $cep, int $cidade, string $telefone, string $celular, string $email, int $nivel, string $senha, string $login)
    {
        if (!Banco::getInstance()->open()) return json_encode('Erro ao conectar-se com o banco de dados.');

        $cid = Cidade::getById($cidade);

        Banco::getInstance()->getConnection()->begin_transaction();
        
        $endereco = new Endereco(0, $rua, $numero, $bairro, $complemento, $cep, $cid);
        $res_end = $endereco->insert();
        if ($res_end == -10) {
            Banco::getInstance()->getConnection()->close();
            return json_encode('Erro ao executar o SQL de gravação do endereço.'); 
        }
        if ($res_end == -5) {
            Banco::getInstance()->getConnection()->close();
            return json_encode('Um ou mais campos inválidos.'); 
        }

        $endereco = new Endereco($res_end, $rua, $numero, $bairro, $complemento, $cep, $cid);
        $contato = new Contato(0, $telefone, $celular, $email, $endereco);
        $res_ctt = $contato->insert();
        if ($res_ctt == -10) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode('Erro ao executar o SQL de gravação do contato.');
        }
        if ($res_ctt == -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode('Um ou mais campos inválidos.');
        }

        $contato = new Contato($res_ctt, $telefone, $celular, $email, $endereco);
        $pessoa = new PessoaFisica(0, $nome, $rg, $cpf, $nascimento, $contato);
        $res_pes = $pessoa->insert();
        if ($res_pes == -10) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode('Erro ao executar o SQL de gravação da pessoa.');
        }
        if ($res_pes == -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode('Um ou mais campos inválidos.');
        }

        $pessoa = new PessoaFisica($res_pes, $nome, $rg, $cpf, $nascimento, $contato);
        $funcionario = new Funcionario(0, $tipo, $admissao, "", $pessoa);
        $res_fun = $funcionario->insert();
        if ($res_fun == -10) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode('Erro ao executar o SQL de gravação do funcionário.');
        }
        if ($res_fun == -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode('Um ou mais campos inválidos.');
        }
        
        if ($tipo == 1) {
            $funcionario = new Funcionario($res_fun, $tipo, $admissao, "", $pessoa);
            $usuario = new Usuario(0, $login, $senha, true, $funcionario, new Nivel($nivel, ""));
            $res_usu = $usuario->insert();
            if ($res_usu == -10) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return json_encode('Erro ao executar o SQL de gravação do usuário.');
            }
            if ($res_usu == -5) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return json_encode('Um ou mais campos inválidos.');
            }
        } else {
            $funcionario = new Funcionario($res_fun, $tipo, $admissao, "", $pessoa);
            $usuario = new Usuario(0, "", "", false, $funcionario, new Nivel(3, ""));
            $res_usu = $usuario->insert();
            if ($res_usu == -10) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return json_encode('Erro ao executar o SQL de gravação do usuário.');
            }
            if ($res_usu == -5) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return json_encode('Um ou mais campos inválidos.');
            }
        }

        Banco::getInstance()->getConnection()->commit();
        Banco::getInstance()->getConnection()->close();
        
        return json_encode('');
    }
}
