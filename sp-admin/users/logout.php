<?php 
    // ========================================
    // logout
    // ========================================

    // verificar a sessão
    if(!isset($_SESSION['a'])){
        exit();
    }

    $nome = $_SESSION['nome'];

    //executa o logout (destruição) da sessão
    funcoes::DestroiSessao();

    //LOG
    funcoes::CriarLOG('Utilizador '.$nome.' fez logout.', $nome);
?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-4 card m-3 p-3 text-center">            
            <p>Até à próxima visita, <?php echo $nome ?></p>
            <a href="?a=inicio" class="btn btn-primary">Início</a>
        </div>        
    </div>
</div>