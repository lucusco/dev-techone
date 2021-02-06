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
                self::$smarty->assign('titulo', 'Nova ');
                break;
            case 'listar':
                $template = 'listaFila.tpl';
                if (is_array($params)) {
                    self::$smarty->assign('filas', $params);
                }
                break;
            case 'editar':
                $template = 'novaFila.tpl';
                self::$smarty->assign('titulo', 'Editar ');
                self::$smarty->assign('fila', $params['fila']);
                self::$smarty->assign('comboRamais', $params['combo']);
                break;
        }
        self::verificaMsgErro();
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
