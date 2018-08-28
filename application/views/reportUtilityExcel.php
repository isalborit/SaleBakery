<?php      
	header("Content-Type: application/vnd.ms-excel; charset=utf-8");
	header("Content-type: application/x-msexcel; charset=utf-8");
	header("content-disposition: attachment;filename=Reporte de Utilidad-Producto.xls");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private", false);
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<style type="text/css">
		table tbody tr td{
		  mso-number-format:"\@";
		}
	</style>
</head>
<body>
	<h2>Reporte de Utilidad de Productos</h2>
	<br>
	<table border="1" cellpadding="4" style="font-family:Arial;">
		<thead>
			<tr bgcolor="#E6E6E6">
				<th width="150">Codigo</th>
				<th>Descripcion del producto</th>
				<th width="80">Unidad</th>
				<th>Marca</th>
				<th>Categoria</th>
				<th>Precio Compra</th>
				<th>Precio Minimo</th>
				<th>Precio Maximo</th>
				<th>Precio Venta</th>
				<th>Utilidad</th>
			</tr>
		</thead>
		<tbody>
			<?=$data;?>
		</tbody>
	</table>
</body>
</html>