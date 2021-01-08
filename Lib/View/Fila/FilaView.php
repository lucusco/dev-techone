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
                $template = 'novaFila.tpl';
                break;
        }

        self::$smarty->display($template);

    }

}

