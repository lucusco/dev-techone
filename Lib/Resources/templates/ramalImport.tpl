{include file="html-inicio.tpl"}
    
    {include file="mensagemRetorno.tpl"}
    <div class="col-10">
        <div class="m-3">
            <div class="jumbotron">
                <h1 class="display-5">Importar</h1>
                <p class="lead">Faça o upload de uma planilha de ramais 
                    <a class="btn btn-sm btn-outline-info" data-toggle="tooltip" data-placement="right" title="Arquivo de exemplo" href="importa-ramal?method=exportar&exemplo">?</a>
                </p> 
                <hr class="my-3">
                <p class="small">*Permitido arquivo CSV separado por ; e cabeçalho com seis colunas: Ramal, Nome, Senha, Gravar, Contexto, Tipo</p>
                <form action="importa-ramal?method=importar" method="post" enctype="multipart/form-data">
                    <div class="custom-file w-50">
                        <input type="hidden" name="MAX_FILE_SIZE" value="3000000" />
                        <input type="file" class="custom-file-input" id="arquivo" name="arquivo">
                        <label class="custom-file-label" for="arquivo">Selecione o arquivo</label>
                        <button class="btn btn-success btn-lg mt-3">Importar</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="ml-3 mr-3">
            <div class="jumbotron">
                <h1 class="display-5">Exportar</h1>
                <p class="lead">Exportar os ramais para arquivo CSV</p>
                <hr class="my-3">
                <p class="small">Faz o download de uma planilha com todos os ramais</p>  
                <a class="btn btn-success btn-lg" href="importa-ramal?method=exportar" role="button">Exportar</a>
            </div>
        </div>
    </div>
    <script>
        {include file="../js/ramal.js"}
    </script>
{include file="html-fim.tpl"}