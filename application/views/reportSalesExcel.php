<?php      
	header("Content-Type: application/vnd.ms-excel; charset=utf-8");
	header("Content-type: application/x-msexcel; charset=utf-8");
	header("content-disposition: attachment;filename=Reporte de Ventas.xls");
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
	<h2>Reporte de Ventas</h2>
	<h3>Desde <?=$from?> hasta <?=$to?></h3>
	<table border="1" cellpadding="4" style="font-family:Arial;">
		<thead>
			<tr bgcolor="#E6E6E6">
				<th width="100">Comprobante</th>
				<th width="100">Serie-Numero</th>
				<th width="180">Fecha</th>
				<th>Usuario</th>
				<th>RUC</th>
				<th>Cliente</th>
				<th>Tipo de pago</th>
				<th>Cancelado</th>
				<th>Vencimiento</th>
				<th>Estado</th>
				<th width="100">Subtotal</th>
				<th width="100">Descuento</th>
				<th width="100">IGV</th>
				<th width="100">Total</th>
			</tr>
		</thead>
		<tbody>
			<?=$data;?>
		</tbody>
	</table>
</body>
</html>