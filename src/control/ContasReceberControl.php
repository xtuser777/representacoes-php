<?php


namespace scr\control;


use scr\model\ContaReceber;
use scr\model\Evento;
use scr\model\FormaPagamento;
use scr\model\Representacao;
use scr\model\Usuario;
use scr\util\Banco;

class ContasReceberControl
{
    public function obter(int $ordem)
    {
        if (!Banco::getInstance()->open()) 
            return json_encode([]);
        
        $contas = (new ContaReceber())->findAll($this->traduzirOrdem($ordem));
        
        Banco::getInstance()->getConnection()->close();
        
        $serial = [];
        /** @var $conta ContaReceber */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterRepresentacoes()
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $representacoes = Representacao::getAll();

        Banco::getInstance()->getConnection()->close();

        $jarray = [];
        /** @var Representacao $representacao */
        foreach ($representacoes as $representacao) {
            $jarray[] = $representacao->jsonSerialize();
        }

        return json_encode($jarray);
    }

    public function obterPorFiltro(string $filtro, int $ordem)
    {
        if (!Banco::getInstance()->open()) 
            return json_encode([]);
        
        $contas = (new ContaReceber())->findByDescription($filtro, $this->traduzirOrdem($ordem));
        
        Banco::getInstance()->getConnection()->close();
        
        $serial = [];
        /** @var $conta  */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltroComissao(string $filtro, int $comissao, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaReceber())->findByDescriptionComission($filtro, $comissao, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $conta ContaReceber */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltroComissaoRepresentacao(string $filtro, int $comissao, int $representacao, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaReceber())->findByDescriptionComissionRepresentation($filtro, $comissao, $representacao, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $conta ContaReceber */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltroSituacao(string $filtro, int $situation, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaReceber())->findByDescriptionSituation($filtro, $situation, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $conta ContaReceber */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltroComissaoSituacao(string $filtro, int $comissao, int $situation, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaReceber())->findByDescriptionComissionSituation($filtro, $comissao, $situation, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $conta ContaReceber */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltroComissaoRepresentacaoSituacao(string $filtro, int $comissao, int $representacao, int $situation, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaReceber())->findByDescriptionComissionRepresentationSituation($filtro, $comissao, $representacao, $situation, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $conta ContaReceber */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorComissao(int $comissao, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaReceber())->findByComission($comissao, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $conta ContaReceber */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorComissaoRepresentacao(int $comissao, int $representacao, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaReceber())->findByComissionRepresentation($comissao, $representacao, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $conta ContaReceber */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorSituacao(int $situation, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaReceber())->findBySituation($situation, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $conta ContaReceber */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorComissaoSituacao(int $comissao, int $situation, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaReceber())->findByComissionSituation($comissao, $situation, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $conta ContaReceber */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorComissaoRepresentacaoSituacao(int $comissao, int $representacao, int $situation, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaReceber())->findByComissionRepresentationSituation($comissao, $representacao, $situation, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $conta ContaReceber */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorData(string $data, int $ordem)
    {
        if (!Banco::getInstance()->open()) 
            return json_encode([]);
        
        $contas = (new ContaReceber())->findByDate($data, $this->traduzirOrdem($ordem));
        
        Banco::getInstance()->getConnection()->close();
        
        $serial = [];
        /** @var $conta ContaReceber */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorDataComissao(string $data, int $comissao, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaReceber())->findByDateComission($data, $comissao, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $conta ContaReceber */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorDataComissaoRepresentacao(string $data, int $comissao, int $representacao, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaReceber())->findByDateComissionRepresentation($data, $comissao, $representacao, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $conta ContaReceber */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorDataSituacao(string $data, int $situacao, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaReceber())->findByDateSituation($data, $situacao, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $conta ContaReceber */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorDataComissaoSituacao(string $data, int $comissao, int $situacao, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaReceber())->findByDateComissionSituation($data, $comissao, $situacao, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $conta ContaReceber */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorDataComissaoRepresentacaoSituacao(string $data, int $comissao, int $representacao, int $situacao, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaReceber())->findByDateComissionRepresentationSituation($data, $comissao, $representacao, $situacao, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $conta ContaReceber */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorPeriodo(string $data1, string $data2, int $ordem)
    {
        if (!Banco::getInstance()->open()) 
            return json_encode([]);
        
        $contas = (new ContaReceber())->findByPeriod($data1, $data2, $this->traduzirOrdem($ordem));
        
        Banco::getInstance()->getConnection()->close();
        
        $serial = [];
        /** @var $conta ContaReceber */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorPeriodoComissao(string $data1, string $data2, int $comissao, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaReceber())->findByPeriodComission($data1, $data2, $comissao, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $conta ContaReceber */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorPeriodoComissaoRepresentacao(string $data1, string $data2, int $comissao, int $representacao, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaReceber())->findByPeriodComissionRepresentation($data1, $data2, $comissao, $representacao, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $conta ContaReceber */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorPeriodoSituacao(string $data1, string $data2, int $situacao, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaReceber())->findByPeriodSituation($data1, $data2, $situacao, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $conta ContaReceber */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorPeriodoComissaoSituacao(string $data1, string $data2, int $comissao, int $situacao, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaReceber())->findByPeriodComissionSituation($data1, $data2, $comissao, $situacao, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $conta ContaReceber */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorPeriodoComissaoRepresentacaoSituacao(string $data1, string $data2, int $comissao, int $representation, int $situacao, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaReceber())->findByPeriodComissionRepresentationSituation($data1, $data2, $comissao, $representation, $situacao, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $conta ContaReceber */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltroData(string $filtro, string $data, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaReceber())->findByDescriptionDate($filtro, $data, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $conta ContaReceber */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltroDataComissao(string $filtro, string $data, int $comissao, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaReceber())->findByDescriptionDateComission($filtro, $data, $comissao, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $conta ContaReceber */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltroDataComissaoRepresentacao(string $filtro, string $data, int $comissao, int $representacao, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaReceber())->findByDescriptionDateComissionRepresentation($filtro, $data, $comissao, $representacao, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $conta ContaReceber */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltroDataSituacao(string $filtro, string $data, int $situacao, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaReceber())->findByDescriptionDateSituation($filtro, $data, $situacao, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $conta ContaReceber */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltroDataComissaoSituacao(string $filtro, string $data, int $comissao, int $situacao, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaReceber())->findByDescriptionDateComissionSituation($filtro, $data, $comissao, $situacao, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $conta ContaReceber */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltroDataComissaoRepresentacaoSituacao(string $filtro, string $data, int $comissao, int $representacao, int $situacao, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaReceber())->findByDescriptionDateComissionRepresentationSituation($filtro, $data, $comissao, $representacao, $situacao, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $conta ContaReceber */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltroPeriodo(string $filtro, string $data1, string $data2, int $ordem)
    {
        if (!Banco::getInstance()->open()) 
            return json_encode([]);
        
        $contas = (new ContaReceber())->findByDescriptionPeriod($filtro, $data1, $data2, $this->traduzirOrdem($ordem));
        
        Banco::getInstance()->getConnection()->close();
        
        $serial = [];
        /** @var $conta ContaReceber */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltroPeriodoComissao(string $filtro, string $data1, string $data2, int $comissao, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaReceber())->findByDescriptionPeriodComission($filtro, $data1, $data2, $comissao, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $conta ContaReceber */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltroPeriodoComissaoRepresentacao(string $filtro, string $data1, string $data2, int $comissao, int $representacao, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaReceber())->findByDescriptionPeriodComissionRepresentation($filtro, $data1, $data2, $comissao, $representacao, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $conta ContaReceber */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltroPeriodoSituacao(string $filtro, string $data1, string $data2, int $situacao, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaReceber())->findByDescriptionPeriodSituation($filtro, $data1, $data2, $situacao, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $conta ContaReceber */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltroPeriodoComissaoSituacao(string $filtro, string $data1, string $data2, int $comissao, int $situacao, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaReceber())->findByDescriptionPeriodComissionSituation($filtro, $data1, $data2, $comissao, $situacao, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $conta ContaReceber */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltroPeriodoComissaoRepresentacaoSituacao(string $filtro, string $data1, string $data2, int $comissao, int $representacao, int $situacao, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaReceber())->findByDescriptionPeriodComissionRepresentationSituation($filtro, $data1, $data2, $comissao, $representacao, $situacao, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $conta ContaReceber */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterFormas()
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $formas = FormaPagamento::findByPayment();

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $forma FormaPagamento */
        foreach ($formas as $forma) {
            $serial[] = $forma->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function traduzirOrdem(int $ordem)
    {
        $ordemTraduzida = "";

        switch ($ordem) {
            case "1": $ordemTraduzida = "con_rec_conta"; break;

            case "2": $ordemTraduzida = "con_rec_conta DESC"; break;

            case "3": $ordemTraduzida = "con_rec_descricao"; break;

            case "4": $ordemTraduzida = "con_rec_descricao DESC"; break;

            case "5": $ordemTraduzida = "con_rec_valor"; break;

            case "6": $ordemTraduzida = "con_rec_valor DESC"; break;

            case "7": $ordemTraduzida = "con_rec_vencimento"; break;

            case "8": $ordemTraduzida = "con_rec_vencimento DESC"; break;

            case "9": $ordemTraduzida = "con_rec_situacao"; break;

            case "10": $ordemTraduzida = "con_rec_situacao DESC"; break;
        }

        return $ordemTraduzida;
    }

    public function enviar(int $id)
    {
        if ($id <= 0)
            return json_encode("Parâmetro inválido.");

        Banco::getInstance()->open();

        $conta = (new ContaReceber())->findById($id);

        Banco::getInstance()->getConnection()->close();

        if ($conta->getSituacao() > 2)
            return json_encode("Não é possível alterar uma conta já paga.");

        setcookie("CONREC", $id, time() + 3600, "/", "", 0 , 1);

        return json_encode("");
    }

    public function estornar(int $id)
    {
        if ($id <= 0)
            return json_encode("Parâmetro inválido.");

        if (!Banco::getInstance()->open())
            return json_encode("Problemas ao se conectar com o banco de dados.");

        $conta = (new ContaReceber())->findById($id);
        if ($conta === null)
            return json_encode("Registro não encontrado.");

        if ($conta->getSituacao() === 1)
            return json_encode("Esta conta ainda não foi recebida...");

        if ($conta->getPendencia() !== null && $conta->getPendencia()->getSituacao() > 1)
            return json_encode("Esta conta possui pendências recebidas... Estorne-as primeiro.");

        $autor = Usuario::getById($_COOKIE["USER_ID"]);

        Banco::getInstance()->getConnection()->begin_transaction();

        $cp = $conta->estornar();
        if ($cp == -10 || $cp == -1) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getconnection()->close();
            return json_encode("Ocorreu um problema ao estornar a despesa.");
        }
        if ($cp == -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getconnection()->close();
            return json_encode("Parâmetro inválido.");
        }

        if ($conta->getPendencia() !== null) {
            $cpp = $conta->getPendencia()->delete();
            if ($cpp == -10 || $cpp == -1) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getconnection()->close();
                return json_encode("Ocorreu um problema ao excluir a pendência da despesa.");
            }
            if ($cpp == -5) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getconnection()->close();
                return json_encode("Parâmetro inválido.");
            }
        }

        $evt = $this->criarEvento($conta, $autor);
        if ($evt == -10 || $evt == -1) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getconnection()->close();
            return json_encode("Ocorreu um problema ao criar o evento.");
        }
        if ($evt == -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getconnection()->close();
            return json_encode("Parâmetros inválidos.");
        }

        Banco::getInstance()->getConnection()->commit();
        Banco::getInstance()->getconnection()->close();

        return json_encode("");
    }

    /**
     * @param ContaReceber $conta
     * @param Usuario $autor
     * @return int
     */
    private function criarEvento(ContaReceber $conta, Usuario $autor): int
    {
        if ($conta === null || $autor === null)
            return -5;

        $evento = new Evento();
        $evento->setDescricao("A conta a receber \"". $conta->getDescricao() ."\" foi estornada.");
        $evento->setData(date("Y-m-d"));
        $evento->setHora(date("H:i:s"));
        $evento->setAutor($autor);
        if ($conta->getPedidoVenda())
            $evento->setPedidoVenda($conta->getPedidoVenda());

        if ($conta->getPedidoFrete())
            $evento->setPedidoFrete($conta->getPedidoFrete());

        return $evento->save();
    }
}
