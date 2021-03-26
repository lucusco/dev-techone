<?php

namespace Techone\Lib\Model;

use PDO;
use PDOException;
use Techone\Lib\Model\Ramal;
use Techone\Lib\Database\Connection;
use Techone\Lib\Controller\FilaControl;

class RamalEmFila
{
    const TABLENAME = 'extensions_queues';

    public $idFila;
    public $ramaisNaFila = array();
    public $ramaisPorId = array();

    public function __construct($filaId)
    {
        $this->idFila = $filaId;
    }

    /**
     *  Popula o atributo de ramais na fila com objetos de Ramal
     *
     * @param array $ramais ID dos ramais a serem inseridos na fila
     */
    public function saveQueueExtens(array $ramais)
    {
        foreach ($ramais as $idExten) {
            $ramalAux = Ramal::loadById($idExten);
            if (empty($ramalAux)) continue;
            $this->ramaisNaFila[] = $ramalAux;
            $this->ramaisPorId[]  = $ramalAux->id;
        }
        if (!empty($this->ramaisNaFila)) {
            return $this->saveExtensions();
        }
    }

    /**
     * Salva os ramais que fazem parte da fila
     *
     * @return bool true|false
     */
    private function saveExtensions(): bool
    {
        //var_dump($this); die;
        try {
            $conn = Connection::getConnection();
            $conn->exec("DELETE FROM " . self::TABLENAME . " WHERE id_queue = {$this->idFila}");
            $query = "INSERT INTO " . self::TABLENAME . " (id_exten, id_queue) VALUES (?, ?)";
            foreach ($this->ramaisNaFila as $key => $exten) {
                $stmt = $conn->prepare($query);
                $stmt->bindValue(1, $exten->id);
                $stmt->bindValue(2, $this->idFila);
                $stmt->execute();
            }
            return true;
        } catch (PDOException $e) {
            FilaControl::renderizaErro($e->getMessage());
        }
    }

    /**
     * Busca os ramais que foram inseridos na fila
     * 
     *  @return array|null ramais
     */
    public function loadExtens()
    {
        try {
            $query = 'SELECT id_exten FROM ' . self::TABLENAME . ' WHERE id_queue = :idfila';
            $conn = Connection::getConnection();
            $stmt = $conn->prepare($query);
            $stmt->bindValue(':idfila', $this->idFila);
            $stmt->execute();
            while ($exten = $stmt->fetch(PDO::FETCH_NUM)) {
                $ramal = Ramal::loadById($exten[0]);
                if (empty($ramal)) continue;
                $this->ramaisNaFila[] = $ramal;
                $this->ramaisPorId[]  = $ramal->id;
            }
        } catch (PDOException $e) {
            FilaControl::renderizaErro($e->getMessage());
        }    
    }
}
