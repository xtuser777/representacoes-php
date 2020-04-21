<?php namespace scr\control;

use scr\model\Contato;
use scr\model\Endereco;
use scr\model\PessoaJuridica;
use scr\model\Representacao;
use scr\util\Banco;

class RepresentacaoControl
{
    public function obter()
    {
        $jarray = array();
        if (Banco::getInstance()->open())
        {
            $array = Representacao::getAll();
            Banco::getInstance()->getConnection()->close();

            for ($i = 0; $i < count($array); $i++)
            {
                /** @var Representacao $r */
                $r = $array[$i];
                $jarray[] = $r->jsonSerialize();
            }
        }

        return json_encode($jarray);
    }

    public function obterPorChave(string $chave)
    {
        $jarray = array();
        if (Banco::getInstance()->open())
        {
            $array = Representacao::getByKey($chave);
            Banco::getInstance()->getConnection()->close();

            for ($i = 0; $i < count($array); $i++)
            {
                /** @var Representacao $r */
                $r = $array[$i];
                $jarray[] = $r->jsonSerialize();
            }
        }

        return json_encode($jarray);
    }

    public function obterPorCadastro(string $cad)
    {
        $jarray = array();
        if (Banco::getInstance()->open())
        {
            $array = Representacao::getByCad($cad);
            Banco::getInstance()->getConnection()->close();

            for ($i = 0; $i < count($array); $i++)
            {
                /** @var Representacao $r */
                $r = $array[$i];
                $jarray[] = $r->jsonSerialize();
            }
        }

        return json_encode($jarray);
    }

    public function obterPorChaveCad(string $chave, string $cad)
    {
        $jarray = array();
        if (Banco::getInstance()->open())
        {
            $array = Representacao::getByKeyCad($chave, $cad);
            Banco::getInstance()->getConnection()->close();

            for ($i = 0; $i < count($array); $i++)
            {
                /** @var Representacao $r */
                $r = $array[$i];
                $jarray[] = $r->jsonSerialize();
            }
        }

        return json_encode($jarray);
    }

