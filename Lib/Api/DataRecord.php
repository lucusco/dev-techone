<?php

namespace Techone\Lib\Api;

use PDO;
use PDOException;
use Techone\Lib\Database\Connection;

/**
 *  Layer Supertype Pattern - baseada em Active Record\
 *  Fornece os recursos necessários para persistência de um objeto no DB\
 *  Classes de objetos persistenetes devem herdá-la
 */
abstract class DataRecord
{

    abstract function setId($id);
    abstract function getId();

    /**
     *  Obtém o nome da tabela do banco de dados a ser manipulada
     */
    private function getEntity()
    {
        $class = get_class($this);
        return constant("{$class}::TABLENAME");
    }

    /**
     *  Persiste o objeto no banco de dados
     *  @param $object  Array com os dados do objeto
     *  @return object|null Retorna o objeto com o id setado ou null em caso de falha
     */
    protected function store(array $object): bool
    {
        $object = self::prepare($object);

        if ($this->loadFromId() === false) { // INSERT    
            $columns = implode(', ', array_keys($object));
            foreach ($object as $column => $value) {
                $valores[] = ":$column";
            }

            $query = "INSERT INTO {$this->getEntity()} ({$columns}) VALUES (" . implode(', ', $valores). ")"; 

        } else { //UPDATE
            $id = array_shift($object);
            foreach ($object as $column => $value) {
                $sets[] = "$column = :$column";
            }

            $query = "UPDATE {$this->getEntity()} SET " . implode(', ', $sets) . " WHERE id ={$id}";
        }

        try {
            $conn = Connection::getConnection();
            $stmt = $conn->prepare($query);
            foreach ($object as $column => $value) {
                $tipo = is_numeric($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
                $stmt->bindValue(":$column", $value, $tipo);
            }
            return $stmt->execute();


        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Carrega dados (objetos) do banco\
     * Possibilidade de procurar por valores específicos
     *
     * @param int $id Id do objeto
     * @param string $coluna Coluna a ser pesquisada
     * @param string $order Ordenação
     */
    protected function load(int $id = null, string $coluna = '*', string $order = 'id')
    {
        $sql = isset($id) ? "SELECT $coluna FROM {$this->getEntity()} WHERE id = $id" : "SELECT $coluna FROM {$this->getEntity()} ORDER BY $order";
        try {
            $conn = Connection::getConnection();
            $stmt = $conn->query($sql);
            $tipoFetch = is_numeric($id) ? 'fetch' : 'fetchAll';
            return $stmt->$tipoFetch(PDO::FETCH_OBJ);

        } catch (PDOException $e) {
            return $e->getMessage();
        }
         
    }

    protected function loadFromId()
    {
        try {
            $conn = Connection::getConnection();
            $sql = "SELECT * FROM {$this->getEntity()} WHERE id = {$this->getId()}";
            return $conn->query($sql)->fetchObject(get_class($this));

        } catch (PDOException $e) {
            return $e->getMessage();
        }
        /* Forma alternativa */
        //$stmt = $conn->query($sql);
        //$stmt->setFetchMode(PDO::FETCH_CLASS, get_class($this));
        //return $stmt->fetch();
    }

    /**
     * Obtém proxímo ID a ser utilizado
     *
     * @return int
     */
    protected function getProximoId(): int
    {
        try {
            $conn = Connection::getConnection();
            $stmt = $conn->query("SELECT nextval('" . $this->getEntity() . "_id_seq') AS proximo");
            return $stmt->fetch()['proximo'];

        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Prepara os dados antes de serem inseridos na base
     *
     * @param array $data Dados a serem preparados
     * @return array
     */
    protected static function prepare(array $data): array
    {
        $prepared = array();
        foreach ($data as $key => $value) {
            if (is_scalar($value)) { // Scalar variables are those containing an integer, float, string or boolean. 
                if (is_string($value) && !empty($value)) {
                    $value = addslashes($value);
                    $prepared[$key] = "$value";
                } else if (is_bool($value)) {
                    $prepared[$key] = $value ? "'true'" : "'false'";
                } else if ($value !== '') {
                    $prepared[$key] = $value;
                } else {
                    $prepared[$key] = NULL;
                }
            }
        }
        return $prepared;
    }

    /**
     *  Validar se determinado valor existe na coluna especificada.
     *
     * @param string $coluna Coluna do BD a ser vasculhada
     * @param mixed $valor Valor procurado
     * @return true|false 
     */
    protected function jaExiste($coluna, $valor): bool
    {   
        try{
            $sql = "SELECT id FROM {$this->getEntity()} WHERE $coluna = '$valor' AND id != {$this->getId()}";
            $conn = Connection::getConnection();
            $stmt = $conn->query($sql);

            return  $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;

        } catch (PDOException $e) {
            return $e->getMessage();
        }     
    }
}
