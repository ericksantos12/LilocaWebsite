                
<?php
require_once 'conectaBD.php';


try {

     // pega os campos
  
    $nome = $_POST['userNome'];
    $CPF = $_POST['userCPF'];
    $email = $_POST['userEmail'];
    $tel = $_POST['userTel'];

    // Grava no Banco de Dados

    $conn-> exec("Insert into cliente(nome, telefone, email, cpf) values ('" .$nome. "', '" .$tel. "', '".$email."', '".$CPF."')");
    echo 'Cadastrado com sucesso!';
    }

    catch(PDOException $e){
        
      echo 'Erro:'. $e->getMessage();  
    }
		

    
?>