<?php

namespace Techone\Lib\Controller;

use DomainException;
use stdClass;
use Techone\Lib\Helper\ControllerAuxTrait;
use Techone\Lib\Model\Fila;
use Techone\Lib\Model\Ramal;
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
        $ramaisDisponiveis = Ramal::todosRamais();
        $comboRamais = array();
        foreach ($ramaisDisponiveis['ramais'] as $r) {
            $ramal = new stdClass;
            $ramal->id = $r->id;
            $ramal->descricao = "{$r->exten} - {$r->username}";
            $comboRamais[] = $ramal;
        }
        FilaView::renderizar('nova-fila', $comboRamais);
    }

    public function editar()
    {

    }

    public function persistir()
    {
        $dados = (object)$_POST;
        $fila = new Fila;

        try {
            $fila->setNumber(intval($dados->entrada));
            $fila->setDescription($dados->descricao);
            $fila->setStrategy($dados->estrategia);
            $fila->setExtensions($dados->ramais);
            $sucesso = $fila->save();
            if ($sucesso) {
                $this->setaMensagemRetorno('success', "Fila inserida com sucesso!"); 
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
        FilaView::renderizar('listar');
    }

    public function remover()
    {

    }

}
