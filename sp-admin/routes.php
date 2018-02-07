<?php 
    // ========================================
    // routes do backend
    // ========================================

    // verificar a sessão
    if(!isset($_SESSION['a'])){
        exit();
    }

    $a = 'inicio';
    if(isset($_GET['a'])){
        $a = $_GET['a'];
    }

    // verificar o login
    if(!funcoes::VerificarLogin()){
        
        //caso especiais
        $routes_especiais = [
            'recuperar_password',
            'setup',
            'setup_criar_bd',
            'setup_inserir_utilizadores'
        ];

        //bypass do sistema normal
        if(!in_array($a, $routes_especiais)){
            $a='login';
        }                        
    }

    // ========================================
    // ROUTES
    // ========================================
    switch ($a) {

        // =====================================
        // login
        case 'login':                           include_once('users/login.php'); break;
        // logout
        case 'logout':                          include_once('users/logout.php'); break;
        // recuperar password
        case 'recuperar_password':              include_once('users/recuperar_password.php'); break;

        // =====================================
        // perfil
        case 'perfil':                          include_once('users/perfil/perfil_menu.php'); break;
        //alterar password
        case 'perfil_alterar_password':         include_once('users/perfil/perfil_alterar_password.php'); break;
        //alterar email
        case 'perfil_alterar_email':            include_once('users/perfil/perfil_alterar_email.php'); break;
        
        // =====================================
        //opções do administrador
        case 'utilizadores_gerir':              include_once('admin/utilizadores_gerir.php'); break;
        //formulário para adicionar novo utilizador
        case 'utilizadores_adicionar':          include_once('admin/utilizadores_adicionar.php'); break;
        //editar utilizador
        case 'editar_utilizador':               include_once('admin/utilizadores_editar.php'); break;
        //editar permissões
        case 'editar_permissoes':               include_once('admin/utilizadores_permissoes_editar.php'); break;
        //eliminar utilizador
        case 'eliminar_utilizador':             include_once('admin/utilizadores_eliminar.php'); break;



        //apresentar a página inicial
        case 'inicio':                          include_once('inicio.php'); break;
        //apresenta a página acerca de
        case 'about':                           include_once('about.php'); break;
        //abre o menu do setup
        case 'setup':                           include_once('setup/setup.php'); break;







        // =====================================
        // SETUP
        //setup - criar a base de dados
        case 'setup_criar_bd':                  include_once('setup/setup.php'); break;
        //setup - inserir utilizadores
        case 'setup_inserir_utilizadores':      include_once('setup/setup.php'); break;
    }
?>