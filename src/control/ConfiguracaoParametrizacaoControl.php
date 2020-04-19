<?php namespace scr\control;

use scr\model\Estado;
use scr\model\Cidade;
use scr\model\Endereco;
use scr\model\Contato;
use scr\model\PessoaJuridica;
use scr\model\Parametrizacao;
use scr\util\Banco;

class ConfiguracaoParametrizacaoControl
{
    public function obter()
    {
        $db = Banco::getInstance();
        $db->open();
        $par = Parametrizacao::get($db->getConnection());
        $db->getConnection()->close();

        return json_encode($par != null ? $par->jsonSerialize() : null);
    }

    public function verificarCnpj(string $cnpj)
    {
        $db = Banco::getInstance();
        $db->open();
        $res = PessoaJuridica::verifyCnpj($db->getConnection(), $cnpj);
        $db->getConnection()->close();

        return json_encode($res);
    }

    public function gravar(string $rsocial, string $nfantasia, string $cnpj, string $rua, string $numero, string $bairro, string $complemento, string $cep, int $cidade, string $telefone, string $celular, string $email)
    {
        $db = Banco::getInstance();
        $db->open();
        if (!$db->getConnection()) return json_encode('Ocorreu um problema na conexão com o banco de dados.');

        $db->getConnection()->begin_transaction();

        $cid = new Cidade($cidade, '', new Estado(0, '', ''));
        $endereco = new Endereco(0, $rua, $numero, $bairro, $complemento, $cep, $cid);
        $res_end = $endereco->insert($db->getConnection());
        if ($res_end == -10) {
            $db->getConnection()->close();
            return json_encode('Ocorreu um problema ao gravar o endereço.');
        }
        if ($res_end == -5) {
            $db->getConnection()->close();
            return json_encode('Um ou mais campos inválidos no endereço.');
        }

        $contato = new Contato(0, $telefone, $celular, $email, new Endereco($res_end, $rua, $numero, $bairro, $complemento, $cep, $cid));
        $res_ctt = $contato->insert($db->getConnection());
        if ($res_ctt == -10) {
            $db->getConnection()->rollback();
            $db->getConnection()->close();
            return json_encode('Ocorreu um problema ao gravar o contato.');
        }
        if ($res_ctt == -5) {
            $db->getConnection()->rollback();
            $db->getConnection()->close();
            return json_encode('Um ou mais campos inválidos no contato.');
        }

        $pessoa = new PessoaJuridica(0, $rsocial, $nfantasia, $cnpj, new Contato($res_ctt, $telefone, $celular, $email, new Endereco($res_end, $rua, $numero, $bairro, $complemento, $cep, $cid)));
        $res_pes = $pessoa->insert($db->getConnection());
        if ($res_pes == -10) {
            $db->getConnection()->rollback();
            $db->getConnection()->close();
            return json_encode('Ocorreu um problema ao gravar a pessoa.');
        }
        if ($res_pes == -5) {
            $db->getConnection()->rollback();
            $db->getConnection()->close();
            return json_encode('Um ou mais campos inválidos na pessoa.');
        }

        $parametrizacao = new Parametrizacao(0, '', new PessoaJuridica($res_pes, $rsocial, $nfantasia, $cnpj, new Contato($res_ctt, $telefone, $celular, $email, new Endereco($res_end, $rua, $numero, $bairro, $complemento, $cep, $cid))));
        $res = $parametrizacao->insert($db->getConnection());
        if ($res == -10) {
            $db->getConnection()->rollback();
            $db->getConnection()->close();
            return json_encode('Ocorreu um problema ao gravar a parametrização.');
        }
        if ($res == -5) {
            $db->getConnection()->rollback();
            $db->getConnection()->close();
            return json_encode('Um ou mais campos inválidos na parametrização.');
        }

        $db->getConnection()->commit();
        $db->getConnection()->close();

        return json_encode('');
    }

    public function alterar(int $end, int $ctt, int $pes, string $rsocial, string $nfantasia, string $cnpj, string $rua, string $numero, string $bairro, string $complemento, string $cep, int $cidade, string $telefone, string $celular, string $email)
    {
        $db = Banco::getInstance();
        $db->open();
        if (!$db->getConnection()) return json_encode('Ocorreu um problema na conexão com o banco de dados.');

        $db->getConnection()->begin_transaction();

        $cid = new Cidade($cidade, '', new Estado(0, '', ''));
        $endereco = new Endereco($end, $rua, $numero, $bairro, $complemento, $cep, $cid);
        $res_end = $endereco->update($db->getConnection());
        if ($res_end == -10) {
            $db->getConnection()->close();
            return json_encode('Ocorreu um problema ao alterar o endereço.');
        }
        if ($res_end == -5) {
            $db->getConnection()->close();
            return json_encode('Um ou mais campos inválidos no endereço.');
        }

        $contato = new Contato($ctt, $telefone, $celular, $email, new Endereco($end, $rua, $numero, $bairro, $complemento, $cep, $cid));
        $res_ctt = $contato->update($db->getConnection());
        if ($res_ctt == -10) {
            $db->getConnection()->rollback();
            $db->getConnection()->close();
            return json_encode('Ocorreu um problema ao alterar o contato.');
        }
        if ($res_ctt == -5) {
            $db->getConnection()->rollback();
            $db->getConnection()->close();
            return json_encode('Um ou mais campos inválidos no contato.');
        }

        $pessoa = new PessoaJuridica($pes, $rsocial, $nfantasia, $cnpj, new Contato($ctt, $telefone, $celular, $email, new Endereco($end, $rua, $numero, $bairro, $complemento, $cep, $cid)));
        $res_pes = $pessoa->update($db->getConnection());
        if ($res_pes == -10) {
            $db->getConnection()->rollback();
            $db->getConnection()->close();
            return json_encode('Ocorreu um problema ao alterar a pessoa.');
        }
        if ($res_pes == -5) {
            $db->getConnection()->rollback();
            $db->getConnection()->close();
            return json_encode('Um ou mais campos inválidos na pessoa.');
        }

        $parametrizacao = new Parametrizacao(1, '', new PessoaJuridica($pes, $rsocial, $nfantasia, $cnpj, new Contato($ctt, $telefone, $celular, $email, new Endereco($end, $rua, $numero, $bairro, $complemento, $cep, $cid))));
        $res = $parametrizacao->update($db->getConnection());
        if ($res == -10) {
            $db->getConnection()->rollback();
            $db->getConnection()->close();
            return json_encode('Ocorreu um problema ao alterar a parametrização.');
        }
        if ($res == -5) {
            $db->getConnection()->rollback();
            $db->getConnection()->close();
            return json_encode('Um ou mais campos inválidos na parametrização.');
        }

        $db->getConnection()->commit();
        $db->getConnection()->close();

        return json_encode('');
    }
}