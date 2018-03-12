<?php     
    // verificar a sessão
    if(!isset($_SESSION['a'])){
        exit();
    } 

    $erro = false;
    $mensagem = '';

    //verifica se os dados de login estão corretos
    $utilizador = $_POST['text_utilizador'];
    $senha = md5($_POST['text_senha']);

    //prepara a query para o login
    $gestor = new cl_gestorBD();
    $parametros = [
        ':utilizador'       => $utilizador,
        ':palavra_passe'    => $senha
    ];
    $dados = $gestor->EXE_QUERY('
        SELECT * FROM clientes
        WHERE utilizador = :utilizador
        AND palavra_passe = :palavra_passe
    ',$parametros);

    //verifica se existe dados
    if(count($dados) == 0){
        //não foi encontrado nenhum cliente com os dados indicados
        $erro = true;
        $mensagem = "Não existe conta de cliente.";
    } else {
        //caso exista um cliente, vamos verificar se a conta está validada
        if($dados[0]['validada'] == 0){
            $erro = true;
            $mensagem = 'Atenção: verifica a sua caixa de correio eletrónico, uma vez que a sua conta de cliente não foi ainda validada.';
        }
    }

    if(!$erro){
        //login efetuado com sucesso
        funcoes::IniciarSessaoCliente($dados);
    }
?>

<?php if($erro):?>
<div class="alert alert-danger">
    <p><?php echo $mensagem ?></p>
</div>
<?php else :?>
    
<div class="container-fluid">
    
    <div class="row">
        <div class="col-4 offset-4 mt-5 mb-5 card p-4 text-center">
            <p>Bem-vindo(a), <b><?php echo $dados[0]['nome'] ?></b>.</p>
            <a href="?a=home" class="btn btn-primary">Ok</a>
        </div>
    </div>

</div>

<?php endif;?>