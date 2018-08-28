<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Sale_model extends CI_Model{

	function __construct(){
		parent::__construct();
		$this->load->database();
	}

	function saveSale($data){
		if(isset($_SESSION['usu_sv'])){
			$user = $_SESSION['usu_sv'];
		}else if(isset($_SESSION['adm_sv'])){
			$user = $_SESSION['adm_sv'];
		}

		$userSql = $this->db->query("SELECT usu_id FROM tbl_usuario WHERE usu_nombre = '$user'");
		$userId = 0;
		foreach($userSql->result_array() as $row) {
			$userId = $row['usu_id'];
		}
		
		$insert = $this->db->query("INSERT INTO tbl_venta(venta_serie, venta_numero, venta_tipo, venta_fecha, venta_moneda, cliente_id, movimiento_id, credito_id, contado_id, usuario_id, igv_porcentaje, venta_estado, pedido_id, venta_tipo_documento, venta_monto_recibido, venta_cancelada) VALUES ('".$data['serie']."', '".$data['nSale']."', '".$data['doc']."', '".$data['date']."', '".$data['currency']."', '".$data['cli']."', '".$data['mov']."', '".$data['credit']."', '".$data['method']."', '$userId', '".$data['igv']."', '1', '".$data['order']."', '".$data['typeDoc']."', '".$data['received']."', '".$data['payed']."')");
		if($insert){
			$id = $this->db->insert_id();
		}else{
			$id = 0;
		}
		return $id;
	}

	function saveDetailSale($data){
		$insert = $this->db->query("INSERT INTO tbl_venta_detalle(venta_id, producto_id, detalle_precio, detalle_precio_sin_igv, detalle_unidad, detalle_cantidad, detalle_descuento, detalle_descuento_sin_igv, detalle_impuesto, detalle_referencia) VALUES ('".$data['idSale']."', '".$data['idPro']."', '".$data['pricePro']."', '".$data['priceProWIGV']."', '".$data['unitPro']."', '".$data['amountPro']."', '".$data['descPro']."', '".$data['descProWIGV']."', '1', '".$data['ref']."')");
	}

	function actualizarPreciosV($id, $precioV){
		$sql = $this->db->query("UPDATE tbl_producto SET prod_precio_venta = '$precioV' WHERE prod_id = '$id'");
	}

	function updateSaleResults($id, $subtotal, $discount, $igv, $neto){
		$this->db->query("UPDATE tbl_venta SET venta_subtotal = '$subtotal', venta_descuento = '$discount', venta_igv = '$igv', venta_neto = '$neto' WHERE venta_id = '$id'");
	}

	function delete($id){
		$delete = $this->db->query("UPDATE tbl_venta SET venta_estado = 0 WHERE venta_id = '$id'");
		if ($delete){
			return 1;
		}else{
			return 0;
		}
	}

	function loadRecordSaleDetail($value){
		$saleDetail = $this->db->query("SELECT * FROM tbl_venta_detalle WHERE venta_id = '$value'");
		return $saleDetail->result_array();	
	}

	function loadRecordSaleNDetail($value){
		$saleDetail = $this->db->query("SELECT * FROM tbl_venta_detalle WHERE venta_id = '$value'");
		return $saleDetail->num_rows();	
	}

	function loadRecordSaleId($value){
		$sale = $this->db->query("SELECT * FROM tbl_venta WHERE venta_id = '$value' LIMIT 1");
		return $sale->result_array();	
	}

	function loadRecordSaleToday(){
		if(isset($_SESSION['adm_sv'])){
			$clause = "";
		}else if(isset($_SESSION['usu_sv'])){
			$clause = "AND usu_id IN(SELECT usu_id FROM tbl_usuario WHERE usu_nombre = '".$_SESSION['user_sv']."')";
		}

		$sale = $this->db->query("SELECT * FROM tbl_venta WHERE DATE(venta_fecha) = DATE(NOW()) $clause AND venta_estado = 1 ORDER BY venta_fecha DESC");
		return $sale->result_array();	
	}

	function searchSaleByDate($data, $type){
		if($type == 1){
			$doc = explode('-', $data['doc']);
			$serie = $doc[0];
			$number = $doc[1];
			$clause1 = "venta_fecha BETWEEN '".$data['from']."' AND '".$data['to']."' AND venta_serie = '$serie' AND venta_numero = '$number'";
		}else if($type == 2){
			$clause1 = "venta_fecha BETWEEN '".$data['from']."' AND '".$data['to']."'";
		}else if($type == 3){
			$doc = explode('-', $data['doc']);
			$serie = $doc[0];
			$number = $doc[1];
			$clause1 = "venta_serie = '$serie' AND venta_numero = '$number'";
		}

		if(isset($_SESSION['adm_sv'])){
			$clause2 = "";
		}else if(isset($_SESSION['usu_sv'])){
			$clause2 = "AND usu_id IN(SELECT usu_id FROM tbl_usuario WHERE usu_nombre = '".$_SESSION['usu_sv']."')";
		}
		$sale = $this->db->query("SELECT * FROM tbl_venta WHERE $clause1 $clause2 AND venta_estado = 1 ORDER BY venta_fecha DESC");
		return $sale->result_array();	
	}

	function getDataProduct($id){
		$sql = $this->db->query("SELECT * FROM tbl_producto WHERE prod_id = '$id'");
		return $sql->result_array();
	}

	function igvInvoice($id){
		$saleDetail = $this->db->query("SELECT igv_porcentaje FROM tbl_venta WHERE venta_id = '$id'");
		$igv = 0;
		foreach($saleDetail->result_array() as $row){
			$igv = $row['igv_porcentaje'];
		}
		$array = array(0 => $igv);
		return json_encode($array);
	}

	function nSaleNumber($serie, $number){
		$sql = $this->db->query("SELECT * FROM tbl_venta WHERE venta_serie = '$serie' AND venta_numero = '$number'");
		return $sql->num_rows();
	}

	function loadProfile($data){
		$sql = $this->db->query("SELECT emp.emp_id, emp.emp_documento, emp.emp_nombre, emp.emp_apellido, usu.usu_perfil, usu.usu_rol FROM (tbl_empleado emp INNER JOIN tbl_usuario usu ON emp.emp_id = usu.emp_id) WHERE usu_nombre = '".$data['user']."'");
		return $sql->result_array();
	}

	function loadProductId($value){
		$product = $this->db->query("SELECT * FROM tbl_producto WHERE prod_id = '$value' LIMIT 1");
		return $product->result_array();
	}

	function loadProductSale($value){
		$sql = $this->db->query("SELECT * FROM tbl_producto WHERE prod_nombre LIKE '%$value%' AND prod_estado = 1 LIMIT 8");
		return $sql->result_array();
	}
	function loadProductSaleID($value){
		$sql = $this->db->query("SELECT * FROM tbl_producto WHERE prod_id = $value AND prod_estado = 1 LIMIT 1");
		return $sql->result_array();
	}

	function loadCustomerNameSale($value){
		$sql = $this->db->query("SELECT * FROM tbl_cliente WHERE cli_nombre LIKE '%$value%' AND cli_id != 1");
		return $sql->result_array();
	}

	function loadCustomerDocSale($value){
		$sql = $this->db->query("SELECT * FROM tbl_cliente WHERE cli_documento = '$value'");
		return $sql->result_array();
	}

	function loadMethods(){
		$sql = $this->db->query("SELECT * FROM tbl_contado");
		return $sql->result_array();
	}

	function loadCredits(){
		$sql = $this->db->query("SELECT * FROM tbl_credito ORDER BY credito_numero_dias");
		return $sql->result_array();
	}

	function loadMovements(){
		$movements = $this->db->query("SELECT * FROM tbl_movimiento ORDER BY mov_id");
		return $movements->result_array();
	}

	function loadMovementsId($id){
		$movements = $this->db->query("SELECT * FROM tbl_movimiento WHERE mov_id = '$id' ");
		return $movements->result_array();
	}

	function getCurrentIgv(){
		$igv = $this->db->query("SELECT igv_porcentaje FROM tbl_igv WHERE igv_estado = 1 ORDER BY igv_id DESC LIMIT 1");
		return $igv->result_array();	
	}

	function maxIdSale($doc){
		$sql = $this->db->query("SELECT * FROM tbl_venta WHERE venta_tipo = '$doc'");
		return $sql->num_rows();
	}

	function numberSale(){
		$sql = $this->db->query("SELECT * FROM tbl_ventas");
		$number = $sql->num_rows();
		return $number;
	}

	function loadUser($id){
		$user = $this->db->query("SELECT emp.emp_nombre, emp.emp_apellido FROM (tbl_empleado emp INNER JOIN tbl_usuario usu ON emp.emp_id = usu.emp_id) WHERE usu.usu_id = '$id'");
		return $user->result_array();
	}

	function loadCustomerId($value){
		$customer = $this->db->query("SELECT * FROM tbl_cliente WHERE cli_id = '$value' LIMIT 1");
		return $customer->result_array();
	}

	function editCustomer($data){
		$sql = $this->db->query("UPDATE tbl_cliente SET cli_documento = '".$data['rucCli']."', cli_nombre = '".$data['nameCli']."', cli_direccion = '".$data['directionCli']."', cli_tipo_doc_sunat = '".$data['docSunat']."' WHERE cli_id = '".$data['idCli']."'");
	}

	function addCustomer($data){
		$fecha = date('Y-m-d H:i:s');
		$sql = $this->db->query("INSERT INTO tbl_cliente (cli_registro, cli_documento, cli_tipo_doc_sunat, cli_nombre, cli_direccion, cli_estado) VALUES ('$fecha', '".$data['rucCli']."', '".$data['docSunat']."', '".$data['nameCli']."', '".$data['directionCli']."', 1)");
		$id = $this->db->insert_id();
		return $id;
	}

	function stockReal($id){
		$sqlPro = $this->db->query("SELECT * FROM tbl_producto WHERE prod_id = '$id' LIMIT 1");
		return $sqlPro->result_array();
	}

	function actualizarStock($id, $cant){
		$sql = $this->db->query("UPDATE tbl_producto SET prod_stock_real = '$cant' WHERE prod_id = '$id'");
	}

	function loadCustomerName($value){
		$sql = $this->db->query("SELECT * FROM tbl_cliente WHERE cli_nombre LIKE '%$value%' LIMIT 10");
		return $sql->result_array();
	}

	function searchSaleByDate2($data){
		$sale = $this->db->query("SELECT * FROM tbl_venta WHERE venta_fecha BETWEEN '".$data['from']."' AND '".$data['to']."' AND venta_estado = 1 AND venta_moneda = '".$data['currency']."' ORDER BY venta_fecha ASC");
		return $sale->result_array();	
	}

	function nDaysCredit($id){
		$sql = $this->db->query("SELECT credito_numero_dias FROM tbl_credito WHERE credito_id = '$id'");
		$n = 0;
		foreach ($sql->result_array() as $row){
			$n = $row['credito_numero_dias'];
		}
		return $n;
	}

	function salesStatisticYear($year, $currency, $month){
		$sql = $this->db->query("SELECT SUM(dv.detalle_cantidad * dv.detalle_precio) AS total FROM tbl_venta_detalle AS dv INNER JOIN tbl_venta AS v ON dv.venta_id = v.venta_id WHERE MONTH(v.venta_fecha) = '$month' AND YEAR(v.venta_fecha) = '$year' AND v.venta_estado = 1 AND v.venta_moneda = '$currency'");
		$total = 0;
		foreach ($sql->result_array() as $row){
			$total = $row['total'];
		}
		return $total;
	}

	function searchSaleByType($data, $from, $to, $currency){
		$sql = $this->db->query("SELECT * FROM tbl_venta WHERE venta_fecha BETWEEN '$from' AND '$to' AND movimiento_id = '$data' AND venta_estado = 1 AND venta_moneda = '$currency' ORDER BY venta_fecha ASC");
		return $sql->result_array();	
	}

	function searchSaleByCustomer($data, $from, $to, $currency){
		$sql = $this->db->query("SELECT * FROM tbl_venta WHERE venta_fecha BETWEEN '$from' AND '$to' AND cliente_id = '$data' AND venta_estado = 1 AND venta_moneda = '$currency' ORDER BY venta_fecha ASC");
		return $sql->result_array();	
	}

	function searchSaleByUser($data, $from, $to, $currency){
		$sql = $this->db->query("SELECT * FROM tbl_venta WHERE venta_fecha BETWEEN '$from' AND '$to' AND usuario_id IN(SELECT usu_id FROM tbl_usuario WHERE usu_nombre = '$data') AND venta_estado = 1 AND venta_moneda = '$currency' ORDER BY venta_fecha ASC");
		return $sql->result_array();	
	}

	function searchCustomerInSale($from, $to, $type){
		if ($type == 1){
			$sql = $this->db->query("SELECT COUNT(v.cliente_id) AS habitual, v.cliente_id, c.cli_registro, c.cli_documento, c.cli_nombre, c.cli_direccion, c.cli_telefono FROM (tbl_venta v INNER JOIN tbl_cliente c ON v.cliente_id = c.cli_id) WHERE v.venta_fecha BETWEEN '$from' AND '$to' GROUP BY v.cliente_id");
		}elseif($type == 2){
			$sql = $this->db->query("SELECT COUNT(v.cliente_id) AS habitual, v.cliente_id, c.cli_registro, c.cli_documento, c.cli_nombre, c.cli_direccion, c.cli_telefono FROM (tbl_venta v INNER JOIN tbl_cliente c ON v.cliente_id = c.cli_id) WHERE v.venta_fecha BETWEEN '$from' AND '$to' GROUP BY v.cliente_id ORDER BY habitual DESC LIMIT 20");
		}
		return $sql->result_array();
	}

	function idProduct($idSale){
		$sql = $this->db->query("SELECT * FROM tbl_venta_detalle WHERE venta_id = '$idSale'");
		return $sql->result_array();
	}

	function stockReal1($id){
		$sql = $this->db->query("SELECT * FROM tbl_producto WHERE prod_id = '$id'");
		$cantidad = 0;
		foreach ($sql->result_array() as $row){
			$cantidad = $row['prod_stock_real'];
		}
		return $cantidad;
	}

	function updateStock($id, $cant){
		$sql = $this->db->query("UPDATE tbl_producto SET prod_stock_real = '$cant'  WHERE prod_id = '$id'");
	}

	function verifySaleId($value){
		$sale = $this->db->query("SELECT * FROM tbl_venta WHERE venta_id = '$value' LIMIT 1");
		$response = 0;
		if ($sale->num_rows() > 0){
			$response = 1;
		}else{
			$response = 0;
		}
		return $response;
	}

	function getOrder(){
		$order = $this->db->query("SELECT * FROM tbl_pedido WHERE pedido_estado = 0");
		return $order->result_array();
	}

	function closeOrder($id){
		$this->db->query("UPDATE tbl_pedido SET pedido_estado = '1' WHERE pedido_id = '$id'");
	}

	function loadNumberCredit($id){
		$sql = $this->db->query("SELECT * FROM tbl_credito WHERE credito_id = '$id'");
		$total = 0;
		foreach ($sql->result_array() as $row){
			$total = $row['credito_numero_dias'];
		}
		return $total;
	}

	function insertCtaCharge($sale, $date, $credit, $amount){
		$sql = $this->db->query("INSERT INTO tbl_cuentas_cobrar(venta_id, ctac_fecha_credito, ctac_dias_credito, ctac_monto, ctac_estado) VALUES ('$sale', '$date', '$credit', '$amount', '1')");
	}

	function priceVenta($id, $ref){
		$sql = $this->db->query("SELECT * FROM tbl_venta_detalle WHERE producto_id = '$id' AND detalle_referencia = '$ref'");
		return $sql->result_array();
	}

	function verifyProductSale($value){
		$sale = $this->db->query("SELECT * FROM tbl_venta_detalle WHERE producto_id = '$value' LIMIT 1");
		$response = 0;
		if ($sale->num_rows() > 0){
			$response = 1;
		}else{
			$response = 0;
		}
		return $response;
	}

	function mergeUtility(){
		$sql = $this->db->query("SELECT c.detalle_precio_compra_gasto, c.detalle_precio_min, c.detalle_precio_max, v.detalle_precio, p.prod_id, p.prod_codigo, p.prod_nombre, p.prod_unidad, cat.categ_valor, m.marca_nombre FROM ((((tbl_compra_detalle c INNER JOIN tbl_producto p ON p.prod_id = c.producto_id) INNER JOIN tbl_venta_detalle v ON p.prod_id = v.producto_id) INNER JOIN tbl_categoria cat ON p.categ_id = cat.categ_id) INNER JOIN tbl_marca m ON p.marca_id = m.marca_id) WHERE c.compra_id=v.detalle_referencia");
		return $sql->result_array();
	}

	function getCategory(){
		$sql = $this->db->query("SELECT * FROM tbl_categoria");
		return $sql->result_array();
	}

	function getProductSale($id){
		$sql = $this->db->query("SELECT * FROM tbl_producto WHERE categ_id = '$id'");
		return $sql->result_array();
	}
}