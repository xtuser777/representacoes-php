<?php namespace scr\control;


use scr\model\Cidade;
use scr\model\Contato;
use scr\model\Endereco;
use scr\model\PessoaJuridica;
use scr\model\Representacao;
use scr\util\Banco;

class RepresentacaoAddUnidadeControl
{
    public function obter()
    {
        $json = null;
        if (Banco::getInstance()->open() && isset($_SESSION['REPRESENTACAO']))
        {
            /** @var Representacao $rep */
            $rep = Representacao::getById($_SESSION['REPRESENTACAO']);
            Banco::getInstance()->getConnection()->close();
            if ($rep != null) $json = $rep->jsonSerialize();
        }

        return json_encode($json);
    }

    public function gravar(string $rs, string $nf, string $cnpj, string $tel, string $cel, string $email, string $rua, string $num, string $bairro, string $comp, string $cep, int $cid)
    {
        if (!Banco::getInstance()->open()) return json_encode('Erro ao conectar-se ao banco de dados.');

        $cidade = Cidade::getById(Banco::getInstance()->getConnection(), $cid);

        Banco::getInstance()->getConnection()->begin_transaction();

        $endareco = new Endereco(0, $rua, $num, $bairro, $comp, $cep, $cidade);
        $end = $endareco->insert(Banco::getInstance()->getConnection());
        if ($end == -10 || $end == -1)
        {
            Banco::getInstance()->getConnection()->close();
            return json_encode('Ocorreu um problema na inserção do endereço.');
        }
        if ($end == -5)
        {
            Banco::getInstance()->getConnection()->close();
            return json_encode('Parâmetros de endereço inválidos.');
        }

        $endereco = new Endereco($end, $rua, $num, $bairro, $comp, $cep, $cidade);
        $contato = new Contato(0, $tel, $cel, $email, $endereco);
        $ctt = $contato->insert(Banco::getInstance()->getConnection());
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
            return json_encode('Parâmetros do contato inválidos.');
        }

        $contato = new Contato($ctt, $tel, $cel, $email, $endereco);
        $pessoa = new PessoaJuridica(0, $rs, $nf, $cnpj, $contato);
        $pes = $pessoa->insert(Banco::getInstance()->getConnection());
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
            return json_encode('Parâmetros da pessoa inválidos.');
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
            return json_encode('Parâmetros da representação inválidos.');
        }

        Banco::getInstance()->getConnection()->commit();
        Banco::getInstance()->getConnection()->close();

        return json_encode('');
    }
}