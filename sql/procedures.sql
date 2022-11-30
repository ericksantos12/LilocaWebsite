DELIMITER $$
CREATE PROCEDURE `Consultar_Pedido`(IN `id` INT)
BEGIN

SELECT `aniversariante`, `dataPed`, `tipoDeEntrega`, `endDeEntrega`, `prazoDeEntrega`, `frete`, `sinal`, `restante`, `dataDeEntrega`, `total` 
FROM `pedido` 
INNER JOIN festa 
WHERE `Festa_idFesta`=`idFesta` AND `idPedido`=id;

END$$
DELIMITER ;

-- // ----------------------------------------------------------------------------------------------------------------------// --

DELIMITER $$
CREATE PROCEDURE `Consultar_PedidoProduto`(IN `id` INT)
BEGIN

SELECT item, `quantidade`, `valorQtd` 
FROM pedido_has_produtos 
INNER JOIN produtos 
WHERE pedido_has_produtos.Produtos_idProdutos=produtos.idProdutos AND pedido_has_produtos.Pedido_idPedido = id;

END$$
DELIMITER ;

-- // ----------------------------------------------------------------------------------------------------------------------// --

DELIMITER $$
CREATE PROCEDURE `Consultar_Festa` (IN `id` INT)
BEGIN

SELECT `nome`, `idFesta`, `Cliente_idCliente`, `dataDaFesta`, `aniversariante`, `idade`, `tema`, `cores`
FROM festa
INNER JOIN cliente
WHERE festa.Cliente_idCliente = cliente.idCliente AND Cliente_idCliente = `id`;
END$$
DELIMITER ;

-- // ----------------------------------------------------------------------------------------------------------------------// --

DELIMITER $$
CREATE PROCEDURE `Inclui_Pedido`(IN `idFesta` INT, IN `tipo` VARCHAR(225), IN `endereco` VARCHAR(255), IN `prazo` DATETIME, IN `frete` DOUBLE, IN `sinal` DOUBLE, OUT `LastID` INT)
BEGIN

INSERT INTO `pedido`(`Festa_idFesta`, `dataPed`, `tipoDeEntrega`, `endDeEntrega`, `prazoDeEntrega`, `frete`, `sinal`, `restante`, `dataDeEntrega`, `total`) VALUES (idFesta, CURRENT_DATE(), tipo, endereco, prazo, frete, sinal, sinal, null, frete + (sinal * 2));

SET LastID = LAST_INSERT_ID();

END$$
DELIMITER ;

-- // ----------------------------------------------------------------------------------------------------------------------// --

DELIMITER $$
CREATE PROCEDURE `Inclui_PedidoProduto`(IN `idPed` INT, IN `idProd` INT, IN `qtd` INT)
BEGIN

SELECT valorUnit INTO @valorProduto FROM produtos WHERE produtos.idProdutos = idProd;

INSERT INTO `pedido_has_produtos`(`Pedido_idPedido`, `Produtos_idProdutos`, `quantidade`, `valorQtd`) VALUES (idPed, idProd, qtd, @valorProduto * qtd);

END$$
DELIMITER ;

-- // ----------------------------------------------------------------------------------------------------------------------// --

DELIMITER $$
CREATE PROCEDURE `Inclui_Festa` (IN `idCliente` INT, IN `dataFesta` DATETIME, IN `aniversarianteNome` VARCHAR(255), IN `idadeAniversariante` INT, IN `tema` VARCHAR(255), IN `cor` VARCHAR(255))
BEGIN

INSERT INTO `festa` (`Cliente_idCliente`, `dataDaFesta`, `aniversariante`, `idade`, `tema`, `cores`) VALUES (idCliente, dataFesta, aniversarianteNome, idadeAniversariante, tema, cor);

END$$
DELIMITER ;

-- // ----------------------------------------------------------------------------------------------------------------------// --
DELIMITER $$
CREATE PROCEDURE `Criar_Banco`()
BEGIN
CREATE TABLE Cliente (
  idCliente INT  NOT NULL   AUTO_INCREMENT,
  nome VARCHAR(255)  NULL  ,
  telefone VARCHAR(255)  NULL  ,
  email VARCHAR(255)  NULL  ,
  cpf VARCHAR(255)  NULL    ,
PRIMARY KEY(idCliente));

