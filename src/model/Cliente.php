<?php namespace scr\model;

use mysqli;
use scr\model\PessoaFisica;
use scr\model\PessoaJuridica;
use scr\dao\ClienteDAO;

class Cliente
{
    private $id;
    private $cadastro;
    private $tipo;
    private $pessoaFisica;
    private $pessoaJuridica;

    public function __construct(int $id, string $cadastro, int $tipo , ?PessoaFisica $pessoaFisica, ?PessoaJuridica $pessoaJuridica)
    {
        $this->id = $id;
        $this->cadastro = $cadastro;
        $this->tipo = $tipo;
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

    public function getPessoaFisica(): ?PessoaFisica
    {
        return $this->pessoaFisica;
    }

    public function setPessoaFisica(PessoaFisica $pf) : void
    {
        $this->pessoaFisica = $pf;
    }

    public function getPessoaJuridica(): ?PessoaJuridica
    {
        return $this->pessoaJuridica;
    }

    public function setPessoaJuridica(PessoaJuridica $pj) : void
    {
        $this->pessoaJuridica = $pj;
    }

    public static function getById(int $id): ?Cliente
    {
        return $id > 0 ? ClienteDAO::getById($id) : null;
    }

    public static function getByKey(string $key): array
    {
        return $key !== null && strlen(trim($key)) > 0 ? ClienteDAO::getByKey($key) : array();
    }

    public static function getByCad(string $cad): array
    {
        return $cad !== null && strlen(trim($cad)) > 0 ? ClienteDAO::getByCad($cad) : array();
    }

    public static function getByKeyCad(string $key, string $cad): array
    {
        return ($key !== null && strlen(trim($key)) > 0) && ($cad !== null && strlen(trim($cad)) > 0) ? ClienteDAO::getByKeyCad($key, $cad) : array();
    }

    public static function getByFilterPeriodType(string $filter, string $init, string $end, int $type, string $order): array
    {
        if (trim($filter) === "" || $init === "" || $end === "" || $type <= 0)
            return [];

        return ClienteDAO::getByFilterPeriodType(trim($filter), $init, $end, $type, $order);
    }

    public static function getByFilterPeriod(string $filter, string $init, string $end, string $order): array
    {
        if (trim($filter) === "" || $init === "" || $end === "")
            return [];

        return ClienteDAO::getByFilterPeriod(trim($filter), $init, $end, $order);
    }

    public static function getByFilterType(string $filter, int $type, string $order): array
    {
        if (trim($filter) === "" || $type <= 0)
            return [];

        return ClienteDAO::getByFilterType(trim($filter), $type, $order);
    }

    public static function getByFilter(string $filter, string $order): array
    {
        if (trim($filter) === "")
            return [];

        return ClienteDAO::getByFilter(trim($filter), $order);
    }

    public static function getByPeriodType(string $init, string $end, int $type, string $order): array
    {
        if ($init === "" || $end === "" || $type <= 0)
            return [];

        return ClienteDAO::getByPeriodType($init, $end, $type, $order);
    }

    public static function getByPeriod(string $init, string $end, string $order): array
    {
        if ($init === "" || $end === "")
            return [];

        return ClienteDAO::getByPeriod($init, $end, $order);
    }

    public static function getByType(int $type, string $order): array
    {
        if ($type <= 0)
            return [];

        return ClienteDAO::getByType($type, $order);
    }

    public static function getAll(string $ordem = "pf.pf_nome, pj.pj_nome_fantasia"): array
    {
        return ClienteDAO::getAll($ordem);
    }

    public function insert(): int
    {
        if ($this->id != 0 || strlen(trim($this->cadastro)) <= 0 || $this->tipo <= 0) return -5;

        return ClienteDAO::insert($this->cadastro, $this->tipo, ($this->tipo == 1) ? $this->pessoaFisica->getId() : 0, ($this->tipo == 2) ? $this->pessoaJuridica->getId() : 0);
    }

    public function update(): int
    {
        if ($this->id <= 0 || strlen(trim($this->cadastro)) <= 0 || $this->tipo <= 0) return -5;

        return ClienteDAO::update($this->id, $this->cadastro, $this->tipo);
    }

    public static function delete(int $tipo, int $id): int
    {
        return $id > 0 ? ClienteDAO::delete($tipo, $id) : -5;
    }

    public function jsonSerialize()
    {
        if ($this->tipo == 1) {
            $this->pessoaFisica = $this->pessoaFisica->jsonSerialize();
        } else {
            $this->pessoaJuridica = $this->pessoaJuridica->jsonSerialize();
        }

        return get_object_vars($this);
    }
}