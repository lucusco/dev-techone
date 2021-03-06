<?php

namespace Techone\Lib\View\Ramal;

use Techone\Lib\Helper\SmartyTechone;
use Techone\Lib\Helper\ViewsTrait;

class RamalView
{
    use ViewsTrait;

    private static $smarty;
    private const RAMAL_TPL = 'ramalAdd.tpl';

    public static function renderizar($view, $params = '')
    {
        self::$smarty = new SmartyTechone;

        switch($view) {
            case 'novo-ramal': 
                $template = self::RAMAL_TPL;
                self::$smarty->assign('titulo', 'Novo Ramal');
                self::$smarty->assign('comboRamais', $params);
                break;

            case 'importa-ramal':
                $template = 'ramalImport.tpl';
                break;

            case 'listar':
                $template = 'ramalListagem.tpl';
                if (isset($params['ramais'])) {
                    self::$smarty->assign('ramais', $params['ramais']);
                }
                break;

            case 'editar':
                $template = self::RAMAL_TPL;
                self::$smarty->assign('titulo', 'Editar Ramal');
                self::$smarty->assign('ramal', $params['ramal']);
                self::$smarty->assign('comboRamais', $params['comboRamais']);
                break;
            
            case 'persistir':
                $template = self::RAMAL_TPL;
                self::$smarty->assign('ramal', $params['ramal']);
                self::$smarty->assign('comboRamais', $params['comboRamais']);
                self::$smarty->assign('titulo', 'Editar Ramal');
                break;
            
            case 'error':
                $template = 'error.tpl';
                self::$smarty->assign('mensagem', $params);
                break;
        }
        
        self::verificaMsgErro(self::$smarty);
        self::$smarty->display($template); 
    }

}
