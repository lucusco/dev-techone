<?php

namespace Techone\Lib\Model;

use PDO;
use DomainException;
use PDOException;
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

    private function extensionExists($exten): bool
    {
        $conn = Connection::getConnection();
        $stmt = $conn->query("SELECT id FROM extensions WHERE id = {$exten}");
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        $ret = $result ? true : false;
        return $ret;
    }

    public function saveExtensions(): bool
    {
        try {
            $conn = Connection::getConnection();
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
}
