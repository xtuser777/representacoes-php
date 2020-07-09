<?php


namespace scr\control;


use scr\model\Cidade;
use scr\model\ItemOrcamentoFrete;
use scr\model\OrcamentoFrete;
use scr\model\OrcamentoVenda;
use scr\model\Produto;
use scr\model\Representacao;
use scr\model\TipoCaminhao;
use scr\model\Usuario;
use scr\util\Banco;

class OrcamentoFreteNovoControl
{
    public function obterVendas()
    {
        if (!Banco::getInstance()->open()) return json_encode([]);
        $vendas = OrcamentoVenda::findAll();
        Banco::getInstance()->getConnection()->close();
        $serial = [];
        /** @var OrcamentoVenda $venda */
        foreach ($vendas as $venda) {
            $serial[] = $venda->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterRepresentacoes()
    {
        if (!Banco::getInstance()->open()) return json_encode([]);
        $representacoes = Representacao::getAll();
        Banco::getInstance()->getConnection()->close();
        $serial = [];
        /** @var Representacao $representacao */
        foreach ($representacoes as $representacao) {
            $serial[] = $representacao->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function calcularPisoMinimo(float $distancia, int $eixos)
    {
        $piso = (new OrcamentoFrete())->calcularPisoMinimo($distancia, $eixos);

        return json_encode($piso);
    }

    public function gravar($desc, $ven, $rep, $cid, $tip, $dist, $peso, $valor, $entrega, $venc, $itens)
    {
        if (!Banco::getInstance()->open()) return json_encode("Erro ao conectar-se ao banco de dados.");
        $venda = OrcamentoVenda::findById($ven);
        $representacao = Representacao::getById($rep);
        $cidade = (new Cidade())->getById($cid);
        $tipo = TipoCaminhao::findById($tip);
        $usuario = Usuario::getById($_SESSION["USER_ID"]);
        Banco::getInstance()->getConnection()->begin_transaction();
        $orcamento = new OrcamentoFrete(
            0, $desc, date('Y-m-d'), $dist, $peso, $valor, $entrega, $venc, $venda, $representacao, $tipo, $cidade, $usuario
        );
        $res = $orcamento->save();
        if ($res === -10 || $res === -1 || $res === 0) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Erro ao tentar gravar os dados do orçamento.");
        }
        if ($res === -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Parâmetros inválidos.");
        }
        $orcamento->setId($res);
        for ($i = 0; $i < count($itens); $i++) {
            $produto = Produto::findById($itens[$i]->produto->id);
            $itemOrc = new ItemOrcamentoFrete($orcamento, $produto, $itens[$i]->quantidade, $itens[$i]->peso);
            $ri = $itemOrc->save();
            if ($ri === -10 || $ri === -1) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return json_encode("Erro ao tentar gravar os dados de um item do orçamento.");
            }
            if ($ri === -5) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return json_encode("Parâmetros inválidos de um item do orçamento.");
            }
        }
        Banco::getInstance()->getConnection()->commit();
        Banco::getInstance()->getConnection()->close();

        return json_encode("");
    }
}