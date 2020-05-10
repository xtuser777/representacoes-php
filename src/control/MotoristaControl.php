<?php namespace scr\control;

use scr\model\Contato;
use scr\model\Endereco;
use scr\model\Motorista;
use scr\model\PessoaFisica;
use scr\util\Banco;

class MotoristaControl
{
    public function obter()
    {
        if (!Banco::getInstance()->open()) return json_encode([]);
        $motoristas = Motorista::findAll();
        Banco::getInstance()->getConnection()->close();
        $jarray = [];
        /** @var $motorista Motorista */
        foreach ($motoristas as $motorista) {
            $jarray[] = $motorista->jsonSerialize();
        }

        return json_encode($jarray);
    }

    public function obterPorChave(string $chave)
    {
        if (!Banco::getInstance()->open()) return json_encode([]);
        $motoristas = Motorista::findByKey($chave);
        Banco::getInstance()->getConnection()->close();
        $jarray = [];
        /** @var $motorista Motorista */
        foreach ($motoristas as $motorista) {
            $jarray[] = $motorista->jsonSerialize();
        }

        return json_encode($jarray);
    }

    public function obterPorCadastro(string $cad)
    {
        if (!Banco::getInstance()->open()) return json_encode([]);
        $motoristas = Motorista::findByCad($cad);
        Banco::getInstance()->getConnection()->close();
        $jarray = [];
        /** @var $motorista Motorista */
        foreach ($motoristas as $motorista) {
            $jarray[] = $motorista->jsonSerialize();
        }

        return json_encode($jarray);
    }

    public function obterPorChaveCadastro(string $chave, string $cadastro)
    {
        if (!Banco::getInstance()->open()) return json_encode([]);
        $motoristas = Motorista::findByKeyCad($chave, $cadastro);
        Banco::getInstance()->getConnection()->close();
        $jarray = [];
        /** @var $motorista Motorista */
        foreach ($motoristas as $motorista) {
            $jarray[] = $motorista->jsonSerialize();
        }

        return json_encode($jarray);
    }

