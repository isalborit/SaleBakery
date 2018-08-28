<?php      
	header("Content-Type: application/vnd.ms-excel; charset=utf-8");
	header("Content-type: application/x-msexcel; charset=utf-8");
	header("content-disposition: attachment;filename=Detalle de venta.xls");
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
	<h2>Detalle de Venta</h2>
	<h3><?=$type?> : <?=$serie?></h3>
	<h4>Fecha : <?=$date?></h4>
	<?=$data?>
</body>
</html>