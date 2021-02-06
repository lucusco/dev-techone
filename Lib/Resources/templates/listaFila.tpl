{include file="html-inicio.tpl"}
{* Estrutura padrão para conteúdos 3-tabs *}
            <main>
                {include file="mensagemRetorno.tpl"}
                <div class="container-fluid">
                    <h1 class="mt-4">Filas</h1>
                   <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Filas</li>
                        <li class="breadcrumb-item "><a href="nova-fila">Nova Fila</a></li>
                    </ol>
                    <div class="row">
                        <div class="col-xl-12 col-md-10">
                         <!-- Modal Excluir fila -->
                            <div class="modal fade" id="modalExclusao" tabindex="-1" aria-labelledby="labelTopo" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="labelTopo">Confirmação de Exclusão</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Tem certeza que deseja excluir a fila?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                            <a href="#" id="btnModalExcluir" class="btn btn-danger">Excluir Fila</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <h4 class="mb-4">Filas em uso</h4>
                            {if isset($filas)}
                                <table id="table_filas" class="display">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th>ID</th>
                                            <th>Descrição</th>
                                            <th>Entrada</th>
                                            <th>Estratégia</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {foreach $filas as $fila}
                                            <tr>
                                                <td width="35">
                                                    <a href="#modalExclusao" 
                                                        class="btn-outline-danger"
                                                        data-toggle="modal"
                                                        data-id="{$fila->id}" 
                                                        role="button">
                                                        <i class="fas fa-minus-circle"></i>
                                                    </a>
                                                </td>
                                                <td width="35"><a href="edita-fila?method=editar&id={$fila->id}"><i class="fas fa-edit btn-outline-primary"></i></a></td>
                                                <td>{$fila->id}</td>
                                                <td>{$fila->description|ucfirst}</td>
                                                <td>{$fila->number}</td>
                                                <td>{$fila->strategy|ucfirst}</td>
                                            </tr>
                                        {/foreach}
                                    </tbody>
                                </table>
                            {/if}
                        </div>
                    </div>     
                </div>
            </main>
            <script>
                {include file="../js/filas.js"}
            </script>
{include file="html-fim.tpl"}