    public function ordenar(string $col)
    {
        if (!Banco::getInstance()->open()) return json_encode([]);
        $motoristas = Motorista::findAll();
        Banco::getInstance()->getConnection()->close();

        if (count($motoristas) > 0) {
            switch ($col) {
                case "1":
                    usort($motoristas, function (Motorista $a, Motorista $b) {
                        if ($a->getId() === $b->getId()) return 0;
                        return (($a->getId() < $b->getId()) ? -1 : 1);
                    });
                    break;
                case "2":
                    usort($motoristas, function (Motorista $a, Motorista $b) {
                        if ($a->getId() === $b->getId()) return 0;
                        return (($a->getId() > $b->getId()) ? -1 : 1);
                    });
                    break;
                case "3":
                    usort($motoristas, function (Motorista $a, Motorista $b) {
                        if (strcasecmp($a->getPessoa()->getNome(), $b->getPessoa()->getNome()) === 0) return 0;
                        return ((strcasecmp($a->getPessoa()->getNome(), $b->getPessoa()->getNome()) < 0) ? -1 : 1);
                    });
                    break;
                case "4":
                    usort($motoristas, function (Motorista $a, Motorista $b) {
                        if (strcasecmp($a->getPessoa()->getNome(), $b->getPessoa()->getNome()) === 0) return 0;
                        return ((strcasecmp($a->getPessoa()->getNome(), $b->getPessoa()->getNome()) > 0) ? -1 : 1);
                    });
                    break;
                case "5":
                    usort($motoristas, function (Motorista $a, Motorista $b) {
                        if (strcasecmp($a->getPessoa()->getCpf(), $b->getPessoa()->getCpf()) === 0) return 0;
                        return ((strcasecmp($a->getPessoa()->getCpf(), $b->getPessoa()->getCpf()) < 0) ? -1 : 1);
                    });
                    break;
                case "6":
                    usort($motoristas, function (Motorista $a, Motorista $b) {
                        if (strcasecmp($a->getPessoa()->getCpf(), $b->getPessoa()->getCpf()) === 0) return 0;
                        return ((strcasecmp($a->getPessoa()->getCpf(), $b->getPessoa()->getCpf()) > 0) ? -1 : 1);
                    });
                    break;
                case "7":
                    usort($motoristas, function (Motorista $a, Motorista $b) {
                        if (strcasecmp($a->getCadastro(), $b->getCadastro()) === 0) return 0;
                        return ((strcasecmp($a->getCadastro(), $b->getCadastro()) < 0) ? -1 : 1);
                    });
                    break;
                case "8":
                    usort($motoristas, function (Motorista $a, Motorista $b) {
                        if (strcasecmp($a->getCadastro(), $b->getCadastro()) === 0) return 0;
                        return ((strcasecmp($a->getCadastro(), $b->getCadastro()) > 0) ? -1 : 1);
                    });
                    break;
                case "9":
                    usort($motoristas, function (Motorista $a, Motorista $b) {
                        if (strcasecmp($a->getPessoa()->getContato()->getEmail(), $b->getPessoa()->getContato()->getEmail()) === 0) return 0;
                        return ((strcasecmp($a->getPessoa()->getContato()->getEmail(), $b->getPessoa()->getContato()->getEmail()) < 0) ? -1 : 1);
                    });
                    break;
                case "10":
                    usort($motoristas, function (Motorista $a, Motorista $b) {
                        if (strcasecmp($a->getPessoa()->getContato()->getEmail(), $b->getPessoa()->getContato()->getEmail()) === 0) return 0;
                        return ((strcasecmp($a->getPessoa()->getContato()->getEmail(), $b->getPessoa()->getContato()->getEmail()) > 0) ? -1 : 1);
                    });
                    break;
            }
        }

        $jarray = [];
        /** @var $motorista Motorista */
        foreach ($motoristas as $motorista) {
            $jarray[] = $motorista->jsonSerialize();
        }

        return json_encode($jarray);
    }

    public function enviar(int $id)
    {
        if ($id <= 0) { return json_encode('Parâmetro inválido.'); }
        $_SESSION['MOTORISTA'] = $id;

        return json_encode('');
    }

    public function excluir(int $id)
    {
        if (!Banco::getInstance()->open()) return json_encode("Erro ao conectar-se ao banco de dados.");
        $motorista = Motorista::findById($id);
        if (!$motorista) return json_encode("Registro não encontrado.");
        Banco::getInstance()->getConnection()->begin_transaction();
        $rm = $motorista->delete();
        if ($rm == -10 || $rm == -1) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Ocorreu um problema ao excluir o motorista.");
        }
        if ($rm == -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Parâmetros inválidos.");
        }
        $rdb = $motorista->getDadosBancarios()->delete();
        if ($rm == -10 || $rm == -1) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Ocorreu um problema ao excluir os dados bancários.");
        }
        if ($rm == -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Parâmetros inválidos.");
        }
        $rp = PessoaFisica::delete($motorista->getPessoa()->getId());
        if ($rm == -10 || $rm == -1) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Ocorreu um problema ao excluir a pessoa.");
        }
        if ($rm == -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Parâmetros inválidos.");
        }
        $rc = Contato::delete($motorista->getPessoa()->getContato()->getId());
        if ($rm == -10 || $rm == -1) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Ocorreu um problema ao excluir o contato.");
        }
        if ($rm == -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Parâmetros inválidos.");
        }
        $re = Endereco::delete($motorista->getPessoa()->getContato()->getEndereco()->getId());
        if ($rm == -10 || $rm == -1) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Ocorreu um problema ao excluir o contato.");
        }
        if ($rm == -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Parâmetros inválidos.");
        }
        Banco::getInstance()->getConnection()->commit();
        Banco::getInstance()->getConnection()->close();

        return json_encode("");
    }
}