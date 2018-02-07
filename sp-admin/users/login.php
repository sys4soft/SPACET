<?php

    // ========================================
    // formulário de login
    // ========================================    
    
    // verificar a sessão
    if(!isset($_SESSION['a'])){
        exit();
    }

    $erro = true;    
    $mensagem = '';
    
    //verificar se foi feito um POST    
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        
        //verificar se os dados do login estão corretos
        $gestor = new cl_gestorBD();

        //preparação dos parâmetros
        $parametros = [
            ':utilizador'       => $_POST['text_utilizador'],
            ':palavra_passe'    => md5($_POST['text_password'])
        ];

        //procurar o utilizador na base de dados
        $dados = $gestor->EXE_QUERY(
            'SELECT * FROM utilizadores
             WHERE utilizador = :utilizador
             AND palavra_passe = :palavra_passe',$parametros);
        
        if(count($dados) == 0){
            //login inválido
            $erro = true;
            $mensagem = 'Dados de login inválidos.';
        } else {
            //login válido
            $erro = false;
            //inicia a sessão
            funcoes::IniciarSessao($dados);

            //LOG
            funcoes::CriarLOG('Utilizador '.$_SESSION['nome'].' fez login.', $_SESSION['nome']);
        }
    }
?>

<?php if($erro) : // =============================?>

    <?php 
        if($mensagem!=''){
            echo '<div class="alert alert-danger text-center">'.$mensagem.'</div>';
        }
    ?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-4 card m-3 p-3">
           
            <form action="?a=login" method="post">
                <div class="form-group">
                    <input type="text" name="text_utilizador" class="form-control" placeholder="Utilizador">
                </div>
                <div class="form-group">
                    <input type="password" name="text_password" class="form-control" placeholder="Password">
                </div>
                <div class="form-group text-center">
                    <button role="submit" class="btn btn-primary btn-size-150">Login</button>
                </div>
            </form>

            <div class="text-center">
                <a href="?a=recuperar_password">Recuperar password</a>
            </div>

        </div>        
    </div>
</div>

<?php else : // ==================================?>

    <div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-4 card m-3 p-3 text-center">
            <p>Bem-vindo, <strong><?php echo $dados[0]['nome'] ?></strong></p>
            <a href="?a=inicio" class="btn btn-primary">Avançar</a>
        </div>        
    </div>
</div>   

<?php endif; ?>

<div class="text-center">
    <a href="?a=setup" class="btn btn-secondary">Setup</a>
</div>


