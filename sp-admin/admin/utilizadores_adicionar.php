<?php
    // ========================================
    // gestão de utilizadores - adicionar novo utilizador
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
    
    $gestor = new cl_gestorBD();
    $erro = false;
    $sucesso = false;
    $mensagem = '';


    //==================================================================================
    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        //vai buscar os valores do formulário
        $utilizador =       $_POST['text_utilizador'];
        $password  =        $_POST['text_password'];
        $nome_completo =    $_POST['text_nome'];
        $email =            $_POST['text_email'];

        //permissoes
        $total_permissoes = (count(include('inc/permissoes.php')));
        $permissoes = [];
        if(isset($_POST['check_permissao'])){
            $permissoes = $_POST['check_permissao'];
        }
        $permissoes_finais = '';
        for ($i=0; $i < 100; $i++) { 
            if($i<$total_permissoes){
                if(in_array($i, $permissoes)){
                    $permissoes_finais.='1';        
                } else {
                    $permissoes_finais.='0';
                }
            } else {
                $permissoes_finais.='1';
            }        
        }
        
        //------------------------------------------------------
        // verifica os dados na base de dados

        //------------------------------------------------------
        // verfica se existe utilizador com nome igual
        $parametros = [
            ':utilizador' => $utilizador
        ];

        $dtemp = $gestor->EXE_QUERY('SELECT utilizador 
                                     FROM utilizadores
                                     WHERE utilizador = :utilizador', $parametros);
        if(count($dtemp)!=0){
            $erro = true;
            $mensagem = 'Já existe um utilizador com o mesmo nome.';
        }

        //------------------------------------------------------
        //verifica se existe outro utilizador com o mesmo email
        if(!$erro){
            $parametros = [
                ':email' => $email
            ];

            $dtemp = $gestor->EXE_QUERY('SELECT email 
                                         FROM utilizadores
                                         WHERE email = :email', $parametros);
            if(count($dtemp)!=0){
                $erro = true;
                $mensagem = 'Já existe outro utilizador com o mesmo email.';
            }                          
        }
        
        //------------------------------------------------------
        //guardar na base de dados
        if(!$erro){
            $parametros = [
                ':utilizador'       => $utilizador,
                ':palavra_passe'    => md5($password),
                ':nome'             => $nome_completo,
                ':email'            => $email,
                ':permissoes'       => $permissoes_finais,
                ':criado_em'        => DATAS::DataHoraAtualBD(),
                ':atualizado_em'    => DATAS::DataHoraAtualBD()
            ];

            $gestor->EXE_NON_QUERY('
                INSERT INTO utilizadores
                    (utilizador, palavra_passe, nome, email, permissoes, criado_em, atualizado_em)
                VALUES
                    (:utilizador, :palavra_passe, :nome, :email, :permissoes, :criado_em, :atualizado_em)',$parametros);
            
            //enviar o email para o novo utilizador            
            $mensagem = [
                $email,
                'SPACET - Criação de nova conta de utilizador',
                "<p>Foi criada a nova conta de utilizador com os seguintes dados:<p><p>Utilizador: $utilizador <p><p>Password: $password </p>"
            ];
            $mail = new emails();
            $mail->EnviarEmail($mensagem);
            

            //apresentar um alerta de sucesso
            echo '<div class="alert alert-success text-center">Novo utilizador adicionado com sucesso.</div>';
        }
        
    }    
    //==================================================================================
?>

<!-- apresenta o erro no caso de existir -->
<?php 
    if($erro){
        echo '<div class="alert alert-danger text-center">'.$mensagem.'</div>';
    }
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-8 card m-3 p-3">
        <h4 class="text-center">ADICIONAR NOVO UTILIZADOR</h4>
        <hr>
        
        <!-- formulário para adicionar novo utilizador -->
        <form action="?a=utilizadores_adicionar" method="post">
        
            <!-- utilizador -->
            <div class="form-group">
                <label>Utilizador:</label>
                <input type="text"
                       name="text_utilizador"
                       class="form-control"
                       pattern=".{3,50}"
                       title="Entre 3 e 50 caracteres."
                       required>
            </div>
            <!-- password -->
            <div class="form-group">
                <label>Password:</label>

                <div class="row">                    
                    <div class="col">
                        <input type="text"
                            name="text_password"
                            id="text_password"
                            class="form-control"
                            pattern=".{3,30}"
                            title="Entre 3 e 30 caracteres."
                            required>
                    </div>
                    <div class="col">
                        <button type="button" class="btn btn-primary" onclick="gerarPassword(10)">Gerar password</button>
                    </div>
                </div>
            </div>

            <!-- nome completo -->
            <div class="form-group">
                <label>Nome:</label>
                <input type="text"
                       name="text_nome"
                       class="form-control"
                       pattern=".{3,50}"
                       title="Entre 3 e 50 caracteres."
                       required>
            </div>

            <!-- email -->
            <div class="form-group">
                <label>Email:</label>
                <input type="email"
                       name="text_email"
                       class="form-control"
                       pattern=".{3,50}"
                       title="Entre 3 e 50 caracteres."
                       required>
            </div>

            <div class="text-center">
                <a href="?a=utilizadores_gerir" class="btn btn-primary btn-size-150">Cancelar</a>
                <button class="btn btn-primary btn-size-150">Criar utilizador</button>
            </div>

            <hr>

            <div class="text-center m-3">
                <button type="button" 
                        class="btn btn-primary btn-size-150"
                        data-toggle="collapse" 
                        data-target="#caixa_permissoes">Definir permissões</button>
            </div>

            <!-- caixa permissões -->
            <div class="collapse" id="caixa_permissoes">
                <div class="card p-3 caixa-permissoes">                                    
                    <?php 
                        $permissoes = include('inc/permissoes.php');
                        $id=0;
                        foreach($permissoes as $permissao){ ?>                    
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="check_permissao[]" id="check_permissao" value="<?php echo $id ?> ">
                                <span class="permissao-titulo"><?php echo $permissao['permissao'] ?></span>
                            </label>
                            <p class="permissao-sumario"><?php echo $permissao['sumario'] ?></p>
                        </div>
                    <?php $id++; } ?>
                
                    <!-- todas | nenhuma -->
                    <div>
                        <a href="#" onclick="checks(true); return false">Todas</a> | <a href="#" onclick="checks(false); return false">Nenhumas</a>
                    </div>
                </div>
            </div>
        </form>

        </div>        
    </div>
</div>
