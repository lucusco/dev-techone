<?php

namespace Techone\Lib\Controller;

use PDO;
use Techone\Lib\Database\Transaction;

class TesteConn
{
    public function testarConexao()
    {
        Transaction::openConnection();
        //Transaction::();

        /** $conn PDO */
        $conn = Transaction::getConnection();

        $stmt = $conn->query('SELECT * from extensions');
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        var_dump($result);

        Transaction::close();
    }

    /**
     *  Teste de métodos pelo sistema
     */
    public function testarMetodo()
    {
        //var_dump($_POST); die();
        $conexao = $_POST['conexao'] == 'sim' ? 'sim' : NULL;
        if ($conexao)
            Transaction::openConnection();

        // Chamar o métoto que quer testar  
        var_dump(call_user_func(
            array($_POST['namespace'] . $_POST['classe'], $_POST['metodo'])
        ));

        if ($conexao)
            Transaction::close();
    }
}
