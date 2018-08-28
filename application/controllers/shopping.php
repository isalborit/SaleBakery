<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Shopping extends CI_Controller{

	public function __construct(){
		parent::__construct();
		session_start();

		if (isset($_SESSION['usu_sv']) OR isset($_SESSION['adm_sv'])){
			/*$this->load->model('setting_model');*/
			$this->load->model('login_model');
			$this->load->model('shopping_model');
		}else{
			header('Location:'.base_url());
		}
	}

	function index(){
		$this->header();
		$this->load->view('shopping');
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

		$movements = $this->shopping_model->loadMovements();
		$mov = '';
		foreach ($movements as $row){
			$movId = $row['mov_id'];
			$movDet = stripslashes($row['mov_detalle']);
			$mov .= '<option value="'.$movId.'">'.$movDet.'</option>'; 
		}


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

		$val['optionType'] = $mov;
		$val['optionShape'] = $this->loadMethods('return');

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

	public function maxShopping(){
		$id = $this->shopping_model->numberShopping();
		$number = $id + 1;
		return $number;
	}

	public function loadCredits(){
		$sql = $this->shopping_model->loadCredits();
		$option = '';
		foreach($sql as $row){
			$id = $row['credito_id']; 
			$name = stripslashes($row['credito_descripcion']); 
			$option .= '<option value="'.$id.'">'.$name.'</option>';
		}
		echo $option;
	}

	public function loadMethods($type){
		$sql = $this->shopping_model->loadMethods();
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

	public function getCurrentIgv($type){
		$sql = $this->shopping_model->getCurrentIgv();
		$igv = 0;
		foreach($sql as $row){
			$igv = $row['igv_porcentaje'];
		}

		if($type == 'return'){
			return $igv;	
		}else if($type == 'echo'){
			echo $igv;
		}
	}

	public function shoppingTotal($id){
		$shoppingSinIgv = 0; 
		$shoppingDiscount = 0; 
		$shoppingIgv = 0;  
		$shoppingNeto = 0;
		$igvP = $this->shopping_model->igvInvoice($id);
		$igvF = 1 + $igvP;

		$shoppingSinIgv = $this->shopping_model->CalculateSinIGV($id, $igvF);
		$shoppingDescuento = 0;/*$this->shopping_model->CalculateDescuento($insertShopping);*/
		$shoppingNeto = $this->shopping_model->CalculateConIGV($id);
		$shoppingIgv = $shoppingNeto - $shoppingSinIgv;

		$array = array(0 => $shoppingSinIgv,
					   1 => $shoppingDiscount,
					   2 => $shoppingIgv,
					   3 => $shoppingNeto
					);
		return json_encode($array); 
	}

	public function saveShopping(){
		$idPro = explode(',', $_POST['id']);
		$pricePro = explode(',', $_POST['price']);
		$unitPro = explode(',', $_POST['unit']);
		$amountPro = explode(',', $_POST['amount']);
		$transportePro = explode(',', $_POST['transp']);
		$priceProMin = explode(',', $_POST['price1']);
		$priceProMay = explode(',', $_POST['price2']);
		
		$date = $_POST['date'].' '.date('H:i:s');
		$idProv = $_POST['idProv'];
		$type = $_POST['type'];
		$serie = $_POST['serie'];
		$number = $_POST['number'];
		$mov = $_POST['mov'];
		$credit = $_POST['credit'];
		$method = $_POST['method'];
		$currency = $_POST['currency'];
		$igv = $this->getCurrentIgv('return');

		$count = count($idPro);

		if ($currency == 'DOL'){
			$change = $this->shopping_model->currencyChange();
		}else{
			$change = 1;
		}
		
		$data = array(
			'serie' => $serie,
			'number' => $number,
			'type' => $type,
			'date' => $date,
			'currency' => $currency,
			'prove' => $idProv,
			'mov' => $mov,
			'credit' => $credit,
			'method' => $method,
			'igv' => $igv
		);

		$verify = $this->shopping_model->checkSerieNumber($serie, $number, $idProv);
		if($verify == 0){
			$insertShopping = $this->shopping_model->saveShopping($data);
			if($insertShopping > 0){
				for($i=0; $i<$count; $i++){
					$id = $idPro[$i];
					$price_igv = number_format(($pricePro[$i] * $change),4);
					$price = number_format(($price_igv / (1 + $igv)),4);
					$unit = $unitPro[$i];
					$amount = $amountPro[$i];
					$transporte = $transportePro[$i] * $change;
					$priceMin = $priceProMin[$i];
					$priceMax = $priceProMay[$i];


					$priceUtility = $this->Utility($price);
					$resultUtility = json_decode($priceUtility);
					$gastos = $resultUtility[0];
					$impuest = $resultUtility[1];

					$priceC = $price_igv + $transporte + $impuest; $priceC = round($priceC, 2);
					$priceCG = $priceC + $gastos; $priceCG = round($priceCG, 2);

					$priceT = $priceC + ($transporte * $change);


					$dataDetail = array(
						'idShopping' => $insertShopping,
						'idPro' => $id,
						'pricePro' => $price,
						'priceProIGV' => $price_igv,
						'amountPro' => $amount,
						'unitPro' => $unit,
						'transPro' => $transporte,
						'priceC' => $priceC,
						'priceCG' => $priceCG,
						'percentPro1' => 0,
						'percentPro2' => 0,
						'priceP1' => $priceMin,
						'priceP2' => $priceMax
					);

					$this->shopping_model->saveDetailShopping($dataDetail);
					$this->stock($id, $amount);

					$this->shopping_model->actualizarReferencia($id, $insertShopping);
					
					$this->shopping_model->actualizarPrecios($id, $price_igv, $priceT, $priceCG, $priceMin, $priceMax, $insertShopping);
				}

				$shoppingTotal = $this->shoppingTotal($insertShopping);
				$shoppingArray = json_decode($shoppingTotal);
				$shoppingSinIgv = $shoppingArray[0];
				$shoppingDescuento = $shoppingArray[1];
				$shoppingIgv = $shoppingArray[2];
				$shoppingNeto = $shoppingArray[3];
				

				$this->shopping_model->updateShoppingFinal($insertShopping, $shoppingSinIgv, $shoppingDescuento, $shoppingIgv, $shoppingNeto);
				
				echo 'ok';
			}else{
				echo 'error';
			}
		}else{
			echo 'repeat';
		}
	}

	public function delete($id){
		$response = $this->shopping_model->delete($id);
		echo $response;
	}

	public function loadRecordShoppingToday(){
		$type = "'shopping'";
		$recordShopping = $this->shopping_model->loadRecordShoppingToday(); 
		$count = 0;
		$data = '';
		$dataTotal = 0;
		$igvPercent = 0;

		foreach($recordShopping as $row){
			$count = $count + 1;
			$id = $row['compra_id'];
			$igvPercent = $row['igv_porcentaje'];
			$shoppingId = $this->leftZero(10, $id);
			$shoppingDate = date('d/m/Y H:i:s', strtotime($row['compra_fecha']));
			$shoppingType = $this->shoppingType($row['compra_tipo']);
			$shoppingSerie = $row['compra_serie'];
			$shoppingNumber = $row['compra_numero'];
			$provSql = $this->shopping_model->loadProviderId($row['proveedor_id']);
			$cusName = '';
			$getStatus = $this->getBtnStatus($row['compra_estado'], $row['compra_id']);
			$getStatus = json_decode($getStatus);
			$btnStatus = $getStatus[0];
			$status = $getStatus[1];		

			$sqlTypePay = $this->shopping_model->loadMovementsId($row['movimiento_id']);
			$nameTypePay = '';
			foreach($sqlTypePay as $rowType){
				$nameTypePay = stripslashes($rowType['mov_detalle']);
			}
			$shoppingCurrency = $row['compra_moneda'];

			if ($shoppingCurrency == 'DOL'){
				$change = $this->shopping_model->currencyChange();
				$mon = '$.';
			}else{
				$change = 1;
				$mon = 'S/.';
			}

			foreach($provSql as $rowProv) {
				$ProvName = stripslashes($rowProv['prov_nombre']);
			}

			$shoppingSinIgv = $row['compra_subtotal'] / $change;
			$shoppingDescuento = $row['compra_descuento'];
			$shoppingIgv = $row['compra_igv'] / $change;
			$shoppingNeto = $row['compra_neto'] / $change;

			$dataTotal = $dataTotal+$shoppingNeto;

			$button = '<div class="btn-group" role="group" id="ddb">
						    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						      Accion
						      <span class="caret"></span>
						    </button>
						    <ul class="dropdown-menu">
						      <li>
						      	<a href="javascript:void(0);" onClick="seeDetailShopping('.$id.');" data-toggle="tooltip" title="Ver Detalle"><span class="glyphicon glyphicon-list"></span> Ver detalle</a>
						      </li>
						      <li><a href="javascript:void(0);" onClick="deletePage('.$id.', '.$type.');" data-toggle="tooltip" title="Eliminar"><span class="glyphicon glyphicon-remove"></span> Eliminar</a>
						      </li>
						    </ul>
					  	</div>';

			$data .= '<tr id="reg-'.$id.'">
						<td>'.$shoppingType.'</td>
						<td id="strStatus-'.$id.'">'.$status.'</td>
						<td>'.$shoppingSerie.'-'.$shoppingNumber.'</td>
						<td>'.$shoppingDate.'</td>
						<td>'.$ProvName.'</td>
						<td>'.$nameTypePay.'</td>
						<td>'.$shoppingCurrency.'</td>
						<td align="right">'.number_format($shoppingSinIgv, 2).'</td>
						<td align="right">'.number_format($shoppingDescuento, 2).'</td>
						<td align="right">'.number_format($shoppingIgv, 2).'</td>
						<td align="right">'.number_format($shoppingNeto, 2).'</td>
						<td>
							'.$button.'
						</td>
					  </tr>';
		}

		if($count > 0){
			$files = $data;
		}else{
			/* cambios */
			$files = '<tr><td colspan="12">No se encontraron resultados.</td></tr>';
			/* fin cambios */
		}

		$array = array(0 => $files,
			           1 => 'S/.'.number_format($dataTotal, 2),
			           2 => date('Y-m-d'));
		echo json_encode($array);
	}

	public function searchShoppingByDate(){
		$type = "'shopping'";
		$from = $_POST['shopping-from'].' 00:00:00'; 
		$to = $_POST['shopping-to'].' 23:59:59';
		$dataSearch = array(
			'from' => $from,
			'to' => $to
		);
		$recordShopping = $this->shopping_model->searchShoppingByDate($dataSearch);
		$count = 0;
		$data = '';
		$dataTotal = 0;
		$igvPercent = 0;

		foreach($recordShopping as $row){
			$count = $count + 1;
			$id = $row['compra_id'];
			$igvPercent = $row['igv_porcentaje'];
			$shoppingId = $this->leftZero(10, $id);
			$shoppingDate = date('d/m/Y H:i:s', strtotime($row['compra_fecha']));
			$shoppingType = $this->shoppingType($row['compra_tipo']);
			$shoppingSerie = $row['compra_serie'];
			$shoppingNumber = $row['compra_numero'];
			$provSql = $this->shopping_model->loadProviderId($row['proveedor_id']);
			$cusName = '';
			$getStatus = $this->getBtnStatus($row['compra_estado'], $row['compra_id']);
			$getStatus = json_decode($getStatus);
			$btnStatus = $getStatus[0];
			$status = $getStatus[1];		

			$sqlTypePay = $this->shopping_model->loadMovementsId($row['movimiento_id']);
			$nameTypePay = '';
			foreach($sqlTypePay as $rowType){
				$nameTypePay = stripslashes($rowType['mov_detalle']);
			}
			$shoppingCurrency = $row['compra_moneda'];

			if ($shoppingCurrency == 'DOL'){
				$change = $this->shopping_model->currencyChange();
				$mon = '$.';
			}else{
				$change = 1;
				$mon = 'S/.';
			}

			foreach($provSql as $rowProv) {
				$ProvName = stripslashes($rowProv['prov_nombre']);
			}

			$shoppingSinIgv = $row['compra_subtotal'];
			$shoppingDescuento = $row['compra_descuento'];
			$shoppingIgv = $row['compra_igv'];
			$shoppingNeto = $row['compra_neto'];

			$dataTotal = $dataTotal+$shoppingNeto;

			$button = '<div class="btn-group" role="group" id="ddb">
						    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						      Accion
						      <span class="caret"></span>
						    </button>
						    <ul class="dropdown-menu">
						      <li>
						      	<a href="javascript:void(0);" onClick="seeDetailShopping('.$id.');" data-toggle="tooltip" title="Ver Detalle"><span class="glyphicon glyphicon-list"></span> Ver detalle</a>
						      </li>
						      <li><a href="javascript:void(0);" onClick="deletePage('.$id.', '.$type.');" data-toggle="tooltip" title="Eliminar"><span class="glyphicon glyphicon-remove"></span> Eliminar</a>
						      </li>
						    </ul>
					  	</div>';

			$data .= '<tr id="reg-'.$id.'">
						<td>'.$shoppingType.'</td>
						<td id="strStatus-'.$id.'">'.$status.'</td>
						<td>'.$shoppingSerie.'-'.$shoppingNumber.'</td>
						<td>'.$shoppingDate.'</td>
						<td>'.$ProvName.'</td>
						<td>'.$nameTypePay.'</td>
						<td>'.$shoppingCurrency.'</td>
						<td align="right">'.number_format($shoppingSinIgv, 2).'</td>
						<td align="right">'.number_format($shoppingDescuento, 2).'</td>
						<td align="right">'.number_format($shoppingIgv, 2).'</td>
						<td align="right">'.number_format($shoppingNeto, 2).'</td>
						<td>
							'.$button.'
						</td>
					  </tr>';
		}

		if($count > 0){
			$files = $data;
		}else{
			$files = '<tr><td colspan="11">No se encontraron resultados.</td></tr>';
		}

		$array = array(0 => $files,
			           1 => 'S/.'.number_format($dataTotal, 2));
		echo json_encode($array);
	}

	public function loadRecordShoppingDetail(){
		$id = $_POST['id'];
		$recordShopping = $this->shopping_model->loadRecordShoppingId($id);
		$data = '';
		$igvPercent = 0;
		$shoppingSinIgv = 0;
		$shoppingDescuento = 0;
		$shoppingIgv = 0;
		$shoppingNeto = 0;
		foreach($recordShopping as $row){
			$idShopping = $row['compra_id'];
			$shoppingType = $this->shoppingType($row['compra_tipo']);
			$shopping = $row['compra_serie'].'-'.$row['compra_numero'];
			$date = date('d/m/Y H:i:s', strtotime($row['compra_fecha']));
			$provId = $row['proveedor_id']; 
			$typeId = $row['movimiento_id']; 
			$sqlProvider = $this->shopping_model->loadProviderId($provId);
			$idProvider = '';
			$provider = '';
			foreach($sqlProvider as $rowProveedor){
				$idProvider = stripslashes($rowProveedor['prov_documento']);
				$provider = stripslashes($rowProveedor['prov_nombre']);
			}

			$sqlType = $this->shopping_model->loadMovementsId($typeId);
			$nameType = '';
			foreach($sqlType as $rowType){
				$nameType = stripslashes($rowType['mov_detalle']);
			}

			$shoppingCurrency = $row['compra_moneda'];

			if ($shoppingCurrency == 'DOL'){
				$change = $this->shopping_model->currencyChange();
				$mon = '$.';
			}else{
				$change = 1;
				$mon = 'S/.';
			}
			
			$igvPercent = $row['igv_porcentaje'];

			$data = '<table width="100%">
		        		<tr>
		        			<th>'.$shoppingType.': </th>
		        			<td>'.$shopping.'</td>
		        			<th style="text-align:right;">Tipo: </th>
		        			<td>'.$nameType.'</td>
		        			<th style="text-align:right;">Fecha: </th>
		        			<td>'.$date.'</td>
		        			<th style="text-align:right;">RUC Proveedor: </th>
		        			<td>'.$idProvider.'</td>
		        			<th style="text-align:right;">Nombre: </th>
		        			<td>'.$provider.'</td>
		        			<th></th>
		        			<td></td>
		        		</tr>
		        	</table><br>';
		    $shoppingSinIgv = $row['compra_subtotal'] / $change;
			$shoppingDescuento = $row['compra_descuento'];
			$shoppingIgv = $row['compra_igv'] / $change;
			$shoppingNeto = $row['compra_neto'] / $change;
		}

		$recordShoppingDetail = $this->shopping_model->loadRecordShoppingDetail($id);
		$data .= '<table class="table table-condensed table-striped table-hover">
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
					<tbody>';
		$brutoFooter = 0;
		$igvFooter = 0;
		$descFooter = 0;
		foreach($recordShoppingDetail as $row){
			$productId = $row['producto_id'];
			$dataProduct = $this->shopping_model->loadProductId($productId);
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
						<td>'.$unit.'</td>
						<td align="right">'.number_format($price, 2).'</td>
						<td align="right">'.number_format($amount, 2).'</td>
						<td align="right">'.number_format($desc, 2).'</td>
						<td align="right">'.number_format($subtotal, 2).'</td>
					  </tr>';
		}

		$data .= '<tr>
				  	<th colspan="6" style="text-align:right;">Bruto: </th>
				  	<td align="right"><label class="text-primary">'.$mon.' '.number_format($shoppingSinIgv, 2).'</label></td>
				  </tr>
				  <tr>
				  	<th colspan="6" style="text-align:right;">Descuento: </th>
				  	<td align="right"><label class="text-primary">'.$mon.' '.number_format($shoppingDescuento, 2).'</label></td>
				  </tr>
				  <tr>
				  	<th colspan="6" style="text-align:right;">IGV '.($igvPercent*100).'%: </th>
				  	<td align="right"><label class="text-primary">'.$mon.' '.number_format($shoppingIgv, 2).'</label></td>
				  </tr>
				  <tr>
				  	<th colspan="6" style="text-align:right;">Total: </th>
				  	<td align="right"><label class="text-primary">'.$mon.' '.number_format($shoppingNeto, 2).'</label></td>
				  </tr>
				  </tbody></table>';
		echo $data;
	}

	public function searchProvider(){
		$value = addslashes(strtoupper($_POST['value']));
		$provider = $this->shopping_model->searchProvider($value);
		$id = 0;
		$doc = '';
		$name = '';
		foreach($provider as $row){
			$id = $row['prov_id'];
			$name = stripslashes($row['prov_nombre']);
		}
		$array = array(0 => $id,
					   1 => $name
				   	);
		echo json_encode($array);
	}

	public function searchProductDescription(){
		$value = $_GET['term'];
		$product = $this->shopping_model->loadProductDescription($value);
		$data = array();
		foreach($product as $row){
			$id = $row['prod_id'];
			$barcode = stripslashes($row['prod_codigo']); 
			$name = stripslashes($row['prod_nombre']); 
			$unit = stripslashes($row['prod_unidad']); 
			$price = $row['prod_precio_compra'];
			$data[] = array('value' => $name,
				  		    'id' => $id,
				  		    'barcode' => $barcode,
				  		    'unit' => $unit,
				  		    'price' => $price);
		}
		echo json_encode($data);
	}

	public function searchProductCode($search){
		$code = addslashes(strtoupper($_POST['code']));
		$product = $this->shopping_model->loadProductShopping($code, $search);
		$id = 0;
		$description = '';
		$unit = '';
		$price = '';
		$tax = 0;
		foreach($product as $row){
			$id = $row['prod_id'];
			$description = stripslashes($row['prod_nombre']);
			$unit = $row['prod_unidad'];
			$price = $row['prod_precio_compra'];
		}
		$array = array(0 => $id,
					   1 => $description,
					   2 => $unit,
					   3 => $price
					   );
		echo json_encode($array);
	}

	public function shoppingType($type){
		switch($type){
			case 'B':
				$str = 'Boleta';
			break;
			
			case 'F':
				$str = 'Factura';
			break;

			case 'O':
				$str = 'Otros';
			break;
		}
		return $str;
	}

	public function getBtnStatus($e, $id){
		if($e > 0){
			$status = '<font class="text-success">Activo</font>';
			if(isset($_SESSION['adm_sv'])){
				$btnStatus = '<li>
								<a href="javascript:void(0);" id="btnStatus-'.$id.'" onClick="inactiveShopping('.$id.');" data-toggle="tooltip" title="Anular">
									<span class="glyphicon glyphicon-remove-sign"></span> Anular
								</a>
							  </li>';
			}else{
				$btnStatus = '';
			}
		}else{
			$status = '<font class="text-danger">Anulado</font>';
			if(isset($_SESSION['adm_sv'])){
				$btnStatus = '<li>
								<a href="javascript:void(0);" id="btnStatus-'.$id.'" onClick="activeShopping('.$id.');" data-toggle="tooltip" title="Activar">
									<span class="glyphicon glyphicon-ok-sign"></span> Activar
								</a>
							  </li>';
			}else{
				$btnStatus = '';
			}
		}
		$array = array(0 => $btnStatus,
					   1 => $status);
		return json_encode($array);
	}

	function stock($id, $cantidad){
		$sqlPro = $this->shopping_model->stockReal($id);
		foreach ($sqlPro as $row){
			$stockReal = $row['prod_stock_real'];
		}

		$stockFinal = $stockReal + $cantidad;

		$f5 = $this->shopping_model->actualizarStock($id, $stockFinal);
	}

	function Utility($precio){
		$sql = $this->shopping_model->utilidad();
		foreach ($sql as $row){
			$gasto = $row['datos_gasto_mensual'];
			$imp = $row['datos_impuesto_renta'];
			$porc = $row['datos_porcentaje_gastos'];
		}

		// $gastos = $gasto * $porc;
		$gastos = $precio * (4 / 100);
		$renta = $precio * ($imp / 100);

		$array = array(0 => $gastos,
					   1 => $renta);
		return json_encode($array);
	}
}