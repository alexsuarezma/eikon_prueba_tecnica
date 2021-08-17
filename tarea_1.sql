/*
	Puede correr todo el script.
*/

CREATE DATABASE [eikon_db]
GO

USE [eikon_db]
GO
ALTER AUTHORIZATION ON DATABASE::[eikon_db] TO [sa]
GO

CREATE TABLE clientes(
	id INT NOT NULL PRIMARY KEY, --AUTO_INCREMENT 
	nombre VARCHAR(60),
	apellido VARCHAR(60),
	correo VARCHAR(60),
	cedula VARCHAR(10) 
)

GO

CREATE TABLE locales(
	id INT NOT NULL PRIMARY KEY, --AUTO_INCREMENT 
	descripcion VARCHAR(150),
	cliente_id INT FOREIGN KEY REFERENCES clientes(id)
)

GO 

/*
	El factor determina la presentación del producto.
	sea en unidades o en cajas.
*/
CREATE TABLE productos(
	id INT NOT NULL PRIMARY KEY, --AUTO_INCREMENT 
	descripcion VARCHAR(150),
	costo MONEY,
	pvp MONEY,
	stock DECIMAL,		
	factor DECIMAL
)

GO 

CREATE TABLE pedidos(
	id INT NOT NULL PRIMARY KEY, --AUTO_INCREMENT 
	fecha_emision DATETIME,
	local_id INT FOREIGN KEY REFERENCES locales(id),
	descripcion_local VARCHAR(150),
	cliente_id INT FOREIGN KEY REFERENCES clientes(id)
)

GO 

CREATE TABLE pedidos_detalle(
	pedido_id INT NOT NULL PRIMARY KEY, --AUTO_INCREMENT 
	producto_id INT FOREIGN KEY REFERENCES productos(id),
	cantidad DECIMAL,
	factor DECIMAL,
	costo_unitario MONEY,
	base_iva MONEY,
	iva MONEY,
	venta_total MONEY
)

GO

CREATE VIEW REPORTE_PEDIDOS
AS
SELECT 
  a.id nro_pedido, a.fecha_emision emision, ISNULL(DATEDIFF(MINUTE, b.fecha_emision,a.fecha_emision),'') diferencia,
  a.cantidad, a.factor [Unidades],
  (a.cantidad * a.factor) [Total Unidades]
FROM (
        SELECT RANK() OVER (ORDER BY fecha_emision) FILA,fecha_emision,id,cantidad,factor
        FROM pedidos p INNER JOIN pedidos_detalle pd ON p.id = pd.pedido_id
     ) A 
     LEFT OUTER JOIN
     (
        SELECT RANK() OVER (ORDER BY fecha_emision) FILA,fecha_emision
        FROM pedidos
     ) B ON A.FILA = B.FILA + 1

GO


INSERT INTO clientes VALUES (1,'Alex','Suarez','alexotrowe@gmail.com','0959010042')
GO
INSERT INTO locales VALUES (1,'local uno', 1)
GO
INSERT INTO productos VALUES (1,'caja de bombones', 2.50,4.50,8,20)
GO
INSERT INTO pedidos VALUES (1,GETDATE(),1,'local uno',1)
INSERT INTO pedidos_detalle VALUES (1,1,3,20,4.50,270,22.5,292.5)
GO
INSERT INTO pedidos VALUES (2,GETDATE(),1,'local uno',1)
INSERT INTO pedidos_detalle VALUES (2,1,2,20,4.50,9,1.08,10.08)

GO

SELECT * FROM REPORTE_PEDIDOS

