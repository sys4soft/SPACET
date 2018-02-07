<?php 
    // ========================================
    // perfil - menu inicial
    // ========================================

    // verificar a sessão
    if(!isset($_SESSION['a'])){
        exit();
    }

    $erro = false;
    $mensagem = '';

    //verifica se tem permissão para aceder ao sistema
    if(!funcoes::Permissao(4)){
        $erro = true;
        $mensagem = 'Não tem permissão.';
    }

    //vai buscar todas as informações do utilizador
    $gestor = new cl_gestorBD();
    $parametros = [
        ':id_utilizador' => $_SESSION['id_utilizador']
    ];
    $dados = $gestor->EXE_QUERY(
        'SELECT * FROM utilizadores 
         WHERE id_utilizador = :id_utilizador',$parametros);
?>

<?php if($erro) :?>
    <div class="text-center">
        <h3><?php echo $mensagem ?></h3>
        <a href="?a=inicio" class="btn btn-primary btn-size-150">Voltar</a>
    </div>
<?php else :?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col card m-3 p-3">
        <h4 class="text-center">PERFIL DE UTILIZADOR</h4>        
        <!-- dados do utilizador -->
        <h5><i class="fa fa-user"></i> <?php echo $dados[0]['nome'] ?></h5>
        <p><i class="fa fa-envelope"></i> <?php echo $dados[0]['email'] ?></p>
        </div>          
    </div>
    <div class="text-center">
        <!-- voltar -->
        <a href="?a=inicio" class="btn btn-primary btn-size-150 m-3">Voltar</a>
    </div>
</div>

<?php endif; ?>