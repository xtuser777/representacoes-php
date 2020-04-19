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
        $res = Usuario::verificarLogin($db->getconnection(), $login);
        $db->getConnection()->close();

        return json_encode($res);
    }
    
    public function gravar(string $nome, string $rg, string $cpf, string $nascimento, string $admissao, int $tipo, string $rua, string $numero, string $bairro, string $complemento, string $cep, int $cidade, string $telefone, string $celular, string $email, int $nivel, string $senha, string $login)
    {
        $db = Banco::getInstance();
        $db->open();
        if ($db->getConnection() == null) return json_encode('Erro ao conectar-se com o banco de dados.');
        $db->getConnection()->begin_transaction();
        
        $endereco = new Endereco(0, $rua, $numero, $bairro, $complemento, $cep, new Cidade($cidade, '', new Estado(0,'','')));
        $res_end = $endereco->insert($db->getConnection());
        
        if ($res_end == -10) { 
            $db->getConnection()->close();
            return json_encode('Erro ao executar o SQL de gravação do endereço.'); 
        }
        if ($res_end == -5) { 
            $db->getConnection()->close();
            return json_encode('Um ou mais campos inválidos.'); 
        }
        
        $contato = new Contato(0, $telefone, $celular, $email, new Endereco($res_end, $rua, $numero, $bairro, $complemento, $cep, new Cidade($cidade, '', new Estado(0,'',''))));
        $res_ctt = $contato->insert($db->getConnection());
        
        if ($res_ctt == -10) {
            $db->getConnection()->rollback();
            $db->getConnection()->close();
            return json_encode('Erro ao executar o SQL de gravação do contato.');
        }
        if ($res_ctt == -5) {
            $db->getConnection()->rollback();
            $db->getConnection()->close();
            return json_encode('Um ou mais campos inválidos.');
        }
        
        $pessoa = new PessoaFisica(0, $nome, $rg, $cpf, $nascimento, new Contato($res_ctt, $telefone, $celular, $email, new Endereco($res_end, $rua, $numero, $bairro, $complemento, $cep, new Cidade($cidade, '', new Estado(0,'','')))));
        $res_pes = $pessoa->insert($db->getConnection());
        
        if ($res_pes == -10) {
            $db->getConnection()->rollback();
            $db->getConnection()->close();
            return json_encode('Erro ao executar o SQL de gravação da pessoa.');
        }
        if ($res_pes == -5) {
            $db->getConnection()->rollback();
            $db->getConnection()->close();
            return json_encode('Um ou mais campos inválidos.');
        }
        
        $funcionario = new Funcionario(0, $tipo, $admissao, "", new PessoaFisica($res_pes, $nome, $rg, $cpf, $nascimento, new Contato($res_ctt, $telefone, $celular, $email, new Endereco($res_end, $rua, $numero, $bairro, $complemento, $cep, new Cidade($cidade, '', new Estado(0,'',''))))));
        $res_fun = $funcionario->insert($db->getConnection());
        
        if ($res_fun == -10) {
            $db->getConnection()->rollback();
            $db->getConnection()->close();
            return json_encode('Erro ao executar o SQL de gravação do funcionário.');
        }
        if ($res_fun == -5) {
            $db->getConnection()->rollback();
            $db->getConnection()->close();
            return json_encode('Um ou mais campos inválidos.');
        }
        
        if ($tipo == 1) {
            $usuario = new Usuario(0, $login, $senha, true, new Funcionario($res_fun, $tipo, $admissao, "", new PessoaFisica($res_pes, $nome, $rg, $cpf, $nascimento, new Contato($res_ctt, $telefone, $celular, $email, new Endereco($res_end, $rua, $numero, $bairro, $complemento, $cep, new Cidade($cidade, '', new Estado(0,'','')))))), new Nivel($nivel, ""));
            $res_usu = $usuario->insert($db->getConnection());
            
            if ($res_usu == -10) {
                $db->getConnection()->rollback();
                $db->getConnection()->close();
                return json_encode('Erro ao executar o SQL de gravação do usuário.');
            }
            if ($res_usu == -5) {
                $db->getConnection()->rollback();
                $db->getConnection()->close();
                return json_encode('Um ou mais campos inválidos.');
            }
        } else {
            $usuario = new Usuario(0, "", "", false, new Funcionario($res_fun, $tipo, $admissao, "", new PessoaFisica($res_pes, $nome, $rg, $cpf, $nascimento, new Contato($res_ctt, $telefone, $celular, $email, new Endereco($res_end, $rua, $numero, $bairro, $complemento, $cep, new Cidade($cidade, '', new Estado(0,'','')))))), new Nivel(3, ""));
            $res_usu = $usuario->insert($db->getConnection());
            
            if ($res_usu == -10) {
                $db->getConnection()->rollback();
                $db->getConnection()->close();
                return json_encode('Erro ao executar o SQL de gravação do usuário.');
            }
            if ($res_usu == -5) {
                $db->getConnection()->rollback();
                $db->getConnection()->close();
                return json_encode('Um ou mais campos inválidos.');
            }
        }

        $db->getConnection()->commit();
        $db->getConnection()->close();
        
        return json_encode('');
    }
}
