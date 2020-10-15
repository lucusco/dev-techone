<?php include_once BASE_DIR . 'Resources/html/inicio-html.php'; ?>
    
    <?php if (isset($_SESSION['msg'])) : ?>
        <div class="alert alert-<?= $_SESSION['tipo'] ?> alert-dismissible fade show m-3">
            <button class="close" type="button" data-dismiss="alert"> &times; </button>
            <?= $_SESSION['msg'] ?>
        </div>
        <?php unset($_SESSION['tipo']); unset($_SESSION['msg']); ?>
    <?php endif ?>
    <div class="m-3">
        <div class="jumbotron">
            <h1 class="display-5">Importar</h1>
            <p class="lead">Faça o upload de uma planilha de ramais</p>
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
    <script>
        $(".custom-file-input").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });
    </script>
<?php include_once BASE_DIR . 'Resources/html/fim-html.php'; ?>