<?php

namespace Techone\Lib\Controller;

use DomainException;
use Techone\Lib\Model\Ramal;
use Techone\Lib\Model\RamalImport;
use Techone\Lib\View\Ramal\RamalView;
use Techone\Lib\Helper\ControllerAuxTrait;
use Techone\Lib\Controller\InterfaceController;

class RamalControl implements InterfaceController
{
    use ControllerAuxTrait;

    public function processarRequisicao()
    {
        switch ($_GET['url']) {
            case 'novo-ramal': 
                $this->novo();
                break;
            case 'importa-ramal':
                RamalView::renderizar($_GET['url']);
                break;
            default:
                //TODO Criar um default se nao achar pra onde direcionar
        }
    }

    public function novo()
    {
        $comboRamais = Ramal::comboRamais();
        RamalView::renderizar('novo-ramal', $comboRamais);
    }

    public function listar()
    {
        $dados = array();
        $result = Ramal::todosRamais();

        if (count($result['ramais']) > 0) {
            $dados = [
                'ramais' =>  $result['ramais']
            ];
            
        } else {
            $this->setaMensagemRetorno('info', "Nenhum ramal criado até o momento");  
        }
        
        RamalView::renderizar('listar', $dados);   
    }

    public function editar()
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!is_numeric($id)) return;

        //Objeto com os dados do ramal preenchidos
        $ramal = new Ramal;
        $dados['ramal'] = $ramal->carregarRamal($id); //TODO - tratar possíveis erros
        $dados['comboRamais'] = Ramal::comboRamais($dados['ramal']->exten);
        
        RamalView::renderizar('editar', $dados);
    }

    public function persistir()
    {
        if (isset($_GET['id'])) {
            $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
            $acao = 'atualizado';
        } else {
            $id = NULL;
            $acao = 'inserido';
        }

        $dados = $_POST;
        $ramal = new Ramal;
        $gerouException = null;
        $location = 'lista-ramal?method=listar';

        if ($id) { // Update
            $dados['id'] = $id;
            $ramal->setId($dados['id']);
        }
        try {
            $ramal->setExten($dados['ramal']);
            $ramal->setUsername($dados['nome']);
            $ramal->setSecret($dados['senha']);
            $ramal->setContext($dados['context']);
            $ramal->setTech($dados['tech']);
            $ramal->setRecording();
        } catch (DomainException $e) {
            $gerouException = true;
            $ramal = $ramal->carregarRamal($id);
            $this->setaMensagemRetorno('error', "{$e->getMessage()}");  
            $dados['ramal'] = $ramal;
            $dados['comboRamais'] = Ramal::comboRamais($dados['ramal']->exten);
            RamalView::renderizar('persistir', $dados);
        }
        
        if (!$gerouException) {
            $result = $ramal->persistir();
            if ($result)
                $this->setaMensagemRetorno('success', "Ramal {$dados['ramal']} $acao com sucesso!");  
            else 
                $this->setaMensagemRetorno('error', "Houve erro ao salvar o ramal");  
            
            header("Location: $location");
        }
    }

    public function remover()
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $err = Ramal::removerRamal($id);

        if (is_int($err)) $this->setaMensagemRetorno('success', 'Ramal removido com sucesso');
        else $this->setaMensagemRetorno('error', $err);
        
        header('Location: lista-ramal?method=listar');
    }

    public function importar()
    {
        $ret = RamalImport::importarRamal($_FILES);
        if ($ret === true) $this->setaMensagemRetorno('success', 'Planilha de ramais importada com sucesso!');
        else {
            $err = is_string($ret) ? $ret : 'Houve erro ao importar a planilha de ramais, contate o administrador';
            $this->setaMensagemRetorno('error', "$err");
            RamalImport::removerCsv();
        }
        header('Location: importa-ramal');
    }

    public function exportar()
    { 
        $file = isset($_GET['exemplo']) ? RamalImport::exportarCsv(true) : RamalImport::exportarCsv();
        
        if (file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header("Content-Disposition: attachment; filename=ramais.csv");
            header('Content-Length: ' . filesize($file));
            header('Pragma: no-cache');
            ob_clean();
            readfile($file);
            // TODO Ver uma outra de enviar mensagem de sucesso nesse caso, se eu fizer outro header o arquivo não baixa
        } else {
            $this->setaMensagemRetorno('info', 'A planilha não foi exportada porque não há ramais criados');
            header('Location: importa-ramal');
        }
    }

    public static function renderizaErro($msg = '')
    {
        RamalView::renderizar('error', $msg);
        die;
    }
}
