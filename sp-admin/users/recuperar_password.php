<?php

    // ========================================
    // formulário de login
    // ========================================    
    
    // verificar a sessão
    if(!isset($_SESSION['a'])){
        exit();
    }

    $erro = false;
    $mensagem = '';
    $mensagem_enviada = false;


    // verificar se existe um POST
    if($_SERVER['REQUEST_METHOD']=='POST'){
        $text_email = $_POST['text_email'];

        //criar o objeto da base de dados
        $gestor = new cl_gestorBD();

        //parametros
        $parametros = [
            ':email' => $text_email
        ];

        //pesquisar na bd para verificar se existe conta de utilizador com este email
        $dados = $gestor->EXE_QUERY('SELECT * FROM utilizadores WHERE email = :email',$parametros);

        //verificar se foi encontrado email
        if(count($dados) == 0){
            $erro = true;
            $mensagem = 'Não foi encontrada conta de utilizador com esse email.';
        }
        
        //no caso de não haver erro (foi encontrada conta de utilizador com o email indicado)
        else{

            //recuperar a password            
            $nova_password = funcoes::CriarCodigoAlfanumerico(15);

            //enviar o email
            $email = new emails();
            //preparação dos dados do email
            $temp = [
                $dados[0]['email'],
                'SPACET - Recuperação da password',
                '<h3>SPACET</H3><h4>RECUPERAÇÃO DA PASSWORD</h4><p>'.$nova_password.'</p>'
            ];

            //envio do email
            $mensagem_enviada = $email->EnviarEmail($temp);

            //alterar a senha na bd
            if($mensagem_enviada){
                $id_utilizador = $dados[0]['id_utilizador'];

                $parametros = [
                    ':id_utilizador'    => $id_utilizador,
                    ':palavra_passe'    => md5($nova_password)
                ];

                $gestor->EXE_NON_QUERY(
                    'UPDATE utilizadores
                    SET palavra_passe = :palavra_passe
                    WHERE id_utilizador = :id_utilizador', $parametros);

                //LOG
                funcoes::CriarLOG('O utilizador '.$dados[0]['nome'].' solicitou recuperação da password.', $dados[0]['nome']);

            } else {
                //aconteceu um erro
                $erro = true;
                $mensagem = 'ATENÇÃO: O email de recuperação não foi enviado com sucesso. Tente novamente.';
            }
        }
    }
?>

<?php if($mensagem_enviada == false) : ?>

    <!-- apresentação de erro -->
    <?php if($erro): ?>
        <div class="alert alert-danger text-center">
            <?php echo $mensagem ?>
        </div>
    <?php endif; ?>

    <!-- apresentação do formulário -->
    <div class="container-fluid">    
        <div class="row justify-content-center">
            <div class="col-md-4 card m-3 p-3">
            
                <form action="?a=recuperar_password" method="post">                
                    <div class="text-center">
                    <h3>Recuperar Password</h3>
                    <p>Coloque aqui o seu endereço de email para recuperação da password.</p>
                    </div>
                    <div class="form-group">
                        <input type="email" name="text_email" class="form-control" placeholder="email" required>
                    </div>                
                    <div class="form-group text-center">
                        <a href="?a=inicio" class="btn btn-primary btn-size-150">Cancelar</a>
                        <button role="submit" class="btn btn-primary btn-size-150">Recuperar</button>
                    </div>
                </form>            
            </div>        
        </div>
    </div>

<?php else :?>
    <!-- apresentação da mensagem de sucesso na recuperação da password -->
    <div class="container-fluid">    
        <div class="row justify-content-center">
            <div class="col-md-4 card m-3 p-3 text-center">
            
            <h2>RECUPERAÇÃO COM SUCESSO</h2>
            <p>A recuperação da password foi efetuada com sucesso.<br>Consulte a sua caixa de entrada do email para conhecer a nova password.</p>

            <div class="text-center">
            <a href="?a=inicio" class="btn btn-primary btn-size-150">Voltar</a>
            </div>

            </div>                    
        </div>
    </div>
<?php endif; ?>