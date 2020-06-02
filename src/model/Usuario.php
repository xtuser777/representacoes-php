<?php namespace scr\model;

use mysqli;
use scr\model\Funcionario;
use scr\model\Nivel;
use scr\dao\UsuarioDAO;

class Usuario 
{
    private $id;
    private $login;
    private $senha;
    private $ativo;
    private $funcionario;
    private $nivel;
    
    public function __construct(int $id, string $login, string $senha, bool $ativo, Funcionario $funcionario, Nivel $nivel)
    {
        $this->id = $id;
        $this->login = $login;
        $this->senha = $senha;
        $this->ativo = $ativo;
        $this->funcionario = $funcionario;
        $this->nivel = $nivel;
    }
    
    public function getId(): int
    {
        return $this->id;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function getSenha(): string
    {
        return $this->senha;
    }

    public function getAtivo(): bool
    {
        return $this->ativo;
    }

    public function getFuncionario(): Funcionario
    {
        return $this->funcionario;
    }

    public function getNivel(): Nivel
    {
        return $this->nivel;
    }

    public function jsonSerialize() 
    {
        $this->funcionario = $this->funcionario->jsonSerialize();
        $this->nivel = $this->nivel->jsonSerialize();
        return get_object_vars($this);
    }

    public static function autenticar(string $login, string $senha): ?Usuario
    {
        return strlen(trim($login)) > 0 && strlen(trim($senha)) > 0 ? UsuarioDAO::autenticar($login, $senha) : null;
    }
    
    public static function getById(int $id)
    {
        return $id > 0 ? UsuarioDAO::getById($id) : null;
    }
    
    public static function getAll() : array
    {
        return UsuarioDAO::getAll();
    }
    
    public static function getByKey(string $key) : array
    {
        return strlen(trim($key)) > 0 ? UsuarioDAO::getByKey($key) : array();
    }
    
    public static function getByAdm(string $adm) : array
    {
        return strlen(trim($adm)) > 0 ? UsuarioDAO::getByAdm($adm) : array();
    }
    
    public static function getByKeyAdm(string $key, string $adm) : array
    {
        return strlen(trim($key)) > 0 && strlen(trim($adm)) > 0 ? UsuarioDAO::getByKeyAdm($key, $adm) : array();
    }
    
    public static function verificarLogin(string $login) : bool
    {
        return strlen(trim($login)) > 0 && UsuarioDAO::loginCount($login) > 0;
    }
    
    public static function isLastAdmin() : bool
    {
        return UsuarioDAO::adminCount() == 1;
    }
    
    public function insert() : int
    {
        if ($this->id != 0 || $this->funcionario == null || $this->nivel == null) return -5;
        
        return UsuarioDAO::insert($this->login, $this->senha, $this->ativo, $this->funcionario->getId(), $this->nivel->getId());
    }
    
    public function update() : int
    {
        if ($this->id <= 0 || $this->funcionario == null || $this->nivel == null) return -5;
        
        return UsuarioDAO::update($this->id, $this->login, $this->senha, $this->ativo, $this->funcionario->getId(), $this->nivel->getId());
    }
    
    public static function delete(int $id) : int
    {
        return $id > 0 ? UsuarioDAO::delete($id) : -5;
    }
}
