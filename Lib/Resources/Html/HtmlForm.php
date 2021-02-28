<?php

namespace Techone\Lib\Resources\Html;

/**
 * Classe auxiliar para montagem de componentes de formulários
 */
class HtmlForm
{
    /**
     * Função para montar combo do tipo select
     */
    public static function montaCombo(string $classe, string $name, array $valores, string $id = '', $selected = null, $extra = ''): string
    {
        $combo = '<select class="' . $classe . '" name="' . $name . '" id="' . $id . '"' . " $extra>\n";

        $option = "<option value=''>Selecione uma opção</option>\n";
        foreach ($valores as $chave => $valor) {
            $option .= '<option value="' . $chave . '"';
            if ($selected == $chave) $option .= ' selected';
            $option .= ">$valor</option>\n";
        }
        $option .= '</select>';

        $combo .= $option;
        return $combo;
    }
}
