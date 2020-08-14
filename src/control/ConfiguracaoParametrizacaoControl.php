<?php


namespace scr\control;


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
        $parametrizacao = null;
        if (Banco::getInstance()->open())
        {
            $par = Parametrizacao::get();
            Banco::getInstance()->getConnection()->close();

            if ($par) $parametrizacao = $par->jsonSerialize();
        }

        return json_encode($parametrizacao);
    }

    public function verificarCnpj(string $cnpj)
    {
        $res = false;
        if (Banco::getInstance()->open())
        {
            $res = PessoaJuridica::verifyCnpj($cnpj);
            Banco::getInstance()->getConnection()->close();
        }

        return json_encode($res);
    }

    public function gravar(string $rsocial, string $nfantasia, string $cnpj, string $rua, string $numero, string $bairro, string $complemento, string $cep, int $cidade, string $telefone, string $celular, string $email)
    {
        if (!Banco::getInstance()->open()) return json_encode('Ocorreu um problema na conexão com o banco de dados.');

        $cid = (new Cidade())->getById($cidade);

        Banco::getInstance()->getConnection()->begin_transaction();

        $endereco = new Endereco(0, $rua, $numero, $bairro, $complemento, $cep, $cid);
        $res_end = $endereco->insert();
        if ($res_end == -10) {
            Banco::getInstance()->getConnection()->close();
            return json_encode('Ocorreu um problema ao gravar o endereço.');
        }
        if ($res_end == -5) {
            Banco::getInstance()->getConnection()->close();
            return json_encode('Um ou mais campos inválidos no endereço.');
        }

        $endereco = new Endereco($res_end, $rua, $numero, $bairro, $complemento, $cep, $cid);
        $contato = new Contato(0, $telefone, $celular, $email, $endereco);
        $res_ctt = $contato->insert();
        if ($res_ctt == -10) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode('Ocorreu um problema ao gravar o contato.');
        }
        if ($res_ctt == -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode('Um ou mais campos inválidos no contato.');
        }

        $contato = new Contato($res_ctt, $telefone, $celular, $email, $endereco);
        $pessoa = new PessoaJuridica(0, $rsocial, $nfantasia, $cnpj, $contato);
        $res_pes = $pessoa->insert();
        if ($res_pes == -10) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode('Ocorreu um problema ao gravar a pessoa.');
        }
        if ($res_pes == -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode('Um ou mais campos inválidos na pessoa.');
        }

        $pessoa = new PessoaJuridica($res_pes, $rsocial, $nfantasia, $cnpj, $contato);
        $parametrizacao = new Parametrizacao(0, '', $pessoa);
        $res = $parametrizacao->insert();
        if ($res == -10) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode('Ocorreu um problema ao gravar a parametrização.');
        }
        if ($res == -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode('Um ou mais campos inválidos na parametrização.');
        }

        Banco::getInstance()->getConnection()->commit();
        Banco::getInstance()->getConnection()->close();

        return json_encode('');
    }

    public function alterar(int $end, int $ctt, int $pes, string $rsocial, string $nfantasia, string $cnpj, string $rua, string $numero, string $bairro, string $complemento, string $cep, int $cidade, string $telefone, string $celular, string $email)
    {
        if (!Banco::getInstance()->open()) return json_encode('Ocorreu um problema na conexão com o banco de dados.');

        $cid = (new Cidade())->getById($cidade);

        Banco::getInstance()->getConnection()->begin_transaction();

        $endereco = new Endereco($end, $rua, $numero, $bairro, $complemento, $cep, $cid);
        $res_end = $endereco->update();
        if ($res_end == -10) {
            Banco::getInstance()->getConnection()->close();
            return json_encode('Ocorreu um problema ao alterar o endereço.');
        }
        if ($res_end == -5) {
            Banco::getInstance()->getConnection()->close();
            return json_encode('Um ou mais campos inválidos no endereço.');
        }

        $contato = new Contato($ctt, $telefone, $celular, $email, $endereco);
        $res_ctt = $contato->update();
        if ($res_ctt == -10) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode('Ocorreu um problema ao alterar o contato.');
        }
        if ($res_ctt == -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode('Um ou mais campos inválidos no contato.');
        }

        $pessoa = new PessoaJuridica($pes, $rsocial, $nfantasia, $cnpj, $contato);
        $res_pes = $pessoa->update();
        if ($res_pes == -10) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode('Ocorreu um problema ao alterar a pessoa.');
        }
        if ($res_pes == -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode('Um ou mais campos inválidos na pessoa.');
        }

        $parametrizacao = new Parametrizacao(1, '', $pessoa);
        $res = $parametrizacao->update();
        if ($res == -10) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode('Ocorreu um problema ao alterar a parametrização.');
        }
        if ($res == -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode('Um ou mais campos inválidos na parametrização.');
        }

        Banco::getInstance()->getConnection()->commit();
        Banco::getInstance()->getConnection()->close();

        return json_encode('');
    }
}