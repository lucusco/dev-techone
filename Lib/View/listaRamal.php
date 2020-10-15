<?php include_once BASE_DIR.'Resources/html/inicio-html.php'; ?>
  
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
    <h4 class="p-3">Ramais em uso</h4>
    <?php if (isset($_SESSION['msg'])) : ?>
        <div class="alert alert-<?= $_SESSION['tipo'] ?> alert-dismissible fade show m-3 w-75">
            <button class="close" type="button" data-dismiss="alert"> &times; </button>
            <?= $_SESSION['msg'] ?>
        </div>
        <?php unset($_SESSION['tipo']); unset($_SESSION['msg']); ?>
    <?php endif ?>
        <div class="table-responsive">
            <table class="table table-striped w-75 ml-3 ">
                <thead>
                    <tr class="table-primary">
                        <td style="width: 1rem"></td>
                        <td style="width: 1rem"></td>
                        <td>ID</td>
                        <td>Ramal</td>
                        <td>Usuário</td>
                        <td>Senha Registro</td>
                        <td>Tipo</td>
                    </tr>
                </thead>
                <tbody>
                <?php if (isset($ramais)) : ?>
                    <?php foreach ($ramais as $ramal): ?>
                    <tr>
                        <td><a href="#modalExclusao" 
                            class="btn btn-sm btn-outline-danger" 
                            data-toggle="modal"
                            data-id="<?php if (isset($ramal)) echo $ramal->id ?>" 
                            role="button">
                            <img src="<?= IMG_DIR ?>remove.svg" width="15" >
                            </a>
                        </td>
                        <td><a href="edita-ramal?method=editar&id=<?= $ramal->id ?>" class="btn btn-sm btn-outline-primary"><img src="<?= IMG_DIR ?>edit.svg" width="15"></a></td>
                        <td class="align-middle"><?= $ramal->id ?></td>
                        <td class="align-middle"><?= $ramal->exten ?></td>
                        <td class="align-middle"><?= $ramal->username ?></td>
                        <td class="align-middle"><?= $ramal->secret ?></td>
                        <td class="align-middle"><?= strtoupper($ramal->tech) ?></td>
                    </tr>
                    <?php endforeach ?>
                <?php endif ?>
                </tbody>
            </table>
        </div>
        <div class="p-3">
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <?php for ($i = 0; $i < $quantidadePaginas; $i++): ?>
                        <li class="page-item"><a class="page-link" href="lista-ramal?method=listar&page=<?=($i+1)?>"><?= ($i+1) ?></a></li> 
                    <?php endfor; ?>
                </ul>
            </nav>
        </div>
    <script>
        $('.btn-outline-danger').click(function(){
            var id=$(this).data('id');
            $('#btnModalExcluir').attr('href','exclui-ramal?method=excluir&id='+id);
        });
    </script>
<?php include_once BASE_DIR . 'Resources/html/fim-html.php'; ?>