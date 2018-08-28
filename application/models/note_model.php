<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Note_model extends CI_Model{

	function __construct(){
		parent::__construct();
		$this->load->database();
	}

	function start(){
		$sql = $this->db->query("SELECT * FROM tbl_nota");
		return $sql->result_array();
	}

	function register($data){
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

		$insert = $this->db->query("INSERT INTO tbl_nota(nota_fecha, nota_tipo, nota_serie, nota_numero, venta_id, nota_motivo, tipo_doc_id, usuario_id) VALUES ('".$data['date']."', '".$data['type']."', '".$data['serie']."', '".$data['number']."', '".$data['sale']."', '".$data['desc']."', '".$data['typeDoc']."', '$userId')");

		if ($insert){
			$id = $this->db->insert_id();
		}else{
			$id = 0;
		}
		return $id;
	}

	/*function delete($id){
		$delete = $this->db->query("UPDATE tbl_producto SET prod_estado = 0 WHERE prod_id = '$id'");
		if ($delete){
			return 1;
		}else{
			return 0;
		}
	}*/

	function numberNote(){
		$sql = $this->db->query("SELECT * FROM tbl_nota");
		$number = $sql->num_rows();
		return $number;
	}

	function numberSale($id){
		$sql = $this->db->query("SELECT * FROM tbl_venta WHERE venta_id = '$id'");
		return $sql->result_array();
	}

	function verifyDoc($serie, $number){
		$sql = $this->db->query("SELECT * FROM tbl_venta WHERE venta_serie = '$serie' AND venta_numero = '$number' AND venta_estado = 1");
		if ($sql->num_rows() > 0){
			return 1;
		}else{
			return 0;
		}
	}

	function saleCode($serie, $number){
		$sql = $this->db->query("SELECT * FROM tbl_venta WHERE venta_serie = '$serie' AND venta_numero = '$number'");
		$code = 0;
		foreach ($sql->result_array() as $row){
			$code = $row['venta_id'];
		}
		return $code;
	}

	function updateSale($id){
		$update = $this->db->query("UPDATE tbl_venta SET venta_estado = 0 WHERE venta_id = '$id'");
	}

	function idProduct($idSale){
		$sql = $this->db->query("SELECT * FROM tbl_venta_detalle WHERE venta_id = '$idSale'");
		return $sql->result_array();
	}

	function stockReal($id){
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
}