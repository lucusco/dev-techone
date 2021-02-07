<?php

namespace Techone\Lib\Helper;

/**
 * Trait auxiliar para as views
 */
trait ViewsTrait
{
    /**
     * Armazenar mensagens de erro
     *
     * @param smarty $smarty
     */
    public static function verificaMsgErro($smarty)
    {
        if (isset($_SESSION['flash'])) {
            $smarty->assign('tipo', $_SESSION['flash']['tipo']);
            $smarty->assign('mensagem', $_SESSION['flash']['msg']);
            unset($_SESSION['flash']); 
        } 
    }
}