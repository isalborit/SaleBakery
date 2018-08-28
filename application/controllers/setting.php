<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setting extends CI_Controller {

	public function __construct(){
		parent::__construct();
		session_start();
		$this->load->model('setting_model');
	}

	public function index(){
		$this->load->view('setting');
	}

	public function updateSeries(){
		$s1 = addslashes($_POST['serie-1']);
		$s2 = addslashes($_POST['serie-2']);
		$s3 = addslashes($_POST['serie-3']);
		$s4 = addslashes($_POST['serie-4']);
		$s5 = addslashes($_POST['serie-5']);
		$s6 = addslashes($_POST['serie-6']);
		$data = array('s1' => $s1,
					  's2' => $s2,
					  's3' => $s3,
					  's4' => $s4,
					  's5' => $s5,
					  's6' => $s6);
		$sql = $this->setting_model->updateSeries($data);
		echo $sql;
	}

	public function updateDataMoney(){
		$d1 = addslashes($_POST['datos-1']);
		$d2 = addslashes($_POST['datos-2']);
		$d3 = addslashes($_POST['datos-3']);
		$d4 = addslashes($_POST['datos-4']);
		$d5 = addslashes($_POST['datos-5']);
		$d6 = date('Y-m-d H:i:s');
		$data = array('d1' => $d1,
					  'd2' => $d2,
					  'd3' => $d3,
					  'd4' => $d5);

		$data1 = array('d5' => $d6,
					  'd6' => $d4);
		$sql = $this->setting_model->updateDatosEconomicos($data);
		$sql2 = $this->setting_model->updateIgv($data1);
		echo $sql;
	}

	public function updatePassword(){
		$p1 = $this->encode5t(addslashes($_POST['pass-1']));
		$p2 = $this->encode5t(addslashes($_POST['pass-2']));
		$sql = $this->setting_model->checkPassword($p1);
		if($sql > 0){
			$sql2 = $this->setting_model->updatePassword($p2);
			echo $sql2;
		}else{
			echo 'password';
		}
	}

	public function encode5t($str){
	  	for($i=0; $i<5;$i++){
	    	$str=strrev(base64_encode($str));
	  	}
	  	return $str;
	}

	public function getSerie($type){
		if($type > 0){
			$sql = $this->setting_model->getSerie($type);
			if(strlen($sql) > 0){
				$sql2 = $this->setting_model->getNumberDocument($type, $sql);
				$number = $this->leftZero(8, $sql2);
				echo $sql.'-'.$number;
			}else{
				echo '';
			}
		}else{
			echo '';
		}
	}

	public function leftZero($lenght, $number){
		$nLen = strlen($number);
		$zeros = '';
		for($i=0; $i<($lenght-$nLen); $i++){
			$zeros = $zeros.'0';
		}
		return $zeros.$number;
	}
}