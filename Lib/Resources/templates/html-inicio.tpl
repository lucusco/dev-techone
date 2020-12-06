<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="{$css_dir}bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="{$css_dir}estilo.css">
    <link rel="icon" type="image/png" href="assets/favicon.ico">
    <!-- Bootstrap JS -->
    <script src="{$css_dir}jquery.min.js"></script>
    <script src="{$css_dir}popper.min.js"></script>
    <script src="{$css_dir}bootstrap.min.js"></script>
    <script src="{$css_dir}sweetalert.min.js"></script>

    <style>
        .sidebar {
            box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
        /*position: fixed;
        /*top: 0;   
        bottom: 0;
        left: 0;
        z-index: 1;
        padding: 48px 0 0;
        box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1); */
        }
        .borda {
            border: 1px solid red;
        }
    </style>

    <title>techoOne</title>
</head>
<body>
    <div class="container">
        <div class="">
            <nav class="navbar sticky-top navbar-expand-md navbar-dark bg-primary shadow">
                <!-- Logo -->
                <img class="rounded mr-2" src="{$img_dir}logo.jpg" width="45" loading="lazy" alt="Logo">
                <a class="navbar-brand mr-auto h1 mb-0">techOne</a>
                <!-- Botao hamburguer -->
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#conteudoNavbar" aria-controls="conteudoSuportado" aria-expanded="false" aria-label="Alterna navegação">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <!-- Menus -->
                <div class="ml-3 collapse navbar-collapse" id="conteudoNavbar">
                    <ul class="navbar-nav mr-auto font-weight-bold mt-1">
                        <li class=""><a href="home" class="nav-link">Início</a></li>
                        <!-- Item dropdown Ramais -->
                        <li class="nav-item dropdown ">
                            <a href="#" class="nav-link dropdown-toggle"  data-toggle="dropdown">Ramal</a>
                            <div class="dropdown-menu ">
                                <a href="novo-ramal" class="dropdown-item ">Novo ramal</a>
                                <a href="lista-ramal?method=listar&page=1" class="dropdown-item">Ramais em uso</a>
                                <a href="importa-ramal" class="dropdown-item">Importar & Exportar</a>
                            </div>
                        </li>
                        <!-- Item dropdown Filas -->
                        <li class="nav-item dropdown ">
                            <a href="#" class="nav-link dropdown-toggle disabled"  data-toggle="dropdown">Fila</a>
                            <div class="dropdown-menu ">
                                <a href="#" class="dropdown-item ">Nova Fila</a>
                                <a href="#" class="dropdown-item">Filas em uso</a>
                            </div>
                        </li>
                        <li class=""><a href="escreveArqs" class="nav-link">Configurações</a></li>
                    </ul>
                    <ul class="navbar-nav font-weight-bold mt-1">
                        <li class="nav-link active">Bem-vindo Fulano</li>
                        <li class="nav-item "><a href="sair" class="nav-link disabled">Sair</a></li>
                    </ul>   
                </div>
            </nav>
        </div>  
       <div>
            <div class="row no-gutters">
                <div class="col-2">
                    <nav class="bg-light sidebar"> 
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link" href="#"> Dashboard </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#"> Ramal </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#"> Fila </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#"> Configurações </a>
                            </li>             
                        </ul> 
                    </nav>
                </div>