{include file="html-inicio.tpl"}
{* Estrutura padrão para conteúdos 3-tabs *}
            <main>
                <div class="container-fluid">
                    <h1 class="mt-4">Filas</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Filas</li>
                        <li class="breadcrumb-item "><a href="nova-fila">Nova Fila</a></li>
                    </ol>
                    <div class="row">
                        <div class="col-xl-12 col-md-10">
                            <form action="salvar-fila?method=persistir" method="post">
                                <div class="form-group">
                                    <fieldset class="rounded">
                                        <legend>Nova Fila</legend>
                                        <div class="form-group">
                                            <label for="entrada">Entrada</label>
                                            <input class="form-control" type="text" name="entrada" id="entrada" value="">
                                        </div>
                                        <div class="form-group">
                                            <label for="descricao">Descrição</label>
                                            <input class="form-control" type="text" name="descricao" id="descricao" value="">
                                        </div>
                                        <div class="form-group">
                                            <label for="estrategia">Estratégia</label>
                                            <select name="estrategia" class="form-control">
                                                <option value="0">Selecione uma opção</option>
                                                <option value="random">Aleatório</option>
                                                <option value="linear">Sequencial</option>
                                                <option value="ringall">Tocar Todos</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="ramais">Ramais da fila</label>
                                            <select multiple class="form-control" name="ramais[]" id="ramais" size="10">
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
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