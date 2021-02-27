{include file="html-inicio.tpl"}
{* Estrutura padrão para conteúdos 3-tabs *}
            <main>
                <div class="container-fluid">
                    <h1 class="mt-4">Configurações Gerais</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Configurações Gerais</li>
                    </ol>
                    <div class="row">
                        <div class="col-xl-12 col-md-10">
                            <form action="salvar-config" method="post">
                                <div class="form-group">
                                    <label class="h5">Faixa de Ramais</label>
                                    <div class="row">
                                        <div class="col-md-2 col-sm-1">
                                            <label for="rangeinicio" class="form-label">Ramal inicial</label>
                                            <input class="form-control" type="text" name="rangeinicio" id="rangeinicio" placeholder="Ramal inicial">
                                        </div>
                                        <div class="col-md-2 col-sm-1">
                                            <label for="rangefim" class="form-label">Ramal final</label>
                                            <input class="form-control" type="text" name="rangefim" id="rangefim" placeholder="Ramal Final">
                                        </div>
                                    </div>
                                    <button class="btn btn-success mt-3">Salvar</button>
                                </div>
                            </form>
                        </div>
                    </div>     
                </div>
            </main>
{include file="html-fim.tpl"}