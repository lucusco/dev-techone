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

    public function __construct()
    {
        //TODO: receber id e carregar
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
     * Retorna um inteiro indicando que o ramal foi removido (PDO exec) ou uma string de erro
     *
     * @param int $id ID do ramal a ser removido
     * @return int|string 
     */
    public static function removerRamal(int $id)
    {
        try {
            /** @var \PDO conn */
            $conn = Connection::getConnection();
            $sql = "DELETE FROM extensions WHERE id = {$id}";
            //var_dump($sql); die;
            $result = $conn->exec($sql);
            if ($result) {
                Asterisk::escreveConfRamais();
            }
            return $result;
        } catch (PDOException $e) {
            $errorCode = $e->getCode();           
            return ($errorCode == ID_EH_FK_EMUSO) ? 'Este ramal está em uso por alguma fila e não pode ser removido.'
                                                  : 'Houve um erro ao remover o ramal, contate o administrador.';
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
