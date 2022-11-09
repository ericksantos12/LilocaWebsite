
<?php
require_once 'conectaBD.php';
try {

    
    // connect to the database and select the publisher

    
    $sql = 'CALL get_books_published_after()';
    
    $publishers = [];
    
    $statement = $conn->prepare($sql);
    
    $statement->execute();
    
    $publishers = $statement->fetchAll(PDO::FETCH_ASSOC);
    
    print_r($publishers);


        
    } catch(PDOException $e) {
        echo 'ERROR: ' . $e->getMessage();
    }



?>
