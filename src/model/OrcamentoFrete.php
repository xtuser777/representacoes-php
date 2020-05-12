<?php namespace scr\model;

use scr\dao\OrcamentoFreteDAO;

class OrcamentoFrete
{
    private $id;
    private $descricao;
    private $data;
    private $distancia;
    private $peso;
    private $valor;
    private $entrega;
    private $validade;
    private $orcamentoVenda;
    private $representacao;
    private $tipoCaminhao;
    private $destino;
    private $autor;

    public function __construct(int $id, string $descricao, string $data, int $distancia, float $peso, float $valor, string $entrega, string $validade, ?OrcamentoVenda $orcamentoVenda, ?Representacao $representacao, TipoCaminhao $tipoCaminhao, Cidade $destino, Usuario $autor)
    {
        $this->id = $id;
        $this->descricao = $descricao;
        $this->data = $data;
        $this->distancia = $distancia;
        $this->peso = $peso;
        $this->valor = $valor;
        $this->entrega = $entrega;
        $this->validade = $validade;
        $this->orcamentoVenda = $orcamentoVenda;
        $this->representacao = $representacao;
        $this->tipoCaminhao = $tipoCaminhao;
        $this->destino = $destino;
        $this->autor = $autor;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getDescricao(): string
    {
        return $this->descricao;
    }

    public function getData(): string
    {
        return $this->data;
    }

    public function getDistancia(): int
    {
        return $this->distancia;
    }

    public function getPeso(): float
    {
        return $this->peso;
    }

    public function getValor(): float
    {
        return $this->valor;
    }

    public function getEntrega(): string
    {
        return $this->entrega;
    }

    public function getValidade(): string
    {
        return $this->validade;
    }

    public function getOrcamentoVenda(): ?OrcamentoVenda
    {
        return $this->orcamentoVenda;
    }

    public function getRepresentacao(): ?Representacao
    {
        return $this->representacao;
    }

    public function getTipoCaminhao(): TipoCaminhao
    {
        return $this->tipoCaminhao;
    }

    public function getDestino(): Cidade
    {
        return $this->destino;
    }

    public function getAutor(): Usuario
    {
        return $this->autor;
    }

    public static function findById(int $id): ?OrcamentoFrete
    {
        return $id > 0 ? OrcamentoFreteDAO::selectId($id) : null;
    }

    public static function findByKey(string $key): array
    {
        return strlen(trim($key)) > 0 ? OrcamentoFreteDAO::selectKey($key) : [];
    }

    public static function findByDate(string $date): array
    {
        return strlen(trim($date)) > 0 ? OrcamentoFreteDAO::selectDate($date) : [];
    }

    public static function findByKeyDate(string $key, string $date): array
    {
        return strlen(trim($key)) > 0 && strlen(trim($date)) > 0 ? OrcamentoFreteDAO::selectKeyDate($key, $date) : [];
    }

    public static function findAll(): array
    {
        return OrcamentoFreteDAO::select();
    }

    public function save(): int
    {
        if ($this->id != 0 || strlen($this->descricao) <= 0 || strlen($this->data) <= 0 ||
            $this->distancia <= 0 || $this->valor <= 0 || $this->peso <= 0 || strlen($this->validade) <= 0 ||
            $this->destino == null || $this->tipoCaminhao == null || $this->autor == null
        ) return -5;

        return OrcamentoFreteDAO::insert($this->descricao, $this->data, $this->distancia, $this->peso, $this->valor,
            $this->entrega, $this->validade, ((!$this->orcamentoVenda) ? 0 : $this->orcamentoVenda->getId()),
            ((!$this->representacao) ? 0 : $this->representacao->getId()), $this->tipoCaminhao->getId(),
            $this->destino->getId(), $this->autor->getId()
        );
    }

    public function update(): int
    {
        if ($this->id <= 0 || strlen($this->descricao) <= 0 || strlen($this->data) <= 0 ||
            $this->distancia <= 0 || $this->valor <= 0 || $this->peso <= 0 || strlen($this->validade) <= 0 ||
            $this->destino == null || $this->tipoCaminhao == null || $this->autor == null
        ) return -5;

        return OrcamentoFreteDAO::update($this->id, $this->descricao, $this->data, $this->distancia, $this->peso,
            $this->valor, $this->entrega, $this->validade, ((!$this->orcamentoVenda) ? 0 : $this->orcamentoVenda->getId()),
            ((!$this->representacao) ? 0 : $this->representacao->getId()), $this->tipoCaminhao->getId(),
            $this->destino->getId(), $this->autor->getId()
        );
    }

    public function delete(): int
    {
        return $this->id > 0 ? OrcamentoFreteDAO::delete($this->id) : -5;
    }

    public function jsonSerialize()
    {
        $this->orcamentoVenda = $this->orcamentoVenda->jsonSerialize();
        $this->representacao = $this->representacao->jsonSerialize();
        $this->tipoCaminhao = $this->tipoCaminhao->jsonSerialize();
        $this->destino = $this->destino->jsonSerialize();
        $this->autor = $this->autor->jsonSerialize();

        return get_object_vars($this);
    }
}