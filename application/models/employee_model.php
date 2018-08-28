<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Employee_model extends CI_Model{

	function __construct(){
		parent::__construct();
		$this->load->database();
	}

	function start(){
		$sql = $this->db->query("SELECT * FROM tbl_empleado WHERE emp_estado = 1");
		return $sql->result_array();
	}

	function register($data){
		$insert = $this->db->query("INSERT INTO tbl_empleado(emp_registro, emp_documento, emp_nombre, emp_apellido, emp_direccion, emp_telefono, emp_sexo, temp_id, area_id, emp_estado) VALUES ('".$data['date']."', '".$data['document']."', '".$data['name']."', '".$data['lastname']."', '".$data['direction']."', '".$data['phone']."', '".$data['sex']."', '".$data['temp']."', '".$data['area']."', '".$data['status']."')");

		if ($insert){
			$id = $this->db->insert_id();
		}else{
			$id = 0;
		}
		return $id;
	}

	function update($data){
		$update = $this->db->query("UPDATE tbl_empleado SET emp_documento = '".$data['document']."', emp_nombre = '".$data['name']."', emp_apellido = '".$data['lastname']."', emp_direccion = '".$data['direction']."', emp_telefono = '".$data['phone']."', emp_sexo = '".$data['sex']."', temp_id = '".$data['temp']."', area_id = '".$data['area']."' WHERE emp_id = '".$data['id']."'");
		if ($update){
			$id = 1;
		}else{
			$id = 0;
		}
		return $id;
	}

	function registerType($data){
		$insert = $this->db->query("INSERT INTO tbl_tipo_empleado(temp_valor) VALUES ('".$data['name']."')");

		if ($insert){
			$id = $this->db->insert_id();
		}else{
			$id = 0;
		}
		return $id;
	}

	function updateTypeEmployee($data){
		$update = $this->db->query("UPDATE tbl_tipo_empleado SET temp_valor = '".$data['name']."' WHERE temp_id = '".$data['id']."'");
		if ($update){
			$id = 1;
		}else{
			$id = 0;
		}
		return $id;
	}

	function updateArea($data){
		$update = $this->db->query("UPDATE tbl_area SET area_nombre = '".$data['name']."' WHERE area_id = '".$data['id']."'");
		if ($update){
			$id = 1;
		}else{
			$id = 0;
		}
		return $id;
	}

	function registerArea($data){
		$insert = $this->db->query("INSERT INTO tbl_area(area_nombre) VALUES ('".$data['name']."')");

		if ($insert){
			$id = $this->db->insert_id();
		}else{
			$id = 0;
		}
		return $id;
	}

	function delete($id){
		$delete = $this->db->query("UPDATE tbl_empleado SET emp_estado = 0 WHERE emp_id = '$id'");
		if ($delete){
			return 1;
		}else{
			return 0;
		}
	}

	function loadTypeEmployeeTable(){
		$sql = $this->db->query("SELECT * FROM tbl_tipo_empleado ORDER BY temp_id ASC");
		return $sql->result_array();
	}

	function loadTypeId($id){
		$sql = $this->db->query("SELECT * FROM tbl_tipo_empleado WHERE temp_id = '$id' ORDER BY temp_id ASC");
		return $sql->result_array();
	}

	function loadTypeEmployee(){
		$sql = $this->db->query("SELECT * FROM tbl_tipo_empleado ORDER BY temp_valor ASC");
		return $sql->result_array();
	}

	function loadTypeEmployeeIDS($id){
		$sql = $this->db->query("SELECT * FROM tbl_tipo_empleado WHERE temp_id = '$id' LIMIT 1");
		return $sql->result_array();
	}

	function loadAreaTable(){
		$sql = $this->db->query("SELECT * FROM tbl_area ORDER BY area_id ASC");
		return $sql->result_array();
	}

	function loadArea(){
		$sql = $this->db->query("SELECT * FROM tbl_area ORDER BY area_nombre ASC");
		return $sql->result_array();
	}

	function loadAreaId($id){
		$sql = $this->db->query("SELECT * FROM tbl_area WHERE area_id = '$id' ORDER BY area_nombre ASC");
		return $sql->result_array();
	}

	function loadTypeEmployeeId($id){
		$sql = $this->db->query("SELECT * FROM tbl_tipo_empleado WHERE temp_id = '$id'");
		$tempName = '';

		foreach ($sql->result_array() as $row) {
			$tempName = $row['temp_valor'];
		}

		return $tempName;
	}

	function loadRepAreaId($id){
		$sql = $this->db->query("SELECT * FROM tbl_area WHERE area_id = '$id'");
		$areaName = '';

		foreach ($sql->result_array() as $row) {
			$areaName = $row['area_nombre'];
		}

		return $areaName;
	}

	function loadAreaIDS($id){
		$sql = $this->db->query("SELECT * FROM tbl_area WHERE area_id = '$id' LIMIT 1");
		return $sql->result_array();
	}

	function loadEmployee($id){
		$sql = $this->db->query("SELECT * FROM tbl_empleado WHERE emp_id = '$id' LIMIT 1");
		return $sql->result_array();
	}

	function numberEmployee(){
		$sql = $this->db->query("SELECT * FROM tbl_empleado");
		$number = $sql->num_rows();
		return $number;
	}

	function searchEmployeeByDate($from, $to){
		$sql = $this->db->query("SELECT * FROM tbl_empleado WHERE emp_registro BETWEEN '$from' AND '$to' ORDER BY emp_registro ASC");
		return $sql->result_array();	
	}

	function userExists($id){
		$sql = $this->db->query("SELECT * FROM tbl_usuario WHERE emp_id = '$id' LIMIT 1");
		if ($sql->num_rows() > 0){
			return 1;
		}else{
			return 0;
		}
	}
}