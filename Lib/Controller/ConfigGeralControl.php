<?php

namespace Techone\Lib\Controller;

use Techone\Lib\Helper\SmartyTechone;

class ConfigGeralControl
{
    public function processarRequisicao()
    {
        switch($_GET['url']) {
            case 'config':
                $smarty = new SmartyTechone;
                $smarty->display('configuracoes.tpl');
                break;
            case 'salvar-config':
                $this->salvarConfiguracoes();
                break;
            default:
                echo 'Não achou';
        }        
    }

    public function salvarConfiguracoes()
    {
        //TODO checar apenas configurações que mudaram (pegar todas do BD?)

        $ramalInicial = filter_input(INPUT_POST, 'rangeinicio', FILTER_VALIDATE_INT);
        $ramalFinal = filter_input(INPUT_POST, 'rangefim', FILTER_VALIDATE_INT);

        if ($ramalInicial && $ramalFinal) {

        }
    }
}
