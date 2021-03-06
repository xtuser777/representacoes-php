<?php


namespace scr\control;


use scr\model\ItemOrcamentoFrete;
use scr\model\OrcamentoFrete;
use scr\model\PedidoFrete;
use scr\util\Banco;

class OrcamentoFreteControl
{
    public function obter()
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $orcamentos = (new OrcamentoFrete())->findAll();

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $orcamento OrcamentoFrete */
        foreach ($orcamentos as $orcamento) {
            $serial[] = $orcamento->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltro(string $filtro)
    {
        if (!Banco::getInstance()->open()) return json_encode([]);
        $orcamentos = (new OrcamentoFrete())->findByKey($filtro);
        Banco::getInstance()->getConnection()->close();
        $serial = [];
        /** @var $orcamento OrcamentoFrete */
        foreach ($orcamentos as $orcamento) {
            $serial[] = $orcamento->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorData(string $data)
    {
        if (!Banco::getInstance()->open()) return json_encode([]);
        $orcamentos = (new OrcamentoFrete())->findByDate($data);
        Banco::getInstance()->getConnection()->close();
        $serial = [];
        /** @var $orcamento OrcamentoFrete */
        foreach ($orcamentos as $orcamento) {
            $serial[] = $orcamento->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltroData(string $filtro, string $data)
    {
        if (!Banco::getInstance()->open()) return json_encode([]);
        $orcamentos = (new OrcamentoFrete())->findByKeyDate($filtro, $data);
        Banco::getInstance()->getConnection()->close();
        $serial = [];
        /** @var $orcamento OrcamentoFrete */
        foreach ($orcamentos as $orcamento) {
            $serial[] = $orcamento->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function ordenar(string $col)
    {
        if (!Banco::getInstance()->open()) return json_encode([]);
        $orcamentos = (new OrcamentoFrete())->findAll();
        Banco::getInstance()->getConnection()->close();
        if (count($orcamentos) > 0) {
            switch ($col) {
                case "1":
                    usort($orcamentos, function (OrcamentoFrete $a, OrcamentoFrete $b) {
                        if ($a->getId() === $b->getId()) return 0;
                        return (($a->getId() < $b->getId()) ? -1 : 1);
                    });
                    break;
                case "2":
                    usort($orcamentos, function (OrcamentoFrete $a, OrcamentoFrete $b) {
                        if ($a->getId() === $b->getId()) return 0;
                        return (($a->getId() > $b->getId()) ? -1 : 1);
                    });
                    break;
                case "3":
                    usort($orcamentos, function (OrcamentoFrete $a, OrcamentoFrete $b) {
                        if (strcasecmp($a->getDescricao(), $b->getDescricao()) === 0) return 0;
                        return ((strcasecmp($a->getDescricao(), $b->getDescricao()) < 0) ? -1 : 1);
                    });
                    break;
                case "4":
                    usort($orcamentos, function (OrcamentoFrete $a, OrcamentoFrete $b) {
                        if (strcasecmp($a->getDescricao(), $b->getDescricao()) === 0) return 0;
                        return ((strcasecmp($a->getDescricao(), $b->getDescricao()) > 0) ? -1 : 1);
                    });
                    break;
                case "5":
                    usort($orcamentos, function (OrcamentoFrete $a, OrcamentoFrete $b) {
                        if ($a->getOrcamentoVenda() === null && $b->getOrcamentoVenda() === null) {
                            if (strcasecmp($a->getRepresentacao()->getPessoa()->getNomeFantasia(), $b->getRepresentacao()->getPessoa()->getNomeFantasia()) === 0) return 0;
                            return ((strcasecmp($a->getRepresentacao()->getPessoa()->getNomeFantasia(), $b->getRepresentacao()->getPessoa()->getNomeFantasia()) < 0) ? -1 : 1);
                        } elseif ($a->getOrcamentoVenda() === null) {
                            if (strcasecmp($a->getRepresentacao()->getPessoa()->getNomeFantasia(), $b->getOrcamentoVenda()->getNomeCliente()) === 0) return 0;
                            return ((strcasecmp($a->getRepresentacao()->getPessoa()->getNomeFantasia(), $b->getOrcamentoVenda()->getNomeCliente()) < 0) ? -1 : 1);
                        } elseif ($b->getOrcamentoVenda() === null) {
                            if (strcasecmp($a->getOrcamentoVenda()->getNomeCliente(), $b->getRepresentacao()->getPessoa()->getNomeFantasia()) === 0) return 0;
                            return ((strcasecmp($a->getOrcamentoVenda()->getNomeCliente(), $b->getRepresentacao()->getPessoa()->getNomeFantasia()) < 0) ? -1 : 1);
                        } else {
                            if (strcasecmp($a->getOrcamentoVenda()->getNomeCliente(), $b->getOrcamentoVenda()->getNomeCliente()) === 0) return 0;
                            return ((strcasecmp($a->getOrcamentoVenda()->getNomeCliente(), $b->getOrcamentoVenda()->getNomeCliente()) < 0) ? -1 : 1);
                        }
                    });
                    break;
                case "6":
                    usort($orcamentos, function (OrcamentoFrete $a, OrcamentoFrete $b) {
                        if ($a->getOrcamentoVenda() === null && $b->getOrcamentoVenda() === null) {
                            if (strcasecmp($a->getRepresentacao()->getPessoa()->getNomeFantasia(), $b->getRepresentacao()->getPessoa()->getNomeFantasia()) === 0) return 0;
                            return ((strcasecmp($a->getRepresentacao()->getPessoa()->getNomeFantasia(), $b->getRepresentacao()->getPessoa()->getNomeFantasia()) > 0) ? -1 : 1);
                        } elseif ($a->getOrcamentoVenda() === null) {
                            if (strcasecmp($a->getRepresentacao()->getPessoa()->getNomeFantasia(), $b->getOrcamentoVenda()->getNomeCliente()) === 0) return 0;
                            return ((strcasecmp($a->getRepresentacao()->getPessoa()->getNomeFantasia(), $b->getOrcamentoVenda()->getNomeCliente()) > 0) ? -1 : 1);
                        } elseif ($b->getOrcamentoVenda() === null) {
                            if (strcasecmp($a->getOrcamentoVenda()->getNomeCliente(), $b->getRepresentacao()->getPessoa()->getNomeFantasia()) === 0) return 0;
                            return ((strcasecmp($a->getOrcamentoVenda()->getNomeCliente(), $b->getRepresentacao()->getPessoa()->getNomeFantasia()) > 0) ? -1 : 1);
                        } else {
                            if (strcasecmp($a->getOrcamentoVenda()->getNomeCliente(), $b->getOrcamentoVenda()->getNomeCliente()) === 0) return 0;
                            return ((strcasecmp($a->getOrcamentoVenda()->getNomeCliente(), $b->getOrcamentoVenda()->getNomeCliente()) > 0) ? -1 : 1);
                        }
                    });
                    break;
                case "7":
                    usort($orcamentos, function (OrcamentoFrete $a, OrcamentoFrete $b) {
                        if (strcasecmp($a->getData(), $b->getData()) === 0) return 0;
                        return ((strcasecmp($a->getData(), $b->getData()) < 0) ? -1 : 1);
                    });
                    break;
                case "8":
                    usort($orcamentos, function (OrcamentoFrete $a, OrcamentoFrete $b) {
                        if (strcasecmp($a->getData(), $b->getData()) === 0) return 0;
                        return ((strcasecmp($a->getData(), $b->getData()) > 0) ? -1 : 1);
                    });
                    break;
                case "9":
                    usort($orcamentos, function (OrcamentoFrete $a, OrcamentoFrete $b) {
                        if (strcasecmp($a->getAutor()->getFuncionario()->getPessoa()->getNome(), $b->getAutor()->getFuncionario()->getPessoa()->getNome()) === 0) return 0;
                        return ((strcasecmp($a->getAutor()->getFuncionario()->getPessoa()->getNome(), $b->getAutor()->getFuncionario()->getPessoa()->getNome()) < 0) ? -1 : 1);
                    });
                    break;
                case "10":
                    usort($orcamentos, function (OrcamentoFrete $a, OrcamentoFrete $b) {
                        if (strcasecmp($a->getAutor()->getFuncionario()->getPessoa()->getNome(), $b->getAutor()->getFuncionario()->getPessoa()->getNome()) === 0) return 0;
                        return ((strcasecmp($a->getAutor()->getFuncionario()->getPessoa()->getNome(), $b->getAutor()->getFuncionario()->getPessoa()->getNome()) > 0) ? -1 : 1);
                    });
                    break;
                case "11":
                    usort($orcamentos, function (OrcamentoFrete $a, OrcamentoFrete $b) {
                        if ($a->getValor() === $b->getValor()) return 0;
                        return (($a->getValor() < $b->getValor()) ? -1 : 1);
                    });
                    break;
                case "12":
                    usort($orcamentos, function (OrcamentoFrete $a, OrcamentoFrete $b) {
                        if ($a->getValor() === $b->getValor()) return 0;
                        return (($a->getValor() > $b->getValor()) ? -1 : 1);
                    });
                    break;
            }
        }
        $serial = [];
        /** @var $orcamento OrcamentoFrete */
        foreach ($orcamentos as $orcamento) {
            $serial[] = $orcamento->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function enviar(int $id)
    {
        if ($id <= 0) return json_encode("Parâmetro inválido.");

        Banco::getInstance()->open();

        $pedido = (new PedidoFrete())->findByOrder($id);
        if ($pedido !== null)
            return json_encode("Este orçamento tem vínculo com o pedido \"" . $pedido->getDescricao() . "\"");

        Banco::getInstance()->getConnection()->close();

        setcookie("ORCFRE", $id, time() + 3600, "/", "", 0, 1);

        return json_encode("");
    }

    public function excluir(int $id)
    {
        if (!Banco::getInstance()->open())
            return json_encode("Erro ao conectar-se ao banco de dados.");

        $orcamento = (new OrcamentoFrete())->findById($id);
        if (!$orcamento)
            return json_encode("Registro não encontrado.");

        $pedido = (new PedidoFrete())->findByOrder($id);
        if ($pedido !== null)
            return json_encode("Este orçamento tem vínculo com o pedido \"" . $pedido->getDescricao() . "\"");

        Banco::getInstance()->getConnection()->begin_transaction();

        if (!$this->excluirItens($id))
            return json_encode("Erro ao excluir os itens do orçamento.");

        $orc = $orcamento->delete();
        if ($orc == -10 || $orc == -1) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getconnection()->close();
            return json_encode("Ocorreu um problema ao excluir o orçamento.");
        }
        if ($orc == -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getconnection()->close();
            return json_encode("Parâmetro inválido.");
        }

        Banco::getInstance()->getConnection()->commit();
        Banco::getInstance()->getconnection()->close();

        return json_encode("");
    }

    public function excluirItens(int $orcamento): bool
    {
        $itens = (new ItemOrcamentoFrete())->findAll($orcamento);
        if (count($itens) <= 0) return true;
        /** @var ItemOrcamentoFrete $item */
        foreach ($itens as $item) {
            $iv = $item->delete();
            if ($iv == -10 || $iv == -1) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return false;
            }
        }

        return true;
    }
}