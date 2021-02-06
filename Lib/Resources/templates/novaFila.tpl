{include file="html-inicio.tpl"}
{* Estrutura padrão para conteúdos 3-tabs *}
            {include file="mensagemRetorno.tpl"}
            <main>
                <div class="container-fluid">
                    <h1 class="mt-4">Filas</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Filas</li>
                        <li class="breadcrumb-item "><a href="nova-fila">{$titulo}Fila</a></li>
                    </ol>
                    <div class="row">
                        <div class="col-xl-12 col-md-10">
                            <form action="salvar-fila?method=persistir&id={if isset($fila)}{$fila->id}{/if}" method="post">
                                <div class="form-group">
                                    <fieldset class="rounded">
                                        <legend>{$titulo}Fila</legend>
                                        {if isset($fila)}
                                            <input type="hidden" name="id" id="id" value="{$fila->id}">
                                        {/if}
                                        <div class="form-group">
                                            <label for="entrada">Entrada</label>
                                            <input class="form-control" type="text" name="entrada" id="entrada" value="{if isset($fila)}{$fila->number}{/if}">
                                        </div>
                                        <div class="form-group">
                                            <label for="descricao">Descrição</label>
                                            <input class="form-control" type="text" name="descricao" id="descricao" value="{if isset($fila)}{$fila->description}{/if}">
                                        </div>
                                        <div class="form-group">
                                            <label for="estrategia">Estratégia</label>
                                            <select name="estrategia" class="form-control">
                                                <option value="0">Selecione uma opção</option>
                                                <option value="random" {if isset($fila) && $fila->strategy == 'random'}selected{/if}>Aleatório</option>
                                                <option value="linear" {if isset($fila) && $fila->strategy == 'linear'}selected{/if}>Sequencial</option>
                                                <option value="ringall" {if isset($fila) && $fila->strategy == 'ringall'}selected{/if}>Tocar Todos</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="ramais">Ramais da fila</label>
                                            <select multiple class="form-control" name="ramais[]" id="ramais" size="10">
                                            {if isset($comboRamais)}
                                                {foreach $comboRamais as $ramal} 
                                                    <option value="{$ramal->id}" {if isset($fila) && in_array($ramal->id, array_values($fila->extensions))}selected{/if}>{$ramal->descricao}</option>
                                                {/foreach}
                                            {/if}
                                            </select>
                                        </div>
                                        <div>
                                            <button class="btn btn-success">Adicionar</button>
                                        </div>
                                    </fieldset>
                                </div>
                            </form>
                        </div>
                    </div>     
                </div>
            </main>
{include file="html-fim.tpl"}