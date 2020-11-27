<?php


namespace scr\control;


use scr\model\ContaPagar;
use scr\model\Evento;
use scr\model\FormaPagamento;
use scr\model\Funcionario;
use scr\model\PedidoFrete;
use scr\model\Usuario;
use scr\util\Banco;

class ContasPagarControl
{
    public function obter(int $ordem)
    {
        if (!Banco::getInstance()->open()) 
            return json_encode([]);
        
        $contas = (new ContaPagar())->findAll($this->traduzirOrdem($ordem));
        
        Banco::getInstance()->getConnection()->close();
        
        $serial = [];
        /** @var $conta ContaPagar */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltro(string $filtro, int $ordem)
    {
        if (!Banco::getInstance()->open()) 
            return json_encode([]);
        
        $contas = (new ContaPagar())->findByDescription($filtro, $this->traduzirOrdem($ordem));
        
        Banco::getInstance()->getConnection()->close();
        
        $serial = [];
        /** @var $conta ContaPagar */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltroComissao(string $filtro, int $comissao, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaPagar())->findByDescriptionComission($filtro, $comissao, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $conta ContaPagar */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltroComissaoVendedor(string $filtro, int $comissao, int $vendedor, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaPagar())->findByDescriptionComissionEmployee($filtro, $comissao, $vendedor, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $conta ContaPagar */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltroSituacao(string $filtro, int $situation, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaPagar())->findByDescriptionSituation($filtro, $situation, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $conta ContaPagar */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltroComissaoSituacao(string $filtro, int $comissao, int $situation, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaPagar())->findByDescriptionComissionSituation($filtro, $comissao, $situation, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $conta ContaPagar */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltroComissaoVendedorSituacao(string $filtro, int $comissao, int $vendedor, int $situation, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaPagar())->findByDescriptionComissionEmployeeSituation($filtro, $comissao, $vendedor, $situation, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $conta ContaPagar */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorSituacao(int $situation, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaPagar())->findBySituation($situation, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $conta ContaPagar */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorComissao(int $comissao, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaPagar())->findByComission($comissao, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $conta ContaPagar */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorComissaoVendedor(int $comissao, int $vendedor, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaPagar())->findByComissionEmployee($comissao, $vendedor, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $conta ContaPagar */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorComissaoSituacao(int $comissao, int $situation, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaPagar())->findByComissionSituation($comissao, $situation, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $conta ContaPagar */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorComissaoVendedorSituacao(int $comissao, int $vendedor, int $situation, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaPagar())->findByComissionEmployeeSituation($comissao, $vendedor, $situation, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $conta ContaPagar */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorPeriodo(string $data1, string $data2, int $ordem)
    {
        if (!Banco::getInstance()->open()) 
            return json_encode([]);
        
        $contas = (new ContaPagar())->findByPeriod($data1, $data2, $this->traduzirOrdem($ordem));
        
        Banco::getInstance()->getConnection()->close();
        
        $serial = [];
        /** @var $conta ContaPagar */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorPeriodoComissao(string $data1, string $data2, int $comissao, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaPagar())->findByPeriodComission($data1, $data2, $comissao, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $conta ContaPagar */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorPeriodoComissaoVendedor(string $data1, string $data2, int $comissao, int $vendedor, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaPagar())->findByPeriodComissionEmployee($data1, $data2, $comissao, $vendedor, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $conta ContaPagar */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorPeriodoSituacao(string $data1, string $data2, int $situacao, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaPagar())->findByPeriodSituation($data1, $data2, $situacao, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $conta ContaPagar */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorPeriodoComissaoSituacao(string $data1, string $data2, int $comissao, int $situacao, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaPagar())->findByPeriodComissionSituation($data1, $data2, $comissao, $situacao, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $conta ContaPagar */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorPeriodoComissaoVendedorSituacao(string $data1, string $data2, int $comissao, int $vendedor, int $situacao, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaPagar())->findByPeriodComissionEmployeeSituation($data1, $data2, $comissao, $vendedor, $situacao, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $conta ContaPagar */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltroPeriodo(string $filtro, string $data1, string $data2, int $ordem)
    {
        if (!Banco::getInstance()->open()) 
            return json_encode([]);
        
        $contas = (new ContaPagar())->findByDescriptionPeriod($filtro, $data1, $data2, $this->traduzirOrdem($ordem));
        
        Banco::getInstance()->getConnection()->close();
        
        $serial = [];
        /** @var $conta ContaPagar */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltroPeriodoComissao(string $filtro, string $data1, string $data2, int $comissao, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaPagar())->findByDescriptionPeriodComission($filtro, $data1, $data2, $comissao, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $conta ContaPagar */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltroPeriodoComissaoVendedor(string $filtro, string $data1, string $data2, int $comissao, int $vendedor, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaPagar())->findByDescriptionPeriodComissionEmployee($filtro, $data1, $data2, $comissao, $vendedor, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $conta ContaPagar */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltroPeriodoSituacao(string $filtro, string $data1, string $data2, int $situacao, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaPagar())->findByDescriptionPeriodSituation($filtro, $data1, $data2, $situacao, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $conta ContaPagar */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltroPeriodoComissaoSituacao(string $filtro, string $data1, string $data2, int $comissao, int $situacao, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaPagar())->findByDescriptionPeriodComissionSituation($filtro, $data1, $data2, $comissao, $situacao, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $conta ContaPagar */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltroPeriodoComissaoVendedorSituacao(string $filtro, string $data1, string $data2, int $comissao, int $vendedor, int $situacao, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaPagar())->findByDescriptionPeriodComissionEmployeeSituation($filtro, $data1, $data2, $comissao, $vendedor, $situacao, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $conta ContaPagar */
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

    public function obterVendedores()
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $vendedores = Funcionario::getVendedores();

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $vendedor Funcionario */
        foreach ($vendedores as $vendedor) {
            $serial[] = $vendedor->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function traduzirOrdem(int $ordem)
    {
        $ordemTraduzida = "";

        switch ($ordem) {
            case "1": $ordemTraduzida = "con_pag_conta,con_pag_parcela"; break;

            case "2": $ordemTraduzida = "con_pag_conta"; break;

            case "3": $ordemTraduzida = "con_pag_conta DESC"; break;

            case "4": $ordemTraduzida = "con_pag_descricao"; break;

            case "5": $ordemTraduzida = "con_pag_descricao DESC"; break;

            case "6": $ordemTraduzida = "con_pag_parcela"; break;

            case "7": $ordemTraduzida = "con_pag_parcela DESC"; break;

            case "8": $ordemTraduzida = "con_pag_valor"; break;

            case "9": $ordemTraduzida = "con_pag_valor DESC"; break;

            case "10": $ordemTraduzida = "con_pag_vencimento"; break;

            case "11": $ordemTraduzida = "con_pag_vencimento DESC"; break;

            case "12": $ordemTraduzida = "con_pag_situacao"; break;

            case "13": $ordemTraduzida = "con_pag_situacao DESC"; break;
        }

        return $ordemTraduzida;
    }

    public function enviar(int $id)
    {
        if ($id <= 0)
            return json_encode("Parâmetro inválido.");

        Banco::getInstance()->open();
        $conta = (new  ContaPagar())->findById($id);
        Banco::getInstance()->getConnection()->close();

        if ($conta->getSituacao() > 2)
            return json_encode("Não é possível alterar uma conta já paga.");

        setcookie("CONPAG", $id, time() + 3600, "/", "", 0 , 1);

        return json_encode("");
    }

    public function estornar(int $id)
    {
        if ($id <= 0)
            return json_encode("Parâmetro inválido.");

        if (!Banco::getInstance()->open())
            return json_encode("Problemas ao se conectar com o banco de dados.");

        $conta = (new ContaPagar())->findById($id);
        if ($conta === null)
            return json_encode("Registro não encontrado.");

        if ($conta->getSituacao() === 1)
            return json_encode("Esta conta ainda não foi quitada...");

        if ($conta->getPendencia() !== null && $conta->getPendencia()->getSituacao() > 1)
            return json_encode("Esta conta possui pendências pagas... Estorne-as primeiro.");

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
     * @param ContaPagar $conta
     * @param Usuario $autor
     * @return int
     */
    private function criarEvento(ContaPagar $conta, Usuario $autor): int
    {
        if ($conta === null || $autor === null)
            return -5;

        $evento = new Evento();
        $evento->setDescricao("A conta a pagar \"" . $conta->getDescricao() . "\" foi estornada.");
        $evento->setData(date("Y-m-d"));
        $evento->setHora(date("H:i:s"));
        if ($conta->getPedidoVenda())
            $evento->setPedidoVenda($conta->getPedidoVenda());

        if ($conta->getPedidoFrete())
            $evento->setPedidoFrete($conta->getPedidoFrete());

        $evento->setAutor($autor);

        return $evento->save();
    }
}
