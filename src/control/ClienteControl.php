<?php


namespace scr\control;


use scr\util\Banco;
use scr\model\Endereco;
use scr\model\Contato;
use scr\model\PessoaFisica;
use scr\model\PessoaJuridica;
use scr\model\Cliente;

class ClienteControl
{
    public function obter()
    {
        $jarray = array();
        if (!Banco::getInstance()->open()) return json_encode($jarray);
        $array = Cliente::getAll();
        Banco::getInstance()->getConnection()->close();

        for ($i = 0; $i < count($array); $i++) {
            /** @var Cliente $cli */
            $cli = $array[$i];
            $jarray[] = $cli->jsonSerialize();
        }

        return json_encode($jarray);
    }

    public function obterPorId(int $id)
    {
        if (!Banco::getInstance()->open()) return json_encode(null);
        $array = Cliente::getById($id);
        Banco::getInstance()->getConnection()->close();

        return json_encode($array);
    }

    public function obterPorChave(string $key)
    {
        $jarray = array();
        if (!Banco::getInstance()->open()) return json_encode($jarray);
        $array = Cliente::getByKey($key);
        Banco::getInstance()->getConnection()->close();

        for ($i = 0; $i < count($array); $i++) {
            /** @var Cliente $cli */
            $cli = $array[$i];
            $jarray[] = $cli->jsonSerialize();
        }

        return json_encode($jarray);
    }

    public function obterPorCadastro(string $cad)
    {
        $jarray = array();
        if (!Banco::getInstance()->open()) return json_encode($jarray);
        $array = Cliente::getByCad($cad);
        Banco::getInstance()->getConnection()->close();

        for ($i = 0; $i < count($array); $i++) {
            /** @var Cliente $cli */
            $cli = $array[$i];
            $jarray[] = $cli->jsonSerialize();
        }

        return json_encode($jarray);
    }

    public function obterPorChaveCadastro(string $key, string $cad)
    {
        $jarray = array();
        if (!Banco::getInstance()->open()) return json_encode($jarray);
        $array = Cliente::getByKeyCad($key, $cad);
        Banco::getInstance()->getConnection()->close();

        for ($i = 0; $i < count($array); $i++) {
            /** @var Cliente $cli */
            $cli = $array[$i];
            $jarray[] = $cli->jsonSerialize();
        }

        return json_encode($jarray);
    }

