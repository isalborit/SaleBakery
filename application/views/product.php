<div class="container-page">
	<div class="row">
		<div class="col-md-12">
			<fieldset>
				<h3>Modulo de Productos</h3>
				<hr>
				<table class="table table-striped table-condensed table-bordered" id="tablaProducto">
					<thead>
						<tr>
							<th width="80">CODIGO</th>
							<th>NOMBRE DEL PRODUCTO</th>
							<th>MARCA</th>
							<th>CATEGORIA</th>
							<th width="50">UNIDAD</th>
							<th width="65">PRECIO COMPRA</th>
							<th width="65">PRECIO MIN</th>
							<th width="65">PRECIO MAX</th>
							<th>STOCK</th>
							<th width="50">ACCION</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</fieldset>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<fieldset>
				<hr>
				<table class="table-header" align="right">
					<tr>
						<td>
							<button type="button" class="btn btn-primary" onclick="openModal('product');">Nuevo Producto</button>
						</td>
						<td>
							<button type="button" class="btn btn-success" onclick="openModal('category');">Nueva Categoría</button>
						</td>
						<td>
							<button type="button" class="btn btn-warning" onclick="openModal('mark');">Nueva Marca</button>
						</td>
					</tr>
				</table>
			</fieldset>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="modal-product">
	<div class="modal-dialog" style="width: 400px;">
		<div class="modal-content">
			<form class="formPage" id="formPage" form="product" type="register">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
					<h3 class="modal-title">Formulario de Registro</h3>
				</div>
				<div class="modal-body">
					<input type="text" name="id" id="id" style="display: none;">
					<label>Nombre:</label>
					<input type="text" name="name" id="name" class="form-control" placeholder="Nombre del producto" required>
					<br>
					<label>Unidad:</label>
					<input type="text" name="unit" id="unit" class="form-control" placeholder="Unidad de medida" maxlength="5" required>
					<br>
					<label>Marca:</label>
					<select name="marks" id="marks" class="form-control" required></select>
					<br>
					<label>Categoria:</label>
					<select name="cate" id="cate" class="form-control" required></select>
					<br>
					<label>Stock minimo:</label>
					<input type="number" name="stockM" id="stockM" class="form-control" step="any" min="0" placeholder="0.00" required>
					<br>
					<label>Stock Real:</label>
					<input type="number" name="stockR" id="stockR" class="form-control" step="any" min="0" placeholder="0.00" required>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success">Guardar <span class="fa fa-save"></span></button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- Modal para Categoria -->
<div class="modal fade" tabindex="-1" role="dialog" id="modal-category">
	<div class="modal-dialog" style="width: 400px;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
				<h3 class="modal-title">Formulario de Registro</h3>
			</div>
			<div class="modal-body">
				<form class="formPage" id="formPage2" form="category" type="register">
					<fieldset>
						<legend>Registro</legend>
						<input type="text" name="id-cat" id="id-cat" style="display: none;">
						<label>Categoria:</label>
						<input type="text" name="nameC" id="nameC" class="form-control" placeholder="Descripcion categoria" required>
						<br>
						<label>Subir Imágen:</label>
						<input type="file" name="file_cat" id="file_cat" class="form-control" required>
						<br>
						<button type="submit" class="btn btn-block btn-success">
					  		<span class="glyphicon glyphicon-floppy-save"></span> Guardar
					  	</button>
					</fieldset>
				</form>
				<br>
				<fieldset>
					<legend>Historial</legend>
					<table class="table table-condensed table-striped sortable table-scroll" width="100%" id="history-category">
						<thead>
							<tr>
								<th>Descripcion</th>
								<th width="80">Accion</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</fieldset>
			</div>
		</div>
	</div>
</div>
<!-- Modal para Marca -->
<div class="modal fade" tabindex="-1" role="dialog" id="modal-mark">
	<div class="modal-dialog" style="width: 400px;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
				<h3 class="modal-title">Formulario de Registro</h3>
			</div>
			<div class="modal-body">
				<form class="formPage" id="formPage3" form="mark" type="register">
					<fieldset>
						<legend>Registro</legend>
						<input type="text" name="id-mark" id="id-mark" style="display: none;">
						<label>Marca:</label>
						<input type="text" name="nameM" id="nameM" class="form-control" placeholder="Descripcion marca" required>
						<br>
						<button type="submit" class="btn btn-block btn-success">
					  		<span class="glyphicon glyphicon-floppy-save"></span> Guardar
					  	</button>
					</fieldset>
				</form>
				<br>
				<fieldset>
					<legend>Historial</legend>
					<table class="table table-condensed table-striped sortable table-scroll" width="100%" id="history-mark">
						<thead>
							<tr>
								<th>Descripcion</th>
								<th width="80">Accion</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</fieldset>
			</div>
		</div>
	</div>
</div>
<!-- Modal para Precios -->
<div class="modal fade" tabindex="-1" role="dialog" id="modal-price">
	<div class="modal-dialog" style="width: 200px;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
				<h3 class="modal-title" style="text-align: center;">Actualizar Precios</h3>
			</div>
			<div class="modal-body">
				<form id="formPrice">
					<fieldset>
						<label>Precio Minimo:</label>
						<input type="text" name="idPriceP" id="idPriceP" class="form-control" style="display: none;">
						<input type="number" name="price1" id="price1" step="any" min="0" class="form-control" required style="text-align: right;">
						<br>
						<label>Precio Maximo:</label>
						<input type="number" name="price2" id="price2" step="any" min="0" class="form-control" required style="text-align: right;">
						<br>
						<button type="submit" class="btn btn-block btn-success" style="display: none;">
					  		<span class="glyphicon glyphicon-floppy-save"></span> Guardar
					  	</button>
					</fieldset>
				</form>
			</div>
		</div>
	</div>
</div>