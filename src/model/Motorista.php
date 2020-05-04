<?php namespace scr\model;


class Motorista
{
    private $id;
    private $cadastro;
    private $pessoa;
    private $dadosBancarios;

    public function __construct(int $id, string $cadastro, PessoaFisica $pessoa, DadosBancarios $dadosBancarios)
    {
        $this->id = $id;
        $this->cadastro = $cadastro;
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

    public function getPessoa(): PessoaFisica
    {
        return $this->pessoa;
    }

    public function getDadosBancarios(): DadosBancarios
    {
        return $this->dadosBancarios;
    }

    public function jsonSerialize()
    {
        $this->pessoa = $this->pessoa->jsonSerialize();
        $this->dadosBancarios = $this->dadosBancarios->jsonSerialize();

        return get_object_vars($this);
    }
}