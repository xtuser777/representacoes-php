<?php namespace scr\control;


use scr\model\Cidade;
use scr\model\Contato;
use scr\model\DadosBancarios;
use scr\model\Endereco;
use scr\model\Motorista;
use scr\model\PessoaFisica;
use scr\util\Banco;

class MotoristaNovoControl
{
    public function verificarCpf(string $cpf)
    {
        if (!Banco::getInstance()->open()) return json_encode(true);
        $res = PessoaFisica::verifyCpf($cpf);
        Banco::getInstance()->getConnection()->close();

        return json_encode($res);
    }

    public function gravar(string $nome, string $rg, string $cpf, string $nasc, string $banco, string $agencia, string $conta, int $tipo, string $tel, string $cel, string $email, string $rua, string $num, string $bairro, string $comp, string $cep, int $cid)
    {
        if (!Banco::getInstance()->open()) return json_encode("Erro ao conectar-se ao banco de dados.");
        $cidade = Cidade::getById($cid);
        Banco::getInstance()->getConnection()->begin_transaction();
        $endereco = new Endereco(
            0, $rua, $num, $bairro, $comp, $cep, $cidade
        );
        $res_end = $endereco->insert();
        if ($res_end == -10 || $res_end == -1 || $res_end == 0) {
            Banco::getInstance()->getConnection()->close();
            return json_encode('Ocorreu um problema ao gravar o endereço.');
        }
        if ($res_end == -5) {
            Banco::getInstance()->getConnection()->close();
            return json_encode('Um ou mais campos inválidos no endereço.');
        }
        $contato = new Contato (
            0, $tel, $cel, $email,
            new Endereco (
                $res_end, $rua, $num, $bairro, $comp, $cep, $cidade
            )
        );
        $res_ctt = $contato->insert();
        if ($res_ctt == -10 || $res_ctt == -1 || $res_ctt == 0) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode('Ocorreu um problema ao gravar o contato.');
        }
        if ($res_ctt == -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode('Um ou mais campos inválidos no contato.');
        }
        $pessoa = new PessoaFisica (
            0, $nome, $rg, $cpf, $nasc,
            new Contato (
                $res_ctt, $tel, $cel, $email,
                new Endereco (
                    $res_end, $rua, $num, $bairro, $comp, $cep, $cidade
                )
            )
        );
        $res_pes = $pessoa->insert();
        if ($res_pes == -10 || $res_pes == -1 || $res_pes == 0) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode('Ocorreu um problema ao gravar a pessoa.');
        }
        if ($res_pes == -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode('Um ou mais campos inválidos na pessoa.');
        }
        $dados = new DadosBancarios(0, $banco, $agencia, $conta, $tipo);
        $res_dad = $dados->save();
        if ($res_dad == -10 || $res_dad == -1 || $res_dad == 0) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode('Ocorreu um problema ao gravar os dados bancários.');
        }
        if ($res_dad == -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode('Um ou mais campos inválidos.');
        }
        $motorista = new Motorista(
            0,date('Y-m-d'),
            new PessoaFisica (
                $res_pes, $nome, $rg, $cpf, $nasc,
                new Contato (
                    $res_ctt, $tel, $cel, $email,
                    new Endereco (
                        $res_end, $rua, $num, $bairro, $comp, $cep, $cidade
                    )
                )
            ),
            new DadosBancarios($res_dad, $banco, $agencia, $conta, $tipo)
        );
        $res_mot = $motorista->save();
        if ($res_mot == -10 || $res_mot == -1 || $res_mot == 0) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode('Ocorreu um problema ao gravar o motorista.');
        }
        if ($res_mot == -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode('Um ou mais campos inválidos.');
        }
        Banco::getInstance()->getConnection()->commit();
        Banco::getInstance()->getConnection()->close();

        return json_encode("");
    }
}