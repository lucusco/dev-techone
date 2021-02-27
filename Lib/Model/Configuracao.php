<?php

namespace Techone\Lib\Model;

use PDOException;
use Techone\Lib\Database\Connection;

class Configuracao
{   
    private $nome;
    private $valor;
    private $descricao;

    /** @var mixed $configuracoes Coringa para manipular as diversas configurações que podem existir  */
    private $configuracoes;

    /* 
     * Setters and Getters
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    public function setDescricao($descricao)
    {
        $this->descricao = addslashes($descricao);
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function getValor()
    {
        return $this->valor;
    }

    public function getDescricao()
    {
        return $this->descricao;
    }


    /**
     * Faz as tratativas para as configurações\
     * Regra para os retornos:\
     * -true: mudou alguma config \
     * -null: config é igual a que já existe no BD \
     * -false: falha ao alterar valor da config \
     * null e true significam 'sucesso na alteração' para o front
     * 
     * @return bool 
     */
    public function preparaConfigs(): bool
    {
        $configuracoes = $this->configuracoes;
        $faixaRamais = $this->configuracoes['faixaRamais'] ?? null;
        $ret[] = false;
        
        if ($faixaRamais) {
            $ret['faixaRamais'] = $this->salvarFaixaRamais();
        }

        $ret = (in_array(null, $ret, true) || in_array(true, $ret, true)) ? true : false;
        return $ret;
    }

    /**
     * Persiste a configuração no BD
     * 
     * @return bool
     */
    private function salvaConfigs(): bool
    {
        try {
            $query = "INSERT INTO settings (name, value, description) VALUES (:nome, :valor, :descricao)";
            $conn = Connection::getConnection();
            $conn->exec("DELETE FROM settings WHERE name = '{$this->getNome()}'"); //TODO: Fazer update
            $stmt = $conn->prepare($query);
            $stmt->bindValue(':nome', $this->getNome());
            $stmt->bindValue(':valor', $this->getValor());
            $stmt->bindValue(':descricao', $this->getDescricao());
            $result = $stmt->execute();
            if ($result)
                return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Monta o array de faixa de ramais
     *
     * @param int $inicio Ramal de inicio
     * @param int $fim Último ramal
     */
    public function setFaixaRamais(int $inicio, int $fim): void
    {
        $this->configuracoes['faixaRamais'] = array(
            'inicio' => $inicio,
            'fim'    => $fim
        );
    }

    /**
     * Prepara a faixa de ramais para inserção no BD
     *
     * @return bool|null
     */
    private function salvarFaixaRamais(): ?bool
    {
        $valorAtual = $this->buscaValorConfig('faixaRamais');   
        $novoValor = "{$this->configuracoes['faixaRamais']['inicio']}-{$this->configuracoes['faixaRamais']['fim']}";

        if ($novoValor === $valorAtual->valor) return null;

        $this->setNome('faixaRamais');
        $this->setDescricao('Faixa de ramais do Asterisk');
        $this->setValor($novoValor);
        $ret = $this->salvaConfigs();
        return $ret;
    }

    /**
     * Carrega todas as configs salvas no BD e as retorna
     *
     * @return object|null
     */
    public static function carregarConfigs(): ?object
    {
        $conn = Connection::getConnection();
        $stmt = $conn->query("SELECT name, value FROM settings");
        $configuracoes = new \stdClass;
        
        while ($configSalva = $stmt->fetch(\PDO::FETCH_OBJ)) {
            switch ($configSalva->name) {
                case 'faixaRamais':
                    $faixa =  explode('-', $configSalva->value);
                    $configuracoes->faixaRamalInicial = $faixa[0];
                    $configuracoes->faixaRamalFinal = $faixa[1];
                    break;
            }
        }

        return $configuracoes;
    }

    /**
     * Busca o valor de uma configuração específica no BD e a retorna
     *
     * @param [string] $configName Nome da config
     * @return object|null
     */
    private function buscaValorConfig(string $configName = null): ?object
    {
        $conn = Connection::getConnection();
        $stmt = $conn->query("SELECT value as valor FROM settings WHERE name = '$configName'");
        return $stmt->fetch(\PDO::FETCH_OBJ);
    }

}
