{include file="html-inicio.tpl"}
            <main>
                <div class="container-fluid">
                    <h1 class="mt-4">Ramais em uso</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Ramais</li>
                        <li class="breadcrumb-item "><a href="lista-ramal?method=listar&page=1">Em uso</a></li>
                    </ol>
                    <div class="row">
                        <div class="col-12">
                            <!-- Modal Excluir ramal-->
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
                                            <p>Tem certeza que deseja excluir o ramal?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                            <a href="#" id="btnModalExcluir" class="btn btn-danger">Excluir Ramal</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <h4 class="p-3">Ramais em uso</h4>
                            {include file="mensagemRetorno.tpl"}
                                <div class="px-3">
                                    <table id="table_ramais" class="display">
                                        <thead>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td>ID</td>
                                                <td>Ramal</td>
                                                <td>Usuário</td>
                                                <td>Senha Registro</td>
                                                {* <td>Tipo</td> *}
                                            </tr>
                                        </thead>
                                        <tbody>
                                        {if isset($ramais)}
                                            {foreach $ramais as $ramal}
                                            <tr>
                                                <td width="35"><a href="#modalExclusao" 
                                                    class="btn-outline-danger" 
                                                    data-toggle="modal"
                                                    data-id="{$ramal->id}" 
                                                    role="button">
                                                    <i class="fas fa-minus-circle"></i>
                                                    </a>
                                                </td>
                                                <td width="35"><a href="edita-ramal?method=editar&id={$ramal->id}" class="btn btn-sm"><i class="fas fa-edit btn-outline-primary"></i></a></td>
                                                <td class="align-middle">{$ramal->id}</td>
                                                <td class="align-middle">{$ramal->exten}</td>
                                                <td class="align-middle">{$ramal->username}</td>
                                                <td class="align-middle">{$ramal->secret}</td>
                                                {* <td class="align-middle">{$ramal->tech|upper}</td> *}
                                            </tr>
                                            {/foreach}
                                        {/if}
                                        </tbody>
                                    </table>
                                </div>
                            <script>
                                {include file="../js/ramal.js"}
                            </script>
                        </div>
                    </div>     
                </div>
            </main>
{include file="html-fim.tpl"}