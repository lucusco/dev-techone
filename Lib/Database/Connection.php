<?php

namespace Techone\Lib\Database;

use Exception;
use PDO;
use PDOException;

/**
 *  Classe responsável por fazer a conexão com o banco e retorná-la
 */
class Connection
{
    /** @var PDO conn */
    public static $conn;
    public static $msgFail;

    private function __construct()
    {
    }

    /**
     * Faz o parse das informações de conexão com o banco e a retorna a conexão ou false em caso de falha
     *
     * @return PDO|null
     */
    private static function conectar(): ?PDO
    {
        $dados = parse_ini_file(BASE_DIR . 'Config/techone.ini');
        extract($dados);

        if (empty($host) || empty($user) || empty($password) || empty($dbname) || empty($port) || empty($type)) {
            throw new Exception("Erro ao conectar com o banco de dados, contate o Luis");
        }
        try {
            switch ($type) {
                case 'pgsql':
                    $connection = new PDO("pgsql:host={$host};dbname={$dbname};port={$port}", $user, $password);
                    break;
                case 'mysql':
                    // @TODO
                    break;
            }
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $connection->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
            //$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            return $connection;
        } catch (PDOException $e) {
            self::erroPDO($e);
            return null;
        }
    }

    private static function openConnection()
    {
        if (empty(self::$conn)) {
            self::$conn = self::conectar();
            if (!self::$conn instanceof PDO) 
                throw new PDOException(self::$msgFail);
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
     *  Inicia transação
     */
    public static function begin()
    {
        if (self::$conn) {
            self::$conn->beginTransaction();
        }
    }

    /**
     *  Commitar alterações feitas via transação
     */
    public static function close()
    {
        if (self::$conn) {
            self::$conn->commit();
            self::$conn = NULL;
        }
    }

    /**
     * Desfaz alterações em caso de falha em transação
     */
    public static function rollback()
    {
        if (self::$conn) {
            self::$conn->rollback();
            self::$conn = NULL;
        }
    }


    private static function erroPDO(PDOException $e)
    {
        $erro = "Mensagem: {$e->getMessage()}\n".
        "Arquivo: {$e->getFile()}\n".
        "Linha: {$e->getLine()}";

        self::$msgFail = $erro;
    }
}
