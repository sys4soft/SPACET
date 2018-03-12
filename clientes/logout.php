<?php 
    // ========================================
    // routes da página web
    // ========================================

    // verificar a sessão
    if(!isset($_SESSION['a'])){
        exit();
    }

    //guardar o nome do cliente para ser apresentado na "despedida"
    $nome = $_SESSION['nome_cliente'];

    //executa a destruição da sessão do cliente
    funcoes::DestroiSessaoCliente();

?>

<div class="container">
    <div class="row">
        <div class="col-sm-4 offset-sm-4 text-center p-3 card mt-5 mb-5">
            <p>Até à próxima visita <b><?php echo $nome ?></b></p>
            <div>
                <a href="?a=home" class="btn btn-primary btn-size-150">OK</a>
            </div>
        </div>
    </div>
</div>