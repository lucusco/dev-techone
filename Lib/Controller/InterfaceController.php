<?php

namespace Techone\Lib\Controller;

/**
 *  Métodos comuns que todos os controllers devem implementar
 */
interface InterfaceController
{
    /**
     * Processa requisições que não possuem método
     */
    public function processarRequisicao();

    /**
     * Edita a entidade e salva a alteração no BD
     */
    public function editar();

    /**
     * Persiste a entidade manipulada no BD
     */
    public function persistir();

    /**
     * Listagem dos objetos do BD
     */
    public function listar();

    /**
     * Remove a entidade do BD
     */
    public function remover();

}
