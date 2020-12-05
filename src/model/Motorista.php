<?php


namespace scr\model;


use scr\dao\MotoristaDAO;

class Motorista
{
    private $id;
    private $cadastro;
    private $cnh;
    private $pessoa;
    private $dadosBancarios;

    public function __construct(int $id, string $cadastro, string $cnh, PessoaFisica $pessoa, DadosBancarios $dadosBancarios)
    {
        $this->id = $id;
        $this->cadastro = $cadastro;
        $this->cnh = $cnh;
        $this->pessoa = $pessoa;
        $this->dadosBancarios = $dadosBancarios;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCadastro(): string
    {
        return $this->cadastro;
    }

    /**
     * @return string
     */
    public function getCnh(): string
    {
        return $this->cnh;
    }

    public function getPessoa(): PessoaFisica
    {
        return $this->pessoa;
    }

    public function getDadosBancarios(): DadosBancarios
    {
        return $this->dadosBancarios;
    }

    public static function findById(int $id): ?Motorista
    {
        return $id > 0 ? MotoristaDAO::selectId($id) : null;
    }

    public static function findByKey(string $key): array
    {
        return strlen(trim($key)) > 0 ? MotoristaDAO::selectKey($key) : array();
    }

    public static function findByCad(string $cad): array
    {
        return strlen(trim($cad)) > 0 ? MotoristaDAO::selectCad($cad) : array();
    }

    public static function findByKeyCad(string $key, string $cad): array
    {
        return strlen(trim($key)) > 0 && strlen(trim($cad)) > 0 ? MotoristaDAO::selectKeyCad($key, $cad) : array();
    }

    public static function findAll(): array
    {
        return MotoristaDAO::select();
    }

    public function save(): int
    {
        if ($this->id != 0 || strlen($this->cadastro) <= 0 || $this->pessoa == null || $this->dadosBancarios == null) return -5;

        return MotoristaDAO::insert($this->cadastro, $this->cnh, $this->pessoa->getId(), $this->dadosBancarios->getId());
    }

    public function update(): int
    {
        if ($this->id <= 0 )
            return -5;

        return MotoristaDAO::update($this->id, $this->cnh);
    }

    public function delete(): int
    {
        return $this->id > 0 ? MotoristaDAO::delete($this->id) : -5;
    }

    public function jsonSerialize()
    {
        $this->pessoa = $this->pessoa->jsonSerialize();
        $this->dadosBancarios = $this->dadosBancarios->jsonSerialize();

        return get_object_vars($this);
    }
}