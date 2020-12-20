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
    public static function openConnection() //TODO mudar para private após refactoring
    {
        if (empty(self::$conn)) {
            self::$conn = Connection::conectar();
            if (!self::$conn instanceof PDO) 
                throw new PDOException('Erro ao conectar com o banco de dados.');
        }
        return self::$conn;
    }

    /**
     *  Retorna a conexão
     * 
     * @return PDO conn
     */
    public static function getConnection()
    {
        if (empty(self::$conn)) {
            self::$conn = self::openConnection();
        }
        return self::$conn;
    }

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
