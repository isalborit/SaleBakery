<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model{

	function __construct(){
		parent::__construct();
		$this->load->database();
	}

	function start(){
		$sql = $this->db->query("SELECT * FROM tbl_usuario WHERE usu_estado = 1");
		return $sql->result_array();
	}

	function register($data){
		$insert = $this->db->query("INSERT INTO tbl_usuario(emp_id, usu_registro, usu_nombre, usu_clave, usu_perfil, usu_rol, usu_estado) VALUES ('".$data['id']."', '".$data['date']."', '".$data['name']."', '".$data['pass']."', '', '".$data['role']."', '".$data['status']."')");

		if ($insert){
			$id = $this->db->insert_id();
		}else{
			$id = 0;
		}
		return $id;
	}

	function delete($id){
		$delete = $this->db->query("UPDATE tbl_usuario SET usu_estado = 0 WHERE usu_id = '$id'");
		if ($delete){
			return 1;
			$deleteAccess = $this->query("DELETE FROM tbl_acceso WHERE usu_id = '$id'");
		}else{
			return 0;
		}
	}

	function registerAccess($data){
		$insert = $this->db->query("INSERT INTO tbl_acceso(acceso_registro, usu_id, acceso_empleado, acceso_usuario, acceso_cliente, acceso_proveedor, acceso_producto, acceso_ventas, acceso_compras, acceso_nota_credito, acceso_pedido, acceso_cuentas, acceso_reporte) VALUES ('".$data['Adate']."', '".$data['Aid']."', '".$data['Aemployee']."', '".$data['Auser']."', '".$data['Acustomer']."', '".$data['Aprovider']."', '".$data['Aproduct']."', '".$data['Asale']."', '".$data['Ashopping']."', '".$data['Anote']."', '".$data['Aorder']."', '".$data['Aaccount']."', '".$data['Areport']."')");

		if ($insert){
			$id = $this->db->insert_id();
		}else{
			$id = 0;
		}
		return $id;
	}

	function nameEmployee($emp){
		$name = '';
		$sql = $this->db->query("SELECT emp_nombre, emp_apellido FROM tbl_empleado WHERE emp_id = '$emp'");
		

		if ($sql){
			$result = $sql->result_array();
			foreach ($result as $row) {
				$name = $row['emp_nombre'].' '.$row['emp_apellido'];
			}
		}

		return $name;
	}

	function checkDni($dni){
		$sql = $this->db->query("SELECT * FROM tbl_empleado WHERE emp_documento = '$dni'");

		if ($sql->num_rows() > 0){
			return 1;
		}else{
			return 0;
		}
	}

	function loadEmployeeDni($dni){
		$sql = $this->db->query("SELECT emp_id, emp_nombre, emp_apellido FROM tbl_empleado WHERE emp_documento = '$dni'");
		return $sql->result_array();
	}

	function checkUser($id){
		$sql = $this->db->query("SELECT * FROM tbl_usuario WHERE emp_id = '$id' AND usu_estado = 1");

		if ($sql->num_rows() > 0){
			return 1;
		}else{
			return 0;
		}
	}

	function numberUser(){
		$sql = $this->db->query("SELECT * FROM tbl_usuario");
		$number = $sql->num_rows();
		return $number;
	}
}