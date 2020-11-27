<?php


namespace scr\control;


use scr\model\Evento;
use scr\model\FormaPagamento;
use scr\model\ContaPagar;
use scr\model\Usuario;
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
    
    public function obterPorConta(int $conta)
    {
        if (Banco::getInstance()->open() === false)
            return json_encode("Erro ao conectar-se ao banco de dados.");

        $despesas = (new ContaPagar())->findByCount($conta);
        
        Banco::getInstance()->getConnection()->close();
        
        $serial = [];
        /** @var $despesa ContaPagar */
        foreach ($despesas as $despesa) {
            $serial[] = $despesa->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function quitar($despesa, $forma, $valor, $pagamento)
    {
        if (Banco::getInstance()->open() === false)
            return json_encode("Problema ao conectar-se ao banco de dados.");

        $conta = (new ContaPagar())->findById($despesa);

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
            $pendencia = new ContaPagar();
            $pendencia->setEmpresa($conta->getEmpresa());
            $pendencia->setCategoria($conta->getCategoria());
            $pendencia->setPedidoFrete($conta->getPedidoFrete());
            $pendencia->setConta($conta->getConta());
            $pendencia->setDescricao($conta->getDescricao());
            $pendencia->setTipo($conta->getTipo());
            $pendencia->setData($conta->getData());
            $pendencia->setParcela($conta->getParcela());
            $pendencia->setValor($restante);
            $pendencia->setVencimento($conta->getVencimento());
            $pendencia->setSituacao(1);
            $pendencia->setAutor($conta->getAutor());

            $des = $pendencia->save();
            if ($des === -10 || $des === -1) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return json_encode("Problema ao salvar a pendência da despesa no banco de dados.");
            }
            if ($des === -5) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return json_encode("Parâmetro ou parâmetros inválidos.");
            }
        }

        $res = $conta->quitar($forma, $valor, $pagamento, $situacao, $des);
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
     * @param ContaPagar $conta
     * @param Usuario $autor
     * @param int $situacao
     * @return int
     */
    private function criarEvento(ContaPagar $conta, Usuario $autor, int $situacao): int
    {
        if ($conta === null || $autor === null || $situacao <= 0)
            return -5;

        $evento = new Evento();
        if ($situacao === 2)
            $evento->setDescricao("A conta a pagar \"". $conta->getDescricao() ."\" foi quitada parcialmente.");
        else
            $evento->setDescricao("A conta a pagar \"". $conta->getDescricao() ."\" foi quitada.");
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
