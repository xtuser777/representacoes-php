<?php


namespace scr\control;


use scr\model\Categoria;
use scr\model\ContaPagar;
use scr\model\PedidoFrete;
use scr\model\Usuario;
use scr\util\Banco;

class LancarDespesasNovoControl
{
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

    public function lancar(string $empresa, int $categoria, int $pedido, string $descricao, string $data, float $valor, string $vencimento)
    {
        if (Banco::getInstance()->open() === false)
            return json_encode("Problema ao conectar-se ao bano de dados.");

        $cat = Categoria::findById($categoria);
        $ped = ($pedido > 0) ? (new PedidoFrete())->findById($pedido) : null;
        $autor = Usuario::getById($_COOKIE["USER_ID"]);

        Banco::getInstance()->getConnection()->begin_transaction();

        $despesa = new ContaPagar();
        $despesa->setEmpresa($empresa);
        $despesa->setCategoria($cat);
        $despesa->setPedidoFrete($ped);
        $despesa->setDescricao($descricao);
        $despesa->setData($data);
        $despesa->setValor($valor);
        $despesa->setVencimento($vencimento);
        $despesa->setSituacao(1);
        $despesa->setAutor($autor);

        $des = $despesa->save(1);
        if ($des === -10 || $des === -1) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Problema ao salvar a despesa no banco de dados.");
        }
        if ($des === -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Parâmetro ou parâmetros inválidos.");
        }

        Banco::getInstance()->getConnection()->commit();
        Banco::getInstance()->getConnection()->close();

        return json_encode("");
    }
}