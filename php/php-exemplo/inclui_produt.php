                
<?php
require_once 'conectaBD.php';


try {

     // pega os campos
  
    $nome = $_GET['userNome'];
    $valor = $_GET['valor'];

    // Grava no Banco de Dados

    $conn-> exec("Insert into produtos(item, valorUnit) values ('" .$nome."', " .$valor. ")");
    echo 'Cadastrado com sucesso!';
    }
    //http://localhost/LilocaWebsite/php/inclui_produt.php?userNome=%22gani%22&valor=500

    catch(PDOException $e){
        
      echo 'Erro:'. $e->getMessage();  
    }
		

    
?>