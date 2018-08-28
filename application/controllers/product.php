<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Product extends CI_Controller{

	public function __construct(){
		parent::__construct();
		session_start();

		if (isset($_SESSION['usu_sv']) OR isset($_SESSION['adm_sv'])){
			/*$this->load->model('setting_model');*/
			$this->load->model('login_model');
			$this->load->model('product_model');
		}else{
			header('Location:'.base_url());
		}
	}

	function index(){
		$this->header();
		$this->load->view('product');
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

	public function maxProduct(){
		$id = $this->product_model->numberProduct();
		$number = $id + 1;
		return $number;
	}

	public function loadCategoryModal(){
		$response1 = $this->product_model->loadCategory();
		$html1 = '';

		foreach ($response1 as $row1) {
			$id1 = $row1['categ_id'];
			$value1 = $row1['categ_valor'];
			$html1.= '<option value="'.$id1.'">'.$value1.'</option>';
		}

		$response2 = $this->product_model->loadMarks();
		$html2 = '';

		foreach ($response2 as $row2) {
			$id2 = $row2['marca_id'];
			$value2 = $row2['marca_nombre'];
			$html2.= '<option value="'.$id2.'">'.$value2.'</option>';
		}

		$array = array(0 => $html1,
					   1 => $html2);

		echo json_encode($array);
	}

	public function loadSubCategoryModal(){
		$id = $_POST['id'];
		$response = $this->product_model->loadSubCategory($id);
		$html = '';

		foreach ($response as $row) {
			$id = $row['subcateg_id'];
			$value = $row['subcateg_valor'];
			$html.= '<option value="'.$id.'">'.$value.'</option>';
		}

		echo $html;
	}

	public function loadCategory(){
		$sql = $this->product_model->loadCategoryTable();
		$html = '';
		foreach ($sql as $row){
			$type = "'category'";
			$id = $row['categ_id'];
			$value = $row['categ_valor'];
			$html .= '<tr id="reg-'.$id.'">
						<td>'.$value.'</td>
						<td><a href="javascript:void(0);" onClick="editPage('.$id.', '.$type.');" class="btn btn-sm btn-default">Editar</a>
						</td>
					</tr>';
		}

		echo $html;
	}

	public function loadCategoryIDS($id, $return){
		$sql = $this->product_model->loadCategorysId($id);
		$html = '';
		foreach ($sql as $row){
			$type = "'category'";
			$id = $row['categ_id'];
			$value = $row['categ_valor'];
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

	public function loadSubCategory(){
		$sql = $this->product_model->loadSubCategoryTable();
		$response = $this->product_model->loadCategory();
		$html = '';
		$opt = '';
		foreach ($sql as $row){
			$type = "'subcategory'";
			$id = $row['subcateg_id'];
			$cate = $row['categ_valor'];
			$value = $row['subcateg_valor'];
			$html .= '<tr id="reg-'.$id.'">
						<td>'.$cate.'</td>
						<td>'.$value.'</td>
						<td><a href="javascript:void(0);" onClick="editPage('.$id.', '.$type.');" class="btn btn-sm btn-default">Editar</a>
						</td>
					</tr>';
		}

		foreach ($response as $row) {
			$id = $row['categ_id'];
			$value = $row['categ_valor'];
			$opt.= '<option value="'.$id.'">'.$value.'</option>';
		}

		$array = array(0 => $html,
					   1 => $opt);
		echo json_encode($array);
	}

	public function loadSubCategoryIDS($id, $return){
		$sql = $this->product_model->loadSubCategoryIds($id);
		$html = '';
		foreach ($sql as $row){
			$type = "'subcategory'";
			$id = $row['subcateg_id'];
			$cate = $row['categ_valor'];
			$value = $row['subcateg_valor'];
			$html .= '<tr id="reg-'.$id.'">
						<td>'.$cate.'</td>
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

	public function start(){
		$result = $this->product_model->start();
		$output = array('data' => array());

		$type = "'product'";
		
		foreach ($result as $row){
				$id = $row['prod_id'];
				$code = $row['prod_codigo'];
				$name = $row['prod_nombre'];
				$unit = $row['prod_unidad'];
				$priceC = $row['prod_precio_compra'];
				$priceV1 = $row['prod_precio_vp1'];
				$priceV2 = $row['prod_precio_vp2'];
				$stockM = $row['prod_stock_min'];
				$stockR = $row['prod_stock_real'];
				$cate = $row['categ_valor'];
				$mark = $row['marca_nombre'];

				if ($stockM > $stockR) {
					$stock = '<span class="label label-danger" style="font-family:Arial; font-size: 12px;">'.$stockR.'</span>';
				}else if($stockM == $stockR){
					$stock = '<span class="label label-warning" style="font-family:Arial; font-size: 12px;">'.$stockR.'</span>';
				}else if($stockM < $stockR){
					$stock = '<span class="label label-success" style="font-family:Arial; font-size: 12px;">'.$stockR.'</span>';
				}

			$action ='
			<a href="javascript: void(0);" onClick="changePrice('.$id.');"><span class="fa fa-money"></span></a>
			<a href="javascript: void(0);" onClick="editPage('.$id.', '.$type.');"><span class="fa fa-edit"></span></a>
			<a href="javascript: void(0);" onClick="deletePage('.$id.', '.$type.');"><span class="fa fa-trash"></span></a>';

			$output['data'][] = array(
									$code,
									$name,
									$mark,
									$cate,
									$unit,
									number_format($priceC, 2),
									number_format($priceV1, 2),
									number_format($priceV2, 2),
									$stock,
									$action
								);
		}
		
		echo json_encode($output);
	}

	public function register(){
		$name = addslashes($_POST['name']);
		$unit = addslashes($_POST['unit']);
		$stockM = $_POST['stockM'];
		$stockR = $_POST['stockR'];
		$cate = $_POST['cate'];
		$mark = $_POST['marks'];

		$number = $this->maxProduct();
		$serie = $this->leftZero(7, $number);
		$code = 'PROD-'.$serie;

		$data = array(
			'code' => $code,
			'name' => $name,
			'unit' => $unit,
			'stockM' => $stockM,
			'stockR' => $stockR,
			'cate' => $cate,
			'mark' => $mark
		);

		$insert = $this->product_model->register($data);
		
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

			$sql = $this->product_model->loadProduct($id);
			$response1 = $this->product_model->loadCategory();
			$response2 = $this->product_model->loadMarca();
			$htmlOpt1 = '';
			$htmlOpt2 = '';
			
			foreach ($sql as $row){
				$name = $row['prod_nombre'];
				$unit = $row['prod_unidad'];
				$stockM = $row['prod_stock_min'];
				$stockR = $row['prod_stock_real'];
				$cate = $row['categ_id'];
				$mark = $row['marca_id'];
			}

			foreach ($response1 as $row1){
				$idOpt1 = $row1['categ_id'];
				$valueOpt1 = $row1['categ_valor'];
				$htmlOpt1.= '<option value="'.$idOpt1.'">'.$valueOpt1.'</option>';
			}

			foreach ($response2 as $row2){
				$idOpt2 = $row2['marca_id'];
				$valueOpt2 = $row2['marca_nombre'];
				$htmlOpt2.= '<option value="'.$idOpt2.'">'.$valueOpt2.'</option>';
			}

			$json = array(0 => $id,
						  1 => $name,
						  2 => $unit,
						  3 => $stockM,
						  4 => $stockR,
						  5 => $htmlOpt1,
						  6 => $cate,
						  7 => $htmlOpt2,
						  8 => $mark
						);
			echo json_encode($json);
		}elseif($type == 'updateData'){
			$id = $_POST['id'];
			$name = addslashes($_POST['name']);
			$unit = addslashes($_POST['unit']);
			$stockM = $_POST['stockM'];
			$stockR = $_POST['stockR'];
			$cate = $_POST['cate'];
			$mark = $_POST['marks'];

			$data = array(
				'id' => $id,
				'name' => $name,
				'unit' => $unit,
				'stockM' => $stockM,
				'stockR' => $stockR,
				'cate' => $cate,
				'mark' => $mark
			);

			$update = $this->product_model->update($data);
			
			if ($update > 0){
				$value = 'Ok';
			}else{
				$value = 'Null';
			}
			$array = array(0 => $value);
			echo json_encode($array);
		}
	}

	public function registerCategory(){
		$name = $_POST['nameC'];
		$html = '';
		$rename = base64_encode($name).time();

		if(!empty($_FILES['file_cat']['name'])){
			$config = [
				'upload_path' => './public/files/categorias',
				'file_name' => $rename,
				'allowed_types' => 'gif|jpg|jpeg|png'
			];

			$this->load->library('upload', $config);

			if ($this->upload->do_upload('file_cat')){
				$data = array('upload_data' => $this->upload->data());

				$array = array(
					'name'		=> $name,
					'archivo'	=> $data['upload_data']['file_name']
				);

				$insert = $this->product_model->registerCategory($array);

				if ($insert > 0){
					$response = 'ok';
					$sql = $this->product_model->loadCategoryId($insert);

					foreach ($sql as $row){
						$type = "'category'";
						$id = $row['categ_id'];
						$value = $row['categ_valor'];
						$html .= '<tr id="reg-'.$id.'">
									<td>'.$value.'</td>
									<td><a href="javascript:void(0);" onClick="editPage('.$id.', '.$type.');" class="btn btn-sm btn-default">Editar</a>
								</td>
								</tr>';
					}
				}else{
					$response = 'null';
					$html = '';
				}
			}else{
				$response = 'error';
				$html = '';
			}
		}else{
			$response = 'null';
			$html = '';
		}
		$array = array(0 => $response,
					   1 => $html);
		echo json_encode($array);
	}

	// public function registerCategory(){
	// 	$name = $_POST['nameC'];
	// 	$html = '';
	// 	$data = array('name' => $name);
	// 	$insert = $this->product_model->registerCategory($data);

	// 	if ($insert > 0){
	// 		$response = 'Ok';
	// 		$sql = $this->product_model->loadCategoryId($insert);

	// 		foreach ($sql as $row){
	// 			$type = "'category'";
	// 			$id = $row['categ_id'];
	// 			$value = $row['categ_valor'];
	// 			$html .= '<tr id="reg-'.$id.'">
	// 						<td>'.$value.'</td>
	// 						<td><a href="javascript:void(0);" onClick="editPage('.$id.', '.$type.');" class="btn btn-sm btn-default">Editar</a>
	// 					</td>
	// 					</tr>';
	// 		}
	// 	}else{
	// 		$response = 'Null';
	// 		$html = '';
	// 	}
	// 	$array = array(0 => $response,
	// 				   1 => $html);
	// 	echo json_encode($array);
	// }

	public function EditionCategory($type){
		if($type == 'bringData'){
			$id = $_POST['id'];

			$sql = $this->product_model->loadCategorysId($id);
			
			foreach ($sql as $row) {
				$name = $row['categ_valor'];
			}

			$json = array(0 => $id,
						  1 => $name
						);
			echo json_encode($json);
		}elseif($type == 'updateData'){
			$id = $_POST['id-cat'];
			$name = addslashes($_POST['nameC']);

			$data = array(
				'id' => $id,
				'name' => $name
			);

			$update = $this->product_model->updateCategory($data);
			
			if ($update > 0){
				$value = 'Ok';
				$html = $this->loadCategoryIDS($id, 'return');
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

	public function delete($id){
		$response = $this->product_model->delete($id);
		echo $response;
	}

	public function loadMark(){
		$sql = $this->product_model->loadMarkTable();
		$html = '';
		foreach ($sql as $row){
			$type = "'mark'";
			$id = $row['marca_id'];
			$value = $row['marca_nombre'];
			$html .= '<tr id="reg-'.$id.'">
						<td>'.$value.'</td>
						<td><a href="javascript:void(0);" onClick="editPage('.$id.', '.$type.');" class="btn btn-sm btn-default">Editar</a>
						</td>
					</tr>';
		}

		echo $html;
	}

	public function registerMark(){
		$name = $_POST['nameM'];
		$html = '';
		$data = array('name' => $name);
		$insert = $this->product_model->registerMark($data);

		if ($insert > 0){
			$response = 'Ok';
			$sql = $this->product_model->loadMarkId($insert);

			foreach ($sql as $row){
				$type = "'marca'";
				$id = $row['marca_id'];
				$value = $row['marca_nombre'];
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

	public function EditionMark($type){
		if($type == 'bringData'){
			$id = $_POST['id'];

			$sql = $this->product_model->loadMarkIDS($id);
			
			foreach ($sql as $row) {
				$name = $row['marca_nombre'];
			}

			$json = array(0 => $id,
						  1 => $name
						);
			echo json_encode($json);
		}elseif($type == 'updateData'){
			$id = $_POST['id-mark'];
			$name = addslashes($_POST['nameM']);

			$data = array(
				'id' => $id,
				'name' => $name
			);

			$update = $this->product_model->updateMark($data);
			
			if ($update > 0){
				$value = 'Ok';
				$html = $this->loadMarkIDS($id, 'return');
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

	public function loadPriceProduct(){
		$id = $_POST['id'];
		$sql = $this->product_model->loadPriceProduct($id);

		foreach ($sql as $row){
			$price1 = $row['prod_precio_vp1'];
			$price2 = $row['prod_precio_vp2'];
		}

		$array = array(0 => $price1,
					   1 => $price2);

		echo json_encode($array);
	}

	public function loadMarkIDS($id, $return){
		$sql = $this->product_model->loadMarkIDS($id);
		$html = '';
		foreach ($sql as $row){
			$type = "'marK'";
			$id = $row['marca_id'];
			$value = $row['marca_nombre'];
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

	public function updatePrice(){
		$id = $_POST['id'];
		$p1 = $_POST['priceP'];
		$p2 = $_POST['priceT'];

		$response = $this->product_model->actualPrice($id, $p1, $p2);
		echo $response;
	}
}