<?php namespace scr\control;

use scr\model\Cidade;
use scr\model\Contato;
use scr\model\DadosBancarios;
use scr\model\Endereco;
use scr\model\Motorista;
use scr\model\PessoaFisica;
use scr\util\Banco;

class MotoristaDetalhesControl
{
    public function obter()
    {
        if (!isset($_SESSION["MOTORISTA"])) return json_encode(null);
        if (!Banco::getInstance()->open()) return json_encode(null);
        $motorista = Motorista::findById($_SESSION["MOTORISTA"]);
        Banco::getInstance()->getConnection()->close();

        return json_encode($motorista !== null ? $motorista->jsonSerialize() : null);
    }

    public function verificarCpf(string $cpf)
    {
        if (!Banco::getInstance()->open()) return json_encode(true);
        $res = PessoaFisica::verifyCpf($cpf);
        Banco::getInstance()->getConnection()->close();

        return json_encode($res);
    }

    public function alterar(int $end, int $ctt, int $pes, int $dad, int $mot, string $nome, string $rg, string $cpf, string $nasc, string $banco, string $agencia, string $conta, int $tipo, string $tel, string $cel, string $email, string $rua, string $num, string $bairro, string $comp, string $cep, int $cid)
    {
        if (!Banco::getInstance()->open()) return json_encode("Erro ao conectar-se ao banco de dados.");
        $cidade = Cidade::getById($cid);
        Banco::getInstance()->getConnection()->begin_transaction();
        $endereco = new Endereco($end,$rua,$num,$bairro,$comp,$cep,$cidade);
        $re = $endereco->update();
        if ($re == -10 || $re == -1) {
            Banco::getInstance()->getConnection()->close();
            return json_encode('Ocorreu um problema ao alterar o endereço.');
        }
        if ($re == -5) {
            Banco::getInstance()->getConnection()->close();
            return json_encode('Um ou mais campos inválidos no endereço.');
        }
        $contato = new Contato($ctt,$tel,$cel,$email,$endereco);
        $rc = $contato->update();
        if ($rc == -10 || $rc == -1) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode('Ocorreu um problema ao alterar o contato.');
        }
        if ($rc == -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode('Um ou mais campos inválidos no contato.');
        }
        $pessoa = new PessoaFisica($pes,$nome,$rg,$cpf, $nasc,$contato);
        $rp = $pessoa->update();
        if ($rp == -10 || $rp == -1) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode('Ocorreu um problema ao alterar a pessoa.');
        }
        if ($rp == -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode('Um ou mais campos inválidos na pessoa.');
        }
        $dados = new DadosBancarios($dad,$banco,$agencia,$conta,$tipo);
        $rd = $dados->update();
        if ($rd == -10 || $rd == -1) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode('Ocorreu um problema ao alterar a pessoa.');
        }
        if ($rd == -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode('Um ou mais campos inválidos na pessoa.');
        }
        Banco::getInstance()->getConnection()->commit();
        Banco::getInstance()->getConnection()->close();

        return json_encode("");
    }
}