<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logout extends CI_Controller {

	function __construct(){
		parent::__construct();
		session_start();
	}

	public function index(){
		if (isset($_SESSION['usu_sv']) OR isset($_SESSION['adm_sv'])){
			session_destroy();
		}
		header('Location:'.base_url());
	}

}
