<?php namespace scr\model;

use scr\dao\OrcamentoFreteDAO;
use scr\dao\OrcamentoVendaDAO;

class OrcamentoVenda
{
    private $id;
    private $descricao;
    private $data;
    private $nomeCliente;
    private $documentoCliente;
    private $telefoneCliente;
    private $celularCliente;
    private $emailCliente;
    private $peso;
    private $valor;
    private $validade;
    private $vendedor;
    private $cliente;
    private $destino;
    private $tipoCaminhao;
    private $autor;

    public function __construct(int $id, string $descricao, string $data, string $nomeCliente, string $documentoCliente, string $telefoneCliente, string $celularCliente, string $emailCliente, float $peso, float $valor, string $validade, ?Funcionario $vendedor, ?Cliente $cliente, Cidade $destino, TipoCaminhao $tipoCaminhao, Usuario $autor)
    {
        $this->id = $id;
        $this->descricao = $descricao;
        $this->data = $data;
        $this->nomeCliente = $nomeCliente;
        $this->documentoCliente = $documentoCliente;
        $this->telefoneCliente = $telefoneCliente;
        $this->celularCliente = $celularCliente;
        $this->emailCliente = $emailCliente;
        $this->peso = $peso;
        $this->valor = $valor;
        $this->validade = $validade;
        $this->vendedor = $vendedor;
        $this->cliente = $cliente;
        $this->destino = $destino;
        $this->tipoCaminhao = $tipoCaminhao;
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

    public function getNomeCliente(): string
    {
        return $this->nomeCliente;
    }

    public function getDocumentoCliente(): string
    {
        return $this->documentoCliente;
    }

    public function getTelefoneCliente(): string
    {
        return $this->telefoneCliente;
    }

    public function getCelularCliente(): string
    {
        return $this->celularCliente;
    }

    public function getEmailCliente(): string
    {
        return $this->emailCliente;
    }

    public function getPeso(): float
    {
        return $this->peso;
    }

    public function getValor(): float
    {
        return $this->valor;
    }

    public function getValidade(): string
    {
        return $this->validade;
    }

    public function getVendedor(): ?Funcionario
    {
        return $this->vendedor;
    }

    public function getCliente(): ?Cliente
    {
        return $this->cliente;
    }

    public function getDestino(): Cidade
    {
        return $this->destino;
    }

    public function getTipoCaminhao(): TipoCaminhao
    {
        return $this->tipoCaminhao;
    }

    public function getAutor(): Usuario
    {
        return $this->autor;
    }

    public static function findById(int $id): ?OrcamentoVenda
    {
        return $id > 0 ? OrcamentoVendaDAO::selectId($id) : null;
    }

    public static function findByKey(string $key): array
    {
        return strlen(trim($key)) > 0 ? OrcamentoVendaDAO::selectKey($key) : array();
    }

    public static function findByDate(string $date): array
    {
        return strlen(trim($date)) > 0 ? OrcamentoVendaDAO::selectDate($date) : array();
    }

    public static function findByKeyDate(string $key, string $date): array
    {
        return strlen(trim($key)) > 0 && strlen(trim($date)) > 0 ? OrcamentoVendaDAO::selectKeyDate($key, $date) : array();
    }

    public static function findAll(): array
    {
        return OrcamentoVendaDAO::select();
    }

    public function save(): int
    {
        if ($this->id != 0 || strlen($this->descricao) <= 0 || strlen($this->data) <= 0 ||
            strlen($this->nomeCliente) <= 0 || strlen($this->documentoCliente) <= 0 ||
            strlen($this->telefoneCliente) <= 0 || strlen($this->celularCliente) <= 0 ||
            strlen($this->emailCliente) <= 0 || $this->valor <= 0 || $this->peso <= 0 ||
            strlen($this->validade) <= 0 || $this->destino == null || $this->tipoCaminhao == null ||
            $this->autor == null
        ) return -5;

        return OrcamentoVendaDAO::insert($this->descricao, $this->data, $this->nomeCliente, $this->documentoCliente,
            $this->telefoneCliente, $this->celularCliente, $this->emailCliente, $this->peso, $this->valor,
            $this->validade, ((!$this->vendedor) ? 0 : $this->vendedor->getId()),
            ((!$this->cliente) ? 0 : $this->cliente->getId()), $this->destino->getId(),
            $this->tipoCaminhao->getId(), $this->autor->getId()
        );
    }

    public function update(): int
    {
        if ($this->id <= 0 || strlen($this->descricao) <= 0 || strlen($this->data) <= 0 ||
            strlen($this->nomeCliente) <= 0 || strlen($this->documentoCliente) <= 0 ||
            strlen($this->telefoneCliente) <= 0 || strlen($this->celularCliente) <= 0 ||
            strlen($this->emailCliente) <= 0 || $this->valor <= 0 || $this->peso <= 0 ||
            strlen($this->validade) <= 0 || $this->destino == null || $this->tipoCaminhao == null ||
            $this->autor == null
        ) return -5;

        return OrcamentoVendaDAO::update($this->id, $this->descricao, $this->data, $this->nomeCliente,
            $this->documentoCliente, $this->telefoneCliente, $this->celularCliente, $this->emailCliente, $this->peso,
            $this->valor, $this->validade, ((!$this->vendedor) ? 0 : $this->vendedor->getId()),
            ((!$this->cliente) ? 0 : $this->cliente->getId()), $this->destino->getId(),
            $this->tipoCaminhao->getId(), $this->autor->getId()
        );
    }

    public function delete(): int
    {
        return $this->id > 0 ? OrcamentoVendaDAO::delete($this->id) : -5;
    }

    public function jsonSerialize()
    {
        $this->vendedor = $this->vendedor->jsonSerialize();
        $this->cliente = $this->cliente->jsonSerialize();
        $this->destino = $this->destino->jsonSerialize();
        $this->tipoCaminhao = $this->tipoCaminhao->jsonSerialize();
        $this->autor = $this->autor->jsonSerialize();

        return get_object_vars($this);
    }
}