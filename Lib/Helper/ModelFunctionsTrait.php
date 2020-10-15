<?php

namespace Techone\Lib\Helper;

trait ModelFunctionsTrait
{
    public function toArray()
    {
        $data = array();
        foreach ($this as $attr => $value) {
            $data[$attr] = $value;    
        }
        return $data; 
    }


}