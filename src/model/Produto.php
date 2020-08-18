<?php


namespace scr\model;


use ArrayObject;
use mysqli_result;
use mysqli_stmt;
use scr\dao\ProdutoDAO;
use scr\util\Banco;

class Produto
{
    private $id;
    private $descricao;
    private $medida;
    private $peso;
    private $preco;
    private $precoOut;
    private $representacao;
    private $tiposCaminhao;

    public function __construct(int $id, string $descricao, string $medida, float $peso, float $preco, float $precoOut, Representacao $representacao, array $tipos)
    {
        $this->id = $id;
        $this->descricao = $descricao;
        $this->medida = $medida;
        $this->peso = $peso;
        $this->preco = $preco;
        $this->precoOut = $precoOut;
        $this->representacao = $representacao;
        $this->tiposCaminhao = $tipos;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getDescricao(): string
    {
        return $this->descricao;
    }

    public function getMedida(): string
    {
        return $this->medida;
    }

    public function getPeso(): float
    {
        return $this->peso;
    }

    public function getPreco(): float
    {
        return $this->preco;
    }

    public function getPrecoOut(): float
    {
        return $this->precoOut;
    }

    public function getRepresentacao(): Representacao
    {
        return $this->representacao;
    }

    public function getTipos(): array
    {
        return $this->tiposCaminhao;
    }

    public static function findById(int $id): ?Produto
    {
        if ($id <= 0) return null;
        $sql = "
            select e.est_id, e.est_nome, e.est_sigla,
                   c.cid_id, c.cid_nome,
                   en.end_id, en.end_rua, en.end_numero, en.end_bairro, en.end_complemento, en.end_cep,
                   ct.ctt_id, ct.ctt_telefone, ct.ctt_celular, ct.ctt_email,
                   pj.pj_id, pj.pj_razao_social, pj.pj_nome_fantasia, pj.pj_cnpj,
                   r.rep_id, r.rep_cadastro, r.rep_unidade,
                   p.pro_id, p.pro_descricao, p.pro_medida, p.pro_peso, p.pro_preco, p.pro_preco_out
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
            $row["pro_id"], $row["pro_descricao"], $row["pro_medida"], $row["pro_peso"], $row["pro_preco"], $row["pro_preco_out"],
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

    public static function findByKeyRepresentation(string $key, int $representacao): array
    {
        if (strlen(trim($key)) === 0 && $representacao === 0) return array();
        $sql = "
            select e.est_id, e.est_nome, e.est_sigla,
                   c.cid_id, c.cid_nome,
                   en.end_id, en.end_rua, en.end_numero, en.end_bairro, en.end_complemento, en.end_cep,
                   ct.ctt_id, ct.ctt_telefone, ct.ctt_celular, ct.ctt_email,
                   pj.pj_id, pj.pj_razao_social, pj.pj_nome_fantasia, pj.pj_cnpj,
                   r.rep_id, r.rep_cadastro, r.rep_unidade,
                   p.pro_id, p.pro_descricao, p.pro_medida, p.pro_peso, p.pro_preco, p.pro_preco_out
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
        while ($row = $result->fetch_assoc()) {
            $produtos[] = new Produto (
                $row["pro_id"], $row["pro_descricao"], $row["pro_medida"], $row["pro_peso"], $row["pro_preco"], $row["pro_preco_out"],
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

    public static function findByKey(string $key): array
    {
        if (strlen(trim($key)) === 0) return array();
        $sql = "
            select e.est_id, e.est_nome, e.est_sigla,
                   c.cid_id, c.cid_nome,
                   en.end_id, en.end_rua, en.end_numero, en.end_bairro, en.end_complemento, en.end_cep,
                   ct.ctt_id, ct.ctt_telefone, ct.ctt_celular, ct.ctt_email,
                   pj.pj_id, pj.pj_razao_social, pj.pj_nome_fantasia, pj.pj_cnpj,
                   r.rep_id, r.rep_cadastro, r.rep_unidade,
                   p.pro_id, p.pro_descricao, p.pro_medida, p.pro_peso, p.pro_preco, p.pro_preco_out
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
        while ($row = $result->fetch_assoc()) {
            $produtos[] = new Produto (
                $row["pro_id"], $row["pro_descricao"], $row["pro_medida"], $row["pro_peso"], $row["pro_preco"], $row["pro_preco_out"],
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

    public static function findByRepresentation(int $representacao): array
    {
        if ($representacao <= 0) return array();
        $sql = "
            select e.est_id, e.est_nome, e.est_sigla,
                   c.cid_id, c.cid_nome,
                   en.end_id, en.end_rua, en.end_numero, en.end_bairro, en.end_complemento, en.end_cep,
                   ct.ctt_id, ct.ctt_telefone, ct.ctt_celular, ct.ctt_email,
                   pj.pj_id, pj.pj_razao_social, pj.pj_nome_fantasia, pj.pj_cnpj,
                   r.rep_id, r.rep_cadastro, r.rep_unidade,
                   p.pro_id, p.pro_descricao, p.pro_medida, p.pro_peso, p.pro_preco, p.pro_preco_out
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
        while ($row = $result->fetch_assoc()) {
            $produtos[] = new Produto (
                $row["pro_id"], $row["pro_descricao"], $row["pro_medida"], $row["pro_peso"], $row["pro_preco"], $row["pro_preco_out"],
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

    public static function findAll(): array
    {
        $sql = "
            select e.est_id, e.est_nome, e.est_sigla,
                   c.cid_id, c.cid_nome,
                   en.end_id, en.end_rua, en.end_numero, en.end_bairro, en.end_complemento, en.end_cep,
                   ct.ctt_id, ct.ctt_telefone, ct.ctt_celular, ct.ctt_email,
                   pj.pj_id, pj.pj_razao_social, pj.pj_nome_fantasia, pj.pj_cnpj,
                   r.rep_id, r.rep_cadastro, r.rep_unidade,
                   p.pro_id, p.pro_descricao, p.pro_medida, p.pro_peso, p.pro_preco, p.pro_preco_out
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
        while ($row = $result->fetch_assoc()) {
            $produtos[] = new Produto (
                $row["pro_id"], $row["pro_descricao"], $row["pro_medida"], $row["pro_peso"], $row["pro_preco"], $row["pro_preco_out"],
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
        if ($type <= 0) return false;
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

    public function save(): int
    {
        if ($this->id != 0 || strlen(trim($this->descricao)) <= 0 || strlen(trim($this->medida)) <= 0 ||
            $this->peso <= 0 || $this->preco <= 0 || $this->representacao == null
        ) return -5;
        $sql = "
            insert into produto (pro_descricao,pro_medida,pro_peso,pro_preco,pro_preco_out,rep_id)
            values (?,?,?,?,?,?);
        ";
        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }
        $rep = $this->representacao->getId();
        $stmt->bind_param(
            "ssdddi",
            $this->descricao, $this->medida, $this->peso, $this->preco,
            $this->precoOut, $rep
        );
        if (!$stmt->execute()) {
            echo $stmt->error;
            return -10;
        }

        return $stmt->insert_id;
    }

    public function saveType(int $type): int
    {
        if ($type <= 0) return -5;
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

        $stmt->bind_param("ii", $this->id, $type);
        if (!$stmt->execute()) {
            echo $stmt->error;
            return -10;
        }

        return $stmt->affected_rows;
    }

    public function update(): int
    {
        if ($this->id <= 0 || strlen(trim($this->descricao)) <= 0 || strlen(trim($this->medida)) <= 0 || $this->peso <= 0 ||
            $this->preco <= 0 || $this->representacao == null
        ) return -5;
        $sql = "
            update produto
            set pro_descricao = ?, pro_medida = ?, pro_peso = ?, pro_preco = ?, pro_preco_out = ?, rep_id = ?
            where pro_id = ?;
        ";
        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }
        $rep = $this->representacao->getId();
        $stmt->bind_param(
            "ssdddii",
            $this->descricao, $this->medida, $this->peso, $this->preco, $this->precoOut, $rep, $this->id
        );
        if (!$stmt->execute()) {
            echo $stmt->error;
            return -10;
        }

        return $stmt->affected_rows;
    }

    public function delete(): int
    {
        if ($this->id <= 0) return -5;
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
        $stmt->bind_param("i", $this->id);
        if (!$stmt->execute()) {
            echo $stmt->error;
            return -10;
        }

        return $stmt->affected_rows;
    }

    public function deleteType(int $type): int
    {
        if ($type <= 0) return -5;
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
        $stmt->bind_param("ii", $this->id, $type);
        if (!$stmt->execute()) {
            echo $stmt->error;
            return -10;
        }

        return $stmt->affected_rows;
    }

    public function jsonSerialize()
    {
        $this->representacao = $this->representacao->jsonSerialize();
        return get_object_vars($this);
    }
}