    public function ordenar(string $col)
    {
        $jarray = array();
        if (Banco::getInstance()->open())
        {
            $array = Representacao::getAll();
            Banco::getInstance()->getConnection()->close();

            if (count($array) >= 2) {
                switch ($col) {
                    case '1':
                        usort($array, function (Representacao $a, Representacao $b) {
                            if ($a->getId() == $b->getId()) return 0;
                            return (($a->getId() < $b->getId()) ? -1 : 1);
                        });
                        break;
                    case '2':
                        usort($array, function (Representacao $a, Representacao $b) {
                            if ($a->getId() == $b->getId()) return 0;
                            return (($a->getId() > $b->getId()) ? -1 : 1);
                        });
                        break;
                    case '3':
                        usort($array, function (Representacao $a, Representacao $b) {
                            if (strcasecmp($a->getPessoa()->getNomeFantasia(), $b->getPessoa()->getNomeFantasia()) === 0) return 0;
                            return ((strcasecmp($a->getPessoa()->getNomeFantasia(), $b->getPessoa()->getNomeFantasia()) < 0) ? -1 : 1);
                        });
                        break;
                    case '4':
                        usort($array, function (Representacao $a, Representacao $b) {
                            if (strcasecmp($a->getPessoa()->getNomeFantasia(), $b->getPessoa()->getNomeFantasia()) === 0) return 0;
                            return ((strcasecmp($a->getPessoa()->getNomeFantasia(), $b->getPessoa()->getNomeFantasia()) > 0) ? -1 : 1);
                        });
                        break;
                    case '5':
                        usort($array, function (Representacao $a, Representacao $b) {
                            if (strcasecmp($a->getPessoa()->getCnpj(), $b->getPessoa()->getCnpj()) === 0) return 0;
                            return ((strcasecmp($a->getPessoa()->getCnpj(), $b->getPessoa()->getCnpj()) < 0) ? -1 : 1);
                        });
                        break;
                    case '6':
                        usort($array, function (Representacao $a, Representacao $b) {
                            if (strcasecmp($a->getPessoa()->getCnpj(), $b->getPessoa()->getCnpj()) === 0) return 0;
                            return ((strcasecmp($a->getPessoa()->getCnpj(), $b->getPessoa()->getCnpj()) > 0) ? -1 : 1);
                        });
                        break;
                    case '7':
                        usort($array, function (Representacao $a, Representacao $b) {
                            if (date($a->getCadastro()) === date($b->getCadastro())) return 0;
                            return ((date($a->getCadastro()) < date($b->getCadastro())) ? -1 : 1);
                        });
                        break;
                    case '8':
                        usort($array, function (Representacao $a, Representacao $b) {
                            if (date($a->getCadastro()) === date($b->getCadastro())) return 0;
                            return ((date($a->getCadastro()) > date($b->getCadastro())) ? -1 : 1);
                        });
                        break;
                    case '9':
                        usort($array, function (Representacao $a, Representacao $b) {
                            if (strcasecmp($a->getUnidade(), $b->getUnidade()) === 0) return 0;
                            return ((strcasecmp($a->getUnidade(), $b->getUnidade()) < 0) ? -1 : 1);
                        });
                        break;
                    case '10':
                        usort($array, function (Representacao $a, Representacao $b) {
                            if (strcasecmp($a->getUnidade(), $b->getUnidade()) === 0) return 0;
                            return ((strcasecmp($a->getUnidade(), $b->getUnidade()) > 0) ? -1 : 1);
                        });
                        break;
                    case '11':
                        usort($array, function (Representacao $a, Representacao $b) {
                            if (strcasecmp($a->getPessoa()->getContato()->getEmail(), $b->getPessoa()->getContato()->getEmail()) === 0) return 0;
                            return ((strcasecmp($a->getPessoa()->getContato()->getEmail(), $b->getPessoa()->getContato()->getEmail()) < 0) ? -1 : 1);
                        });
                        break;
                    case '12':
                        usort($array, function (Representacao $a, Representacao $b) {
                            if (strcasecmp($a->getPessoa()->getContato()->getEmail(), $b->getPessoa()->getContato()->getEmail()) === 0) return 0;
                            return ((strcasecmp($a->getPessoa()->getContato()->getEmail(), $b->getPessoa()->getContato()->getEmail()) > 0) ? -1 : 1);
                        });
                        break;
                }
            }

            for ($i = 0; $i < count($array); $i++)
            {
                /** @var Representacao $r */
                $r = $array[$i];
                $jarray[] = $r->jsonSerialize();
            }
        }

        return json_encode($jarray);
    }

    public function enviar(int $id)
    {
        if ($id <= 0) return json_encode('Parâmetro inválido.');
        $_SESSION['REPRESENTACAO'] = $id;

        return json_encode('');
    }

    public function excluir(int $id)
    {
        if (!Banco::getInstance()->open()) return json_encode('Problema ao conectar-se ao banoc de dados.');

        $representacao = Representacao::getById($id);

        if (!$representacao) return json_encode('Registro não existente.');

        $rep = Representacao::delete($id);
        if ($rep == -10 || $rep == -1)
        {
            Banco::getInstance()->getConnection()->close();
            return json_encode('Erro ao executar o código SQL da exclusão da Representação.');
        }
        if ($rep == -5)
        {
            Banco::getInstance()->getConnection()->close();
            return json_encode('Identificação inválida.');
        }

        $pes = PessoaJuridica::delete(Banco::getInstance()->getConnection(), $representacao->getPessoa()->getId());
        if ($pes == -10 || $pes == -1)
        {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode('Erro ao executar o código SQL da exclusão da Pessoa.');
        }
        if ($pes == -5)
        {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode('Identificação inválida.');
        }

        $ctt = Contato::delete(Banco::getInstance()->getConnection(), $representacao->getPessoa()->getContato()->getId());
        if ($ctt == -10 || $ctt == -1)
        {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode('Erro ao executar o código SQL da exclusão do Contato.');
        }
        if ($ctt == -5)
        {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode('Identificação inválida.');
        }

        $end = Endereco::delete(Banco::getInstance()->getConnection(), $representacao->getPessoa()->getContato()->getEndereco()->getId());
        if ($end == -10 || $end == -1)
        {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode('Erro ao executar o código SQL da exclusão do Endereço.');
        }
        if ($end == -5)
        {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode('Identificação inválida.');
        }

        Banco::getInstance()->getConnection()->commit();
        Banco::getInstance()->getConnection()->close();

        return json_encode('');
    }
}