<?php

namespace Techone\Lib\Model;

use PDOException;
use DomainException;
use Techone\Lib\Model\Ramal;
use Techone\Lib\Conf\Asterisk;
use Techone\Lib\Database\Connection;
use Techone\Lib\Controller\RamalControl;

/**
 * Classe responsável por manipular as importações/exportações de ramais em massa
 */

class RamalImport extends Ramal
{
    // Mantém salva o nome do útimo arquivo csv que foi feito upload
    private static $lastUploadedCsv;
    
    // Auxiliar para salvar id do ramal
    private static $idAuxiliar;
    
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
     *  Deleta o arquivo que foi feito upload caso tenha ocorrido algum erro
     */
    public static function removerCsv()
    {
        if (file_exists(self::$lastUploadedCsv))
            unlink(self::$lastUploadedCsv);
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
            $ramais = parent::todosRamais();
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

        // Manipulação do arquivo
        $uploadedPath = self::moveCsvParaDestino($infoArquivo->tmp_name);
        $arqCsv = fopen($uploadedPath, 'r');
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
    
        $ramaisImportar = self::montaArrayRamais($dadosRamais);
        if (empty($ramaisImportar)) throw new DomainException('Não foi identificado nenhum ramal a ser importado.');

        return $ramaisImportar;
    }


    /**
     * Função auxiliar que move o arquivo csv para o destino correto e retorna o novo path
     *
     * @param string $tmpName Nome temporário
     * @return string Caminho no qual o .csv se encontra
     * @throws DomainException
     */
    private static function moveCsvParaDestino(string $tmpName): string
    {
        $novoCaminho = BASE_DIR . 'Files/Upload/' . date('d-m-y_H:i:s') . '_' . basename($tmpName);
        self::$lastUploadedCsv = $novoCaminho;
        if (move_uploaded_file($tmpName, $novoCaminho)) {
            return $novoCaminho;
        } else {
            throw new DomainException('Erro ao enviar arquivo .csv para o destino correto!');
        }
    }


    /**
     * Função auxiliar que monta o array com os ramais a serem importados
     *
     * @param array $dadosRamais Array com todos os ramais do arquivo 
     * @return array Array com objetos do tipo Ramal
     */
    private static function montaArrayRamais(array $dadosRamais): array
    {
        self::$idAuxiliar = null;
        $ramaisImportar = array();
        if (!empty($dadosRamais)) {
            foreach ($dadosRamais as $ramal) {
                $obj = new Ramal();
                self::$idAuxiliar = empty(self::$idAuxiliar) ? $obj->getProximoId() : self::$idAuxiliar;
                try {
                    $obj->setId(self::$idAuxiliar);
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
                self::$idAuxiliar++;
            }
        }
        return $ramaisImportar;
    }

}
