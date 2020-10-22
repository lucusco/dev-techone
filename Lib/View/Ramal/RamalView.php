<?php

namespace Techone\Lib\View\Ramal;

use Techone\Lib\Helper\SmartyTechone;

class RamalView
{
    public static function renderizar($view, $params = '')
    {
        $smarty = new SmartyTechone;

        switch($view) {
            case 'novo-ramal': 
                $template = 'ramalAdd.tpl';
                $smarty->assign('titulo', 'Novo Ramal');
                break;

            case 'importa-ramal':
                $template = 'ramalImport.tpl';
                break;

            case 'listar':
                $template = 'ramalListagem.tpl';
                $smarty->assign('ramais', $params['ramais']);
                $smarty->assign('paginas', $params['quantidadePaginas']);
                break;

            case 'editar':
                $template = 'ramalAdd.tpl';
                $smarty->assign('titulo', 'Editar Ramal');
                $smarty->assign('ramal', $params['ramal']);
                break;
            
            case 'persistir':
                $template = 'ramalAdd.tpl';
                $smarty->assign('titulo', 'Editar Ramal');
                
                break;
        }
        $smarty->display($template); 
    }
}