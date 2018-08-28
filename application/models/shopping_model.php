<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Shopping_model extends CI_Model{

	function __construct(){
		parent::__construct();
		$this->load->database();
	}

	function saveShopping($data){
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
		
		$insert = $this->db->query("INSERT INTO tbl_compra(compra_serie, compra_numero, compra_tipo, compra_fecha, compra_moneda, proveedor_id, movimiento_id, credito_id, contado_id, usuario_id, igv_porcentaje, compra_estado)VALUES('".$data['serie']."', '".$data['number']."', '".$data['type']."', '".$data['date']."', '".$data['currency']."', '".$data['prove']."', '".$data['mov']."', '".$data['credit']."', '".$data['method']."', '$userId', '".$data['igv']."', 1)");
		if($insert){
			$id = $this->db->insert_id();
		}else{
			$id = 0;
		}
		return $id;
	}

	function saveDetailShopping($data){
		$insert = $this->db->query("INSERT INTO tbl_compra_detalle(compra_id, producto_id, detalle_precio, detalle_precio_igv, detalle_precio_transporte, detalle_precio_compra, detalle_precio_compra_gasto, detalle_porc_min, detalle_porc_max, detalle_precio_min, detalle_precio_max, detalle_unidad, detalle_cantidad, detalle_descuento, detalle_impuesto)VALUES('".$data['idShopping']."', '".$data['idPro']."', '".$data['pricePro']."', '".$data['priceProIGV']."', '".$data['transPro']."', '".$data['priceC']."', '".$data['priceCG']."', '".$data['percentPro1']."', '".$data['percentPro2']."', '".$data['priceP1']."', '".$data['priceP2']."', '".$data['unitPro']."', '".$data['amountPro']."', 0, 1)");
	}

	function delete($id){
		$delete = $this->db->query("DELETE FROM tbl_compra WHERE compra_id = '$id'");
		if($delete){
			$deleteDetail = $this->db->query("DELETE FROM tbl_compra_detalle WHERE compra_id = '$id'");
			if($deleteDetail){
				return 1;
			}else{
				return 0;
			}
		}else{
			return 0;
		}
	}

	function loadRecordShoppingId($value){
		$sale = $this->db->query("SELECT * FROM tbl_compra WHERE compra_id = '$value' LIMIT 1");
		return $sale->result_array();	
	}

	function loadRecordShoppingToday(){
		$hoy = date('Y-m-d');
		$sale = $this->db->query("SELECT * FROM tbl_compra WHERE DATE(compra_fecha) = '$hoy' AND compra_estado = 1 ORDER BY compra_fecha DESC");
		return $sale->result_array();	
	}

	function searchShoppingByDate($data){
		$sale = $this->db->query("SELECT * FROM tbl_compra WHERE compra_fecha BETWEEN '".$data['from']."' AND '".$data['to']."' AND compra_estado = 1 ORDER BY compra_fecha DESC");
		return $sale->result_array();	
	}

	function loadProviderId($value){
		$customer = $this->db->query("SELECT prov_documento, prov_nombre FROM tbl_proveedor WHERE prov_id = '$value' LIMIT 1");
		return $customer->result_array();
	}

	function loadPrice($id){
		$sql = $this->db->query("SELECT * FROM tbl_producto WHERE prod_id = $id");
		return $sql->result_array();
	}

	function numberShopping(){
		$sql = $this->db->query("SELECT * FROM tbl_compra");
		$number = $sql->num_rows();
		return $number;
	}

	function loadMovements(){
		$movements = $this->db->query("SELECT * FROM tbl_movimiento ORDER BY mov_id");
		return $movements->result_array();
	}

	function loadMovementsId($id){
		$movements = $this->db->query("SELECT * FROM tbl_movimiento WHERE mov_id = '$id' ");
		return $movements->result_array();
	}

	function loadCredits(){
		$sql = $this->db->query("SELECT * FROM tbl_credito ORDER BY credito_numero_dias");
		return $sql->result_array();
	}

	function loadMethods(){
		$sql = $this->db->query("SELECT * FROM tbl_contado");
		return $sql->result_array();
	}

	function loadProductId($value){
		$product = $this->db->query("SELECT * FROM tbl_producto WHERE prod_id = '$value' LIMIT 1");
		return $product->result_array();
	}

	function loadProductDescription($value){
		$product = $this->db->query("SELECT * FROM tbl_producto WHERE prod_nombre LIKE '%$value%' LIMIT 8");
		return $product->result_array();
	}

	function loadProductShopping($value, $type){
		if($type == 'code'){
			$product = $this->db->query("SELECT * FROM tbl_producto WHERE prod_codigo = '$value' LIMIT 1");
		}
		return $product->result_array();
	}

	function getCurrentIgv(){
		$igv = $this->db->query("SELECT igv_porcentaje FROM tbl_igv WHERE igv_estado = 1 ORDER BY igv_id DESC LIMIT 1");
		return $igv->result_array();	
	}

	function checkSerieNumber($serie, $numero, $prov){
		$sql = $this->db->query("SELECT * FROM tbl_compra WHERE compra_serie = '$serie' AND compra_numero = '$numero' AND proveedor_id = '$prov'");
		return $sql->num_rows();
	}
	/*aki me kede*/
	function updateShoppingFinal($id, $subtotal, $discount, $igv, $neto){
		$this->db->query("UPDATE tbl_compra SET compra_subtotal = '$subtotal', compra_descuento = '$discount', compra_igv = '$igv', compra_neto = '$neto' WHERE compra_id = '$id'");
	}

	function igvInvoice($id){
		$saleDetail = $this->db->query("SELECT igv_porcentaje FROM tbl_compra WHERE compra_id = '$id'");
		$igv = 0;
		foreach($saleDetail->result_array() as $row){
			$igv = $row['igv_porcentaje'];
		}
		return $igv;
	}

	function loadRecordShoppingDetail($value){
		$saleDetail = $this->db->query("SELECT * FROM tbl_compra_detalle WHERE compra_id = '$value'");
		return $saleDetail->result_array();	
	}

	function searchProvider($value){
		$cliente = $this->db->query("SELECT * FROM tbl_proveedor WHERE prov_documento = '$value' LIMIT 1");
		return $cliente->result_array();
	}

	function stockReal($id){
		$sqlPro = $this->db->query("SELECT * FROM tbl_producto WHERE prod_id = '$id' LIMIT 1");
		return $sqlPro->result_array();
	}

	function actualizarStock($id, $cant){
		$sql = $this->db->query("UPDATE tbl_producto SET prod_stock_real = '$cant' WHERE prod_id = '$id'");
	}

	function actualizarReferencia($id, $shopping){
		$sql = $this->db->query("UPDATE tbl_producto SET prod_referencia = '$shopping' WHERE prod_id = '$id'");
	}

	function actualizarPrecios($id, $precioC, $precioT, $precioU, $precioP1, $precioP2, $shopping){
		$sql = $this->db->query("UPDATE tbl_producto SET prod_precio_compra = '$precioC', prod_precio_transporte = '$precioT', prod_precio_gastos = '$precioU', prod_precio_vp1 = '$precioP1', prod_precio_vp2 = '$precioP2', prod_referencia = '$shopping' WHERE prod_id = '$id'");
	}

	function salesStatisticYear($year, $currency, $month){
		$sql = $this->db->query("SELECT SUM(dv.detalle_cantidad * dv.detalle_precio) AS total FROM tbl_compra_detalle AS dv INNER JOIN tbl_compra AS v ON dv.compra_id = v.compra_id WHERE MONTH(v.compra_fecha) = '$month' AND YEAR(v.compra_fecha) = '$year' AND v.compra_estado = 1 AND v.compra_moneda = '$currency'");
		$total = 0;
		foreach ($sql->result_array() as $row){
			$total = $row['total'];
		}
		return $total;
	}

	function searchShoppingByDate2($data){
		$sale = $this->db->query("SELECT * FROM tbl_compra WHERE compra_fecha BETWEEN '".$data['from']."' AND '".$data['to']."' AND compra_estado = 1 AND compra_moneda = '".$data['currency']."' ORDER BY compra_fecha ASC");
		return $sale->result_array();	
	}

	function loadProviderName($value){
		$customer = $this->db->query("SELECT prov_id, prov_nombre FROM tbl_proveedor WHERE prov_nombre LIKE '%$value%' LIMIT 10");
		return $customer->result_array();
	}

	function searchShoppingByCustomer($data, $from, $to, $currency){
		$sale = $this->db->query("SELECT * FROM tbl_compra WHERE compra_fecha BETWEEN '$from' AND '$to' AND proveedor_id = '$data' AND compra_estado = 1 AND compra_moneda = '$currency' ORDER BY compra_fecha ASC");
		return $sale->result_array();	
	}

	function searchProviderInShopping($from, $to, $type){
		if ($type == 1){
			$sql = $this->db->query("SELECT COUNT(c.proveedor_id) AS habitual, c.proveedor_id, p.prov_registro, p.prov_documento, p.prov_nombre, p.prov_direccion, p.prov_telefono, p.prov_correo FROM (tbl_compra c INNER JOIN tbl_proveedor p ON c.proveedor_id = p.prov_id) WHERE c.compra_fecha BETWEEN '$from' AND '$to' GROUP BY c.proveedor_id");
		}elseif($type == 2){
			$sql = $this->db->query("SELECT COUNT(c.proveedor_id) AS habitual, c.proveedor_id, p.prov_registro, p.prov_documento, p.prov_nombre, p.prov_direccion, p.prov_telefono, p.prov_correo FROM (tbl_compra c INNER JOIN tbl_proveedor p ON c.proveedor_id = p.prov_id) WHERE c.compra_fecha BETWEEN '$from' AND '$to' GROUP BY c.proveedor_id ORDER BY habitual DESC LIMIT 20");
		}
		return $sql->result_array();
	}

	function currencyChange(){
		$sql = $this->db->query("SELECT * FROM tbl_datos_economicos ORDER BY datos_id DESC LIMIT 1");
		$tpc = 0;
		foreach ($sql->result_array() as $row){
			$tpc = $row['datos_tipo_cambio'];
		}
		return $tpc;
	}

	function utilidad(){
		$sql = $this->db->query("SELECT * FROM tbl_datos_economicos ORDER BY datos_id DESC LIMIT 1");
		return $sql->result_array();
	}

	function loadNumberCredit($id){
		$sql = $this->db->query("SELECT * FROM tbl_credito WHERE credito_id = '$id'");
		$total = 0;
		foreach ($sql->result_array() as $row){
			$total = $row['credito_numero_dias'];
		}
		return $total;
	}

	function insertCtaPay($shopping, $date, $credit, $amount){
		$sql = $this->db->query("INSERT INTO tbl_cuentas_pagar(compra_id, ctap_fecha_credito, ctap_dias_credito, ctap_monto, ctap_estado) VALUES ('$shopping', '$date', '$credit', '$amount', '1')");
	}

	function priceCompra($id){
		$sql = $this->db->query("SELECT * FROM tbl_compra_detalle WHERE producto_id = '$id'");
		return $sql->result_array();
	}

	function CalculateConIGV($id){
		$sql = $this->db->query("SELECT SUM(detalle_precio * detalle_cantidad) AS conIGV FROM tbl_compra_detalle WHERE compra_id = '$id'");
		$total = 0;
		foreach ($sql->result_array() as $row){
			$total = $row['conIGV'];
		}
		return $total;
	}

	function CalculateSinIGV($id, $igv){
		$sql = $this->db->query("SELECT (SUM((detalle_precio / '$igv') * detalle_cantidad)) AS sinIGV FROM tbl_compra_detalle WHERE compra_id = '$id'");
		$total = 0;
		foreach ($sql->result_array() as $row){
			$total = $row['sinIGV'];
		}
		return $total;
	}
}