<?php namespace scr\model;

use mysqli_result;
use mysqli_stmt;
use scr\dao\CaminhaoDAO;
use scr\util\Banco;

class Caminhao
{
    private $id;
    private $placa;
    private $marca;
    private $modelo;
    private $cor;
    private $anoFabricacao;
    private $anoModelo;
    private $tipo;
    private $proprietario;

    public function __construct(int $id, string $placa, string $marca, string $modelo, string $cor, string $anoFabricacao, string $anoModelo, TipoCaminhao $tipo, Proprietario $proprietario)
    {
        $this->id = $id;
        $this->placa = $placa;
        $this->marca = $marca;
        $this->modelo = $modelo;
        $this->cor = $cor;
        $this->anoFabricacao = $anoFabricacao;
        $this->anoModelo = $anoModelo;
        $this->tipo = $tipo;
        $this->proprietario = $proprietario;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPlaca(): string
    {
        return $this->placa;
    }

    public function getMarca(): string
    {
        return $this->marca;
    }

    public function getModelo(): string
    {
        return $this->modelo;
    }

    public function getCor(): string
    {
        return $this->cor;
    }

    public function getAnoFabricacao(): string
    {
        return $this->anoFabricacao;
    }

    public function getAnoModelo(): string
    {
        return $this->anoModelo;
    }

    public function getTipo(): TipoCaminhao
    {
        return $this->tipo;
    }

    public function getProprietario(): Proprietario
    {
        return $this->proprietario;
    }

    public static function findById(int $id): ?Caminhao
    {
        if ($id <= 0) return null;

        $sql = "
            select tc.tip_cam_id,tc.tip_cam_descricao,tc.tip_cam_eixos,tc.tip_cam_capacidade,
                   cm.cam_id,cm.cam_placa,cm.cam_marca,cm.cam_modelo,cm.cam_cor,cm.cam_ano_fabricacao,cm.cam_ano_modelo,cm.prp_id
            from caminhao cm 
            inner join tipo_caminhao tc on tc.tip_cam_id = cm.tip_cam_id
            where cm.cam_id = ?;
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
        $result = $stmt->get_result();
        if (!$result || $result->num_rows <= 0) {
            echo $stmt->error;
            return null;
        }
        $row = $result->fetch_assoc();

        return new Caminhao(
            $row["cam_id"], $row["cam_placa"], $row["cam_marca"], $row["cam_modelo"], $row["cam_cor"], $row["cam_ano_fabricacao"], $row["cam_ano_modelo"],
            new TipoCaminhao (
                $row["tip_cam_id"], $row["tip_cam_descricao"], $row["tip_cam_eixos"], $row["tip_cam_capacidade"]
            ),
            (new Proprietario())->findById($row["prp_id"])
        );
    }

    public static function findByKey(string $key): array
    {
        if (strlen(trim($key)) <= 0) return array();

        $sql = "
            select tc.tip_cam_id,tc.tip_cam_descricao,tc.tip_cam_eixos,tc.tip_cam_capacidade,
                   cm.cam_id,cm.cam_placa,cm.cam_marca,cm.cam_modelo,cm.cam_cor,cm.cam_ano_fabricacao,cm.cam_ano_modelo,cm.prp_id
            from caminhao cm 
            inner join tipo_caminhao tc on tc.tip_cam_id = cm.tip_cam_id
            where cm.cam_marca like ?
            or cm.cam_modelo like ?
            order by cm.cam_id;
        ";
        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return array();
        }
        $filter = "%".$key."%";
        $stmt->bind_param("ss", $filter, $filter);
        if (!$stmt->execute()) {
            echo $stmt->error;
            return array();
        }
        /** @var $result mysqli_result */
        $result = $stmt->get_result();
        if (!$result || $result->num_rows <= 0) {
            echo $stmt->error;
            return array();
        }
        $caminhoes = [];
        while ($row = $result->fetch_assoc()) {
            $caminhoes[] = new Caminhao(
                $row["cam_id"], $row["cam_placa"], $row["cam_marca"], $row["cam_modelo"], $row["cam_cor"], $row["cam_ano_fabricacao"], $row["cam_ano_modelo"],
                new TipoCaminhao (
                    $row["tip_cam_id"], $row["tip_cam_descricao"], $row["tip_cam_eixos"], $row["tip_cam_capacidade"]
                ),
                (new Proprietario())->findById($row["prp_id"])
            );
        }

        return $caminhoes;
    }

