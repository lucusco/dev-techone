<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="<?=CSS_DIR?>bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="<?=CSS_DIR?>estilo.css">
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@400&display=swap" rel="stylesheet">
    <!-- Bootstrap JS -->
    <script src="<?=CSS_DIR?>jquery.slim.min.js"></script>
    <script src="<?=CSS_DIR?>popper.min.js"></script>
    <script src="<?=CSS_DIR?>bootstrap.min.js"></script>
    <title>techoOne</title>
</head>
<body>
    <nav class="navbar sticky-top navbar-expand-md navbar-dark bg-primary">
        <!-- Logo -->
        <img class="rounded mr-2" src="<?=IMG_DIR?>logo.jpg" width="45" loading="lazy" alt="Logo">
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
                <li class=""><a href="#" class="nav-link">Testes</a></li>
            </ul>
            <ul class="navbar-nav font-weight-bold mt-1">
                <li class="nav-link active">Bem-vindo Fulano</li>
                <li class="nav-item "><a href="sair" class="nav-link disabled">Sair</a></li>
            </ul>   
        </div>
    </nav>