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
                                                <td width="35"><i class="fas fa-minus-circle  btn-outline-danger"></i></td>
                                                <td width="35"><i class="fas fa-edit btn-outline-primary"></i></td>
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