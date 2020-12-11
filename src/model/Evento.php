<?php


namespace scr\model;


use mysqli_result;
use mysqli_stmt;
use scr\util\Banco;

class Evento
{
    /** @var int */
    private $id;

    /** @var string */
    private $descricao;

    /** @var string */
    private $data;

    /** @var string */
    private $hora;

    /** @var PedidoVenda|null */
    private $pedidoVenda;

    /** @var PedidoFrete|null */
    private $pedidoFrete;

    /** @var Usuario|null */
    private $autor;

    /**
     * Evento constructor.
     * @param int $id
     * @param string $descricao
     * @param string $data
     * @param string $hora
     * @param PedidoVenda|null $pedidoVenda
     * @param PedidoFrete|null $pedidoFrete
     * @param Usuario|null $autor
     */
    public function __construct(int $id = 0, string $descricao = "", string $data = "", string $hora = "", ?PedidoVenda $pedidoVenda = null, ?PedidoFrete $pedidoFrete = null, ?Usuario $autor = null)
    {
        $this->id = $id;
        $this->descricao = $descricao;
        $this->data = $data;
        $this->hora = $hora;
        $this->pedidoVenda = $pedidoVenda;
        $this->pedidoFrete = $pedidoFrete;
        $this->autor = $autor;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getDescricao(): string
    {
        return $this->descricao;
    }

    /**
     * @param string $descricao
     */
    public function setDescricao(string $descricao): void
    {
        $this->descricao = $descricao;
    }

    /**
     * @return string
     */
    public function getData(): string
    {
        return $this->data;
    }

    /**
     * @param string $data
     */
    public function setData(string $data): void
    {
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getHora(): string
    {
        return $this->hora;
    }

    /**
     * @param string $hora
     */
    public function setHora(string $hora): void
    {
        $this->hora = $hora;
    }

    /**
     * @return PedidoVenda|null
     */
    public function getPedidoVenda(): ?PedidoVenda
    {
        return $this->pedidoVenda;
    }

    /**
     * @param PedidoVenda|null $pedidoVenda
     */
    public function setPedidoVenda(?PedidoVenda $pedidoVenda): void
    {
        $this->pedidoVenda = $pedidoVenda;
    }

    /**
     * @return PedidoFrete|null
     */
    public function getPedidoFrete(): ?PedidoFrete
    {
        return $this->pedidoFrete;
    }

    /**
     * @param PedidoFrete|null $pedidoFrete
     */
    public function setPedidoFrete(?PedidoFrete $pedidoFrete): void
    {
        $this->pedidoFrete = $pedidoFrete;
    }

    /**
     * @return Usuario|null
     */
    public function getAutor(): ?Usuario
    {
        return $this->autor;
    }

    /**
     * @param Usuario|null $autor
     */
    public function setAutor(?Usuario $autor): void
    {
        $this->autor = $autor;
    }

    /**
     * @param array $row
     * @return Evento
     */
    private function rowToObject(array $row): Evento
    {
        $evento = new Evento();
        $evento->setId($row["evt_id"]);
        $evento->setDescricao($row["evt_descricao"]);
        $evento->setData($row["evt_data"]);
        $evento->setHora($row["evt_hora"]);
        $evento->setPedidoVenda($row["ped_ven_id"] ? (new PedidoVenda())->findById($row["ped_ven_id"]) : null);
        $evento->setPedidoFrete($row["ped_fre_id"] ? (new PedidoFrete())->findById($row["ped_fre_id"]) : null);
        $evento->setAutor(Usuario::getById($row["usu_id"]));

        return $evento;
    }

    /**
     * @param mysqli_result $result
     * @return Evento
     */
    private function resultToObject(mysqli_result $result): Evento
    {
        $row = $result->fetch_assoc();

        return $this->rowToObject($row);
    }

    /**
     * @param mysqli_result $result
     * @return array
     */
    private function resultToList(mysqli_result $result): array
    {
        $eventos = [];

        while ($row = $result->fetch_assoc()) {
            $eventos[] = $this->rowToObject($row);
        }

        return $eventos;
    }

    /**
     * @param int $id
     * @return Evento
     */
    public function findById(int $id): ?Evento
    {
        if ($id <= 0)
            return null;

        $sql = "
            SELECT *
            FROM evento 
            WHERE evt_id = ?;
        ";

        if (!Banco::getInstance()->prepareStatement($sql))
            return null;

        if (!Banco::getInstance()->addParameters("i", [ $id ]))
            return null;

        if (!Banco::getInstance()->executeStatement())
            return null;

        return $this->resultToObject(Banco::getInstance()->getResult());
    }

    /**
     * @param string $filter
     * @param string $date
     * @param int $type
     * @return array
     */
    public function findByFilterDateType(string $filter, string $date, int $type): array
    {
        if (strlen(trim($filter)) === 0 || strlen($date) === 0 || $type <= 0)
            return [];

        $pedido = $type === 1 ? "ped_ven_id" : "ped_fre_id";

        $sql = "
            SELECT * 
            FROM evento 
            WHERE evt_descricao LIKE ? AND evt_data = ? AND $pedido IS NOT NULL
            ORDER BY evt_id;
        ";

        if (!Banco::getInstance()->prepareStatement($sql))
            return [];

        $filtro = "%$filter%";

        if (!Banco::getInstance()->addParameters("ss", [ $filtro, $date ]))
            return [];

        if (!Banco::getInstance()->executeStatement())
            return [];

        return $this->resultToList(Banco::getInstance()->getResult());
    }

    /**
     * @param string $filter
     * @param int $type
     * @return array
     */
    public function findByFilterType(string $filter, int $type): array
    {
        if (strlen(trim($filter)) === 0 || $type <= 0)
            return [];

        $pedido = $type === 1 ? "ped_ven_id" : "ped_fre_id";

        $sql = "
            SELECT * 
            FROM evento 
            WHERE evt_descricao LIKE ? AND $pedido IS NOT NULL
            ORDER BY evt_id;
        ";

        if (!Banco::getInstance()->prepareStatement($sql))
            return [];

        $filtro = "%$filter%";

        if (!Banco::getInstance()->addParameters("s", [ $filtro ]))
            return [];

        if (!Banco::getInstance()->executeStatement())
            return [];

        return $this->resultToList(Banco::getInstance()->getResult());
    }

    /**
     * @param string $date
     * @param int $type
     * @return array
     */
    public function findByDateType(string $date, int $type): array
    {
        if (strlen($date) === 0 || $type <= 0)
            return [];

        $pedido = $type === 1 ? "ped_ven_id" : "ped_fre_id";

        $sql = "
            SELECT * 
            FROM evento 
            WHERE evt_data = ? AND $pedido IS NOT NULL
            ORDER BY evt_id;
        ";

        if (!Banco::getInstance()->prepareStatement($sql))
            return [];

        if (!Banco::getInstance()->addParameters("s", [ $date ]))
            return [];

        if (!Banco::getInstance()->executeStatement())
            return [];

        return $this->resultToList(Banco::getInstance()->getResult());
    }

    /**
     * @param int $type
     * @return array
     */
    public function findByType(int $type): array
    {
        if ($type <= 0)
            return [];

        $pedido = $type === 1 ? "ped_ven_id" : "ped_fre_id";

        $sql = "
            SELECT * 
            FROM evento 
            WHERE $pedido IS NOT NULL
            ORDER BY evt_id;
        ";

        if (!Banco::getInstance()->prepareStatement($sql))
            return [];

        if (!Banco::getInstance()->executeStatement())
            return [];

        return $this->resultToList(Banco::getInstance()->getResult());
    }

    /**
     * @param string $filter
     * @param string $date
     * @return array
     */
    public function findByFilterDate(string $filter, string $date): array
    {
        if (strlen(trim($filter)) === 0 || strlen($date) === 0)
            return [];

        $sql = "
            SELECT * 
            FROM evento 
            WHERE evt_descricao LIKE ? AND evt_data = ? 
            ORDER BY evt_id;
        ";

        if (!Banco::getInstance()->prepareStatement($sql))
            return [];

        $filtro = "%$filter%";

        if (!Banco::getInstance()->addParameters("ss", [ $filtro, $date ]))
            return [];

        if (!Banco::getInstance()->executeStatement())
            return [];

        return $this->resultToList(Banco::getInstance()->getResult());
    }

    /**
     * @param string $filter
     * @return array
     */
    public function findByFilter(string $filter): array
    {
        if (strlen(trim($filter)) === 0)
            return [];

        $sql = "
            SELECT * 
            FROM evento 
            WHERE evt_descricao LIKE ? 
            ORDER BY evt_id;
        ";

        if (!Banco::getInstance()->prepareStatement($sql))
            return [];

        $filtro = "%$filter%";

        if (!Banco::getInstance()->addParameters("s", [ $filtro ]))
            return [];

        if (!Banco::getInstance()->executeStatement())
            return [];

        return $this->resultToList(Banco::getInstance()->getResult());
    }

    /**
     * @param string $date
     * @return array
     */
    public function findByDate(string $date): array
    {
        if (strlen($date) === 0)
            return [];

        $sql = "
            SELECT * 
            FROM evento 
            WHERE evt_data = ? 
            ORDER BY evt_id;
        ";

        if (!Banco::getInstance()->prepareStatement($sql))
            return [];

        if (!Banco::getInstance()->addParameters("s", [ $date ]))
            return [];

        if (!Banco::getInstance()->executeStatement())
            return [];

        return $this->resultToList(Banco::getInstance()->getResult());
    }

    /**
     * @return array
     */
    public function findAll(): array
    {
        $sql = "
            SELECT *
            FROM evento 
            ORDER BY evt_id;
        ";

        return $this->resultToList(Banco::getInstance()->getResultQuery($sql));
    }

    /**
     * @return int
     */
    public function save(): int
    {
        if (
            $this->id != 0 ||
            strlen(trim($this->descricao)) === 0 ||
            strlen(trim($this->data)) === 0 ||
            strlen(trim($this->hora)) === 0 ||
            $this->autor === null
        )
            return -5;

        $sql = "
            INSERT 
            INTO evento (evt_descricao, evt_data, evt_hora, ped_ven_id, ped_fre_id, usu_id) 
            VALUES (?,?,?,?,?,?);
        ";

        /** @var $stmt mysqli_stmt */
        $stmt = Banco::getInstance()->getConnection()->prepare($sql);
        if (!$stmt) {
            echo Banco::getInstance()->getConnection()->error;
            return -10;
        }

        $pedven = $this->pedidoVenda ? $this->pedidoVenda->getId() : null;
        $pedfre = $this->pedidoFrete ? $this->pedidoFrete->getId() : null;
        $autor = $this->autor->getId();

        $stmt->bind_param(
            "sssiii",
            $this->descricao,
            $this->data,
            $this->hora,
            $pedven,
            $pedfre,
            $autor
        );

        if (!$stmt->execute()) {
            echo $stmt->error;
            return -10;
        }

        return $stmt->insert_id;
    }

    /**
     * @return int
     */
    public function delete(): int
    {
        if ($this->id <= 0)
            return -5;

        $sql = "
            DELETE 
            FROM evento 
            WHERE evt_id = ?;
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

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        $pedidoVenda = $this->pedidoVenda ? $this->pedidoVenda->jsonSerialize() : null;
        $pedidoFrete = $this->pedidoFrete ? $this->pedidoFrete->jsonSerialize() : null;
        $autor = $this->autor->jsonSerialize();

        return [
            "id" => $this->id,
            "descricao" => $this->descricao,
            "data" => $this->data,
            "hora" => $this->hora,
            "pedidoVenda" => $pedidoVenda,
            "pedidoFrete" => $pedidoFrete,
            "autor" => $autor
        ];
    }
}