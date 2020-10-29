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
    private function __construct()
    {
    }

    /**
     * Faz o parse das informações de conexão com o banco e a retorna a conexão ou false em caso de falha
     *
     * @return PDO|false
     */
    public static function conectar()
    {
        $dados = parse_ini_file(BASE_DIR . 'Config/techone.ini');
        extract($dados);

        if (empty($host) || empty($user) || empty($password) || empty($dbname) || empty($port) || empty($type)) {
            //Erro
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
            //$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            return $connection;
        } catch (Exception $e) {
            return false;
        }
    }
}
