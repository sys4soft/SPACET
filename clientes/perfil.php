<?php     
    // verificar a sessão
    if(!isset($_SESSION['a'])){
        exit();
    }

    if(!funcoes::VerificarLoginCliente()){
        exit();
    }

    $erro = false;
    $sucesso = false;
    $mensagem = '';

    // =================================================
    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        $p = $_GET['p'];
        $id_cliente = $_SESSION['id_cliente'];
        $gestor = new cl_gestorBD();
        $data = new DateTime();

        switch ($p) {
            // -----------------------------------------
            case 1:
                // alterar o nome do cliente
                $parametros = [
                    ':id_cliente' => $id_cliente,
                    ':nome'       => $_POST['text_nome']
                ];

                $dados = $gestor->EXE_QUERY('SELECT id_cliente, nome FROM clientes
                                             WHERE id_cliente <> :id_cliente
                                             AND nome = :nome', $parametros);
                if(count($dados) != 0){
                    //foi encontrado outro cliente com o mesmo nome
                    $erro = true;
                    $mensagem = 'Já existe outro cliente com o mesmo nome.';
                } else {

                    $parametros = [
                        ':id_cliente'       => $id_cliente,
                        ':nome'             => $_POST['text_nome'],
                        ':atualizado_em'    => $data->format('Y-m-d H:i:s')
                    ];

                    $gestor->EXE_NON_QUERY('UPDATE clientes SET
                                            nome = :nome,
                                            atualizado_em = :atualizado_em
                                            WHERE id_cliente = :id_cliente',$parametros);
                    $sucesso = true;
                    $mensagem = 'Nome alterado com sucesso.';
                    
                }
                break;
            
                // -----------------------------------------
            case 2:
                // alterar o email do cliente
                $text_email_1 = $_POST['text_email'];
                $text_email_2 = $_POST['text_email_repetir'];

                //verifica se os emails introduzidos correspondem
                if($text_email_1 != $text_email_2){
                    $erro = true;
                    $mensagem = 'Os emails não correspondem.';
                }

                //verifica se já existe outro cliente com o mesmo email
                if(!$erro){                    
                    $parametros = [
                        ':id_cliente'   => $id_cliente,
                        ':email'        => $text_email_1
                    ];

                    $dados = $gestor->EXE_QUERY('SELECT id_cliente, email FROM clientes
                                                 WHERE id_cliente <> :id_cliente
                                                 AND email = :email', $parametros);
                    if(count($dados) != 0){
                        $erro = true;
                        $mensagem = 'Já existe outro cliente com o mesmo email.';
                    }
                }

                //atualização do email do cliente na base de dados
                if(!$erro){ 
                    $parametros = [
                        ':id_cliente'   => $id_cliente,
                        ':email'        => $text_email_1,
                        ':atualizado_em'=> $data->format('Y-m-d H:i:s')
                    ];

                    $gestor->EXE_NON_QUERY('UPDATE clientes SET
                                            email = :email,
                                            atualizado_em = :atualizado_em
                                            WHERE id_cliente = :id_cliente',$parametros);
                    $sucesso = true;
                    $mensagem = 'Email do cliente alterado com sucesso.';
                }
                break;
            
            // -----------------------------------------
            case 3:
                // alterar a senha do cliente
                $text_senha_atual = $_POST['text_senha_atual'];
                $text_senha_nova = $_POST['text_senha_nova'];
                $text_senha_nova_1 = $_POST['text_senha_nova_1'];
                
                //verificar se senha atual é a mesma da base de dados
                $parametros = [
                    ':id_cliente'           => $id_cliente,
                    ':palavra_passe'        => md5($text_senha_atual)
                ];
                $dados = $gestor->EXE_QUERY('SELECT id_cliente, palavra_passe FROM clientes
                                             WHERE id_cliente = :id_cliente 
                                             AND palavra_passe = :palavra_passe', $parametros);
                if(count($dados)==0){
                    //existe um erro - a senha não é igual à que se encontra na bd
                    $erro = true;
                    $mensagem = 'Senha atual não corresponde.';
                }

                //verificar se nova senha e senha repetida são iguais
                if(!$erro){
                    if($text_senha_nova != $text_senha_nova_1){
                        //as senhas novas não correspondem
                        $erro = true;
                        $mensagem = 'As senhas novas não correspondem.';
                    }
                }   

                //atualizar nova senha na base de dados
                if(!$erro){
                    $parametros = [
                        ':id_cliente'   =>$id_cliente,
                        ':palavra_passe'=> md5($text_senha_nova),
                        ':atualizado_em'=> $data->format('Y-m-d H:i:s')
                    ];

                    $gestor->EXE_NON_QUERY('UPDATE clientes SET
                                            palavra_passe = :palavra_passe,
                                            atualizado_em = :atualizado_em
                                            WHERE id_cliente = :id_cliente',$parametros);
                    $sucesso = true;
                    $mensagem = 'Senha alterada com sucesso.';
                }
                break;            
        }
    }

    // =================================================

    //vamos buscar os dados do cliente
    $parametros = [
        ':id_cliente' => $_SESSION['id_cliente']
    ];
    $gestor = new cl_gestorBD();
    $dados_cliente = $gestor->EXE_QUERY('SELECT * FROM clientes 
                                         WHERE id_cliente = :id_cliente', 
                                         $parametros);  
    $dados = $dados_cliente[0];    //passar os dados todos para um array unidimensional  
?>

<!-- erro -->
<?php if($erro):?>
    <div class="alert alert-danger text-center">
        <p><?php echo $mensagem ?></p>
    </div>
<?php endif;?>

<!-- sucesso -->
<?php if($sucesso):?>
    <div class="alert alert-success text-center">
        <p><?php echo $mensagem ?></p>
    </div>
<?php endif;?>


<div class="container-fluid perfil">
    <div class="container pt-5 pb-5">

        <h3 class="text-center mb-5">Editar perfil de cliente</h3>

        <div class="row">    
            <div class="col-sm-6 offset-sm-3 col-12">

        <div id="accordion">

            <!-- alterar utilizador -->
            <div class="card">
                <div class="card-header" id="caixa_utilizador">
                <h5 class="mb-0">
                    <button class="btn btn-link" data-toggle="collapse" data-target="#t_1" aria-expanded="true" aria-controls="collapseOne">
                    Alterar nome do utilizador
                    </button>
                </h5>
                </div>

                <div id="t_1" class="collapse show" aria-labelledby="caixa_utilizador" data-parent="#accordion">
                <div class="card-body">
                    
                    <!-- formulário para alterar o nome do utilizador -->
                    Nome atual: <b><?php echo $dados['nome'] ?></b>

                    <form action="?a=perfil&p=1" method="post" class="mt-3">
                        <div class="form-group">
                            <input type="text"
                                   class="form-control"
                                   name="text_nome" 
                                   placeholder="Novo nome" 
                                   required>
                        </div>
                        <div class="text-right">
                            <input type="submit" value="Alterar" class="btn btn-primary">
                        </div>
                    </form>

                </div>
                </div>
            </div>

            <!-- alterar email -->
            <div class="card">
                <div class="card-header" id="caixa_email">
                <h5 class="mb-0">
                    <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#t_2" aria-expanded="false" aria-controls="collapseTwo">
                    Alterar email
                    </button>
                </h5>
                </div>
                <div id="t_2" class="collapse" aria-labelledby="caixa_email" data-parent="#accordion">
                <div class="card-body">
                    

                    <!-- formulário para alterar o email -->
                    Email atual: <b><?php echo $dados['email'] ?></b>

                    <form action="?a=perfil&p=2" method="post" class="mt-3">
                        <div class="form-group">
                            <input type="email"
                                   class="form-control"
                                   name="text_email" 
                                   placeholder="Novo email" 
                                   required>
                        </div>

                        <div class="form-group">
                            <input type="email"
                                   class="form-control"
                                   name="text_email_repetir" 
                                   placeholder="Repetir novo email" 
                                   required>
                        </div>

                        <div class="text-right">
                            <input type="submit" value="Alterar" class="btn btn-primary">
                        </div>
                    </form>

                </div>
                </div>
            </div>

            <!-- alterar senha -->
            <div class="card">
                <div class="card-header" id="caixa_senha">
                <h5 class="mb-0">
                    <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#t_3" aria-expanded="false" aria-controls="collapseThree">
                    Alterar senha
                    </button>
                </h5>
                </div>
                <div id="t_3" class="collapse" aria-labelledby="caixa_senha" data-parent="#accordion">
                <div class="card-body">
                    

                    <!-- formulário para alterar a senha -->
                    <form action="?a=perfil&p=3" method="post" class="mt-3">
                        <div class="form-group">
                            <input type="password"
                                   class="form-control"
                                   name="text_senha_atual" 
                                   placeholder="Senha atual" 
                                   required>
                        </div>

                        <div class="form-group">
                            <input type="password"
                                   class="form-control"
                                   name="text_senha_nova" 
                                   placeholder="Nova senha" 
                                   required>
                        </div>

                        <div class="form-group">
                            <input type="password"
                                   class="form-control"
                                   name="text_senha_nova_1" 
                                   placeholder="Repetir a nova senha" 
                                   required>
                        </div>

                        <div class="text-right">
                            <input type="submit" value="Alterar" class="btn btn-primary">
                        </div>
                    </form>

                </div>
                </div>
            </div>


        </div>



            </div>
        </div>
    </div>
</div>