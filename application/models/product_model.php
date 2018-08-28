<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Product_model extends CI_Model{

	function __construct(){
		parent::__construct();
		$this->load->database();
	}

	function start(){
		$sql = $this->db->query("SELECT p.prod_id, p.prod_referencia, p.prod_codigo, p.prod_nombre, p.prod_unidad, p.prod_precio_compra, p.prod_precio_transporte, p.prod_precio_gastos, p.prod_precio_vp1, p.prod_precio_vp2, p.prod_precio_venta, p.prod_stock_min, p.prod_stock_real, c.categ_valor, m.marca_nombre FROM ((tbl_producto p INNER JOIN tbl_categoria c ON p.categ_id = c.categ_id) INNER JOIN tbl_marca m ON p.marca_id = m.marca_id) WHERE p.prod_estado = 1");
		return $sql->result_array();
	}

	function register($data){
		$insert = $this->db->query("INSERT INTO tbl_producto(prod_codigo, prod_nombre, prod_unidad, prod_stock_min, prod_stock_real, marca_id, categ_id, prod_estado) VALUES ('".$data['code']."', '".$data['name']."', '".$data['unit']."', '".$data['stockM']."', '".$data['stockR']."', '".$data['mark']."', '".$data['cate']."', 1)");

		if ($insert){
			$id = $this->db->insert_id();
		}else{
			$id = 0;
		}
		return $id;
	}

	function update($data){
		$update = $this->db->query("UPDATE tbl_producto SET prod_nombre = '".$data['name']."', prod_unidad = '".$data['unit']."', prod_stock_min = '".$data['stockM']."', prod_stock_real = '".$data['stockR']."', categ_id = '".$data['cate']."', marca_id = '".$data['mark']."' WHERE prod_id = '".$data['id']."'");
		if ($update){
			$id = 1;
		}else{
			$id = 0;
		}
		return $id;
	}

	function updateCategory($data){
		$update = $this->db->query("UPDATE tbl_categoria SET categ_valor = '".$data['name']."' WHERE categ_id = '".$data['id']."'");
		if ($update){
			$id = 1;
		}else{
			$id = 0;
		}
		return $id;
	}

	function loadProduct($id){
		$sql = $this->db->query("SELECT * FROM tbl_producto WHERE prod_id = '$id' LIMIT 1");
		return $sql->result_array();
	}

	function registerCategory($data){
		$insert = $this->db->query("INSERT INTO tbl_categoria(categ_valor, categ_img) VALUES ('".$data['name']."', '".$data['archivo']."')");

		if ($insert){
			$id = $this->db->insert_id();
		}else{
			$id = 0;
		}
		return $id;
	}

	function delete($id){
		$delete = $this->db->query("UPDATE tbl_producto SET prod_estado = 0 WHERE prod_id = '$id'");
		if ($delete){
			return 1;
		}else{
			return 0;
		}
	}

	function numberProduct(){
		$sql = $this->db->query("SELECT * FROM tbl_producto");
		$number = $sql->num_rows();
		return $number;
	}

	function loadCategoryTable(){
		$sql = $this->db->query("SELECT * FROM tbl_categoria ORDER BY categ_id ASC");
		return $sql->result_array();
	}


	function loadCategorysId($id){
		$sql = $this->db->query("SELECT * FROM tbl_categoria WHERE categ_id = '$id' LIMIT 1");
		return $sql->result_array();
	}

	function loadCategoryId($id){
		$sql = $this->db->query("SELECT * FROM tbl_categoria WHERE categ_id = '$id' ORDER BY categ_valor ASC");
		return $sql->result_array();
	}

	function loadCategory(){
		$sql = $this->db->query("SELECT * FROM tbl_categoria ORDER BY categ_valor ASC");
		return $sql->result_array();
	}

	function maxPrice($id){
		$sql = $this->db->query("SELECT MAX(detalle_precio) AS price_max FROM tbl_venta_detalle WHERE producto_id = '$id'");
		$priceMax = '';

		foreach ($sql->result_array() as $row) {
			$priceMax = $row['price_max'];
		}
		return $priceMax;
	}
	function minPrice($id){
		$sql = $this->db->query("SELECT MIN(detalle_precio) AS price_min FROM tbl_venta_detalle WHERE producto_id = '$id'");
		$priceMin = '';

		foreach ($sql->result_array() as $row) {
			$priceMin = $row['price_min'];
		}
		return $priceMin;
	}

	function loadProductsAll(){
		$products = $this->db->query("SELECT prod_id, categ_id FROM tbl_producto ORDER BY prod_nombre");
		return $products->result_array();
	}

	function loadProductsAllType($type){
		$products = $this->db->query("SELECT prod_id, categ_id FROM tbl_producto WHERE categ_id = '$type' ORDER BY prod_nombre");
		return $products->result_array();
	}
	function verifyProductInventary($id, $from, $to){
		$from = $from.' 00:00:00';
		$to = $to.' 23:59:59';
		$shopping = $this->db->query("SELECT cd.* FROM (tbl_compra_detalle cd INNER JOIN tbl_compra c ON cd.compra_id = c.compra_id) WHERE cd.producto_id = '$id' AND c.compra_fecha BETWEEN '$from' AND '$to' AND c.compra_estado = 1");
		$sale = $this->db->query("SELECT vd.* FROM (tbl_venta_detalle vd INNER JOIN tbl_venta v ON vd.venta_id = v.venta_id) WHERE vd.producto_id = '$id' AND v.venta_fecha BETWEEN '$from' AND '$to' AND v.venta_estado = 1");
		$shopping = $shopping->num_rows();
		$sale = $sale->num_rows();
		$total = $shopping + $sale;
		return $total;
	}

	function loadProducts($value){
		$products = $this->db->query("SELECT * FROM tbl_producto WHERE prod_id = '$value' ORDER BY prod_id DESC");
		return $products->result_array();
	}

	function loadSalesKardexArrayBefore($id, $from){
		$from = $from.' 00:00:00';
		$sales = $this->db->query("SELECT SUM(vd.detalle_cantidad) AS total FROM (tbl_venta_detalle vd INNER JOIN tbl_venta v ON vd.venta_id = v.venta_id) WHERE vd.producto_id = '$id' AND v.venta_fecha < '$from' AND v.venta_estado = 1");
		$total = 0;
		foreach ($sales->result_array() as $row){
			$total = $row['total'];
		}
		return $total;
	}

	function loadShoppingKardexArrayBefore($id, $from){
		$from = $from.' 00:00:00';
		$sales = $this->db->query("SELECT SUM(cd.detalle_cantidad) AS total FROM (tbl_compra_detalle cd INNER JOIN tbl_compra c ON cd.compra_id = c.compra_id) WHERE cd.producto_id = '$id' AND c.compra_fecha < '$from' AND c.compra_estado = 1");
		$total = 0;
		foreach ($sales->result_array() as $row){
			$total = $row['total'];
		}
		return $total;
	}

	function loadSalesKardexNumDate($id, $from, $to){
		$from = $from.' 00:00:00';
		$to = $to.' 23:59:59';
		$sales = $this->db->query("SELECT v.* FROM (tbl_venta v INNER JOIN tbl_venta_detalle vd ON v.venta_id = vd.venta_id) WHERE vd.producto_id = '$id' AND v.venta_fecha BETWEEN '$from' AND '$to' AND v.venta_estado = 1");
		return $sales->num_rows();
	}

	function loadShoppingKardexNumDate($id, $from, $to){
		$from = $from.' 00:00:00';
		$to = $to.' 23:59:59';
		$shopping = $this->db->query("SELECT c.* FROM (tbl_compra c INNER JOIN tbl_compra_detalle cd ON c.compra_id = cd.compra_id) WHERE cd.producto_id = '$id' AND c.compra_fecha BETWEEN '$from' AND '$to' AND c.compra_estado = 1");
		return $shopping->num_rows();
	}

	function loadSalesKardexArrayDate($id, $from, $to){
		$from = $from.' 00:00:00';
		$to = $to.' 23:59:59';
		$sales = $this->db->query("SELECT v.* FROM (tbl_venta v INNER JOIN tbl_venta_detalle vd ON v.venta_id = vd.venta_id) WHERE vd.producto_id = '$id' AND v.venta_fecha BETWEEN '$from' AND '$to' AND v.venta_estado = 1");
		return $sales->result_array();
	}

	function loadShoppingKardexArrayDate($id, $from, $to){
		$from = $from.' 00:00:00';
		$to = $to.' 23:59:59';
		$shopping = $this->db->query("SELECT c.* FROM (tbl_compra c INNER JOIN tbl_compra_detalle cd ON c.compra_id = cd.compra_id) WHERE cd.producto_id = '$id' AND c.compra_fecha BETWEEN '$from' AND '$to' AND c.compra_estado = 1");
		return $shopping->result_array();
	}

	function loadSaleSQL($id){
		$sales = $this->db->query("SELECT * FROM tbl_venta WHERE venta_estado = 1 AND venta_id = '$id'");
		return $sales->result_array();
	}

	function customerSale($value){
		$customer = $this->db->query("SELECT * FROM tbl_cliente WHERE cli_id = '$value' LIMIT 1");
		return $customer->result_array();
	}

	function sale($idSale, $idProduct){
		$sale = $this->db->query("SELECT SUM(vd.detalle_cantidad) AS total FROM (tbl_venta_detalle vd INNER JOIN tbl_venta v ON vd.venta_id = v.venta_id) WHERE vd.producto_id = '$idProduct' AND v.venta_id = '$idSale' AND v.venta_estado = 1");
		return $sale->result_array();
	}

	function loadShoppingSQL($id){
		$shopping = $this->db->query("SELECT * FROM tbl_compra WHERE compra_estado = 1 AND compra_id = '$id'");
		return $shopping->result_array();
	}

	function providerSale($value){
		$customer = $this->db->query("SELECT * FROM tbl_proveedor WHERE prov_id = '$value' LIMIT 1");
		return $customer->result_array();
	}

	function shopping($idShopping, $idProduct){
		$sale = $this->db->query("SELECT SUM(cd.detalle_cantidad) AS total FROM (tbl_compra_detalle cd INNER JOIN tbl_compra c ON cd.compra_id = c.compra_id) WHERE cd.producto_id = '$idProduct' AND c.compra_id = '$idShopping' AND c.compra_estado = 1");
		return $sale->result_array();
	}

	function amountDateProductBefore($type, $before, $id){
		$from = $before.' 00:00:00';

		$shopping = $this->db->query("SELECT SUM(cd.detalle_cantidad) AS total FROM (tbl_compra_detalle cd INNER JOIN tbl_compra c ON cd.compra_id = c.compra_id) WHERE cd.producto_id = '$id' AND c.compra_fecha < '$from' AND c.compra_estado = 1");
		$totalShopping = 0;
		foreach ($shopping->result_array() as $rowShopping){
			$totalShopping = $rowShopping['total'];
		}

		$sale = $this->db->query("SELECT SUM(vd.detalle_cantidad) AS total FROM (tbl_venta_detalle vd INNER JOIN tbl_venta v ON vd.venta_id = v.venta_id) WHERE vd.producto_id = '$id' AND v.venta_fecha < '$from' AND v.venta_estado = 1");
		$totalSale = 0;
		foreach ($sale->result_array() as $rowSale){
			$totalSale = $rowSale['total'];
		}

		$total = $totalShopping - $totalSale;
		return $total;
	}

	function amountDateProduct($type, $date, $id){
		$from = $date.' 00:00:00';
		$to = $date.' 23:59:59';

		$shopping = $this->db->query("SELECT SUM(cd.detalle_cantidad) AS total FROM (tbl_compra_detalle cd INNER JOIN tbl_compra c ON cd.compra_id = c.compra_id) WHERE cd.producto_id = '$id' AND c.compra_fecha BETWEEN '$from' AND '$to' AND c.compra_estado = 1");
		$totalShopping = 0;
		foreach ($shopping->result_array() as $rowShopping){
			$totalShopping = $rowShopping['total'];
		}

		$sale = $this->db->query("SELECT SUM(vd.detalle_cantidad) AS total FROM (tbl_venta_detalle vd INNER JOIN tbl_venta v ON vd.venta_id = v.venta_id) WHERE vd.producto_id = '$id' AND v.venta_fecha BETWEEN '$from' AND '$to' AND v.venta_estado = 1");
		$totalSale = 0;
		foreach ($sale->result_array() as $rowSale){
			$totalSale = $rowSale['total'];
		}

		$total = $totalShopping - $totalSale;
		return $total;
	}

	function loadPriceProduct($id){
		$sql = $this->db->query("SELECT prod_precio_vp1, prod_precio_vp2 FROM tbl_producto WHERE prod_id = $id");
		return $sql->result_array();
	}

	function loadMarkTable(){
		$sql = $this->db->query("SELECT * FROM tbl_marca ORDER BY marca_id ASC");
		return $sql->result_array();
	}

	function registerMark($data){
		$insert = $this->db->query("INSERT INTO tbl_marca(marca_nombre) VALUES ('".$data['name']."')");

		if ($insert){
			$id = $this->db->insert_id();
		}else{
			$id = 0;
		}
		return $id;
	}

	function loadMarkId($id){
		$sql = $this->db->query("SELECT * FROM tbl_marca WHERE marca_id = '$id' ORDER BY marca_nombre ASC");
		return $sql->result_array();
	}

	function loadMarkasId($id){
		$sql = $this->db->query("SELECT * FROM tbl_marca WHERE marca_id = '$id' LIMIT 1");
		return $sql->result_array();
	}

	function loadMarkIDS($id){
		$sql = $this->db->query("SELECT * FROM tbl_marca WHERE marca_id = '$id' LIMIT 1");
		return $sql->result_array();
	}

	function loadMarca(){
		$sql = $this->db->query("SELECT * FROM tbl_marca ORDER BY marca_nombre ASC");
		return $sql->result_array();
	}

	function updateMark($data){
		$update = $this->db->query("UPDATE tbl_marca SET marca_nombre = '".$data['name']."' WHERE marca_id = '".$data['id']."'");
		if ($update){
			$id = 1;
		}else{
			$id = 0;
		}
		return $id;
	}

	function loadMarks(){
		$sql = $this->db->query("SELECT * FROM tbl_marca ORDER BY marca_nombre ASC");
		return $sql->result_array();
	}

	function actualPrice($id, $p1, $p2){
		$sql = $this->db->query("UPDATE tbl_producto SET prod_precio_vp1 = '$p1', prod_precio_vp2 = '$p2' WHERE  prod_id = '$id'");

		if ($sql){
			return 1;
		}else{
			return 0;
		}
	}
}