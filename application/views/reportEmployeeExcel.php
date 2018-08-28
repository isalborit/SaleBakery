<?php      
	header("Content-Type: application/vnd.ms-excel; charset=utf-8");
	header("Content-type: application/x-msexcel; charset=utf-8");
	header("content-disposition: attachment;filename=Reporte de Empleados.xls");
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
	<h2>Reporte de Empleados</h2>
	<h3>Desde <?=$from?> hasta <?=$to?></h3>
	<table border="1" cellpadding="4" style="font-family:Arial;">
		<thead>
			<tr bgcolor="#E6E6E6">
				<th>Fecha de registro</th>
				<th width="100">DNI</th>
				<th>Nombres y Apellidos</th>
				<th>Direccion</th>
				<th>Telefono</th>
				<th>Area</th>
				<th>Tipo de empleado</th>
				<th>Usuario</th>
			</tr>
		</thead>
		<tbody>
			<?=$data;?>
		</tbody>
	</table>
</body>
</html>