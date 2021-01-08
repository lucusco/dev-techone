<?php

namespace Techone\Lib\Controller;

use Techone\Lib\View\Fila\FilaView;

class FilaControl implements InterfaceController
{

    public function processarRequisicao()
    {
        switch ($_GET['url']) {
            case 'nova-fila':
                FilaView::renderizar('nova-fila');
                break;
            default:
                echo 'Ops!';
        }
    }

    public function editar()
    {

    }

    public function persistir()
    {

    }

    public function listar()
    {

    }

    public function remover()
    {

    }

}
