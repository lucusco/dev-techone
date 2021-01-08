<?php

namespace Techone\Lib\Api;

use PDO;
use DomainException;
use Techone\Lib\Database\Connection;

/**
 *  Layer Supertype Pattern - baseada em Active Record\
 *  Fornece os recursos necessários para persistência de um objeto no DB\
 *  Classes de objetos persistenetes devem herdá-la
 */
abstract class DataRecord
{
    /**
     *  Obtém o nome da entidade sendo manipulada
     */
    private function getEntity()
    {
        $class = get_class($this);
        return constant("{$class}::TABLENAME");
    }

    /**
     *  Persiste o objeto no banco de dados
     *  @param $object  Array com os dados do objeto
     *  @return boll  PDO::exec retorna um inteiro com a quantidade de linhas afetadas 
     *  @throws DomainException Para casos em que não foi possível obter a conexão
     */
    protected function store(array $object)
    {
        $object = self::prepare($object);

        if (!isset($object['id'])) { // INSERT
            $object['id'] = $this->getProximoId();
            $sql = "INSERT INTO {$this->getEntity()}" .
                '(' . implode(', ', array_keys($object)) . ')' .
                ' VALUES ' .
                '(' . implode(', ', array_values($object)) . ')';

        } else { //UPDATE
            $sql = "UPDATE {$this->getEntity()} ";
            foreach ($object as $column => $value) {
                $sets[] = "$column = $value";
            }
            $sql .= 'SET ' . implode(', ', $sets);
            $sql .= "WHERE id = {$object['id']}";
        }

        if ($conn = Connection::getConnection()) {
            $result = $conn->exec($sql);
            return $result;
        } else {
            throw new DomainException('Não foi possível obter a conexão');
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
    public function load(int $id = null, string $coluna = '*', string $order = 'id')
    {
        $sql = isset($id) ? "SELECT $coluna FROM {$this->getEntity()} WHERE id = $id" : "SELECT $coluna FROM {$this->getEntity()} ORDER BY $order";
        
        if ($conn = Connection::getConnection()) {
            $stmt = $conn->query($sql);
            $tipoFetch = is_numeric($id) ? 'fetch' : 'fetchAll';
            return $stmt->$tipoFetch(PDO::FETCH_OBJ);
        }   
    }

    /**
     * Obtém proxímo ID a ser utilizado
     *
     * @return int
     */
    private function getProximoId(): int
    {
        $conn = Connection::getConnection();
        $stmt = $conn->query("SELECT COALESCE(max(id), 0) AS ultimo FROM {$this->getEntity()}");
        $result = $stmt->fetch();
        return $result['ultimo'] + 1;
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
                    $prepared[$key] = "'$value'";
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
     * @param int $id ID do objeto
     * @return true|false 
     */
    public function jaExiste($coluna, $valor, $id)
    {        
        $sql = "SELECT id FROM {$this->getEntity()} WHERE $coluna = $valor";
        $conn = Connection::getConnection();
        $stmt = $conn->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Validações com base no id retornado
        if (!$result) return false;
        else if (count($result) > 1) return true;
        else if ($result[0]['id'] != $id) return true;
        else return false;
    }
}
