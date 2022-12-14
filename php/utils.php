<?php
    

    /**
     * Método capaz de mostrar o resultado de um SELECT formatando-o em linhas e colunas de uma table
     *
     * Este método mostra os resultados de um SELECT estruturando-os em tags de tabela, vale ressaltar que este método deve ser chamado dentro de um \<tbody>, consequentemente dentro de uma tag \<table> para que funcione.
     *
     * @param object $conn Variável de conexão com o banco
     * @param string $procedure String contendo a query com o procedure
     * @param array $id Identificador de chave para o procedure/select
     **/
    function mostrarTabela(object $conn, string $procedure, array $id){
        
        $result = fetchSelect($conn, $procedure, $id);

        for ($i=0; $i < count($result); $i++) { 
            echo "<tr>";
            for ($j=0; $j < count($result[$i]); $j++) { 
                echo "<td class='px-2'>" . $result[$i][$j] . "</td>";
            }
            echo"</tr>";
        }
    }

    /**
     * Recebe um SELECT e passa seu resultado para um array, indexando os elementos numéricamente
     * 
     * @param object $conn Variável de conexão com o banco
     * @param string $select String contendo o SELECT para preparar a query
     * @param array $id Array contendo as variáveis coringas que serão executadas na query preparada
     * @return array 
     */
    function fetchSelect(object $conn, string $select, array $id){
        $query = $conn -> prepare($select);
                $query -> execute($id);

        $result = $query -> fetchAll(PDO::FETCH_NUM);

        return $result;
    }
?>