<?php
require_once 'conectaBD.php';


try {

     // pega os campos
  
    $idCliente = $_POST['idCliente'];
    $dataFesta = $_POST['dateTime'];
    $aniversarianteNome = $_POST['aniversariante'];
    $idade = $_POST['idade'];
    $tema = $_POST['tema'];
    $cor = $_POST['cor'];

    // Grava no Banco de Dados

    $query = $conn->prepare("CALL `Inclui_Festa`(?,?,?,?,?,?);");
    $query->execute([$idCliente, $dataFesta, $aniversarianteNome, $idade, $tema, $cor]);

    }

    catch(PDOException $e){
        
      echo 'Erro:'. $e->getMessage();  
    }
		
    echo 'Cadastrado!';
    
?>

<a href="javascript:history.back()">Voltar</a>