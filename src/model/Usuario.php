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
    
    public function getId() : int
    {
        return $this->id;
    }

    public function getLogin() : string
    {
        return $this->login;
    }

    public function getSenha() : string
    {
        return $this->senha;
    }

    public function getAtivo() : bool
    {
        return $this->ativo;
    }

    public function getFuncionario() : Funcionario
    {
        return $this->funcionario;
    }

    public function getNivel() : Nivel
    {
        return $this->nivel;
    }

    public function jsonSerialize() 
    {
        $this->funcionario = $this->funcionario->jsonSerialize();
        $this->nivel = $this->nivel->jsonSerialize();
        return get_object_vars($this);
    }

    public static function autenticar(mysqli $conn, string $login, string $senha)
    {
        return strlen(trim($login)) > 0 && strlen(trim($senha)) > 0 ? UsuarioDAO::autenticar($conn, $login, $senha) : null;
    }
    
    public static function getById(mysqli $conn, int $id)
    {
        return $id > 0 ? UsuarioDAO::getById($conn, $id) : null;
    }
    
    public static function getAll(mysqli $conn) : array
    {
        return UsuarioDAO::getAll($conn);
    }
    
    public static function getByKey(mysqli $conn, string $key) : array
    {
        return strlen(trim($key)) > 0 ? UsuarioDAO::getByKey($conn, $key) : array();
    }
    
    public static function getByAdm(mysqli $conn, string $adm) : array
    {
        return strlen(trim($adm)) > 0 ? UsuarioDAO::getByAdm($conn, $adm) : array();
    }
    
    public static function getByKeyAdm(mysqli $conn, string $key, string $adm) : array
    {
        return strlen(trim($key)) > 0 && strlen(trim($adm)) > 0 ? UsuarioDAO::getByKeyAdm($conn, $key, $adm) : array();
    }
    
    public static function verificarLogin(mysqli $conn, string $login) : bool
    {
        return strlen(trim($login)) > 0 && UsuarioDAO::loginCount($conn, $login) > 0;
    }
    
    public static function isLastAdmin(mysqli $conn) : bool
    {
        return UsuarioDAO::adminCount($conn) == 1;
    }
    
    public function insert(mysqli $conn) : int
    {
        if ($this->id != 0 || strlen(trim($this->login)) <= 0 || strlen(trim($this->senha)) < 6 || $this->funcionario == null || $this->nivel == null) { return -5; }
        
        return UsuarioDAO::insert($conn, $this->login, $this->senha, $this->ativo, $this->funcionario->getId(), $this->nivel->getId());
    }
    
    public function update(mysqli $conn) : int
    {
        if ($this->id <= 0 || strlen(trim($this->login)) <= 0 || strlen(trim($this->senha)) < 6 || $this->funcionario == null || $this->nivel == null) { return -5; }
        
        return UsuarioDAO::update($conn, $this->id, $this->login, $this->senha, $this->ativo, $this->funcionario->getId(), $this->nivel->getId());
    }
    
    public static function delete(mysqli $conn, int $id) : int
    {
        return $id > 0 ? UsuarioDAO::delete($conn, $id) : -5;
    }
}
