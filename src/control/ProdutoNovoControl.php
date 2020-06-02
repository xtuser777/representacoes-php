<?php namespace scr\control;

use scr\model\Produto;
use scr\model\Representacao;
use scr\util\Banco;

class ProdutoNovoControl
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

    public function gravar(string $descricao, string $medida, float $peso, float $preco, float $precoOut, int $representacao)
    {
        if (!Banco::getInstance()->open()) return json_encode("Erro ao conectar-se ao banco de dados.");

        $rep = Representacao::getById($representacao);

        Banco::getInstance()->getConnection()->begin_transaction();

        $precoOut = $precoOut <= 0 ? $preco : $precoOut;
        $produto = new Produto(0, $descricao, $medida, $peso, $preco, $precoOut, $rep, []);
        $res = $produto->save();
        if ($res == -10 || $res == -1 || $res == 0) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Ocorreu um erro ao salvar o produto.");
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