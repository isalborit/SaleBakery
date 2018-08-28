<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Provider extends CI_Controller{

	public function __construct(){
		parent::__construct();
		session_start();

		if (isset($_SESSION['usu_sv']) OR isset($_SESSION['adm_sv'])){
			/*$this->load->model('setting_model');*/
			$this->load->model('login_model');
			$this->load->model('provider_model');
		}else{
			header('Location:'.base_url());
		}
	}

	function index(){
		$this->header();
		$this->load->view('provider');
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

	public function leftZero($lenght, $number){
		$nLen = strlen($number);
		$zeros = '';
		for($i=0; $i<($lenght-$nLen); $i++){
			$zeros = $zeros.'0';
		}
		return $zeros.$number;
	}

	public function start(){
		$result = $this->provider_model->start();
		$output = array('data' => array());

		$type = "'provider'";
		
		foreach ($result as $row){
				$id = $row['prov_id'];
				$document = $row['prov_documento'];
				$name = $row['prov_nombre'];
				$phone = $row['prov_telefono'];
				$direction = $row['prov_direccion'];
				$email = $row['prov_correo'];
				$status = $row['prov_estado'];

			$action ='
			<a href="javascript: void(0);" onClick="editPage('.$id.', '.$type.');"><span class="fa fa-edit"></span></a>
			<a href="javascript: void(0);" onClick="deletePage('.$id.', '.$type.');"><span class="fa fa-trash"></span></a>';

			$output['data'][] = array(
									$document,
									$name,
									$phone,
									$direction,
									$email,
									$action
								);
		}
		
		echo json_encode($output);
	}

	public function register(){
		$document = addslashes($_POST['document']);
		$name = addslashes($_POST['name']);
		$direction = addslashes($_POST['direction']);
		$phone = addslashes($_POST['phone']);
		$email = addslashes($_POST['email']);
		$date = date('Y-m-d H:i:s');
		$status = 1 ;

		$data = array(
			'date' => $date,
			'document' => $document,
			'name' => $name,
			'direction' => $direction,
			'phone' => $phone,
			'email' => $email,
			'status' => $status
		);

		$insert = $this->provider_model->register($data);
		
		if ($insert > 0){
			$value = 'Ok';
		}else{
			$value = 'Null';
		}
		$array = array(0 => $value);
		echo json_encode($array);
	}

	public function Edition($type){
		if($type == 'bringData'){
			$id = $_POST['id'];

			$sql = $this->provider_model->loadProvider($id);
			
			foreach ($sql as $row) {
				$doc = $row['prov_documento'];
				$name = stripslashes($row['prov_nombre']);
				$direction = stripslashes($row['prov_direccion']);
				$phone = $row['prov_telefono'];
				$email = $row['prov_correo'];
			}

			$json = array(0 => $id,
						  1 => $doc,
						  2 => $name,
						  3 => $direction,
						  4 => $phone,
						  5 => $email
						);
			echo json_encode($json);
		}elseif($type == 'updateData'){
			$id = $_POST['id'];
			$document = addslashes($_POST['document']);
			$name = addslashes($_POST['name']);
			$direction = addslashes($_POST['direction']);
			$phone = addslashes($_POST['phone']);
			$email = addslashes($_POST['email']);

			$data = array(
				'id' => $id,
				'document' => $document,
				'name' => $name,
				'direction' => $direction,
				'phone' => $phone,
				'email' => $email
			);

			$update = $this->provider_model->update($data);
			
			if ($update > 0){
				$value = 'Ok';
			}else{
				$value = 'Null';
			}
			$array = array(0 => $value);
			echo json_encode($array);
		}
	}

	public function delete($id){
		$response = $this->provider_model->delete($id);
		echo $response;
	}
}