<?php

use Techone\Lib\Controller\{
    ExibirHome,
    FilaControl,
    RamalControl
};

$routes = [
    'home'       => ExibirHome::class,
    'novo-ramal' => RamalControl::class,
    'lista-ramal' => RamalControl::class,
    'salvar-ramal' => RamalControl::class,
    'edita-ramal' => RamalControl::class,
    'exclui-ramal' => RamalControl::class,
    'importa-ramal' => RamalControl::class,
    'nova-fila' => FilaControl::class,
    'lista-fila' => '',
    'salvar-fila' => '',
    'sair' => ''
];

return $routes;