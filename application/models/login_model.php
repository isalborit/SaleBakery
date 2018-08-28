<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Login_model extends CI_Model{

	function __construct(){
		parent::__construct();
		$this->load->database();
	}

	function accessLogin($data){
		$user = trim($data['user']);
		$pass = trim($data['pass']);

		$login = $this->db->query("SELECT * FROM tbl_usuario WHERE usu_nombre = '$user' AND usu_estado = 1");
		$result = $login->num_rows();
		$rol = '';

		if ($result > 0){
			$login1 = $this->db->query("SELECT * FROM tbl_usuario WHERE usu_nombre = '$user' AND usu_clave = '$pass'");
			$result1 = $login1->num_rows();

			if ($result1 > 0){
				foreach ($login->result_array() as $row){
					$rol = $row['usu_rol'];
				}
				$status = 1;
			}else{
				$status = 0;
			}
			
		}else{
			$status = 2;
		}

		$array = array(0 => $status,
					   1 => $rol);

		return json_encode($array);
	}

	function userData($data){
		$userData = $this->db->query("SELECT emp.emp_nombre, emp.emp_apellido FROM tbl_empleado emp INNER JOIN tbl_usuario usu ON usu.emp_id = emp.emp_id WHERE usu_nombre = '".$data['user']."' LIMIT 1");
		return $userData->result_array();
	}

	function userDataId($data){
		$userData = $this->db->query("SELECT emp.emp_nombre, emp.emp_apellido FROM tbl_empleado emp INNER JOIN tbl_usuario usu ON usu.emp_id = emp.emp_id WHERE usu_id = '".$data['id']."' LIMIT 1");
		return $userData->result_array();
	}

	function accessAll($data){
		$user = $this->db->query("SELECT usu_id FROM tbl_usuario WHERE usu_nombre = '".$data['user']."'");
		$userId = '';
		foreach ($user->result_array() as $row){
			$userId = $row['usu_id'];
		}

		$access = $this->db->query("SELECT * FROM tbl_acceso WHERE usu_id = '$userId'");

		$empleado = 0;
		$usuario = 0;
		$cliente = 0;
		$proveedor = 0;
		$producto = 0;
		$ventas = 0;
		$compras = 0;
		$reportes = 0;
		$note = 0;
		$cuentas = 0;

		foreach ($access->result_array() as $row){
			$empleado = $row['acceso_empleado'];
			$usuario = $row['acceso_usuario'];
			$cliente = $row['acceso_cliente'];
			$proveedor = $row['acceso_proveedor'];
			$producto = $row['acceso_producto'];
			$ventas = $row['acceso_ventas'];
			$compras = $row['acceso_compras'];
			$reportes = $row['acceso_reporte'];
			$note = $row['acceso_nota_credito'];
		}

		$array = array(0 => $cliente,
					   1 => $empleado,
					   2 => $producto,
					   3 => $proveedor,
					   4 => $compras,
					   5 => $usuario,
					   6 => $ventas,
					   7 => $reportes,
					   8 => $note
					);

		return json_encode($array);
	}

	function getSeries(){
		$sql = $this->db->query("SELECT * FROM tbl_serie ORDER BY serie_id DESC LIMIT 1");
		return $sql->result_array();
	}

	function getDatos(){
		$sql = $this->db->query("SELECT * FROM tbl_datos_economicos ORDER BY datos_id DESC LIMIT 1");
		return $sql->result_array();
	}

	function getIgv(){
		$sql = $this->db->query("SELECT * FROM tbl_igv ORDER BY igv_id DESC LIMIT 1");
		return $sql->result_array();
	}
}