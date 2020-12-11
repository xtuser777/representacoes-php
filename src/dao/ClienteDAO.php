<?php namespace scr\dao;

use mysqli_result;
use scr\model\Estado;
use scr\model\Cidade;
use scr\model\Endereco;
use scr\model\Contato;
use scr\model\PessoaFisica;
use scr\model\PessoaJuridica;
use scr\model\Cliente;
use scr\util\Banco;
use mysqli;

class ClienteDAO
{
    public static function insert(string $cadastro, int $tipo, int $pessoaFisica, int $pessoaJuridica): int
    {
        if (!Banco::getInstance()->getConnection()) return -10;

        $sql = '
            insert into cliente (cli_cadastro, cli_tipo) 
            values (?,?);
        ';
        $statement = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$statement) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }

        $statement->bind_param('si', $cadastro, $tipo);
        $statement->execute();

        $id = $statement->insert_id;

        return self::insert_pessoa($id, $tipo, $pessoaFisica, $pessoaJuridica);
    }

    private static function insert_pessoa(int $id, int $tipo, int $pessoaFisica, int $pessoaJuridica): int
    {
        if ($tipo == 1) {
            $sql = '
                insert into cliente_pessoa_fisica (cli_id, pf_id) 
                values (?,?);
            ';
        } else {
            $sql = '
                insert into cliente_pessoa_juridica (cli_id, pj_id) 
                values (?,?);
            ';
        }

        $statement = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$statement) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }

        if ($tipo == 1) $statement->bind_param('ii', $id, $pessoaFisica);
        else $statement->bind_param('ii', $id, $pessoaJuridica);
        $statement->execute();

        $res = $statement->affected_rows;

        return $res > 0 ? $id : -10;
    }

    public static function update(int $id, string $cadastro, int $tipo): int
    {
        if (!Banco::getInstance()->getConnection()) return -10;

        $sql = '
            update cliente 
            set cli_cadastro = ?, cli_tipo = ?
            where cli_id = ?;
        ';
        $statement = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$statement) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }

        $statement->bind_param('sii', $cadastro, $tipo, $id);
        $statement->execute();

        return $statement->affected_rows;
    }

    public static function delete(int $tipo, int $id): int
    {
        if (!Banco::getInstance()->getConnection()) return -10;

        $res = 0;
        if ($tipo == 1) {
            $res = self::deletePessoaFisica($id);
        } else {
            $res = self::deletePessoaJuridica($id);
        }

        if ($res <= 0) return $res;

        $sql = '
            delete 
            from cliente
            where cli_id = ?;
        ';
        $statement = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$statement) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }

        $statement->bind_param('i', $id);
        $statement->execute();

        return $statement->affected_rows;
    }

    public static function deletePessoaFisica(int $id)
    {
        $sql = '
            delete 
            from cliente_pessoa_fisica
            where cli_id = ?;
        ';
        $statement = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$statement) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }

        $statement->bind_param('i', $id);
        $statement->execute();

        return $statement->affected_rows;
    }

    public static function deletePessoaJuridica(int $id)
    {
        $sql = '
            delete 
            from cliente_pessoa_juridica
            where cli_id = ?;
        ';
        $statement = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$statement) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }

        $statement->bind_param('i', $id);
        $statement->execute();

        return $statement->affected_rows;
    }

    private static function rowToObject(array $row): Cliente
    {
        return new Cliente(
            $row['cli_id'], $row['cli_cadastro'], $row['cli_tipo'],
            $row['cli_tipo'] == 2 ? null : new PessoaFisica(
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
            ),
            $row['cli_tipo'] == 1 ? null : new PessoaJuridica(
                $row['pj_id'], $row['pj_razao_social'], $row['pj_nome_fantasia'], $row['pj_cnpj'],
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

    private static function resultToObject(mysqli_result $result): ?Cliente
    {
        if (!$result || $result->num_rows === 0)
            return null;

        return self::rowToObject($result->fetch_assoc());
    }

    private static function resultToList(mysqli_result $result): array
    {
        if (!$result || $result->num_rows === 0)
            return [];

        $clientes = [];
        while ($row = $result->fetch_assoc()) {
            $clientes[] = self::rowToObject($row);
        }

        return $clientes;
    }

    public static function getById(int $id): ?Cliente
    {
        if (!Banco::getInstance()->getConnection()) return null;

        $sql = '
            select e.est_id, e.est_nome, e.est_sigla,
                   c.cid_id, c.cid_nome,
                   en.end_id, en.end_rua, en.end_numero, en.end_bairro, en.end_complemento, en.end_cep,
                   ct.ctt_id, ct.ctt_telefone, ct.ctt_celular, ct.ctt_email,
                   pf.pf_id, pf.pf_nome, pf.pf_rg, pf.pf_cpf, pf.pf_nascimento,
                   pj.pj_id, pj.pj_razao_social, pj.pj_nome_fantasia, pj.pj_cnpj,
                   cl.cli_id,cl.cli_cadastro,cl.cli_tipo
            from cliente cl
            left join cliente_pessoa_fisica cpf on cl.cli_id = cpf.cli_id
            left join cliente_pessoa_juridica cpj on cl.cli_id = cpj.cli_id
            left join pessoa_fisica pf on cpf.pf_id = pf.pf_id
            left join pessoa_juridica pj on cpj.pj_id = pj.pj_id
            inner join contato ct on ct.ctt_id = pf.ctt_id or ct.ctt_id = pj.ctt_id
            inner join endereco en on ct.end_id = en.end_id
            inner join cidade c on en.cid_id = c.cid_id
            inner join estado e on c.est_id = e.est_id
            where cl.cli_id = ?;
        ';
        $statement = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$statement) {
            echo Banco::getInstance()->getConnection()->error;
            return null;
        }

        $statement->bind_param('i', $id);
        $statement->execute();

        if (!($result = $statement->get_result()) || $result->num_rows == 0) {
            echo $statement->error;
            return null;
        }
        $row = $result->fetch_assoc();

        $cl = new Cliente(
            $row['cli_id'], $row['cli_cadastro'], $row['cli_tipo'],
            $row['cli_tipo'] == 2 ? null : new PessoaFisica(
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
            ),
            $row['cli_tipo'] == 1 ? null : new PessoaJuridica(
                $row['pj_id'], $row['pj_razao_social'], $row['pj_nome_fantasia'], $row['pj_cnpj'],
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

        return $cl;
    }

    public static function getByKey(string $key): array
    {
        if (!Banco::getInstance()->getConnection()) return array();

        $sql = '
            select e.est_id, e.est_nome, e.est_sigla,
                   c.cid_id, c.cid_nome,
                   en.end_id, en.end_rua, en.end_numero, en.end_bairro, en.end_complemento, en.end_cep,
                   ct.ctt_id, ct.ctt_telefone, ct.ctt_celular, ct.ctt_email,
                   pf.pf_id, pf.pf_nome, pf.pf_rg, pf.pf_cpf, pf.pf_nascimento,
                   pj.pj_id, pj.pj_razao_social, pj.pj_nome_fantasia, pj.pj_cnpj,
                   cl.cli_id,cl.cli_cadastro,cl.cli_tipo
            from cliente cl
            left join cliente_pessoa_fisica cpf on cl.cli_id = cpf.cli_id
            left join cliente_pessoa_juridica cpj on cl.cli_id = cpj.cli_id
            left join pessoa_fisica pf on cpf.pf_id = pf.pf_id
            left join pessoa_juridica pj on cpj.pj_id = pj.pj_id
            inner join contato ct on ct.ctt_id = pf.ctt_id or ct.ctt_id = pj.ctt_id
            inner join endereco en on ct.end_id = en.end_id
            inner join cidade c on en.cid_id = c.cid_id
            inner join estado e on c.est_id = e.est_id
            where pf.pf_nome like ? 
            or pj.pj_nome_fantasia like ? 
            or ct.ctt_email like ?;
        ';
        $statement = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$statement) {
            echo Banco::getInstance()->getConnection()->error;
            return array();
        }

        $pkey = '%'.$key.'%';
        $statement->bind_param('sss', $pkey, $pkey, $pkey);
        $statement->execute();

        if (!($result = $statement->get_result()) || $result->num_rows == 0) {
            echo $statement->error;
            return array();
        }

        $clientes = array();
        for ($i = 0; $i < $result->num_rows; $i++) {
            $row = $result->fetch_assoc();
            $clientes[] = new Cliente(
                $row['cli_id'], $row['cli_cadastro'], $row['cli_tipo'],
                $row['cli_tipo'] == 2 ? null : new PessoaFisica(
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
                ),
                $row['cli_tipo'] == 1 ? null : new PessoaJuridica(
                    $row['pj_id'], $row['pj_razao_social'], $row['pj_nome_fantasia'], $row['pj_cnpj'],
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

        return $clientes;
    }

    public static function getByCad(string $cad): array
    {
        if (!Banco::getInstance()->getConnection()) return array();

        $sql = '
            select e.est_id, e.est_nome, e.est_sigla,
                   c.cid_id, c.cid_nome,
                   en.end_id, en.end_rua, en.end_numero, en.end_bairro, en.end_complemento, en.end_cep,
                   ct.ctt_id, ct.ctt_telefone, ct.ctt_celular, ct.ctt_email,
                   pf.pf_id, pf.pf_nome, pf.pf_rg, pf.pf_cpf, pf.pf_nascimento,
                   pj.pj_id, pj.pj_razao_social, pj.pj_nome_fantasia, pj.pj_cnpj,
                   cl.cli_id,cl.cli_cadastro,cl.cli_tipo
            from cliente cl
            left join cliente_pessoa_fisica cpf on cl.cli_id = cpf.cli_id
            left join cliente_pessoa_juridica cpj on cl.cli_id = cpj.cli_id
            left join pessoa_fisica pf on cpf.pf_id = pf.pf_id
            left join pessoa_juridica pj on cpj.pj_id = pj.pj_id
            inner join contato ct on ct.ctt_id = pf.ctt_id or ct.ctt_id = pj.ctt_id
            inner join endereco en on ct.end_id = en.end_id
            inner join cidade c on en.cid_id = c.cid_id
            inner join estado e on c.est_id = e.est_id
            where cl.cli_cadastro = ?;
        ';
        $statement = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$statement) {
            echo Banco::getInstance()->getConnection()->error;
            return array();
        }

        $statement->bind_param('s', $cad);
        $statement->execute();

        if (!($result = $statement->get_result()) || $result->num_rows == 0) {
            echo $statement->error;
            return array();
        }

        $clientes = array();
        for ($i = 0; $i < $result->num_rows; $i++) {
            $row = $result->fetch_assoc();
            $clientes[] = new Cliente(
                $row['cli_id'], $row['cli_cadastro'], $row['cli_tipo'],
                $row['cli_tipo'] == 2 ? null : new PessoaFisica(
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
                ),
                $row['cli_tipo'] == 1 ? null : new PessoaJuridica(
                    $row['pj_id'], $row['pj_razao_social'], $row['pj_nome_fantasia'], $row['pj_cnpj'],
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

        return $clientes;
    }

    public static function getByKeyCad(string $key, string $cad): array
    {
        if (!Banco::getInstance()->getConnection()) return array();

        $sql = '
            select e.est_id, e.est_nome, e.est_sigla,
                   c.cid_id, c.cid_nome,
                   en.end_id, en.end_rua, en.end_numero, en.end_bairro, en.end_complemento, en.end_cep,
                   ct.ctt_id, ct.ctt_telefone, ct.ctt_celular, ct.ctt_email,
                   pf.pf_id, pf.pf_nome, pf.pf_rg, pf.pf_cpf, pf.pf_nascimento,
                   pj.pj_id, pj.pj_razao_social, pj.pj_nome_fantasia, pj.pj_cnpj,
                   cl.cli_id,cl.cli_cadastro,cl.cli_tipo
            from cliente cl
            left join cliente_pessoa_fisica cpf on cl.cli_id = cpf.cli_id
            left join cliente_pessoa_juridica cpj on cl.cli_id = cpj.cli_id
            left join pessoa_fisica pf on cpf.pf_id = pf.pf_id
            left join pessoa_juridica pj on cpj.pj_id = pj.pj_id
            inner join contato ct on ct.ctt_id = pf.ctt_id or ct.ctt_id = pj.ctt_id
            inner join endereco en on ct.end_id = en.end_id
            inner join cidade c on en.cid_id = c.cid_id
            inner join estado e on c.est_id = e.est_id
            where (pf.pf_nome like ? or pj.pj_nome_fantasia like ? or ct.ctt_email like ?)
            and cl.cli_cadastro = ?;
        ';
        $statement = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$statement) {
            echo Banco::getInstance()->getConnection()->error;
            return array();
        }

        $pkey = '%'.$key.'%';
        $statement->bind_param('ssss', $pkey, $pkey, $pkey, $cad);
        $statement->execute();

        if (!($result = $statement->get_result()) || $result->num_rows == 0) {
            echo $statement->error;
            return array();
        }

        $clientes = array();
        for ($i = 0; $i < $result->num_rows; $i++) {
            $row = $result->fetch_assoc();
            $clientes[] = new Cliente(
                $row['cli_id'], $row['cli_cadastro'], $row['cli_tipo'],
                $row['cli_tipo'] == 2 ? null : new PessoaFisica(
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
                ),
                $row['cli_tipo'] == 1 ? null : new PessoaJuridica(
                    $row['pj_id'], $row['pj_razao_social'], $row['pj_nome_fantasia'], $row['pj_cnpj'],
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

        return $clientes;
    }

    public static function getByFilterPeriodType(string $filter, string $init, string $end, int $type, string $order): array
    {
        $sql = "
            select e.est_id, e.est_nome, e.est_sigla,
                   c.cid_id, c.cid_nome,
                   en.end_id, en.end_rua, en.end_numero, en.end_bairro, en.end_complemento, en.end_cep,
                   ct.ctt_id, ct.ctt_telefone, ct.ctt_celular, ct.ctt_email,
                   pf.pf_id, pf.pf_nome, pf.pf_rg, pf.pf_cpf, pf.pf_nascimento,
                   pj.pj_id, pj.pj_razao_social, pj.pj_nome_fantasia, pj.pj_cnpj,
                   cl.cli_id,cl.cli_cadastro,cl.cli_tipo
            from cliente cl
            left join cliente_pessoa_fisica cpf on cl.cli_id = cpf.cli_id
            left join cliente_pessoa_juridica cpj on cl.cli_id = cpj.cli_id
            left join pessoa_fisica pf on cpf.pf_id = pf.pf_id
            left join pessoa_juridica pj on cpj.pj_id = pj.pj_id
            inner join contato ct on ct.ctt_id = pf.ctt_id or ct.ctt_id = pj.ctt_id
            inner join endereco en on ct.end_id = en.end_id
            inner join cidade c on en.cid_id = c.cid_id
            inner join estado e on c.est_id = e.est_id
            where (pf.pf_nome like ? or pj.pj_nome_fantasia like ? or ct.ctt_email like ?) and (cl.cli_cadastro >= ? and cl.cli_cadastro <= ?) and cl.cli_tipo = ?
            order by $order;
        ";

        if (!Banco::getInstance()->prepareStatement($sql))
            return [];

        $filtro = "%$filter%";

        if (!Banco::getInstance()->addParameters("sssssi", [ $filtro, $filtro, $filtro, $init, $end, $type ]))
            return [];

        if (!Banco::getInstance()->executeStatement())
            return [];

        return self::resultToList(Banco::getInstance()->getResult());
    }

    public static function getByFilterPeriod(string $filter, string $init, string $end, string $order): array
    {
        $sql = "
            select e.est_id, e.est_nome, e.est_sigla,
                   c.cid_id, c.cid_nome,
                   en.end_id, en.end_rua, en.end_numero, en.end_bairro, en.end_complemento, en.end_cep,
                   ct.ctt_id, ct.ctt_telefone, ct.ctt_celular, ct.ctt_email,
                   pf.pf_id, pf.pf_nome, pf.pf_rg, pf.pf_cpf, pf.pf_nascimento,
                   pj.pj_id, pj.pj_razao_social, pj.pj_nome_fantasia, pj.pj_cnpj,
                   cl.cli_id,cl.cli_cadastro,cl.cli_tipo
            from cliente cl
            left join cliente_pessoa_fisica cpf on cl.cli_id = cpf.cli_id
            left join cliente_pessoa_juridica cpj on cl.cli_id = cpj.cli_id
            left join pessoa_fisica pf on cpf.pf_id = pf.pf_id
            left join pessoa_juridica pj on cpj.pj_id = pj.pj_id
            inner join contato ct on ct.ctt_id = pf.ctt_id or ct.ctt_id = pj.ctt_id
            inner join endereco en on ct.end_id = en.end_id
            inner join cidade c on en.cid_id = c.cid_id
            inner join estado e on c.est_id = e.est_id
            where (pf.pf_nome like ? or pj.pj_nome_fantasia like ? or ct.ctt_email like ?) and (cl.cli_cadastro >= ? and cl.cli_cadastro <= ?) 
            order by $order;
        ";

        if (!Banco::getInstance()->prepareStatement($sql))
            return [];

        $filtro = "%$filter%";

        if (!Banco::getInstance()->addParameters("sssss", [ $filtro, $filtro, $filtro, $init, $end ]))
            return [];

        if (!Banco::getInstance()->executeStatement())
            return [];

        return self::resultToList(Banco::getInstance()->getResult());
    }

    public static function getByFilterType(string $filter, int $type, string $order): array
    {
        $sql = "
            select e.est_id, e.est_nome, e.est_sigla,
                   c.cid_id, c.cid_nome,
                   en.end_id, en.end_rua, en.end_numero, en.end_bairro, en.end_complemento, en.end_cep,
                   ct.ctt_id, ct.ctt_telefone, ct.ctt_celular, ct.ctt_email,
                   pf.pf_id, pf.pf_nome, pf.pf_rg, pf.pf_cpf, pf.pf_nascimento,
                   pj.pj_id, pj.pj_razao_social, pj.pj_nome_fantasia, pj.pj_cnpj,
                   cl.cli_id,cl.cli_cadastro,cl.cli_tipo
            from cliente cl
            left join cliente_pessoa_fisica cpf on cl.cli_id = cpf.cli_id
            left join cliente_pessoa_juridica cpj on cl.cli_id = cpj.cli_id
            left join pessoa_fisica pf on cpf.pf_id = pf.pf_id
            left join pessoa_juridica pj on cpj.pj_id = pj.pj_id
            inner join contato ct on ct.ctt_id = pf.ctt_id or ct.ctt_id = pj.ctt_id
            inner join endereco en on ct.end_id = en.end_id
            inner join cidade c on en.cid_id = c.cid_id
            inner join estado e on c.est_id = e.est_id
            where (pf.pf_nome like ? or pj.pj_nome_fantasia like ? or ct.ctt_email like ?) and cl.cli_tipo = ?
            order by $order;
        ";

        if (!Banco::getInstance()->prepareStatement($sql))
            return [];

        $filtro = "%$filter%";

        if (!Banco::getInstance()->addParameters("sssi", [ $filtro, $filtro, $filtro, $type ]))
            return [];

        if (!Banco::getInstance()->executeStatement())
            return [];

        return self::resultToList(Banco::getInstance()->getResult());
    }

    public static function getByFilter(string $filter, string $order): array
    {
        $sql = "
            select e.est_id, e.est_nome, e.est_sigla,
                   c.cid_id, c.cid_nome,
                   en.end_id, en.end_rua, en.end_numero, en.end_bairro, en.end_complemento, en.end_cep,
                   ct.ctt_id, ct.ctt_telefone, ct.ctt_celular, ct.ctt_email,
                   pf.pf_id, pf.pf_nome, pf.pf_rg, pf.pf_cpf, pf.pf_nascimento,
                   pj.pj_id, pj.pj_razao_social, pj.pj_nome_fantasia, pj.pj_cnpj,
                   cl.cli_id,cl.cli_cadastro,cl.cli_tipo
            from cliente cl
            left join cliente_pessoa_fisica cpf on cl.cli_id = cpf.cli_id
            left join cliente_pessoa_juridica cpj on cl.cli_id = cpj.cli_id
            left join pessoa_fisica pf on cpf.pf_id = pf.pf_id
            left join pessoa_juridica pj on cpj.pj_id = pj.pj_id
            inner join contato ct on ct.ctt_id = pf.ctt_id or ct.ctt_id = pj.ctt_id
            inner join endereco en on ct.end_id = en.end_id
            inner join cidade c on en.cid_id = c.cid_id
            inner join estado e on c.est_id = e.est_id
            where (pf.pf_nome like ? or pj.pj_nome_fantasia like ? or ct.ctt_email like ?)
            order by $order;
        ";

        if (!Banco::getInstance()->prepareStatement($sql))
            return [];

        $filtro = "%$filter%";

        if (!Banco::getInstance()->addParameters("sss", [ $filtro, $filtro, $filtro ]))
            return [];

        if (!Banco::getInstance()->executeStatement())
            return [];

        return self::resultToList(Banco::getInstance()->getResult());
    }

    public static function getByPeriodType(string $init, string $end, int $type, string $order): array
    {
        $sql = "
            select e.est_id, e.est_nome, e.est_sigla,
                   c.cid_id, c.cid_nome,
                   en.end_id, en.end_rua, en.end_numero, en.end_bairro, en.end_complemento, en.end_cep,
                   ct.ctt_id, ct.ctt_telefone, ct.ctt_celular, ct.ctt_email,
                   pf.pf_id, pf.pf_nome, pf.pf_rg, pf.pf_cpf, pf.pf_nascimento,
                   pj.pj_id, pj.pj_razao_social, pj.pj_nome_fantasia, pj.pj_cnpj,
                   cl.cli_id,cl.cli_cadastro,cl.cli_tipo
            from cliente cl
            left join cliente_pessoa_fisica cpf on cl.cli_id = cpf.cli_id
            left join cliente_pessoa_juridica cpj on cl.cli_id = cpj.cli_id
            left join pessoa_fisica pf on cpf.pf_id = pf.pf_id
            left join pessoa_juridica pj on cpj.pj_id = pj.pj_id
            inner join contato ct on ct.ctt_id = pf.ctt_id or ct.ctt_id = pj.ctt_id
            inner join endereco en on ct.end_id = en.end_id
            inner join cidade c on en.cid_id = c.cid_id
            inner join estado e on c.est_id = e.est_id
            where (cl.cli_cadastro >= ? and cl.cli_cadastro <= ?) and cl.cli_tipo = ?
            order by $order;
        ";

        if (!Banco::getInstance()->prepareStatement($sql))
            return [];

        if (!Banco::getInstance()->addParameters("ssi", [ $init, $end, $type ]))
            return [];

        if (!Banco::getInstance()->executeStatement())
            return [];

        return self::resultToList(Banco::getInstance()->getResult());
    }

    public static function getByPeriod(string $init, string $end, string $order): array
    {
        $sql = "
            select e.est_id, e.est_nome, e.est_sigla,
                   c.cid_id, c.cid_nome,
                   en.end_id, en.end_rua, en.end_numero, en.end_bairro, en.end_complemento, en.end_cep,
                   ct.ctt_id, ct.ctt_telefone, ct.ctt_celular, ct.ctt_email,
                   pf.pf_id, pf.pf_nome, pf.pf_rg, pf.pf_cpf, pf.pf_nascimento,
                   pj.pj_id, pj.pj_razao_social, pj.pj_nome_fantasia, pj.pj_cnpj,
                   cl.cli_id,cl.cli_cadastro,cl.cli_tipo
            from cliente cl
            left join cliente_pessoa_fisica cpf on cl.cli_id = cpf.cli_id
            left join cliente_pessoa_juridica cpj on cl.cli_id = cpj.cli_id
            left join pessoa_fisica pf on cpf.pf_id = pf.pf_id
            left join pessoa_juridica pj on cpj.pj_id = pj.pj_id
            inner join contato ct on ct.ctt_id = pf.ctt_id or ct.ctt_id = pj.ctt_id
            inner join endereco en on ct.end_id = en.end_id
            inner join cidade c on en.cid_id = c.cid_id
            inner join estado e on c.est_id = e.est_id
            where cl.cli_cadastro >= ? and cl.cli_cadastro <= ?
            order by $order;
        ";

        if (!Banco::getInstance()->prepareStatement($sql))
            return [];

        if (!Banco::getInstance()->addParameters("ss", [ $init, $end ]))
            return [];

        if (!Banco::getInstance()->executeStatement())
            return [];

        return self::resultToList(Banco::getInstance()->getResult());
    }

    public static function getByType(int $type, string $order): array
    {
        $sql = "
            select e.est_id, e.est_nome, e.est_sigla,
                   c.cid_id, c.cid_nome,
                   en.end_id, en.end_rua, en.end_numero, en.end_bairro, en.end_complemento, en.end_cep,
                   ct.ctt_id, ct.ctt_telefone, ct.ctt_celular, ct.ctt_email,
                   pf.pf_id, pf.pf_nome, pf.pf_rg, pf.pf_cpf, pf.pf_nascimento,
                   pj.pj_id, pj.pj_razao_social, pj.pj_nome_fantasia, pj.pj_cnpj,
                   cl.cli_id,cl.cli_cadastro,cl.cli_tipo
            from cliente cl
            left join cliente_pessoa_fisica cpf on cl.cli_id = cpf.cli_id
            left join cliente_pessoa_juridica cpj on cl.cli_id = cpj.cli_id
            left join pessoa_fisica pf on cpf.pf_id = pf.pf_id
            left join pessoa_juridica pj on cpj.pj_id = pj.pj_id
            inner join contato ct on ct.ctt_id = pf.ctt_id or ct.ctt_id = pj.ctt_id
            inner join endereco en on ct.end_id = en.end_id
            inner join cidade c on en.cid_id = c.cid_id
            inner join estado e on c.est_id = e.est_id
            where cl.cli_tipo = ?
            order by $order;
        ";

        if (!Banco::getInstance()->prepareStatement($sql))
            return [];

        if (!Banco::getInstance()->addParameters("i", [ $type ]))
            return [];

        if (!Banco::getInstance()->executeStatement())
            return [];

        return self::resultToList(Banco::getInstance()->getResult());
    }

    public static function getAll(string $ordem): array
    {
        $sql = "
            select e.est_id, e.est_nome, e.est_sigla,
                   c.cid_id, c.cid_nome,
                   en.end_id, en.end_rua, en.end_numero, en.end_bairro, en.end_complemento, en.end_cep,
                   ct.ctt_id, ct.ctt_telefone, ct.ctt_celular, ct.ctt_email,
                   pf.pf_id, pf.pf_nome, pf.pf_rg, pf.pf_cpf, pf.pf_nascimento,
                   pj.pj_id, pj.pj_razao_social, pj.pj_nome_fantasia, pj.pj_cnpj,
                   cl.cli_id,cl.cli_cadastro,cl.cli_tipo
            from cliente cl
            left join cliente_pessoa_fisica cpf on cl.cli_id = cpf.cli_id
            left join cliente_pessoa_juridica cpj on cl.cli_id = cpj.cli_id
            left join pessoa_fisica pf on cpf.pf_id = pf.pf_id
            left join pessoa_juridica pj on cpj.pj_id = pj.pj_id
            inner join contato ct on ct.ctt_id = pf.ctt_id or ct.ctt_id = pj.ctt_id
            inner join endereco en on ct.end_id = en.end_id
            inner join cidade c on en.cid_id = c.cid_id
            inner join estado e on c.est_id = e.est_id
            order by $ordem;
        ";

        $statement = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$statement) {
            echo Banco::getInstance()->getConnection()->error;
            return array();
        }

        $statement->execute();

        if (!($result = $statement->get_result()) || $result->num_rows == 0) {
            echo $statement->error;
            return array();
        }

        $clientes = array();
        for ($i = 0; $i < $result->num_rows; $i++) {
            $row = $result->fetch_assoc();
            $clientes[] = new Cliente(
                $row['cli_id'], $row['cli_cadastro'], $row['cli_tipo'],
                $row['cli_tipo'] == 2 ? null : new PessoaFisica(
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
                ),
                $row['cli_tipo'] == 1 ? null : new PessoaJuridica(
                    $row['pj_id'], $row['pj_razao_social'], $row['pj_nome_fantasia'], $row['pj_cnpj'],
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

        return $clientes;
    }
}