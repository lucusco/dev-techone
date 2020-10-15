<?php

use Techone\Lib\Controller\{
    ExibirHome,
    RamalControl,
    TesteConn
};

$routes = [
    'home'       => ExibirHome::class,
    'novo-ramal' => RamalControl::class,
    'lista-ramal' => RamalControl::class,
    'salvar-ramal' => RamalControl::class,
    'edita-ramal' => RamalControl::class,
    'exclui-ramal' => RamalControl::class,
    'importa-ramal' => RamalControl::class,
    'nova-fila' => '',
    'lista-fila' => '',
    'salvar-fila' => '',
    'sair' => '',
    'testes' => TesteConn::class,
];

return $routes;