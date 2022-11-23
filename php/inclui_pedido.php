<?php
require_once 'conectaBD.php';
require_once 'utils.php';

// TODO: Fazer stored procedure inserção de pedido e associativa com produto
// TODO: Integrar stored procedure com o código

try {

    $produtos = ['baloes', 'docinhos', 'marshmallow', 'salgadinhos', 'coca350ml', 'coca2l', 'minisanduiche', 'toalhacolorida', 'boloprestigio', 'bolotrufado', 'tortaholandesa', 'copodescartavel', 'pratodescartavel', 'talherdescartavel'];
    
    $festa = $_POST['festa'];
    $dataPedido = date('Y-m-d');
    $tipoEntrega = $_POST['tipoentrega'];
    $endereco = $_POST['endereco'];
    $prazoEntrega = $_POST['prazo'];
    $frete = $tipoEntrega == 'entrega' ? 20 : 0;
    $valorProdutos = 0;
    $sinal = 0;
    $total = 0;

    // $query = "INSERT INTO `pedido`(`Festa_idFesta`, `dataPed`, `tipoDeEntrega`, `endDeEntrega`, `prazoDeEntrega`, `frete`, `sinal`, `restante`, `dataDeEntrega`, `total`) VALUES ('".$festa."', '". $dataPedido ."', '".$tipoEntrega."', '".$endereco."', '".$prazoEntrega."', '".$frete."', '".$sinal."', '".$sinal."', null, '".$total."');";

    $conn->beginTransaction();

    // prepara a query inserção de pedido
    $query = $conn->prepare("INSERT INTO `pedido`(`Festa_idFesta`, `dataPed`, `tipoDeEntrega`, `endDeEntrega`, `prazoDeEntrega`, `frete`, `sinal`, `restante`, `dataDeEntrega`, `total`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, null, ?);");

    // executa a query
    $query->execute([$festa, $dataPedido, $tipoEntrega, $endereco, $prazoEntrega, $frete, $sinal, $sinal, $total]);
    $lastID = $conn->lastInsertId(); // pega o id do pedido

    //prepara a query inserção na tabela associativa
    $query = $conn->prepare("INSERT INTO `pedido_has_produtos`(`Pedido_idPedido`, `Produtos_idProdutos`, `quantidade`, `valorQtd`) VALUES (?, ?, ?, ?);");

    for ($i=0; $i < count($produtos); $i++) { 
        $produtoQtd = (int)$_POST[$produtos[$i]]; // pega a quantidade de produtos escolhidos no forms

        if ($produtoQtd != 0){ // verifica se a quantidade nao é 0

            // Pega o valor unitário do produto e múltiplica pela quantidade selecionada
            $valorQtd = fetchSelect($conn, "SELECT `valorUnit` FROM `produtos` WHERE idProdutos = ?", [$i + 1])[0][0] * $produtoQtd;

            $query->execute([$lastID, $i + 1, $produtoQtd, $valorQtd]); // executa a inserção na tabela associativa

            $valorProdutos += $valorQtd; // soma o valor final na variavel total
        }
    }

    $sinal = $valorProdutos / 2; // divide o sinal 50%
    $total = $frete + $valorProdutos; // calcula o total com base no valor total de produtos + o frete

    // prepara query para dar update no ultimo pedido inserido
    $query = $conn->prepare("UPDATE `pedido` SET `sinal`= ? ,`restante`= ? , `total` = ? WHERE `idPedido`= ? ");
    $query->execute([$sinal, $sinal, $total, $lastID]); // adiciona o valor do sinal e total
    
    $conn->commit(); //fim

    echo "Cadastrado com sucesso <br>";

} catch (PDOException $e) {
    $conn->rollBack();
    echo "Erro: " . $e->getMessage();
}

?>

<a href="javascript:history.back()">Voltar</a>