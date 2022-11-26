<?php
require_once 'conectaBD.php';
require_once 'utils.php';

try {

    $produtos = ['baloes', 'docinhos', 'marshmallow', 'salgadinhos', 'coca350ml', 'coca2l', 'minisanduiche', 'toalhacolorida', 'boloprestigio', 'bolotrufado', 'tortaholandesa', 'copodescartavel', 'pratodescartavel', 'talherdescartavel'];
    
    $festa = $_POST['festa'];
    $tipoEntrega = $_POST['tipoentrega'];
    $endereco = $_POST['endereco'];
    $prazoEntrega = $_POST['prazo'];
    $frete = $tipoEntrega == 'entrega' ? 20 : 0;
    $sinal = 0;

    $conn->beginTransaction();

    for ($i=0; $i < count($produtos); $i++) { 
        $produtoQtd = (int)$_POST[$produtos[$i]];

        if ($produtoQtd != 0) {
            // pega o valor unitario de cada produto e multiplica por sua quantidade, somando-os na variável sinal
            $sinal += fetchSelect($conn, "SELECT valorUnit FROM produtos WHERE idProdutos = ?;", [$i + 1])[0][0] * $produtoQtd;
        }
    }
    $sinal /= 2;

    // prepara inserção no pedido: idFesta, tipo, endereco, prazo, frete, sinal
    $query = $conn->prepare("CALL Inclui_Pedido(?, ?, ?, ?, ?, ?, @LastID);"); // <-- poe pra fora variavel lastID com o ultimo id de pedido
    $query->execute([$festa, $tipoEntrega, $endereco, $prazoEntrega, $frete, $sinal]);

    $query2 = $conn->query("SELECT @LastID AS id;"); // <-- pega o valor da variavel LastID
    $lastID = $query2->fetch(PDO::FETCH_NUM)[0];

    // prepara inserção na associativa: idPed, idProd, qtd
    $query = $conn->prepare("CALL Inclui_PedidoProduto(?, ?, ?);");

    for ($i=0; $i < count($produtos); $i++) { 
        $produtoQtd = (int)$_POST[$produtos[$i]];

        if ($produtoQtd != 0){
            $query->execute([$lastID, $i + 1, $produtoQtd]); // executa a inserção na tabela associativa
        }
    }
    
    $conn->commit(); //fim

    echo "Cadastrado com sucesso <br>";

} catch (PDOException $e) {
    $conn->rollBack();
    echo "Erro: " . $e->getMessage();
}

?>

<a href="javascript:history.back()">Voltar</a>