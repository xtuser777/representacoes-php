<?php


namespace scr\control;


use DateInterval;
use DateTime;
use scr\model\Categoria;
use scr\model\ContaPagar;
use scr\model\FormaPagamento;
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

    public function obterConta()
    {
        if (Banco::getInstance()->open() === false)
            return json_encode(0);

        $conta = (new ContaPagar())->findNewCount();

        Banco::getInstance()->getConnection()->close();

        return json_encode($conta);
    }

    public function lancar(string $empresa, int $categoria, int $pedido, int $conta, string $descricao, int $tipo, int $forma, int $intervalo, int $frequencia, string $data, float $valorPago, int $parcelas, float $valor, string $vencimento)
    {
        if (Banco::getInstance()->open() === false)
            return json_encode("Problema ao conectar-se ao bano de dados.");

        $cat = Categoria::findById($categoria);
        $ped = ($pedido > 0) ? (new PedidoFrete())->findById($pedido) : null;
        $fp = ($forma > 0) ? FormaPagamento::findById($forma) : null;
        $autor = Usuario::getById($_COOKIE["USER_ID"]);

        Banco::getInstance()->getConnection()->begin_transaction();

        if ($tipo === 2)
            $valor_parcela = $valor / $parcelas;
        else
            $valor_parcela = $valor;

        $despesa = new ContaPagar();
        $despesa->setEmpresa($empresa);
        $despesa->setCategoria($cat);
        $despesa->setPedidoFrete($ped);
        $despesa->setConta($conta);
        $despesa->setDescricao($descricao);
        $despesa->setTipo($tipo);
        $despesa->setData($data);
        $despesa->setParcela(1);
        $despesa->setValor($valor_parcela);
        $despesa->setVencimento($vencimento);
        $despesa->setSituacao(1);
        $despesa->setAutor($autor);

        $des = $despesa->save();
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

        $sit = 3;
        $pen = 0;
        if ($tipo === 1 && $valorPago < $valor) {
            $sit = 2;
            $valorPendente = $valor - $valorPago;

            $pendencia = new ContaPagar();
            $pendencia->setEmpresa($empresa);
            $pendencia->setCategoria($cat);
            $pendencia->setPedidoFrete($ped);
            $pendencia->setConta($conta);
            $pendencia->setDescricao($descricao);
            $pendencia->setTipo($tipo);
            $pendencia->setData($data);
            $pendencia->setParcela(1);
            $pendencia->setValor($valorPendente);
            $pendencia->setVencimento($vencimento);
            $pendencia->setSituacao(1);
            $pendencia->setAutor($autor);

            $pen = $pendencia->save();
            if ($pen === -10 || $pen === -1) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return json_encode("Problema ao salvar a pendência da despesa no banco de dados.");
            }
            if ($pen === -5) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return json_encode("Parâmetro ou parâmetros inválidos.");
            }
        }

        if ($tipo === 1 && $des > 0) {
	    $despesa->setId($des);
            $des1 = $despesa->quitar($forma, $valorPago, $vencimento, $sit, $pen);
            if ($des1 === -10 || $des1 === -1) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return json_encode("Problema ao quitar a despesa no banco de dados.");
            }
            if ($des1 === -5) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return json_encode("Parâmetro ou parâmetros inválidos.");
            }
        }

        $date = new DateTime($vencimento);

        for ($i = 1; $i < $parcelas && $des > 0; $i++) {
            $despesa = new ContaPagar();
            $despesa->setEmpresa($empresa);
            $despesa->setCategoria($cat);
            $despesa->setPedidoFrete($ped);
            $despesa->setConta($conta);
            $despesa->setDescricao($descricao);
            $despesa->setTipo($tipo);
            $despesa->setData($data);
            $despesa->setParcela($i+1);
            $despesa->setValor($valor_parcela);
            if ($tipo === 2) {
                $date = $date->add(new DateInterval("P".$intervalo."D"));
            } else {
                switch ($frequencia) {
                    case 1:
                        $date = $date->add(new DateInterval("P1M"));
                        break;
                    case 2:
                        $date = $date->add(new DateInterval("P12M"));
                        break;
                }
            }
            $despesa->setVencimento($date->format("Y-m-d"));
            $despesa->setSituacao(1);
            $despesa->setAutor($autor);

            $des = $despesa->save();
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
        }

        Banco::getInstance()->getConnection()->commit();
        Banco::getInstance()->getConnection()->close();

        return json_encode("");
    }
}