CREATE TABLE Produtos (
  idProdutos INTEGER UNSIGNED  NOT NULL   AUTO_INCREMENT,
  item VARCHAR(255)  NULL  ,
  valorUnit DOUBLE  NULL    ,
PRIMARY KEY(idProdutos));


CREATE TABLE Itens (
  Produtos_idProdutos INTEGER UNSIGNED  NOT NULL  ,
  quantidade INTEGER UNSIGNED  NULL    ,
INDEX Table_09_FKIndex1(Produtos_idProdutos),
  FOREIGN KEY(Produtos_idProdutos)
    REFERENCES Produtos(idProdutos)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION);


CREATE TABLE Festa (
  idFesta INTEGER UNSIGNED  NOT NULL   AUTO_INCREMENT,
  Cliente_idCliente INT  NOT NULL  ,
  dataDaFesta DATETIME  NULL  ,
  aniversariante VARCHAR(255)  NULL  ,
  idade INTEGER UNSIGNED  NULL  ,
  tema VARCHAR(255)  NULL  ,
  cores VARCHAR(255)  NULL    ,
PRIMARY KEY(idFesta)  ,
INDEX Evento_FKIndex1(Cliente_idCliente),
  FOREIGN KEY(Cliente_idCliente)
    REFERENCES Cliente(idCliente)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION);


CREATE TABLE Alimentos (
  Produtos_idProdutos INTEGER UNSIGNED  NOT NULL  ,
  unidade INTEGER UNSIGNED  NULL    ,
INDEX Alimentos_FKIndex1(Produtos_idProdutos),
  FOREIGN KEY(Produtos_idProdutos)
    REFERENCES Produtos(idProdutos)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION);


CREATE TABLE Bebida (
  Produtos_idProdutos INTEGER UNSIGNED  NOT NULL  ,
  mililitros FLOAT  NULL  ,
  marca VARCHAR(255)  NULL  ,
  embalagem VARCHAR(255)  NULL  ,
  quantidade INTEGER UNSIGNED  NULL    ,
INDEX Bebida_FKIndex1(Produtos_idProdutos),
  FOREIGN KEY(Produtos_idProdutos)
    REFERENCES Produtos(idProdutos)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION);


CREATE TABLE DoceSalgados (
  Produtos_idProdutos INTEGER UNSIGNED  NOT NULL  ,
  centos FLOAT  NULL    ,
INDEX DoceSalgados_FKIndex1(Produtos_idProdutos),
  FOREIGN KEY(Produtos_idProdutos)
    REFERENCES Produtos(idProdutos)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION);


CREATE TABLE Pedido (
  idPedido INTEGER UNSIGNED  NOT NULL   AUTO_INCREMENT,
  Festa_idFesta INTEGER UNSIGNED  NOT NULL  ,
  dataPed DATETIME  NULL  ,
  tipoDeEntrega VARCHAR(255)  NULL  ,
  endDeEntrega VARCHAR(255)  NULL  ,
  prazoDeEntrega DATETIME  NULL  ,
  frete DOUBLE  NULL  ,
  sinal DOUBLE  NULL  ,
  restante DOUBLE  NULL  ,
  dataDeEntrega DATETIME  NULL  ,
  total DOUBLE  NULL    ,
PRIMARY KEY(idPedido)  ,
INDEX Pedido_FKIndex1(Festa_idFesta),
  FOREIGN KEY(Festa_idFesta)
    REFERENCES Festa(idFesta)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION);


CREATE TABLE Pedido_has_Produtos (
  Pedido_idPedido INTEGER UNSIGNED  NOT NULL  ,
  Produtos_idProdutos INTEGER UNSIGNED  NOT NULL  ,
  quantidade INTEGER UNSIGNED  NULL  ,
  valorQtd DOUBLE  NULL    ,
PRIMARY KEY(Pedido_idPedido, Produtos_idProdutos)  ,
INDEX Pedido_has_Produtos_FKIndex1(Pedido_idPedido)  ,
INDEX Pedido_has_Produtos_FKIndex2(Produtos_idProdutos),
  FOREIGN KEY(Pedido_idPedido)
    REFERENCES Pedido(idPedido)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION,
  FOREIGN KEY(Produtos_idProdutos)
    REFERENCES Produtos(idProdutos)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION);
END$$
DELIMITER ;