    public function ordenar(string $col)
    {
        $jarray = array();
        if (!Banco::getInstance()->open()) return json_encode($jarray);
        $array = Cliente::getAll();
        Banco::getInstance()->getConnection()->close();

        if (count($array) >= 2) {
            switch ($col) {
                case '1':
                    usort($array, function (Cliente $a, Cliente $b) {
                        if ($a->getId() == $b->getId()) return 0;
                        return (($a->getId() < $b->getId()) ? -1 : 1);
                    });
                    break;
                case '2':
                    usort($array, function (Cliente $a, Cliente $b) {
                        if ($a->getId() == $b->getId()) return 0;
                        return (($a->getId() > $b->getId()) ? -1 : 1);
                    });
                    break;
                case '3':
                    usort($array, function (Cliente $a, Cliente $b) {
                        if ($a->getTipo() == 1 && $b->getTipo() == 1) {
                            if (strcasecmp($a->getPessoaFisica()->getNome(), $b->getPessoaFisica()->getNome()) === 0) return 0;
                            return ((strcasecmp($a->getPessoaFisica()->getNome(), $b->getPessoaFisica()->getNome()) < 0) ? -1 : 1);
                        } elseif ($a->getTipo() == 1) {
                            if (strcasecmp($a->getPessoaFisica()->getNome(), $b->getPessoaJuridica()->getNomeFantasia()) === 0) return 0;
                            return ((strcasecmp($a->getPessoaFisica()->getNome(), $b->getPessoaJuridica()->getNomeFantasia()) < 0) ? -1 : 1);
                        } elseif ($b->getTipo() == 1) {
                            if (strcasecmp($a->getPessoaJuridica()->getNomeFantasia(), $b->getPessoaFisica()->getNome()) === 0) return 0;
                            return ((strcasecmp($a->getPessoaJuridica()->getNomeFantasia(), $b->getPessoaFisica()->getNome()) < 0) ? -1 : 1);
                        } else {
                            if (strcasecmp($a->getPessoaJuridica()->getNomeFantasia(), $b->getPessoaJuridica()->getNomeFantasia()) === 0) return 0;
                            return ((strcasecmp($a->getPessoaJuridica()->getNomeFantasia(), $b->getPessoaJuridica()->getNomeFantasia()) < 0) ? -1 : 1);
                        }
                    });
                    break;
                case '4':
                    usort($array, function (Cliente $a, Cliente $b) {
                        if ($a->getTipo() == 1 && $b->getTipo() == 1) {
                            if (strcasecmp($a->getPessoaFisica()->getNome(), $b->getPessoaFisica()->getNome()) === 0) return 0;
                            return ((strcasecmp($a->getPessoaFisica()->getNome(), $b->getPessoaFisica()->getNome()) > 0) ? -1 : 1);
                        } elseif ($a->getTipo() == 1) {
                            if (strcasecmp($a->getPessoaFisica()->getNome(), $b->getPessoaJuridica()->getNomeFantasia()) === 0) return 0;
                            return ((strcasecmp($a->getPessoaFisica()->getNome(), $b->getPessoaJuridica()->getNomeFantasia()) > 0) ? -1 : 1);
                        } elseif ($b->getTipo() == 1) {
                            if (strcasecmp($a->getPessoaJuridica()->getNomeFantasia(), $b->getPessoaFisica()->getNome()) === 0) return 0;
                            return ((strcasecmp($a->getPessoaJuridica()->getNomeFantasia(), $b->getPessoaFisica()->getNome()) > 0) ? -1 : 1);
                        } else {
                            if (strcasecmp($a->getPessoaJuridica()->getNomeFantasia(), $b->getPessoaJuridica()->getNomeFantasia()) === 0) return 0;
                            return ((strcasecmp($a->getPessoaJuridica()->getNomeFantasia(), $b->getPessoaJuridica()->getNomeFantasia()) > 0) ? -1 : 1);
                        }
                    });
                    break;
                case '5':
                    usort($array, function (Cliente $a, Cliente $b) {
                        if ($a->getTipo() == 1 && $b->getTipo() == 1) {
                            if (strcasecmp($a->getPessoaFisica()->getCpf(), $b->getPessoaFisica()->getCpf()) === 0) return 0;
                            return ((strcasecmp($a->getPessoaFisica()->getCpf(), $b->getPessoaFisica()->getCpf()) < 0) ? -1 : 1);
                        } elseif ($a->getTipo() == 1) {
                            if (strcasecmp($a->getPessoaFisica()->getCpf(), $b->getPessoaJuridica()->getCnpj()) === 0) return 0;
                            return ((strcasecmp($a->getPessoaFisica()->getCpf(), $b->getPessoaJuridica()->getCnpj()) < 0) ? -1 : 1);
                        } elseif ($b->getTipo() == 1) {
                            if (strcasecmp($a->getPessoaJuridica()->getCnpj(), $b->getPessoaFisica()->getCpf()) === 0) return 0;
                            return ((strcasecmp($a->getPessoaJuridica()->getCnpj(), $b->getPessoaFisica()->getCpf()) < 0) ? -1 : 1);
                        } else {
                            if (strcasecmp($a->getPessoaJuridica()->getCnpj(), $b->getPessoaJuridica()->getCnpj()) === 0) return 0;
                            return ((strcasecmp($a->getPessoaJuridica()->getCnpj(), $b->getPessoaJuridica()->getCnpj()) < 0) ? -1 : 1);
                        }
                    });
                    break;
                case '6':
                    usort($array, function (Cliente $a, Cliente $b) {
                        if ($a->getTipo() == 1 && $b->getTipo() == 1) {
                            if (strcasecmp($a->getPessoaFisica()->getCpf(), $b->getPessoaFisica()->getCpf()) === 0) return 0;
                            return ((strcasecmp($a->getPessoaFisica()->getCpf(), $b->getPessoaFisica()->getCpf()) > 0) ? -1 : 1);
                        } elseif ($a->getTipo() == 1) {
                            if (strcasecmp($a->getPessoaFisica()->getCpf(), $b->getPessoaJuridica()->getCnpj()) === 0) return 0;
                            return ((strcasecmp($a->getPessoaFisica()->getCpf(), $b->getPessoaJuridica()->getCnpj()) > 0) ? -1 : 1);
                        } elseif ($b->getTipo() == 1) {
                            if (strcasecmp($a->getPessoaJuridica()->getCnpj(), $b->getPessoaFisica()->getCpf()) === 0) return 0;
                            return ((strcasecmp($a->getPessoaJuridica()->getCnpj(), $b->getPessoaFisica()->getCpf()) > 0) ? -1 : 1);
                        } else {
                            if (strcasecmp($a->getPessoaJuridica()->getCnpj(), $b->getPessoaJuridica()->getCnpj()) === 0) return 0;
                            return ((strcasecmp($a->getPessoaJuridica()->getCnpj(), $b->getPessoaJuridica()->getCnpj()) > 0) ? -1 : 1);
                        }
                    });
                    break;
                case '7':
                    usort($array, function (Cliente $a, Cliente $b) {
                        if (date($a->getCadastro()) === date($b->getCadastro())) return 0;
                        return ((date($a->getCadastro()) < date($b->getCadastro())) ? -1 : 1);
                    });
                    break;
                case '8':
                    usort($array, function (Cliente $a, Cliente $b) {
                        if (date($a->getCadastro()) === date($b->getCadastro())) return 0;
                        return ((date($a->getCadastro()) > date($b->getCadastro())) ? -1 : 1);
                    });
                    break;
                case '9':
                    usort($array, function (Cliente $a, Cliente $b) {
                        if ($a->getTipo() === $b->getTipo()) return 0;
                        return (($a->getTipo() < $b->getTipo()) ? -1 : 1);
                    });
                    break;
                case '10':
                    usort($array, function (Cliente $a, Cliente $b) {
                        if ($a->getTipo() === $b->getTipo()) return 0;
                        return (($a->getTipo() > $b->getTipo()));
                    });
                    break;
                case '11':
                    usort($array, function (Cliente $a, Cliente $b) {
                        if ($a->getTipo() == 1 && $b->getTipo() == 1) {
                            if (strcasecmp($a->getPessoaFisica()->getContato()->getEmail(), $b->getPessoaFisica()->getContato()->getEmail()) === 0) return 0;
                            return ((strcasecmp($a->getPessoaFisica()->getContato()->getEmail(), $b->getPessoaFisica()->getContato()->getEmail()) < 0) ? -1 : 1);
                        } elseif ($a->getTipo() == 1) {
                            if (strcasecmp($a->getPessoaFisica()->getContato()->getEmail(), $b->getPessoaJuridica()->getContato()->getEmail()) === 0) return 0;
                            return ((strcasecmp($a->getPessoaFisica()->getContato()->getEmail(), $b->getPessoaJuridica()->getContato()->getEmail()) < 0) ? -1 : 1);
                        } elseif ($b->getTipo() == 1) {
                            if (strcasecmp($a->getPessoaJuridica()->getContato()->getEmail(), $b->getPessoaFisica()->getContato()->getEmail()) === 0) return 0;
                            return ((strcasecmp($a->getPessoaJuridica()->getContato()->getEmail(), $b->getPessoaFisica()->getContato()->getEmail()) < 0) ? -1 : 1);
                        } else {
                            if (strcasecmp($a->getPessoaJuridica()->getContato()->getEmail(), $b->getPessoaJuridica()->getContato()->getEmail()) === 0) return 0;
                            return ((strcasecmp($a->getPessoaJuridica()->getContato()->getEmail(), $b->getPessoaJuridica()->getContato()->getEmail()) < 0) ? -1 : 1);
                        }
                    });
                    break;
                case '12':
                    usort($array, function (Cliente $a, Cliente $b) {
                        if ($a->getTipo() == 1 && $b->getTipo() == 1) {
                            if (strcasecmp($a->getPessoaFisica()->getContato()->getEmail(), $b->getPessoaFisica()->getContato()->getEmail()) === 0) return 0;
                            return ((strcasecmp($a->getPessoaFisica()->getContato()->getEmail(), $b->getPessoaFisica()->getContato()->getEmail()) > 0) ? -1 : 1);
                        } elseif ($a->getTipo() == 1) {
                            if (strcasecmp($a->getPessoaFisica()->getContato()->getEmail(), $b->getPessoaJuridica()->getContato()->getEmail()) === 0) return 0;
                            return ((strcasecmp($a->getPessoaFisica()->getContato()->getEmail(), $b->getPessoaJuridica()->getContato()->getEmail()) > 0) ? -1 : 1);
                        } elseif ($b->getTipo() == 1) {
                            if (strcasecmp($a->getPessoaJuridica()->getContato()->getEmail(), $b->getPessoaFisica()->getContato()->getEmail()) === 0) return 0;
                            return ((strcasecmp($a->getPessoaJuridica()->getContato()->getEmail(), $b->getPessoaFisica()->getContato()->getEmail()) > 0) ? -1 : 1);
                        } else {
                            if (strcasecmp($a->getPessoaJuridica()->getContato()->getEmail(), $b->getPessoaJuridica()->getContato()->getEmail()) === 0) return 0;
                            return ((strcasecmp($a->getPessoaJuridica()->getContato()->getEmail(), $b->getPessoaJuridica()->getContato()->getEmail()) > 0) ? -1 : 1);
                        }
                    });
                    break;
            }
        }

        $jarray = [];
        for ($i = 0; $i < count($array); $i++) {
            /** @var Cliente $cli */
            $cli = $array[$i];
            $jarray[] = $cli->jsonSerialize();
        }

        return json_encode($jarray);
    }

