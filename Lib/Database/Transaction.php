<?php

namespace Techone\Lib\Database;

use Exception;
use PDO;
use PDOException;
use Techone\Lib\Database\Connection;

/**
 *  Responsabilidae de servir conexões
 */
class Transaction 
{
    /** @var PDO conn */
    private static $conn;

    private function __construct()
    {
    }

    /**
     *  Atribui uma conexão à variável conn
     * 
     * @throws PDOException Se o retorno não for uma conexão PDO
     */
    public static function openConnection()
    {
        if (empty(self::$conn)) {
            if ((self::$conn = Connection::conectar()) instanceof PDO) 
                self::$conn->beginTransaction();
            else
                throw new PDOException('Erro ao conectar com o banco de dados.');
        }
    }

    /**
     *  Retorna a conexão
     * 
     * @return PDO conn
     */
    public static function getConnection()
    {
        return self::$conn;
    }

    /**
     *  Fecha a conexão
     */
    public static function close()
    {
        if (self::$conn) {
            self::$conn->commit();
            self::$conn = NULL;
        }
    }

    /**
     * Desfaz alterações em caso de falha
     */
    public static function rollback()
    {
        if (self::$conn) {
            self::$conn->rollback();
            self::$conn = NULL;
        }
    }
}
