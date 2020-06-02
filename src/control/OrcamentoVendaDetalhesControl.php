<?php


namespace scr\control;


use scr\model\Cidade;
use scr\model\Cliente;
use scr\model\Contato;
use scr\model\Funcionario;
use scr\model\ItemOrcamentoVenda;
use scr\model\OrcamentoVenda;
use scr\model\PessoaFisica;
use scr\model\PessoaJuridica;
use scr\model\Produto;
use scr\model\Usuario;
use scr\util\Banco;

class OrcamentoVendaDetalhesControl
{
    public function obter()
    {
        if (!Banco::getInstance()->open()) return json_encode(null);
        $orcamento = OrcamentoVenda::findById($_SESSION["ORCVEN"]);
        Banco::getInstance()->getConnection()->close();

        return json_encode(($orcamento !== null) ? $orcamento->jsonSerialize() : null);
    }

    public function obterClientes()
    {
        if (!Banco::getInstance()->open()) return json_encode([]);
        $clientes = Cliente::getAll();
        Banco::getInstance()->getConnection()->close();
        $serial = [];
        /** @var Cliente $cliente */
        foreach ($clientes as $cliente) {
            $serial[] = $cliente->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterVendedores()
    {
        if (!Banco::getInstance()->open()) return json_encode([]);
        $vendedores = Funcionario::getVendedores();
        Banco::getInstance()->getConnection()->close();
        $serial = [];
        /** @var Funcionario $vendedor */
        foreach ($vendedores as $vendedor) {
            $serial[] = $vendedor->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function validarEmail(string $email)
    {
        return json_encode(Contato::validarEmail($email));
    }

    public function validarCPF(string $cpf)
    {
        return json_encode(PessoaFisica::validarCPF($cpf));
    }

    public function validarCNPJ(string $cnpj)
    {
        return json_encode(PessoaJuridica::validarCNPJ($cnpj));
    }

    public function alterar(int $orc, int $cli, string $nc, string $dc, string $tc, string $cc, string $ec, string $desc, int $vdd, int $cid, float $peso, float $valor, string $venc, array $itens)
    {
        if (!Banco::getInstance()->open()) return json_encode("Erro ao conectar-se ao banco de dados.");
        $cliente = ($cli > 0) ? Cliente::getById($cli) : null;
        $vendedor = ($vdd > 0) ? Funcionario::getById($vdd) : null;
        $cidade = Cidade::getById($cid);
        if (!$cidade) return json_encode("Cidade não encontrada no cadastro.");
        $usuario = Usuario::getById($_SESSION["USER_ID"]);
        Banco::getInstance()->getConnection()->begin_transaction();
        $orcamento = new OrcamentoVenda(
            $orc, $desc, date('Y-m-d'), $nc, $dc, $tc, $cc, $ec, $peso, $valor, $venc,
            $vendedor, $cliente, $cidade, $usuario
        );
        $res = $orcamento->update();
        if ($res === -10 || $res === -1) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Erro ao tentar gravar os dados do orçamento.");
        }
        if ($res === -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Parâmetros inválidos.");
        }

        $itensBanco = ItemOrcamentoVenda::findAllItems($orc);
        /** @var ItemOrcamentoVenda $item */
        foreach ($itensBanco as $item) {
            $rid = $item->delete();
            if ($rid === -10 || $rid === -1) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return json_encode("Erro ao tentar alterar os dados de um item do orçamento.");
            }
            if ($rid === -5) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return json_encode("Parâmetros inválidos de um item do orçamento.");
            }
        }

        for ($i = 0; $i < count($itens); $i++) {
            $produto = Produto::findById($itens[$i]->produto->id);
            $itemOrc = new ItemOrcamentoVenda($orcamento, $produto, $itens[$i]->quantidade, $itens[$i]->valor, $itens[$i]->peso);
            $ri = $itemOrc->save();
            if ($ri === -10 || $ri === -1) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return json_encode("Erro ao tentar alterar os dados de um item do orçamento.");
            }
            if ($ri === -5) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return json_encode("Parâmetros inválidos de um item do orçamento.");
            }
        }
        Banco::getInstance()->getConnection()->commit();
        Banco::getInstance()->getConnection()->close();

        return json_encode("");
    }
}