<div class="container-page">
	<div class="row">
		<div class="col-md-12">
			<fieldset>
				<h3>Modulo de Empleados</h3>
				<hr>
				<table class="table table-striped table-condensed table-bordered" id="tablaEmpleado">
					<thead>
						<tr>
							<th width="100">DNI</th>
							<th>NOMBRES</th>
							<th>APELLIDOS</th>
							<th width="90">TELEFONO</th>
							<th>DIRECCION</th>
							<th>CARGO</th>
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
							<button type="button" class="btn btn-primary" onclick="openModal('employee');">Nuevo Empleado</button>
						</td>
						<td>
							<button type="button" class="btn btn-default" onclick="openModal('type-employee');">Nuevo Tipo Empleado</button>
						</td>
						<td>
							<button type="button" class="btn btn-default" onclick="openModal('area');"> Nueva Area</button>
						</td>
					</tr>
				</table>
			</fieldset>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="modal-employee">
	<div class="modal-dialog" style="width: 400px;">
		<div class="modal-content">
			<form class="formPage" id="formPage" form="employee" type="register">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
					<h3 class="modal-title">Formulario de Registro</h3>
				</div>
				<div class="modal-body">
					<input type="text" name="id" id="id" style="display: none;">
					<label>DNI:</label>
					<input type="text" name="document" id="document" class="form-control" placeholder="Documento de identidad" maxlength="8" required>
					<br>
					<label>Nombres:</label>
					<input type="text" name="name" id="name" class="form-control" placeholder="Nombres cliente" required>
					<br>
					<label>Apellidos:</label>
					<input type="text" name="lastname" id="lastname" class="form-control" placeholder="Apellidos cliente" required>
					<br>
					<label>Direccion:</label>
					<input type="text" name="direction" id="direction" class="form-control" placeholder="Direccion actual" required>
					<br>
					<label>Telefono:</label>
					<input type="text" name="phone" id="phone" class="form-control" placeholder="Numero telefonico" maxlength="12" required>
					<br>
					<label>Sexo:</label>
					<select name="sex" id="sex" class="form-control" required>
						<option value="" disabled selected>Elija una opcion..</option>
						<option value="M">MASCULINO</option>
						<option value="F">FEMENINO</option>
					</select>
					<br>
					<label>Tipo Empleado:</label>
					<select name="temp" id="temp" class="form-control" required></select>
					<br>
					<label>Area:</label>
					<select name="area" id="area" class="form-control" required></select>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success">Guardar <span class="fa fa-save"></span></button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- Modal para Tipo Empleado -->
<div class="modal fade" tabindex="-1" role="dialog" id="modal-type-employee">
	<div class="modal-dialog" style="width: 350px;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
				<h3 class="modal-title">Formulario de Registro</h3>
			</div>
			<div class="modal-body">
				<form class="formPage" id="formPage2" form="type-employee" type="register">
					<fieldset>
						<legend>Registro</legend>
						<input type="text" name="id-type" id="id-type" style="display: none;">
						<label>Tipo Empleado:</label>
						<input type="text" name="nameT" id="nameT" class="form-control" placeholder="Descripcion tipo empleado" required>
						<br>
						<button type="submit" class="btn btn-block btn-success">
					  		<span class="glyphicon glyphicon-floppy-save"></span> Guardar
					  	</button>
					</fieldset>
				</form>
				<br>
				<fieldset>
					<legend>Historial</legend>
					<table class="table table-condensed table-striped sortable" width="100%" id="history-type-employee">
						<thead>
							<tr>
								<th>Descripcion</th>
								<th width="90">Accion</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</fieldset>
			</div>
		</div>
	</div>
</div>
<!-- Modal para Area -->
<div class="modal fade" tabindex="-1" role="dialog" id="modal-area">
	<div class="modal-dialog" style="width: 350px;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
				<h3 class="modal-title">Formulario de Registro</h3>
			</div>
			<div class="modal-body">
				<form class="formPage" id="formPage3" form="area" type="register">
					<fieldset>
						<legend>Registro</legend>
						<input type="text" name="id-area" id="id-area" style="display: none;">
						<label>Area:</label>
						<input type="text" name="nameA" id="nameA" class="form-control" placeholder="Descripcion area" required>
						<br>
						<button type="submit" class="btn btn-block btn-success">
					  		<span class="glyphicon glyphicon-floppy-save"></span> Guardar
					  	</button>
					</fieldset>
				</form>
				<br>
				<fieldset>
					<legend>Historial</legend>
					<table class="table table-condensed table-striped sortable" width="100%" id="history-area">
						<thead>
							<tr>
								<th>Descripcion</th>
								<th width="90">Accion</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</fieldset>
			</div>
		</div>
	</div>
</div>