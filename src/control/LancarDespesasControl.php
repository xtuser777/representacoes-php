<?php


namespace scr\control;


use scr\model\CategoriaContaPagar;
use scr\model\ContaPagar;
use scr\util\Banco;

class LancarDespesasControl
{
    public function obter()
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaPagar())->findAll();

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $conta ContaPagar */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterCategorias()
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $cats = (new CategoriaContaPagar)->findAll();

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $cat CategoriaContaPagar */
        foreach ($cats as $cat) {
            $serial[] = $cat->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltro(string $filtro)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaPagar())->findByDescription($filtro, "con_pag_conta, con_pag_parcela");

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $conta ContaPagar */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorPeriodo(string $data1, string $data2)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaPagar())->findByPeriod($data1, $data2, "con_pag_conta, con_pag_parcela");

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $conta ContaPagar */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltroPeriodo(string $filtro, string $data1, string $data2)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaPagar())->findByDescriptionPeriod($filtro, $data1, $data2, "con_pag_conta, con_pag_parcela");

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $conta ContaPagar */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function ordenar(string $col)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaPagar())->findAll();

        Banco::getInstance()->getConnection()->close();

        if (count($contas) > 0) {
            switch ($col) {
                case "1":
                    break;
                case "2":
                    usort($contas, function (ContaPagar $a, ContaPagar $b) {
                        if ($a->getConta() === $b->getConta()) return 0;
                        return (($a->getConta() < $b->getConta()) ? -1 : 1);
                    });
                    break;
                case "3":
                    usort($contas, function (ContaPagar $a, ContaPagar $b) {
                        if ($a->getConta() === $b->getConta()) return 0;
                        return (($a->getConta() > $b->getConta()) ? -1 : 1);
                    });
                    break;
                case "4":
                    usort($contas, function (ContaPagar $a, ContaPagar $b) {
                        if (strcasecmp($a->getDescricao(), $b->getDescricao()) === 0) return 0;
                        return ((strcasecmp($a->getDescricao(), $b->getDescricao()) < 0) ? -1 : 1);
                    });
                    break;
                case "5":
                    usort($contas, function (ContaPagar $a, ContaPagar $b) {
                        if (strcasecmp($a->getDescricao(), $b->getDescricao()) === 0) return 0;
                        return ((strcasecmp($a->getDescricao(), $b->getDescricao()) > 0) ? -1 : 1);
                    });
                    break;
                case "6":
                    usort($contas, function (ContaPagar $a, ContaPagar $b) {
                        if ($a->getParcela() === $b->getParcela()) return 0;
                        return (($a->getParcela() < $b->getParcela()) ? -1 : 1);
                    });
                    break;
                case "7":
                    usort($contas, function (ContaPagar $a, ContaPagar $b) {
                        if ($a->getParcela() === $b->getParcela()) return 0;
                        return (($a->getParcela() > $b->getParcela()) ? -1 : 1);
                    });
                    break;
                case "8":
                    usort($contas, function (ContaPagar $a, ContaPagar $b) {
                        if (strcasecmp($a->getCategoria()->getDescricao(), $b->getCategoria()->getDescricao()) === 0) return 0;
                        return ((strcasecmp($a->getCategoria()->getDescricao(), $b->getCategoria()->getDescricao()) < 0) ? -1 : 1);
                    });
                    break;
                case "9":
                    usort($contas, function (ContaPagar $a, ContaPagar $b) {
                        if (strcasecmp($a->getCategoria()->getDescricao(), $b->getCategoria()->getDescricao()) === 0) return 0;
                        return ((strcasecmp($a->getCategoria()->getDescricao(), $b->getCategoria()->getDescricao()) > 0) ? -1 : 1);
                    });
                    break;
                case "12":
                    usort($contas, function (ContaPagar $a, ContaPagar $b) {
                        if (strcasecmp($a->getVencimento(), $b->getVencimento()) === 0) return 0;
                        return ((strcasecmp($a->getVencimento(), $b->getVencimento()) < 0) ? -1 : 1);
                    });
                    break;
                case "13":
                    usort($contas, function (ContaPagar $a, ContaPagar $b) {
                        if (strcasecmp($a->getVencimento(), $b->getVencimento()) === 0) return 0;
                        return ((strcasecmp($a->getVencimento(), $b->getVencimento()) > 0) ? -1 : 1);
                    });
                    break;
                case "14":
                    usort($contas, function (ContaPagar $a, ContaPagar $b) {
                        if (strcasecmp($a->getAutor()->getFuncionario()->getPessoa()->getNome(), $b->getAutor()->getFuncionario()->getPessoa()->getNome()) === 0) return 0;
                        return ((strcasecmp($a->getAutor()->getFuncionario()->getPessoa()->getNome(), $b->getAutor()->getFuncionario()->getPessoa()->getNome()) < 0) ? -1 : 1);
                    });
                    break;
                case "15":
                    usort($contas, function (ContaPagar $a, ContaPagar $b) {
                        if (strcasecmp($a->getAutor()->getFuncionario()->getPessoa()->getNome(), $b->getAutor()->getFuncionario()->getPessoa()->getNome()) === 0) return 0;
                        return ((strcasecmp($a->getAutor()->getFuncionario()->getPessoa()->getNome(), $b->getAutor()->getFuncionario()->getPessoa()->getNome()) > 0) ? -1 : 1);
                    });
                    break;
                case "16":
                    usort($contas, function (ContaPagar $a, ContaPagar $b) {
                        if ($a->getValor() === $b->getValor()) return 0;
                        return (($a->getValor() < $b->getValor()) ? -1 : 1);
                    });
                    break;
                case "17":
                    usort($contas, function (ContaPagar $a, ContaPagar $b) {
                        if ($a->getValor() === $b->getValor()) return 0;
                        return (($a->getValor() > $b->getValor()) ? -1 : 1);
                    });
                    break;
            }
        }

        $serial = [];
        /** @var $conta ContaPagar */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function enviar(int $id)
    {
        if ($id <= 0)
            return json_encode("Parâmetro inválido.");

        Banco::getInstance()->open();
        $conta = (new  ContaPagar())->findById($id);
        Banco::getInstance()->getConnection()->close();

        if ($conta->getSituacao() > 1 || strlen($conta->getDataPagamento()) > 0)
            return json_encode("Não é possível alterar uma conta já paga.");

        if ($conta->getMotorista() || $conta->getVendedor())
            return json_encode("Não é possível alterar uma conta criada por um pedido.");

        setcookie("DESP", $id, time() + 3600, "/", "", 0 , 1);

        return json_encode("");
    }

    public function excluir(int $id)
    {
        if (!Banco::getInstance()->open())
            return json_encode("Erro ao conectar-se ao banco de dados.");

        $conta = (new ContaPagar())->findById($id);
        if (!$conta)
            return json_encode("Registro não encontrado.");

        if ($conta->getSituacao() > 1 || strlen($conta->getDataPagamento()) > 0)
            return json_encode("Não é possível remover uma conta já paga.");

        if ($conta->getMotorista() || $conta->getVendedor())
            return json_encode("Não é possível remover uma conta criada por um pedido, remova o pedido antes.");

        $parcelas = [];
        if ($conta->getTipo() > 1) {
            $parcelas = (new ContaPagar())->findByCount($conta->getConta());
        }

        Banco::getInstance()->getConnection()->begin_transaction();

        if ($conta->getTipo() === 1) {
            $cp = $conta->delete();
            if ($cp == -10 || $cp == -1) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getconnection()->close();
                return json_encode("Ocorreu um problema ao excluir a despesa.");
            }
            if ($cp == -5) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getconnection()->close();
                return json_encode("Parâmetro inválido.");
            }

            Banco::getInstance()->getConnection()->commit();
            Banco::getInstance()->getconnection()->close();

            return json_encode("");
        } else {
            $cp = 0;
            for ($i = $conta->getParcela()-1; $i < count($parcelas) && $cp >= 0; $i++) {
                $cp = $parcelas[$i]->delete();
                if ($cp == -10 || $cp == -1) {
                    Banco::getInstance()->getConnection()->rollback();
                    Banco::getInstance()->getconnection()->close();
                    return json_encode("Ocorreu um problema ao excluir a despesa.");
                }
                if ($cp == -5) {
                    Banco::getInstance()->getConnection()->rollback();
                    Banco::getInstance()->getconnection()->close();
                    return json_encode("Parâmetro inválido.");
                }
            }

            Banco::getInstance()->getConnection()->commit();
            Banco::getInstance()->getconnection()->close();

            return json_encode("");
        }

    }
}