<?php

namespace Techone\Lib\Model;

use PDO;
use Exception;
use PDOException;
use DomainException;
use Techone\Lib\Conf\Asterisk;
use Techone\Lib\Api\DataRecord;
use Techone\Lib\Database\Connection;
use Techone\Lib\Controller\RamalControl;
use Techone\Lib\Helper\ModelFunctionsTrait;

/**
 *  Classe para manipular os ramais
 */
class Ramal extends DataRecord
{
    use ModelFunctionsTrait;

    const TABLENAME = 'extensions';

    private $id;
    private $exten;
    private $username;
    private $secret;
    private $context;
    private $tech;
    private $recording;
    private static $lastUploadedCsv;

    public function __construct()
    {
    }

    //Setters e getters
    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getExten()
    {
        return $this->exten;
    }

    public function setExten($exten)
    {   
        if (empty($exten) || $exten == '0') throw new Exception('Número do Ramal deve ser informado');
        else if (!is_numeric($exten)) throw new Exception('Número do Ramal deve ser numérico');
        else if (!is_int($exten + 0)) throw new Exception('Número do ramal inválido');
        else if ($this->jaExiste('exten', $exten, $this->id) === true) throw new Exception('Ramal informado já existe');
        else  $this->exten = $exten;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        if (empty($username)) 
            throw new Exception('Descrição deve ser informada');
        else {
            $username = filter_var($username, FILTER_SANITIZE_STRING);
            $this->username = $username;
        }
    }

    public function getSecret()
    {
        return $this->secret;
    }

    public function setSecret($secret)
    {
        if (empty($secret) || strlen($secret) < 4) 
            throw new Exception('Senha deve ser informada e deve ter mais pelo menos 4 caracteres');
        else {
            $secret = filter_var($secret, FILTER_SANITIZE_STRING);
            $this->secret = $secret;
        }
    }

    public function getContext()
    {
        return $this->context;
    }

    public function setContext($context)
    {
        if (empty($context)) 
            throw new Exception('Context deve ser informado');
        else {
            $context = filter_var($context, FILTER_SANITIZE_STRING);
            $this->context = strtolower($context);
        }
    }

    public function getTech()
    {
        return $this->tech;
    }

    public function setTech($tech)
    {
        if (empty($tech)) 
            throw new Exception('Tecnologia deve ser informada');
        else {
            $tech = filter_var($tech, FILTER_SANITIZE_STRING);
            $this->tech = strtoupper($tech);
        }
    }

    public function getRecording()
    {
        return $this->recording;
    }

    public function setRecording($recording = '')
    {
        if (empty($recording) && !isset($_POST['gravacao'])) {
            throw new Exception('Gravação deve ser informada');
        } else {
            $recording = !empty($recording) ? $recording : $_POST['gravacao'];
        }
        
        if (!in_array($recording, array('sim', 'nao', 's', 'n', 'não', 'on', 'off'))) {
            throw new Exception('Gravação não especificada. Tipos permitidos são: sim, nao, s, n, não');
        } else {
            strtolower($recording) == 'sim' || strtolower($recording) == 's' || $_POST['gravacao'] == 'on' ? $this->recording = 'true' : $this->recording = 'false'; 
        }
    }

    /**
     * Carrega um objeto ramal do BD
     *
     * @param $id ID do ramal a ser carregado
     */
    public function carregarRamal($id = null)
    {
        try {
            if ($id) {
                $ramal = $this->load($id);
                return $ramal;
            }
        } catch (PDOException $e) {
            RamalControl::renderizaErro($e->getMessage());
        }
    }

