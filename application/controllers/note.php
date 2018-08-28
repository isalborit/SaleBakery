<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Note extends CI_Controller{

	public function __construct(){
		parent::__construct();
		session_start();

		if (isset($_SESSION['usu_sv']) OR isset($_SESSION['adm_sv'])){
			/*$this->load->model('setting_model');*/
			$this->load->model('login_model');
			$this->load->model('note_model');
		}else{
			header('Location:'.base_url());
		}
	}

	function index(){
		$this->header();
		$this->load->view('note');
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

	public function maxNote(){
		$id = $this->note_model->numberNote();
		$number = $id + 1;
		return $number;
	}

	public function start(){
		$result = $this->note_model->start();
		$output = array('data' => array());

		$type = "'note'";
		
		foreach ($result as $row){
				$id = $row['nota_id'];
				$date = date('d/m/Y', strtotime($row['nota_fecha']));
				$noteType = $row['nota_tipo'];
				$serie = $row['nota_serie'];
				$number = $row['nota_numero'];
				$sale = $row['venta_id'];

				if ($noteType == 'FC'){
					$typeText = 'Credito Factura';
				}else if ($noteType == 'BC'){
					$typeText = 'Credito Boleta';
				}

				$dataSale = $this->note_model->numberSale($sale);

				foreach ($dataSale as $rowSale){
					$serieSale = $rowSale['venta_serie'];
					$numberSale = $rowSale['venta_numero'];
					$subtotalSale = $rowSale['venta_subtotal'];
					$descSale = $rowSale['venta_descuento'];
					$igvSale = $rowSale['venta_igv'];
					$totalSale = $rowSale['venta_neto'];

					$serieNumberSale = $serieSale.'-'.$numberSale;
				}

				$serieNumber = $serie.'-'.$number;

			$action ='
			<center><a href="javascript: void(0);" onClick="deletePage('.$id.', '.$type.');"><span class="fa fa-trash"></span></a></center>';

			$output['data'][] = array(
									$typeText,
									$date,
									$serieNumber,
									$serieNumberSale,
									$subtotalSale,
									$descSale,
									$igvSale,
									$totalSale,
									$action
								);
		}
		
		echo json_encode($output);
	}

	function register(){
		$type = $_POST['note-type'];
		$date = $_POST['note-date'];
		$hour = date('H:i:s');
		$dateNote = $date.' '.$hour;
		$dateHour = date('Y-m-d H:i:s', strtotime($dateNote));
		$se_nu = $_POST['note-serie'];
		$doc = $_POST['note-document'];
		$desc = $_POST['note-desc'];

		$serieNumber = explode("-", $se_nu);
		$document = explode("-", $doc);

		$docSerie = $document[0];
		$docNumber = $document[1];

		$serie = $serieNumber[0];
		$number = $serieNumber[1];

		$typeDocument = 3;

		$sqlFact = $this->note_model->verifyDoc($docSerie, $docNumber);
		if ($sqlFact > 0){
			$sale = $this->note_model->saleCode($docSerie, $docNumber);
			$data = array(
				'date' => $dateHour,
				'type' => $type,
				'serie' => $serie,
				'number' => $number,
				'sale' => $sale,
				'desc' => $desc,
				'typeDoc' => $typeDocument
			);

			$insert = $this->note_model->register($data);
			if ($insert > 0){
				$value = 'Ok';
				$updateSale = $this->note_model->updateSale($sale);
				$dataProduct = $this->note_model->idProduct($sale);

				foreach ($dataProduct as $rowP) {
					$idPro = $rowP['producto_id'];
					$amountPro = $rowP['detalle_cantidad'];

					$stockReal = $this->note_model->stockReal($idPro);
					$amountTotal = $stockReal + $amountPro;

					$actualize = $this->note_model->updateStock($idPro, $amountTotal);
				}
			}else{
				$value = 'Null';
			}
		}else{
			$value = 'Error';
		}

		$array = array(0 => $value);
		echo json_encode($array);
	}
}