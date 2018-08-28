<div class="container-page">
	<div class="row">
		<div class="col-md-12">
			<fieldset>
				<h3>Modulo de Proveedores</h3>
				<hr>
				<table class="table table-striped table-condensed table-bordered" id="tablaProveedor">
					<thead>
						<tr>
							<th width="100">RUC</th>
							<th>RAZON SOCIAL</th>
							<th width="80">TELEFONO</th>
							<th>DIRECCION</th>
							<th>CORREO</th>
							<th width="60">ACCION</th>
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
							<button type="button" class="btn btn-primary" onclick="openModal('provider');">Nuevo Proveedor</button>
						</td>
					</tr>
				</table>
			</fieldset>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="modal-provider">
	<div class="modal-dialog" style="width: 400px;">
		<div class="modal-content">
			<form class="formPage" id="formPage" form="provider" type="register">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
					<h3 class="modal-title">Formulario de Registro</h3>
				</div>
				<div class="modal-body">
					<input type="text" name="id" id="id" style="display: none;">
					<label>RUC:</label>
					<input type="text" name="document" id="document" class="form-control" placeholder="Numero ruc" maxlength="11" required>
					<br>
					<label>Razon Social:</label>
					<input type="text" name="name" id="name" class="form-control" placeholder="Nombre de la empresa" required>
					<br>
					<label>Direccion:</label>
					<input type="text" name="direction" id="direction" class="form-control" placeholder="Direccion actual">
					<br>
					<label>Telefono:</label>
					<input type="text" name="phone" id="phone" class="form-control" placeholder="Numero telefonico" maxlength="12" required>
					<br>
					<label>Correo:</label>
					<input type="email" name="email" id="email" class="form-control" placeholder="empresa@gmail.com">
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success">Guardar <span class="fa fa-save"></span></button>
				</div>
			</form>
		</div>
	</div>
</div>