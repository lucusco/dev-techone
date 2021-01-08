<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" type="text/css" href="{$css_dir}bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="{$css_dir}estilo.css">
    <link rel="stylesheet" type="text/css" href="{$css_dir}sidebar.css">
    <link rel="icon" type="image/svg" href="assets/images/asterisk-icon.svg">
    <!-- Bootstrap JS -->
    <script src="{$css_dir}jquery.min.js"></script>
    <script src="{$css_dir}popper.min.js"></script>
    <script src="{$css_dir}bootstrap.min.js"></script>
    <script src="{$css_dir}sweetalert.min.js"></script>
    
    <!-- Font Awesome -->
    {* <link rel="stylesheet" type="text/css" href="{$css_dir}awesomefonts-all.min.css">
    <link rel="stylesheet" type="text/css" href="{$css_dir}awesomefonts-solid.min.css"> *}
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    
    <title>techoOne</title>
</head>
<body class="sb-nav-fixed">
    <!-- Nav Topo -->
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand" href="home"><i class="fas fa-asterisk fa-lg orange"></i> tehcOne </a>
        <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button>
        <!-- Navbar Search-->
        <form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">
            <div class="input-group">
                <input class="form-control" type="text" placeholder="Pesquisar" aria-label="Search" aria-describedby="basic-addon2" />
                <div class="input-group-append">
                    <button class="btn btn-primary" type="button"><i class="fas fa-search"></i></button>
                </div>
            </div>
        </form>
        <!-- Navbar-->
        <ul class="navbar-nav ml-auto ml-md-0">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="#">Configurações</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">Sair</a>
                </div>
            </li>
        </ul>
    </nav>
    <!-- Fim nav topo -->
     <!-- Sidebar -->
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading"></div>
                        <a class="nav-link" href="home">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>
                        <div class="sb-sidenav-menu-heading">Cadastros</div>
                        <!-- Menu Ramais -->
                        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLayoutsRamais" aria-expanded="false" aria-controls="collapseLayouts">
                            <div class="sb-nav-link-icon"><i class="fas fa-tty"></i></div>
                            Ramais
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseLayoutsRamais" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="novo-ramal">Novo Ramal</a>
                                <a class="nav-link" href="lista-ramal?method=listar&page=1">Ramais em Uso</a>
                                <a class="nav-link" href="importa-ramal">Importar / Exportar</a>
                            </nav>
                        </div>
                        <!-- Menu Filas -->
                        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLayoutsFilas" aria-expanded="false" aria-controls="collapseLayouts">
                            <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                            Filas
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseLayoutsFilas" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="nova-fila">Nova Fila</a>
                                <a class="nav-link" href="">Filas em Uso</a>
                            </nav>
                        </div>
                        <!-- Menu Exemplo -->
                        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="false" aria-controls="collapsePages">
                            <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                            Exemplo
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapsePages" aria-labelledby="headingTwo" data-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#pagesCollapseAuth" aria-expanded="false" aria-controls="pagesCollapseAuth">
                                    Usuários
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <div class="collapse" id="pagesCollapseAuth" aria-labelledby="headingOne" data-parent="#sidenavAccordionPages">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <a class="nav-link" href="">Novo</a>
                                        <a class="nav-link" href="">Em uso</a>
                                        <a class="nav-link" href="">Resetar Senha</a>
                                    </nav>
                                </div>
                            </nav>
                        </div>
                        <!-- Fim Menu Cadastros -->
                        <div class="sb-sidenav-menu-heading">Funcionalidades</div>
                        <!-- Menu Relatórios -->
                        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseRelatorios" aria-expanded="false" aria-controls="collapseLayouts">
                            <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                            Relatórios
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseRelatorios" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="">Chamadas</a>
                                <a class="nav-link" href="">Status</a>
                            </nav>
                        </div>
                        <!-- Fim Menu Rel. -->
                        <a class="nav-link" href="">
                            <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                            Ferramentas
                        </a>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Bem vindo(a)</div>
                    Fulano de Tal
                </div>
            </nav>
        </div>
        <!-- Fim Sidebar -->
        <!-- Início Conteúdo -->
        <div id="layoutSidenav_content">
