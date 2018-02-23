<?php 
    // ========================================
    // login
    // ========================================

    // verificar a sessão
    if(!isset($_SESSION['a'])){
        exit();
    }  

    $erro = false;
    $sucesso = false;
    $mensagem = '';
    $gestor = new cl_gestorBD();

    // dados de cliente
    $nome = '';
    $email = '';
    $utilizador = '';
    $codigo_validacao = '';
    
    // ========================================
    // verifica se foi feito POST
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
      
        //recolha dos dados
        $nome = $_POST['text_nome_completo'];
        $email = $_POST['text_email'];
        $utilizador = $_POST['text_utilizador'];
        $senha1 = $_POST['text_senha_1'];
        $senha2 = $_POST['text_senha_2'];

        //verifica se as senhas são correspondentes (iguais)
        if($senha1 != $senha2){
            $erro = true;
            $mensagem = 'As senhas não coincidem.';
        }

        //verificar se já existe um cliente com os mesmo "dados"
        if(!$erro){
            $parametros = [
                ':nome'             => $nome,
                ':email'            => $email,
                ':utilizador'       => $utilizador
            ];

            $dados = $gestor->EXE_QUERY('
                SELECT * FROM clientes WHERE
                nome = :nome OR
                email = :email OR
                utilizador = :utilizador
            ',$parametros);

            if(count($dados) != 0){
                $erro = true;
                $mensagem = 'Já existe um cliente com dados iguais.';
            }
        }

        //vamos criar condições para criar a conta de cliente (em validação)
        if(!$erro){
            $codigo_validacao = funcoes::CriarCodigoAlfanumerico(30);
            $data = new DateTime();

            $parametros = [
                ':nome'             => $nome,
                ':email'            => $email,
                ':utilizador'       => $utilizador,
                ':palavra_passe'    => md5($senha1),
                ':codigo_validacao' => funcoes::CriarCodigoAlfanumericoSemSinais(30),
                ':validada'         => 0,
                ':criado_em'        => $data->format('Y-m-d H:i:s'),
                ':atualizado_em'    => $data->format('Y-m-d H:i:s')
            ];

            //regista o cliente na base de dados
            $gestor->EXE_NON_QUERY('
                INSERT INTO
                clientes(nome, email, utilizador, palavra_passe, codigo_validacao, validada, criado_em, atualizado_em)
                VALUES
                (:nome, :email, :utilizador, :palavra_passe, :codigo_validacao, :validada, :criado_em, :atualizado_em)
            ',$parametros);





            //envio do email para o cliente validar a sua nova conta
            $email_a_enviar = new emails();

            //criar o link de ativação
            $config = require_once('inc/config.php');
            $link = $config['BASE_URL'].'?a=validar&v='.$parametros[':codigo_validacao'];            

            //preparação dos dados do email
            $temp = [
                
                $email,
                
                'SPACET - Ativação da conta de cliente',
                
                '<p>Clique no link seguinte para validar a sua conta de cliente:</p>'.
                '<a href="'.$link.'">'.$link.'</a>'                
            ];

            //envio do email
            $mensagem_enviada = $email_a_enviar->EnviarEmailCliente($temp);
        }
    }
?>
<!-- ERRO ============================================== -->
<?php if($erro){
        echo '<div class="alert alert-danger text-center">'.$mensagem.'</div>';
    }
?>

<div class="container signup mt-5">
    <div class="text-center"><h3>NOVA CONTA DE CLIENTE</h3></div>    
    <div class="row">
        <div class="col-sm-6 offset-sm-3">
            
            <form action="" method="post">
                <!-- nome completo do cliente -->
                <div class="form-group">
                    <input type="text"
                           class="form-control"
                           name="text_nome_completo"
                           placeholder = "Nome completo"
                           value="<?php echo $nome ?>"
                           required>
                </div>
                <!-- email -->
                <div class="form-group">
                    <input type="email"
                           class="form-control"
                           name="text_email"
                           placeholder = "Email"
                           value="<?php echo $email ?>"
                           required>
                </div>
                <!-- utilizador -->
                <div class="form-group">
                    <input type="text"
                           class="form-control"
                           name="text_utilizador"
                           placeholder = "Utilizador"
                           value="<?php echo $utilizador ?>"
                           required>
                </div>
                <!-- senha 1 -->
                <div class="form-group">
                    <input type="password"
                           class="form-control"
                           name="text_senha_1"
                           placeholder = "Senha"
                           required>
                </div>
                <!-- senha 2 (verificação) -->
                <div class="form-group">
                    <input type="password"
                           class="form-control"
                           name="text_senha_2"
                           placeholder = "Repetir a senha"
                           required>
                </div>
                <!-- aceitação dos termos de utilização -->
                <div class="text-center form-group">
                    <input type="checkbox" 
                           name="check_termos" 
                           id="check_termos" 
                           class="mr-2"
                           required>
                           <label for="check_termos">Li e aceitos os <a href="">Termos de Utilização</a>.</label>
                </div>
                <!-- submeter -->
                <div class="text-center">                    
                    <button class="btn btn-primary mb-5">Criar cliente</button>
                </div>
            </form>
        </div>
    </div>
</div>