    public static function findAll(): array
    {
        $sql = "
            select tc.tip_cam_id,tc.tip_cam_descricao,tc.tip_cam_eixos,tc.tip_cam_capacidade,
                   cm.cam_id,cm.cam_placa,cm.cam_marca,cm.cam_modelo,cm.cam_cor,cm.cam_ano_fabricacao,cm.cam_ano_modelo,cm.prp_id
            from caminhao cm 
            inner join tipo_caminhao tc on tc.tip_cam_id = cm.tip_cam_id
            order by cm.cam_id;
        ";
        /** @var $result mysqli_result */
        $result = Banco::getInstance()->getConnection()->query($sql);
        if (!$result || $result->num_rows <= 0) {
            echo Banco::getInstance()->getConnection()->error;
            return array();
        }
        $caminhoes = [];
        while ($row = $result->fetch_assoc()) {
            $caminhoes[] = new Caminhao(
                $row["cam_id"], $row["cam_placa"], $row["cam_marca"], $row["cam_modelo"], $row["cam_cor"], $row["cam_ano_fabricacao"], $row["cam_ano_modelo"],
                new TipoCaminhao (
                    $row["tip_cam_id"], $row["tip_cam_descricao"], $row["tip_cam_eixos"], $row["tip_cam_capacidade"]
                ),
                (new Proprietario())->findById($row["prp_id"])
            );
        }

        return $caminhoes;
    }

    public function save(): int
    {
        if ($this->id != 0 || strlen($this->placa) <= 0 || strlen($this->marca) <= 0 || strlen($this->modelo) <= 0
            || strlen($this->cor) <= 0 || strlen($this->anoFabricacao) <= 0 || strlen($this->anoModelo) <= 0
            || $this->tipo == null || $this->proprietario == null)
            return -5;

        $sql = "
            insert into caminhao(cam_placa,cam_marca,cam_modelo,cam_cor,cam_ano_fabricacao,cam_ano_modelo,tip_cam_id,prp_id)
            values(?,?,?,?,?,?,?,?);
        ";
        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }
        $tip = $this->tipo->getId();
        $prp = $this->proprietario->getId();
        $stmt->bind_param("ssssssii", $this->placa, $this->marca, $this->modelo, $this->cor, $this->anoFabricacao, $this->anoModelo, $tip, $prp);
        if (!$stmt->execute()) {
            echo $stmt->error;
            return -10;
        }

        return $stmt->insert_id;
    }

    public function update(): int
    {
        if ($this->id <= 0 || strlen($this->placa) <= 0 || strlen($this->marca) <= 0 || strlen($this->modelo) <= 0
            || strlen($this->cor) <= 0 || strlen($this->anoFabricacao) <= 0 || strlen($this->anoModelo) <= 0
            || $this->tipo == null || $this->proprietario == null)
            return -5;

        $sql = "
            update caminhao
            set cam_placa = ?,cam_marca = ?,cam_modelo = ?,cam_cor = ?,cam_ano_fabricacao = ?,cam_ano_modelo = ?,tip_cam_id = ?,prp_id = ?
            where cam_id = ?;
        ";
        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }
        $tip = $this->tipo->getId();
        $prp = $this->proprietario->getId();
        $stmt->bind_param("ssssssiii", $this->placa, $this->marca, $this->modelo, $this->cor, $this->anoFabricacao, $this->anoModelo, $tip, $prp, $this->id);
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
            from caminhao
            where cam_id = ?;
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

    public function jsonSerialize()
    {
        $this->tipo = $this->tipo->jsonSerialize();
        $this->proprietario = $this->proprietario->jsonSerialize();

        return get_object_vars($this);
    }
}