<?php


namespace scr\control;


use scr\model\ContaPagar;
use scr\model\FormaPagamento;
use scr\model\PedidoFrete;
use scr\model\PedidoVenda;
use scr\util\Banco;

class FormaPagamentoControl
{
    public function obter()
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $formas = FormaPagamento::findAll();

        Banco::getInstance()->getConnection()->close();

        $jarray = [];
        /** @var $forma FormaPagamento */
        foreach ($formas as $forma) {
            $jarray[] = $forma->jsonSerialize();
        }

        return json_encode($jarray);
    }

    public function obterPorChave(string $chave)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $formas = FormaPagamento::findByKey($chave);

        Banco::getInstance()->getConnection()->close();

        $jarray = [];
        /** @var $forma FormaPagamento */
        foreach ($formas as $forma) {
            $jarray[] = $forma->jsonSerialize();
        }

        return json_encode($jarray);
    }

    public function ordenar(string $col)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $formas = FormaPagamento::findAll();

        Banco::getInstance()->getConnection()->close();

        if (count($formas) > 0) {
            switch ($col) {
                case "1":
                    usort($formas, function (FormaPagamento $a, FormaPagamento $b) {
                        if ($a->getId() === $b->getId()) return 0;
                        return (($a->getId() < $b->getId()) ? -1 : 1);
                    });
                    break;
                case "2":
                    usort($formas, function (FormaPagamento $a, FormaPagamento $b) {
                        if ($a->getId() === $b->getId()) return 0;
                        return (($a->getId() > $b->getId()) ? -1 : 1);
                    });
                    break;
                case "3":
                    usort($formas, function (FormaPagamento $a, FormaPagamento $b) {
                        if (strcasecmp($a->getDescricao(), $b->getDescricao()) === 0) return 0;
                        return ((strcasecmp($a->getDescricao(), $b->getDescricao()) < 0) ? -1 : 1);
                    });
                    break;
                case "4":
                    usort($formas, function (FormaPagamento $a, FormaPagamento $b) {
                        if (strcasecmp($a->getDescricao(), $b->getDescricao()) === 0) return 0;
                        return ((strcasecmp($a->getDescricao(), $b->getDescricao()) > 0) ? -1 : 1);
                    });
                    break;
                case "5":
                    usort($formas, function (FormaPagamento $a, FormaPagamento $b) {
                        if ($a->getVinculo() === $b->getVinculo()) return 0;
                        return (($a->getVinculo() < $b->getVinculo()) ? -1 : 1);
                    });
                    break;
                case "6":
                    usort($formas, function (FormaPagamento $a, FormaPagamento $b) {
                        if ($a->getVinculo() === $b->getVinculo()) return 0;
                        return (($a->getVinculo() > $b->getVinculo()) ? -1 : 1);
                    });
                    break;
                case "7":
                    usort($formas, function (FormaPagamento $a, FormaPagamento $b) {
                        if ($a->getPrazo() === $b->getPrazo()) return 0;
                        return (($a->getPrazo() < $b->getPrazo()) ? -1 : 1);
                    });
                    break;
                case "8":
                    usort($formas, function (FormaPagamento $a, FormaPagamento $b) {
                        if ($a->getPrazo() === $b->getPrazo()) return 0;
                        return (($a->getPrazo() > $b->getPrazo()) ? -1 : 1);
                    });
                    break;
            }
        }

        $jarray = [];
        /** @var $forma FormaPagamento */
        foreach ($formas as $forma) {
            $jarray[] = $forma->jsonSerialize();
        }

        return json_encode($jarray);
    }

    public function enviar(int $id)
    {
        if ($id <= 0) {
            return json_encode('Parâmetro inválido.');
        }

        Banco::getInstance()->open();

        $pv = (new PedidoVenda())->findRelationsByFP($id);
        if ($pv > 0)
            return json_encode('Esta forma de pagamento está vinculada a um ou mais pedidos de venda.');

        $pf = (new PedidoFrete())->findRelationsByFP($id);
        if ($pf > 0)
            return json_encode('Esta forma de pagamento está vinculada a um ou mais pedidos de frete.');

        $pfm = (new PedidoFrete())->findRelationsByFPM($id);
        if ($pfm > 0)
            return json_encode('Esta forma de pagamento está vinculada a um ou mais pagamentos a motorista.');

        $cp = (new ContaPagar())->findRelationsByFP($id);
        if ($cp > 0)
            return json_encode('Esta forma de pagamento está vinculada a uma ou mais contas a pagar.');

        Banco::getInstance()->getConnection()->close();

        setcookie('FORMA', $id, time() + (3600), "/", "", 0, 1);

        return json_encode('');
    }

    public function excluir(int $id)
    {
        if (!Banco::getInstance()->open())
            return json_encode("Erro ao conectar-se ao banco de dados.");

        $pv = (new PedidoVenda())->findRelationsByFP($id);
        if ($pv > 0)
            return json_encode('Esta forma de pagamento está vinculada a um ou mais pedidos de venda.');

        $pf = (new PedidoFrete())->findRelationsByFP($id);
        if ($pf > 0)
            return json_encode('Esta forma de pagamento está vinculada a um ou mais pedidos de frete.');

        $pfm = (new PedidoFrete())->findRelationsByFPM($id);
        if ($pfm > 0)
            return json_encode('Esta forma de pagamento está vinculada a um ou mais pagamentos a motorista.');

        $cp = (new ContaPagar())->findRelationsByFP($id);
        if ($cp > 0)
            return json_encode('Esta forma de pagamento está vinculada a uma ou mais contas a pagar.');

        $forma = FormaPagamento::findById($id);
        if (!$forma)
            return json_encode("Registro não encontrado.");

        Banco::getInstance()->getConnection()->begin_transaction();
        $res = $forma->delete();
        if ($res == -10 || $res == -1) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Ocorreu um erro ao excluir a forma de pagamento.");
        }
        if ($res == -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Parâmetros incorretos.");
        }

        Banco::getInstance()->getConnection()->commit();
        Banco::getInstance()->getConnection()->close();

        return json_encode("");
    }
}