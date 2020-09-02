<?php


namespace scr\control;


use scr\model\FormaPagamento;
use scr\model\ContaPagar;
use scr\util\Banco;

class ContasPagarDetalhesControl
{
    public function obter()
    {
        if (!isset($_COOKIE["CONPAG"]))
            return json_encode("Conta não selecionada.");

        if (Banco::getInstance()->open() === false)
            return json_encode("Erro ao conectar-se ao banco de dados.");

        $despesa = (new ContaPagar())->findById($_COOKIE["CONPAG"]);
        Banco::getInstance()->getConnection()->close();

        return json_encode(($despesa !== null) ? $despesa->jsonSerialize() : null);
    }

    public function obterFormas()
    {
        if (Banco::getInstance()->open() === false)
            return json_encode([]);

        $formas = FormaPagamento::findAll();
        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $forma FormaPagamento */
        foreach ($formas as $forma) {
            $serial[] = $forma->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function quitar($despesa, $forma, $valor, $pagamento)
    {
        if (Banco::getInstance()->open() === false)
            return json_encode("Problema ao conectar-se ao banco de dados.");

        $conta = (new ContaPagar())->findById($despesa);

        Banco::getInstance()->getConnection()->begin_transaction();

        $situacao = 0;
        if ($valor < $conta->getValor()) {
            $situacao = 2;
        } else {
            $situacao = 3;
        }

        $res = $conta->quitar($forma, $valor, $pagamento, $situacao);
        if ($res === -10 || $res === -1) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Problema ao quitar a despesa no banco de dados.");
        }
        if ($res === -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Parâmetros inválidos.");
        }

        Banco::getInstance()->getConnection()->commit();
        Banco::getInstance()->getConnection()->close();

        return json_encode("");
    }
}
