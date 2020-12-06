{include file="html-inicio.tpl"}

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
    <!-- Listagem Ramal -->
    <div class="col-10">
        <h4 class="p-3">Ramais em uso</h4>
        {include file="mensagemRetorno.tpl"}
            <div class="table px-3">
                <table class="table table-striped">
                    <thead>
                        <tr class="table-primary">
                            <td style="width: 1rem"></td>
                            <td style="width: 1rem"></td>
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
                            <td><a href="#modalExclusao" 
                                class="btn btn-sm btn-outline-danger" 
                                data-toggle="modal"
                                data-id="{$ramal->id}" 
                                role="button">
                                <img src="{$img_dir}remove.svg" width="15" >
                                </a>
                            </td>
                            <td><a href="edita-ramal?method=editar&id={$ramal->id}" class="btn btn-sm btn-outline-primary"><img src="{$img_dir}edit.svg" width="15"></a></td>
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
            {if isset($paginas)}
            <div class="p-3">
                <nav aria-label="Navegacao">
                    <ul class="pagination">
                    {for $i = 1 to $paginas}
                        <li class="page-item"><a class="page-link" href="lista-ramal?method=listar&page={$i}">{$i}</a></li> 
                    {/for}
                    </ul>
                </nav>
            </div>
            {/if}
    </div>
    <script>
        {include file="../js/ramal.js"}
    </script>
{include file="html-fim.tpl"}