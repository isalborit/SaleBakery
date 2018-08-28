<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Provider_model extends CI_Model{

	function __construct(){
		parent::__construct();
		$this->load->database();
	}

	function start(){
		$sql = $this->db->query("SELECT * FROM tbl_proveedor WHERE prov_estado = 1");
		return $sql->result_array();
	}

	function register($data){
		$insert = $this->db->query("INSERT INTO tbl_proveedor(prov_registro, prov_documento, prov_nombre, prov_direccion, prov_telefono, prov_correo, prov_estado) VALUES ('".$data['date']."', '".$data['document']."', '".$data['name']."', '".$data['direction']."', '".$data['phone']."', '".$data['email']."', '".$data['status']."')");

		if ($insert){
			$id = $this->db->insert_id();
		}else{
			$id = 0;
		}
		return $id;
	}

	function update($data){
		$update = $this->db->query("UPDATE tbl_proveedor SET prov_documento = '".$data['document']."', prov_nombre = '".$data['name']."', prov_direccion = '".$data['direction']."', prov_telefono = '".$data['phone']."', prov_correo = '".$data['email']."' WHERE prov_id = '".$data['id']."'");
		if ($update){
			$id = 1;
		}else{
			$id = 0;
		}
		return $id;
	}

	function loadProvider($id){
		$sql = $this->db->query("SELECT * FROM tbl_proveedor WHERE prov_id = '$id' LIMIT 1");
		return $sql->result_array();
	}

	function delete($id){
		$delete = $this->db->query("UPDATE tbl_proveedor SET prov_estado = 0 WHERE prov_id = '$id'");
		if ($delete){
			return 1;
		}else{
			return 0;
		}
	}

	function numberProvider(){
		$sql = $this->db->query("SELECT * FROM tbl_proveedor");
		$number = $sql->num_rows();
		return $number;
	}
}