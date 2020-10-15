<?php include_once BASE_DIR . 'Resources/html/inicio-html.php'; ?>
    <!-- Form Add Ramal -->
    <?php if (isset($_SESSION['msg'])) : ?>
        <div class="alert alert-<?= $_SESSION['tipo'] ?> alert-dismissible fade show m-3 w-75">
            <button class="close" type="button" data-dismiss="alert"> &times; </button>
            <?= $_SESSION['msg'] ?>
        </div>
        <?php unset($_SESSION['tipo']); unset($_SESSION['msg']); ?>
    <?php endif ?>
    <div>
        <form action="salvar-ramal?method=persistir<?= isset($ramal->id) ? '&id='.$ramal->id : ''?>" method="post">
            <div class="form-group w-50">
                <fieldset class="rounded">
                    <legend><?= $titulo ?></legend>
                    <div class="form-group">
                        <label for="ramal">Ramal</label>
                        <input class="form-control" type="text" name="ramal" id="ramal" value="<?= isset($ramal) ? $ramal->exten : '' ?>">
                    </div>
                    <div class="form-group">
                        <label for="nome">Descrição</label>
                        <input class="form-control" type="text" name="nome" id="nome" value="<?= isset($ramal) ? $ramal->username : '' ?>">
                    </div>
                    <div class="form-group">
                        <label for="senha">Senha de Registro</label>
                        <input class="form-control" type="text" name="senha" id="senha" value="<?= isset($ramal) ? $ramal->secret : '' ?>">
                    </div>
                    <div class="form-group">
                        <label for="context">Contexto</label>
                        <input class="form-control" type="text" name="context" id="context" value="<?= isset($ramal) ? $ramal->context : '' ?>">
                    </div>
                    <div class="form-group">
                        <label>Tecnologia</label>
                        <select class="form-control" name="tech">
                            <option value="0" <?= isset($ramal) ? '' : 'selected' ?> >Selecione uma opção</option>
                            <option value="sip"<?php if (isset($ramal) && $ramal->tech == 'SIP') echo 'selected'?>>SIP</option>
                            <option value="iax"<?php if (isset($ramal) && $ramal->tech == 'IAX') echo 'selected'?>>IAX</option>
                        </select>
                    </div>
                    <br>
                    <div class="form-group">
                        <label>Gravar Chamadas</label><br>
                        <input type="radio" name="gravacao" id="gravacao" value="on"<?php if (isset($ramal) && $ramal->recording == true) echo 'checked'?>> Sim <br>
                        <input type="radio" name="gravacao" id="gravacao" value="off"<?php if (isset($ramal) && $ramal->recording == false) echo 'checked'?>> Não
                    </div>
                    <div>
                        <button class="btn btn-success">Adicionar</button>
                    </div>
                </fieldset>
            </div>
        </form>
    </div>
<?php include_once BASE_DIR . 'Resources/html/fim-html.php'; ?>
