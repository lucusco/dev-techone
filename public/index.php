<?php

/**
 *  Front controller
 */
session_start();
// Autoload
require_once '../vendor/autoload.php';
// Rotas
$rotas = require_once BASE_DIR . 'Config/routes.php';

// Se url digitada não existir no array de rotas, morrer
if (!array_key_exists($_GET['url'], $rotas)) {
    http_response_code(404);
    die();
}

// Obtem nome da classe a ser instanciada
$classeControladora = $rotas[$_GET['url']];


$controle = new $classeControladora;

// TODO Tratar o método method = ?
$method = $_GET['method'] ?? NULL;

if ($method) {
    if (method_exists($controle, $method)) {
        call_user_func(array($controle, $method));
    } else {
        echo '<h2>Não achou método</h2>';
        var_dump($_GET);
        var_dump($_POST);
        // TODO: para onde mandar se method não existir
    }
} else {
    // TODO: definir função padrão qdo não houver método especificado
    $controle->processarRequisicao();
}
