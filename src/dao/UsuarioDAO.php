<?php namespace scr\dao;

use mysqli;
use scr\util\Banco;
use scr\model\Estado;
use scr\model\Cidade;
use scr\model\Endereco;
use scr\model\Contato;
use scr\model\PessoaFisica;
use scr\model\Funcionario;
use scr\model\Nivel;
use scr\model\Usuario;

class UsuarioDAO 
{
    public static function insert(string $login, string $senha, bool $ativo, int $funcionario, int $nivel) : int
    {
        if (!Banco::getInstance()->getConnection()) return -10;

        $sql = "
            insert into usuario(usu_login,usu_senha,usu_ativo,fun_id,niv_id) 
            values(?,?,?,?,?);
        ";
        $statement = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$statement) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }
        
        $statement->bind_param('ssiii', $login, $senha, $ativo, $funcionario, $nivel);
        $statement->execute();

        return $statement->insert_id;
    }
    
    public static function update(int $id, string $login, string $senha, bool $ativo, int $funcionario, int $nivel) : int
    {
        if (!Banco::getInstance()->getConnection()) return -10;

        $sql = "
            update usuario 
            set usu_login = ?,usu_senha = ?,usu_ativo = ?,fun_id = ?,niv_id = ? 
            where usu_id = ?;
        ";
        $statement = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$statement) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }
        
        $statement->bind_param('ssiiii', $login, $senha, $ativo, $funcionario, $nivel, $id);
        $statement->execute();

        return $statement->affected_rows;
    }
    
    public static function delete(int $id) : int
    {
        if (!Banco::getInstance()->getConnection()) return -10;

        $sql = "
            delete 
            from usuario 
            where usu_id = ?;
        ";
        $statement = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$statement) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }
        
        $statement->bind_param('i', $id);
        $statement->execute();

        return $statement->affected_rows;
    }
    
    public static function getById(int $id): ?Usuario
    {
        if (!Banco::getInstance()->getConnection()) return null;

        $sql = "
            select e.est_id,e.est_nome,e.est_sigla,
                   c.cid_id,c.cid_nome,
                   en.end_id,en.end_rua,en.end_numero,en.end_bairro,en.end_complemento,en.end_cep,
                   ct.ctt_id,ct.ctt_telefone,ct.ctt_celular,ct.ctt_email,
                   pf.pf_id,pf.pf_nome,pf.pf_rg,pf.pf_cpf,pf.pf_nascimento,
                   f.fun_id,f.fun_tipo,f.fun_admissao,f.fun_demissao,
                   n.niv_id,n.niv_descricao,
                   u.usu_id,u.usu_login,u.usu_senha,u.usu_ativo 
            from usuario u 
            inner join nivel n on n.niv_id = u.niv_id
            inner join funcionario f on f.fun_id = u.fun_id
            inner join pessoa_fisica pf on pf.pf_id = f.pf_id
            inner join contato ct on ct.ctt_id = pf.ctt_id
            inner join endereco en on en.end_id = ct.end_id
            inner join cidade c on c.cid_id = en.cid_id
            inner join estado e on e.est_id = c.est_id
            where u.usu_id = ?;
        ";
        $st = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$st) {
            echo Banco::getInstance()->getConnection()->error;
            return null;
        }
        
        $st->bind_param('i', $id);
        $st->execute();
        
        if (!($result = $st->get_result()) || $result->num_rows == 0) {
            echo $st->error;
            return null;
        }
        $row = $result->fetch_assoc();

        $cl = new Usuario(
            $row['usu_id'], $row['usu_login'], $row['usu_senha'], $row['usu_ativo'],
            new Funcionario(
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
            ),
            new Nivel(
                $row['niv_id'], $row['niv_descricao']
            )
        );
        
        return $cl;
    }
    
    public static function autenticar(string $login, string $senha): ?Usuario
    {
        if (!Banco::getInstance()->getConnection()) return null;

        $sql = "
            select e.est_id,e.est_nome,e.est_sigla,
                   c.cid_id,c.cid_nome,
                   en.end_id,en.end_rua,en.end_numero,en.end_bairro,en.end_complemento,en.end_cep,
                   ct.ctt_id,ct.ctt_telefone,ct.ctt_celular,ct.ctt_email,
                   pf.pf_id,pf.pf_nome,pf.pf_rg,pf.pf_cpf,pf.pf_nascimento,
                   f.fun_id,f.fun_tipo,f.fun_admissao,f.fun_demissao,
                   n.niv_id,n.niv_descricao,
                   u.usu_id,u.usu_login,u.usu_senha,u.usu_ativo
            from usuario u 
            inner join nivel n on n.niv_id = u.niv_id
            inner join funcionario f on f.fun_id = u.fun_id
            inner join pessoa_fisica pf on pf.pf_id = f.pf_id
            inner join contato ct on ct.ctt_id = pf.ctt_id
            inner join endereco en on en.end_id = ct.end_id
            inner join cidade c on c.cid_id = en.cid_id
            inner join estado e on e.est_id = c.est_id
            where u.usu_login = ? 
            and u.usu_senha = ? 
            and u.usu_ativo = true;
        ";
        $st = Banco::getInstance()->getConnection()->prepare($sql);
        if ($st === null) {
            echo Banco::getInstance()->getConnection()->error;
            return null;
        }
        
        $st->bind_param('ss', $login, $senha);
        $st->execute();

        if (!($result = $st->get_result()) || $result->num_rows == 0) {
            echo $st->error;
            return null;
        }
        $row = $result->fetch_assoc();

        $u = new Usuario(
            $row['usu_id'], $row['usu_login'], $row['usu_senha'], $row['usu_ativo'],
            new Funcionario(
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
            ),
            new Nivel(
                $row['niv_id'], $row['niv_descricao']
            )
        );
        
        return $u;
    }
    
    public static function getAll() : array
    {
        if (!Banco::getInstance()->getConnection()) return array();

        $sql = "
            select e.est_id,e.est_nome,e.est_sigla,
                   c.cid_id,c.cid_nome,
                   en.end_id,en.end_rua,en.end_numero,en.end_bairro,en.end_complemento,en.end_cep,
                   ct.ctt_id,ct.ctt_telefone,ct.ctt_celular,ct.ctt_email,
                   pf.pf_id,pf.pf_nome,pf.pf_rg,pf.pf_cpf,pf.pf_nascimento,
                   f.fun_id,f.fun_tipo,f.fun_admissao,f.fun_demissao,
                   n.niv_id,n.niv_descricao,
                   u.usu_id,u.usu_login,u.usu_senha,u.usu_ativo 
            from usuario u 
            inner join nivel n on n.niv_id = u.niv_id
            inner join funcionario f on f.fun_id = u.fun_id
            inner join pessoa_fisica pf on pf.pf_id = f.pf_id
            inner join contato ct on ct.ctt_id = pf.ctt_id
            inner join endereco en on en.end_id = ct.end_id
            inner join cidade c on c.cid_id = en.cid_id
            inner join estado e on e.est_id = c.est_id;
        ";
        $st = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$st) {
            echo Banco::getInstance()->getConnection()->error;
            return array();
        }
        
        $st->execute();

        if (!($result = $st->get_result()) || $result->num_rows == 0) {
            echo $st->error;
            return array();
        }

        $usuarios = array();
        for ($i = 0; $i < $result->num_rows; $i++) {
            $row = $result->fetch_assoc();
            $usuarios[] = new Usuario(
                $row['usu_id'], $row['usu_login'], $row['usu_senha'], $row['usu_ativo'],
                new Funcionario(
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
                ),
                new Nivel(
                    $row['niv_id'], $row['niv_descricao']
                )
            );
        }
        
        return $usuarios;
    }
    
    public static function getByKey(string $key) : array
    {
        if (!Banco::getInstance()->getConnection()) return array();

        $sql = "
            select e.est_id,e.est_nome,e.est_sigla,
                   c.cid_id,c.cid_nome,
                   en.end_id,en.end_rua,en.end_numero,en.end_bairro,en.end_complemento,en.end_cep,
                   ct.ctt_id,ct.ctt_telefone,ct.ctt_celular,ct.ctt_email,
                   pf.pf_id,pf.pf_nome,pf.pf_rg,pf.pf_cpf,pf.pf_nascimento,
                   f.fun_id,f.fun_tipo,f.fun_admissao,f.fun_demissao,
                   n.niv_id,n.niv_descricao,
                   u.usu_id,u.usu_login,u.usu_senha,u.usu_ativo 
            from usuario u 
            inner join nivel n on n.niv_id = u.niv_id
            inner join funcionario f on f.fun_id = u.fun_id
            inner join pessoa_fisica pf on pf.pf_id = f.pf_id
            inner join contato ct on ct.ctt_id = pf.ctt_id
            inner join endereco en on en.end_id = ct.end_id
            inner join cidade c on c.cid_id = en.cid_id
            inner join estado e on e.est_id = c.est_id
            where u.usu_login like ? 
            or pf.pf_nome like ? 
            or ct.ctt_id like ?;
        ";
        $st = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$st) {
            echo Banco::getInstance()->getConnection()->error;
            return array();
        }
        
        $pkey = '%'.$key.'%';
        $st->bind_param('sss', $pkey, $pkey, $pkey);
        $st->execute();
        
        if (!($result = $st->get_result()) || $result->num_rows == 0) {
            echo $st->error;
            return array();
        }

        $usuarios = array();
        for ($i = 0; $i < $result->num_rows; $i++) {
            $row = $result->fetch_assoc();
            $usuarios[] = new Usuario(
                $row['usu_id'], $row['usu_login'], $row['usu_senha'], $row['usu_ativo'],
                new Funcionario(
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
                ),
                new Nivel(
                    $row['niv_id'], $row['niv_descricao']
                )
            );
        }
        
        return $usuarios;
    }
    
    public static function getByAdm(string $adm) : array
    {
        if (!Banco::getInstance()->getConnection()) return array();

        $sql = "
            select e.est_id,e.est_nome,e.est_sigla,
                   c.cid_id,c.cid_nome,
                   en.end_id,en.end_rua,en.end_numero,en.end_bairro,en.end_complemento,en.end_cep,
                   ct.ctt_id,ct.ctt_telefone,ct.ctt_celular,ct.ctt_email,
                   pf.pf_id,pf.pf_nome,pf.pf_rg,pf.pf_cpf,pf.pf_nascimento,
                   f.fun_id,f.fun_tipo,f.fun_admissao,f.fun_demissao,
                   n.niv_id,n.niv_descricao,
                   u.usu_id,u.usu_login,u.usu_senha,u.usu_ativo
            from usuario u 
            inner join nivel n on n.niv_id = u.niv_id
            inner join funcionario f on f.fun_id = u.fun_id
            inner join pessoa_fisica pf on pf.pf_id = f.pf_id
            inner join contato ct on ct.ctt_id = pf.ctt_id
            inner join endereco en on en.end_id = ct.end_id
            inner join cidade c on c.cid_id = en.cid_id
            inner join estado e on e.est_id = c.est_id
            where  date(f.fun_admissao) = date(?);
        ";
        $st = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$st) {
            echo Banco::getInstance()->getConnection()->error;
            return array();
        }
        
        $st->bind_param('s', $adm);
        $st->execute();

        if (!($result = $st->get_result()) || $result->num_rows == 0) {
            echo $st->error;
            return array();
        }

        $usuarios = array();
        for ($i = 0; $i < $result->num_rows; $i++) {
            $row = $result->fetch_assoc();
            $usuarios[] = new Usuario(
                $row['usu_id'], $row['usu_login'], $row['usu_senha'], $row['usu_ativo'],
                new Funcionario(
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
                ),
                new Nivel(
                    $row['niv_id'], $row['niv_descricao']
                )
            );
        }
        
        return $usuarios;
    }
    
    public static function getByKeyAdm(string $key, string $adm) : array
    {
        if (!Banco::getInstance()->getConnection()) return array();

        $sql = "
            select e.est_id,e.est_nome,e.est_sigla,
                   c.cid_id,c.cid_nome,
                   en.end_id,en.end_rua,en.end_numero,en.end_bairro,en.end_complemento,en.end_cep,
                   ct.ctt_id,ct.ctt_telefone,ct.ctt_celular,ct.ctt_email,
                   pf.pf_id,pf.pf_nome,pf.pf_rg,pf.pf_cpf,pf.pf_nascimento,
                   f.fun_id,f.fun_tipo,f.fun_admissao,f.fun_demissao,
                   n.niv_id,n.niv_descricao,
                   u.usu_id,u.usu_login,u.usu_senha,u.usu_ativo 
            from usuario u 
            inner join nivel n on n.niv_id = u.niv_id
            inner join funcionario f on f.fun_id = u.fun_id
            inner join pessoa_fisica pf on pf.pf_id = f.pf_id
            inner join contato ct on ct.ctt_id = pf.ctt_id
            inner join endereco en on en.end_id = ct.end_id
            inner join cidade c on c.cid_id = en.cid_id
            inner join estado e on e.est_id = c.est_id
            where (u.usu_login like ? or pf.pf_nome like ? or ct.ctt_id like ?) 
            and date(f.fun_admissao) = date(?);
        ";
        $st = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$st) {
            echo Banco::getInstance()->getConnection()->error;
            return array();
        }
        
        $pkey = '%'.$key.'%';
        $st->bind_param('ssss', $pkey, $pkey, $pkey, $adm);
        $st->execute();

        if (!($result = $st->get_result()) || $result->num_rows == 0) {
            echo $st->error;
            return array();
        }

        $usuarios = array();
        for ($i = 0; $i < $result->num_rows; $i++) {
            $row = $result->fetch_assoc();
            $usuarios[] = new Usuario(
                $row['usu_id'], $row['usu_login'], $row['usu_senha'], $row['usu_ativo'],
                new Funcionario(
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
                ),
                new Nivel(
                    $row['niv_id'], $row['niv_descricao']
                )
            );
        }
        
        return $usuarios;
    }
    
    public static function adminCount() : int
    {
        if (!Banco::getInstance()->getConnection()) return -10;

        $sql = "
            select count(usuario.usu_id) as admins 
            from usuario 
            inner join funcionario 
            on usuario.fun_id = funcionario.fun_id
            where usuario.niv_id = 1 
            and funcionario.fun_demissao is null;
        ";
        $statement = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$statement) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }
        
        $statement->execute();
        
        $admins = 0;
        $statement->bind_result($admins);
        
        if (!$statement->fetch()) {
            echo $statement->error;
            return -10;
        }
        
        return $admins;
    }
    
    public static function loginCount(string $login) : int
    {
        if (!Banco::getInstance()->getConnection()) return -10;

        $sql = "
            select count(usu_id) as logins 
            from usuario 
            where usu_login = ?;
        ";
        
        $statement = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$statement) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }
        
        $statement->bind_param('s', $login);
        $statement->execute();
        
        $logins = 0;
        $statement->bind_result($logins);
        
        if (!$statement->fetch()) {
            echo $statement->error;
            return -10;
        }
        
        return $logins;
    }
}
