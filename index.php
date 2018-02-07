<?php

    // ========================================
    // index da página web
    // ========================================    
    
    //controlo de sessão
    session_start();
    if(!isset($_SESSION['a'])){
        $_SESSION['a'] = 'inicio';
    }

    //inclui as funcoes necessárias do sistema 
    include_once('inc/funcoes.php');
    include_once('inc/cl_datas.php');
    include_once('inc/emails.php');
    include_once('inc/gestorBD.php');    

    include_once('_cabecalho.php');    

    include_once('routes.php');

    include_once('_rodape.php');
?>