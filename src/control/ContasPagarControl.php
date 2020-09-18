<?php


namespace scr\control;


use scr\model\Categoria;
use scr\model\ContaPagar;
use scr\model\FormaPagamento;
use scr\util\Banco;

class ContasPagarControl
{
    public function obter()
    {
        if (!Banco::getInstance()->open()) return json_encode([]);
        $contas = (new ContaPagar())->findAll();
        Banco::getInstance()->getConnection()->close();
        $serial = [];
        /** @var $conta ContaPagar */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltro(string $filtro)
    {
        if (!Banco::getInstance()->open()) return json_encode([]);
        $contas = (new ContaPagar())->findByDescription($filtro);
        Banco::getInstance()->getConnection()->close();
        $serial = [];
        /** @var $conta ContaPagar */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorData(string $data)
    {
        if (!Banco::getInstance()->open()) return json_encode([]);
        $contas = (new ContaPagar())->findByDate($data);
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
        if (!Banco::getInstance()->open()) return json_encode([]);
        $contas = (new ContaPagar())->findByPeriod($data1, $data2);
        Banco::getInstance()->getConnection()->close();
        $serial = [];
        /** @var $conta ContaPagar */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltroData(string $filtro, string $data)
    {
        if (!Banco::getInstance()->open()) return json_encode([]);
        $contas = (new ContaPagar())->findByDescriptionDate($filtro, $data);
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
        if (!Banco::getInstance()->open()) return json_encode([]);
        $contas = (new ContaPagar())->findByDescriptionPeriod($filtro, $data1, $data2);
        Banco::getInstance()->getConnection()->close();
        $serial = [];
        /** @var $conta ContaPagar */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterFormas()
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $formas = FormaPagamento::findByPayment();

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $forma FormaPagamento */
        foreach ($formas as $forma) {
            $serial[] = $forma->jsonSerialize();
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
                    usort($contas, function (ContaPagar $a, ContaPagar $b) {
                        if (strcasecmp($a->getDescricao(), $b->getDescricao()) === 0) return 0;
                        return ((strcasecmp($a->getDescricao(), $b->getDescricao()) < 0) ? -1 : 1);
                    });
                    break;

                case "2":
                    usort($contas, function (ContaPagar $a, ContaPagar $b) {
                        if (strcasecmp($a->getDescricao(), $b->getDescricao()) === 0) return 0;
                        return ((strcasecmp($a->getDescricao(), $b->getDescricao()) > 0) ? -1 : 1);
                    });
                    break;

                case "3":
                    usort($contas, function (ContaPagar $a, ContaPagar $b) {
                        if (strcasecmp($a->getEmpresa(), $b->getEmpresa()) === 0) return 0;
                        return ((strcasecmp($a->getEmpresa(), $b->getEmpresa()) < 0) ? -1 : 1);
                    });
                    break;

                case "4":
                    usort($contas, function (ContaPagar $a, ContaPagar $b) {
                        if (strcasecmp($a->getEmpresa(), $b->getEmpresa()) === 0) return 0;
                        return ((strcasecmp($a->getEmpresa(), $b->getEmpresa()) > 0) ? -1 : 1);
                    });
                    break;

                case "5":
                    usort($contas, function (ContaPagar $a, ContaPagar $b) {
                        if (strcasecmp($a->getCategoria()->getDescricao(), $b->getCategoria()->getDescricao()) === 0) return 0;
                        return ((strcasecmp($a->getCategoria()->getDescricao(), $b->getCategoria()->getDescricao()) < 0) ? -1 : 1);
                    });
                    break;

                case "6":
                    usort($contas, function (ContaPagar $a, ContaPagar $b) {
                        if (strcasecmp($a->getCategoria()->getDescricao(), $b->getCategoria()->getDescricao()) === 0) return 0;
                        return ((strcasecmp($a->getCategoria()->getDescricao(), $b->getCategoria()->getDescricao()) > 0) ? -1 : 1);
                    });
                    break;

                case "7":
                    usort($contas, function (ContaPagar $a, ContaPagar $b) {
                        if (strcasecmp($a->getData(), $b->getData()) === 0) return 0;
                        return ((strcasecmp($a->getData(), $b->getData()) < 0) ? -1 : 1);
                    });
                    break;

                case "8":
                    usort($contas, function (ContaPagar $a, ContaPagar $b) {
                        if (strcasecmp($a->getData(), $b->getData()) === 0) return 0;
                        return ((strcasecmp($a->getData(), $b->getData()) > 0) ? -1 : 1);
                    });
                    break;

                case "9":
                    usort($contas, function (ContaPagar $a, ContaPagar $b) {
                        if (strcasecmp($a->getVencimento(), $b->getVencimento()) === 0) return 0;
                        return ((strcasecmp($a->getVencimento(), $b->getVencimento()) < 0) ? -1 : 1);
                    });
                    break;

                case "10":
                    usort($contas, function (ContaPagar $a, ContaPagar $b) {
                        if (strcasecmp($a->getVencimento(), $b->getVencimento()) === 0) return 0;
                        return ((strcasecmp($a->getVencimento(), $b->getVencimento()) > 0) ? -1 : 1);
                    });
                    break;

                case "11":
                    usort($contas, function (ContaPagar $a, ContaPagar $b) {
                        if ($a->getValor() === $b->getValor()) return 0;
                        return (($a->getValor() < $b->getValor()) ? -1 : 1);
                    });
                    break;

                case "12":
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

        if ($conta->getSituacao() > 2)
            return json_encode("Não é possível alterar uma conta já paga.");

        setcookie("CONPAG", $id, time() + 3600, "/", "", 0 , 1);

        return json_encode("");
    }

    public function estornar(int $id)
    {
        if ($id <= 0)
            return json_encode("Parâmetro inválido.");

        if (!Banco::getInstance()->open())
            return json_encode("Problemas ao se conectar com o banco de dados.");

        $conta = (new ContaPagar())->findById($id);
        if ($conta === null)
            return json_encode("Registro não encontrado.");

        if ($conta->getSituacao() === 1)
            return json_encode("Esta conta ainda não foi quitada...");

        if ($conta->getPendencia() !== null && $conta->getPendencia()->getSituacao() > 1)
            return json_encode("Esta conta possui pendências pagas... Estorne-as primeiro.");

        Banco::getInstance()->getConnection()->begin_transaction();

        $cp = $conta->estornar();
        if ($cp == -10 || $cp == -1) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getconnection()->close();
            return json_encode("Ocorreu um problema ao estornar a despesa.");
        }
        if ($cp == -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getconnection()->close();
            return json_encode("Parâmetro inválido.");
        }

        if ($conta->getPendencia() !== null) {
            $cpp = $conta->getPendencia()->delete();
            if ($cpp == -10 || $cpp == -1) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getconnection()->close();
                return json_encode("Ocorreu um problema ao excluir a pendência da despesa.");
            }
            if ($cpp == -5) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getconnection()->close();
                return json_encode("Parâmetro inválido.");
            }
        }

        Banco::getInstance()->getConnection()->commit();
        Banco::getInstance()->getconnection()->close();

        return json_encode("");
    }

    /*
    public function excluir(int $id)
    {
        if (!Banco::getInstance()->open()) return json_encode("Erro ao conectar-se ao banco de dados.");
        $conta = (new ContaPagar())->findById($id);
        if (!$conta)
            return json_encode("Registro não encontrado.");

        if ($conta->getSituacao() > 1 || strlen($conta->getDataPagamento()) > 0)
            return json_encode("Não é possível remover uma conta já paga.");

        Banco::getInstance()->getConnection()->begin_transaction();
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
    }
    */
}