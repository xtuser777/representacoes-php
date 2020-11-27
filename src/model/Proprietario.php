<?php


namespace scr\model;


use mysqli_result;
use mysqli_stmt;
use scr\util\Banco;

class Proprietario
{
    private $id;
    private $cadastro;
    private $tipo;
    private $motorista;
    private $pessoaFisica;
    private $pessoaJuridica;

    public function __construct(int $id = 0, string $cadastro = "", int $tipo = 0, ?Motorista $motorista = null, ?PessoaFisica $pessoaFisica = null, ?PessoaJuridica $pessoaJuridica = null)
    {
        $this->id = $id;
        $this->cadastro = $cadastro;
        $this->tipo = $tipo;
        $this->motorista = $motorista;
        $this->pessoaFisica = $pessoaFisica;
        $this->pessoaJuridica = $pessoaJuridica;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCadastro(): string
    {
        return $this->cadastro;
    }

    public function getTipo(): int
    {
        return $this->tipo;
    }

    public function getMotorista(): ?Motorista
    {
        return $this->motorista;
    }

    public function getPessoaFisica(): ?PessoaFisica
    {
        return $this->pessoaFisica;
    }

    public function getPessoaJuridica(): ?PessoaJuridica
    {
        return $this->pessoaJuridica;
    }

    public function findById(int $id): ?Proprietario
    {
        if ($id <= 0) return null;
        $sql = "
            SELECT e.est_id, e.est_nome, e.est_sigla,
                   c.cid_id, c.cid_nome,
                   en.end_id, en.end_rua, en.end_numero, en.end_bairro, en.end_complemento, en.end_cep,
                   ct.ctt_id, ct.ctt_telefone, ct.ctt_celular, ct.ctt_email,
                   pf.pf_id, pf.pf_nome, pf.pf_rg, pf.pf_cpf, pf.pf_nascimento,
                   pj.pj_id, pj.pj_razao_social, pj.pj_nome_fantasia, pj.pj_cnpj,
                   pp.prp_id,pp.prp_cadastro,pp.prp_tipo,pp.mot_id
            FROM proprietario pp
            LEFT JOIN proprietario_pessoa_fisica ppf ON pp.prp_id = ppf.prp_id
            LEFT JOIN proprietario_pessoa_juridica ppj ON pp.prp_id = ppj.prp_id
            LEFT JOIN pessoa_fisica pf ON ppf.pf_id = pf.pf_id
            LEFT JOIN pessoa_juridica pj ON ppj.pj_id = pj.pj_id
            INNER JOIN contato ct ON ct.ctt_id = pf.ctt_id OR ct.ctt_id = pj.ctt_id
            INNER JOIN endereco en ON ct.end_id = en.end_id
            INNER JOIN cidade c ON en.cid_id = c.cid_id
            INNER JOIN estado e ON c.est_id = e.est_id
            WHERE pp.prp_id = ?;
        ";
        /** @var mysqli_stmt $stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return null;
        }
        $stmt->bind_param("i", $id);
        if (!$stmt->execute()) {
            echo $stmt->error;
            return null;
        }
        /** @var mysqli_result $result */
        $result = $stmt->get_result();
        if (!$result || $result->num_rows <= 0) {
            $stmt->error;
            return null;
        }
        $row = $result->fetch_assoc();

        return new Proprietario(
            $row["prp_id"], $row["prp_cadastro"], $row["prp_tipo"],
            (isset($row["mot_id"])) ? Motorista::findById($row["mot_id"]) : null,
            ($row["prp_tipo"] === 2) ? null : new PessoaFisica (
                $row['pf_id'], $row['pf_nome'], $row['pf_rg'], $row['pf_cpf'], $row['pf_nascimento'],
                new Contato (
                    $row['ctt_id'], $row['ctt_telefone'], $row['ctt_celular'], $row['ctt_email'],
                    new Endereco (
                        $row['end_id'], $row['end_rua'], $row['end_numero'], $row['end_bairro'], $row['end_complemento'], $row['end_cep'],
                        new Cidade (
                            $row['cid_id'],$row['cid_nome'],
                            new Estado (
                                $row['est_id'],$row['est_nome'],$row['est_sigla']
                            )
                        )
                    )
                )
            ),
            ($row["prp_tipo"] === 1) ? null : new PessoaJuridica (
                $row['pj_id'], $row['pj_razao_social'], $row['pj_nome_fantasia'], $row['pj_cnpj'],
                new Contato (
                    $row['ctt_id'], $row['ctt_telefone'], $row['ctt_celular'], $row['ctt_email'],
                    new Endereco (
                        $row['end_id'], $row['end_rua'], $row['end_numero'], $row['end_bairro'], $row['end_complemento'], $row['end_cep'],
                        new Cidade (
                            $row['cid_id'], $row['cid_nome'],
                            new Estado (
                                $row['est_id'], $row['est_nome'], $row['est_sigla']
                            )
                        )
                    )
                )
            )
        );
    }

    public function findByFilter(string $filter): array
    {
        if (strlen(trim($filter)) <= 0) return [];
        $sql = "
            SELECT e.est_id, e.est_nome, e.est_sigla,
                   c.cid_id, c.cid_nome,
                   en.end_id, en.end_rua, en.end_numero, en.end_bairro, en.end_complemento, en.end_cep,
                   ct.ctt_id, ct.ctt_telefone, ct.ctt_celular, ct.ctt_email,
                   pf.pf_id, pf.pf_nome, pf.pf_rg, pf.pf_cpf, pf.pf_nascimento,
                   pj.pj_id, pj.pj_razao_social, pj.pj_nome_fantasia, pj.pj_cnpj,
                   pp.prp_id,pp.prp_cadastro,pp.prp_tipo,pp.mot_id
            FROM proprietario pp
            LEFT JOIN proprietario_pessoa_fisica ppf ON pp.prp_id = ppf.prp_id
            LEFT JOIN proprietario_pessoa_juridica ppj ON pp.prp_id = ppj.prp_id
            LEFT JOIN pessoa_fisica pf ON ppf.pf_id = pf.pf_id
            LEFT JOIN pessoa_juridica pj ON ppj.pj_id = pj.pj_id
            INNER JOIN contato ct ON ct.ctt_id = pf.ctt_id OR ct.ctt_id = pj.ctt_id
            INNER JOIN endereco en ON ct.end_id = en.end_id
            INNER JOIN cidade c ON en.cid_id = c.cid_id
            INNER JOIN estado e ON c.est_id = e.est_id
            WHERE pf.pf_nome like ?
            OR pj.pj_nome_fantasia like ?;
        ";
        /** @var mysqli_stmt $stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return [];
        }
        $filtro = "%".$filter."%";
        $stmt->bind_param("ss", $filtro, $filtro);
        if (!$stmt->execute()) {
            echo $stmt->error;
            return [];
        }
        /** @var mysqli_result $result */
        $result = $stmt->get_result();
        if (!$result || $result->num_rows <= 0) {
            $stmt->error;
            return [];
        }
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $props = [];
        foreach ($rows as $row) {
            $props[] = new Proprietario(
                $row["prp_id"], $row["prp_cadastro"], $row["prp_tipo"],
                (isset($row["mot_id"])) ? Motorista::findById($row["mot_id"]) : null,
                ($row["prp_tipo"] === 2) ? null : new PessoaFisica (
                    $row['pf_id'], $row['pf_nome'], $row['pf_rg'], $row['pf_cpf'], $row['pf_nascimento'],
                    new Contato (
                        $row['ctt_id'], $row['ctt_telefone'], $row['ctt_celular'], $row['ctt_email'],
                        new Endereco (
                            $row['end_id'], $row['end_rua'], $row['end_numero'], $row['end_bairro'], $row['end_complemento'], $row['end_cep'],
                            new Cidade (
                                $row['cid_id'],$row['cid_nome'],
                                new Estado (
                                    $row['est_id'],$row['est_nome'],$row['est_sigla']
                                )
                            )
                        )
                    )
                ),
                ($row["prp_tipo"] === 1) ? null : new PessoaJuridica (
                    $row['pj_id'], $row['pj_razao_social'], $row['pj_nome_fantasia'], $row['pj_cnpj'],
                    new Contato (
                        $row['ctt_id'], $row['ctt_telefone'], $row['ctt_celular'], $row['ctt_email'],
                        new Endereco (
                            $row['end_id'], $row['end_rua'], $row['end_numero'], $row['end_bairro'], $row['end_complemento'], $row['end_cep'],
                            new Cidade (
                                $row['cid_id'], $row['cid_nome'],
                                new Estado (
                                    $row['est_id'], $row['est_nome'], $row['est_sigla']
                                )
                            )
                        )
                    )
                )
            );
        }

        return $props;
    }

    public function findByCad(string $cad): array
    {
        if (strlen(trim($cad)) <= 0) return [];
        $sql = "
            SELECT e.est_id, e.est_nome, e.est_sigla,
                   c.cid_id, c.cid_nome,
                   en.end_id, en.end_rua, en.end_numero, en.end_bairro, en.end_complemento, en.end_cep,
                   ct.ctt_id, ct.ctt_telefone, ct.ctt_celular, ct.ctt_email,
                   pf.pf_id, pf.pf_nome, pf.pf_rg, pf.pf_cpf, pf.pf_nascimento,
                   pj.pj_id, pj.pj_razao_social, pj.pj_nome_fantasia, pj.pj_cnpj,
                   pp.prp_id,pp.prp_cadastro,pp.prp_tipo,pp.mot_id
            FROM proprietario pp
            LEFT JOIN proprietario_pessoa_fisica ppf ON pp.prp_id = ppf.prp_id
            LEFT JOIN proprietario_pessoa_juridica ppj ON pp.prp_id = ppj.prp_id
            LEFT JOIN pessoa_fisica pf ON ppf.pf_id = pf.pf_id
            LEFT JOIN pessoa_juridica pj ON ppj.pj_id = pj.pj_id
            INNER JOIN contato ct ON ct.ctt_id = pf.ctt_id OR ct.ctt_id = pj.ctt_id
            INNER JOIN endereco en ON ct.end_id = en.end_id
            INNER JOIN cidade c ON en.cid_id = c.cid_id
            INNER JOIN estado e ON c.est_id = e.est_id
            WHERE pp.prp_cadastro = ?;
        ";
        /** @var mysqli_stmt $stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return [];
        }
        $stmt->bind_param("s", $cad);
        if (!$stmt->execute()) {
            echo $stmt->error;
            return [];
        }
        /** @var mysqli_result $result */
        $result = $stmt->get_result();
        if (!$result || $result->num_rows <= 0) {
            $stmt->error;
            return [];
        }
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $props = [];
        foreach ($rows as $row) {
            $props[] = new Proprietario(
                $row["prp_id"], $row["prp_cadastro"], $row["prp_tipo"],
                (isset($row["mot_id"])) ? Motorista::findById($row["mot_id"]) : null,
                ($row["prp_tipo"] === 2) ? null : new PessoaFisica (
                    $row['pf_id'], $row['pf_nome'], $row['pf_rg'], $row['pf_cpf'], $row['pf_nascimento'],
                    new Contato (
                        $row['ctt_id'], $row['ctt_telefone'], $row['ctt_celular'], $row['ctt_email'],
                        new Endereco (
                            $row['end_id'], $row['end_rua'], $row['end_numero'], $row['end_bairro'], $row['end_complemento'], $row['end_cep'],
                            new Cidade (
                                $row['cid_id'],$row['cid_nome'],
                                new Estado (
                                    $row['est_id'],$row['est_nome'],$row['est_sigla']
                                )
                            )
                        )
                    )
                ),
                ($row["prp_tipo"] === 1) ? null : new PessoaJuridica (
                    $row['pj_id'], $row['pj_razao_social'], $row['pj_nome_fantasia'], $row['pj_cnpj'],
                    new Contato (
                        $row['ctt_id'], $row['ctt_telefone'], $row['ctt_celular'], $row['ctt_email'],
                        new Endereco (
                            $row['end_id'], $row['end_rua'], $row['end_numero'], $row['end_bairro'], $row['end_complemento'], $row['end_cep'],
                            new Cidade (
                                $row['cid_id'], $row['cid_nome'],
                                new Estado (
                                    $row['est_id'], $row['est_nome'], $row['est_sigla']
                                )
                            )
                        )
                    )
                )
            );
        }

        return $props;
    }

    public function findByFilterCad(string $filter, string $cad): array
    {
        if (strlen(trim($filter)) <= 0) return [];
        $sql = "
            SELECT e.est_id, e.est_nome, e.est_sigla,
                   c.cid_id, c.cid_nome,
                   en.end_id, en.end_rua, en.end_numero, en.end_bairro, en.end_complemento, en.end_cep,
                   ct.ctt_id, ct.ctt_telefone, ct.ctt_celular, ct.ctt_email,
                   pf.pf_id, pf.pf_nome, pf.pf_rg, pf.pf_cpf, pf.pf_nascimento,
                   pj.pj_id, pj.pj_razao_social, pj.pj_nome_fantasia, pj.pj_cnpj,
                   pp.prp_id,pp.prp_cadastro,pp.prp_tipo,pp.mot_id
            FROM proprietario pp
            LEFT JOIN proprietario_pessoa_fisica ppf ON pp.prp_id = ppf.prp_id
            LEFT JOIN proprietario_pessoa_juridica ppj ON pp.prp_id = ppj.prp_id
            LEFT JOIN pessoa_fisica pf ON ppf.pf_id = pf.pf_id
            LEFT JOIN pessoa_juridica pj ON ppj.pj_id = pj.pj_id
            INNER JOIN contato ct ON ct.ctt_id = pf.ctt_id OR ct.ctt_id = pj.ctt_id
            INNER JOIN endereco en ON ct.end_id = en.end_id
            INNER JOIN cidade c ON en.cid_id = c.cid_id
            INNER JOIN estado e ON c.est_id = e.est_id
            WHERE (pf.pf_nome like ?
            OR pj.pj_nome_fantasia like ?)
            AND pp.prp_cadastro = ?;
        ";
        /** @var mysqli_stmt $stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return [];
        }
        $filtro = "%".$filter."%";
        $stmt->bind_param("sss", $filtro, $filtro, $cad);
        if (!$stmt->execute()) {
            echo $stmt->error;
            return [];
        }
        /** @var mysqli_result $result */
        $result = $stmt->get_result();
        if (!$result || $result->num_rows <= 0) {
            $stmt->error;
            return [];
        }
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $props = [];
        foreach ($rows as $row) {
            $props[] = new Proprietario(
                $row["prp_id"], $row["prp_cadastro"], $row["prp_tipo"],
                (isset($row["mot_id"])) ? Motorista::findById($row["mot_id"]) : null,
                ($row["prp_tipo"] === 2) ? null : new PessoaFisica (
                    $row['pf_id'], $row['pf_nome'], $row['pf_rg'], $row['pf_cpf'], $row['pf_nascimento'],
                    new Contato (
                        $row['ctt_id'], $row['ctt_telefone'], $row['ctt_celular'], $row['ctt_email'],
                        new Endereco (
                            $row['end_id'], $row['end_rua'], $row['end_numero'], $row['end_bairro'], $row['end_complemento'], $row['end_cep'],
                            new Cidade (
                                $row['cid_id'],$row['cid_nome'],
                                new Estado (
                                    $row['est_id'],$row['est_nome'],$row['est_sigla']
                                )
                            )
                        )
                    )
                ),
                ($row["prp_tipo"] === 1) ? null : new PessoaJuridica (
                    $row['pj_id'], $row['pj_razao_social'], $row['pj_nome_fantasia'], $row['pj_cnpj'],
                    new Contato (
                        $row['ctt_id'], $row['ctt_telefone'], $row['ctt_celular'], $row['ctt_email'],
                        new Endereco (
                            $row['end_id'], $row['end_rua'], $row['end_numero'], $row['end_bairro'], $row['end_complemento'], $row['end_cep'],
                            new Cidade (
                                $row['cid_id'], $row['cid_nome'],
                                new Estado (
                                    $row['est_id'], $row['est_nome'], $row['est_sigla']
                                )
                            )
                        )
                    )
                )
            );
        }

        return $props;
    }

    public function findByType(int $type): array
    {
        if ($type <= 0)
            return [];

        $sql = "
            SELECT e.est_id, e.est_nome, e.est_sigla,
                   c.cid_id, c.cid_nome,
                   en.end_id, en.end_rua, en.end_numero, en.end_bairro, en.end_complemento, en.end_cep,
                   ct.ctt_id, ct.ctt_telefone, ct.ctt_celular, ct.ctt_email,
                   pf.pf_id, pf.pf_nome, pf.pf_rg, pf.pf_cpf, pf.pf_nascimento,
                   pj.pj_id, pj.pj_razao_social, pj.pj_nome_fantasia, pj.pj_cnpj,
                   pp.prp_id,pp.prp_cadastro,pp.prp_tipo,pp.mot_id
            FROM proprietario pp
            LEFT JOIN proprietario_pessoa_fisica ppf ON pp.prp_id = ppf.prp_id
            LEFT JOIN proprietario_pessoa_juridica ppj ON pp.prp_id = ppj.prp_id
            LEFT JOIN pessoa_fisica pf ON ppf.pf_id = pf.pf_id
            LEFT JOIN pessoa_juridica pj ON ppj.pj_id = pj.pj_id
            INNER JOIN contato ct ON ct.ctt_id = pf.ctt_id OR ct.ctt_id = pj.ctt_id
            INNER JOIN endereco en ON ct.end_id = en.end_id
            INNER JOIN cidade c ON en.cid_id = c.cid_id
            INNER JOIN estado e ON c.est_id = e.est_id 
            INNER JOIN caminhao c2 on pp.prp_id = c2.prp_id
            WHERE c2.tip_cam_id = ?;
        ";

        /** @var mysqli_stmt $stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return [];
        }

        $stmt->bind_param("i", $type);
        if (!$stmt->execute()) {
            echo $stmt->error;
            return [];
        }

        /** @var mysqli_result $result */
        $result = $stmt->get_result();
        if (!$result) {
            $stmt->error;
            return [];
        }

        $props = [];
        while ($row = $result->fetch_assoc()) {
            $props[] = new Proprietario(
                $row["prp_id"], $row["prp_cadastro"], $row["prp_tipo"],
                (isset($row["mot_id"])) ? Motorista::findById($row["mot_id"]) : null,
                ($row["prp_tipo"] === 2) ? null : new PessoaFisica (
                    $row['pf_id'], $row['pf_nome'], $row['pf_rg'], $row['pf_cpf'], $row['pf_nascimento'],
                    new Contato (
                        $row['ctt_id'], $row['ctt_telefone'], $row['ctt_celular'], $row['ctt_email'],
                        new Endereco (
                            $row['end_id'], $row['end_rua'], $row['end_numero'], $row['end_bairro'], $row['end_complemento'], $row['end_cep'],
                            new Cidade (
                                $row['cid_id'],$row['cid_nome'],
                                new Estado (
                                    $row['est_id'],$row['est_nome'],$row['est_sigla']
                                )
                            )
                        )
                    )
                ),
                ($row["prp_tipo"] === 1) ? null : new PessoaJuridica (
                    $row['pj_id'], $row['pj_razao_social'], $row['pj_nome_fantasia'], $row['pj_cnpj'],
                    new Contato (
                        $row['ctt_id'], $row['ctt_telefone'], $row['ctt_celular'], $row['ctt_email'],
                        new Endereco (
                            $row['end_id'], $row['end_rua'], $row['end_numero'], $row['end_bairro'], $row['end_complemento'], $row['end_cep'],
                            new Cidade (
                                $row['cid_id'], $row['cid_nome'],
                                new Estado (
                                    $row['est_id'], $row['est_nome'], $row['est_sigla']
                                )
                            )
                        )
                    )
                )
            );
        }

        return $props;
    }

    public function findAll(): array
    {
        $sql = "
            SELECT e.est_id, e.est_nome, e.est_sigla,
                   c.cid_id, c.cid_nome,
                   en.end_id, en.end_rua, en.end_numero, en.end_bairro, en.end_complemento, en.end_cep,
                   ct.ctt_id, ct.ctt_telefone, ct.ctt_celular, ct.ctt_email,
                   pf.pf_id, pf.pf_nome, pf.pf_rg, pf.pf_cpf, pf.pf_nascimento,
                   pj.pj_id, pj.pj_razao_social, pj.pj_nome_fantasia, pj.pj_cnpj,
                   pp.prp_id,pp.prp_cadastro,pp.prp_tipo,pp.mot_id
            FROM proprietario pp
            LEFT JOIN proprietario_pessoa_fisica ppf ON pp.prp_id = ppf.prp_id
            LEFT JOIN proprietario_pessoa_juridica ppj ON pp.prp_id = ppj.prp_id
            LEFT JOIN pessoa_fisica pf ON ppf.pf_id = pf.pf_id
            LEFT JOIN pessoa_juridica pj ON ppj.pj_id = pj.pj_id
            INNER JOIN contato ct ON ct.ctt_id = pf.ctt_id OR ct.ctt_id = pj.ctt_id
            INNER JOIN endereco en ON ct.end_id = en.end_id
            INNER JOIN cidade c ON en.cid_id = c.cid_id
            INNER JOIN estado e ON c.est_id = e.est_id;
        ";
        /** @var mysqli_stmt $stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return [];
        }
        if (!$stmt->execute()) {
            echo $stmt->error;
            return [];
        }
        /** @var mysqli_result $result */
        $result = $stmt->get_result();
        if (!$result || $result->num_rows <= 0) {
            $stmt->error;
            return [];
        }

        $props = [];
        while ($row = $result->fetch_assoc()) {
            $props[] = new Proprietario(
                $row["prp_id"], $row["prp_cadastro"], $row["prp_tipo"],
                (isset($row["mot_id"])) ? Motorista::findById($row["mot_id"]) : null,
                ($row["prp_tipo"] === 2) ? null : new PessoaFisica (
                    $row['pf_id'], $row['pf_nome'], $row['pf_rg'], $row['pf_cpf'], $row['pf_nascimento'],
                    new Contato (
                        $row['ctt_id'], $row['ctt_telefone'], $row['ctt_celular'], $row['ctt_email'],
                        new Endereco (
                            $row['end_id'], $row['end_rua'], $row['end_numero'], $row['end_bairro'], $row['end_complemento'], $row['end_cep'],
                            new Cidade (
                                $row['cid_id'],$row['cid_nome'],
                                new Estado (
                                    $row['est_id'],$row['est_nome'],$row['est_sigla']
                                )
                            )
                        )
                    )
                ),
                ($row["prp_tipo"] === 1) ? null : new PessoaJuridica (
                    $row['pj_id'], $row['pj_razao_social'], $row['pj_nome_fantasia'], $row['pj_cnpj'],
                    new Contato (
                        $row['ctt_id'], $row['ctt_telefone'], $row['ctt_celular'], $row['ctt_email'],
                        new Endereco (
                            $row['end_id'], $row['end_rua'], $row['end_numero'], $row['end_bairro'], $row['end_complemento'], $row['end_cep'],
                            new Cidade (
                                $row['cid_id'], $row['cid_nome'],
                                new Estado (
                                    $row['est_id'], $row['est_nome'], $row['est_sigla']
                                )
                            )
                        )
                    )
                )
            );
        }

        return $props;
    }

    public function save(): int
    {
        if ($this->id !== 0 || strlen(trim($this->cadastro)) <= 0 || $this->tipo <= 0) return -5;
        $sql = "
            INSERT 
            INTO proprietario(prp_cadastro, prp_tipo, mot_id)
            VALUES (?,?,?);
        ";
        /** @var mysqli_stmt $stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }
        $motorista = ($this->motorista) ? $this->motorista->getId() : 0;
        $stmt->bind_param("sii", $this->cadastro, $this->tipo, $motorista);
        if (!$stmt->execute()) {
            echo $stmt->error;
            return -10;
        }
        $insertId = $stmt->insert_id;
        if ($insertId <= 0) {
            return $insertId;
        }
        $sql_pes = "";
        if ($this->tipo === 1) {
            $sql_pes = "
                INSERT 
                INTO proprietario_pessoa_fisica(prp_id, pf_id)
                VALUES (?,?);
            ";
        } else {
            $sql_pes = "
                INSERT 
                INTO proprietario_pessoa_juridica(prp_id, pj_id)
                VALUES (?,?);
            ";
        }
        /** @var mysqli_stmt $stmt_pes */
        $stmt_pes = Banco::getInstance()->getConnection()->prepare($sql_pes);
        if (!$stmt_pes) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }
        $pessoa = ($this->pessoaFisica) ? $this->pessoaFisica->getId() : $this->pessoaJuridica->getId();
        $stmt_pes->bind_param("ii", $insertId, $pessoa);
        if (!$stmt_pes->execute()) {
            echo $stmt_pes->error;
            return -10;
        }

        return $stmt_pes->affected_rows;
    }

