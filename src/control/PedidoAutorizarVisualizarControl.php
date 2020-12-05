<?php


namespace scr\control;


use scr\model\EtapaCarregamento;
use scr\model\Evento;
use scr\model\Parametrizacao;
use scr\model\PedidoFrete;
use scr\model\Status;
use scr\model\StatusPedido;
use scr\model\Usuario;
use scr\util\Autorizacao;
use scr\util\Banco;

class PedidoAutorizarVisualizarControl
{
    public function obter()
    {
        if (!isset($_COOKIE["PEDFRE"]))
            return json_encode("Pedido não selecionado.");

        if (!Banco::getInstance()->open())
            return json_encode("Erro ao conectar no banco de dados.");

        $pedido = (new PedidoFrete())->findById($_COOKIE["PEDFRE"]);

        Banco::getInstance()->getConnection()->close();

        return json_encode($pedido ? $pedido->jsonSerialize() : null);
    }

    public function autorizar(int $etapa, int $pedido)
    {
        if (!Banco::getInstance()->open())
            return json_encode("Erro ao conectar no banco de dados.");

        $frete = (new PedidoFrete())->findById($pedido);

        $etapa = (new EtapaCarregamento())->findById($etapa, $pedido);
        if ($etapa === null)
            return json_encode("Registro não encontrado.");

        Banco::getInstance()->getConnection()->begin_transaction();

        $res = $etapa->autorize();
        if ($res === -10 || $res === -1) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Ocorreu um problema ao atualizar o registro da etapa.");
        }

        if ($res == -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Parâmetro ou registro inválido.");
        }

        if ($frete->getStatus()->getStatus()->getId() === 1) {
            $sp = new StatusPedido();
            $sp->setStatus((new Status())->findById(2));
            $sp->setData(date("Y-m-d"));
            $sp->setObservacoes("");
            $sp->setAtual(true);
            $sp->setAutor(Usuario::getById($_COOKIE["USER_ID"]));

            $res1 = $sp->save($frete->getId());
            if ($res1 === -10 || $res1 === -1) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return json_encode("Ocorreu um problema ao vincular o novo status.");
            }

            if ($res1 == -5) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return json_encode("Parâmetro ou registro inválido.");
            }

            $frete->getStatus()->desatualizar($pedido, 1);
        }

        $ordem = $etapa->getOrdem();
        $descricao = $frete->getDescricao();

        $evento = new Evento();
        $evento->setDescricao("Autorização de carregamento da etapa $ordem do pedido de frete $descricao.");
        $evento->setData(date("y-m-d"));
        $evento->setHora(date("H:i:s"));
        $evento->setPedidoFrete($frete);
        $evento->setAutor(Usuario::getById($_COOKIE["USER_ID"]));

        $res2 = $evento->save();
        if ($res2 === -10 || $res2 === -1) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Ocorreu um problema ao criar o evento.");
        }

        if ($res2 == -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Parâmetro ou registro inválido.");
        }

        Banco::getInstance()->getConnection()->commit();
        Banco::getInstance()->getConnection()->close();

        return json_encode("");
    }

    public function gerarDocumentoAutorizacao(int $pedido, int $etapaId)
    {
        if (!Banco::getInstance()->open())
            return "Erro ao conectar no banco de dados.";

        $parametrizacao = Parametrizacao::get();
        $frete = (new PedidoFrete())->findById($pedido);

        $etapa = (new EtapaCarregamento())->findById($etapaId, $pedido);

        Banco::getInstance()->getConnection()->close();

        $etp = $etapa->getOrdem();

        $par = (object) $parametrizacao->jsonSerialize();
        $par->pessoa = (object) $par->pessoa;
        $par->pessoa->contato = (object) $par->pessoa->contato;
        $par->pessoa->contato->endereco = (object) $par->pessoa->contato->endereco;
        $par->pessoa->contato->endereco->cidade = (object) $par->pessoa->contato->endereco->cidade;
        $par->pessoa->contato->endereco->cidade->estado = (object) $par->pessoa->contato->endereco->cidade->estado;

        $autorizacao = new Autorizacao("P", "mm", "A4", $par);
        $autorizacao->AddPage();
        $autorizacao->DadosDocumento();
        $autorizacao->DadosRepresentacao(
            $frete->getId(),
            $etapa->getRepresentacao()->getPessoa()->getRazaoSocial(),
            ""
        );
        $autorizacao->TituloDocumento();
        $autorizacao->DadosMotorista(
            $frete->getMotorista()->getId(),
            $frete->getMotorista()->getPessoa()->getNome(),
            $frete->getMotorista()->getPessoa()->getCpf(),
            $frete->getMotorista()->getPessoa()->getRg(),
            $frete->getMotorista()->getCnh(),
            $frete->getCaminhao()->getPlaca(),
            ""
        );
        $autorizacao->DadosProprietario(
            $frete->getProprietario()->getTipo() === 1
                ? $frete->getProprietario()->getPessoaFisica()->getNome()
                : $frete->getProprietario()->getPessoaJuridica()->getNomeFantasia(),
            $frete->getProprietario()->getTipo() === 1
                ? $frete->getProprietario()->getPessoaFisica()->getCpf()
                : $frete->getProprietario()->getPessoaJuridica()->getCnpj()
        );
        $autorizacao->DadosCliente(
            $frete->getCliente()->getId(),
            $frete->getCliente()->getTipo() === 1
                ? $frete->getCliente()->getPessoaFisica()->getNome()
                : $frete->getCliente()->getPessoaJuridica()->getNomeFantasia(),
            $frete->getCliente()->getTipo() === 1
                ? $frete->getCliente()->getPessoaFisica()->getCpf()
                : $frete->getCliente()->getPessoaJuridica()->getCnpj(),
            $frete->getCliente()->getTipo() === 1
                ? $frete->getCliente()->getPessoaFisica()->getRg()
                : "",
            $frete->getCliente()->getTipo() === 1
                ? $frete->getCliente()->getPessoaFisica()->getContato()->getEndereco()->getCidade()->getNome()
                : $frete->getCliente()->getPessoaJuridica()->getContato()->getEndereco()->getCidade()->getNome(),
            $frete->getCliente()->getTipo() === 1
                ? $frete->getCliente()->getPessoaFisica()->getContato()->getEndereco()->getCidade()->getEstado()->getNome()
                : $frete->getCliente()->getPessoaJuridica()->getContato()->getEndereco()->getCidade()->getEstado()->getNome(),
            $etapa->getRepresentacao()->getPessoa()->getNomeFantasia()
        );
        $autorizacao->TabelaItens($frete->getItens());
        $autorizacao->Observacoes();
        $autorizacao->Mensagem();
        $autorizacao->Assinatura();

        return $autorizacao->Output("I", "AutorizacaoCarregamentoEtapa$etp");
    }
}