<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Report extends CI_Controller{

	public function __construct(){
		parent::__construct();
		session_start();

		if (isset($_SESSION['usu_sv']) OR isset($_SESSION['adm_sv'])){
			$this->load->model('setting_model');
			$this->load->model('login_model');
			$this->load->model('product_model');
			$this->load->model('shopping_model');
			$this->load->model('sale_model');
			$this->load->model('employee_model');
		}else{
			header('Location:'.base_url());
		}
	}

	function index(){
		$this->header();
		$this->load->view('report');
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

	public function saleType($type){
		switch($type){
			case 'B':
				$str = 'Boleta';
			break;
			
			case 'F':
				$str = 'Factura';
			break;

			case 'A':
				$str = 'Alternativa';
			break;

			default:
				$str = 'Otro';
			break;
		}
		return $str;
	}

	public function salePayed($type, $id){
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

	public function restaFechasDias($fechaVen, $fechaHoy){
        $segundos = strtotime($fechaVen) - strtotime($fechaHoy);
        $diferencia_dias = intval($segundos/60/60/24);
        return $diferencia_dias; 
    }

	function nameUserSale($id){
		$data = array('id' => $id);
		$sql = $this->login_model->userDataId($data);
		$name = '';
		foreach($sql as $row){
			$name = $row['emp_nombre'].' '.$row['emp_apellido'];
		}
		return $name;
	}

	public function searchCustomerName(){
    	$value = $_GET['term'];
		$product = $this->sale_model->loadCustomerName($value);
		$data = array();
		foreach($product as $row){
			$id = $row['cli_id'];
			$ruc = stripslashes($row['cli_documento']);
			$description = stripslashes($row['cli_nombre']);
			$direction = stripslashes($row['cli_direccion']); 
			$data[] = array('value' => $description,
				  		    'id' => $id,
				  		    'direction' => $direction,
				  		    'ruc' => $ruc);
		}
		echo json_encode($data);
    }

    public function searchProviderName(){
		$value = $_GET['term'];
		$customer = $this->shopping_model->loadProviderName($value);
		$data = array();
		foreach($customer as $row){
			$id = $row['prov_id'];
			$description = stripslashes($row['prov_nombre']); 
			$data[] = array('value' => $description,
				  		    'id' => $id);
		}
		echo json_encode($data);
	}

	public function getSaleStatistic(){
		$value = $_POST['value'];
		$currency = $_POST['currency'];
		$sql1 = $this->sale_model->salesStatisticYear($value, $currency, 1);
		$sql2 = $this->sale_model->salesStatisticYear($value, $currency, 2);
		$sql3 = $this->sale_model->salesStatisticYear($value, $currency, 3);
		$sql4 = $this->sale_model->salesStatisticYear($value, $currency, 4);
		$sql5 = $this->sale_model->salesStatisticYear($value, $currency, 5);
		$sql6 = $this->sale_model->salesStatisticYear($value, $currency, 6);
		$sql7 = $this->sale_model->salesStatisticYear($value, $currency, 7);
		$sql8 = $this->sale_model->salesStatisticYear($value, $currency, 8);
		$sql9 = $this->sale_model->salesStatisticYear($value, $currency, 9);
		$sql10 = $this->sale_model->salesStatisticYear($value, $currency, 10);
		$sql11 = $this->sale_model->salesStatisticYear($value, $currency, 11);
		$sql12 = $this->sale_model->salesStatisticYear($value, $currency, 12);

		$total = $sql1+$sql2+$sql3+$sql4+$sql5+$sql6+$sql7+$sql8+$sql9+$sql10+$sql11+$sql12;

		$array = array(0 => number_format($sql1, 2, '.', ''),
					   1 => number_format($sql2, 2, '.', ''),
					   2 => number_format($sql3, 2, '.', ''),
					   3 => number_format($sql4, 2, '.', ''),
					   4 => number_format($sql5, 2, '.', ''),
					   5 => number_format($sql6, 2, '.', ''),
					   6 => number_format($sql7, 2, '.', ''),
					   7 => number_format($sql8, 2, '.', ''),
					   8 => number_format($sql9, 2, '.', ''),
					   9 => number_format($sql10, 2, '.', ''),
					   10 => number_format($sql11, 2, '.', ''),
					   11 => number_format($sql12, 2, '.', ''),
					   12 => number_format($total, 2, '.', ''));

		echo json_encode($array);
	}

	public function getShoppingStatistic(){
		$value = $_POST['value'];
		$currency = $_POST['currency'];
		$sql1 = $this->shopping_model->salesStatisticYear($value, $currency, 1);
		$sql2 = $this->shopping_model->salesStatisticYear($value, $currency, 2);
		$sql3 = $this->shopping_model->salesStatisticYear($value, $currency, 3);
		$sql4 = $this->shopping_model->salesStatisticYear($value, $currency, 4);
		$sql5 = $this->shopping_model->salesStatisticYear($value, $currency, 5);
		$sql6 = $this->shopping_model->salesStatisticYear($value, $currency, 6);
		$sql7 = $this->shopping_model->salesStatisticYear($value, $currency, 7);
		$sql8 = $this->shopping_model->salesStatisticYear($value, $currency, 8);
		$sql9 = $this->shopping_model->salesStatisticYear($value, $currency, 9);
		$sql10 = $this->shopping_model->salesStatisticYear($value, $currency, 10);
		$sql11 = $this->shopping_model->salesStatisticYear($value, $currency, 11);
		$sql12 = $this->shopping_model->salesStatisticYear($value, $currency, 12);

		$total = $sql1+$sql2+$sql3+$sql4+$sql5+$sql6+$sql7+$sql8+$sql9+$sql10+$sql11+$sql12;

		$array = array(0 => number_format($sql1, 2, '.', ''),
					   1 => number_format($sql2, 2, '.', ''),
					   2 => number_format($sql3, 2, '.', ''),
					   3 => number_format($sql4, 2, '.', ''),
					   4 => number_format($sql5, 2, '.', ''),
					   5 => number_format($sql6, 2, '.', ''),
					   6 => number_format($sql7, 2, '.', ''),
					   7 => number_format($sql8, 2, '.', ''),
					   8 => number_format($sql9, 2, '.', ''),
					   9 => number_format($sql10, 2, '.', ''),
					   10 => number_format($sql11, 2, '.', ''),
					   11 => number_format($sql12, 2, '.', ''),
					   12 => number_format($total, 2, '.', ''));

		echo json_encode($array);
	}

	public function inventaryDetailed($type, $from, $to){
		$status = 0;
		if ($type == 0){
			$sql = $this->product_model->loadProductsAll();
		}else if($type > 0){	
			$sql = $this->product_model->loadProductsAllType($type);
		}
		$html = '';
		foreach($sql as $row){
			$id = $row['prod_id'];
			$n = $this->product_model->verifyProductInventary($id, $from, $to);
			if($n > 0){
				$status = 1;
				$html .= $this->processReportKardex($id, $from, $to, $type);
			}
		}	
		if($status > 0){
			return $html;
		}else{
			return '<br><center><p>...No se encontraron resultados...</p></center>';
		}
	}

	public function processReportKardex($idProduct, $from, $to, $type){
		$products = $this->product_model->loadProducts($idProduct);
		$typePro = 0;
		foreach($products as $row){
			$id = $row['prod_id'];
			$code = stripslashes($row['prod_codigo']);
			$name = stripslashes($row['prod_nombre']);
			$unit = stripslashes($row['prod_unidad']);
			$stock = /*$this->stock($row['prod_id'], $type)*/$row['prod_stock_real'];
			$stockMinimum = $row['prod_stock_min'];
			$cate = $row['categ_id'];
			$marc = $row['marca_id'];

			$categ = $this->product_model->loadCategorysId($cate);
			foreach ($categ as $value){
				$categoria = $value['categ_valor'];
			}

			$marcs = $this->product_model->loadMarkasId($marc);
			foreach ($marcs as $value){
				$marca = $value['marca_nombre'];
			}
		}

		$html = '<br>
				<div class="thumbnail alert-default">
					<table class="tableSearchInformation" width="100%">
						<tr>
							<th width="120">Codigo interno: </th>
							<td width="250">'.$code.'</td>
							<th width="120">Descripcion: </th>
							<td colspan="3">'.$name.'</td>
							<th width="120">Unidad: </th>
							<td>'.$unit.'</td>
						</tr>
						<tr>
							<th width="120">Marca: </th>
							<td>'.$marca.'</td>
							<th width="120">Categoria: </th>
							<td>'.$categoria.'</td>
							<th width="120">Stock minimo: </th>
							<td>'.number_format($stockMinimum, 2).'</td>
							<th width="120">Stock actual: </th>
							<td>'.number_format($stock, 2).'</td>
						</tr>
					</table>';
	    
	    $html .= $this->getKardexDate($idProduct, $from, $to);
	    return $html;
	}

	public function processReportKardexGrouped($idProduct, $from, $to, $type){
		$products = $this->product_model->loadProducts($idProduct);
		$typePro = 0;
		foreach($products as $row){
			$id = $row['prod_id'];
			$code = stripslashes($row['prod_codigo']);
			$name = stripslashes($row['prod_nombre']);
			$unit = stripslashes($row['prod_unidad']);
			$priceCost = number_format($row['prod_precio_compra'], 2);
			$priceSale = number_format($row['prod_precio_venta'], 2);
			$stock = /*$this->stock($row['prod_id'], $type)*/$row['prod_stock_real'];
			$stockMinimum = $row['prod_stock_min'];
			$cate = $row['categ_id'];

			$categ = $this->product_model->loadCategorysId($cate);
			foreach ($categ as $value){
				$categoria = $value['categ_valor'];
			}
		}

		$from = date('Y-m-d', strtotime($from));
		$to = date('Y-m-d', strtotime($to));
		$nDays = $this->restaFechasDias($to, $from);
		$before = $this->product_model->amountDateProductBefore($type, $from, $idProduct);

		$html = '<tr>
					<td>'.$code.'</td>
					<td>'.$name.'</td>
					<td>'.$unit.'</td>
					<td align="right">'.number_format($before, 2, '.', '').'</td>';

		$dateSql = strtotime('-1 day', strtotime($from));
		$dateSql = date('Y-m-d', $dateSql);
		$saldo = $before;
		for($i=1; $i<=($nDays+1); $i++){
			$dateSql = strtotime('+1 day', strtotime($dateSql));
			$dateSql = date('Y-m-d', $dateSql);
			$amount = $this->product_model->amountDateProduct($type, $dateSql, $idProduct);
			$html .= '<td align="right">'.number_format($amount, 2, '.', '').'</td>';
			$saldo = $saldo+$amount;
		}

		$html .= '<td align="right">'.number_format($saldo, 2, '.', '').'</td>
			      </tr>';
	
	    return $html;
	}

	public function getKardexDate($idProduct, $from, $to){
		$html = '<table border="1" class="table table-condensed table-hover table-bordered table-responsive">
					<thead>
						<tr>
							<th>Tipo</th>
							<th>Fecha</th>
							<th>Serie-Numero</th>
							<th colspan="2">Cliente/Area/Proveedor</th>
							<th>Entrada</th>
							<th>Salida</th>
							<th>Saldo</th>
						</tr>
					</thead>
					<tbody id="historyKardex" style="background-color:white;">';
		$totalShoppingBefore = $this->product_model->loadShoppingKardexArrayBefore($idProduct, $from);
		$totalSaleBefore = $this->product_model->loadSalesKardexArrayBefore($idProduct, $from);
		$totalBefore = $totalShoppingBefore - $totalSaleBefore;
		$html .= '<tr>
    				<td colspan="7"><strong>Saldo anterior</strong></td>
    				<td align="right">'.number_format($totalBefore, 2, '.', '').'</td>
    			  </tr>';

    	$rowSale = $this->product_model->loadSalesKardexNumDate($idProduct, $from, $to);
		$rowShopping = $this->product_model->loadShoppingKardexNumDate($idProduct, $from, $to);

        if($rowSale > 0){
        	$sqlS = $this->product_model->loadSalesKardexArrayDate($idProduct, $from, $to);
            foreach($sqlS as $rowS){
            	$dataSale[] = array('date'=>$rowS['venta_fecha'], 'id'=>$rowS['venta_id'], 'type'=>'sale', 'currency' => $rowS['venta_moneda']);
            }   
      	}else{
        	$dataSale[] = array('date'=>date('Y-m-d H:i:s'), 'id'=>null, 'type'=>null, 'currency'=>null);
      	}
      
        if($rowShopping > 0){
        	$sqlShopping = $this->product_model->loadShoppingKardexArrayDate($idProduct, $from, $to);
            foreach($sqlShopping as $rowShopping){
            	$dataShopping[] = array('date'=>$rowShopping['compra_fecha'], 'id'=>$rowShopping['compra_id'], 'type'=>'shopping', 'currency' => $rowShopping['compra_moneda']);
            }   
      	}else{
        	$dataShopping[] = array('date'=>date('Y-m-d H:i:s'), 'id'=>null, 'type'=>null, 'currency'=>null);
      	}

      	$arrayTotal = array();
	   	$arrayTotal = array_merge($dataSale, $dataShopping);

	   	$date = array(); 
		foreach($arrayTotal as $row){
			$date[] = $row['date'];
		}

		if($rowSale > 0 OR $rowShopping > 0){
            array_multisort($date, SORT_ASC, $arrayTotal);
            $saldo = $totalBefore;
            foreach($arrayTotal as $key => $val){
                $docType = $val['type'];
                $docId = $val['id'];
                $docDate = $val['date'];
                if($docType != null){
	                if($docType == 'sale'){
	                	$sSQL = $this->product_model->loadSaleSQL($docId);
	                	foreach($sSQL as $sRow){
	                		$sId = $sRow['venta_serie'].'-'.$sRow['venta_numero'];
	                		$sDate = date('d/m/Y', strtotime($sRow['venta_fecha']));
	                		$cus = $this->product_model->customerSale($sRow['cliente_id']);
							$sCus = '';
							foreach($cus as $cusRow){
								$sCus = $cusRow['cli_nombre'];
							}
							$sTotal = $this->product_model->sale($sRow['venta_id'], $idProduct);
							$sAmount = 0;
							foreach($sTotal as $sTotalRow){
								$sAmount = $sTotalRow['total'];
							}
                		}
                		$saldo = $saldo-$sAmount;
                		$html .= '<tr>
	                				<td>Venta</td>
	                				<td>'.$sDate.'</td>
	                				<td>'.$sId.'</td>
	                				<td colspan="2">'.$sCus.'</td>
	                				<td align="right">0.00</td>
	                				<td align="right">'.number_format($sAmount, 2, '.', '').'</td>
	                				<td align="right">'.number_format($saldo, 2, '.', '').'</td>
	                			  </tr>';
	                }else if($docType == 'shopping'){
	                	$shoppingSQL = $this->product_model->loadShoppingSQL($docId);
	                	foreach($shoppingSQL as $shoppingRow){
	                		$shoppingId = $shoppingRow['compra_serie'].'-'.$shoppingRow['compra_numero'];
	                		$shoppingDate = date('d/m/Y', strtotime($shoppingRow['compra_fecha']));
	                		$provider = $this->product_model->providerSale($shoppingRow['proveedor_id']);
							$shoppingProvider = '';
							foreach($provider as $providerShow){
								$shoppingProvider = $providerShow['prov_nombre'];
							}
							$shoppingTotal = $this->product_model->shopping($shoppingRow['compra_id'], $idProduct);
							$shoppingAmount = 0;
							foreach($shoppingTotal as $shoppingTotalRow){
								$shoppingAmount = $shoppingTotalRow['total'];
							}
	                	}
	                	$saldo = $saldo+$shoppingAmount;
	                	$html .= '<tr>
	                				<td>Compra</td>
	                				<td>'.$shoppingDate.'</td>
	                				<td>'.$shoppingId.'</td>
	                				<td colspan="2">'.$shoppingProvider.'</td>
	                				<td align="right">'.number_format($shoppingAmount, 2, '.', '').'</td>
	                				<td align="right">0.00</td>
	                				<td align="right">'.number_format($saldo, 2, '.', '').'</td>
	                			  </tr>';	
	                }
	            }
            }
        }
		$html .= '</tbody></table></div>';
		unset($arrayTotal, $dataSale, $dataShopping);
		return $html;
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

	public function reportStatistic(){
		echo '<h3>Cuadros estadisticos</h3>
				<table width="100%" class="tableStatistic">
					<tr>
						<td width="50%">
							<div class="input-group" style="width:450px; margin-left:50px;">
							  	<span class="input-group-addon">Total de ventas en el año </span>
							  	<select class="form-control" id="year" onChange="reportSalesStatistic();" style="width:80px;">';
								for($i=2016; $i<=2030; $i++){
									if($i == date('Y')){
										echo '<option value="'.$i.'" selected>'.$i.'</option>';
									}else{
										echo '<option value="'.$i.'">'.$i.'</option>';
									}
								}
					      echo '</select>
					      		<span class="input-group-addon"></span>
					      		<select class="form-control" id="currency" onChange="reportSalesStatistic();" style="width:110px;">
									<option value="PEN">PEN - S/.</option>
									<option value="DOL">USD - $</option>
					      		</select>
							  	<span class="input-group-addon" id="total-statistic-sale" style="min-width: 100px; text-align: right;"></span>
							</div>
							<br>
							<div id="reportSalesStatistic"></div>
						</td>
						<td width="50%">
							<div class="input-group" style="width:450px; margin-left:50px;">
							  	<span class="input-group-addon">Total de compras en el año </span>
							  	<select class="form-control" id="yearC" onChange="reportShoppingStatistic();" style="width:80px;">';
							  	for($i=2016; $i<=2030; $i++){
									if($i == date('Y')){
										echo '<option value="'.$i.'" selected>'.$i.'</option>';
									}else{
										echo '<option value="'.$i.'">'.$i.'</option>';
									}
								}
						  echo '</select>
						   	    <span class="input-group-addon"></span>
					      		<select class="form-control" id="currencyC" onChange="reportShoppingStatistic();" style="width:110px;">
									<option value="PEN">PEN - S/.</option>
									<option value="DOL">USD - $</option>
					      		</select>
							  	<span class="input-group-addon" id="total-statistic-shopping" style="min-width: 100px; text-align: right;"></span>
							</div>
							<br>
							<div id="reportShoppingStatistic"></div>
						</td>
					</tr>
				 </table>';
		echo '<script>
				reportSalesStatistic();
				reportShoppingStatistic();
			  </script>';
	}

	public function reportSales(){
		$html = '<h3>Resumen de ventas</h3>
				 <table>
      				<tr>
      					<td style="padding-right:10px;">
      						<div class="input-group">
							  	<span class="input-group-addon">
							  		Desde
							  	</span>
							  	<input type="date" name="sale-from" class="form-control" style="width:160px;" value="'.date('Y-m-d').'" required>
							</div>
						</td>
      					<td style="padding-right:10px;">
      						<div class="input-group">
							  	<span class="input-group-addon">
							  		Hasta
							  	</span>
							  	<input type="date" name="sale-to" class="form-control" style="width:160px;" value="'.date('Y-m-d').'" required>
							</div>
      					</td>
      					<td style="padding-right:10px;">
      						<div class="input-group">
							  	<span class="input-group-addon">
							  		Moneda
							  	</span>
							  	<select name="currency" class="form-control" style="width:170px;" required>
									<option value="PEN">Nuevos Soles</option>
									<option value="DOL">Dolares Americanos</option>
							  	</select>
							</div>
      					</td>
      					<td style="padding-right:10px;">
      						<button type="button" class="btn btn-info" onClick="processReportSales();">Buscar <span class="glyphicon glyphicon-search"></span>
      						</button>
      					</td>
      					<td>
      						<button class="btn btn-success" onClick="processReportSalesExcel();">Excel <span class="glyphicon glyphicon-arrow-down"></span>
      						</button>
      					</td>
      				</tr>
      			</table>
      			<br>
      			<table class="table table-bordered table-condensed table-hover">
	        		<thead>
	        			<tr>
	        				<th width="100">Comprobante</th>
	        				<th width="120">Serie-Numero</th>
	        				<th width="90">Fecha</th>
	        				<th>Usuario</th>
	        				<th>RUC</th>
	        				<th>Cliente</th>
	        				<th>Tipo de pago</th>
	        				<th>Pagado</th>
	        				<th width="90">Subtotal</th>
	        				<th width="90">Descuento</th>
	        				<th width="90">IGV</th>
	        				<th width="90">Total</th>
	        				<th width="60">Opcion</th>
	        			</tr>
	        		</thead>
	        		<tbody id="dataRecordSale"></tbody>
	        	</table>';
	    echo $html;
	}

	public function reportShopping(){
		$html = '<h3>Resumen de compras</h3>
				 <table>
      				<tr>
      					<td style="padding-right:10px;">
      						<div class="input-group">
							  	<span class="input-group-addon">
							  		Desde
							  	</span>
							  	<input type="date" name="shopping-from" class="form-control" style="width:160px;" value="'.date('Y-m-d').'" required>
							</div>
						</td>
      					<td style="padding-right:10px;">
      						<div class="input-group">
							  	<span class="input-group-addon">
							  		Hasta
							  	</span>
							  	<input type="date" name="shopping-to" class="form-control" style="width:160px;" value="'.date('Y-m-d').'" required>
							</div>
      					</td>
      					<td style="padding-right:10px;">
      						<div class="input-group">
							  	<span class="input-group-addon">
							  		Moneda
							  	</span>
							  	<select name="currency" class="form-control" style="width:170px;" required>
									<option value="PEN">Nuevos Soles</option>
									<option value="DOL">Dolares Americanos</option>
							  	</select>
							</div>
      					</td>
      					<td style="padding-right:10px;">
      						<button type="button" class="btn btn-info" onClick="processReportShopping();">Buscar <span class="glyphicon glyphicon-search"></span>
      						</button>
      					</td>
      					<td>
      						<button class="btn btn-success" onClick="processReportShoppingExcel();">Excel <span class="glyphicon glyphicon-arrow-down"></span>
      						</button>
      					</td>
      				</tr>
      			</table>
      			<br>
      			<table class="table table-bordered table-condensed table-hover sortable">
	        		<thead>
	        			<tr>
	        				<th width="100">Comprobante</th>
	        				<th width="120">Serie-Numero</th>
	        				<th width="90">Fecha</th>
	        				<th>Usuario</th>
	        				<th>RUC</th>
	        				<th>Proveedor</th>
	        				<th>Tipo de pago</th>
	        				<th width="90">Subtotal</th>
	        				<th width="90">Descuento</th>
	        				<th width="90">IGV</th>
	        				<th width="90">Total</th>
	        				<th width="60">Opcion</th>
	        			</tr>
	        		</thead>
	        		<tbody id="dataRecordSale"></tbody>
	        	</table>';
	    echo $html;
	}

	public function reportSalesType(){
		$movements = $this->sale_model->loadMovements();
		$mov = '';
		foreach($movements as $row){
			$movId = $row['mov_id'];
			$movDet = stripslashes($row['mov_detalle']);
			$mov .= '<option value="'.$movId.'">'.$movDet.'</option>'; 
		}
		$html = '<h3>Ventas por Tipo de Pago</h3>
				 <table>
      				<tr>
      					<td style="padding-right:10px;">
      						<div class="input-group">
							  	<span class="input-group-addon">
							  		Tipo
							  	</span>
							  	<select id="sale-movement" class="form-control" style="width:110px;">'.$mov.'</select>
							</div>
      					</td>
      					<td style="padding-right:10px;">
      						<div class="input-group">
							  	<span class="input-group-addon">
							  		Desde
							  	</span>
							  	<input type="date" name="sale-from" class="form-control" style="width:160px;" value="'.date('Y-m-d').'" required>
							</div>
						</td>
      					<td style="padding-right:10px;">
      						<div class="input-group">
							  	<span class="input-group-addon">
							  		Hasta
							  	</span>
							  	<input type="date" name="sale-to" class="form-control" style="width:160px;" value="'.date('Y-m-d').'" required>
							</div>
      					</td>
      					<td style="padding-right:10px;">
      						<div class="input-group">
							  	<span class="input-group-addon">
							  		Moneda
							  	</span>
							  	<select name="currency" class="form-control" style="width:170px;" required>
									<option value="PEN">Nuevos Soles</option>
									<option value="DOL">Dolares Americanos</option>
							  	</select>
							</div>
      					</td>
      					<td style="padding-right:10px;">
      						<button type="button" class="btn btn-info" onClick="processReportSalesType();">Buscar <span class="glyphicon glyphicon-search"></span>
      						</button>
      					</td>
      					<td>
      						<button class="btn btn-success" onClick="processReportSalesTypeExcel();">Excel <span class="glyphicon glyphicon-arrow-down"></span>
      						</button>
      					</td>
      				</tr>
      			</table>
      			<br>
      			<table class="table table-bordered table-condensed table-hover sortable">
	        		<thead>
	        			<tr>
	        				<th width="100">Comprobante</th>
	        				<th width="120">Serie-Numero</th>
	        				<th width="90">Fecha</th>
	        				<th>Usuario</th>
	        				<th>RUC</th>
	        				<th>Cliente</th>
	        				<th>Tipo de pago</th>
	        				<th>Pagado</th>
	        				<th width="90">Subtotal</th>
	        				<th width="90">Descuento</th>
	        				<th width="90">IGV</th>
	        				<th width="90">Total</th>
	        			</tr>
	        		</thead>
	        		<tbody id="dataRecordSale"></tbody>
	        	</table>';
	    echo $html;
	}

	public function reportCustomerSales(){
		$html = '<h3>Ventas por cliente</h3>
				 <table>
      				<tr>
      					<td style="padding-right:10px; display:none;">
      						<div class="input-group">
							  	<span class="input-group-addon">
							  		Codigo
							  	</span>
							  	<input type="text" class="form-control" style="width:90px; text-transform:uppercase;" onkeyup="searchCustomer(this.value, 1);" id="customer">
							</div>
						</td>
      					
						<td style="padding-right:10px;">
      						<div class="input-group">
							  	<span class="input-group-addon">
							  		Razon
							  	</span>
							  	<input type="text" class="form-control" id="sale-customer-name" style="width:280px;">
							</div>
						</td>
      					<td style="padding-right:10px;">
      						<div class="input-group">
							  	<span class="input-group-addon">
							  		Desde
							  	</span>
							  	<input type="date" name="sale-from" class="form-control" style="width:160px;" value="'.date('Y-m-d').'" required>
							</div>
						</td>
      					<td style="padding-right:10px;">
      						<div class="input-group">
							  	<span class="input-group-addon">
							  		Hasta
							  	</span>
							  	<input type="date" name="sale-to" class="form-control" style="width:160px;" value="'.date('Y-m-d').'" required>
							</div>
      					</td>
      					<td style="padding-right:10px;">
      						<div class="input-group">
							  	<span class="input-group-addon">
							  		Moneda
							  	</span>
							  	<select name="currency" class="form-control" style="width:170px;" required>
									<option value="PEN">Nuevos Soles</option>
									<option value="DOL">Dolares Americanos</option>
							  	</select>
							</div>
      					</td>
      					<td style="padding-right:10px;">
      						<button type="button" class="btn btn-info" onClick="processReportSalesCustomer();">Buscar <span class="glyphicon glyphicon-search"></span>
      						</button>
      					</td>
      					<td>
      						<button class="btn btn-success" onClick="processReportSalesCustomerExcel();">Excel <span class="glyphicon glyphicon-arrow-down"></span>
      						</button>
      					</td>
      				</tr>
      			</table>
      			<br>
      			<table class="table table-bordered table-condensed table-hover sortable">
	        		<thead>
	        			<tr>
	        				<th>Comprobante</th>
	        				<th width="120">Serie-Numero</th>
	        				<th width="90">Fecha</th>
	        				<th>Usuario</th>
	        				<th>RUC</th>
	        				<th>Cliente</th>
	        				<th>Tipo de pago</th>
	        				<th width="90">Subtotal</th>
	        				<th width="90">Descuento</th>
	        				<th width="90">IGV</th>
	        				<th width="90">Total</th>
	        			</tr>
	        		</thead>
	        		<tbody id="dataRecordSale"></tbody>
	        	</table>
	        	<script>
	        		$("#sale-customer-name").autocomplete({
				        source: baseUrl+"report/searchCustomerName",
				        select: function(event, ui){
				            var id = ui.item.id;
				            $("#customer").val(id);
				        }
				    });
	        	</script>';
	    echo $html;	
	}

	public function reportProviderShopping(){
		$html = '<h3>Compras por proveedor</h3>
				 <table>
      				<tr>
      					<td style="padding-right:10px; display:none;">
      						<div class="input-group">
							  	<span class="input-group-addon">
							  		Codigo
							  	</span>
							  	<input type="text" class="form-control" style="width:90px; text-transform:uppercase;" onkeyup="searchProvider(this.value, 1);" id="customer">
							</div>
						</td>
						<td style="padding-right:10px;">
      						<div class="input-group">
							  	<span class="input-group-addon">
							  		Razon
							  	</span>
							  	<input type="text" class="form-control" id="sale-customer-name" style="width:280px;">
							</div>
						</td>
      					<td style="padding-right:10px;">
      						<div class="input-group">
							  	<span class="input-group-addon">
							  		Desde
							  	</span>
							  	<input type="date" name="sale-from" class="form-control" style="width:160px;" value="'.date('Y-m-d').'" required>
							</div>
						</td>
      					<td style="padding-right:10px;">
      						<div class="input-group">
							  	<span class="input-group-addon">
							  		Hasta
							  	</span>
							  	<input type="date" name="sale-to" class="form-control" style="width:160px;" value="'.date('Y-m-d').'" required>
							</div>
      					</td>
      					<td style="padding-right:10px;">
      						<div class="input-group">
							  	<span class="input-group-addon">
							  		Moneda
							  	</span>
							  	<select name="currency" class="form-control" style="width:170px;" required>
									<option value="PEN">Nuevos Soles</option>
									<option value="DOL">Dolares Americanos</option>
							  	</select>
							</div>
      					</td>
      					<td style="padding-right:10px;">
      						<button type="button" class="btn btn-info" onClick="processReportShoppingProvider();">Buscar <span class="glyphicon glyphicon-search"></span>
      						</button>
      					</td>
      					<td>
      						<button class="btn btn-success" onClick="processReportShoppingProviderExcel();">Excel <span class="glyphicon glyphicon-arrow-down"></span>
      						</button>
      					</td>
      				</tr>
      			</table>
      			<br>
      			<table class="table table-bordered table-condensed table-hover sortable">
	        		<thead>
	        			<tr>
	        				<th width="100">Comprobante</th>
	        				<th width="120">Serie-Numero</th>
	        				<th width="90">Fecha</th>
	        				<th>Usuario</th>
	        				<th>RUC</th>
	        				<th>Proveedor</th>
	        				<th>Tipo de pago</th>
	        				<th width="90">Subtotal</th>
	        				<th width="90">Descuento</th>
	        				<th width="90">IGV</th>
	        				<th width="90">Total</th>
	        			</tr>
	        		</thead>
	        		<tbody id="dataRecordSale"></tbody>
	        	</table>
	        	<script>
	        		$("#sale-customer-name").autocomplete({
						source: baseUrl+"report/searchProviderName",
				        select: function(event, ui){
				            var id = ui.item.id;
				            $("#customer").val(id);
				        }
				    });
	        	</script>';
	    echo $html;	
	}

	public function reportSalesUser(){
		$html = '<h3>Ventas por usuario</h3>
				 <table>
      				<tr>
      					<td style="padding-right:10px;">
      						<div class="input-group">
							  	<span class="input-group-addon">
							  		Usuario
							  	</span>
							  	<input type="text" class="form-control" style="width:100px;"  onkeyup="searchUserData(this.value);" id="customer">
							</div>
						</td>
      					<td style="padding-right:10px;">
      						<div class="input-group">
							  	<span class="input-group-addon">
							  		Nombre
							  	</span>
							  	<input type="text" class="form-control" id="sale-customer-name" style="width:180px;" readonly>
							</div>
						</td>
      					<td style="padding-right:10px;">
      						<div class="input-group">
							  	<span class="input-group-addon">
							  		Desde
							  	</span>
							  	<input type="date" name="sale-from" class="form-control" style="width:150px;" value="'.date('Y-m-d').'" required>
							</div>
						</td>
      					<td style="padding-right:10px;">
      						<div class="input-group">
							  	<span class="input-group-addon">
							  		Hasta
							  	</span>
							  	<input type="date" name="sale-to" class="form-control" style="width:150px;" value="'.date('Y-m-d').'" required>
							</div>
      					</td>
      					<td style="padding-right:10px;">
      						<div class="input-group">
							  	<span class="input-group-addon">
							  		Moneda
							  	</span>
							  	<select name="currency" class="form-control" style="width:150px;" required>
									<option value="PEN">Nuevos Soles</option>
									<option value="DOL">Dolares Americanos</option>
							  	</select>
							</div>
      					</td>
      					<td style="padding-right:10px;">
      						<button type="button" class="btn btn-info" onClick="processReportSalesUser();">Buscar <span class="glyphicon glyphicon-search"></span>
      						</button>
      					</td>
      					<td>
      						<button class="btn btn-success" onClick="processReportSalesUserExcel();">Excel <span class="glyphicon glyphicon-arrow-down"></span>
      						</button>
      					</td>
      				</tr>
      			</table>
      			<br>
      			<table class="table table-bordered table-condensed table-hover sortable">
	        		<thead>
	        			<tr>
	        				<th width="100">Comprobante</th>
	        				<th width="120">Serie-Numero</th>
	        				<th width="90">Fecha</th>
	        				<th>DNI</th>
	        				<th>Cliente</th>
	        				<th>Tipo de pago</th>
	        				<th>Pagado</th>
	        				<th width="90">Subtotal</th>
	        				<th width="90">Descuento</th>
	        				<th width="90">IGV</th>
	        				<th width="90">Total</th>
	        			</tr>
	        		</thead>
	        		<tbody id="dataRecordSale"></tbody>
	        	</table>';
	    echo $html;
	}

	public function reportEmployee(){
		$html = '<h3>Resumen de empleados</h3>
				 <table>
      				<tr>
      					<td style="padding-right:10px;">
      						<div class="input-group">
							  	<span class="input-group-addon">
							  		Desde
							  	</span>
							  	<input type="date" name="emp-from" class="form-control" style="width:160px;" value="'.date('Y-m-d').'" required>
							</div>
						</td>
      					<td style="padding-right:10px;">
      						<div class="input-group">
							  	<span class="input-group-addon">
							  		Hasta
							  	</span>
							  	<input type="date" name="emp-to" class="form-control" style="width:160px;" value="'.date('Y-m-d').'" required>
							</div>
      					</td>
      					<td style="padding-right:10px;">
      						<button type="button" class="btn btn-info" onClick="processReportEmployee();">Buscar <span class="glyphicon glyphicon-search"></span>
      						</button>
      					</td>
      					<td>
      						<button class="btn btn-success" onClick="processReportEmployeeExcel();">Excel <span class="glyphicon glyphicon-arrow-down"></span>
      						</button>
      					</td>
      				</tr>
      			</table>
      			<br>
      			<table class="table table-bordered table-condensed table-hover">
	        		<thead>
	        			<tr>
	        				<th>Fecha de registro</th>
	        				<th width="100">DNI</th>
	        				<th>Nombres y Apellidos</th>
	        				<th>Direccion</th>
	        				<th>Telefono</th>
	        				<th>Area</th>
	        				<th>Tipo de empleado</th>
	        				<th>Usuario</th>
	        			</tr>
	        		</thead>
	        		<tbody id="dataRecordSale"></tbody>
	        	</table>';
	    echo $html;
	}

	public function reportUtility(){
		$html = '<h3>Utilidad de productos</h3>
				 <table>
      				<tr>
      					<td style="padding-right:10px;">
      						<button type="button" class="btn btn-info" onClick="processReportUtility();">Buscar <span class="glyphicon glyphicon-search"></span>
      						</button>
      					</td>
      					<td>
      						<button class="btn btn-success" onClick="processReportUtilityExcel();">Excel <span class="glyphicon glyphicon-arrow-down"></span>
      						</button>
      					</td>
      				</tr>
      			</table>
      			<br>
      			<table class="table table-bordered table-condensed table-hover">
	        		<thead>
	        			<tr>
	        				<th width="150">Codigo</th>
	        				<th>Descripcion del producto</th>
	        				<th width="80">Unidad</th>
	        				<th>Marca</th>
	        				<th>Categoria</th>
	        				<th width="100" style="text-align:center;">Precio Compra</th>
	        				<th width="100" style="text-align:center;">Precio Min.</th>
	        				<th width="100" style="text-align:center;">Precio Max.</th>
	        				<th width="100" style="text-align:center;">Precio Venta</th>
	        				<th width="80">Utilidad</th>
	        			</tr>
	        		</thead>
	        		<tbody id="dataRecordSale"></tbody>
	        	</table>';
	    echo $html;
	}

	public function reportCustomers(){
		$html = '<h3>Resumen de clientes</h3>
				 <table>
      				<tr>
      					<td style="padding-right:10px;">
      						<div class="input-group">
							  	<span class="input-group-addon">
							  		Desde
							  	</span>
							  	<input type="date" name="value-from" class="form-control" style="width:160px;" value="'.date('Y-m-d').'" required>
							</div>
						</td>
      					<td style="padding-right:10px;">
      						<div class="input-group">
							  	<span class="input-group-addon">
							  		Hasta
							  	</span>
							  	<input type="date" name="value-to" class="form-control" style="width:160px;" value="'.date('Y-m-d').'" required>
							</div>
      					</td>
      					<td style="padding-right:10px;">
      						<div class="input-group">
							  	<span class="input-group-addon">
							  		Tipo
							  	</span>
							  	<select name="value-type" class="form-control" style="width:200px;" required>
									<option value="1">Todos los clientes</option>
									<option value="2">Clientes habituales</option>
							  	</select>
							</div>
      					</td>
      					<td style="padding-right:10px;">
      						<button type="button" class="btn btn-info" onClick="processReportResumenCustomer();">Buscar <span class="glyphicon glyphicon-search"></span>
      						</button>
      					</td>
      					<td>
      						<button class="btn btn-success" onClick="processReportResumenCustomerExcel();">Excel <span class="glyphicon glyphicon-arrow-down"></span>
      						</button>
      					</td>
      				</tr>
      			</table>
      			<br>
      			<table class="table table-bordered table-condensed table-hover">
	        		<thead>
	        			<tr>
	        				<th width="150">Fecha de Registro</th>
	        				<th width="150">DNI / RUC</th>
	        				<th>Nombre / Razon Social</th>
	        				<th>Direccion</th>
	        				<th>Telefono</th>
	        				<th width="150">Cantidad en Ventas</th>
	        			</tr>
	        		</thead>
	        		<tbody id="dataRecordSale"></tbody>
	        	</table>';
	    echo $html;
	}

	public function reportProvider(){
		$html = '<h3>Resumen de proveedores</h3>
				 <table>
      				<tr>
      					<td style="padding-right:10px;">
      						<div class="input-group">
							  	<span class="input-group-addon">
							  		Desde
							  	</span>
							  	<input type="date" name="value-from" class="form-control" style="width:160px;" value="'.date('Y-m-d').'" required>
							</div>
						</td>
      					<td style="padding-right:10px;">
      						<div class="input-group">
							  	<span class="input-group-addon">
							  		Hasta
							  	</span>
							  	<input type="date" name="value-to" class="form-control" style="width:160px;" value="'.date('Y-m-d').'" required>
							</div>
      					</td>
      					<td style="padding-right:10px;">
      						<div class="input-group">
							  	<span class="input-group-addon">
							  		Tipo
							  	</span>
							  	<select name="value-type" class="form-control" style="width:200px;" required>
									<option value="1">Todos los proveedores</option>
									<option value="2">Proveedores habituales</option>
							  	</select>
							</div>
      					</td>
      					<td style="padding-right:10px;">
      						<button type="button" class="btn btn-info" onClick="processReportResumenProviders();">Buscar <span class="glyphicon glyphicon-search"></span>
      						</button>
      					</td>
      					<td>
      						<button class="btn btn-success" onClick="processReportResumenProvidersExcel();">Excel <span class="glyphicon glyphicon-arrow-down"></span>
      						</button>
      					</td>
      				</tr>
      			</table>
      			<br>
      			<table class="table table-bordered table-condensed table-hover">
	        		<thead>
	        			<tr>
	        				<th width="150">Fecha de Registro</th>
	        				<th width="150">RUC</th>
	        				<th>Nombre / Razon Social</th>
	        				<th>Direccion</th>
	        				<th>Telefono</th>
	        				<th>Correo</th>
	        				<th width="160">Cantidad en Compras</th>
	        			</tr>
	        		</thead>
	        		<tbody id="dataRecordSale"></tbody>
	        	</table>';
	    echo $html;
	}

	public function reportInventary(){
		$html = '<h3>Inventario de productos</h3>
				 <table>
      				<tr>
      					<td style="padding-right:10px;">
      						<div class="input-group">
							  	<span class="input-group-addon">
							  		Categorias
							  	</span>
							  	<select class="form-control" name="sale-type" style="width: 180px;">
							  		<option value="0">Todos los productos</option>';
							  		$sql = $this->product_model->loadCategory();
									foreach($sql as $row){
										$id = $row['categ_id'];
										$string = $row['categ_valor'];
										$html .= '<option value="'.$id.'">'.$string.'</option>';
									}
						$html.= '</select>
							</div>
						</td>
						<td style="padding-right:10px;">
      						<div class="input-group">
							  	<span class="input-group-addon">
							  		Modelo de vista
							  	</span>
							  	<select class="form-control" name="sale-model">
							  		<option value="1">Detallada</option>
							  		<option value="2">Agrupado</option>
							  	</select>
							</div>
						</td>
      					<td style="padding-right:10px;">
      						<div class="input-group">
							  	<span class="input-group-addon">
							  		Desde
							  	</span>
							  	<input type="date" name="sale-from" class="form-control" style="width:155px;" value="'.date('Y-m-d').'" required>
							</div>
						</td>
      					<td style="padding-right:10px;">
      						<div class="input-group">
							  	<span class="input-group-addon">
							  		Hasta
							  	</span>
							  	<input type="date" name="sale-to" class="form-control" style="width:155px;" value="'.date('Y-m-d').'" required>
							</div>
      					</td>
      					<td style="padding-right:10px;">
      						<button type="button" class="btn btn-info" onClick="processReportInventary();">Buscar <span class="glyphicon glyphicon-search"></span>
      						</button>
      					</td>
      					<td>
      						<button class="btn btn-success" onClick="processReportInventaryExcel();">Excel <span class="glyphicon glyphicon-arrow-down"></span>
      						</button>
      					</td>
      				</tr>
      			</table>
      			<div id="spaceInventary" class="spaceInventary"></div>';
	    echo $html;	
	}

	/* Cargas */
	public function processReportSales(){
		$from = $_POST['from'].' 00:00:00'; 
		$to = $_POST['to'].' 23:59:59';
		$currency = $_POST['currency'];
		$dataSearch = array(
			'from' => $from,
			'to' => $to,
			'currency' => $currency
		);
		$recordSale = $this->sale_model->searchSaleByDate2($dataSearch);
		$count = 0;
		$data = '';
		$dataTotal = 0;

		$varFinal1 = 0; 
		$varFinal2 = 0;
		$varFinal3 = 0;
		$varFinal4 = 0;
		foreach($recordSale as $row){
			$count = $count + 1;
			$id = $row['venta_id'];
			$saleType = $this->saleType($row['venta_tipo']);
			$sqlType = $this->sale_model->loadMovementsId($row['movimiento_id']);
			$salePayed = $this->salePayed($row['venta_cancelada'], $row['venta_id']);
			$nameUser = $this->nameUserSale($row['usuario_id']);
			$nameType = '';
			foreach($sqlType as $rowType){
				$nameType = stripslashes($rowType['mov_detalle']);
			}
			$igvPercent = $row['igv_porcentaje'];
			$saleId = $row['venta_serie'].'-'.$row['venta_numero'];
			$saleDate = date('d/m/Y', strtotime($row['venta_fecha']));
			$saleSinIgv = $row['venta_subtotal'];
			$saleDescuento = $row['venta_descuento'];
			$saleIgv = $row['venta_igv'];
			$saleNeto = $row['venta_neto'];
			$cusSql = $this->sale_model->loadCustomerId($row['cliente_id']);
			$cusName = '';
			$cusRuc = '';
			foreach($cusSql as $rowCus) {
				$cusName = stripslashes($rowCus['cli_nombre']);
				$cusRuc = stripslashes($rowCus['cli_documento']);
			}

			$btn = '<button style="background-color:#d9534f; color:white; border:none; padding:5px;" onClick="downloadExcelSale('.$id.');">Detalle</button>';
			
			$data .= '<tr>
						<td>'.$saleType.'</td>
						<td>'.$saleId.'</td>
						<td>'.$saleDate.'</td>
						<td>'.$nameUser.'</td>
						<td>'.$cusRuc.'</td>
						<td>'.$cusName.'</td>
						<td>'.$nameType.'</td>
						<td>'.$salePayed.'</td>
						<td align="right">'.number_format($saleSinIgv, 2).'</td>
						<td align="right">'.number_format($saleDescuento, 2).'</td>
						<td align="right">'.number_format($saleIgv, 2).'</td>
						<td align="right">'.number_format($saleNeto, 2).'</td>
						<td align="center">'.$btn.'</td>
					  </tr>';

			$varFinal1 = $varFinal1 + $saleSinIgv;
			$varFinal2 = $varFinal2 + $saleDescuento;
			$varFinal3 = $varFinal3 + $saleIgv;
			$varFinal4 = $varFinal4 + $saleNeto;
		}


		$data .= '<tr>
					<th style="text-align:right;" colspan="8">Sumatorias: </th>
					<td align="right">'.number_format($varFinal1, 2).'</td>
					<td align="right">'.number_format($varFinal2, 2).'</td>
					<td align="right">'.number_format($varFinal3, 2).'</td>
					<td align="right">'.number_format($varFinal4, 2).'</td>
				  </tr>';

		if($count > 0){
			$files = $data;
		}else{
			$files = '<tr><td colspan="13">No se encontraron resultados.</td></tr>';
		}
		echo $files;
	}

	public function processReportShopping(){
		$from = $_POST['from'].' 00:00:00'; 
		$to = $_POST['to'].' 23:59:59';
		$currency = $_POST['currency'];
		$dataSearch = array(
			'from' => $from,
			'to' => $to,
			'currency' => $currency
		);
		$recordShopping = $this->shopping_model->searchShoppingByDate2($dataSearch);
		$count = 0;
		$data = '';
		$dataTotal = 0;

		$varFinal1 = 0; 
		$varFinal2 = 0;
		$varFinal3 = 0;
		$varFinal4 = 0;

		foreach($recordShopping as $row){
			$count = $count + 1;
			$id = $row['compra_id'];
			$shoppingType = $this->saleType($row['compra_tipo']);
			$nameUser = $this->nameUserSale($row['usuario_id']);
			$sqlType = $this->sale_model->loadMovementsId($row['movimiento_id']);
			$nameType = '';
			foreach($sqlType as $rowType){
				$nameType = stripslashes($rowType['mov_detalle']);
			}
			$igvPercent = $row['igv_porcentaje'];
			$shoppingId = $row['compra_serie'].'-'.$row['compra_numero'];
			$shoppingDate = date('d/m/Y', strtotime($row['compra_fecha']));
			$provSql = $this->shopping_model->loadProviderId($row['proveedor_id']);
			$provName = '';
			foreach($provSql as $rowProv) {
				$provName = stripslashes($rowProv['prov_nombre']);
				$provRuc = $rowProv['prov_documento'];
			}

			$shoppingSinIgv = $row['compra_subtotal'];
			$shoppingDescuento = $row['compra_descuento'];
			$shoppingIgv = $row['compra_igv'];
			$shoppingNeto = $row['compra_neto'];

			$btn = '<button style="background-color:#d9534f; color:white; border:none; padding:5px;" onClick="downloadExcelShopping('.$id.');">Detalle</button>';

			$data .= '<tr>
						<td>'.$shoppingType.'</td>
						<td>'.$shoppingId.'</td>
						<td>'.$shoppingDate.'</td>
						<td>'.$nameUser.'</td>
						<td>'.$provRuc.'</td>
						<td>'.$provName.'</td>
						<td>'.$nameType.'</td>
						<td align="right">'.number_format($shoppingSinIgv, 2).'</td>
						<td align="right">'.number_format($shoppingDescuento, 2).'</td>
						<td align="right">'.number_format($shoppingIgv, 2).'</td>
						<td align="right">'.number_format($shoppingNeto, 2).'</td>
						<td align="center">'.$btn.'</td>
					  </tr>';	

			$varFinal1 = $varFinal1 + $shoppingSinIgv;
			$varFinal2 = $varFinal2 + $shoppingDescuento;
			$varFinal3 = $varFinal3 + $shoppingIgv;
			$varFinal4 = $varFinal4 + $shoppingNeto;
		}

		$data .= '<tr>
					<th style="text-align:right;" colspan="7">Sumatorias: </th>
					<td align="right">'.number_format($varFinal1, 2).'</td>
					<td align="right">'.number_format($varFinal2, 2).'</td>
					<td align="right">'.number_format($varFinal3, 2).'</td>
					<td align="right">'.number_format($varFinal4, 2).'</td>
				  </tr>';

		if($count > 0){
			$files = $data;
		}else{
			$files = '<tr><td colspan="12">No se encontraron resultados.</td></tr>';
		}

		echo $files;
	}

	public function processReportSalesType(){
		$type = $_POST['mov'];
		$from = $_POST['from'].' 00:00:00'; 
		$to = $_POST['to'].' 23:59:59';
		$currency = $_POST['currency'];
		$recordSale = $this->sale_model->searchSaleByType($type, $from, $to, $currency);
		$count = 0;
		$data = '';
		$dataTotal = 0;

		$varFinal1 = 0; 
		$varFinal2 = 0;
		$varFinal3 = 0;
		$varFinal4 = 0;
		foreach($recordSale as $row){
			$count = $count + 1;
			$id = $row['venta_id'];
			$saleType = $this->saleType($row['venta_tipo']);
			$nameUser = $this->nameUserSale($row['usuario_id']);
			$salePayed = $this->salePayed($row['venta_cancelada'], $row['venta_id']);
			$sqlType = $this->sale_model->loadMovementsId($row['movimiento_id']);
			$nameType = '';
			foreach($sqlType as $rowType){
				$nameType = stripslashes($rowType['mov_detalle']);
			}
			$igvPercent = $row['igv_porcentaje'];
			$saleId = $row['venta_serie'].'-'.$row['venta_numero'];
			$saleSinIgv = $row['venta_subtotal'];
			$saleDescuento = $row['venta_descuento'];
			$saleIgv = $row['venta_igv'];
			$saleNeto = $row['venta_neto'];
			$saleDate = date('d/m/Y', strtotime($row['venta_fecha']));
			$cusSql = $this->sale_model->loadCustomerId($row['cliente_id']);
			$cusName = '';
			$cusRuc = '';
			foreach($cusSql as $rowCus) {
				$cusName = stripslashes($rowCus['cli_nombre']);
				$cusRuc = stripslashes($rowCus['cli_documento']);
			}

			$btn = '<button style="background-color:#d9534f; color:white; border:none; padding:5px;" onClick="downloadPDF('.$id.', 1);">PDF</button>';

			$data .= '<tr>
						<td>'.$saleType.'</td>
						<td>'.$saleId.'</td>
						<td>'.$saleDate.'</td>
						<td>'.$nameUser.'</td>
						<td>'.$cusRuc.'</td>
						<td>'.$cusName.'</td>
						<td>'.$nameType.'</td>
						<td>'.$salePayed.'</td>
						<td align="right">'.number_format($saleSinIgv, 2).'</td>
						<td align="right">'.number_format($saleDescuento, 2).'</td>
						<td align="right">'.number_format($saleIgv, 2).'</td>
						<td align="right">'.number_format($saleNeto, 2).'</td>
					  </tr>';

			$varFinal1 = $varFinal1 + $saleSinIgv;
			$varFinal2 = $varFinal2 + $saleDescuento;
			$varFinal3 = $varFinal3 + $saleIgv;
			$varFinal4 = $varFinal4 + $saleNeto;
		}

		$data .= '<tr>
					<th style="text-align:right;" colspan="8">Sumatorias: </th>
					<td align="right">'.number_format($varFinal1, 2).'</td>
					<td align="right">'.number_format($varFinal2, 2).'</td>
					<td align="right">'.number_format($varFinal3, 2).'</td>
					<td align="right">'.number_format($varFinal4, 2).'</td>
				  </tr>';

		if($count > 0){
			$files = $data;
		}else{
			$files = '<tr><td colspan="12">No se encontraron resultados.</td></tr>';
		}

		echo $files;
	}

	public function processReportSalesCustomer(){
		$customer = addslashes($_POST['customer']);
		$from = $_POST['from'].' 00:00:00'; 
		$to = $_POST['to'].' 23:59:59';
		$currency = $_POST['currency'];
		$recordSale = $this->sale_model->searchSaleByCustomer($customer, $from, $to, $currency);
		$count = 0;
		$data = '';
		$dataTotal = 0;

		$varFinal1 = 0; 
		$varFinal2 = 0;
		$varFinal3 = 0;
		$varFinal4 = 0;
		foreach($recordSale as $row){
			$count = $count + 1;
			$id = $row['venta_id'];
			$saleType = $this->saleType($row['venta_tipo']);
			$nameUser = $this->nameUserSale($row['usuario_id']);
			$sqlType = $this->sale_model->loadMovementsId($row['movimiento_id']);
			$nameType = '';
			foreach($sqlType as $rowType){
				$nameType = stripslashes($rowType['mov_detalle']);
			}
			$igvPercent = $row['igv_porcentaje'];
			$saleId = $row['venta_serie'].'-'.$row['venta_numero'];
			$saleDate = date('d/m/Y', strtotime($row['venta_fecha']));
			$saleSinIgv = $row['venta_subtotal'];
			$saleDescuento = $row['venta_descuento'];
			$saleIgv = $row['venta_igv'];
			$saleNeto = $row['venta_neto'];
			$cusSql = $this->sale_model->loadCustomerId($row['cliente_id']);
			$cusName = '';
			$cusRuc = '';
			foreach($cusSql as $rowCus) {
				$cusName = stripslashes($rowCus['cli_nombre']);
				$cusRuc = stripslashes($rowCus['cli_documento']);
			}

			$btn = '<button style="background-color:#d9534f; color:white; border:none; padding:5px;" onClick="downloadPDF('.$id.', 1);">PDF</button>';

			$data .= '<tr>
						<td>'.$saleType.'</td>
						<td>'.$saleId.'</td>
						<td>'.$saleDate.'</td>
						<td>'.$nameUser.'</td>
						<td>'.$cusRuc.'</td>
						<td>'.$cusName.'</td>
						<td>'.$nameType.'</td>
						<td align="right">'.number_format($saleSinIgv, 2).'</td>
						<td align="right">'.number_format($saleDescuento, 2).'</td>
						<td align="right">'.number_format($saleIgv, 2).'</td>
						<td align="right">'.number_format($saleNeto, 2).'</td>
					  </tr>';

			$varFinal1 = $varFinal1 + $saleSinIgv;
			$varFinal2 = $varFinal2 + $saleDescuento;
			$varFinal3 = $varFinal3 + $saleIgv;
			$varFinal4 = $varFinal4 + $saleNeto;
		}

		$data .= '<tr>
					<th style="text-align:right;" colspan="7">Sumatorias: </th>
					<td align="right">'.number_format($varFinal1, 2).'</td>
					<td align="right">'.number_format($varFinal2, 2).'</td>
					<td align="right">'.number_format($varFinal3, 2).'</td>
					<td align="right">'.number_format($varFinal4, 2).'</td>
				  </tr>';


		if($count > 0){
			$files = $data;
		}else{
			$files = '<tr><td colspan="11">No se encontraron resultados.</td></tr>';
		}

		echo $files;
	}

	public function processReportShoppingProvider(){
		$provider = addslashes($_POST['provider']);
		$from = $_POST['from'].' 00:00:00'; 
		$to = $_POST['to'].' 23:59:59';
		$currency = $_POST['currency'];
		$recordShopping = $this->shopping_model->searchShoppingByCustomer($provider, $from, $to, $currency);
		$count = 0;
		$data = '';
		$dataTotal = 0;

		$varFinal1 = 0; 
		$varFinal2 = 0;
		$varFinal3 = 0;
		$varFinal4 = 0;
		foreach($recordShopping as $row){
			$count = $count + 1;
			$id = $row['compra_id'];
			$shoppingType = $this->saleType($row['compra_tipo']);
			$nameUser = $this->nameUserSale($row['usuario_id']);
			$sqlType = $this->sale_model->loadMovementsId($row['movimiento_id']);
			$nameType = '';
			foreach($sqlType as $rowType){
				$nameType = stripslashes($rowType['mov_detalle']);
			}
			$igvPercent = $row['igv_porcentaje'];
			$shoppingId = $row['compra_serie'].'-'.$row['compra_numero'];
			$shoppingDate = date('d/m/Y', strtotime($row['compra_fecha']));
			$provSql = $this->shopping_model->loadProviderId($row['proveedor_id']);
			$provName = '';
			foreach($provSql as $rowProv) {
				$provName = stripslashes($rowProv['prov_nombre']);
				$provRuc = $rowProv['prov_documento'];
			}

			$shoppingSinIgv = $row['compra_subtotal'];
			$shoppingDescuento = $row['compra_descuento'];
			$shoppingIgv = $row['compra_igv'];
			$shoppingNeto = $row['compra_neto'];

			$data .= '<tr>
						<td>'.$shoppingType.'</td>
						<td>'.$shoppingId.'</td>
						<td>'.$shoppingDate.'</td>
						<td>'.$nameUser.'</td>
						<td>'.$provRuc.'</td>
						<td>'.$provName.'</td>
						<td>'.$nameType.'</td>
						<td align="right">'.number_format($shoppingSinIgv, 2).'</td>
						<td align="right">'.number_format($shoppingDescuento, 2).'</td>
						<td align="right">'.number_format($shoppingIgv, 2).'</td>
						<td align="right">'.number_format($shoppingNeto, 2).'</td>
					  </tr>';	

			$varFinal1 = $varFinal1 + $shoppingSinIgv;
			$varFinal2 = $varFinal2 + $shoppingDescuento;
			$varFinal3 = $varFinal3 + $shoppingIgv;
			$varFinal4 = $varFinal4 + $shoppingNeto;
		}

		$data .= '<tr>
					<th style="text-align:right;" colspan="7">Sumatorias: </th>
					<td align="right">'.number_format($varFinal1, 2).'</td>
					<td align="right">'.number_format($varFinal2, 2).'</td>
					<td align="right">'.number_format($varFinal3, 2).'</td>
					<td align="right">'.number_format($varFinal4, 2).'</td>
				  </tr>';

		if($count > 0){
			$files = $data;
		}else{
			$files = '<tr><td colspan="11">No se encontraron resultados.</td></tr>';
		}

		echo $files;
	}

	public function processReportSalesUser(){
		$customer = addslashes($_POST['customer']);
		$from = $_POST['from'].' 00:00:00'; 
		$to = $_POST['to'].' 23:59:59';
		$currency = $_POST['currency'];
		$recordSale = $this->sale_model->searchSaleByUser($customer, $from, $to, $currency);
		$count = 0;
		$data = '';
		$dataTotal = 0;

		$varFinal1 = 0; 
		$varFinal2 = 0;
		$varFinal3 = 0;
		$varFinal4 = 0;
		foreach($recordSale as $row){
			$count = $count + 1;
			$id = $row['venta_id'];
			$saleType = $this->saleType($row['venta_tipo']);
			$nameUser = $this->nameUserSale($row['usuario_id']);
			$salePayed = $this->salePayed($row['venta_cancelada'], $row['venta_id']);
			$sqlType = $this->sale_model->loadMovementsId($row['movimiento_id']);
			$nameType = '';
			foreach($sqlType as $rowType){
				$nameType = stripslashes($rowType['mov_detalle']);
			}
			$igvPercent = $row['igv_porcentaje'];
			$saleId = $row['venta_serie'].'-'.$row['venta_numero'];
			$saleDate = date('d/m/Y', strtotime($row['venta_fecha']));
			$saleSinIgv = $row['venta_subtotal'];
			$saleDescuento = $row['venta_descuento'];
			$saleIgv = $row['venta_igv'];
			$saleNeto = $row['venta_neto'];
			$cusSql = $this->sale_model->loadCustomerId($row['cliente_id']);
			$cusName = '';
			$cusRuc = '';
			foreach($cusSql as $rowCus) {
				$cusName = stripslashes($rowCus['cli_nombre']);
				$cusRuc = stripslashes($rowCus['cli_documento']);
			}

			$btn = '<button style="background-color:#d9534f; color:white; border:none; padding:5px;" onClick="downloadPDF('.$id.', 1);">PDF</button>';

			$data .= '<tr>
						<td>'.$saleType.'</td>
						<td>'.$saleId.'</td>
						<td>'.$saleDate.'</td>
						<td>'.$cusRuc.'</td>
						<td>'.$cusName.'</td>
						<td>'.$nameType.'</td>
						<td>'.$salePayed.'</td>
						<td align="right">'.number_format($saleSinIgv, 2).'</td>
						<td align="right">'.number_format($saleDescuento, 2).'</td>
						<td align="right">'.number_format($saleIgv, 2).'</td>
						<td align="right">'.number_format($saleNeto, 2).'</td>
					  </tr>';

			$varFinal1 = $varFinal1 + $saleSinIgv;
			$varFinal2 = $varFinal2 + $saleDescuento;
			$varFinal3 = $varFinal3 + $saleIgv;
			$varFinal4 = $varFinal4 + $saleNeto;
		}

		$data .= '<tr>
					<th style="text-align:right;" colspan="7">Sumatorias: </th>
					<td align="right">'.number_format($varFinal1, 2).'</td>
					<td align="right">'.number_format($varFinal2, 2).'</td>
					<td align="right">'.number_format($varFinal3, 2).'</td>
					<td align="right">'.number_format($varFinal4, 2).'</td>
				  </tr>';


		if($count > 0){
			$files = $data;
		}else{
			$files = '<tr><td colspan="11">No se encontraron resultados.</td></tr>';
		}

		echo $files;
	}

	public function processReportEmployee(){
		$from = $_POST['from'].' 00:00:00'; 
		$to = $_POST['to'].' 23:59:59';
		$recordEmp = $this->employee_model->searchEmployeeByDate($from, $to);
		$count = 0;
		$data = '';
		$dataTotal = 0;

		foreach ($recordEmp as $row){
			$count = $count + 1;
			$id = $row['emp_id'];
			$reg = $row['emp_registro'];
			$doc = $row['emp_documento'];
			$name = $row['emp_nombre'].' '.$row['emp_apellido'];
			$direc = $row['emp_direccion'];
			$phone = $row['emp_telefono'];
			$temp = $this->employee_model->loadTypeEmployeeId($row['temp_id']);
			$area = $this->employee_model->loadRepAreaId($row['area_id']);

			$usuExists = $this->employee_model->userExists($row['emp_id']);

			if ($usuExists > 0){
				$status = '<font class="text-primary">Si Tiene</font>';
			}else{
				$status = '<font class="text-danger">No Tiene</font>';
			}

			$data .= '<tr>
						<td>'.$reg.'</td>
						<td>'.$doc.'</td>
						<td>'.$name.'</td>
						<td>'.$direc.'</td>
						<td>'.$phone.'</td>
						<td>'.$area.'</td>
						<td>'.$temp.'</td>
						<td>'.$status.'</td>
					  </tr>';
		}

		if($count > 0){
			$files = $data;
		}else{
			$files = '<tr><td colspan="8">No se encontraron resultados.</td></tr>';
		}

		echo $files;
	}

	public function processReportUtility(){
		$recordProd = $this->sale_model->mergeUtility();
		$count = 0;
		$data = '';
		$priceV = '0.00';

		foreach ($recordProd as $row){
			$count = $count + 1;
			$id = $row['prod_id'];
			$code = $row['prod_codigo'];
			$name = $row['prod_nombre'];
			$unit = $row['prod_unidad'];
			$cat = $row['categ_valor'];
			$mark = $row['marca_nombre'];

			$priceC = $row['detalle_precio_compra_gasto'];
			$price1 = $row['detalle_precio_min'];
			$price2 = $row['detalle_precio_max'];
			$priceV = $row['detalle_precio'];

			if($priceV > 0){
				$priceU = $priceV - $priceC;
				$uti = (($priceU / $priceC) * 100);
			}else{
				$priceV = '0.00';
				$uti = '0.00';
			}
			
			$data .= '<tr>
						<td>'.$code.'</td>
						<td>'.$name.'</td>
						<td>'.$unit.'</td>
						<td>'.$mark.'</td>
						<td>'.$cat.'</td>
						<td align="right">'.number_format($priceC, 2).'</td>
						<td align="right">'.number_format($price1, 2).'</td>
						<td align="right">'.number_format($price2, 2).'</td>
						<td align="right">'.number_format($priceV, 2).'</td>
						<td align="right">'.round($uti).'%</td>
					  </tr>';
		}

		if($count > 0){
			$files = $data;
		}else{
			$files = '<tr><td colspan="10">No se encontraron resultados.</td></tr>';
		}

		echo $files;
	}

	public function processReportResumenCustomer(){
		$from = $_POST['from'].' 00:00:00'; 
		$to = $_POST['to'].' 23:59:59';
		$type = $_POST['type'];
		$recordResumen = $this->sale_model->searchCustomerInSale($from, $to, $type);
		$count = 0;
		$data = '';
		$dataTotal = 0;
		foreach($recordResumen as $row){
			$count = $count + 1;
			$id = $row['cliente_id'];
			$reg = $row['cli_registro'];
			$doc = $row['cli_documento'];
			$name = $row['cli_nombre'];
			$direc = $row['cli_direccion'];
			$phone = $row['cli_telefono'];
			$habitual = $row['habitual'];

			$data .= '<tr>
						<td>'.$reg.'</td>
						<td>'.$doc.'</td>
						<td>'.$name.'</td>
						<td>'.$direc.'</td>
						<td>'.$phone.'</td>
						<td align="right">'.$habitual.'</td>
					  </tr>';
		}

		if($count > 0){
			$files = $data;
		}else{
			$files = '<tr><td colspan="6">No se encontraron resultados.</td></tr>';
		}
		echo $files;
	}

	public function processReportResumenProviders(){
		$from = $_POST['from'].' 00:00:00'; 
		$to = $_POST['to'].' 23:59:59';
		$type = $_POST['type'];
		$recordResumen = $this->shopping_model->searchProviderInShopping($from, $to, $type);
		$count = 0;
		$data = '';
		$dataTotal = 0;
		foreach($recordResumen as $row){
			$count = $count + 1;
			$id = $row['proveedor_id'];
			$reg = $row['prov_registro'];
			$doc = $row['prov_documento'];
			$name = $row['prov_nombre'];
			$direc = $row['prov_direccion'];
			$phone = $row['prov_telefono'];
			$mail = $row['prov_correo'];
			$habitual = $row['habitual'];

			$data .= '<tr>
						<td>'.$reg.'</td>
						<td>'.$doc.'</td>
						<td>'.$name.'</td>
						<td>'.$direc.'</td>
						<td>'.$phone.'</td>
						<td>'.$mail.'</td>
						<td align="right">'.$habitual.'</td>
					  </tr>';
		}

		if($count > 0){
			$files = $data;
		}else{
			$files = '<tr><td colspan="7">No se encontraron resultados.</td></tr>';
		}
		echo $files;
	}

	public function processReportInventary(){
		$type = $_POST['type']; 
		$model = $_POST['model']; 
		$from = $_POST['from']; 
		$to = $_POST['to'];	
		if($model == 1){
			$html = $this->inventaryDetailed($type, $from, $to);
		}else if($model == 2){
			$html = $this->inventaryGrouped($type, $from, $to);
		}
		echo $html;
	}

	public function inventaryGrouped($type, $from, $to){
		if ($type == 0){
			$sql = $this->product_model->loadProductsAll();
		}else if($type > 0){	
			$sql = $this->product_model->loadProductsAllType($type);
		}
		$from = date('Y-m-d', strtotime($from));
		$to = date('Y-m-d', strtotime($to));
		$nDays = $this->restaFechasDias($to, $from);
		$status = 0;
		$html = '<br>
				 <table border="1" class="table table-condensed table-bordered table-hover sortable" width="100%">
			     	<thead>
				     	<tr>
				     		<th rowspan="2" style="vertical-align:middle;">Codigo</th>
				     		<th rowspan="2" style="vertical-align:middle;">Producto</th>
				     		<th rowspan="2" style="vertical-align:middle;">Unidad</th>
				     		<th rowspan="2" style="vertical-align:middle;">Anterior</th>
				     		<th colspan="'.($nDays+1).'" style="text-align:center;">Saldo por dia entre '.date('d/m/Y', strtotime($from)).' y '.date('d/m/Y', strtotime($to)).'</th>
				     		<th rowspan="2" style="vertical-align:middle;">Saldo</th>
				     	</tr>
				     	<tr>';
		for($i = $from; $i <= $to; $i = date("Y-m-d", strtotime($i ."+ 1 days"))){
			$fexa = date('d', strtotime($i));
			$html .= '<th>'.$fexa.'</th>';
		}

		$html .= '</tr>
		  	      </thead>
				  <tbody>';

		foreach($sql as $row){
			$id = $row['prod_id'];
			$n = $this->product_model->verifyProductInventary($id, $from, $to);
			if($n > 0){
				$status = 1;
				$html .= $this->processReportKardexGrouped($id, $from, $to, $type);
			}
		}

		if($status > 0){
			return $html;
		}else{
			return '<br><center><p>...No se encontraron resultados...</p></center>';
		}
	}

	/* Exportar en Excel */
	public function processReportSalesExcel($from, $to, $currency){
		$from = $from.' 00:00:00'; 
		$to = $to.' 23:59:59';
		$dataSearch = array(
			'from' => $from,
			'to' => $to,
			'currency' => $currency
		);
		$recordSale = $this->sale_model->searchSaleByDate2($dataSearch);
		$count = 0;
		$data = '';
		$dataTotal = 0;

		$varFinal1 = 0; 
		$varFinal2 = 0;
		$varFinal3 = 0;
		$varFinal4 = 0;
		foreach($recordSale as $row){
			$count = $count + 1;
			$id = $row['venta_id'];
			$saleType = $this->saleType($row['venta_tipo']);
			$nameUser = $this->nameUserSale($row['usuario_id']);
			$salePayed = $this->salePayed($row['venta_cancelada'], $row['venta_id']);
			$sqlType = $this->sale_model->loadMovementsId($row['movimiento_id']);
			$nameType = '';
			foreach($sqlType as $rowType){
				$nameType = stripslashes($rowType['mov_detalle']);
			}
			$igvPercent = $row['igv_porcentaje'];
			$saleId = $row['venta_serie'].'-'.$row['venta_numero'];
			$saleDate = date('d/m/Y', strtotime($row['venta_fecha']));
			$saleSinIgv = $row['venta_subtotal'];
			$saleDescuento = $row['venta_descuento'];
			$saleIgv = $row['venta_igv'];
			$saleNeto = $row['venta_neto'];
			$cusSql = $this->sale_model->loadCustomerId($row['cliente_id']);
			$cusName = '';
			$cusRuc = '';
			foreach($cusSql as $rowCus) {
				$cusName = stripslashes($rowCus['cli_nombre']);
				$cusRuc = stripslashes($rowCus['cli_documento']);
			}

			if($row['movimiento_id'] == 1){
				$expired = '-';
				$saleStatus = '-';
			}else if($row['movimiento_id'] == 2){
				$nDays = $this->sale_model->nDaysCredit($row['credito_id']);
				$saleExpired = strtotime('+'.$nDays.' day', strtotime($row['venta_fecha']));
				$expired = date('Y-m-d', $saleExpired);
				$today = date('Y-m-d');
				$saleStatus = $this->restaFechasDias($expired, $today);
				if($saleStatus < 0){
					$saleStatus = '<font class="text-danger">Expirado</font>';
				}else{
					if($row['venta_cancelada'] == 0){
						$saleStatus = '<font class="text-primary">Deuda</font>';
					}else{
						$saleStatus = '<font class="text-success">Pagado</font>';
					}
				}
				$expired = date('d/m/Y', strtotime($expired));
			}

			$data .= '<tr>
						<td>'.$saleType.'</td>
						<td>'.$saleId.'</td>
						<td>'.$saleDate.'</td>
						<td>'.$nameUser.'</td>
						<td>'.$cusRuc.'</td>
						<td>'.$cusName.'</td>
						<td>'.$nameType.'</td>
						<td>'.$salePayed.'</td>
						<td>'.$expired.'</td>
						<td>'.$saleStatus.'</td>
						<td align="right">'.number_format($saleSinIgv, 2).'</td>
						<td align="right">'.number_format($saleDescuento, 2).'</td>
						<td align="right">'.number_format($saleIgv, 2).'</td>
						<td align="right">'.number_format($saleNeto, 2).'</td>
					  </tr>';

			$varFinal1 = $varFinal1 + $saleSinIgv;
			$varFinal2 = $varFinal2 + $saleDescuento;
			$varFinal3 = $varFinal3 + $saleIgv;
			$varFinal4 = $varFinal4 + $saleNeto;
		}

		$data .= '<tr>
					<th style="text-align:right;" colspan="10">Sumatorias: </th>
					<td align="right">'.number_format($varFinal1, 2).'</td>
					<td align="right">'.number_format($varFinal2, 2).'</td>
					<td align="right">'.number_format($varFinal3, 2).'</td>
					<td align="right">'.number_format($varFinal4, 2).'</td>
				  </tr>';

		if($count > 0){
			$files = $data;
		}else{
			$files = '<tr><td colspan="14">No se encontraron resultados.</td></tr>';
		}

		$from = date('d/m/Y', strtotime($from));
		$to = date('d/m/Y', strtotime($to));
		$array = array(
			'from' => $from,
			'to' => $to,
			'data' => $files
		);
		$this->load->view('reportSalesExcel', $array);
	}

	public function processReportShoppingExcel($from, $to, $currency){
		$from = $from.' 00:00:00'; 
		$to = $to.' 23:59:59';
		$dataSearch = array(
			'from' => $from,
			'to' => $to,
			'currency' => $currency
		);
		$recordShopping = $this->shopping_model->searchShoppingByDate2($dataSearch);
		$count = 0;
		$data = '';
		$dataTotal = 0;

		$varFinal1 = 0; 
		$varFinal2 = 0;
		$varFinal3 = 0;
		$varFinal4 = 0;
		foreach($recordShopping as $row){
			$count = $count + 1;
			$id = $row['compra_id'];
			$shoppingType = $this->saleType($row['compra_tipo']);
			$nameUser = $this->nameUserSale($row['usuario_id']);
			$sqlType = $this->sale_model->loadMovementsId($row['movimiento_id']);
			$nameType = '';
			foreach($sqlType as $rowType){
				$nameType = stripslashes($rowType['mov_detalle']);
			}
			$igvPercent = $row['igv_porcentaje'];
			$shoppingId = $row['compra_serie'].'-'.$row['compra_numero'];
			$shoppingDate = date('d/m/Y', strtotime($row['compra_fecha']));
			$provSql = $this->shopping_model->loadProviderId($row['proveedor_id']);
			$provName = '';
			foreach($provSql as $rowProv) {
				$provName = stripslashes($rowProv['prov_nombre']);
				$provRuc = $rowProv['prov_documento'];
			}

			if($row['movimiento_id'] == 1){
				$expired = '-';
				$shoppingStatus = '-';
			}else if($row['movimiento_id'] == 2){
				$nDays = $this->sale_model->nDaysCredit($row['credito_id']);
				$shoppingExpired = strtotime('+'.$nDays.' day', strtotime($row['compra_fecha']));
				$expired = date('Y-m-d', $shoppingExpired);
				$today = date('Y-m-d');
				$shoppingStatus = $this->restaFechasDias($expired, $today);
				if($shoppingStatus < 0){
					$shoppingStatus = '<font class="text-danger">Expirado</font>';
				}else{
					$shoppingStatus = '<font class="text-primary">Deuda</font>';
				}
				$expired = date('d/m/Y', strtotime($expired));
			}

			$shoppingSinIgv = $row['compra_subtotal'];
			$shoppingDescuento = $row['compra_descuento'];
			$shoppingIgv = $row['compra_igv'];
			$shoppingNeto = $row['compra_neto'];

			$data .= '<tr>
						<td>'.$shoppingType.'</td>
						<td>'.$shoppingId.'</td>
						<td>'.$shoppingDate.'</td>
						<td>'.$nameUser.'</td>
						<td>'.$provRuc.'</td>
						<td>'.$provName.'</td>
						<td>'.$nameType.'</td>
						<td align="right">'.number_format($shoppingSinIgv, 2).'</td>
						<td align="right">'.number_format($shoppingDescuento, 2).'</td>
						<td align="right">'.number_format($shoppingIgv, 2).'</td>
						<td align="right">'.number_format($shoppingNeto, 2).'</td>
					  </tr>';	

			$varFinal1 = $varFinal1 + $shoppingSinIgv;
			$varFinal2 = $varFinal2 + $shoppingDescuento;
			$varFinal3 = $varFinal3 + $shoppingIgv;
			$varFinal4 = $varFinal4 + $shoppingNeto;
		}

		$data .= '<tr>
					<th style="text-align:right;" colspan="7">Sumatorias: </th>
					<td align="right">'.number_format($varFinal1, 2).'</td>
					<td align="right">'.number_format($varFinal2, 2).'</td>
					<td align="right">'.number_format($varFinal3, 2).'</td>
					<td align="right">'.number_format($varFinal4, 2).'</td>
				  </tr>';

		if($count > 0){
			$files = $data;
		}else{
			$files = '<tr><td colspan="11">No se encontraron resultados.</td></tr>';
		}

		$from = date('d/m/Y', strtotime($from));
		$to = date('d/m/Y', strtotime($to));
		$array = array(
			'from' => $from,
			'to' => $to,
			'data' => $files
		);
		$this->load->view('reportShoppingExcel', $array);
	}

	public function processReportSalesTypeExcel($type, $from, $to, $currency){
		$from = $from.' 00:00:00'; 
		$to = $to.' 23:59:59';
		$recordSale = $this->sale_model->searchSaleByType($type, $from, $to, $currency);
		$count = 0;
		$data = '';
		$dataTotal = 0;

		$subtotalSD = 0;
      	$totalIV = 0;
      	$totalDesc = 0;
      	$totalCD = 0;
		foreach($recordSale as $row){
			$count = $count + 1;
			$id = $row['venta_id'];
			$saleType = $this->saleType($row['venta_tipo']);
			$nameUser = $this->nameUserSale($row['usuario_id']);
			$salePayed = $this->salePayed($row['venta_cancelada'], $row['venta_id']);
			$sqlType = $this->sale_model->loadMovementsId($row['movimiento_id']);
			$nameType = '';
			foreach($sqlType as $rowType){
				$nameType = stripslashes($rowType['mov_detalle']);
			}
			$igvPercent = $row['igv_porcentaje'];
			$saleId = $row['venta_serie'].'-'.$row['venta_numero'];
			$saleSinIgv = $row['venta_subtotal'];
			$saleDescuento = $row['venta_descuento'];
			$saleIgv = $row['venta_igv'];
			$saleNeto = $row['venta_neto'];
			$saleDate = date('d/m/Y', strtotime($row['venta_fecha']));
			$cusSql = $this->sale_model->loadCustomerId($row['cliente_id']);
			$cusName = '';
			$cusRuc = '';
			foreach($cusSql as $rowCus) {
				$cusName = stripslashes($rowCus['cli_nombre']);
				$cusRuc = stripslashes($rowCus['cli_documento']);
			}

			if($row['movimiento_id'] == 1){
				$expired = '-';
				$saleStatus = '-';
			}else if($row['movimiento_id'] == 2){
				$nDays = $this->sale_model->nDaysCredit($row['credito_id']);
				$saleExpired = strtotime('+'.$nDays.' day', strtotime($row['venta_fecha']));
				$expired = date('Y-m-d', $saleExpired);
				$today = date('Y-m-d');
				$saleStatus = $this->restaFechasDias($expired, $today);
				if($saleStatus < 0){
					$saleStatus = '<font class="text-danger">Expirado</font>';
				}else{
					if($row['venta_cancelada'] == 0){
						$saleStatus = '<font class="text-primary">Deuda</font>';
					}else{
						$saleStatus = '<font class="text-success">Pagado</font>';
					}
				}
				$expired = date('d/m/Y', strtotime($expired));
			}

			$data .= '<tr>
						<td>'.$saleType.'</td>
						<td>'.$saleId.'</td>
						<td>'.$saleDate.'</td>
						<td>'.$nameUser.'</td>
						<td>'.$cusRuc.'</td>
						<td>'.$cusName.'</td>
						<td>'.$nameType.'</td>
						<td>'.$salePayed.'</td>
						<td>'.$expired.'</td>
						<td>'.$saleStatus.'</td>
						<td align="right">'.number_format($saleSinIgv, 2).'</td>
						<td align="right">'.number_format($saleDescuento, 2).'</td>
						<td align="right">'.number_format($saleIgv, 2).'</td>
						<td align="right">'.number_format($saleNeto, 2).'</td>
					  </tr>';

			$varFinal1 = $varFinal1 + $saleSinIgv;
			$varFinal2 = $varFinal2 + $saleDescuento;
			$varFinal3 = $varFinal3 + $saleIgv;
			$varFinal4 = $varFinal4 + $saleNeto;
		}

		$data .= '<tr>
					<th style="text-align:right;" colspan="10">Sumatorias: </th>
					<td align="right">'.number_format($varFinal1, 2).'</td>
					<td align="right">'.number_format($varFinal2, 2).'</td>
					<td align="right">'.number_format($varFinal3, 2).'</td>
					<td align="right">'.number_format($varFinal4, 2).'</td>
				  </tr>';

		if($count > 0){
			$files = $data;
		}else{
			$files = '<tr><td colspan="14">No se encontraron resultados.</td></tr>';
		}

		$sqlMov = $this->sale_model->loadMovementsId($type);
	    $movName = '';
	    foreach($sqlMov as $rowMov){
	    	$movName = $rowMov['mov_detalle'];
	    }

		$array = array(
			'movName' => $movName,
			'data' => $files
		);
		$this->load->view('reportSalesTypeExcel', $array);
	}

	public function processReportSalesCustomerExcel($customer, $from, $to, $currency){
		$from = $from.' 00:00:00'; 
		$to = $to.' 23:59:59';
		$recordSale = $this->sale_model->searchSaleByCustomer($customer, $from, $to, $currency);
		$count = 0;
		$data = '';
		$dataTotal = 0;

		$varFinal1 = 0; 
		$varFinal2 = 0;
		$varFinal3 = 0;
		$varFinal4 = 0;
		$cusNameGeneral = '';

		foreach($recordSale as $row){
			$count = $count + 1;
			$id = $row['venta_id'];
			$saleType = $this->saleType($row['venta_tipo']);
			$nameUser = $this->nameUserSale($row['usuario_id']);
			$salePayed = $this->salePayed($row['venta_cancelada'], $row['venta_id']);
			$sqlType = $this->sale_model->loadMovementsId($row['movimiento_id']);
			$nameType = '';
			foreach($sqlType as $rowType){
				$nameType = stripslashes($rowType['mov_detalle']);
			}
			$igvPercent = $row['igv_porcentaje'];
			$saleId = $row['venta_serie'].'-'.$row['venta_numero'];
			$saleDate = date('d/m/Y', strtotime($row['venta_fecha']));
			$saleSinIgv = $row['venta_subtotal'];
			$saleDescuento = $row['venta_descuento'];
			$saleIgv = $row['venta_igv'];
			$saleNeto = $row['venta_neto'];
			$cusSql = $this->sale_model->loadCustomerId($row['cliente_id']);
			$cusName = '';
			$cusRuc = '';
			foreach($cusSql as $rowCus) {
				$cusNameGeneral = stripslashes($rowCus['cli_nombre']);
				$cusName = stripslashes($rowCus['cli_nombre']);
				$cusRuc = stripslashes($rowCus['cli_documento']);
			}

			$data .= '<tr>
						<td>'.$saleType.'</td>
						<td>'.$saleId.'</td>
						<td>'.$saleDate.'</td>
						<td>'.$nameUser.'</td>
						<td>'.$cusRuc.'</td>
						<td>'.$cusName.'</td>
						<td>'.$nameType.'</td>
						<td align="right">'.number_format($saleSinIgv, 2).'</td>
						<td align="right">'.number_format($saleDescuento, 2).'</td>
						<td align="right">'.number_format($saleIgv, 2).'</td>
						<td align="right">'.number_format($saleNeto, 2).'</td>
					  </tr>';

			$varFinal1 = $varFinal1 + $saleSinIgv;
			$varFinal2 = $varFinal2 + $saleDescuento;
			$varFinal3 = $varFinal3 + $saleIgv;
			$varFinal4 = $varFinal4 + $saleNeto;
		}

		$data .= '<tr>
					<th style="text-align:right;" colspan="7">Sumatorias: </th>
					<td align="right">'.number_format($varFinal1, 2).'</td>
					<td align="right">'.number_format($varFinal2, 2).'</td>
					<td align="right">'.number_format($varFinal3, 2).'</td>
					<td align="right">'.number_format($varFinal4, 2).'</td>
				  </tr>';

		if($count > 0){
			$files = $data;
		}else{
			$files = '<tr><td colspan="12">No se encontraron resultados.</td></tr>';
		}

		$array = array(
			'cusNameGeneral' => $cusNameGeneral,
			'data' => $files
		);
		$this->load->view('reportSalesCustomerExcel', $array);
	}

	public function processReportShoppingProviderExcel($provider, $from, $to, $currency){
		$from = $from.' 00:00:00'; 
		$to = $to.' 23:59:59';
		$recordSale = $this->shopping_model->searchShoppingByCustomer($provider, $from, $to, $currency);
		$count = 0;
		$data = '';
		$dataTotal = 0;

		$varFinal1 = 0; 
		$varFinal2 = 0;
		$varFinal3 = 0;
		$varFinal4 = 0;
		$cusNameGeneral = '';
		foreach($recordSale as $row){
			$count = $count + 1;
			$id = $row['compra_id'];
			$saleType = $this->saleType($row['compra_tipo']);
			$nameUser = $this->nameUserSale($row['usuario_id']);
			$sqlType = $this->sale_model->loadMovementsId($row['movimiento_id']);
			$nameType = '';
			foreach($sqlType as $rowType){
				$nameType = stripslashes($rowType['mov_detalle']);
			}
			$igvPercent = $row['igv_porcentaje'];
			$saleId = $row['compra_serie'].'-'.$row['compra_numero'];
			$saleDate = date('d/m/Y', strtotime($row['compra_fecha']));
			$provSql = $this->shopping_model->loadProviderId($row['proveedor_id']);
			$provName = '';
			foreach($provSql as $rowProv) {
				$provName = stripslashes($rowProv['prov_nombre']);
				$provNameGeneral = stripslashes($rowProv['prov_nombre']);
				$provRuc = $rowProv['prov_documento'];
			}

			if($row['movimiento_id'] == 1){
				$expired = '-';
				$saleStatus = '-';
			}else if($row['movimiento_id'] == 2){
				$nDays = $this->sale_model->nDaysCredit($row['credito_id']);
				$saleExpired = strtotime('+'.$nDays.' day', strtotime($row['compra_fecha']));
				$expired = date('Y-m-d', $saleExpired);
				$today = date('Y-m-d');
				$saleStatus = $this->restaFechasDias($expired, $today);
				if($saleStatus < 0){
					$saleStatus = '<font class="text-danger">Expirado</font>';
				}else{
					$saleStatus = '<font class="text-primary">Deuda</font>';
				}
				$expired = date('d/m/Y', strtotime($expired));
			}

			$saleSinIgv = $row['compra_subtotal'];
			$saleDescuento = $row['compra_descuento'];
			$saleIgv = $row['compra_igv'];
			$saleNeto = $row['compra_neto'];

			$data .= '<tr>
						<td>'.$saleType.'</td>
						<td>'.$saleId.'</td>
						<td>'.$saleDate.'</td>
						<td>'.$nameUser.'</td>
						<td>'.$provRuc.'</td>
						<td>'.$provName.'</td>
						<td>'.$nameType.'</td>
						<td align="right">'.number_format($saleSinIgv, 2).'</td>
						<td align="right">'.number_format($saleDescuento, 2).'</td>
						<td align="right">'.number_format($saleIgv, 2).'</td>
						<td align="right">'.number_format($saleNeto, 2).'</td>
					  </tr>';	

			$varFinal1 = $varFinal1 + $saleSinIgv;
			$varFinal2 = $varFinal2 + $saleDescuento;
			$varFinal3 = $varFinal3 + $saleIgv;
			$varFinal4 = $varFinal4 + $saleNeto;
		}

		$data .= '<tr>
					<th style="text-align:right;" colspan="7">Sumatorias: </th>
					<td align="right">'.number_format($varFinal1, 2).'</td>
					<td align="right">'.number_format($varFinal2, 2).'</td>
					<td align="right">'.number_format($varFinal3, 2).'</td>
					<td align="right">'.number_format($varFinal4, 2).'</td>
				  </tr>';

		if($count > 0){
			$files = $data;
		}else{
			$files = '<tr><td colspan="11">No se encontraron resultados.</td></tr>';
		}

		$array = array(
			'cusNameGeneral' => $provNameGeneral,
			'data' => $files
		);
		$this->load->view('reportShoppingProviderExcel', $array);
	}

	public function processReportSalesUserExcel($customer, $from, $to, $currency){
		$from = $from.' 00:00:00'; 
		$to = $to.' 23:59:59';
		$recordSale = $this->sale_model->searchSaleByUser($customer, $from, $to, $currency);
		$count = 0;
		$data = '';
		$dataTotal = 0;

		$varFinal1 = 0; 
		$varFinal2 = 0;
		$varFinal3 = 0;
		$varFinal4 = 0;
		$cusNameGeneral = '';

		foreach($recordSale as $row){
			$count = $count + 1;
			$id = $row['venta_id'];
			$saleType = $this->saleType($row['venta_tipo']);
			$salePayed = $this->salePayed($row['venta_cancelada'], $row['venta_id']);
			$nameUser = $this->nameUserSale($row['usuario_id']);
			$sqlType = $this->sale_model->loadMovementsId($row['movimiento_id']);
			$nameType = '';
			foreach($sqlType as $rowType){
				$nameType = stripslashes($rowType['mov_detalle']);
			}
			$igvPercent = $row['igv_porcentaje'];
			$saleId = $row['venta_serie'].'-'.$row['venta_numero'];
			$saleDate = date('d/m/Y', strtotime($row['venta_fecha']));
			$saleSinIgv = $row['venta_subtotal'];
			$saleDescuento = $row['venta_descuento'];
			$saleIgv = $row['venta_igv'];
			$saleNeto = $row['venta_neto'];
			$cusSql = $this->sale_model->loadCustomerId($row['cliente_id']);
			$cusNameGeneral = $this->nameUserSale($row['usuario_id']);
			$cusName = '';
			$cusRuc = '';
			foreach($cusSql as $rowCus) {
				$cusName = stripslashes($rowCus['cli_nombre']);
				$cusRuc = stripslashes($rowCus['cli_documento']);
			}

			if($row['movimiento_id'] == 1){
				$expired = '-';
				$saleStatus = '-';
			}else if($row['movimiento_id'] == 2){
				$nDays = $this->sale_model->nDaysCredit($row['credito_id']);
				$saleExpired = strtotime('+'.$nDays.' day', strtotime($row['venta_fecha']));
				$expired = date('Y-m-d', $saleExpired);
				$today = date('Y-m-d');
				$saleStatus = $this->restaFechasDias($expired, $today);
				if($saleStatus < 0){
					$saleStatus = '<font class="text-danger">Expirado</font>';
				}else{
					if($row['venta_cancelada'] == 0){
						$saleStatus = '<font class="text-primary">Deuda</font>';
					}else{
						$saleStatus = '<font class="text-success">Pagado</font>';
					}
				}
				$expired = date('d/m/Y', strtotime($expired));
			}

			$data .= '<tr>
						<td>'.$saleType.'</td>
						<td>'.$saleId.'</td>
						<td>'.$saleDate.'</td>
						<td>'.$cusRuc.'</td>
						<td>'.$cusName.'</td>
						<td>'.$nameType.'</td>
						<td>'.$salePayed.'</td>
						<td>'.$expired.'</td>
						<td>'.$saleStatus.'</td>
						<td align="right">'.number_format($saleSinIgv, 2).'</td>
						<td align="right">'.number_format($saleDescuento, 2).'</td>
						<td align="right">'.number_format($saleIgv, 2).'</td>
						<td align="right">'.number_format($saleNeto, 2).'</td>
					  </tr>';

			$varFinal1 = $varFinal1 + $saleSinIgv;
			$varFinal2 = $varFinal2 + $saleDescuento;
			$varFinal3 = $varFinal3 + $saleIgv;
			$varFinal4 = $varFinal4 + $saleNeto;
		}

		$data .= '<tr>
					<th style="text-align:right;" colspan="9">Sumatorias: </th>
					<td align="right">'.number_format($varFinal1, 2).'</td>
					<td align="right">'.number_format($varFinal2, 2).'</td>
					<td align="right">'.number_format($varFinal3, 2).'</td>
					<td align="right">'.number_format($varFinal4, 2).'</td>
				  </tr>';

		if($count > 0){
			$files = $data;
		}else{
			$files = '<tr><td colspan="13">No se encontraron resultados.</td></tr>';
		}

		$array = array(
			'cusNameGeneral' => $cusNameGeneral,
			'data' => $files
		);
		$this->load->view('reportSalesUserExcel', $array);
	}

	public function processReportEmployeeExcel($from, $to){
		$from = $from.' 00:00:00'; 
		$to = $to.' 23:59:59';
		$recordEmp = $this->employee_model->searchEmployeeByDate($from, $to);
		$count = 0;
		$data = '';
		$dataTotal = 0;

		foreach ($recordEmp as $row){
			$count = $count + 1;
			$id = $row['emp_id'];
			$reg = $row['emp_registro'];
			$doc = $row['emp_documento'];
			$name = $row['emp_nombre'].' '.$row['emp_apellido'];
			$direc = $row['emp_direccion'];
			$phone = $row['emp_telefono'];
			$temp = $this->employee_model->loadTypeEmployeeId($row['temp_id']);
			$area = $this->employee_model->loadRepAreaId($row['area_id']);

			$usuExists = $this->employee_model->userExists($row['emp_id']);

			if ($usuExists > 0){
				$status = '<font class="text-primary">Si Tiene</font>';
			}else{
				$status = '<font class="text-danger">No Tiene</font>';
			}

			$data .= '<tr>
						<td>'.$reg.'</td>
						<td>'.$doc.'</td>
						<td>'.$name.'</td>
						<td>'.$direc.'</td>
						<td>'.$phone.'</td>
						<td>'.$area.'</td>
						<td>'.$temp.'</td>
						<td>'.$status.'</td>
					  </tr>';
		}

		if($count > 0){
			$files = $data;
		}else{
			$files = '<tr><td colspan="8">No se encontraron resultados.</td></tr>';
		}

		$from = date('d/m/Y', strtotime($from));
		$to = date('d/m/Y', strtotime($to));
		$array = array(
			'from' => $from,
			'to' => $to,
			'data' => $files
		);
		$this->load->view('reportEmployeeExcel', $array);
	}

	public function processReportUtilityExcel(){
		$recordProd = $this->sale_model->mergeUtility();
		$count = 0;
		$data = '';
		$priceV = '0.00';

		foreach ($recordProd as $row){
			$count = $count + 1;
			$id = $row['prod_id'];
			$code = $row['prod_codigo'];
			$name = $row['prod_nombre'];
			$unit = $row['prod_unidad'];
			$cat = $row['categ_valor'];
			$mark = $row['marca_nombre'];

			$priceC = $row['detalle_precio_compra_gasto'];
			$price1 = $row['detalle_precio_min'];
			$price2 = $row['detalle_precio_max'];
			$priceV = $row['detalle_precio'];

			if($priceV > 0){
				$priceU = $priceV - $priceC;
				$uti = (($priceU / $priceC) * 100);
			}else{
				$priceV = '0.00';
				$uti = '0.00';
			}
			
			$data .= '<tr>
						<td>'.$code.'</td>
						<td>'.$name.'</td>
						<td>'.$unit.'</td>
						<td>'.$mark.'</td>
						<td>'.$cat.'</td>
						<td align="right">'.number_format($priceC, 2).'</td>
						<td align="right">'.number_format($price1, 2).'</td>
						<td align="right">'.number_format($price2, 2).'</td>
						<td align="right">'.number_format($priceV, 2).'</td>
						<td align="right">'.round($uti).'%</td>
					  </tr>';
		}

		if($count > 0){
			$files = $data;
		}else{
			$files = '<tr><td colspan="10">No se encontraron resultados.</td></tr>';
		}

		$array = array(
			'data' => $files
		);
		$this->load->view('reportUtilityExcel', $array);
	}

	public function processReportResumenCustomerExcel($from, $to, $type){
		$from = $from.' 00:00:00'; 
		$to = $to.' 23:59:59';
		$type = $type;
		$recordResumen = $this->sale_model->searchCustomerInSale($from, $to, $type);
		$count = 0;
		$data = '';
		$dataTotal = 0;
		foreach($recordResumen as $row){
			$count = $count + 1;
			$id = $row['cliente_id'];
			$reg = $row['cli_registro'];
			$doc = $row['cli_documento'];
			$name = $row['cli_nombre'];
			$direc = $row['cli_direccion'];
			$phone = $row['cli_telefono'];
			$habitual = $row['habitual'];

			$data .= '<tr>
						<td>'.$reg.'</td>
						<td>'.$doc.'</td>
						<td>'.$name.'</td>
						<td>'.$direc.'</td>
						<td>'.$phone.'</td>
						<td align="right">'.$habitual.'</td>
					  </tr>';
		}

		if ($type == 1){
			$titulo = 'Todos los Clientes';
		}elseif($type == 2){
			$titulo = 'Clientes Habituales';
		}

		if($count > 0){
			$files = $data;
		}else{
			$files = '<tr><td colspan="6">No se encontraron resultados.</td></tr>';
		}
		
		$from = date('d/m/Y', strtotime($from));
		$to = date('d/m/Y', strtotime($to));
		$array = array(
			'from' => $from,
			'to' => $to,
			'titulo' => $titulo,
			'data' => $files
		);

		$this->load->view('reportCustomers', $array);
	}

	public function processReportResumenProvidersExcel($from, $to, $type){
		$from = $from.' 00:00:00'; 
		$to = $to.' 23:59:59';
		$type = $type;
		$recordResumen = $this->shopping_model->searchProviderInShopping($from, $to, $type);
		$count = 0;
		$data = '';
		$dataTotal = 0;
		foreach($recordResumen as $row){
			$count = $count + 1;
			$id = $row['proveedor_id'];
			$reg = $row['prov_registro'];
			$doc = $row['prov_documento'];
			$name = $row['prov_nombre'];
			$direc = $row['prov_direccion'];
			$phone = $row['prov_telefono'];
			$mail = $row['prov_correo'];
			$habitual = $row['habitual'];

			$data .= '<tr>
						<td>'.$reg.'</td>
						<td>'.$doc.'</td>
						<td>'.$name.'</td>
						<td>'.$direc.'</td>
						<td>'.$phone.'</td>
						<td>'.$mail.'</td>
						<td align="right">'.$habitual.'</td>
					  </tr>';
		}

		if ($type == 1){
			$titulo = 'Todos los Proveedores';
		}elseif($type == 2){
			$titulo = 'Proveedores Habituales';
		}

		if($count > 0){
			$files = $data;
		}else{
			$files = '<tr><td colspan="7">No se encontraron resultados.</td></tr>';
		}

		$from = date('d/m/Y', strtotime($from));
		$to = date('d/m/Y', strtotime($to));
		
		$array = array(
			'from' => $from,
			'to' => $to,
			'titulo' => $titulo,
			'data' => $files
		);
		$this->load->view('reportProviders', $array);
	}

	public function processReportInventaryExcel($type, $model, $from, $to){
		if($model == 1){
			$html = $this->inventaryDetailed($type, $from, $to);
		}else if($model == 2){
			$html = $this->inventaryGrouped($type, $from, $to);
		}

		$from = date('d/m/Y', strtotime($from));
		$to = date('d/m/Y', strtotime($to));
		$array = array(
			'from' => $from,
			'to' => $to,
			'data' => $html,
			'type' => $type
		);
		$this->load->view('reportInventaryExcel', $array);
	}

	public function downloadExcelSale($id){
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
		        			<th style="text-align:right;">RUC: </th>
		        			<td style="text-align:left;">'.$rucCustomer.'</td>
		        		</tr>
		        		<tr>
		        			<th style="text-align:right;">Nombre: </th>
		        			<td style="text-align:left;">'.$customer.'</td>
		        		</tr>
		        	</table><br>';
		}

		$recordSaleDetail = $this->sale_model->loadRecordSaleDetail($id);
		$data .= '<table border="1" cellpadding="4">
					<thead>
						<tr bgcolor="#E6E6E6">
							<th width="120">Codigo</th>
							<th>Producto</th>
							<th width="60">Unidad</th>
							<th>Precio Venta</th>
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
				  	<td align="right"><label>'.$simbol.' '.number_format($saleSinIgv, 2).'</label></td>
				  </tr>
				  <tr>
				  	<th colspan="6" style="text-align:right;">Descuento: </th>
				  	<td align="right"><label>'.$simbol.' '.number_format($saleDescuento, 2).'</label></td>
				  </tr>
				  <tr>
				  	<th colspan="6" style="text-align:right;">IGV '.($igvPercent*100).'%: </th>
				  	<td align="right"><label>'.$simbol.' '.number_format($saleIgv, 2).'</label></td>
				  </tr>
				  <tr>
				  	<th colspan="6" style="text-align:right;">Total: </th>
				  	<td align="right"><label>'.$simbol.' '.number_format($saleNeto, 2).'</label></td>
				  </tr>
				  </tbody></table>';

		$array = array(
			'type' => $saleType,
			'serie' => $sale,
			'date' => $date,
			'data' => $data
		);
		$this->load->view('downloadExcelSale', $array);
	}

	public function downloadExcelShopping($id){
		$recordShopping = $this->shopping_model->loadRecordShoppingId($id);
		$data = '';

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
			
			$igvPercent = $row['igv_porcentaje'];

			$data = '<table width="100%">
		        		<tr>
		        			<th style="text-align:right;">RUC Proveedor: </th>
		        			<td style="text-align:left;">'.$idProvider.'</td>
		        		</tr>
		        		<tr>
		        			<th style="text-align:right;">Nombre: </th>
		        			<td style="text-align:left;">'.$provider.'</td>
		        		</tr>
		        	</table><br>';
		    $shoppingSinIgv = $row['compra_subtotal'];
			$shoppingDescuento = $row['compra_descuento'];
			$shoppingIgv = $row['compra_igv'];
			$shoppingNeto = $row['compra_neto'];
		}

		$recordShoppingDetail = $this->shopping_model->loadRecordShoppingDetail($id);
		$data .= '<table border="1" cellpadding="4">
					<thead>
						<tr bgcolor="#E6E6E6">
							<th width="120">Codigo</th>
							<th>Producto</th>
							<th width="60">Unidad</th>
							<th width="90">Cantidad</th>
							<th>Precio</th>
							<th>Precio por Transporte</th>
							<th>Precio Compra</th>
							<th>% MP</th>
							<th>Precio MP</th>
							<th>% SP</th>
							<th>Precio SP</th>
							<th>% PM</th>
							<th>Precio PM</th>
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
			$transp = $row['detalle_precio_transporte'];
			$priceCompra = $row['detalle_precio_compra'];
			$porc1 = $row['detalle_porc_min'];
			$porc2 = $row['detalle_porc_max'];
			$precio1 = $row['detalle_precio_min'];
			$precio2 = $row['detalle_precio_max'];
			$amount = $row['detalle_cantidad'];
			$desc = $row['detalle_descuento'];
			$unit = $row['detalle_unidad'];
			$subtotal = ($price*$amount)-$desc;
			$data .= '<tr>
						<td>'.$productCode.'</td>
						<td>'.$productName.'</td>
						<td>'.$unit.'</td>
						<td align="right">'.number_format($amount, 2).'</td>
						<td align="right">'.number_format($price, 2).'</td>
						<td align="right">'.number_format($transp, 2).'</td>
						<td align="right">'.number_format($priceCompra, 2).'</td>
						<td align="right">'.$porc1.'%</td>
						<td align="right">'.number_format($precio1, 2).'</td>
						<td align="right">'.$porc2.'%</td>
						<td align="right">'.number_format($precio2, 2).'</td>>
						<td align="right">'.number_format($desc, 2).'</td>
						<td align="right">'.number_format($subtotal, 2).'</td>
					  </tr>';
		}

		$data .= '<tr>
				  	<th colspan="12" style="text-align:right;">Bruto: </th>
				  	<td align="right"><label>S/. '.number_format($shoppingSinIgv, 2).'</label></td>
				  </tr>
				  <tr>
				  	<th colspan="12" style="text-align:right;">Descuento: </th>
				  	<td align="right"><label>S/. '.number_format($shoppingDescuento, 2).'</label></td>
				  </tr>
				  <tr>
				  	<th colspan="12" style="text-align:right;">IGV '.($igvPercent*100).'%: </th>
				  	<td align="right"><label>S/. '.number_format($shoppingIgv, 2).'</label></td>
				  </tr>
				  <tr>
				  	<th colspan="12" style="text-align:right;">Total: </th>
				  	<td align="right"><label>S/. '.number_format($shoppingNeto, 2).'</label></td>
				  </tr>
				  </tbody></table>';

		$array = array(
			'type' => $shoppingType,
			'serie' => $shopping,
			'date' => $date,
			'data' => $data
		);
		$this->load->view('downloadExcelShopping', $array);
	}
}