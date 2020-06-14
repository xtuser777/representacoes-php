<?php


namespace scr\control;


use scr\model\Contato;
use scr\model\Endereco;
use scr\model\PessoaFisica;
use scr\model\PessoaJuridica;
use scr\model\Proprietario;
use scr\util\Banco;

class ProprietarioControl
{
    public function obter()
    {
        if (!Banco::getInstance()->open()) return json_encode([]);
        $props = (new Proprietario())->findAll();
        Banco::getInstance()->getConnection()->close();
        $serial = [];
        /** @var Proprietario $prop */
        foreach ($props as $prop) {
            $serial[] = $prop->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltro($filtro)
    {
        if (!Banco::getInstance()->open()) return json_encode([]);
        $props = (new Proprietario())->findByFilter($filtro);
        Banco::getInstance()->getConnection()->close();
        $serial = [];
        /** @var Proprietario $prop */
        foreach ($props as $prop) {
            $serial[] = $prop->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorCadastro($cad)
    {
        if (!Banco::getInstance()->open()) return json_encode([]);
        $props = (new Proprietario())->findByCad($cad);
        Banco::getInstance()->getConnection()->close();
        $serial = [];
        /** @var Proprietario $prop */
        foreach ($props as $prop) {
            $serial[] = $prop->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltroCadastro($filtro, $cad)
    {
        if (!Banco::getInstance()->open()) return json_encode([]);
        $props = (new Proprietario())->findByFilterCad($filtro, $cad);
        Banco::getInstance()->getConnection()->close();
        $serial = [];
        /** @var Proprietario $prop */
        foreach ($props as $prop) {
            $serial[] = $prop->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function ordenar(string $col)
    {
        if (!Banco::getInstance()->open()) return json_encode([]);
        $props = (new Proprietario())->findAll();
        Banco::getInstance()->getConnection()->close();

        if (count($props) > 0) {
            switch($col) {
                case "1":
                    usort($props, function (Proprietario $a, Proprietario $b) {
                        if ($a->getId() === $b->getId()) return 0;
                        return (($a->getId() < $b->getId()) ? -1 : 1);
                    });
                    break;
                case "2":
                    usort($props, function (Proprietario $a, Proprietario $b) {
                        if ($a->getId() === $b->getId()) return 0;
                        return (($a->getId() > $b->getId()) ? -1 : 1);
                    });
                    break;
                case "3":
                    usort($props, function (Proprietario $a, Proprietario $b) {
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
                case "4":
                    usort($props, function (Proprietario $a, Proprietario $b) {
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
                case "5":
                    usort($props, function (Proprietario $a, Proprietario $b) {
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
                case "6":
                    usort($props, function (Proprietario $a, Proprietario $b) {
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
                case "7":
                    usort($props, function (Proprietario $a, Proprietario $b) {
                        if (date($a->getCadastro()) === date($b->getCadastro())) return 0;
                        return ((date($a->getCadastro()) < date($b->getCadastro())) ? -1 : 1);
                    });
                    break;
                case "8":
                    usort($props, function (Proprietario $a, Proprietario $b) {
                        if (date($a->getCadastro()) === date($b->getCadastro())) return 0;
                        return ((date($a->getCadastro()) > date($b->getCadastro())) ? -1 : 1);
                    });
                    break;
                case "9":
                    usort($props, function (Proprietario $a, Proprietario $b) {
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
                case "10":
                    usort($props, function (Proprietario $a, Proprietario $b) {
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

        $serial = [];
        /** @var Proprietario $prop */
        foreach ($props as $prop) {
            $serial[] = $prop->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function enviar(int $id)
    {
        if ($id <= 0) return json_encode("Parâmetro inválido.");
        $_SESSION["PROP"] = $id;

        return json_encode("");
    }

    public function excluir(int $id)
    {
        if (!Banco::getInstance()->open()) return json_encode("Erro ao conectar-se ao banco de dados.");
        $prop = (new Proprietario())->findById($id);
        if (!$prop) return json_encode("Registro não encontrado.");
        if ($prop->getTipo() === 1) {
            $end = Endereco::delete($prop->getPessoaFisica()->getContato()->getEndereco()->getId());
            if ($end === -10 || $end === -1) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return json_encode("Ocorreu um erro ao excluir o endereço.");
            }
            if ($end === -5) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return json_encode("Registro inválido.");
            }
            $ctt = Contato::delete($prop->getPessoaFisica()->getContato()->getId());
            if ($ctt === -10 || $ctt === -1) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return json_encode("Ocorreu um erro ao excluir o contato.");
            }
            if ($ctt === -5) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return json_encode("Registro inválido.");
            }
            $pes = PessoaFisica::delete($prop->getPessoaFisica()->getId());
            if ($pes === -10 || $pes === -1) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return json_encode("Ocorreu um erro ao excluir a pessoa.");
            }
            if ($pes === -5) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return json_encode("Registro inválido.");
            }
        } else {
            $end = Endereco::delete($prop->getPessoaJuridica()->getContato()->getEndereco()->getId());
            if ($end === -10 || $end === -1) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return json_encode("Ocorreu um erro ao excluir o endereço.");
            }
            if ($end === -5) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return json_encode("Registro inválido.");
            }
            $ctt = Contato::delete($prop->getPessoaJuridica()->getContato()->getId());
            if ($ctt === -10 || $ctt === -1) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return json_encode("Ocorreu um erro ao excluir o contato.");
            }
            if ($ctt === -5) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return json_encode("Registro inválido.");
            }
            $pes = PessoaJuridica::delete($prop->getPessoaJuridica()->getId());
            if ($pes === -10 || $pes === -1) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return json_encode("Ocorreu um erro ao excluir a pessoa.");
            }
            if ($pes === -5) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return json_encode("Registro inválido.");
            }
        }
        $prp = $prop->delete();
        if ($prp === -10 || $prp === -1) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("");
        }
        if ($prp === -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("");
        }
        Banco::getInstance()->getConnection()->rollback();
        Banco::getInstance()->getConnection()->close();

        return json_encode("");
    }
}