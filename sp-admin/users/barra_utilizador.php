<?php 
    // ========================================
    // barra do utilizador
    // ========================================

    // verificar a sessão
    if(!isset($_SESSION['a'])){
        exit();
    }

    $nome_utilizador = 'Nenhum utilizador ativo.';
    $classe = 'barra_utilizador_inativo';

    //verifica se existe sessão
    if(funcoes::VerificarLogin()){
        $nome_utilizador = $_SESSION['nome'];
        $classe = 'barra_utilizador_ativo';
    }

?>
<div class="barra_utilizadores">
    
    <?php if(funcoes::VerificarLogin()): ?>
        
    <!-- dropdown -->
    <div class="dropdown">
        <span class="mr-3"><i class="fa fa-user mr-3"></i><?php echo $nome_utilizador ?></span>
        <button class="btn btn-secondary dropdown-toggle" type="button" id="d1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-cog"></i> 
        </button>
        <div class="dropdown-menu" aria-labelledby="d1">
            <a class="dropdown-item" href="?a=perfil">Acesso ao perfil</a>
            <a class="dropdown-item" href="?a=perfil_alterar_password">Alterar Password</a>
            <a class="dropdown-item" href="?a=perfil_alterar_email">Alterar Email</a>
            <div class="dropdown-divider"></div>
            
            <!-- opções disponíveis apenas para admin -->
            <?php if(funcoes::Permissao(0)): ?>
                <a class="dropdown-item" href="?a=utilizadores_gerir">Gerir utilizadores</a>
                <div class="dropdown-divider"></div>
            <?php endif; ?>
            
            
            <a class="dropdown-item" href="?a=logout">Logout</a>
        </div>
    </div>

    <?php else : ?>
        <span class="<?php echo $classe ?>"><i class="fa fa-user"></i> <?php echo $nome_utilizador ?></span>            
    <?php endif; ?>
</div>