<?php

namespace Techone\Lib\Database;

use Techone\Lib\Database\Connection;

/**
 *  Responsabilidae de servir conexões
 */
class Transaction 
{
    private static $conn;

    private function __construct()
    {
    }

    public static function openConnection()
    {
        if (empty(self::$conn)) {
            self::$conn = Connection::conectar();
            self::$conn->beginTransaction();
        }
    }

    public static function getConnection()
    {
        return self::$conn;
    }

    public static function close()
    {
        if (self::$conn) {
            self::$conn->commit();
            self::$conn = NULL;
        }
    }

    public static function rollback()
    {
        if (self::$conn) {
            self::$conn->rollback();
            self::$conn = NULL;
        }
    }
}
