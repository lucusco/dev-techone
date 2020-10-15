<?php

namespace Techone\Lib\Helper;

trait ModelFunctionsTrait
{
    /**
     * Transforma os atributos->valores de um objeto em chave/valor em um array associativo
     * 
     * @return array
     */
    public function toArray()
    {
        $data = array();
        foreach ($this as $attr => $value) {
            $data[$attr] = $value;    
        }
        return $data; 
    }


}