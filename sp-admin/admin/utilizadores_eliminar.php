<?php
    // ========================================
    // gestão de utilizadores - eliminar utilizador
    // ========================================

    // verificar a sessão
    if(!isset($_SESSION['a'])){
        exit();
    }
    
    //verificar permissão
    $erro_permissao = false;
    if(!funcoes::Permissao(0)){
        $erro_permissao = true;
    }

    //verifica se id_utilizador está definido
    $id_utilizador = -1;
    if(isset($_GET['id'])){
        $id_utilizador = $_GET['id'];
    } else {
        $erro_permissao = true;
    }

    //verifica se pode avançar (id_utilizador != 1 e != do da sessão)
    if($id_utilizador == 1 || $id_utilizador == $_SESSION['id_utilizador']){
        $erro_permissao = true;
    }

    // ==============================================================
    $dados_utilizador = null;
    $gestor = new cl_gestorBD();
    if(!$erro_permissao){
        //buscar os dados do utilizador
        $parametros = [':id_utilizador' => $id_utilizador];
        $dados_utilizador = $gestor->EXE_QUERY('SELECT * FROM utilizadores
                                                WHERE id_utilizador = :id_utilizador', $parametros);
    }

    // ==============================================================
    // verifica se foi dada resposta afirmativa para eliminação
    $sucesso = false;
    if(isset($_GET['r'])){
        if($_GET['r']==1){
            
            //remover o utilizador da base de dados
            $parametros = [':id_utilizador' => $id_utilizador];            
            $gestor->EXE_NON_QUERY('DELETE FROM utilizadores WHERE id_utilizador = :id_utilizador', $parametros);

            //informa o sistema que a remoção do utilizador aconteceu com sucesso.
            $sucesso = true;
        }
    }
?>

<!-- sem permissão -->
<?php if($erro_permissao) : ?>
    <?php include('inc/sem_permissao.php') ?>
<?php else : ?>

    <!-- remoção com sucesso -->
    <?php if($sucesso) :?>
        
        <div class="container">
            <div class="row mt-5 mb-5">
                <div class="col-md-6 offset-md-3 text-center">
                    <p class="alert alert-success">Utilizador removido com sucesso.</p>
                    <a href="?a=utilizadores_gerir" class="btn btn-primary btn-size-100">Voltar</a>
                </div>
            </div>
        </div>

    <?php else : ?>

        <!-- apresentação dos dados do utilizador a remover -->
        <div class="container">
            <div class="mt-3 mb-3 p-3">
                <h4 class="text-center">REMOVER UTILIZADOR</h4>                    

                <!-- dados do utilizador -->    
                <div class="row">
                    <div class="col-md-8 offset-md-2 card mt-3 mb-3 p-3">

                        <p class="text-center">Tem a certeza que pretende eliminar o utilizador:<br><strong><?php echo $dados_utilizador[0]['nome'] ?></strong>, cujo email é <strong><?php echo $dados_utilizador[0]['email'] ?></strong> ?</p>

                        <!-- botões não e sim -->
                        <div class="text-center mt-3 mb-3">
                            <a href="?a=utilizadores_gerir" class="btn btn-primary btn-size-100">Não</a>
                            <a href="?a=eliminar_utilizador&id=<?php echo $id_utilizador ?>&r=1" class="btn btn-primary btn-size-100">Sim</a>
                        </div>

                    </div>
                </div>

            </div>
        </div>

    <?php endif; ?>

<?php endif; ?>