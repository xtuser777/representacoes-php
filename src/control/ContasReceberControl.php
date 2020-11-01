<?php


namespace scr\control;


use scr\model\ContaReceber;
use scr\model\FormaPagamento;
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
        /** @var $conta  */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
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

    public function obterPorFiltroSituacao(string $filtro, int $situation, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaReceber())->findByDescriptionSituation($filtro, $situation, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $conta  */
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
        /** @var $conta  */
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
        /** @var $conta  */
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
        /** @var $conta  */
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
        /** @var $conta  */
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
        /** @var $conta  */
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
        /** @var $conta  */
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
        /** @var $conta  */
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
        /** @var $conta  */
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
        /** @var $conta  */
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
