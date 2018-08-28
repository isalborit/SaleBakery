<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Employee extends CI_Controller{

	public function __construct(){
		parent::__construct();
		session_start();

		if (isset($_SESSION['usu_sv']) OR isset($_SESSION['adm_sv'])){
			/*$this->load->model('setting_model');*/
			$this->load->model('login_model');
			$this->load->model('employee_model');
		}else{
			header('Location:'.base_url());
		}
	}

	function index(){
		$this->header();
		$this->load->view('employee');
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

	public function loadOptionEmployee(){
		$responseT = $this->employee_model->loadTypeEmployee();
		$responseA = $this->employee_model->loadArea();
		$htmlT = '';
		$htmlA = '';

		foreach ($responseT as $rowT){
			$id = $rowT['temp_id'];
			$value = $rowT['temp_valor'];
			$htmlT.= '<option value="'.$id.'">'.$value.'</option>';
		}

		foreach ($responseA as $rowA){
			$id = $rowA['area_id'];
			$value = $rowA['area_nombre'];
			$htmlA.= '<option value="'.$id.'">'.$value.'</option>';
		}

		$array = array(0 => $htmlT,
					   1 => $htmlA);

		echo json_encode($array);
	}

	public function loadTypeEmployee(){
		$sql = $this->employee_model->loadTypeEmployeeTable();
		$html = '';
		foreach ($sql as $row){
			$type = "'type-employee'";
			$id = $row['temp_id'];
			$value = $row['temp_valor'];
			$html .= '<tr id="reg-'.$id.'">
						<td>'.$value.'</td>
						<td><a href="javascript:void(0);" onClick="editPage('.$id.', '.$type.');" class="btn btn-sm btn-default">Editar</a>
						</td>
					</tr>';
		}

		echo $html;
	}

	public function loadArea(){
		$sql = $this->employee_model->loadAreaTable();
		$html = '';
		foreach ($sql as $row){
			$type = "'area'";
			$id = $row['area_id'];
			$value = $row['area_nombre'];
			$html .= '<tr id="reg-'.$id.'">
						<td>'.$value.'</td>
						<td><a href="javascript:void(0);" onClick="editPage('.$id.', '.$type.');" class="btn btn-sm btn-default">Editar</a>
						</td>
					</tr>';
		}

		echo $html;
	}

	public function start(){
		$result = $this->employee_model->start();
		$output = array('data' => array());

		$type = "'employee'";
		
		foreach ($result as $row){
				$id = $row['emp_id'];
				$document = $row['emp_documento'];
				$name = $row['emp_nombre'];
				$lastname = $row['emp_apellido'];
				$phone = $row['emp_telefono'];
				$direction = $row['emp_direccion'];
				$sex = $row['emp_sexo'];
				$tempId = $row['temp_id'];
				$areaId = $row['area_id'];
				$status = $row['emp_estado'];
				$temp = $this->employee_model->loadTypeEmployeeId($tempId);

			$action ='
			<a href="javascript: void(0);" onClick="editPage('.$id.', '.$type.');"><span class="fa fa-edit"></span></a>
			<a href="javascript: void(0);" onClick="deletePage('.$id.', '.$type.');"><span class="fa fa-trash"></span></a>';

			$output['data'][] = array(
									$document,
									$name,
									$lastname,
									$phone,
									$direction,
									$temp,
									$action
								);
		}
		
		echo json_encode($output);
	}

	public function register(){
		$document = addslashes($_POST['document']);
		$name = addslashes($_POST['name']);
		$lastname = addslashes($_POST['lastname']);
		$direction = addslashes($_POST['direction']);
		$phone = addslashes($_POST['phone']);
		$sex = $_POST['sex'];
		$temp = $_POST['temp'];
		$area = $_POST['area'];
		$date = date('Y-m-d H:i:s');
		$status = 1 ;

		$data = array(
			'date' => $date,
			'document' => $document,
			'name' => $name,
			'lastname' => $lastname,
			'direction' => $direction,
			'phone' => $phone,
			'sex' => $sex,
			'temp' => $temp,
			'area' => $area,
			'status' => $status
		);

		$insert = $this->employee_model->register($data);
		
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

			$sql = $this->employee_model->loadEmployee($id);
			$responseT = $this->employee_model->loadTypeEmployee();
			$responseA = $this->employee_model->loadArea();

			foreach ($sql as $row) {
				$doc = $row['emp_documento'];
				$name = stripslashes($row['emp_nombre']);
				$lastname = stripslashes($row['emp_apellido']);
				$direction = stripslashes($row['emp_direccion']);
				$phone = $row['emp_telefono'];
				$sex = $row['emp_sexo'];
			}

			$htmlT = '';
			$htmlA = '';

			foreach ($responseT as $rowT){
				$idT = $rowT['temp_id'];
				$valueT = $rowT['temp_valor'];
				$htmlT.= '<option value="'.$idT.'">'.$valueT.'</option>';
			}

			foreach ($responseA as $rowA){
				$idA = $rowA['area_id'];
				$valueA = $rowA['area_nombre'];
				$htmlA.= '<option value="'.$idA.'">'.$valueA.'</option>';
			}

			$json = array(0 => $id,
						  1 => $doc,
						  2 => $name,
						  3 => $lastname,
						  4 => $direction,
						  5 => $phone,
						  6 => $sex,
						  7 => $htmlT,
						  8 => $htmlA
						);
			echo json_encode($json);
		}elseif($type == 'updateData'){
			$id = $_POST['id'];
			$document = addslashes($_POST['document']);
			$name = addslashes($_POST['name']);
			$lastname = addslashes($_POST['lastname']);
			$direction = addslashes($_POST['direction']);
			$phone = addslashes($_POST['phone']);
			$sex = $_POST['sex'];
			$temp = $_POST['temp'];
			$area = $_POST['area'];
			$date = date('Y-m-d H:i:s');

			$data = array(
				'id' => $id,
				'date' => $date,
				'document' => $document,
				'name' => $name,
				'lastname' => $lastname,
				'direction' => $direction,
				'phone' => $phone,
				'sex' => $sex,
				'temp' => $temp,
				'area' => $area
			);

			$update = $this->employee_model->update($data);
			
			if ($update > 0){
				$value = 'Ok';
			}else{
				$value = 'Null';
			}
			$array = array(0 => $value);
			echo json_encode($array);
		}
	}

	public function EditionTypeEmployee($type){
		if($type == 'bringData'){
			$id = $_POST['id'];

			$sql = $this->employee_model->loadTypeId($id);
			
			foreach ($sql as $row) {
				$name = $row['temp_valor'];
			}

			$json = array(0 => $id,
						  1 => $name
						);
			echo json_encode($json);
		}elseif($type == 'updateData'){
			$id = $_POST['id-type'];
			$name = addslashes($_POST['nameT']);

			$data = array(
				'id' => $id,
				'name' => $name
			);

			$update = $this->employee_model->updateTypeEmployee($data);
			
			if ($update > 0){
				$value = 'Ok';
				$html = $this->loadTypeEmployeeID($id, 'return');
			}else{
				$value = 'Null';
				$html = '';
			}
			$array = array(0 => $value,
						   1 => $html,
						   2 => $id);
			echo json_encode($array);
		}
	}

	public function EditionArea($type){
		if($type == 'bringData'){
			$id = $_POST['id'];

			$sql = $this->employee_model->loadAreaIDS($id);
			
			foreach ($sql as $row) {
				$name = $row['area_nombre'];
			}

			$json = array(0 => $id,
						  1 => $name
						);
			echo json_encode($json);
		}elseif($type == 'updateData'){
			$id = $_POST['id-area'];
			$name = addslashes($_POST['nameA']);

			$data = array(
				'id' => $id,
				'name' => $name
			);

			$update = $this->employee_model->updateArea($data);
			
			if ($update > 0){
				$value = 'Ok';
				$html = $this->loadAreaIDS($id, 'return');
			}else{
				$value = 'Null';
				$html = '';
			}
			$array = array(0 => $value,
						   1 => $html,
						   2 => $id);
			echo json_encode($array);
		}
	}

	public function registerType(){
		$name = $_POST['nameT'];
		$html = '';
		$data = array('name' => $name);
		$insert = $this->employee_model->registerType($data);

		if ($insert > 0){
			$response = 'Ok';
			$sql = $this->employee_model->loadTypeId($insert);

			foreach ($sql as $row){
				$type = "'type-employee'";
				$id = $row['temp_id'];
				$value = $row['temp_valor'];
				$html .= '<tr id="reg-'.$id.'">
							<td>'.$value.'</td>
							<td><a href="javascript:void(0);" onClick="editPage('.$id.', '.$type.');" class="btn btn-sm btn-default">Editar</a></td>	
						</tr>';
			}
		}else{
			$response = 'Null';
			$html = '';
		}
		$array = array(0 => $response,
					   1 => $html);
		echo json_encode($array);
	}

	public function loadTypeEmployeeID($id, $return){
		$sql = $this->employee_model->loadTypeEmployeeIDS($id);
		$html = '';
		foreach ($sql as $row){
			$type = "'type-employee'";
			$id = $row['temp_id'];
			$value = $row['temp_valor'];
			$html .= '<tr id="reg-'.$id.'">
						<td>'.$value.'</td>
						<td><a href="javascript:void(0);" onClick="editPage('.$id.', '.$type.');" class="btn btn-sm btn-default">Editar</a>
						</td>
					</tr>';
		}

		if($return == 'echo'){
			echo $html;
		}else if($return == 'return'){
			return $html;
		}
	}

	public function loadAreaIDS($id, $return){
		$sql = $this->employee_model->loadAreaIDS($id);
		$html = '';
		foreach ($sql as $row){
			$type = "'area'";
			$id = $row['area_id'];
			$value = $row['area_nombre'];
			$html .= '<tr id="reg-'.$id.'">
						<td>'.$value.'</td>
						<td><a href="javascript:void(0);" onClick="editPage('.$id.', '.$type.');" class="btn btn-sm btn-default">Editar</a>
						</td>
					</tr>';
		}

		if($return == 'echo'){
			echo $html;
		}else if($return == 'return'){
			return $html;
		}
	}

	public function registerArea(){
		$name = $_POST['nameA'];
		$html = '';
		$data = array('name' => $name);
		$insert = $this->employee_model->registerArea($data);

		if ($insert > 0){
			$response = 'Ok';
			$sql = $this->employee_model->loadAreaId($insert);

			foreach ($sql as $row){
				$type = "'area'";
				$id = $row['area_id'];
				$value = $row['area_nombre'];
				$html .= '<tr id="reg-'.$id.'">
							<td>'.$value.'</td>
							<td><a href="javascript:void(0);" onClick="editPage('.$id.', '.$type.');" class="btn btn-sm btn-default">Editar</a></td>
					</tr>';
			}
		}else{
			$response = 'Null';
			$html = '';
		}
		$array = array(0 => $response,
					   1 => $html);
		echo json_encode($array);
	}

	public function delete($id){
		$response = $this->employee_model->delete($id);
		echo $response;
	}
}