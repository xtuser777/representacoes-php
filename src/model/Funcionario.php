<?php

namespace scr\model;

use mysqli_result;
use scr\model\PessoaFisica;
use scr\dao\FuncionarioDAO;
use scr\util\Banco;

class Funcionario 
{
    private $id;
    private $tipo;
    private $admissao;
    private $demissao;
    private $pessoa;
    
    public function __construct(int $id, int $tipo, string $admissao, string $demissao, PessoaFisica $pessoa) 
    {
        $this->id = $id;
        $this->tipo = $tipo;
        $this->admissao = $admissao;
        $this->demissao = $demissao;
        $this->pessoa = $pessoa;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTipo(): int
    {
        return $this->tipo;
    }

    public function getAdmissao() : string
    {
        return $this->admissao;
    }

    public function getDemissao() : string
    {
        return $this->demissao;
    }

    public function getPessoa() : PessoaFisica
    {
        return $this->pessoa;
    }
    
    public static function getById(int $id) : ?Funcionario
    {
        return $id > 0 ? FuncionarioDAO::getById($id) : null;
    }

    public static function getVendedores(): array
    {
        $sql = "
            select e.est_id,e.est_nome,e.est_sigla,
                   c.cid_id,c.cid_nome,
                   en.end_id,en.end_rua,en.end_numero,en.end_bairro,en.end_complemento,en.end_cep,
                   ct.ctt_id,ct.ctt_telefone,ct.ctt_celular,ct.ctt_email,
                   pf.pf_id,pf.pf_nome,pf.pf_rg,pf.pf_cpf,pf.pf_nascimento,
                   f.fun_id,f.fun_tipo,f.fun_admissao,f.fun_demissao
            from funcionario f 
            inner join pessoa_fisica pf on pf.pf_id = f.pf_id
            inner join contato ct on ct.ctt_id = pf.ctt_id
            inner join endereco en on en.end_id = ct.end_id
            inner join cidade c on c.cid_id = en.cid_id
            inner join estado e on e.est_id = c.est_id
            where f.fun_tipo = 2;
        ";
        /** @var mysqli_result $result */
        $result = Banco::getInstance()->getConnection()->query($sql);
        if (!$result || $result->num_rows <= 0) {

        }
        $vendedores = [];
        while ($row = $result->fetch_assoc()) {
            $vendedores[] = new Funcionario(
                $row['fun_id'], $row['fun_tipo'], $row['fun_admissao'], $row['fun_demissao'] != null ? $row['fun_demissao'] : "",
                new PessoaFisica(
                    $row['pf_id'], $row['pf_nome'], $row['pf_rg'], $row['pf_cpf'], $row['pf_nascimento'],
                    new Contato(
                        $row['ctt_id'], $row['ctt_telefone'], $row['ctt_celular'], $row['ctt_email'],
                        new Endereco(
                            $row['end_id'], $row['end_rua'], $row['end_numero'], $row['end_bairro'], $row['end_complemento'], $row['end_cep'],
                            new Cidade(
                                $row['cid_id'], $row['cid_nome'],
                                new Estado(
                                    $row['est_id'], $row['est_nome'], $row['est_sigla']
                                )
                            )
                        )
                    )
                )
            );
        }

        return $vendedores;
    }
    
    public function insert() : int
    {
        if ($this->id != 0 || $this->tipo <= 0 || $this->admissao == null || $this->pessoa == null) return -5;
        
        return FuncionarioDAO::insert($this->tipo, $this->admissao, $this->demissao, $this->pessoa->getId());
    }
    
    public function update() : int
    {
        if ($this->id <= 0 || $this->tipo <= 0 || $this->admissao == null || $this->pessoa == null) return -5;
        
        return FuncionarioDAO::update($this->id, $this->tipo, $this->admissao, $this->demissao, $this->pessoa->getId());
    }
    
    public static function delete(int $id) : int
    {
        return $id > 0 ? FuncionarioDAO::delete($id) : -5;
    }
    
    public static function desativar(int $id) : int
    {
        return $id > 0 ? FuncionarioDAO::desativar($id) : -5;
    }
    
    public static function reativar(int $id) : int
    {
        return $id > 0 ? FuncionarioDAO::reativar($id) : -5;
    }

    public function jsonSerialize() 
    {
        $this->pessoa = $this->pessoa->jsonSerialize();
        return get_object_vars($this);
    }
}
