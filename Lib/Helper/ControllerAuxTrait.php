<?php

namespace Techone\Lib\Helper;

/**
 *  Funções auxiliares comum a alguns para os controllers
 *  
 */
trait ControllerAuxTrait
{
    public function processarVisao()
    {
        
    }

    /**
     *  Monta o HTML a ser exibido e o retorno
     * @return string
     */
    public function renderizarHtml(string $qualRequire, $dados = []): string
    {
        extract($dados);
        //Fazer com que o PHP comece a guardar tudo que será exibido
        ob_start();
        require BASE_DIR . $qualRequire; // o require não é exbido nesse momento
        $html = ob_get_clean(); //Faz as duas coisas, pega o conteúdo e já limpa o buffer
        return $html;
    }

    /**
     * Seta mensagens de retorno na variável de sessão
     *
     * @param string $mensagem Mensagem a ser exibida para o usuário
     * @param string $tipo Tipo (tipos de alerta do Bootstrap)
     */
    public function setaMensagemRetorno(string $mensagem, string $tipo): void
    {
        $_SESSION['tipo'] = $tipo;
        $_SESSION['msg']  = $mensagem;
    }
}