    public function enviar(int $id)
    {
        if ($id <= 0) return json_encode('Parâmetro inválido.');
        setcookie('CLIENTE', $id, time() + (3600), "/", "", 0, 1);

        return json_encode('');
    }

    public function excluir(int $id)
    {
        if (!Banco::getInstance()->open()) return json_encode('Erro ao abrir a conexão com o banco de dados.');
        
        $cliente = Cliente::getById($id);
        
        Banco::getInstance()->getConnection()->begin_transaction();

        $res_cli = Cliente::delete($cliente->getTipo(), $id);
        if ($res_cli == -10 || $res_cli == -1) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode('Ocorreu um problema ao excluir o cliente;');
        }
        if ($res_cli == -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode('Id inválido.');
        }

        if ($cliente->getTipo() == 1) {
            $res_pes = PessoaFisica::delete($cliente->getPessoaFisica()->getId());
            if ($res_pes == -10 || $res_pes == -1) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return json_encode('Ocorreu um problema ao excluir a pessoa;');
            }
            if ($res_pes == -5) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return json_encode('Id inválido.');
            }
        } else {
            $res_pes = PessoaJuridica::delete($cliente->getPessoaJuridica()->getId());
            if ($res_pes == -10 || $res_pes == -1) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return json_encode('Ocorreu um problema ao excluir a pessoa;');
            }
            if ($res_pes == -5) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return json_encode('Id inválido.');
            }
        }

        $res_ctt = Contato::delete($cliente->getTipo() == 1 ? $cliente->getPessoaFisica()->getContato()->getId() : $cliente->getPessoaJuridica()->getContato()->getId());
        if ($res_ctt == -10 || $res_ctt == -1) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode('Ocorreu um problema ao excluir o contato;');
        }
        if ($res_ctt == -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode('Id inválido.');
        }

        $res_end = Endereco::delete($cliente->getTipo() == 1 ? $cliente->getPessoaFisica()->getContato()->getEndereco()->getId() : $cliente->getPessoaJuridica()->getContato()->getEndereco()->getId());
        if ($res_end == -10 || $res_end == -1) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode('Ocorreu um problema ao excluir o endereço;');
        }
        if ($res_end == -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode('Id inválido.');
        }

        Banco::getInstance()->getConnection()->commit();
        Banco::getInstance()->getConnection()->close();

        return json_encode('');
    }
}