{include file="html-inicio.tpl"}

    <!-- Form Add/Edit Ramal -->
    {include file="mensagemRetorno.tpl"}
            <main>
                <div class="container-fluid">
                    <h1 class="mt-4">Ramais</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Ramais</li>
                        <li class="breadcrumb-item "><a href="novo-ramal">{$titulo}</a></li>
                    </ol>
                    <div class="row">
                        <div class="col-12">
                            <form action="salvar-ramal?method=persistir{if isset($ramal)}&id={$ramal->id}{/if}" method="post">
                                <div class="form-group">
                                    <fieldset class="rounded">
                                        <legend>{$titulo}</legend>
                                        <div class="form-group">
                                            <label for="ramal">Ramal</label>
                                            <input class="form-control" type="text" name="ramal" id="ramal" value="{if isset($ramal)}{$ramal->exten}{/if}">
                                        </div>
                                        <div class="form-group">
                                            <label for="nome">Descrição</label>
                                            <input class="form-control" type="text" name="nome" id="nome" value="{if isset($ramal)}{$ramal->username}{/if}">
                                        </div>
                                        <div class="form-group">
                                            <label for="senha">Senha de Registro</label>
                                            <input class="form-control" type="text" name="senha" id="senha" value="{if isset($ramal)}{$ramal->secret}{/if}">
                                        </div>
                                        <div class="form-group">
                                            <label for="context">Contexto</label>
                                            <input class="form-control" type="text" name="context" id="context" value="{if isset($ramal)}{$ramal->context}{/if}">
                                        </div>
                                        <div class="form-group">
                                            <label>Tecnologia</label>
                                            <select class="form-control" name="tech">
                                                <option value="0" >Selecione uma opção</option>
                                                <option value="sip" {if isset($ramal) && $ramal->tech == 'SIP'}selected{/if}>SIP</option>
                                                <option value="iax" {if isset($ramal) && $ramal->tech == 'IAX'}selected{/if}>IAX</option>
                                            </select>
                                        </div>
                                        <br>
                                        <div class="form-group">
                                            <label>Gravar Chamadas</label><br>
                                            <input type="radio" name="gravacao" id="gravacao" value="on"  {if isset($ramal) && $ramal->recording == true}checked{/if}> Sim <br>
                                            <input type="radio" name="gravacao" id="gravacao" value="off" {if isset($ramal) && $ramal->recording == false}checked{/if}> Não
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
