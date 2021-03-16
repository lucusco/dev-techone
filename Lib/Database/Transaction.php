<?php

namespace Techone\Lib\Database;

use PDO;

/**
 *  Responsabilidae de servir conexões
 */
class Transaction 
{
    /** @var PDO conn */
    private static $conn;

    /**
     *  Inicia transações
     */
    public static function begin()
    {
        if (self::$conn) {
            self::$conn->beginTransaction();
        }
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
