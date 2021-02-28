<?php

namespace Techone\Lib\Controller;

use Techone\Lib\Helper\ViewsTrait;
use Techone\Lib\Model\Configuracao;
use Techone\Lib\Helper\SmartyTechone;
use Techone\Lib\Helper\ControllerAuxTrait;

class ConfigGeralControl
{
    use ControllerAuxTrait;
    use ViewsTrait;

    public function processarRequisicao()
    {
        switch($_GET['url']) {
            case 'config':
                $smarty = new SmartyTechone;
                $configs = Configuracao::carregarConfigs();
                $smarty->assign('config', $configs);
                ViewsTrait::verificaMsgErro($smarty);
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
        $ramalInicial = filter_input(INPUT_POST, 'rangeinicio', FILTER_VALIDATE_INT);
        $ramalFinal = filter_input(INPUT_POST, 'rangefim', FILTER_VALIDATE_INT);

        $configs = new Configuracao();

        if ($ramalInicial && $ramalFinal && ($ramalInicial < $ramalFinal)) {
            $configs->setFaixaRamais($ramalInicial, $ramalFinal);
        }

        $ret = $configs->preparaConfigs();

        if ($ret) $this->setaMensagemRetorno('success', "Configurações atualizadas com sucesso!");
        else $this->setaMensagemRetorno('error', "Erro ao atualizar configurações");

        header("Location: config");
    }

    public static function paginaErro($erro)
    {
        self::renderizaPageError($erro);
    }
}
