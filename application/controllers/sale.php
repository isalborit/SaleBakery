<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sale extends CI_Controller{

	public function __construct(){
		parent::__construct();
		session_start();

		if (isset($_SESSION['usu_sv']) OR isset($_SESSION['adm_sv'])){
			$this->load->model('setting_model');
			$this->load->model('login_model');
			$this->load->model('product_model');
			$this->load->model('sale_model');
		}else{
			header('Location:'.base_url());
		}
	}

	function index(){
		$this->header();
		if (isset($_SESSION['usu_sv'])){
			$user = $_SESSION['usu_sv'];
		}else if (isset($_SESSION['adm_sv'])){
			$user = $_SESSION['adm_sv'];
		}
		$val['config_profile'] = $this->infoUser($user);
		$this->load->view('sale', $val);
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

		$val['simbol'] = 'S/.';

		$movements = $this->sale_model->loadMovements();
		$mov = '';
		foreach($movements as $row){
			$movId = $row['mov_id'];
			$movDet = stripslashes($row['mov_detalle']);
			$mov .= '<option value="'.$movId.'">'.$movDet.'</option>'; 
		}

		$val['optionMovement'] = $mov;
		$val['optionMethod'] = $this->loadMethods('return');

		$nextSaleF = $this->nextSale('F');
		$nSaleF = $this->leftZero(8, $nextSaleF);
		$lastF = 'F001-'.$nSaleF;

		$nextSaleB = $this->nextSale('B');
		$nSaleB = $this->leftZero(8, $nextSaleB);
		$lastB = 'B001-'.$nSaleB;

		$val['lastF'] = $lastF;
		$val['lastB'] = $lastB;

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

	/* Guardar la Venta */
	public function saveSale(){
		$doc =  $_POST['doc'];
		if($doc == 'F'){
			$typeSerie = 1;
		}else if($doc == 'B'){
			$typeSerie = 2;
		}else if($doc == 'A'){
			$typeSerie = 3;
		}
		$idPro = explode(',', $_POST['id']);
		$pricePro = explode(',', $_POST['price']);
		$unitPro = explode(',', $_POST['unit']);
		$descPro = explode(',', $_POST['desc']);
		$amountPro = explode(',', $_POST['amount']);
		$pbasePro = explode(',', $_POST['pbase']);
		
		$date = $_POST['date'].' '.date('H:i:s');
		
		$idCli = $_POST['cliId'];
		$rucCli = $_POST['cliRuc'];
		$tipSun = strlen($rucCli);
		$nameCli = $_POST['cliName'];
		$directionCli = $_POST['cliDirection'];

		$order = $_POST['order'];

		$mov = $_POST['mov'];
		$payed = $_POST['payed'];
		$received = $_POST['received'];
		$numberSale = $this->getSerie($typeSerie);

		$currency = 'PEN';

		$credit = $_POST['credit'];
		$method = $_POST['method'];
		
		/*$dGlobalWIGV = round(($dGlobal/1.18), 2);*/

		if ($order > 0){
			$this->sale_model->closeOrder($order);
		}

		$igv = $this->getCurrentIgv('function');
		if($doc == 'F'){
			$typeDoc = '1';
		}else if($doc == 'B'){
			$typeDoc = '2';
		}else if($doc == 'A'){
			$typeDoc = '0';
		}

		if($tipSun == 0){
			$tpSunat = 0;
		}else if($tipSun == 8){
			$tpSunat = 1;
		}else if($tipSun == 9){
			$tpSunat = 4;
		}else if($tipSun == 11){
			$tpSunat = 6;
		}

		$numberSale = explode('-', $numberSale);
		$serie = $numberSale[0];
		$nSale = $numberSale[1];

		$sqlInvoice = $this->sale_model->nSaleNumber($serie, $nSale);
		if($sqlInvoice == 0){
			$dataCli = array(
						'idCli' => $idCli,
						'rucCli' => $rucCli,
						'nameCli' => $nameCli,
						'directionCli' => $directionCli,
						'docSunat' => $tpSunat
					);
			if($idCli != 1){
				if($idCli > 0){
					$this->sale_model->editCustomer($dataCli);
				}else{
					if(strlen($rucCli) > 0 AND strlen($nameCli) > 0){
						$idCli = $this->sale_model->addCustomer($dataCli);
					}
				}
			}

			$count = count($idPro);
			$data = array(
				'nSale' => $nSale,
				'date' => $date,
				'cli' => $idCli,
				'mov' => $mov,
				'payed' => $payed,
				'igv' => $igv,
				'currency' => $currency,
				'received' => $received,
				'doc' => $doc,
				'serie' => $serie,
				'typeDoc' => $typeDoc,
				'credit' => $credit,
				'method' => $method,
				'order' => $order
			);
			$insertSale = $this->sale_model->saveSale($data);
			if($insertSale > 0){
				for($i=0; $i<$count; $i++){
					$id = $idPro[$i];
					$price = $pricePro[$i];
					$priceWIGV = round(($pricePro[$i]/1.18), 2);
					$unit = $unitPro[$i];
					$desc = $descPro[$i];
					$descWIGV = round(($descPro[$i]/1.18), 2);
					$amount = $amountPro[$i];
					$ref = $pbasePro[$i];

					$dataDetail = array(
						'idSale' => $insertSale,
						'idPro' => $id,
						'pricePro' => $price,
						'priceProWIGV' => $priceWIGV,
						'unitPro' => $unit,
						'descPro' => $desc,
						'descProWIGV' => $descWIGV,
						'amountPro' => $amount,
						'ref' => $ref
					);
					$this->sale_model->saveDetailSale($dataDetail);
					$this->stock($id, $amount);
					/*$this->sale_model->actualizarPreciosV($id, $price);*/
				}

				$saleTotal = $this->saleTotal($insertSale);
				$saleTotal = json_decode($saleTotal);
				$saleSinIgv = $saleTotal[0];
				$saleDescuento = $saleTotal[1];
				$saleIgv = $saleTotal[2];
				$saleNeto = $saleTotal[3];
				$this->sale_model->updateSaleResults($insertSale, $saleSinIgv, $saleDescuento, $saleIgv, $saleNeto);

				$status = 'ok';
				$result = $insertSale;
			}else{
				$status = 'sale'; //ERROR -> NO SE REGISTRO LA VENTA O FACTURA
				$result = 0;
			}
		}else{
			$status = 'saleNumber'; //ERROR -> EL NUMERO DE COMPROBANTE YA EXISTE
			$result = 0;
		}
		$array = array(0 => $status,
					   1 => $result);
		echo json_encode($array);
	}

	public function delete($id){
		$verify = $this->sale_model->verifySaleId($id);
		if ($verify > 0){
			$dataProduct = $this->sale_model->idProduct($id);

			foreach ($dataProduct as $rowP) {
				$idPro = $rowP['producto_id'];
				$amountPro = $rowP['detalle_cantidad'];

				$stockReal = $this->sale_model->stockReal1($idPro);
				$amountTotal = $stockReal + $amountPro;

				$actualize = $this->sale_model->updateStock($idPro, $amountTotal);
			}
			$response = $this->sale_model->delete($id);
			echo $response;
		}else{
			echo 0;
		}
	}

	public function getCurrentIgv($type){
		$sql = $this->sale_model->getCurrentIgv();
		$igv = 0;
		foreach($sql as $row){
			$igv = $row['igv_porcentaje'];
		}

		if($type == 'function'){
			return $igv;	
		}else if($type == 'ajax'){
			echo $igv;
		}
	}

	public function saleTotal($id){
		$saleSinIgv = 0; 
		$saleDiscount = 0; 
		$saleIgv = 0;  
		$saleNeto = 0;
		$igvSql = $this->sale_model->igvInvoice($id);
		$igvSql = json_decode($igvSql);
		$igvP = $igvSql[0];
		$sql = $this->sale_model->loadRecordSaleDetail($id);
		$n = 0;
		foreach($sql as $row){
			$dPrice = $row['detalle_precio'];
			$dAmount = $row['detalle_cantidad'];
			$dDisc = $row['detalle_descuento'];
			if($row['detalle_impuesto'] == 1){
				$subtotal = ($dPrice*$dAmount)/(1+$igvP);
				$discount = $dDisc/(1+$igvP);
				$igv = (($dPrice*$dAmount)-$dDisc)-((($dPrice*$dAmount)-$dDisc)/(1+$igvP));
				$neto = ($dPrice*$dAmount)-$dDisc;

				$saleSinIgv = $saleSinIgv+$subtotal;
				$saleDiscount = $saleDiscount+$discount;
				$saleIgv = $saleIgv+$igv;
				$saleNeto = $saleNeto+$neto;
			}else{
				$subtotal = ($dPrice*$dAmount);
				$discount = $dDisc;
				$igv = 0;
				$neto = ($dPrice*$dAmount)-$dDisc;

				$saleSinIgv = $saleSinIgv+$subtotal;
				$saleDiscount = $saleDiscount+$discount;
				$saleIgv = $saleIgv+$igv;
				$saleNeto = $saleNeto+$neto;
			}
			$n++;
		}

		$array = array(0 => $saleSinIgv,
					   1 => $saleDiscount,
					   2 => $saleIgv,
					   3 => $saleNeto);
		return json_encode($array); 
	}

	public function loadProductSale(){
		$value = $_GET['term'];
		$sql = $this->sale_model->loadProductSale($value);
		$array = array();

		foreach ($sql as $row){
			$status = 1;
			$id = $row['prod_id'];
			$name = stripslashes($row['prod_nombre']);
			$unit = $row['prod_unidad'];
			$price = $row['prod_precio_vp1'];
			$ref = $row['prod_referencia'];

			$array[] = array('value' => $name,
							 'prod1' => $status,
							 'prod2' => $id,
							 'prod3' => $name,
							 'prod4' => $unit,
							 'prod5' => $price,
							 'prod6' => $ref);
		}

		echo json_encode($array);
	}

	public function loadCustomerNameSale(){
		$value = $_GET['term'];
		$sql = $this->sale_model->loadCustomerNameSale($value);
		$array = array();

		foreach ($sql as $row) {
			$id = $row['cli_id'];
			$document = $row['cli_documento'];
			$name = $row['cli_nombre'];
			$direction = $row['cli_direccion'];

			$array[] = array('value' => $name,
							 'cli1' => $id,
							 'cli2' => $document,
							 'cli3' => $name,
							 'cli4' => $direction);
		}

		echo json_encode($array);
	}

	public function loadCustomerDocSale(){
		$value = addslashes($_POST['value']);
		$sql = $this->sale_model->loadCustomerDocSale($value);

		$status = 0;
		$id = '';
		$document = '';
		$name = '';
		$direction = '';

		foreach ($sql as $row){
			$status = 1;
			$id = $row['cli_id'];
			$document = $row['cli_documento'];
			$name = $row['cli_nombre'];
			$direction = $row['cli_direccion'];
		}

		$array = array(0 => $id,
					   1 => $document,
					   2 => $name,
					   3 => $direction,
					   4 => $status);

		echo json_encode($array);
	}

	public function loadMethods($type){
		$sql = $this->sale_model->loadMethods();
		$option = '';
		foreach($sql as $row){
			$id = $row['contado_id']; 
			$name = stripslashes($row['contado_descripcion']); 
			$option .= '<option value="'.$id.'">'.$name.'</option>';
		}
		if($type == 'echo'){
			echo $option;
		}else if($type == 'return'){
			return $option;
		}
	}

	public function loadCredits(){
		$sql = $this->sale_model->loadCredits();
		$option = '';
		foreach($sql as $row){
			$id = $row['credito_id']; 
			$name = stripslashes($row['credito_descripcion']); 
			$option .= '<option value="'.$id.'">'.$name.'</option>';
		}
		echo $option;
	}

	public function getSerie($type){
		$sql = $this->setting_model->getSerie($type);
		if(strlen($sql) > 0){
			$sql2 = $this->setting_model->getNumberDocument($type, $sql);
			$number = $this->leftZero(8, $sql2);
			return $sql.'-'.$number;
		}else{
			return '';
		}
	}

	public function nextSale($doc){
		$id = $this->sale_model->maxIdSale($doc);
		$nextSale = (int)$id + 1;
		return $nextSale;
	}

	public function leftZero($lenght, $number){
		$nLen = strlen($number);
		$zeros = '';
		for($i=0; $i<($lenght-$nLen); $i++){
			$zeros = $zeros.'0';
		}
		return $zeros.$number;
	}

	public function infoUser($user){
		$data = array('user' => $user);
		$sql = $this->sale_model->loadProfile($data);

		foreach ($sql as $row) {
			$nameUsu = $row['emp_nombre'];
			$lastnameUsu = $row['emp_apellido'];
			$profileUsu = $row['usu_perfil'];
			$rolUsu = $row['usu_rol'];
		}
		if ($rolUsu == 'ADMIN') {
			$rol = "ADMINISTRADOR";
		}else if($rolUsu){
			$rol = "USUARIO";
		}

		$path = 'public/profiles/'.$profileUsu;

		if(file_exists($path) AND strlen($profileUsu) > 0){
			$img = '<img src="'.$path.'" width="100%">';
		}else{
			$img = '<img src="public/resources/sin_foto.jpg" width="100%">';
		}

		$html = '<table class="table table-bordered table-condensed table-striped">
	    			<tr>
	    				<td rowspan="3" width="90px">'.$img.'</td>
	    				<th class="text-primary">'.$rol.'</th>
	    			</tr>
	    			<tr>
	    				<th class="text-danger">'.$nameUsu.'</th>
	    			</tr>
	    			<tr>
	    				<th class="text-danger">'.$lastnameUsu.'</th>
	    			</tr>
	    		</table>';

	    return $html;
	}

	/* Cargar Informacion en Modal */
	public function loadRecordSaleToday(){
		$type = "'sale'";
		$recordSale = $this->sale_model->loadRecordSaleToday(); 
		$count = 0;
		$data = '';
		$dataTotal = 0;
		$igvPercent = 0;
		foreach($recordSale as $row){
			$count = $count + 1;
			$id = $row['venta_id'];
			$igvPercent = $row['igv_porcentaje'];
			$saleDate = date('d/m/Y', strtotime($row['venta_fecha']));
			$saleType = $this->saleType($row['venta_tipo']);
			$salePayed = $this->salePayed($row['venta_cancelada'], $row['venta_id']);
			$saleSerie = $row['venta_serie'];
			$saleNumber = $row['venta_numero'];
			$cusSql = $this->sale_model->loadCustomerId($row['cliente_id']);
			$cusName = '';
			$sqlTypePay = $this->sale_model->loadMovementsId($row['movimiento_id']);
			$nameTypePay = '';
			foreach($sqlTypePay as $rowType){
				$nameTypePay = stripslashes($rowType['mov_detalle']);
			}

			$saleCurrency = $row['venta_moneda'];

			foreach($cusSql as $rowCus) {
				$cusName = stripslashes($rowCus['cli_nombre']);
			}

			$saleTotal = $this->saleTotal($id);
			$saleTotal = json_decode($saleTotal);

			$saleSinIgv = $saleTotal[0];
			$saleDescuento = $saleTotal[1];
			$saleIgv = $saleTotal[2];
			$saleNeto = $saleTotal[3];
			$dataTotal = $dataTotal+$saleNeto;

			if($row['venta_tipo'] == 'A'){
				$button = '<div class="btn-group" role="group" id="ddb">
						    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						      Accion
						      <span class="caret"></span>
						    </button>
						    <ul class="dropdown-menu">
						      <li><a href="javascript:void(0);" onClick="seeDetailSale('.$id.');" data-toggle="tooltip" title="Ver Detalle"><span class="glyphicon glyphicon-list"></span> Ver detalle</a>
						      </li>
						      <li><a href="javascript:void(0);" onClick="deletePage('.$id.', '.$type.');" data-toggle="tooltip" title="Ver Detalle"><span class="glyphicon glyphicon-remove"></span> Eliminar</a>
						      </li>
						    </ul>
					  	</div>';
			}else{
				$button = '<div class="btn-group" role="group" id="ddb">
							    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							      Accion
							      <span class="caret"></span>
							    </button>
							    <ul class="dropdown-menu">
							      <li><a href="javascript:void(0);" onClick="seeDetailSale('.$id.');" data-toggle="tooltip" title="Ver Detalle"><span class="glyphicon glyphicon-list"></span> Ver detalle</a>
							      </li>
							      <li><a href="javascript:void(0);" onClick="printTicket('.$id.');" data-toggle="tooltip" title="Imprimir"><span class="glyphicon glyphicon-print"></span> Imprimir</a>
							      </li>
							    </ul>
						  	</div>';
			}

			$data .= '<tr id="reg-'.$id.'">
						<td>'.$saleType.'</td>
						<td>'.$saleSerie.'-'.$saleNumber.'</td>
						<td>'.$saleDate.'</td>
						<td>'.$cusName.'</td>
						<td>'.$nameTypePay.'</td>
						<td>'.$saleCurrency.'</td>
						<td align="right">'.number_format($saleSinIgv, 2).'</td>
						<td align="right">'.number_format($saleDescuento, 2).'</td>
						<td align="right">'.number_format($saleIgv, 2).'</td>
						<td align="right">'.number_format($saleNeto, 2).'</td>
						<td>
							'.$button.'
						</td>
					  </tr>';
		}

		if($count > 0){
			$files = $data;
		}else{
			$files = '<tr><td colspan="12">No se encontraron resultados.</td></tr>';
		}

		$array = array(0 => $files,
			           1 => 'S/.'.number_format($dataTotal, 2),
			           2 => date('Y-m-d'));
		echo json_encode($array);
	}

	public function searchSaleByDate(){
		$type = "'sale'";
		$from = $_POST['sale-from'].' 00:00:00'; 
		$to = $_POST['sale-to'].' 23:59:59';
		$doc = $_POST['sale-doc'];
		$dataSearch = array(
			'from' => $from,
			'to' => $to,
			'doc' => $doc
		);
		if(strlen($_POST['sale-from']) > 0 AND strlen($_POST['sale-to']) > 0 AND strlen($_POST['sale-doc']) > 0){
			$recordSale = $this->sale_model->searchSaleByDate($dataSearch, 1);
		}else if(strlen($_POST['sale-from']) > 0 AND strlen($_POST['sale-to']) > 0 AND strlen($_POST['sale-doc']) == 0){
			$recordSale = $this->sale_model->searchSaleByDate($dataSearch, 2);
		}else if(strlen($_POST['sale-from']) == 0 AND strlen($_POST['sale-to']) == 0 AND strlen($_POST['sale-doc']) > 0){
			$recordSale = $this->sale_model->searchSaleByDate($dataSearch, 3);
		}else{
			$recordSale = $this->sale_model->searchSaleByDate($dataSearch, 2);
		}

		$count = 0;
		$data = '';
		$dataTotal = 0;
		$igvPercent = 0;
		foreach($recordSale as $row){
			$count = $count + 1;
			$id = $row['venta_id'];
			$igvPercent = $row['igv_porcentaje'];
			$saleDate = date('d/m/Y', strtotime($row['venta_fecha']));
			$saleType = $this->saleType($row['venta_tipo']);
			$salePayed = $this->salePayed($row['venta_cancelada'], $row['venta_id']);
			$saleSerie = $row['venta_serie'];
			$saleNumber = $row['venta_numero'];
			$cusSql = $this->sale_model->loadCustomerId($row['cliente_id']);
			$cusName = '';
			$sqlTypePay = $this->sale_model->loadMovementsId($row['movimiento_id']);
			$nameTypePay = '';
			foreach($sqlTypePay as $rowType){
				$nameTypePay = stripslashes($rowType['mov_detalle']);
			}

			$saleCurrency = $row['venta_moneda'];

			foreach($cusSql as $rowCus) {
				$cusName = stripslashes($rowCus['cli_nombre']);
			}

			$saleTotal = $this->saleTotal($id);
			$saleTotal = json_decode($saleTotal);

			$saleSinIgv = $saleTotal[0];
			$saleDescuento = $saleTotal[1];
			$saleIgv = $saleTotal[2];
			$saleNeto = $saleTotal[3];
			$dataTotal = $dataTotal+$saleNeto;

			if($row['venta_tipo'] == 'A'){
				$button = '<div class="btn-group" role="group" id="ddb">
						    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						      Accion
						      <span class="caret"></span>
						    </button>
						    <ul class="dropdown-menu">
						      <li><a href="javascript:void(0);" onClick="seeDetailSale('.$id.');" data-toggle="tooltip" title="Ver Detalle"><span class="glyphicon glyphicon-list"></span> Ver detalle</a>
						      </li>
						      <li><a href="javascript:void(0);" onClick="deletePage('.$id.', '.$type.');" data-toggle="tooltip" title="Ver Detalle"><span class="glyphicon glyphicon-remove"></span> Eliminar</a>
						      </li>
						    </ul>
					  	</div>';
			}else{
				$button = '<div class="btn-group" role="group" id="ddb">
							    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							      Accion
							      <span class="caret"></span>
							    </button>
							    <ul class="dropdown-menu">
							      <li><a href="javascript:void(0);" onClick="seeDetailSale('.$id.');" data-toggle="tooltip" title="Ver Detalle"><span class="glyphicon glyphicon-list"></span> Ver detalle</a>
							      </li>
							      <li><a href="javascript:void(0);" onClick="printTicket('.$id.');" data-toggle="tooltip" title="Imprimir"><span class="glyphicon glyphicon-print"></span> Imprimir</a>
							      </li>
							    </ul>
						  	</div>';
			}

			$data .= '<tr id="reg-'.$id.'">
						<td>'.$saleType.'</td>
						<td>'.$saleSerie.'-'.$saleNumber.'</td>
						<td>'.$saleDate.'</td>
						<td>'.$cusName.'</td>
						<td>'.$nameTypePay.'</td>
						<td>'.$saleCurrency.'</td>
						<td align="right">'.number_format($saleSinIgv, 2).'</td>
						<td align="right">'.number_format($saleDescuento, 2).'</td>
						<td align="right">'.number_format($saleIgv, 2).'</td>
						<td align="right">'.number_format($saleNeto, 2).'</td>
						<td>
							'.$button.'
						</td>
					  </tr>';
		}

		if($count > 0){
			$files = $data;
		}else{
			$files = '<tr><td colspan="13">No se encontraron resultados.</td></tr>';
		}

		$array = array(0 => $files,
			           1 => 'S/.'.number_format($dataTotal, 2));
		echo json_encode($array);
	}

	public function loadRecordSaleDetail(){
		$id = $_POST['id'];
		$recordSale = $this->sale_model->loadRecordSaleId($id);
		$data = '';
		$igvPercent = 0;
		foreach($recordSale as $row){
			$idSale = $row['venta_id'];
			$saleType = $this->saleType($row['venta_tipo']);
			$sale = $row['venta_serie'].'-'.$row['venta_numero'];
			$date = date('d/m/Y H:i:s', strtotime($row['venta_fecha']));
			$cusId = $row['cliente_id']; 
			$typeId = $row['movimiento_id']; 
			$currency = $row['venta_moneda'];
			$sqlCustomer = $this->sale_model->loadCustomerId($cusId);
			$placaCustomer = '';
			$customer = '';

			if ($currency == 'PEN') {
				$simbol = 'S/.';
			}

			foreach($sqlCustomer as $rowCustomer){
				$rucCustomer = stripslashes($rowCustomer['cli_documento']);
				$customer = stripslashes($rowCustomer['cli_nombre']);
			}

			$sqlType = $this->sale_model->loadMovementsId($typeId);
			$nameType = '';
			foreach($sqlType as $rowType){
				$nameType = stripslashes($rowType['mov_detalle']);
			}

			$igvPercent = $row['igv_porcentaje'];
			
			$data = '<table width="100%">
		        		<tr>
		        			<th>'.$saleType.': </th>
		        			<td>'.$sale.'</td>
		        			<th style="text-align:right;">Tipo: </th>
		        			<td>'.$nameType.'</td>
		        			<th style="text-align:right;">Fecha: </th>
		        			<td>'.$date.'</td>
		        			<th style="text-align:right;">RUC: </th>
		        			<td>'.$rucCustomer.'</td>
		        			<th style="text-align:right;">Nombre: </th>
		        			<td>'.$customer.'</td>
		        			<th></th>
		        			<td></td>
		        		</tr>
		        	</table><br>';
		}

		$recordSaleDetail = $this->sale_model->loadRecordSaleDetail($id);
		$data .= '<table class="table table-condensed table-bordered table-hover">
					<thead>
						<tr>
							<th width="120">Codigo</th>
							<th>Producto</th>
							<th width="60">Unidad</th>
							<th>Precio</th>
							<th width="90">Cantidad</th>
							<th>Descuento</th>
							<th>Subtotal</th>
						</tr>
					</thead>
					<body>';
		$brutoFooter = 0;
		$igvFooter = 0;
		$descFooter = 0;
		$productCode = '';
		$productName = '';
		foreach($recordSaleDetail as $row){
			$productId = $row['producto_id'];
			$dataProduct = $this->sale_model->loadProductId($productId);
			foreach($dataProduct as $rowProduct){
				$productCode = $rowProduct['prod_codigo'];
				$productName = $rowProduct['prod_nombre'];
			}
			$price = $row['detalle_precio'];
			$amount = $row['detalle_cantidad'];
			$desc = $row['detalle_descuento'];
			$unit = $row['detalle_unidad'];
			$subtotal = ($price*$amount)-$desc;
			$data .= '<tr>
						<td>'.$productCode.'</td>
						<td>'.$productName.'</td>
						<td align="center">'.$unit.'</td>
						<td align="right">'.number_format($price, 2).'</td>
						<td align="right">'.number_format($amount, 2).'</td>
						<td align="right">'.number_format($desc, 2).'</td>
						<td align="right">'.number_format($subtotal, 2).'</td>
					  </tr>';
		}

		$saleTotal = $this->saleTotal($id);
		$saleTotal = json_decode($saleTotal);

		$saleSinIgv = $saleTotal[0];
		$saleDescuento = $saleTotal[1];
		$saleIgv = $saleTotal[2];
		$saleNeto = $saleTotal[3];

		$data .= '<tr>
				  	<th colspan="6" style="text-align:right;">Bruto: </th>
				  	<td align="right"><label class="text-primary">'.$simbol.' '.number_format($saleSinIgv, 2).'</label></td>
				  </tr>
				  <tr>
				  	<th colspan="6" style="text-align:right;">Descuento: </th>
				  	<td align="right"><label class="text-primary">'.$simbol.' '.number_format($saleDescuento, 2).'</label></td>
				  </tr>
				  <tr>
				  	<th colspan="6" style="text-align:right;">IGV '.($igvPercent*100).'%: </th>
				  	<td align="right"><label class="text-primary">'.$simbol.' '.number_format($saleIgv, 2).'</label></td>
				  </tr>
				  <tr>
				  	<th colspan="6" style="text-align:right;">Total: </th>
				  	<td align="right"><label class="text-primary">'.$simbol.' '.number_format($saleNeto, 2).'</label></td>
				  </tr>
				  </tbody></table>';
		echo $data;
	}

	/* Imprimir Ticket */
	public function printTicket($id){
		require_once('public/phpqrcode/qrlib.php');
		require_once('public/tcpdf/config/lang/eng.php');
		require_once('public/tcpdf/tcpdf.php');
		$nDetail = $this->sale_model->loadRecordSaleNDetail($id);
		$space = $nDetail*11;
		$layout = array(69, 200+$space);
		$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false); 
		$pdf->setPrintHeader(false); //no imprime la cabecera ni la linea 
		$pdf->setPrintFooter(false); //no imprime el footer ni la linea
		$pdf->AddPage('P', $layout);
		$pdf->SetCreator('AlvaSoft');
		$pdf->SetAuthor('AlvaSoft');
		$pdf->SetTitle('Sistema de Ferreteria 2017/'.date('Y'));
		$pdf->SetAutoPageBreak(true, 5);
		$pdf->SetMargins(2, 2, 2, true);
		//$pdf->SetAutoPageBreak(false);

		$server = $this->config->item('web_company');
		$sql = $this->sale_model->loadRecordSaleId($id);
		$saleType = '';
		$saleSerie = '';
		$saleNumber = '';
		$saleDate = '';
		$saleUser = '';
		$saleCustomer = '';

		/*$saleHash = '';*/ //SUNAT

		$saleSubtotalFinal = 0;
		$saleDiscountFinal = 0;
		$saleIGVFinal = 0;
		$saleNetoFinal = 0;
		$saleCurrency1 = '';
		$salePercent = 0;

		foreach($sql as $row ){
			$saleType = $row['venta_tipo'];
			$saleSerie = $row['venta_serie'];
			$saleNumber = $row['venta_numero'];
			$saleDate = $row['venta_fecha'];
			$saleUser = $row['usuario_id'];
			$saleCustomer = $row['cliente_id'];
			/*$saleHash = $row['venta_sunat_hash'];*/ //SUNAT

			$saleSubtotalFinal = $row['venta_subtotal'];
			$saleDiscountFinal = $row['venta_descuento'];
			$saleIGVFinal = $row['venta_igv'];
			$saleNetoFinal = $row['venta_neto'];

			$saleCurrency1 = $row['venta_moneda'];
			if ($saleCurrency1 == 'PEN'){
				$saleCurrency = 'Nuevos Soles';
			}else{
				$saleCurrency = 'Dolares Americanos';
			}
			$salePercent = round($row['igv_porcentaje']*100, 0);
		}

		$sqlUser = $this->sale_model->loadUser($saleUser);
		$userName = '';
		foreach($sqlUser as $rowUser){
			$userName = $rowUser['emp_nombre'].' '.$rowUser['emp_apellido'];
		}

		$customerRUC = '';
		$customerRUCStr = '';
		$customerName = '';
		$customerDirection = '';
		$customerDoc = '';
		$typeDoc = '';
		$sqlCustomer = $this->sale_model->loadCustomerId($saleCustomer);
		if($saleCustomer != 1){
			foreach($sqlCustomer as $rowCustomer){
				$customerRUC = $rowCustomer['cli_documento'];
				$customerRUCStr = $rowCustomer['cli_documento'];
				$customerName = $rowCustomer['cli_nombre'];
				$customerDirection = $rowCustomer['cli_direccion'];
				$customerDoc = $rowCustomer['cli_tipo_doc_sunat'];
			}
		}else{
			$customerRUC = '11111111';
			$customerRUCStr = '';
		}

		if($saleType == 'B'){
			// $typeInvoice = 'BOLETA ELECTRONICA';
			$typeInvoiceStyle = '';
			$typeDoc2 = 'Nombre';
		}else if($saleType == 'F'){
			// $typeInvoice = 'FACTURA ELECTRONICA';
			$typeDoc2 = 'Razon social';
		}

		$typeInvoice = 'TICKET DE VENTA';

		if($customerDoc == 0){
			$typeDoc = '';
		}else if($customerDoc == 1){
			$typeDoc = 'DNI';
		}else if($customerDoc == 4){
			$typeDoc = 'CARNET EXT.';
		}else if($customerDoc == 6){
			$typeDoc = 'RUC';
		}

		$html = '<table cellpadding="2px" width="187px" style="font-size:22px;">
					<tr>
						<td>
							<table cellpadding="1px">
								<tr>
									<td width="187px" align="center" style="font-size: 30px;"><b>'.$this->config->item('name_company').'</b></td>
								</tr>
								<tr>
									<td width="187px" align="center">RUC: '.$this->config->item('ruc_company').'</td>
								</tr>
								<tr>
									<td width="187px" align="center">'.$this->config->item('direction_company').'</td>
								</tr>
								<tr>
									<td width="187px" align="center">Telefono: '.$this->config->item('phone_company').'</td>
								</tr>
								<tr>
									<td align="center">
										----------------------------------------------------------------------
									</td>
								</tr>
								<tr>
									<td align="center" style="font-size:27px;"><b>'.$typeInvoice.'</b></td>
								</tr>
								<tr>
									<td align="center" style="font-size:25px;">'.$saleSerie.'-'.$saleNumber.'</td>
								</tr>
								<tr>
									<td align="center">Usuario: '.$userName.'</td>
								</tr>
								<tr>
									<td align="center">'.date('d/m/Y H:i:s', strtotime($saleDate)).'</td>
								</tr>
								<tr>
									<td align="center">
										----------------------------------------------------------------------
									</td>
								</tr>
								<tr>
									<td>
										<b>'.$typeDoc.': </b>'.$customerRUCStr.'
									</td>
								</tr>
								<tr>
									<td>
										<b>'.$typeDoc2.': </b>'.$customerName.'
									</td>
								</tr>
								<tr>
									<td>
										<b>Direccion: </b>'.$customerDirection.'
									</td>
								</tr>
								<tr>
									<td align="center">
										----------------------------------------------------------------------
									</td>
								</tr>
								<tr>
									<td align="center">
										<table width="183px;">
											<tr>
												<td align="left" style="width:30px;"><i><b>Item</b></i></td>
												<td colspan="3" align="left" style="width:153px;"><i><b>Descripcion de producto</b></i></td>
											</tr>
											<tr>
												<td align="right"><i><b>Cant.</b></i></td>
												<td align="right"><i><b>Prec.</b></i></td>
												<td align="right"><i><b>Desc.</b></i></td>
												<td align="right"><i><b>Subtotal</b></i></td>
											</tr>
											<tr>
												<td align="center" colspan="4">
													----------------------------------------------------------------------
												</td>
											</tr>';

										$sqlDetail = $this->sale_model->loadRecordSaleDetail($id);
										$sqlNDetail = $this->sale_model->loadRecordSaleNDetail($id);
										$detailItem = 0;
										foreach($sqlDetail as $rowDetail){
											$detailItem++;
											$detailProduct = '';
											$detailProductId = $rowDetail['producto_id'];
											$sqlProduct = $this->sale_model->getDataProduct($detailProductId);
											foreach($sqlProduct as $rowProduct){
												$detailProduct = $rowProduct['prod_nombre'];
											}
											$detailPrice = $rowDetail['detalle_precio'];
											$detailAmount = $rowDetail['detalle_cantidad'];
											$detailDiscount = $rowDetail['detalle_descuento'];
											$detailSubtotal = ($detailPrice*$detailAmount)-$detailDiscount;
										
											$html .= '<tr>
														<td align="left">'.$this->leftZero(3, $detailItem).'</td>
														<td colspan="3" align="left">'.$detailProduct.'</td>
													  </tr>
													  <tr>
														<td align="right">'.number_format($detailAmount, 2).'</td>
														<td align="right">'.number_format($detailPrice, 2).'</td>
														<td align="right">'.number_format($detailDiscount, 2).'</td>
														<td align="right">'.number_format($detailSubtotal, 2).'</td>
													  </tr>
													  <tr><td colspan="4"></td></tr>';
										}	

							//GENERACION QR//////////////////////////////////////////////
							$qr_ruc = $this->config->item('ruc_company');
							if($saleType == 'F'){
								$qr_tipo_comp = '01';
								$qr_tipo_doc_adq = '6';
								$footerStr = 'Factura';
							}else if($saleType == 'B'){
								$qr_tipo_comp = '03';
								$qr_tipo_doc_adq = '1';
								$footerStr = 'Boleta';
							}
							$qr_serie = $saleSerie;
							$qr_numero = $saleNumber;
							$qr_total_igv = number_format($saleIGVFinal, 2, '.', '');
							$qr_total_neto = number_format($saleNetoFinal, 2, '.', '');
							$qr_fecha = date('Y-m-d', strtotime($saleDate));
							$qr_dni_ruc = $customerRUC;
							$qr_path = 'public/qr_generated/'.$qr_ruc.'-'.$qr_tipo_comp.'-'.$qr_serie.'-'.$qr_numero.'.png';
							$valQR = $qr_ruc.'|'.$qr_tipo_comp.'|'.$qr_serie.'|'.$qr_numero.'|'.$qr_total_igv.'|'.$qr_total_neto.'|'.$qr_fecha.'|'.$qr_tipo_doc_adq.'|'.$qr_dni_ruc.'|';
							QRcode::png($valQR, $qr_path, 'Q', 4, 2);
							/////////////////////////////////////////////////////////////

							$html .= '</table>
									</td>
								</tr>
								<tr>
									<td align="center">
										----------------------------------------------------------------------
									</td>
								</tr>
								<tr>
									<td align="center">
										<table>
											<tr>
												<td width="74px" align="left">
													<img src="'.$qr_path.'" width="70px">
												</td>
												<td width="110px">
													<table>
														<tr>
															<td align="left" width="64px"><b>Op. gravadas: </b></td>
															<td align="right" width="45px;">'.number_format($saleSubtotalFinal, 2).'</td>
														</tr>
														<tr>
															<td align="left"><b>Descuentos: </b></td>
															<td align="right">'.number_format($saleDiscountFinal, 2).'</td>
														</tr>
														<tr>
															<td align="left"><b>Total IGV '.$salePercent.'%: </b></td>
															<td align="right">'.number_format($saleIGVFinal, 2).'</td>
														</tr>
														<tr>
															<td align="left"><b>Total a pagar: </b></td>
															<td align="right">'.number_format($saleNetoFinal, 2).'</td>
														</tr>
													</table>
												</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td align="center">
										----------------------------------------------------------------------
									</td>
								</tr>
								<tr>
									<td align="center">
										<table>
											<tr>
												<td align="left" width="183px"><b>Son: </b>'.ucfirst(mb_strtolower($this->convertNumberToWord($saleNetoFinal))).' '.$saleCurrency.'</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td align="center">
										----------------------------------------------------------------------
									</td>
								</tr>
								<tr>
									<td align="center">
										<br>
										<br>
										...Gracias por su visita...
										<br>
										<br>
										.
									</td>
								</tr>
							</table>
						</td>
					</tr>
				 </table>';

	   	$pdf->SetXY(0.5, 2);
	  	$pdf->writeHTML($html, true, false, true, true, 'L');

	  	$pdf->IncludeJS('print();');
	  	$pdf->Output('Ticket.pdf', 'I');

	}

	public function saleType($type){
		switch($type){
			case 'B':
				$str = 'Boleta';
			break;
			
			case 'F':
				$str = 'Factura';
			break;

			case 'A':
				$str = 'Alternativo';
			break;
		}
		return $str;
	}

	public function salePayed($type, $id){
		if(isset($_SESSION['adm_sv'])){
			switch($type){
				case '0':
					$str = '<select class="form-control" onChange="changeStatusPayedSale('.$id.', this.value);">
								<option value="1">Si</option>
								<option value="0" selected>No</option>
							</select>';
				break;
				
				case '1':
					$str = '<select class="form-control" onChange="changeStatusPayedSale('.$id.', this.value);">
								<option value="1" selected>Si</option>
								<option value="0">No</option>
							</select>';
				break;
			}
			return $str;
		}else if(isset($_SESSION['usu_sv'])){
			switch($type){
				case '0':
					$str = '<font class="text-danger">No</font>';
				break;
				
				case '1':
					$str = '<font class="text-success">Si</font>';
				break;
			}
			return $str;
		}
	}

	public function convertNumberToWord($xcifra){
	    $xarray = array(0 => "Cero",
	        1 => "UN", "DOS", "TRES", "CUATRO", "CINCO", "SEIS", "SIETE", "OCHO", "NUEVE",
	        "DIEZ", "ONCE", "DOCE", "TRECE", "CATORCE", "QUINCE", "DIECISEIS", "DIECISIETE", "DIECIOCHO", "DIECINUEVE",
	        "VEINTI", 30 => "TREINTA", 40 => "CUARENTA", 50 => "CINCUENTA", 60 => "SESENTA", 70 => "SETENTA", 80 => "OCHENTA", 90 => "NOVENTA",
	        100 => "CIENTO", 200 => "DOSCIENTOS", 300 => "TRESCIENTOS", 400 => "CUATROCIENTOS", 500 => "QUINIENTOS", 600 => "SEISCIENTOS", 700 => "SETECIENTOS", 800 => "OCHOCIENTOS", 900 => "NOVECIENTOS"
	    );
	//
	    $xcifra = trim($xcifra);
	    $xlength = strlen($xcifra);
	    $xpos_punto = strpos($xcifra, ".");
	    $xaux_int = $xcifra;
	    $xdecimales = "00";
	    if (!($xpos_punto === false)) {
	        if ($xpos_punto == 0) {
	            $xcifra = "0" . $xcifra;
	            $xpos_punto = strpos($xcifra, ".");
	        }
	        $xaux_int = substr($xcifra, 0, $xpos_punto); // obtengo el entero de la cifra a covertir
	        $xdecimales = substr($xcifra . "00", $xpos_punto + 1, 2); // obtengo los valores decimales
	    }
	 
	    $XAUX = str_pad($xaux_int, 18, " ", STR_PAD_LEFT); // ajusto la longitud de la cifra, para que sea divisible por centenas de miles (grupos de 6)
	    $xcadena = "";
	    for ($xz = 0; $xz < 3; $xz++) {
	        $xaux = substr($XAUX, $xz * 6, 6);
	        $xi = 0;
	        $xlimite = 6; // inicializo el contador de centenas xi y establezco el límite a 6 dígitos en la parte entera
	        $xexit = true; // bandera para controlar el ciclo del While
	        while ($xexit) {
	            if ($xi == $xlimite) { // si ya llegó al límite máximo de enteros
	                break; // termina el ciclo
	            }
	 
	            $x3digitos = ($xlimite - $xi) * -1; // comienzo con los tres primeros digitos de la cifra, comenzando por la izquierda
	            $xaux = substr($xaux, $x3digitos, abs($x3digitos)); // obtengo la centena (los tres dígitos)
	            for ($xy = 1; $xy < 4; $xy++) { // ciclo para revisar centenas, decenas y unidades, en ese orden
	                switch ($xy) {
	                    case 1: // checa las centenas
	                        if (substr($xaux, 0, 3) < 100) { // si el grupo de tres dígitos es menor a una centena ( < 99) no hace nada y pasa a revisar las decenas
	                             
	                        } else {
	                            $key = (int) substr($xaux, 0, 3);
	                            if (TRUE === array_key_exists($key, $xarray)){  // busco si la centena es número redondo (100, 200, 300, 400, etc..)
	                                $xseek = $xarray[$key];
	                                $xsub = $this->subfijo($xaux); // devuelve el subfijo correspondiente (Millón, Millones, Mil o nada)
	                                if (substr($xaux, 0, 3) == 100)
	                                    $xcadena = " " . $xcadena . " CIEN " . $xsub;
	                                else
	                                    $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
	                                $xy = 3; // la centena fue redonda, entonces termino el ciclo del for y ya no reviso decenas ni unidades
	                            }
	                            else { // entra aquí si la centena no fue numero redondo (101, 253, 120, 980, etc.)
	                                $key = (int) substr($xaux, 0, 1) * 100;
	                                $xseek = $xarray[$key]; // toma el primer caracter de la centena y lo multiplica por cien y lo busca en el arreglo (para que busque 100,200,300, etc)
	                                $xcadena = " " . $xcadena . " " . $xseek;
	                            } // ENDIF ($xseek)
	                        } // ENDIF (substr($xaux, 0, 3) < 100)
	                        break;
	                    case 2: // checa las decenas (con la misma lógica que las centenas)
	                        if (substr($xaux, 1, 2) < 10) {
	                             
	                        } else {
	                            $key = (int) substr($xaux, 1, 2);
	                            if (TRUE === array_key_exists($key, $xarray)) {
	                                $xseek = $xarray[$key];
	                                $xsub = $this->subfijo($xaux);
	                                if (substr($xaux, 1, 2) == 20)
	                                    $xcadena = " " . $xcadena . " VEINTE " . $xsub;
	                                else
	                                    $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
	                                $xy = 3;
	                            }
	                            else {
	                                $key = (int) substr($xaux, 1, 1) * 10;
	                                $xseek = $xarray[$key];
	                                if (20 == substr($xaux, 1, 1) * 10)
	                                    $xcadena = " " . $xcadena . " " . $xseek;
	                                else
	                                    $xcadena = " " . $xcadena . " " . $xseek . " Y ";
	                            } // ENDIF ($xseek)
	                        } // ENDIF (substr($xaux, 1, 2) < 10)
	                        break;
	                    case 3: // checa las unidades
	                        if (substr($xaux, 2, 1) < 1) { // si la unidad es cero, ya no hace nada
	                             
	                        } else {
	                            $key = (int) substr($xaux, 2, 1);
	                            $xseek = $xarray[$key]; // obtengo directamente el valor de la unidad (del uno al nueve)
	                            $xsub = $this->subfijo($xaux);
	                            $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
	                        } // ENDIF (substr($xaux, 2, 1) < 1)
	                        break;
	                } // END SWITCH
	            } // END FOR
	            $xi = $xi + 3;
	        } // ENDDO
	 
	        if (substr(trim($xcadena), -5, 5) == "ILLON") // si la cadena obtenida termina en MILLON o BILLON, entonces le agrega al final la conjuncion DE
	            $xcadena.= " DE";
	 
	        if (substr(trim($xcadena), -7, 7) == "ILLONES") // si la cadena obtenida en MILLONES o BILLONES, entoncea le agrega al final la conjuncion DE
	            $xcadena.= " DE";
	 
	        // ----------- esta línea la puedes cambiar de acuerdo a tus necesidades o a tu país -------
	        if (trim($xaux) != "") {
	            switch ($xz) {
	                case 0:
	                    if (trim(substr($XAUX, $xz * 6, 6)) == "1")
	                        $xcadena.= "UN BILLON ";
	                    else
	                        $xcadena.= " BILLONES ";
	                    break;
	                case 1:
	                    if (trim(substr($XAUX, $xz * 6, 6)) == "1")
	                        $xcadena.= "UN MILLON ";
	                    else
	                        $xcadena.= " MILLONES ";
	                    break;
	                case 2:
	                    if ($xcifra < 1) {
	                        $xcadena = "CERO CON $xdecimales/100 ";
	                    }
	                    if ($xcifra >= 1 && $xcifra < 2) {
	                        $xcadena = "UNO CON $xdecimales/100 ";
	                    }
	                    if ($xcifra >= 2) {
	                        $xcadena.= " CON $xdecimales/100 "; //
	                    }
	                    break;
	            } // endswitch ($xz)
	        } // ENDIF (trim($xaux) != "")
	        // ------------------      en este caso, para México se usa esta leyenda     ----------------
	        $xcadena = str_replace("VEINTI ", "VEINTI", $xcadena); // quito el espacio para el VEINTI, para que quede: VEINTICUATRO, VEINTIUN, VEINTIDOS, etc
	        $xcadena = str_replace("  ", " ", $xcadena); // quito espacios dobles
	        $xcadena = str_replace("UN UN", "UN", $xcadena); // quito la duplicidad
	        $xcadena = str_replace("  ", " ", $xcadena); // quito espacios dobles
	        $xcadena = str_replace("BILLON DE MILLONES", "BILLON DE", $xcadena); // corrigo la leyenda
	        $xcadena = str_replace("BILLONES DE MILLONES", "BILLONES DE", $xcadena); // corrigo la leyenda
	        $xcadena = str_replace("DE UN", "UN", $xcadena); // corrigo la leyenda
	    } // ENDFOR ($xz)
	    return trim($xcadena);
	}

	function subfijo($xx){ // esta función regresa un subfijo para la cifra
	    $xx = trim($xx);
	    $xstrlen = strlen($xx);
	    if ($xstrlen == 1 || $xstrlen == 2 || $xstrlen == 3)
	        $xsub = "";
	    //
	    if ($xstrlen == 4 || $xstrlen == 5 || $xstrlen == 6)
	        $xsub = "MIL";
	    //
	    return $xsub;
	}

	function stock($id, $cantidad){
		$sqlPro = $this->sale_model->stockReal($id);
		foreach ($sqlPro as $row){
			$stockReal = $row['prod_stock_real'];
		}

		$stockFinal = $stockReal - $cantidad;

		$f5 = $this->sale_model->actualizarStock($id, $stockFinal);
	}

	public function productCategoryVisor($id){
		$sql = $this->sale_model->getProductSale($id);
		$html =
		'<label><h3>Lista de Productos</h3></label>
		<table class="table table-bordered table-condensed">
			<thead style="background-color: #d8d8d8;">
				<tr>
					<th>Descripción</th>
					<th width="120">Precio Minimo</th>
					<th width="120">Precio x Mayor</th>
				</tr>
			</thead>
			<tbody>';
		foreach ($sql as $row){
			$idValue = $row['prod_id'];
			$value = $row['prod_nombre'];
			$p1 = $row['prod_precio_vp1'];
			$p2 = $row['prod_precio_vp2'];
			$html .=
			'<tr onClick="clickTable('.$idValue.');">
				<td><h5>'.$value.'</h5></td>
				<td>'.$p1.'</td>
				<td>'.$p2.'</td>
			</tr>';
		}
		$html .= '</tbody>
			</table>';
		$array = array(0 => $html);
		echo json_encode($array);
	}

	public function loadProductSaleVisor($id){
		$sql = $this->sale_model->loadProductSaleID($id);
		$array = array();

		foreach ($sql as $row){
			$status = 1;
			$id = $row['prod_id'];
			$name = stripslashes($row['prod_nombre']);
			$unit = $row['prod_unidad'];
			$price = $row['prod_precio_vp1'];
			$ref = $row['prod_referencia'];

			$array = array(0 => $status,
							 1 => $id,
							 2 => $name,
							 3 => $unit,
							 4 => $price,
							 5 => $ref);
		}

		echo json_encode($array);
	}

	// public function loadCategoryVisor(){
	// 	$sql = $this->sale_model->getCategory();
	// 	$visor = '';
	// 	foreach ($sql as $row){
	// 		$id = $row['categ_id'];
	// 		$name = $row['categ_valor'];

	// 		$visor .=
	// 		'<div class="col-md-3" onClick="selectCategory('.$id.');">
	// 			<div class="panel panel-info">
	// 			  <div class="panel-body">'.$name.'</div>
	// 			</div>
	// 		</div>';
	// 	}
	// 	$array = array(0 => $visor);
	// 	echo json_encode($array);
	// }
	public function startCategory(){
		$sql = $this->sale_model->getCategory();
		$html = '';

		foreach ($sql as $row){
			$id = $row['categ_id'];
			$name = $row['categ_valor'];
			$img = $row['categ_img'];

			$html .=
			'<div class="col-md-4">
				<img src="'.base_url('public').'/files/categorias/'.$img.'" onclick="selectCategory('.$id.');">
			</div>';
		}
		$array = array(0 => $html);
		echo json_encode($array);
	}
}