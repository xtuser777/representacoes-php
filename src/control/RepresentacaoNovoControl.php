<?php


namespace scr\control;


use scr\model\Cidade;
use scr\model\Contato;
use scr\model\Endereco;
use scr\model\PessoaJuridica;
use scr\model\Representacao;
use scr\util\Banco;

class RepresentacaoNovoControl
{
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

    public function gravar(string $rs, string $nf, string $cnpj, string $tel, string $cel, string $email, string $rua, string $num, string $bairro, string $comp, string $cep, int $cid)
    {
        if (!Banco::getInstance()->open()) return json_encode('Erro ao conectar-se ao banco de dados.');

        $cidade = (new Cidade())->getById($cid);

        Banco::getInstance()->getConnection()->begin_transaction();

        $endareco = new Endereco(0, $rua, $num, $bairro, $comp, $cep, $cidade);
        $end = $endareco->insert();
        if ($end == -10 || $end == -1)
        {
            Banco::getInstance()->getConnection()->close();
            return json_encode('Ocorreu um problema na inserção do endereço.');
        }
        if ($end == -5)
        {
            Banco::getInstance()->getConnection()->close();
            return json_encode('Parâmetros inválidos.');
        }

        $endereco = new Endereco($end, $rua, $num, $bairro, $comp, $cep, $cidade);
        $contato = new Contato(0, $tel, $cel, $email, $endereco);
        $ctt = $contato->insert();
        if ($ctt == -10 || $ctt == -1)
        {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode('Ocorreu um problema na inserção do contato.');
        }
        if ($ctt == -5)
        {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode('Parâmetros inválidos.');
        }

        $contato = new Contato($ctt, $tel, $cel, $email, $endereco);
        $pessoa = new PessoaJuridica(0, $rs, $nf, $cnpj, $contato);
        $pes = $pessoa->insert();
        if ($pes == -10 || $pes == -1)
        {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode('Ocorreu um problema na inserção da pessoa.');
        }
        if ($pes == -5)
        {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode('Parâmetros inválidos.');
        }

        $pessoa = new PessoaJuridica($pes, $rs, $nf, $cnpj, $contato);
        $representacao = new Representacao(0, date('Y-m-d'), $cidade->getNome() . '/' . $cidade->getEstado()->getSigla(), $pessoa);
        $rep = $representacao->insert();
        if ($rep == -10 || $rep == -1)
        {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode('Ocorreu um problema na inserção da representação.');
        }
        if ($rep == -5)
        {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode('Parâmetros inválidos.');
        }

        Banco::getInstance()->getConnection()->commit();
        Banco::getInstance()->getConnection()->close();

        return json_encode('');
    }
}