<?php
    // =======================================================================================
    // gestor | gestor de BD MySQL PDO
    // =======================================================================================

class cl_gestorBD
{
    //==================================================================
    public function EXE_QUERY($query, $parametros = NULL, $fechar_ligacao = TRUE)
    {
        //executa a query à base de dados (SELECT)
        $resultados = NULL;

        $config = include('config.php');

        //abre a ligação à base de dados
        $ligacao = new PDO(
            'mysql:host='.$config['BD_HOST'].
            ';dbname='.$config['BD_DATABASE'].
            ';charset='.$config['BD_CHARSET'],
            $config['BD_USERNAME'],
            $config['BD_PASSWORD'],
            array(PDO::ATTR_PERSISTENT => TRUE));
        $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //executa a query
        if ($parametros != NULL) {
            $gestor = $ligacao->prepare($query);
            $gestor->execute($parametros);
            $resultados = $gestor->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $gestor = $ligacao->prepare($query);
            $gestor->execute();
            $resultados = $gestor->fetchAll(PDO::FETCH_ASSOC);
        }

        #fecha a ligação por defeito
        if ($fechar_ligacao) {
            $ligacao = NULL;
        }

        #retorna os resultados
        return $resultados;
    }

    //==================================================================
    public function EXE_NON_QUERY($query, $parametros = NULL, $fechar_ligacao = TRUE)
    {
        //executa uma query com ou sem parâmetros (INSERT, UPDATE, DELETE)

        $config = include('config.php');

        //abre a ligação à base de dados
        $ligacao = new PDO(
            'mysql:host='.$config['BD_HOST'].
            ';dbname='.$config['BD_DATABASE'].
            ';charset='.$config['BD_CHARSET'],
            $config['BD_USERNAME'],
            $config['BD_PASSWORD'],
            array(PDO::ATTR_PERSISTENT => TRUE));
        $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //executa a query
        $ligacao->beginTransaction();
        try {
            if ($parametros != NULL) {
                $gestor = $ligacao->prepare($query);
                $gestor->execute($parametros);
            } else {
                $gestor = $ligacao->prepare($query);
                $gestor->execute();
            }
            $ligacao->commit();
        } catch (PDOException $e) {
            echo '<p>' . $e . '</p>';
            $ligacao->rollBack();
        }

        #fecha a ligacao por defeito
        if ($fechar_ligacao) {
            $ligacao = NULL;
        }
    }

    //==================================================================
    public function RESET_AUTO_INCREMENT($tabela){
        
        //faz reset ao auto_increment de uma determinada tabela ($tabela)

        $config = include('config.php');

        //abre a ligação à base de dados
        $ligacao = new PDO(
            'mysql:host='.$config['BD_HOST'].
            ';dbname='.$config['BD_DATABASE'].
            ';charset='.$config['BD_CHARSET'],
            $config['BD_USERNAME'],
            $config['BD_PASSWORD'],
            array(PDO::ATTR_PERSISTENT => TRUE));
        $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //reset ao auto_increment
        $ligacao->exec('ALTER TABLE '.$tabela.' AUTO_INCREMENT = 1');

        //fecha a ligacao
        $ligacao = NULL;
    }    
}
?>