<?php

namespace Techone\Lib\Controller;

use DomainException;
use Techone\Lib\Helper\ControllerAuxTrait;
use Techone\Lib\Model\Fila;
use Techone\Lib\View\Fila\FilaView;

class FilaControl implements InterfaceController
{
    use ControllerAuxTrait;

    public function processarRequisicao()
    {
        switch ($_GET['url']) {
            case 'nova-fila':
                $this->novaFila();
                break;
            default:
                echo 'Ops!';
        }
    }

    /**
     * Preparar combo de ramais para seleção na fila
     */
    public function novaFila()
    {
        $comboRamais = Fila::getComboExtens();
        FilaView::renderizar('nova-fila', $comboRamais);
    }

    public function editar()
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!is_numeric($id)) return;
        
        $fila = new Fila;
        $dados['fila'] = $fila->loadQueue($id);
        $dados['combo'] = Fila::getComboExtens();

        FilaView::renderizar('editar', $dados);
    }

    public function persistir()
    {
        $dados = (object)$_POST;
        $fila = new Fila;
        $acao = 'inserida';

        if (is_numeric($dados->id)) {
            $acao = 'atualizada';
            $fila->setId(intval($dados->id));
        }

        try {
            $fila->setNumber(intval($dados->entrada));
            $fila->setDescription($dados->descricao);
            $fila->setStrategy($dados->estrategia);
            $fila->setExtensions($dados->ramais);
            $sucesso = $fila->save();
            if ($sucesso) {
                $this->setaMensagemRetorno('success', "Fila $acao com sucesso!"); 
            } else {
                $this->setaMensagemRetorno('error', "Houve erro ao salvar a fila!"); 
            }
            header("Location: lista-fila?method=listar&page=1");

        } catch (DomainException $e) {
            var_dump($e->getMessage());
            //Renderizar o erro na tela
        }      
    }

    public function listar()
    {
        $fila = new Fila;
        $filas = $fila->loadAll();
        $params = $filas ?? null;
        FilaView::renderizar('listar', $params);
    }

    public function remover()
    {

    }

}
