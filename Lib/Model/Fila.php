<?php

namespace Techone\Lib\Model;

use PDO;
use PDOException;
use DomainException;
use Techone\Lib\Model\Ramal;
use Techone\Lib\Api\DataRecord;
use Techone\Lib\Controller\FilaControl;
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

    public function setId($id = 0)
    {
        if ($id == 0) {
            $id = $this->getProximoId();
        }
        $this->id = $id;
    }

    public function getNumber()
    {
        return $this->number;
    }

    public function setNumber($number)
    {
        if (!is_int($number) || empty($number))
            throw new DomainException('Entrada deve ser um número inteiro!');
        if ($this->jaExiste('number', $number) === true)
            throw new DomainException('Entrada informada já existe!');

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
    public function setExtensions($data)
    {
        if (!isset($data->ramais))
            throw new DomainException('Selecione no mínimo 1 ramal para a fila');

        if (count($data->ramais) < 1)
            throw new DomainException('Não foram informados ramais para a fila!');
        
        $this->extensions = $data->ramais;
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
                $ramaisEmFila = new RamalEmFila($this->id);
                $successSaveExten = $ramaisEmFila->saveQueueExtens($this->extensions);
                if ($successSaveExten) {
                    $this->extensions = $ramaisEmFila;
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
        return $this->load() ?? null;
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
                $ramaisFila = new RamalEmFila($id);
                $ramaisFila->loadExtens();
                $fila->extensions = $ramaisFila;
                return $fila;

            }catch (PDOException $e) {
                FilaControl::renderizaErro($e->getMessage());
            }
        }
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
            return ($result > 0) ? true : false;
        } catch (PDOException $e) {
            echo $e->getMessage(); die;
        }
    }
}
