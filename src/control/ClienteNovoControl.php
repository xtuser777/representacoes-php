<?php namespace scr\control;

use scr\util\Banco;
use scr\model\Cidade;
use scr\model\Endereco;
use scr\model\Contato;
use scr\model\PessoaFisica;
use scr\model\PessoaJuridica;
use scr\model\Cliente;

class ClienteNovoControl
{
    public function verificar_cpf(string $cpf)
    {
        $db = Banco::getInstance();
        $db-open();
        $res = PessoaFisica::verifyCpf($db->getConnection(), $cpf);
        $db->getConnection()->close();
        
        return json_encode($res);
    }

    public function verificar_cnpj(string $cnpj)
    {
        $db = Banco::getInstance();
        $db->open();
        $res = PessoaJuridica::verifyCnpj($db->getConnection(), $cnpj);
        $db->getConnection()->close();
        
        return json_encode($res);
    }

    public function gravar(int $tipo, string $nome, string $rg, string $cpf, string $nasc, string $rs, string $nf, string $cnpj, string $tel, string $cel, string $email, string $rua, string $num, string $bairro, string $comp, string $cep, int $cid)
    {
        $db = Banco::getInstance();
        $db->open();
        if ($db->getConnection() == null) return json_encode ('Erro ao conectar-se ao banco de dados.');
        
        $cidade = Cidade::getById($db->getConnection(), $cid);
        
        $db->getConnection()->begin_transaction();

        $endereco = new Endereco(
            0, $rua, $num, $bairro, $comp, $cep, $cidade
        );
        $res_end = $endereco->insert($db->getConnection());
        if ($res_end == -10) {
            $db->getConnection()->close();
            return json_encode('Ocorreu um problema ao gravar o endereço.');
        }
        if ($res_end == -5) {
            $db->getConnection()->close();
            return json_encode('Um ou mais campos inválidos no endereço.');
        }

        $contato = new Contato (
            0, $tel, $cel, $email,
            new Endereco (
                $res_end, $rua, $num, $bairro, $comp, $cep, $cidade
            )
        );
        $res_ctt = $contato->insert($db->getConnection());
        if ($res_ctt == -10) {
            $db->getConnection()->rollback();
            $db->getConnection()->close();
            return json_encode('Ocorreu um problema ao gravar o contato.');
        }
        if ($res_ctt == -5) {
            $db->getConnection()->rollback();
            $db->getConnection()->close();
            return json_encode('Um ou mais campos inválidos no contato.');
        }

        $res_pes = 0;
        if ($tipo == 1) {
            $pessoa = new PessoaFisica (
                0, $nome, $rg, $cpf, $nasc,
                new Contato (
                    $res_ctt, $tel, $cel, $email,
                    new Endereco (
                        $res_end, $rua, $num, $bairro, $comp, $cep, $cidade
                    )
                )
            );
            $res_pes = $pessoa->insert($db->getConnection());
            if ($res_pes == -10) {
                $db->getConnection()->rollback();
                $db->getConnection()->close();
                return json_encode('Ocorreu um problema ao gravar a pessoa.');
            }
            if ($res_pes == -5) {
                $db->getConnection()->rollback();
                $db->getConnection()->close();
                return json_encode('Um ou mais campos inválidos na pessoa.');
            }
        } else {
            $pessoa = new PessoaJuridica (
                0, $rs, $nf, $cnpj,
                new Contato (
                    $res_ctt, $tel, $cel, $email,
                    new Endereco (
                        $res_end, $rua, $num, $bairro, $comp, $cep, $cidade
                    )
                )
            );
            $res_pes = $pessoa->insert($db->getConnection());
            if ($res_pes == -10) {
                $db->getConnection()->rollback();
                $db->getConnection()->close();
                return json_encode('Ocorreu um problema ao gravar a pessoa.');
            }
            if ($res_pes == -5) {
                $db->getConnection()->rollback();
                $db->getConnection()->close();
                return json_encode('Um ou mais campos inválidos na pessoa.');
            }
        }

        $cliente = new Cliente (
            0, date('Y-m-d'), $tipo,
            $tipo == 2 ? null : new PessoaFisica (
                $res_pes, $nome, $rg, $cpf, $nasc,
                new Contato (
                    $res_ctt, $tel, $cel, $email,
                    new Endereco (
                        $res_end, $rua, $num, $bairro, $comp, $cep, $cidade
                    )
                )
            ),
            $tipo == 1 ? null : new PessoaJuridica (
                $res_pes, $rs, $nf, $cnpj,
                new Contato (
                    $res_ctt, $tel, $cel, $email,
                    new Endereco (
                        $res_end, $rua, $num, $bairro, $comp, $cep, $cidade
                    )
                )
            )
        );
        $res = $cliente->insert($db->getConnection());
        if ($res == -10) {
            $db->getConnection()->rollback();
            $db->getConnection()->close();
            return json_encode('Ocorreu um problema ao gravar o cliente.');
        }
        if ($res == -5) {
            $db->getConnection()->rollback();
            $db->getConnection()->close();
            return json_encode('Um ou mais campos inválidos no cliente.');
        }

        $db->getConnection()->commit();
        $db->getConnection()->close();

        return json_encode('');
    }
}