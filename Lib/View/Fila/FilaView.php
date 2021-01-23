<?php

namespace Techone\Lib\View\Fila;

use Techone\Lib\Helper\SmartyTechone;

class FilaView
{
    private static $smarty;

    public static function renderizar($view, $params = '')
    {
        self::$smarty = new SmartyTechone;

        switch ($view) {
            case 'nova-fila':
                if (is_array($params) && count($params) > 0) {
                    self::$smarty->assign('comboRamais', $params);
                }
                $template = 'novaFila.tpl';
                break;
            case 'listar':
                $template = 'listaFila.tpl';
                self::verificaMsgErro();
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
