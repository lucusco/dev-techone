<?php

namespace Techone\Lib\Helper;

/**
 *  Funções auxiliares comum a alguns para os controllers
 *  
 */
trait ControllerAuxTrait
{
    /**
     *  Monta o HTML a ser exibido e o retorno
     * @return string
     */
    public function renderizarHtml(string $qualRequire, $dados = []): string
    {
        //var_dump($dados); //die();
        extract($dados);
        //Fazer com que o PHP comece a guardar tudo que será exibido
        ob_start();
        require BASE_DIR . $qualRequire; // o require não é exbido nesse momento
        $html = ob_get_clean(); //Faz as duas coisas, pega o conteúdo e já limpa o buffer
        return $html;
    }

    public function setaMensagemRetorno(string $mensagem, string $tipo): void
    {
        $_SESSION['tipo'] = $tipo;
        $_SESSION['msg']  = $mensagem;
    }
}
