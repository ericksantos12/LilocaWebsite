<?php
    

    /**
     * Método capaz de mostrar o resultado de um SELECT formatando-o em linhas e colunas de uma table
     *
     * Este método mostra os resultados de um SELECT estruturando-os em tags de tabela, vale ressaltar que este método deve ser chamado dentro de um \<tbody>, consequentemente dentro de uma tag \<table> para que funcione.
     * <b>Exemplo:</b>
     * <code>
     * <table> 
     *  <thead>...</thead> 
     *  <tbody> 
     *      mostrarTabela(...) 
     *  </tbody> 
     * </table>
     * </code>
     *
     * @param object $conn Variável de conexão com o banco
     * @param string $procedure String contendo query com o procedure
     * @param array $id Identificador de chave para o procedure/select
     **/
    function mostrarTabela(object $conn, string $procedure, array $id){
        $query = $conn -> prepare($procedure);
                $query -> execute($id);

        $result = $query->fetchAll(PDO::FETCH_NUM);

        for ($i=0; $i < count($result); $i++) { 
            echo "<tr>";
            for ($j=0; $j < count($result[$i]); $j++) { 
                echo "<td class='px-2'>" . $result[$i][$j] . "</td>";
            }
            echo"</tr>";
        }
    }
?>