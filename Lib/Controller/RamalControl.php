<?php

namespace Techone\Lib\Controller;

use Exception;
use Techone\Lib\Model\Ramal;
use Techone\Lib\Helper\ControllerAuxTrait;
use Techone\Lib\Controller\InterfaceController;


class RamalControl implements InterfaceController
{
    use ControllerAuxTrait;

    public function processarRequisicao()
    {
        $dados = array();
        //Tratar urls que não chamam método
        switch ($_GET['url']) {
            case 'novo-ramal':
                $view = 'addRamal';
                $dados = ['titulo' => 'Novo Ramal'];
                break;
            case 'importa-ramal':
                $view = 'importarRamal';
                break;
            //TODO Criar um default se nao achar pra onde direcionar
        }
        print $this->renderizarHtml("View/Ramal/$view.php", $dados);
    }

    public function listar()
    {
        $pagina = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
        if (!is_int($pagina)) $pagina = 1;

        $result = Ramal::todosRamais($pagina);
        
        $dados = array();

        if (count($result['ramais']) > 0) {
            $dados = [
                'ramais' =>  $result['ramais'],
                'quantidadePaginas' => $result['totalPaginas']
            ];
        } else {
            $this->setaMensagemRetorno('Nenhum ramal criado até o momento', 'info');
        }
        print $this->renderizarHtml('View/Ramal/listaRamal.php', $dados);
    }

    public function editar()
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!is_numeric($id)) return;

        //Objeto com os dados do ramal preenchidos
        $ramal = new Ramal;
        $ramal = $ramal->carregarRamal($id);

        //Jogar esses dados na view
        $dados = [
            'titulo' => 'Editar Ramal',
            'ramal' => $ramal
        ];
        print $this->renderizarHtml('View/Ramal/addRamal.php', $dados);
    }

    public function persistir()
    {
        var_dump($_GET);
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
            $gravar = $dados['gravacao'] == 'on' ? 'sim' : 'nao';
            $ramal->setRecording($gravar);
        } catch (Exception $e) {
            $this->setaMensagemRetorno("{$e->getMessage()}", 'danger');
            $gerouException = true;
            if ($ramal->getId()) $location = "edita-ramal?method=editar&id={$ramal->getId()}";
            else $location = 'novo-ramal';
        }
        
        if (!$gerouException) {
            $result = $ramal->persistir();
            if ($result) $this->setaMensagemRetorno("Ramal {$ramal->getExten()} $acao com sucesso!", 'success');
            else $this->setaMensagemRetorno("Não foi possível realizar a ação com o ramal {$ramal->getUsername()}", 'danger');
        }

        header("Location: $location"); 
    }

    public function remover()
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        
        if (Ramal::removerRamal($id)) $this->setaMensagemRetorno('Ramal removido com sucesso', 'success');
        else $this->setaMensagemRetorno('Houve um erro ao remover o ramal, contate o administrador.', 'danger');
        
        header('Location: lista-ramal?method=listar');
    }

    public function importar()
    {
        $err = Ramal::importarRamal($_FILES);
        if ($err === true) $this->setaMensagemRetorno('Planilha de ramais importada com sucesso!', 'success');
        else {
            $err = is_string($err) ? $err : 'Houve erro ao importar a planilha de ramais, contate o administrador';
            $this->setaMensagemRetorno("$err", 'danger');
            Ramal::removerCsv();
        }
        header('Location: importa-ramal');
    }

    public function exportar()
    {
        $file = Ramal::exportarCsv();
        if (file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header("Content-Disposition: attachment; filename=ramais.csv");
            header('Content-Length: ' . filesize($file));
            header('Pragma: no-cache');
            ob_clean();
            readfile($file);
            // TODO Ver uma outra de enviar mensagem nesse caso, se eu fizer outro header o arquivo não baixa
        } else {
            $this->setaMensagemRetorno('A planilha não foi exportada porque não há ramais criados', 'danger');
            header('Location: importa-ramal');
        }
    }
}
