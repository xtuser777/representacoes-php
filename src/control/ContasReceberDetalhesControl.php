<?php


namespace scr\control;


use scr\model\Evento;
use scr\model\FormaPagamento;
use scr\model\ContaReceber;
use scr\model\Usuario;
use scr\util\Banco;

class ContasReceberDetalhesControl
{
    public function obter()
    {
        if (!isset($_COOKIE["CONREC"]))
            return json_encode("Conta não selecionada.");

        if (Banco::getInstance()->open() === false)
            return json_encode("Erro ao conectar-se ao banco de dados.");

        $despesa = (new ContaReceber())->findById($_COOKIE["CONREC"]);
        Banco::getInstance()->getConnection()->close();

        return json_encode(($despesa !== null) ? $despesa->jsonSerialize() : null);
    }

    public function obterFormas()
    {
        if (Banco::getInstance()->open() === false)
            return json_encode([]);

        $formas = FormaPagamento::findByReceive();
        
        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $forma FormaPagamento */
        foreach ($formas as $forma) {
            $serial[] = $forma->jsonSerialize();
        }

        return json_encode($serial);
    }
    
    public function obterPorConta(int $conta)
    {
        if (Banco::getInstance()->open() === false)
            return json_encode("Erro ao conectar-se ao banco de dados.");

        $despesas = (new ContaReceber())->findByCount($conta);
        
        Banco::getInstance()->getConnection()->close();
        
        $serial = [];
        /** @var $despesa ContaReceber */
        foreach ($despesas as $despesa) {
            $serial[] = $despesa->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function receber($despesa, $forma, $valor, $recebimento)
    {
        if (Banco::getInstance()->open() === false)
            return json_encode("Problema ao conectar-se ao banco de dados.");

        $conta = (new ContaReceber())->findById($despesa);

        $autor = Usuario::getById($_COOKIE["USER_ID"]);

        Banco::getInstance()->getConnection()->begin_transaction();

        $situacao = 0;
        $restante = 0.0;
        if ($valor < $conta->getValor()) {
            $situacao = 2;
            $restante = $conta->getValor() - $valor;
        } else {
            $situacao = 3;
        }

        $des = 0;
        if ($situacao === 2) {
            $pendencia = new ContaReceber();
            $pendencia->setPagador($conta->getPagador());
            $pendencia->setRepresentacao($conta->getRepresentacao());
            $pendencia->setPedidoVenda($conta->getPedidoVenda());
            $pendencia->setPedidoFrete($conta->getPedidoFrete());
            $pendencia->setConta($conta->getConta());
            $pendencia->setComissao($conta->isComissao());
            $pendencia->setDescricao($conta->getDescricao());
            $pendencia->setData($conta->getData());
            $pendencia->setValor($restante);
            $pendencia->setVencimento($conta->getVencimento());
            $pendencia->setSituacao(1);
            $pendencia->setAutor($conta->getAutor());

            $des = $pendencia->save();
            if ($des === -10 || $des === -1) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return json_encode("Problema ao salvar a pendência no banco de dados.");
            }
            if ($des === -5) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return json_encode("Parâmetro ou parâmetros inválidos.");
            }
        }

        $res = $conta->receber($forma, $valor, $recebimento, $situacao, $des);
        if ($res === -10 || $res === -1) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Problema ao receber a conta no banco de dados.");
        }
        if ($res === -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Parâmetros inválidos.");
        }

        $evt = $this->criarEvento($conta, $autor, $situacao);
        if ($evt === -10 || $evt === -1) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Problema ao criar o evento.");
        }
        if ($evt === -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Parâmetros inválidos.");
        }

        Banco::getInstance()->getConnection()->commit();
        Banco::getInstance()->getConnection()->close();

        return json_encode("");
    }

    /**
     * @param ContaReceber $conta
     * @param Usuario $autor
     * @param int $situacao
     * @return int
     */
    private function criarEvento(ContaReceber $conta, Usuario $autor, int $situacao): int
    {
        if ($conta === null || $autor == null || $situacao <= 0)
            return -5;

        $evento = new Evento();
        if ($situacao === 2)
            $evento->setDescricao("A conta a receber \"" . $conta->getDescricao() . "\" foi recebida parcialmente.");
        else
            $evento->setDescricao("A conta a receber \"" . $conta->getDescricao() . "\" foi recebida.");
        $evento->setData(date("Y-m-d"));
        $evento->setHora(date("H:i:s"));
        $evento->setAutor($autor);
        if ($conta->getPedidoVenda())
            $evento->setPedidoVenda($conta->getPedidoVenda());

        if ($conta->getPedidoFrete())
            $evento->setPedidoFrete($conta->getPedidoFrete());

        return $evento->save();
    }
}
