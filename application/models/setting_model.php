<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Setting_model extends CI_Model{

	function __construct(){
		parent::__construct();
		$this->load->database();
		if(isset($_SESSION['usu_sv'])){
			$this->user = $_SESSION['usu_sv'];
		}else if(isset($_SESSION['adm_sv'])){
			$this->user = $_SESSION['adm_sv'];
		}
	}

	public function getSerie($type){
		switch($type){
			case 1:
				$clause = "SELECT serie_venta_factura AS serie FROM tbl_serie";
			break;
			case 2:
				$clause = "SELECT serie_venta_boleta AS serie FROM tbl_serie";
			break;
			case 3:
				$clause = "SELECT serie_venta_alternativa AS serie FROM tbl_serie";
			break;
			case 4:
				$clause = "SELECT serie_vale AS serie FROM tbl_serie";
			break;
			case 5:
				$clause = "SELECT serie_nota_credito_factura AS serie FROM tbl_serie";
			break;
			case 6:
				$clause = "SELECT serie_nota_credito_boleta AS serie FROM tbl_serie";
			break;
			case 7:
				$clause = "SELECT serie_pedido AS serie FROM tbl_serie";
			break;
		}
		$sql = $this->db->query($clause);
		$serie = '';
		foreach($sql->result_array() as $row){
			$serie = $row['serie'];
		}
		return $serie;
	}

	public function getNumberDocument($type, $serie){
		switch($type){
			case 1:
				$clause = "SELECT (MAX(venta_numero)+1) AS n FROM tbl_venta WHERE venta_tipo = 'F' AND venta_serie = '$serie'";
			break;
			case 2:
				$clause = "SELECT (MAX(venta_numero)+1) AS n FROM tbl_venta WHERE venta_tipo = 'B' AND venta_serie = '$serie'";
			break;
			case 3:
				$clause = "SELECT (MAX(venta_numero)+1) AS n FROM tbl_venta WHERE venta_tipo = 'A' AND venta_serie = '$serie'";
			break;
			case 4:
				$clause = "SELECT (MAX(vale_numero)+1) AS n FROM tbl_vale WHERE vale_serie = '$serie'";
			break;
			case 5:
				$clause = "SELECT (MAX(nota_numero)+1) AS n FROM tbl_nota WHERE nota_tipo = 'FC' AND nota_serie = '$serie'";
			break;
			case 6:
				$clause = "SELECT (MAX(nota_numero)+1) AS n FROM tbl_nota WHERE nota_tipo = 'BC' AND nota_serie = '$serie'";
			break;
			case 7:
				$clause = "SELECT (MAX(pedido_numero)+1) AS n FROM tbl_pedido WHERE pedido_serie = '$serie'";
			break;
		}
		$n = '';
		$sql = $this->db->query($clause);
		foreach ($sql->result_array() AS $row){
			if($row['n'] == NULL){
				$n = 1;
			}else{
				$n = $row['n'];
			}
		}
		return $n;
	}

	public function checkPassword($password){
		$sql = $this->db->query("SELECT * FROM tbl_usuario WHERE usu_clave = '$password' AND usu_nombre = '".$this->user."'");
		return $sql->num_rows();
	}

	public function updatePassword($password){
		$sql = $this->db->query("UPDATE tbl_usuario SET usu_clave = '$password' WHERE usu_nombre = '".$this->user."'");
		if($sql){
			echo 'ok';
		}else{
			echo 'sql';
		}
	}

	public function updateSeries($data){
		$sql = $this->db->query("UPDATE tbl_serie SET serie_venta_factura = '".$data['s1']."', serie_venta_boleta = '".$data['s2']."', serie_venta_alternativa = '".$data['s3']."', serie_nota_credito_factura = '".$data['s4']."', serie_nota_credito_boleta = '".$data['s5']."', serie_pedido = '".$data['s6']."'");
		if($sql){
			return 'ok';
		}else{
			return 'sql';
		}
	}

	public function updateDatosEconomicos($data){
		$sql = $this->db->query("INSERT INTO tbl_datos_economicos(datos_gasto_mensual, datos_impuesto_renta, datos_porcentaje_gastos, datos_tipo_cambio) VALUES ('".$data['d1']."', '".$data['d2']."', '".$data['d3']."', '".$data['d4']."')");
		if($sql){
			return 'ok';
		}else{
			return 'sql';
		}
	}

	public function updateIgv($data){
		$sql = $this->db->query("INSERT INTO tbl_igv(igv_registro, igv_porcentaje, igv_estado) VALUES ('".$data['d5']."', '".$data['d6']."', 1)");
		if($sql){
			return 'ok';
		}else{
			return 'sql';
		}
	}
}