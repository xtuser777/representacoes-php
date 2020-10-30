<?php


namespace scr\control;


use scr\model\ContaPagar;
use scr\model\FormaPagamento;
use scr\util\Banco;

class ContasPagarControl
{
    public function obter()
    {
        if (!Banco::getInstance()->open()) 
            return json_encode([]);
        
        $contas = (new ContaPagar())->findAll();
        
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

    public function obterPorData(string $data, int $ordem)
    {
        if (!Banco::getInstance()->open()) 
            return json_encode([]);
        
        $contas = (new ContaPagar())->findByDate($data, $this->traduzirOrdem($ordem));
        
        Banco::getInstance()->getConnection()->close();
        
        $serial = [];
        /** @var $conta ContaPagar */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorDataSituacao(string $data, int $situacao, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaPagar())->findByDateSituation($data, $situacao, $this->traduzirOrdem($ordem));

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

    public function obterPorFiltroData(string $filtro, string $data, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaPagar())->findByDescriptionDate($filtro, $data, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $conta ContaPagar */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltroDataSituacao(string $filtro, string $data, int $situacao, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaPagar())->findByDescriptionDateSituation($filtro, $data, $situacao, $this->traduzirOrdem($ordem));

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

        Banco::getInstance()->getConnection()->commit();
        Banco::getInstance()->getconnection()->close();

        return json_encode("");
    }
}
