<?php

namespace Techone\Lib\Controller;

use Techone\Lib\Helper\SmartyTechone;

class ExibirHome
{
    public function processarRequisicao()
    {
        $smarty = new SmartyTechone();

        $msg = 'Em breve uma tabela de informações aqui';        
        $smarty->assign('mensagem', $msg);
        $smarty->display('home.tpl');
    }
}
