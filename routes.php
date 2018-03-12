<?php 
    // ========================================
    // routes da página web
    // ========================================

    // verificar a sessão
    if(!isset($_SESSION['a'])){
        exit();
    }

    $a = 'home';
    if(isset($_GET['a'])){
        $a = $_GET['a'];
    }    

    // ========================================
    // ROUTES
    // ========================================
    switch ($a) {

        // =====================================
        // home
        case 'home':                            include_once('webgeral/index.php'); break;                

        // =====================================
        // CLIENTES

        // validar conta de cliente
        case 'validar':                         include_once('clientes/validar_cliente.php'); break;
        // login
        case 'login':                           include_once('clientes/login.php'); break;
        // signup
        case 'signup':                          include_once('clientes/signup.php'); break;
        // logout        
        case 'logout':                          include_once('clientes/logout.php'); break;

        // perfil 
        case 'perfil':                          include_once('clientes/perfil.php'); break;
    }
?>