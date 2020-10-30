<?php

namespace Techone\Lib\View\Ramal;

use Techone\Lib\Helper\SmartyTechone;

class RamalView
{
    private static $smarty;

    public static function renderizar($view, $params = '')
    {
        self::$smarty = new SmartyTechone;

        switch($view) {
            case 'novo-ramal': 
                $template = 'ramalAdd.tpl';
                self::$smarty->assign('titulo', 'Novo Ramal');
                break;

            case 'importa-ramal':
                $template = 'ramalImport.tpl';
                self::verificaMsgErro();
                break;

            case 'listar':
                $template = 'ramalListagem.tpl';
                if (isset($params['ramais'])) {
                    self::$smarty->assign('ramais', $params['ramais']);
                    self::$smarty->assign('paginas', $params['quantidadePaginas']);
                }
                self::verificaMsgErro();
                break;

            case 'editar':
                $template = 'ramalAdd.tpl';
                self::$smarty->assign('titulo', 'Editar Ramal');
                self::$smarty->assign('ramal', $params['ramal']);
                self::verificaMsgErro();
                break;
            
            case 'persistir':
                $template = 'ramalAdd.tpl';
                self::$smarty->assign('ramal', $params['ramal']);
                self::$smarty->assign('titulo', 'Editar Ramal');
                self::verificaMsgErro();
                break;
            
            case 'error':
                $template = 'error.tpl';
                self::$smarty->assign('mensagem', $params);
                break;
        }
        self::$smarty->display($template); 
    }

    private static function verificaMsgErro()
    {
        if (isset($_SESSION['flash'])) {
            self::$smarty->assign('tipo', $_SESSION['flash']['tipo']);
            self::$smarty->assign('mensagem', $_SESSION['flash']['msg']);
            unset($_SESSION['flash']); 
        } 
    }
}