    /**
     * Consulta de ramais dno BD, possibilidade de paginação de resultados
     *
     * @param int $pagina Se enviada, a consulta será paginada
     */
    public static function todosRamais(int $pagina=NULL)
    {
        try {

            /** @var \PDO conn */
            $conn = Connection::getConnection();

            if ($pagina) { //consulta com paginacao
                $busca = "SELECT * FROM extensions ORDER BY id";
                $regPorPagina = 10;
                
                $inicio = $pagina - 1;
                $inicio = $inicio * $regPorPagina;
        
                $limit = "$busca LIMIT $regPorPagina OFFSET $inicio";

                $stmt = $conn->query($limit);
                $result = $stmt->fetchAll(PDO::FETCH_OBJ);

                $stmt = $conn->query("SELECT count(*) AS total FROM extensions");
                $todos = $stmt->fetch(PDO::FETCH_ASSOC);

                $totalPaginas = ceil($todos['total'] / $regPorPagina);

                $retorno = [
                    'totalPaginas' => $totalPaginas,
                    'ramais' => $result
                ];
            } else { //consulta sem paginação
                $stmt = $conn->query("SELECT * FROM extensions ORDER BY id");
                $retorno['ramais'] = $stmt->fetchAll(PDO::FETCH_OBJ);
            }

            return $retorno;

        } catch (PDOException $e) {
            RamalControl::renderizaErro($e->getMessage());
        }
    }

    /**
     * Persiste o ramal no BD
     *  
     */
    public function persistir()
    {
        try {
            $result = $this->store($this->toArray());
            if ($result) {
                Asterisk::escreveConfRamais();
            }
            return $result;
        } catch (PDOException $e) {
            RamalControl::renderizaErro($e->getMessage());
        } catch (DomainException $e) {
            print $e->getMessage();
        }
    }

    /**
     * Remove um ramal do BD
     *
     * @param int $id ID do ramal a ser removido
     */
    public static function removerRamal(int $id)
    {
        try {
            /** @var \PDO conn */
            $conn = Connection::getConnection();
            $sql = "DELETE FROM extensions WHERE id = {$id}";
            $result = $conn->exec($sql);
            return $result;
        } catch (PDOException $e) {
            RamalControl::renderizaErro($e->getMessage());
        }
    }

    /**
     *  Faz a leitura do arquivo CSV e retorna os dados em um array de objetos
     *
     * @param string $filename Nome completo do arquivo
     * @return array $data  Array de objetos com os ramais a serem importados
     */
    private static function preparaCsv($files)
    {
        if (!isset($files['arquivo']))
            return 'Erro, o nome do campo recebido é inválido';

        $infoArquivo = (object)$files['arquivo'];
        $tiposPermitidos = array('text/csv', 'text/plain');
        $cabecalhoPermitido = array('ramal', 'nome', 'senha', 'gravar', 'contexto', 'tipo');
        $cabecalho = NULL;
        $dadosRamais = array();

        // Validações
        if ((int)$infoArquivo->error == 2 || filesize($infoArquivo->tmp_name) > 3000000) return 'O tamanho do arquivo excede o máximo permitido! (3Mb)';
        if ($infoArquivo->size <= 0 || empty($infoArquivo->name))                        return 'Arquivo não detectado, por favor selecione-o novamente.';
        if ((int)$infoArquivo->error != 0)                                               return 'O arquivo importado apresentou erro durante a importação!';
        if (!in_array(mime_content_type($infoArquivo->tmp_name), $tiposPermitidos))      return 'Tipo de arquivo não reconhecido, apenas .csv.';

        $uploadedPath = BASE_DIR . 'Files/Upload/' . date('d-m-y_H:i:s') . '_' . basename($infoArquivo->name);
        self::$lastUploadedCsv = $uploadedPath;

        // Manipulação do arquivo
        if (move_uploaded_file($infoArquivo->tmp_name, $uploadedPath)) {
            if (($file = fopen($uploadedPath, 'r')) !== FALSE) {
                while (($linha = fgetcsv($file, 500, ';')) !== FALSE) {
                    if (!$cabecalho) {
                        foreach ($linha as $l) {
                            if (!in_array(strtolower($l), $cabecalhoPermitido))
                                return "O cabeçalho $l não é permitido, por gentileza realize o ajuste.";

                            // Adequar colunas do arquivo às colunas do banco
                            switch (strtolower($l)) {
                                case 'ramal':    $cabecalho[] = 'exten';     break;
                                case 'nome':     $cabecalho[] = 'username';  break;
                                case 'senha':    $cabecalho[] = 'secret';    break; 
                                case 'gravar':   $cabecalho[] = 'recording'; break;
                                case 'contexto': $cabecalho[] = 'context';   break;
                                case 'tipo':     $cabecalho[] = 'tech';      break;
                            }
                        }
                    } else {
                        foreach ($linha as $dado) {
                            if (empty($dado)) return "Há valores em branco na planilha, todos os campos devem ser preenchidos!";
                        }
                        $dadosRamais[] = array_combine($cabecalho, $linha);
                    }
                }
                fclose($file);
            }
        }
        // Transformar array de objetos Ramal
        if (count($dadosRamais) > 0) {
            $ramaisImportar = array();
            $id = self::proximoId();
            foreach ($dadosRamais as $ramal) { //TODO tratar erros
                $obj = new Ramal();
                try {
                    $obj->setId($id);
                    $obj->setExten($ramal['exten']);
                    $obj->setUsername($ramal['username']);
                    $obj->setSecret($ramal['secret']);
                    $obj->setContext($ramal['context']);
                    $obj->setTech($ramal['tech']);
                    $obj->setRecording($ramal['recording']);
                } catch (Exception $e) {
                    // TODO: Fazer uma contagem daquele que nao foram importados
                    continue;
                }
                $ramaisImportar[] = $obj;
                $id++;
            }
        }
        return $ramaisImportar;
    }

