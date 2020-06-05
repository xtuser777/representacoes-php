<?php namespace scr\control;

use scr\model\Produto;
use scr\model\Representacao;
use scr\util\Banco;

class ProdutoDetalhesControl
{
    public function obterRepresentacoes()
    {
        if (!Banco::getInstance()->open()) return json_encode([]);
        $representacoes = Representacao::getAll();
        Banco::getInstance()->getConnection()->close();

        $jarray = [];
        /** @var Representacao $representacao */
        foreach ($representacoes as $representacao) {
            $jarray[] = $representacao->jsonSerialize();
        }

        return json_encode($jarray);
    }

    public function obter()
    {
        if (!isset($_SESSION["PRODUTO"])) return json_encode(null);
        if (!Banco::getInstance()->open()) return json_encode(null);
        /** @var Produto $produto */
        $produto = Produto::findById($_SESSION["PRODUTO"]);
        Banco::getInstance()->getConnection()->close();

        return json_encode(($produto !== null) ? $produto->jsonSerialize() : null);
    }

    public function alterar(int $pro, string $descricao, string $medida, float $peso, float $preco, float $precoOut, int $representacao)
    {
        if (!Banco::getInstance()->open()) return json_encode("Erro ao conectar-se ao banco de dados.");

        $rep = Representacao::getById($representacao);

        Banco::getInstance()->getConnection()->begin_transaction();

        $precoOut = $precoOut <= 0 ? $preco : $precoOut;
        $produto = new Produto($pro, $descricao, $medida, $peso, $preco, $precoOut, $rep, []);
        $res = $produto->update();
        if ($res == -10 || $res == -1) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Ocorreu um problema ao atualizar os dados do produto.");
        }
        if ($res == -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Parâmetros inválidos.");
        }

        Banco::getInstance()->getConnection()->commit();
        Banco::getInstance()->getConnection()->close();

        return json_encode("");
    }
}