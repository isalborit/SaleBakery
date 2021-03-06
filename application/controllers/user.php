<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller{

	public function __construct(){
		parent::__construct();
		session_start();

		if (isset($_SESSION['usu_sv']) OR isset($_SESSION['adm_sv'])){
			/*$this->load->model('setting_model');*/
			$this->load->model('login_model');
			$this->load->model('user_model');
		}else{
			header('Location:'.base_url());
		}
	}

	function index(){
		$this->header();
		$this->load->view('user');
		$this->load->view('footer');
	}

	function header(){
		if (isset($_SESSION['usu_sv'])){
			$userName = $this->userName($_SESSION['usu_sv']);
			$access = $this->accessAll($_SESSION['usu_sv']);
			$accessResult = json_decode($access);
			$allStatus = false;
		}else if (isset($_SESSION['adm_sv'])){
			$userName = $this->userName($_SESSION['adm_sv']);
			$access = 0;
			$allStatus = true;
		}

		if ($allStatus == false){
			$accessCliente = $accessResult[0];
			$accessEmpleado = $accessResult[1];
			$accessProducto = $accessResult[2];
			$accessProveedor = $accessResult[3];
			$accessCompras = $accessResult[4];
			$accessUsuario = $accessResult[5];
			$accessVentas = $accessResult[6];
			$accessReporte = $accessResult[7];
			$accessNota = $accessResult[8];
		}else{
			$accessCliente = 1;
			$accessEmpleado = 1;
			$accessProducto = 1;
			$accessProveedor = 1;
			$accessCompras = 1;
			$accessUsuario = 1;
			$accessVentas = 1;
			$accessReporte = 1;
			$accessNota = 1;
		}

		$val['userName'] = $userName;
		$val['accessCliente'] = $accessCliente;
		$val['accessEmpleado'] = $accessEmpleado;
		$val['accessProducto'] = $accessProducto;
		$val['accessProveedor'] = $accessProveedor;
		$val['accessCompras'] = $accessCompras;
		$val['accessUsuario'] = $accessUsuario;
		$val['accessVentas'] = $accessVentas;
		$val['accessReporte'] = $accessReporte;
		$val['accessNota'] = $accessNota;

		$serie = $this->getSeries();
        $serie = json_decode($serie);

        $datos = $this->getDatos();
        $datos = json_decode($datos);

        $digv = $this->getIgv();
        $digv = json_decode($digv);

        $val['serie1'] = $serie[0];
        $val['serie2'] = $serie[1];
        $val['serie3'] = $serie[2];
        $val['serie4'] = $serie[3];
        $val['serie5'] = $serie[4];

        $val['datos1'] = $datos[0];
        $val['datos2'] = $datos[1];
        $val['datos3'] = $datos[2];
        $val['datos4'] = $digv[0];
        $val['datos5'] = $datos[3];

		$val['menu_settings'] = 'Configuración';
		$val['menu_close_system'] = 'Cerrar Sesión';
		$this->load->view('header', $val);
	}

	function userName($user){
		$data = array('user' => $user);
		$userData = $this->login_model->userData($data);
		$name = '';
		$lastName = '';

		foreach ($userData as $row){
			$name = $row['emp_nombre'];
			$lastName = $row['emp_apellido'];
			$firstname = explode(" ", $name);
		}
		$user = $firstname[0].' '.$lastName;
		return $user;
	}

	function accessAll($user){
		$data = array('user' => $user);
		$userAccess = $this->login_model->accessAll($data);
		$response = json_decode($userAccess);

		$array = array(0 => $response[0],
					   1 => $response[1],
					   2 => $response[2],
					   3 => $response[3],
					   4 => $response[4],
					   5 => $response[5],
					   6 => $response[6],
					   7 => $response[7],
					   8 => $response[8]);

		return json_encode($array);
	}

	public function getSeries(){
		$sql = $this->login_model->getSeries();
		$serie1 = '';
		$serie2 = '';
		$serie3 = '';
		$serie4 = '';
		$serie5 = '';
		foreach($sql as $row){
			$serie1 = stripcslashes($row['serie_venta_factura']);
			$serie2 = stripcslashes($row['serie_venta_boleta']);
			$serie3 = stripcslashes($row['serie_venta_alternativa']);
			$serie4 = stripcslashes($row['serie_nota_credito_factura']);
			$serie5 = stripcslashes($row['serie_nota_credito_boleta']);
		}
		$array = array(0 => $serie1,
					   1 => $serie2,
					   2 => $serie3,
					   3 => $serie4,
					   4 => $serie5);
		return json_encode($array);
	}

	public function getDatos(){
		$sql = $this->login_model->getDatos();
		$datos1 = '';
		$datos2 = '';
		$datos3 = '';
		$datos4 = '';
		foreach($sql as $row){
			$datos1 = $row['datos_gasto_mensual'];
			$datos2 = $row['datos_impuesto_renta'];
			$datos3 = $row['datos_porcentaje_gastos'];
			$datos4 = $row['datos_tipo_cambio'];
		}
		$array = array(0 => $datos1,
					   1 => $datos2,
					   2 => $datos3,
					   3 => $datos4);
		return json_encode($array);
	}

	public function getIgv(){
		$sql = $this->login_model->getIgv();
		$datos1 = '';
		foreach($sql as $row){
			$datos1 = $row['igv_porcentaje'];
		}
		$array = array(0 => $datos1);
		return json_encode($array);
	}

	public function loadEmployeeDoc(){
		$dni = addslashes($_POST['value']);
		$search = $this->user_model->checkDni($dni);
		$status = 0;
		$id = '';
		$name = '';

		if ($search > 0){
			$status = 1;
			$sql = $this->user_model->loadEmployeeDni($dni);

			foreach ($sql as $row){
				$id = $row['emp_id'];
				$name = $row['emp_nombre'].' '.$row['emp_apellido'];
			}
		}

		$array = array(0 => $id,
					   1 => $name,
					   2 => $status);

		echo json_encode($array);
	}

	public function searchData(){
		$id = addslashes($_POST['value']);
		$data = array('user' => $id);
		$userData = $this->login_model->userData($data);
		$name = '';
		foreach($userData as $row){
			$name = stripslashes($row['emp_nombre']).' '.stripslashes($row['emp_apellido']);
		}
		echo $name;
	}

	public function leftZero($lenght, $number){
		$nLen = strlen($number);
		$zeros = '';
		for($i=0; $i<($lenght-$nLen); $i++){
			$zeros = $zeros.'0';
		}
		return $zeros.$number;
	}

	public function start(){
		$result = $this->user_model->start();
		$output = array('data' => array());

		$type = "'user'";
		
		foreach ($result as $row){
				$id = $row['usu_id'];
				$empId = $row['emp_id'];
				$name = $row['usu_nombre'];
				$password = $row['usu_clave'];

				$encyPass = $this->decode5t($password);

				$emp = $this->user_model->nameEmployee($empId);

			$action ='<center><a href="javascript: void(0);" onClick="deletePage('.$id.', '.$type.');"><span class="fa fa-trash"></span></a></center>';

			$output['data'][] = array(
									$emp,
									$name,
									$encyPass,
									$password,
									$action
								);
		}
		
		echo json_encode($output);
	}

	public function register(){
		$id = addslashes($_POST['id']);
		$name = addslashes($_POST['name']);
		$pass = addslashes($_POST['pass']);
		$role = addslashes($_POST['role']);
		$pass = $this->encode5t($pass);

		if ($role == 'ADMIN'){
			$accessSale = 1;
			$accessShopping = 1;
			$accessProduct = 1;
			$accessCustomer = 1;
			$accessEmployee = 1;
			$accessUser = 1;
			$accessProvider = 1;
			$accessAccount = 1;
			$accessNote = 1;
			$accessOrder = 1;
			$accessReport = 1;
		}else{
			$accessSale = (isset($_POST['user_role_sale'])) ? 1 : 0;
			$accessShopping = (isset($_POST['user_role_shopping'])) ? 1 : 0;
			$accessProduct = (isset($_POST['user_role_product'])) ? 1 : 0;
			$accessCustomer = (isset($_POST['user_role_customer'])) ? 1 : 0;
			$accessEmployee = (isset($_POST['user_role_employee'])) ? 1 : 0;
			$accessUser = (isset($_POST['user_role_user'])) ? 1 : 0;
			$accessProvider = (isset($_POST['user_role_provider'])) ? 1 : 0;
			$accessAccount = (isset($_POST['user_role_account'])) ? 1 : 0;
			$accessNote = (isset($_POST['user_role_note'])) ? 1 : 0;
			$accessOrder = (isset($_POST['user_role_order'])) ? 1 : 0;
			$accessReport = (isset($_POST['user_role_report'])) ? 1 : 0;
		}

		$date = date('Y-m-d H:i:s');
		$status = 1 ;

		$checkUser = $this->user_model->checkUser($id);

		if ($checkUser > 0){
			$value = 'Exists';
		}else{
			$data = array(
				'date' => $date,
				'id' => $id,
				'name' => $name,
				'pass' => $pass,
				'role' => $role,
				'status' => $status
			);

			$insert = $this->user_model->register($data);
			
			if ($insert > 0){
				$roles = array(
					'Aid' => $insert,
					'Adate' => $date,
					'Asale' => $accessSale,
					'Ashopping' => $accessShopping,
					'Aproduct' => $accessProduct,
					'Acustomer' => $accessCustomer,
					'Aprovider' => $accessProvider,
					'Aemployee' => $accessEmployee,
					'Auser' => $accessUser,
					'Aorder' => $accessOrder,
					'Anote' => $accessNote,
					'Aaccount' => $accessAccount,
					'Areport' => $accessReport
				);
				$accesos = $this->user_model->registerAccess($roles);
				if ($accesos > 0){
					$value = 'Ok';
				}else{
					$value = 'Null';
				}
				$value = 'Ok';
			}else{
				$value = 'Null';
			}
		}

		
		$array = array(0 => $value);
		echo json_encode($array);
	}

	public function delete($id){
		$response = $this->user_model->delete($id);
		echo $response;
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