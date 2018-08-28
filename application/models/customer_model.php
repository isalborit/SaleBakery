<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Customer_model extends CI_Model{

	function __construct(){
		parent::__construct();
		$this->load->database();
	}

	function start(){
		$sql = $this->db->query("SELECT * FROM tbl_cliente WHERE cli_estado = 1 AND cli_id != 1");
		return $sql->result_array();
	}

	function register($data){
		$insert = $this->db->query("INSERT INTO tbl_cliente(cli_registro, cli_documento, cli_tipo_doc_sunat, cli_nombre, cli_direccion, cli_telefono, cli_estado) VALUES ('".$data['date']."', '".$data['document']."', '".$data['typeSunat']."', '".$data['name']."', '".$data['direction']."', '".$data['phone']."', '".$data['status']."')");

		if ($insert){
			$id = $this->db->insert_id();
		}else{
			$id = 0;
		}
		return $id;
	}

	function update($data){
		$update = $this->db->query("UPDATE tbl_cliente SET cli_documento = '".$data['document']."', cli_tipo_doc_sunat = '".$data['typeC']."',  cli_nombre = '".$data['name']."', cli_direccion = '".$data['direction']."', cli_telefono = '".$data['phone']."' WHERE cli_id = '".$data['id']."'");
		if ($update){
			$id = 1;
		}else{
			$id = 0;
		}
		return $id;
	}

	function loadCustomer($id){
		$sql = $this->db->query("SELECT * FROM tbl_cliente WHERE cli_id = '$id' LIMIT 1");
		return $sql->result_array();
	}

	function delete($id){
		$delete = $this->db->query("UPDATE tbl_cliente SET cli_estado = 0 WHERE cli_id = '$id'");
		if ($delete){
			return 1;
		}else{
			return 0;
		}
	}

	function numberCustomer(){
		$sql = $this->db->query("SELECT * FROM tbl_cliente");
		$number = $sql->num_rows();
		return $number;
	}
}