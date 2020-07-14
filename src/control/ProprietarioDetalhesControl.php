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

class ProprietarioDetalhesControl
{
    public function obter()
    {
        if (!Banco::getInstance()->open())
            return json_encode(null);
        $prop = (new Proprietario())->findById($_SESSION["PROP"]);
        Banco::getInstance()->getConnection()->close();

        return json_encode(($prop !== null) ? $prop->jsonSerialize() : null);
    }

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

    public function alterar(int $mot, int $tipo, int $prp, int $pes, int $ctt, int $end, string $nome, string $rg, string $cpf, string $nasc, string $rs, string $nf, string $cnpj, string $rua, string $num, string $bairro, string $comp, string $cep, int $cid, string $tel, string $cel, string $email)
    {
        if (!Banco::getInstance()->open())
            return json_encode("Erro ao conectar-se ao banco de dados.");
        $motorista = Motorista::findById($mot);
        $cidade = (new Cidade())->getById($cid);
        if (!$cidade)
            return json_encode("Cidade não registrada.");
        $endereco = new Endereco($end, $rua, $num, $bairro, $comp, $cep, $cidade);
        $rend = $endereco->update();
        if ($rend === -10 || $rend === -1) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Ocorreu um problema ao alterar o endereço.");
        }
        if ($rend === -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Parâmetros inválidos.");
        }
        $contato = new Contato($ctt, $tel, $cel, $email, $endereco);
        $rctt = $contato->update();
        if ($rctt === -10 || $rctt === -1) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Ocorreu um problema ao alterar o contato.");
        }
        if ($rctt === -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Parâmetros inválidos.");
        }
        if ($tipo === 1) {
            $pessoa = new PessoaFisica($pes, $nome, $rg, $cpf, $nasc, $contato);
            $rpes = $pessoa->update();
            if ($rpes === -10 || $rpes === -1) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return json_encode("Ocorreu um problema ao alterar a pessoa.");
            }
            if ($rpes === -5) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return json_encode("Parâmetros inválidos.");
            }
            $prop = new Proprietario($prp, date('Y-m-d'), $tipo, $motorista, $pessoa, null);
            $rprp = $prop->update();
            if ($rprp === -10 || $rprp === -1) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return json_encode("Ocorreu um problema ao alterar o proprietário.");
            }
            if ($rprp === -5) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return json_encode("Parâmetros inválidos.");
            }
        } else {
            $pessoa = new PessoaJuridica($pes, $rs, $nf, $cnpj, $contato);
            $rpes = $pessoa->update();
            if ($rpes === -10 || $rpes === -1) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return json_encode("Ocorreu um problema ao alterar a pessoa.");
            }
            if ($rpes === -5) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return json_encode("Parâmetros inválidos.");
            }
            $prop = new Proprietario($prp, date('Y-m-d'), $tipo, $motorista, null, $pessoa);
            $rprp = $prop->update();
            if ($rprp === -10 || $rprp === -1) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return json_encode("Ocorreu um problema ao alterar o proprietário.");
            }
            if ($rprp === -5) {
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