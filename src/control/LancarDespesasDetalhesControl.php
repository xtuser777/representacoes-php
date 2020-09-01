<?php


namespace scr\control;


use scr\model\Categoria;
use scr\model\ContaPagar;
use scr\model\PedidoFrete;
use scr\model\Usuario;
use scr\util\Banco;

class LancarDespesasDetalhesControl
{
    public function obter()
    {
        if (!isset($_COOKIE["DESP"]))
            return json_encode("Despesa não selecionada.");

        if (Banco::getInstance()->open() === false)
            return json_encode("Erro ao conectar-se ao banco de dados.");

        $despesa = (new ContaPagar())->findById($_COOKIE["DESP"]);
        Banco::getInstance()->getConnection()->close();

        return json_encode(($despesa !== null) ? $despesa->jsonSerialize() : null);
    }

    public function obterCategorias()
    {
        if (Banco::getInstance()->open() === false)
            return json_encode([]);

        $categorias = Categoria::findAll();
        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $categoria Categoria */
        foreach ($categorias as $categoria) {
            $serial[] = $categoria->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPedidos()
    {
        if (Banco::getInstance()->open() === false)
            return json_encode([]);

        $pedidos = (new PedidoFrete())->findAll();
        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $pedido PedidoFrete */
        foreach ($pedidos as $pedido) {
            $serial[] = $pedido->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function alterar(int $des, string $empresa, int $categoria, int $pedido, string $descricao, string $data, float $valor, string $vencimento)
    {
        if (Banco::getInstance()->open() === false)
            return json_encode("Problema ao conectar-se ao banco de dados.");

        $cat = Categoria::findById($categoria);
        $ped = ($pedido > 0) ? (new PedidoFrete())->findById($pedido) : null;
        $autor = Usuario::getById($_COOKIE["USER_ID"]);

        Banco::getInstance()->getConnection()->begin_transaction();

        $despesa = new ContaPagar();
        $despesa->setId($des);
        $despesa->setEmpresa($empresa);
        $despesa->setCategoria($cat);
        $despesa->setPedidoFrete($ped);
        $despesa->setDescricao($descricao);
        $despesa->setData($data);
        $despesa->setValor($valor);
        $despesa->setVencimento($vencimento);
        $despesa->setSituacao(1);
        $despesa->setAutor($autor);

        $res = $despesa->update();
        if ($res === -10 || $res === -1) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Problema ao salvar as alterações da despesa no banco de dados.");
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