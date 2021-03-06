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
        extract($dados);
        //Fazer com que o PHP comece a guardar tudo que será exibido
        ob_start();
        require BASE_DIR . $qualRequire; // o require não é exbido nesse momento
        return ob_get_clean(); //Faz as duas coisas, pega o conteúdo e já limpa o buffer
    }

    /**
     * Seta mensagens de retorno na variável de sessão
     *
     * @param string $mensagem Mensagem a ser exibida para o usuário
     * @param string $tipo Tipo (tipos de alerta do Bootstrap/SweetAlert)
     */
    public function setaMensagemRetorno(string $tipo, string $mensagem): void
    {
        if (!isset($_SESSION['flash'])) {
            $_SESSION['flash'] = [];
        }

        $_SESSION['flash'] = [
            'tipo' => $tipo,
            'msg'  => $mensagem
        ];
    }
}
