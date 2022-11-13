
<?php
try {
  $conn = new PDO('mysql:host=localhost;dbname=lilocapersonalizados', 'root', '');
  // echo 'Conectou';

} catch(PDOException $e) {
    echo 'ERROR: ' . $e->getMessage();
}
?>



