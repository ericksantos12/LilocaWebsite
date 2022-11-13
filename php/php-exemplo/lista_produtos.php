
<?php


try {



    $conn = new PDO('mysql:host=localhost;dbname=vendas', 'root', '') or print (mysql_error());


      
    // connect to the database and select the publisher

    
    $sql = 'CALL get_books_published_after()';
    
      
    $statement = $conn->prepare($sql);
    
    $statement->execute();


    echo'<p>'.$sql.'</p><hr>';
 
	foreach($statement as $linha)
	{
		echo '<p>';
		//Nome do campo na tabela pesquisada
		echo $linha["nome"];
		echo '</p>';
	}
 
	echo '<hr><p>Resultados: '.$statement->rowCount().'</p>';
 
    $sql = 'CALL Selecionar_Produtos(?)';
    $param=2;
      
    $statement = $conn->prepare($sql);
    $statement->bindParam(1, $param); 
    $statement->execute();


    echo'<p>'.$sql.'</p><hr>';
 
	foreach($statement as $linha)
	{
		echo '<p>';
		//Nome do campo na tabela pesquisada
		echo $linha["nome"];
		echo '</p>';
	}
 
	echo '<hr><p>Resultados: '.$statement->rowCount().'</p>';

	//Desconectar
	$conn= null;
   }
    catch(PDOException $erro)
    {
        echo $erro->getMessage();
    }
    
    
 
?>