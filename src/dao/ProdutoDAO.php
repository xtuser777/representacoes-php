<?php

namespace scr\dao;

use ArrayObject;
use mysqli_result;
use mysqli_stmt;
use scr\model\Cidade;
use scr\model\Contato;
use scr\model\Endereco;
use scr\model\Estado;
use scr\model\PessoaJuridica;
use scr\model\Produto;
use scr\model\Representacao;
use scr\model\TipoCaminhao;
use scr\util\Banco;

class ProdutoDAO
{
    public static function insert(string $descricao, string $medida, float $preco, float $precoOut, int $representacao): int
    {
        $sql = "
            insert into produto (pro_descricao,pro_medida,pro_preco,pro_preco_out,rep_id)
            values (?,?,?,?,?);
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }

        $stmt->bind_param("ssddi", $descricao, $medida, $preco, $precoOut, $representacao);
        if (!$stmt->execute()) {
            echo $stmt->error;
            return -10;
        }

        return $stmt->insert_id;
    }

    public static function insertType(int $product, int $type): int
    {
        $sql = "
            insert into produto_tipo_caminhao (pro_id, tip_cam_id)
            values (?, ?);
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }

        $stmt->bind_param("ii", $product, $type);
        if (!$stmt->execute()) {
            echo $stmt->error;
            return -10;
        }

        return $stmt->affected_rows;
    }

    public static function update(int $id, string $descricao, string $medida, float $preco, float $precoOut, int $representacao): int
    {
        $sql = "
            update produto
            set pro_descricao = ?, pro_medida = ?, pro_preco = ?, pro_preco_out = ?, rep_id = ?
            where pro_id = ?;
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }

        $stmt->bind_param("ssddii", $descricao, $medida, $preco, $precoOut, $representacao, $id);
        if (!$stmt->execute()) {
            echo $stmt->error;
            return -10;
        }

        return $stmt->affected_rows;
    }

    public static function delete(int $id): int
    {
        $sql = "
            delete
            from produto
            where pro_id = ?;
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }

        $stmt->bind_param("i", $id);
        if (!$stmt->execute()) {
            echo $stmt->error;
            return -10;
        }

        return $stmt->affected_rows;
    }

    public static function select(): array
    {
        $sql = "
            select e.est_id, e.est_nome, e.est_sigla,
                   c.cid_id, c.cid_nome,
                   en.end_id, en.end_rua, en.end_numero, en.end_bairro, en.end_complemento, en.end_cep,
                   ct.ctt_id, ct.ctt_telefone, ct.ctt_celular, ct.ctt_email,
                   pj.pj_id, pj.pj_razao_social, pj.pj_nome_fantasia, pj.pj_cnpj,
                   r.rep_id, r.rep_cadastro, r.rep_unidade,
                   p.pro_id, p.pro_descricao, p.pro_medida, p.pro_preco, p.pro_preco_out
            from produto p
            inner join representacao r on p.rep_id = r.rep_id
            inner join pessoa_juridica pj on r.pj_id = pj.pj_id
            inner join contato ct on pj.ctt_id = ct.ctt_id
            inner join endereco en on ct.end_id = en.end_id
            inner join cidade c on en.cid_id = c.cid_id
            inner join estado e on c.est_id = e.est_id
            order by p.pro_id;
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt || !$stmt->execute()) {
            echo Banco::getInstance()->getConnection()->error;
            return array();
        }

        /** @var $result mysqli_result */
        if (!($result = $stmt->get_result()) || $result->num_rows <= 0) {
            echo $stmt->error;
            return array();
        }

        $produtos = [];
        foreach ($result as $row) {
            $produtos[] = new Produto (
                $row["pro_id"], $row["pro_descricao"], $row["pro_medida"], $row["pro_preco"], $row["pro_preco_out"],
                new Representacao(
                    $row['rep_id'], $row['rep_cadastro'], $row['rep_unidade'],
                    new PessoaJuridica(
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
                ),
                self::selectTypes($row["pro_id"])
            );
        }

        return $produtos;
    }

    public static function selectId(int $id): ?Produto
    {
        $sql = "
            select e.est_id, e.est_nome, e.est_sigla,
                   c.cid_id, c.cid_nome,
                   en.end_id, en.end_rua, en.end_numero, en.end_bairro, en.end_complemento, en.end_cep,
                   ct.ctt_id, ct.ctt_telefone, ct.ctt_celular, ct.ctt_email,
                   pj.pj_id, pj.pj_razao_social, pj.pj_nome_fantasia, pj.pj_cnpj,
                   r.rep_id, r.rep_cadastro, r.rep_unidade,
                   p.pro_id, p.pro_descricao, p.pro_medida, p.pro_preco, p.pro_preco_out
            from produto p
            inner join representacao r on p.rep_id = r.rep_id
            inner join pessoa_juridica pj on r.pj_id = pj.pj_id
            inner join contato ct on pj.ctt_id = ct.ctt_id
            inner join endereco en on ct.end_id = en.end_id
            inner join cidade c on en.cid_id = c.cid_id
            inner join estado e on c.est_id = e.est_id
            where p.pro_id = ?;
        ";

        /** @var $stmt mysqli_stmt */
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

        /** @var $result mysqli_result */
        if (!($result = $stmt->get_result()) || $result->num_rows <= 0) {
            echo $stmt->error;
            return null;
        }
        $row = $result->fetch_assoc();

        return new Produto (
            $row["pro_id"], $row["pro_descricao"], $row["pro_medida"], $row["pro_preco"], $row["pro_preco_out"],
            new Representacao(
                $row['rep_id'], $row['rep_cadastro'], $row['rep_unidade'],
                new PessoaJuridica(
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
            ),
            self::selectTypes($row["pro_id"])
        );
    }

    public static function selectKeyRepresentation(string $key, int $representacao): array
    {
        $sql = "
            select e.est_id, e.est_nome, e.est_sigla,
                   c.cid_id, c.cid_nome,
                   en.end_id, en.end_rua, en.end_numero, en.end_bairro, en.end_complemento, en.end_cep,
                   ct.ctt_id, ct.ctt_telefone, ct.ctt_celular, ct.ctt_email,
                   pj.pj_id, pj.pj_razao_social, pj.pj_nome_fantasia, pj.pj_cnpj,
                   r.rep_id, r.rep_cadastro, r.rep_unidade,
                   p.pro_id, p.pro_descricao, p.pro_medida, p.pro_preco, p.pro_preco_out
            from produto p
            inner join representacao r on p.rep_id = r.rep_id
            inner join pessoa_juridica pj on r.pj_id = pj.pj_id
            inner join contato ct on pj.ctt_id = ct.ctt_id
            inner join endereco en on ct.end_id = en.end_id
            inner join cidade c on en.cid_id = c.cid_id
            inner join estado e on c.est_id = e.est_id
            where p.pro_descricao like ?
            and r.rep_id = ?
            order by p.pro_id;
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return array();
        }

        $key = "%" . $key . "%";
        $stmt->bind_param("si", $key, $representacao);
        if (!$stmt->execute()) {
            echo $stmt->error;
            return array();
        }

        /** @var $result mysqli_result */
        if (!($result = $stmt->get_result()) || $result->num_rows <= 0) {
            echo $stmt->error;
            return array();
        }

        $produtos = [];
        foreach ($result as $row) {
            $produtos[] = new Produto (
                $row["pro_id"], $row["pro_descricao"], $row["pro_medida"], $row["pro_preco"], $row["pro_preco_out"],
                new Representacao(
                    $row['rep_id'], $row['rep_cadastro'], $row['rep_unidade'],
                    new PessoaJuridica(
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
                ),
                self::selectTypes($row["pro_id"])
            );
        }

        return $produtos;
    }

    public static function selectKey(string $key): array
    {
        $sql = "
            select e.est_id, e.est_nome, e.est_sigla,
                   c.cid_id, c.cid_nome,
                   en.end_id, en.end_rua, en.end_numero, en.end_bairro, en.end_complemento, en.end_cep,
                   ct.ctt_id, ct.ctt_telefone, ct.ctt_celular, ct.ctt_email,
                   pj.pj_id, pj.pj_razao_social, pj.pj_nome_fantasia, pj.pj_cnpj,
                   r.rep_id, r.rep_cadastro, r.rep_unidade,
                   p.pro_id, p.pro_descricao, p.pro_medida, p.pro_preco, p.pro_preco_out
            from produto p
            inner join representacao r on p.rep_id = r.rep_id
            inner join pessoa_juridica pj on r.pj_id = pj.pj_id
            inner join contato ct on pj.ctt_id = ct.ctt_id
            inner join endereco en on ct.end_id = en.end_id
            inner join cidade c on en.cid_id = c.cid_id
            inner join estado e on c.est_id = e.est_id
            where p.pro_descricao like ?
            order by p.pro_id;
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return array();
        }

        $key = "%" . $key . "%";
        $stmt->bind_param("s", $key);
        if (!$stmt->execute()) {
            echo $stmt->error;
            return array();
        }

        /** @var $result mysqli_result */
        if (!($result = $stmt->get_result()) || $result->num_rows <= 0) {
            echo $stmt->error;
            return array();
        }

        $produtos = [];
        foreach ($result as $row) {
            $produtos[] = new Produto (
                $row["pro_id"], $row["pro_descricao"], $row["pro_medida"], $row["pro_preco"], $row["pro_preco_out"],
                new Representacao(
                    $row['rep_id'], $row['rep_cadastro'], $row['rep_unidade'],
                    new PessoaJuridica(
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
                ),
                self::selectTypes($row["pro_id"])
            );
        }

        return $produtos;
    }

    public static function selectRepresentation(int $representacao): array
    {
        $sql = "
            select e.est_id, e.est_nome, e.est_sigla,
                   c.cid_id, c.cid_nome,
                   en.end_id, en.end_rua, en.end_numero, en.end_bairro, en.end_complemento, en.end_cep,
                   ct.ctt_id, ct.ctt_telefone, ct.ctt_celular, ct.ctt_email,
                   pj.pj_id, pj.pj_razao_social, pj.pj_nome_fantasia, pj.pj_cnpj,
                   r.rep_id, r.rep_cadastro, r.rep_unidade,
                   p.pro_id, p.pro_descricao, p.pro_medida, p.pro_preco, p.pro_preco_out
            from produto p
            inner join representacao r on p.rep_id = r.rep_id
            inner join pessoa_juridica pj on r.pj_id = pj.pj_id
            inner join contato ct on pj.ctt_id = ct.ctt_id
            inner join endereco en on ct.end_id = en.end_id
            inner join cidade c on en.cid_id = c.cid_id
            inner join estado e on c.est_id = e.est_id
            and r.rep_id = ?
            order by p.pro_id;
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return array();
        }

        $stmt->bind_param("i", $representacao);
        if (!$stmt->execute()) {
            echo $stmt->error;
            return array();
        }

        /** @var $result mysqli_result */
        if (!($result = $stmt->get_result()) || $result->num_rows <= 0) {
            echo $stmt->error;
            return array();
        }

        $produtos = [];
        foreach ($result as $row) {
            $produtos[] = new Produto (
                $row["pro_id"], $row["pro_descricao"], $row["pro_medida"], $row["pro_preco"], $row["pro_preco_out"],
                new Representacao(
                    $row['rep_id'], $row['rep_cadastro'], $row['rep_unidade'],
                    new PessoaJuridica(
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
                ),
                self::selectTypes($row["pro_id"])
            );
        }

        return $produtos;
    }

    public static function selectTypes(int $product): array
    {
        if ($product <= 0) return array();

        $sql = "
            select tc.tip_cam_id, tc.tip_cam_descricao, tc.tip_cam_eixos, tc.tip_cam_capacidade
            from tipo_caminhao tc
            inner join produto_tipo_caminhao ptc on ptc.tip_cam_id = tc.tip_cam_id
            where ptc.pro_id = ?;
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return array();
        }

        $stmt->bind_param("i", $product);
        if (!$stmt->execute()) {
            echo $stmt->error;
            return array();
        }

        /** @var $result mysqli_result */
        if (!($result = $stmt->get_result()) || $result->num_rows <= 0) {
            echo $stmt->error;
            return array();
        }

        $tipos = array();
        while ($row = $result->fetch_assoc()) {
            $tipos[] = new TipoCaminhao (
                $row["tip_cam_id"],
                $row["tip_cam_descricao"],
                $row["tip_cam_eixos"],
                $row["tip_cam_capacidade"]
            );
        }

        return $tipos;
    }

    public static function verifyType(int $product, int $type): bool
    {
        $sql = "
            select count(tip_cam_id) > 0 as res
            from produto_tipo_caminhao
            where pro_id = ? 
            and tip_cam_id = ?;
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return true;
        }

        $stmt->bind_param("ii", $product, $type);
        if (!$stmt->execute()) {
            echo $stmt->error;
            return true;
        }

        /** @var $result mysqli_result */
        if (!($result = $stmt->get_result()) || $result->num_rows <= 0) {
            echo $stmt->error;
            return true;
        }
        $row = $result->fetch_assoc();

        return $row["res"];
    }

    public static function deleteType(int $product, int $type): int
    {
        $sql = "
            delete
            from produto_tipo_caminhao
            where pro_id = ?
            and tip_cam_id = ?;
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }

        $stmt->bind_param("ii", $product, $type);
        if (!$stmt->execute()) {
            echo $stmt->error;
            return -10;
        }

        return $stmt->affected_rows;
    }
}