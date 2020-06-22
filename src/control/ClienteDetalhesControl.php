<?php


namespace scr\control;


use scr\util\Banco;
use scr\model\Cidade;
use scr\model\Endereco;
use scr\model\Contato;
use scr\model\PessoaFisica;
use scr\model\PessoaJuridica;
use scr\model\Cliente;

class ClienteDetalhesControl
{
    public function obter_detalhes()
    {
        if (!Banco::getInstance()->open()) return json_encode(null);
        $id = $_SESSION['CLIENTE'];
        $cliente = Cliente::getById($id)->jsonSerialize();
        Banco::getInstance()->getConnection()->close();
        
        return json_encode($cliente);
    }

    public function verificar_cpf(string $cpf)
    {
        if (!Banco::getInstance()->open()) return json_encode(true);
        $res = PessoaFisica::verifyCpf($cpf);
        Banco::getInstance()->getConnection()->close();
        
        return json_encode($res);
    }

    public function verificar_cnpj(string $cnpj)
    {
        if (!Banco::getInstance()->open()) return json_encode(true);
        $res = PessoaJuridica::verifyCnpj($cnpj);
        Banco::getInstance()->getConnection()->close();
        
        return json_encode($res);
    }

    public function alterar(int $end, int $ctt, int $pes, int $cli, string $cad, int $tipo, string $nome, string $rg, string $cpf, string $nasc, string $rs, string $nf, string $cnpj, string $tel, string $cel, string $email, string $rua, string $num, string $bairro, string $comp, string $cep, int $cid)
    {
        if (!Banco::getInstance()->open()) return json_encode('Erro ao conectar ao banco de dados.');
        
        $cidade = (new Cidade())->getById($cid);
        
        Banco::getInstance()->getConnection()->begin_transaction();

        $endereco = new Endereco (
            $end, $rua, $num, $bairro, $comp, $cep, $cidade
        );
        $res_end = $endereco->update();
        if ($res_end == -10 || $res_end == -1) {
            Banco::getInstance()->getConnection()->close();
            return json_encode('Ocorreu um problema ao alterar o endereço.');
        }
        if ($res_end == -5) {
            Banco::getInstance()->getConnection()->close();
            return json_encode('Um ou mais campos inválidos no endereço.');
        }

        $contato = new Contato(
            $ctt, $tel, $cel, $email,
            new Endereco(
                $end, $rua, $num, $bairro, $comp, $cep, $cidade
            )
        );
        $res_ctt = $contato->update();
        if ($res_ctt == -10 || $res_ctt == -1) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode('Ocorreu um problema ao alterar o contato.');
        }
        if ($res_ctt == -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode('Um ou mais campos inválidos no contato.');
        }

        $res_pes = 0;
        if ($tipo == 1) {
            $pessoa = new PessoaFisica(
                $pes, $nome, $rg, $cpf, $nasc,
                new Contato(
                    $ctt, $tel, $cel, $email,
                    new Endereco(
                        $end, $rua, $num, $bairro, $comp, $cep, $cidade
                    )
                )
            );
            $res_pes = $pessoa->update();
            if ($res_pes == -10 || $res_pes == -1) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return json_encode('Ocorreu um problema ao alterar a pessoa.');
            }
            if ($res_pes == -5) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return json_encode('Um ou mais campos inválidos na pessoa.');
            }
        } else {
            $pessoa = new PessoaJuridica(
                $pes, $rs, $nf, $cnpj,
                new Contato(
                    $ctt, $tel, $cel, $email,
                    new Endereco(
                        $end, $rua, $num, $bairro, $comp, $cep, $cidade
                    )
                )
            );
            $res_pes = $pessoa->update();
            if ($res_pes == -10 || $res_pes == -1) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return json_encode('Ocorreu um problema ao alterar a pessoa.');
            }
            if ($res_pes == -5) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return json_encode('Um ou mais campos inválidos na pessoa.');
            }
        }

        $cliente = new Cliente
        (
            $cli, $cad, $tipo,
            $tipo == 2 ? null : new PessoaFisica(
                $pes, $nome, $rg, $cpf, $nasc,
                new Contato(
                    $ctt, $tel, $cel, $email,
                    new Endereco(
                        $end, $rua, $num, $bairro, $comp, $cep, $cidade
                    )
                )
            ),
            $tipo == 1 ? null : new PessoaJuridica(
                $pes, $rs, $nf, $cnpj,
                new Contato(
                    $ctt, $tel, $cel, $email,
                    new Endereco(
                        $end, $rua, $num, $bairro, $comp, $cep, $cidade
                    )
                )
            )
        );
        $res = $cliente->update();
        if ($res == -10 || $res == -1) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode('Ocorreu um problema ao alterar o cliente.');
        }
        if ($res == -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode('Um ou mais campos inválidos no cliente.');
        }

        Banco::getInstance()->getConnection()->commit();
        Banco::getInstance()->getConnection()->close();

        return json_encode('');
    }
}