<?php 

    // =================================
    // classe para tratamento de datas
    // =================================

    class DATAS{

        // =================================
        public static function DataHoraAtualBD(){
            //retorna a data e hora atual formatada para MySQL
            $data = new DateTime();
            return $data->format('Y-m-d H:i:s');
        }
    }

?>