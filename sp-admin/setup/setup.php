<?php 
    // ========================================
    // setup
    // ========================================

    // verificar a sessão
    if(!isset($_SESSION['a'])){
        exit();
    } 
    
    //verifica se a está definido na URL
    $a = '';
    if(isset($_GET['a'])){
        $a = $_GET['a'];
    }

    //route do setup
    switch ($a) {
        case 'setup_criar_bd':        
            //executa os procedimentos para criação da base de dados
            include('setup/setup_criar_bd.php');
            break;
        
        case 'setup_inserir_utilizadores':
            //inserir utilizadores
            include('setup/setup_inserir_utilizadores.php');
            break;
    }


?>
<div class="container-fluid pad-20">

    <!-- titulo -->
    <h2 class="text-center">SETUP</h2>

    <div class="text-center">
        
        <!-- criar ou zerar a base de dados -->
        <p><a href="?a=setup_criar_bd" class="btn btn-secondary btn-size-250">Criar a Base de Dados</a></p>
        <!-- inserir usuários/utilizadores -->
        <p><a href="?a=setup_inserir_utilizadores" class="btn btn-secondary btn-size-250">Inserir utilizadores</a></p>
        
        <hr>

        <p><a href="?a=inicio" class="btn btn-secondary btn-size-150">Voltar</a></p>
    </div>
</div>