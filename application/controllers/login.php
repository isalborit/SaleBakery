<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller{

	function __construct(){
		parent::__construct();
		session_start();
		if (isset($_SESSION['usu_sv']) OR isset($_SESSION['adm_sv'])){
			header('Location:'.base_url('home'));
		}else{
			$this->load->model('login_model');
		}
	}

	function index(){
		$this->load->view('login');
	}

	function logeo(){
		$user = addslashes($_POST['user']);
		$pass = $this->encode5t(addslashes($_POST['pass']));

		$data = array(
			'user' => $user,
			'pass' => $pass
		);

		$result = $this->login_model->accessLogin($data);
		$response = json_decode($result);

		if ($response[0] == 1){
			if ($response[1] == 'USER'){
				$_SESSION['usu_sv'] = $user;
			}else if ($response[1] == 'ADMIN'){
				$_SESSION['adm_sv'] = $user;
			}
			$status = 'ok';
		}elseif($response[0] == 2){
			$status = 'null';
		}elseif($response[0] == 0){
			$status = 'login';
		}

		$array = array(0 => $status);
		echo json_encode($array);
	}

	public function encode5t($str){
	  	for($i=0; $i<5;$i++){
	    	$str=strrev(base64_encode($str));
	  	}
	  	return $str;
	}

	function decode5t($str){
	  	for($i=0; $i<5;$i++){
	    	$str=base64_decode(strrev($str));
	  	}
	  	return $str;
	}
}	
