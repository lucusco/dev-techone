<?php

namespace Techone\Lib\Controller;

/**
 *  Métodos comuns que todos os controllers devem implementar
 */
interface InterfaceController
{
    public function processarRequisicao();

    public function editar();

    public function persistir();

    public function listar();

    public function remover();

}
