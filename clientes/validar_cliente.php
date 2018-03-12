<?php 

    // ===========================================
    // validação da conta de cliente
    // ===========================================

    // verificar a sessão
    if(!isset($_SESSION['a'])){
        exit();
    } 

    $erro = false;
    $sucesso = false;
    $mensagem = '';

    //verifica se o valor 'v' está definido na url
    if(!isset($_GET['v'])){
        $erro = true;
        $mensagem = "Não está definido o código de ativação.";
    }

    //se v está definido, avança no processo
    if(!$erro){

        //vamos buscar o código de ativação
        $codigo_ativacao = $_GET['v'];

        //vamos perguntar à bd se existe um cliente com este código de ativação
        $gestor = new cl_gestorBD();
        $parametros = [
            ':codigo_validacao' => $codigo_ativacao
        ];
        $dados = $gestor->EXE_QUERY('SELECT * FROM clientes 
                                     WHERE codigo_validacao = :codigo_validacao', $parametros);
        
        //verificar se foi encontrado um cliente com o código de ativação
        if(count($dados)==0){
            //não foi encontrado nenhum cliente com o código indicado
            $erro = true;
            $mensagem = 'Não existe nenhum cliente com esse código.';
        }

        //vamos verificar se 'validada' já estava como valor 1
        if(!$erro){
            if($dados[0]['validada']==1){
                $erro = true;
                $mensagem = 'Esta conta já está validada.';
            }
        }

        //finalmente (ultrapassados os erros possíveis) > validar a conta
        if(!$erro){
            $parametros = [
                ':id_cliente'   => $dados[0]['id_cliente']
            ]; 
            $gestor->EXE_NON_QUERY('UPDATE clientes SET validada = 1
                                    WHERE id_cliente = :id_cliente', $parametros);
            
            //informar o cliente que a sua conta foi ativada
            $sucesso = true;
            $mensagem = "Conta ativada com sucesso.";
        }
    }
?>

<?php if($erro) : ?>

   <div class="alert alert-danger text-center"><?php echo $mensagem ?></div>           

<?php elseif($sucesso) : ?>

    <div class="alert alert-success text-center"><?php echo $mensagem ?></div>           

<?php endif; ?>
