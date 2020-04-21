<?php namespace scr\control;

use scr\model\Cidade;
use scr\model\Contato;
use scr\model\Endereco;
use scr\model\PessoaJuridica;
use scr\model\Representacao;
use scr\util\Banco;

class RepresentacaoDetalhesControl
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

    public function verificarCnpj(string $cnpj)
    {
        $result = false;
        if (Banco::getInstance()->open())
        {
            $result = PessoaJuridica::verifyCnpj(Banco::getInstance()->getConnection(), $cnpj);
            Banco::getInstance()->getConnection()->close();
        }

        return json_encode($result);
    }

    public function alterar(int $end, int $ctt, int $pes, int $rep, string $rs, string $nf, string $cnpj, string $tel, string $cel, string $email, string $rua, string $num, string $bairro, string $comp, string $cep, int $cid)
    {
        if (!Banco::getInstance()->open()) return json_encode('Erro ao conectar-se ao banco de dados.');

        $cidade = Cidade::getById(Banco::getInstance()->getConnection(), $cid);

        Banco::getInstance()->getConnection()->begin_transaction();

        $endereco = new Endereco($end, $rua, $num, $bairro, $comp, $cep, $cidade);
        $res1 = $endereco->update(Banco::getInstance()->getConnection());
        if ($res1 == -10 || $res1 == -1)
        {
            Banco::getInstance()->getConnection()->close();
            return json_encode('Ocorreu um problema ao altearar o endereço.');
        }
        if ($res1 == -5)
        {
            Banco::getInstance()->getConnection()->close();
            return json_encode('Parâmetros do endereço inválidos.');
        }

        $contato = new Contato($ctt, $tel, $cel, $email, $endereco);
        $res2 = $contato->update(Banco::getInstance()->getConnection());
        if ($res2 == -10 || $res2 == -1)
        {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode('Ocorreu um problema ao altearar o contato.');
        }
        if ($res2 == -5)
        {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode('Parâmetros do contato inválidos.');
        }

        $pessoa = new PessoaJuridica($pes, $rs, $nf, $cnpj, $contato);
        $res3 = $pessoa->update(Banco::getInstance()->getConnection());
        if ($res3 == -10 || $res3 == -1)
        {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode('Ocorreu um problema ao altearar a pessoa.');
        }
        if ($res3 == -5)
        {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode('Parâmetros da pessoa inválidos.');
        }

        $representacao = new Representacao($rep, date('Y-m-d'), $cidade->getNome() . '/' . $cidade->getEstado()->getSigla(), $pessoa);
        $res4 = $representacao->update();
        if ($res4 == -10 || $res4 == -1)
        {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode('Ocorreu um problema ao altearar a representação.');
        }
        if ($res4 == -5)
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