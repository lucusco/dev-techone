<?php include_once BASE_DIR.'Resources/html/inicio-html.php'; ?>

    <div class="p-3">
        <h2 class="mb-5">Bem venido</h2>
        <p class="">
            Em breve uma tabela de informações aqui
        </p>
        <hr>
        <h3 class="mb-4">Área de testes</h3>
        <form action="testes?method=testarMetodo" method="post">
            <div class="form-group">
            <div class="form-group">
                    <label for="metodo" id="metodo">Namespace</label>
                    <input class="form-control w-25" type="text" id="namespace" name="namespace">
                </div>
                <div class="form-group">
                    <label for="metodo" id="metodo">Classe</label>
                    <input class="form-control w-25" type="text" id="classe" name="classe">
                </div>
                <div class="form-group">
                    <label for="rota">Método</label>
                    <input class="form-control w-25" type="text" id="metodo" name="metodo">
                </div>
                <div class="form-group">
                    <label for="rota">Conexão?</label>
                    <input class="form-control w-25" type="text" id="conexao" name="conexao">
                </div>
            </div>
            <button class="btn btn-success">Testar</button>
        </form>
    </div>

<?php include_once BASE_DIR.'Resources/html/fim-html.php'; ?>
    