    public function update(): int
    {
        if ($this->id <= 0 || strlen(trim($this->cadastro)) <= 0 || $this->tipo <= 0) return -5;
        $sql = "
            UPDATE proprietario
            SET prp_tipo = ?,mot_id = ?
            WHERE prp_id = ?;
        ";
        /** @var mysqli_stmt $stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }
        $mot = ($this->motorista) ? $this->motorista->getId() : 0;
        $stmt->bind_param("iii", $this->tipo, $mot, $this->id);
        if (!$stmt->execute()) {
            echo $stmt->error;
            return -10;
        }

        return $stmt->affected_rows;
    }

    public function delete(): int
    {
        if ($this->id <= 0) return -5;

        $ret = 0;
        if ($this->tipo === 1) {
            $ret = $this->deletePF();
        } else {
            $ret = $this->deletePJ();
        }

        if ($ret < 0) return $ret;

        $sql = "
            DELETE
            FROM proprietario
            WHERE prp_id = ?;
        ";
        /** @var mysqli_stmt $stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }
        $stmt->bind_param("i", $this->id);
        if (!$stmt->execute()) {
            echo $stmt->error;
            return -10;
        }

        return $stmt->affected_rows;
    }

    public function deletePF(): int
    {
        $sql = "
            DELETE
            FROM proprietario_pessoa_fisica
            WHERE prp_id = ?;
        ";
        /** @var mysqli_stmt $stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }
        $stmt->bind_param("i", $this->id);
        if (!$stmt->execute()) {
            echo $stmt->error;
            return -10;
        }

        return $stmt->affected_rows;
    }

    public function deletePJ(): int
    {
        $sql = "
            DELETE
            FROM proprietario_pessoa_juridica
            WHERE prp_id = ?;
        ";
        /** @var mysqli_stmt $stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }
        $stmt->bind_param("i", $this->id);
        if (!$stmt->execute()) {
            echo $stmt->error;
            return -10;
        }

        return $stmt->affected_rows;
    }

    public function jsonSerialize()
    {
        $this->motorista = (!!$this->motorista) ? $this->motorista->jsonSerialize() : null;
        $this->pessoaFisica = (!!$this->pessoaFisica) ? $this->pessoaFisica->jsonSerialize(): null;
        $this->pessoaJuridica = (!!$this->pessoaJuridica) ? $this->pessoaJuridica->jsonSerialize() : null;

        return get_object_vars($this);
    }
}