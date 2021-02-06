<?php

namespace Techone\Lib\Model;

use PDO;
use PDOException;
use DomainException;
use Techone\Lib\Model\Ramal;
use Techone\Lib\Api\DataRecord;
use Techone\Lib\Database\Connection;
use Techone\Lib\Helper\ModelFunctionsTrait;

class Fila extends DataRecord
{
    use ModelFunctionsTrait;

    const TABLENAME = 'queues';

    private $id;
    private $number;
    private $description;
    private $strategy;
    private $extensions = array();

    /**
     *  Getter and Setters
     */
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getNumber()
    {
        return $this->number;
    }

    public function setNumber($number)
    {
        if (!is_int($number))
            throw new DomainException('Entrada deve ser um número inteiro!');
        $this->number = $number;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        if (empty($description))
            throw new DomainException('Descrição não pode estar em branco!');
        $this->description = filter_var($description, FILTER_SANITIZE_STRING);
    }

    public function getStrategy()
    {
        return $this->strategy;
    }

    public function setStrategy($strategy)
    {
        if (empty($strategy) || !in_array($strategy, array('random', 'linear', 'ringall')))
            throw new DomainException("Estratégia $strategy não reconhecida!");
        $this->strategy = $strategy;
    }

    public function getExtensions()
    {
        return $this->extensions;
    }

    /**
     * Salva os ramais selecionados no respectivo atributo da classe
     *
     * @param array $extension Array de ramais 
     */
    public function setExtensions($extension)
    {
        if (!is_array($extension) || (count($extension) < 1))
            throw new DomainException('Não foram informados ramais para a fila!');
        
        foreach ($extension as $exten) {
            if (!$this->extensionExists(intval($exten))) {
                //TODO informar que algum ramal não será salvo por não existir
                continue;
            }
            $this->extensions[] = intval($exten);
        }
    }

    /**
     * Verifica se o ramal realmente existe antes de tentar salvá-lo na fila
     *
     * @param int $exten Ramal a ser verificado
     * @return bool
     */
    private function extensionExists($exten): bool
    {
        $conn = Connection::getConnection();
        $stmt = $conn->query("SELECT id FROM extensions WHERE id = {$exten}");
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        $ret = $result ? true : false;
        return $ret;
    }

    /**
     * Salva os ramais que fazem parte da fila
     *
     * @return bool true|false
     */
    public function saveExtensions(): bool
    {
        try {
            $conn = Connection::getConnection();
            $conn->exec("DELETE FROM extensions_queues WHERE id_queue = {$this->getId()}");
            $query = "INSERT INTO extensions_queues (id_exten, id_queue) VALUES (?, ?)";
            foreach ($this->extensions as $extension) {
                $stmt = $conn->prepare($query);
                $stmt->bindValue(1, $extension);
                $stmt->bindValue(2, $this->id);
                $stmt->execute();
            }
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     *  Salva a fila no BD
     */
    public function save()
    {
        $data = $this->toArray();

        try {
            $successSaveQueue = $this->store($data); 
            if ($successSaveQueue) {
                $successSaveExten = $this->saveExtensions();
                if ($successSaveExten) {
                    return true;
                }
            }
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Busca todas as filas no BD e as retorna
     *
     * @return array|null
     */
    public function loadAll(): ?array
    {
        $filas = $this->load();
        if (empty($filas)) {
            return null;
        }
        return $filas;
    }

    /**
     * Carrega a fila do BD
     *
     * @param int $id ID da fila
     * @return mixed Array com os dados da fila
     */
    public function loadQueue($id)
    {   
        if ($id) {
            try {
                $fila = $this->load($id);
                $fila->extensions = $this->loadExtens($id);
                return $fila;
                var_dump($fila);

            }catch (PDOException $e) {
                var_dump($e->getMessage()); die;
                //RamalControl::renderizaErro($e->getMessage());
            }
        }
    }

    /**
     * Busca os ramais que foram inseridos nessa fila
     * 
     *  @return array|null ramais
     */
    private function loadExtens($idFila): ?array
    {
        $extens = array();
        $query = "SELECT id_exten FROM extensions_queues WHERE id_queue = '$idFila'";
        $conn = Connection::getConnection();
        $stmt = $conn->query($query);
        while ($exten = $stmt->fetch(PDO::FETCH_NUM)) {
            $extens[] = $exten[0];
        }
        $ret = $extens ?? null;
        return $ret;
    }

    /**
     * Monta o combo de ramais utilizado ao renderizar a adição/edição de filas
     *
     * @return array
     */
    public static function getComboExtens(): array
    {
        $ramaisDisponiveis = Ramal::todosRamais(null ,'exten');
        $comboRamais = array();
        foreach ($ramaisDisponiveis['ramais'] as $r) {
            $ramal = new \stdClass;
            $ramal->id = $r->id;
            $ramal->descricao = "{$r->exten} - {$r->username}";
            $comboRamais[] = $ramal;
        }
        return $comboRamais;
    }

    /**
     * Remove uma fila do BD
     *
     * @param int $id ID do ramal a ser removido
     */
    public static function removerFila(int $id)
    {
        try {
            /** @var \PDO conn */
            $conn = Connection::getConnection();
            $sql = "DELETE FROM extensions_queues WHERE id_queue = {$id};";
            $sql .= "DELETE FROM queues WHERE id = {$id}";
            $result = $conn->exec($sql);
            $ret = ($result > 0) ? true : false;
            return $ret;
        } catch (PDOException $e) {
            echo $e->getMessage(); die;
        }
    }
}
