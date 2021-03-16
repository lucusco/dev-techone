<?php

namespace Techone\Lib\Model;

use PDO;
use PDOException;
use DomainException;
use Techone\Lib\Conf\Asterisk;
use Techone\Lib\Api\DataRecord;
use Techone\Lib\Database\Connection;
use Techone\Lib\Controller\RamalControl;
use Techone\Lib\Helper\ModelFunctionsTrait;
use Techone\Lib\Resources\Html\HtmlForm;

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
        if (empty($exten) || $exten == '0') throw new DomainException('Número do Ramal deve ser informado');
        else if (!is_numeric($exten)) throw new DomainException('Número do Ramal deve ser numérico');
        else if (!is_int($exten + 0)) throw new DomainException('Número do ramal inválido');
        else if ($this->jaExiste('exten', $exten, $this->id) === true) throw new DomainException('Ramal informado já existe');
        else  $this->exten = $exten;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        if (empty($username)) 
            throw new DomainException('Descrição deve ser informada');
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
            throw new DomainException('Senha deve ser informada e deve ter mais pelo menos 4 caracteres');
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
            throw new DomainException('Context deve ser informado');
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
            throw new DomainException('Tecnologia deve ser informada');
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
            throw new DomainException('Gravação deve ser informada');
        }  
        $recording = !empty($recording) ? $recording : $_POST['gravacao'];
        
        if (!in_array($recording, array('sim', 'nao', 's', 'n', 'não', 'on', 'off'))) {
            throw new DomainException('Gravação não especificada. Tipos permitidos são: sim, nao, s, n, não');
        } 
        $this->recording = (in_array($recording, array('sim', 's')) || (isset($_POST['gravacao']) && $_POST['gravacao'] == 'on')) ?  'true' : 'false'; 
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
                return $this->load($id);
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
    public static function todosRamais(int $pagina=NULL, $order = '')
    {
        $order = empty($order) ? 'id' : $order;
        try {
            /** @var \PDO conn */
            $conn = Connection::getConnection();

            if ($pagina) { //consulta com paginacao
                $busca = "SELECT * FROM extensions ORDER BY $order";
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
                $stmt = $conn->query("SELECT * FROM extensions ORDER BY $order");
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
                $result =  Asterisk::escreveConfRamais();
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
            if ($result) {
                Asterisk::escreveConfRamais();
            }
            return $result;
        } catch (PDOException $e) {
            RamalControl::renderizaErro($e->getMessage());
        }
    }

    /**
     *  Faz a leitura do arquivo CSV e retorna os dados em um array de objetos
     *
     * @param string $files Array do tipo $_FILES
     * @return array $data  Array de objetos com os ramais a serem importados
     */
    private static function preparaCsv($files)
    {
        
        if (!isset($files['arquivo'])) throw new DomainException('Erro, o nome do campo recebido é inválido');
        
        $infoArquivo = (object)$files['arquivo'];
        $tiposPermitidos = array('text/csv', 'text/plain');

        // Validações 
        if ((int)$infoArquivo->error == 2 || filesize($infoArquivo->tmp_name) > 3000000) throw new DomainException('O tamanho do arquivo excede o máximo permitido! (3Mb)');
        if ($infoArquivo->size <= 0 || empty($infoArquivo->name))                        throw new DomainException('Arquivo não detectado, por favor selecione-o novamente.');
        if ((int)$infoArquivo->error != 0)                                               throw new DomainException('O arquivo importado apresentou erro durante a importação!');
        if (!in_array(mime_content_type($infoArquivo->tmp_name), $tiposPermitidos))      throw new DomainException('Tipo de arquivo não reconhecido, apenas .csv.');

        
        $cabecalhoPermitido = array('ramal', 'nome', 'senha', 'gravar', 'contexto', 'tipo');
        $cabecalho = NULL;
        $dadosRamais = array();

        $uploadedPath = BASE_DIR . 'Files/Upload/' . date('d-m-y_H:i:s') . '_' . basename($infoArquivo->name);
        self::$lastUploadedCsv = $uploadedPath;

        // Manipulação do arquivo
        if (move_uploaded_file($infoArquivo->tmp_name, $uploadedPath)) {
            if (($arqCsv = fopen($uploadedPath, 'r')) !== FALSE) {
                while (($linha = fgetcsv($arqCsv, 500, ';')) !== FALSE) {
                    if (!$cabecalho) { 
                        // Ajustar cabeçalho ao ler primeira linha
                        foreach ($linha as $coluna) {
                            if (!in_array(strtolower($coluna), $cabecalhoPermitido))
                                throw new DomainException("O cabeçalho $coluna não é permitido, por gentileza realize o ajuste.");

                            // Adequar colunas do arquivo às colunas do banco
                            switch (strtolower($coluna)) {
                                case 'ramal':    $cabecalho[] = 'exten';     break;
                                case 'nome':     $cabecalho[] = 'username';  break;
                                case 'senha':    $cabecalho[] = 'secret';    break; 
                                case 'gravar':   $cabecalho[] = 'recording'; break;
                                case 'contexto': $cabecalho[] = 'context';   break;
                                case 'tipo':     $cabecalho[] = 'tech';      break;
                            }
                        }
                    } else {
                        foreach ($linha as $colunaRamal) {
                            if (empty($colunaRamal)) throw new DomainException("Há valores em branco na planilha, todos os campos devem ser preenchidos!");
                        }
                        $dadosRamais[] = array_combine($cabecalho, $linha);
                    }
                }
                fclose($arqCsv);
            }
        } else {
            throw new DomainException("Erro ao manipular arquivo contendo os ramais a serem importados!");
        }

        // Transformar em array de objetos Ramal
        $ramaisImportar = array();
        if (!empty($dadosRamais)) {
            $id = self::proximoId();
            foreach ($dadosRamais as $ramal) {
                $obj = new Ramal();
                try {
                    $obj->setId($id);
                    $obj->setExten($ramal['exten']);
                    $obj->setUsername($ramal['username']);
                    $obj->setSecret($ramal['secret']);
                    $obj->setContext($ramal['context']);
                    $obj->setTech($ramal['tech']);
                    $obj->setRecording(strtolower($ramal['recording']));                 
                } catch (DomainException $e) {
                    /* TODO: Fazer uma contagem daquele que nao foram importados
                     * Por hora, apenas continuar o loop ignorando o erro
                     */
                    continue;
                }
                $ramaisImportar[] = $obj;
                $id++;
            }
        }

        if (empty($ramaisImportar)) throw new DomainException('Não foi identificado nenhum ramal a ser importado.');

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
            if (empty($ramais['ramais'])) return false;
        }
        
        $filename = DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . date('H:i:s_') . 'ramais.csv';
        $file = fopen($filename, 'x+');

        $cabecalho = $exemplo ? ['Ramal', 'Nome', 'Contexto', 'Tipo', 'Gravar', 'Senha'] :  ['ID', 'Ramal', 'Nome', 'Senha', 'Contexto', 'Tipo', 'Gravar'];
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
     * Faz a persistência da pĺanilha de ramais
     *
     * @param  array $files Array do tipo $_FILES
     * @return true|false True caso a persistência de pelo menos 1 ramal tenha ocorrido
     */
    public static function importarRamal(array $files)
    {       
        $inseridos = 0;
        try {
            $ramaisImportar = self::preparaCsv($files);
            /** @var PDO conn */
            $conn = Connection::getConnection();

            foreach ($ramaisImportar as $ramal) {
                $ramal = parent::prepare($ramal->toArray()); 
                
                $columns = implode(', ', array_keys($ramal)); 
                $query = "INSERT INTO extensions ($columns) VALUES (:id, :exten, :username, :secret, :context, :tech, :recording)";
                $stmt = $conn->prepare($query);
                $stmt->bindValue(':id',        $ramal['id']);
                $stmt->bindValue(':exten',     $ramal['exten']);
                $stmt->bindValue(':username',  $ramal['username']);
                $stmt->bindValue(':secret',    $ramal['secret']);
                $stmt->bindValue(':context',   $ramal['context']);
                $stmt->bindValue(':tech',      $ramal['tech']);
                $stmt->bindValue(':recording', $ramal['recording']);
                $res = $stmt->execute();

                if ($res === true) $inseridos++;
            }

            if ($inseridos) {
                Asterisk::escreveConfRamais();
                return true;
            }
        } catch (PDOException $e) {
            RamalControl::renderizaErro($e->getMessage());
        } catch (DomainException $e) {
            return $e->getMessage();
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
            return $result['ultimo'] + 1;
        } catch (PDOException $e) {
            RamalControl::renderizaErro($e->getMessage());
        }
    }

    /**
     * Monta o combo de seleção de ramais
     *
     * @param string $ramalEmEdicao Ramal que deve ser mantido no combo (selected)
     */
    public static function comboRamais($ramalEmEdicao = '')
    {
        $faixa = explode('-', Configuracao::buscaValorConfig('faixaRamais')->valor);

        for ($i = $faixa[0]; $i <=  $faixa[1]; $i++) {
            $faixaRamais[$i] = $i;
        }

        $conn = Connection::getConnection();
        $stmt = $conn->query('SELECT exten FROM extensions');
        while ($ramal = $stmt->fetch(PDO::FETCH_OBJ)) {
            if (in_array($ramal->exten, $faixaRamais) && $ramal->exten != $ramalEmEdicao)
                unset($faixaRamais[array_search($ramal->exten, $faixaRamais)]);
        }

        return HtmlForm::montaCombo('form-control', 'ramal', $faixaRamais, 'ramal', $ramalEmEdicao);
    }
}
