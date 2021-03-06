<?php


namespace scr\control;


use scr\model\Cidade;
use scr\model\Contato;
use scr\model\Endereco;
use scr\model\Motorista;
use scr\model\PessoaFisica;
use scr\model\PessoaJuridica;
use scr\model\Proprietario;
use scr\util\Banco;

class ProprietarioNovoControl
{
    public function obterMotoristas()
    {
        if (!Banco::getInstance()->open()) return json_encode([]);
        $motoristas = Motorista::findAll();
        Banco::getInstance()->getConnection()->close();
        $serial = [];
        /** @var Motorista $motorista */
        foreach ($motoristas as $motorista) {
            $serial[] = $motorista->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function validarCpf(string $cpf)
    {
        if (!Banco::getInstance()->open()) return json_encode(false);
        $valid = PessoaFisica::validarCPF($cpf);
        Banco::getInstance()->getConnection()->close();

        return json_encode($valid);
    }

    public function validarCNPJ(string $cnpj)
    {
        if (!Banco::getInstance()->open()) return json_encode(false);
        $valid = PessoaJuridica::validarCNPJ($cnpj);
        Banco::getInstance()->getConnection()->close();

        return json_encode($valid);
    }

    public function validarEmail(string $email)
    {
        if (!Banco::getInstance()->open()) return json_encode(false);
        $valid = Contato::validarEmail($email);
        Banco::getInstance()->getConnection()->close();

        return json_encode($valid);
    }

    public function gravar(int $mot, int $tipo, string $nome, string $rg, string $cpf, string $nasc, string $rs, string $nf, string $cnpj, string $rua, string $num, string $bairro, string $comp, string $cep, int $cid, string $tel, string $cel, string $email)
    {
        if (!Banco::getInstance()->open())
            return json_encode("Erro ao conectar-se ao banco de dados.");
        $motorista = Motorista::findById($mot);
        $cidade = (new Cidade())->getById($cid);
        if (!$cidade)
            return json_encode("Cidade não registrada.");
        $endereco = new Endereco(0, $rua, $num, $bairro, $comp, $cep, $cidade);
        $end = $endereco->insert();
        if ($end === -10 || $end === -1) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Ocorreu um problema ao gravar o endereço.");
        }
        if ($end === -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Parâmetros inválidos.");
        }
        $endereco->setId($end);
        $contato = new Contato(0, $tel, $cel, $email, $endereco);
        $ctt = $contato->insert();
        if ($ctt === -10 || $ctt === -1) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Ocorreu um problema ao gravar o contato.");
        }
        if ($ctt === -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Parâmetros inválidos.");
        }
        $contato->setId($ctt);
        $pes = 0;
        if ($tipo === 1) {
            $pessoa = new PessoaFisica(0, $nome, $rg, $cpf, $nasc, $contato);
            $pes = $pessoa->insert();
            if ($pes === -10 || $pes === -1) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return json_encode("Ocorreu um problema ao gravar a pessoa.");
            }
            if ($pes === -5) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return json_encode("Parâmetros inválidos.");
            }
            $pessoa->setId($pes);
            $prop = new Proprietario(0, date('Y-m-d'), $tipo, $motorista, $pessoa, null);
            $prp = $prop->save();
            if ($prp === -10 || $prp === -1) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return json_encode("Ocorreu um problema ao gravar o proprietário.");
            }
            if ($prp === -5) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return json_encode("Parâmetros inválidos.");
            }
        } else {
            $pessoa = new PessoaJuridica(0, $rs, $nf, $cnpj, $contato);
            $pes = $pessoa->insert();
            if ($pes === -10 || $pes === -1) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return json_encode("Ocorreu um problema ao gravar a pessoa.");
            }
            if ($pes === -5) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return json_encode("Parâmetros inválidos.");
            }
            $pessoa->setId($pes);
            $prop = new Proprietario(0, date('Y-m-d'), $tipo, $motorista, null, $pessoa);
            $prp = $prop->save();
            if ($prp === -10 || $prp === -1) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return json_encode("Ocorreu um problema ao gravar o proprietário.");
            }
            if ($prp === -5) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return json_encode("Parâmetros inválidos.");
            }
        }
        Banco::getInstance()->getConnection()->commit();
        Banco::getInstance()->getConnection()->close();

        return json_encode("");
    }
}