    /**
     *  Exporta todos os ramais para um arquivo .csv\
     *  Possibilita exportar um arquivo de exemplo
     * 
     *  @param bool $exemplo Indica se deve ser gerado um arquivo de exemplo
     *  @return false|string Retorna false caso não existam ramais; Retorna o path do arquivo em caso de sucesso
     */
    public static function exportarCsv($exemplo = false)
    {
        if (!$exemplo) {
            $ramais = self::todosRamais();
            if (!count($ramais['ramais']) > 0) return false;
        }
        
        $filename = DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . date('H:i:s_') . 'ramais.csv';
        $file = fopen($filename, 'x+');

        $cabecalho = $exemplo == true ? ['Ramal', 'Nome', 'Contexto', 'Tipo', 'Gravar', 'Senha'] :  ['ID', 'Ramal', 'Nome', 'Contexto', 'Tipo', 'Gravar', 'Senha'];
        fputcsv($file, $cabecalho, ';', '"');

        if ($exemplo) {
            $dadosExemplo = array('1363', 'Daniela', 'interno', 'SIP', 'Sim', '12#3Eds@');
            fputcsv($file, $dadosExemplo, ';', '"');
        } else {
            foreach ($ramais['ramais'] as $ramal) {
                $ramal = (array)$ramal;
                $ramal['recording'] = $ramal['recording'] == 'true' ? 'Sim' : 'Não';
                fputcsv($file, $ramal, ';', '"');
            }
        }
        fclose($file);
        return $filename;
    }

    /**
     *  Deleta o arquivo que foi feito upload caso tenha ocorrido algum erro
     */
    public static function removerCsv()
    {
        if (file_exists(self::$lastUploadedCsv))
            unlink(self::$lastUploadedCsv);
    }

    /**
     * Faz a persistência de um array de ramais recebido pelo método preparaCsv
     *
     * @param  array $files Array com objetos do tipo Ramal
     * @return true|false True caso a persistência tenha ocorrido, false caso contrário
     */
    public static function importarRamal(array $files)
    {
        $ramaisImportar = self::preparaCsv($files);
        if (!is_array($ramaisImportar)) 
            return $ramaisImportar;

        $query = '';
        foreach ($ramaisImportar as $ramal) {
            $ramal = parent::prepare($ramal->toArray());
            $query .= 'INSERT INTO extensions (' . implode(', ', array_keys($ramal)) . ')' .
                ' VALUES (' . implode(', ', array_values($ramal)) . '); ';
        }
        try {
            /** @var PDO conn */
            $conn = Connection::getConnection();
            $result = $conn->exec($query);
            if ($result > 0) return true;
            else return false;
        } 
        catch (PDOException $e) {
            RamalControl::renderizaErro($e->getMessage());
        }
    }

    /**
     * Obtém o proximo ID livre da tabela do BD
     * 
     * @return int Próximo ID livre
     */
    private static function proximoId(): int
    {
        try {
            $conn = Connection::getConnection();
            $stmt = $conn->query("SELECT COALESCE(max(id), 0) AS ultimo FROM extensions");
            $result = $stmt->fetch();
            $max = $result['ultimo'] + 1;
            return $max;
        } catch (PDOException $e) {
            RamalControl::renderizaErro($e->getMessage());
        }
    }
}
