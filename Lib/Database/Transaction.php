<?php

namespace Techone\Lib\Database;

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
     */
    public static function openConnection()
    {
        if (empty(self::$conn)) {
            self::$conn = Connection::conectar();
            self::$conn->beginTransaction();
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
