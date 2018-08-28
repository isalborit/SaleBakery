<div class="container-page">
	<div class="row">
		<div class="col-md-12">
			<fieldset>
				<h3>Modulo de Usuarios</h3>
				<hr>
				<table class="table table-striped table-condensed table-bordered" id="tablaUsuario">
					<thead>
						<tr>
							<th>NOMBRES DEL EMPLEADO</th>
							<th width="90">USUARIO</th>
							<th>CLAVE ENCRIPTADA</th>
							<th>CLAVE NORMAL</th>
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
							<button type="button" class="btn btn-primary" onclick="openModal('user');">Nuevo Usuario</button>
						</td>
					</tr>
				</table>
			</fieldset>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="modal-user">
	<div class="modal-dialog" style="width: 420px;">
		<div class="modal-content">
			<form class="formPage" id="formPage" form="user" type="register">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
					<h3 class="modal-title">Formulario de Registro</h3>
				</div>
				<div class="modal-body">
					<input type="text" name="id" id="id" style="display: none;">
					<label>DNI / RUC:</label>
					<input type="text" name="document" id="document" class="form-control" onkeyup="loadEmployeeDoc();" placeholder="DNI del empleado" maxlength="8" required>
					<br>
					<label>Datos del Empleado:</label>
					<input type="text" name="nameEmployee" id="nameEmployee" class="form-control" disabled>
					<br>
					<label>Usuario:</label>
					<input type="text" name="name" id="name" class="form-control" placeholder="Nombres cliente" required disabled>
					<br>
					<label>Clave:</label>
					<input type="text" name="pass" id="pass" class="form-control" placeholder="Direccion actual" required disabled>
					<br>
					<label>Accesos:</label>
					<select class="form-control" name="role" id="role" onChange="accessDOM(this.value);" required disabled>
						<option value="" selected disabled>Elija una opcion..</option>
						<option value="ADMIN">Administrador</option>
						<option value="USER">Usuario</option>
					</select>
					<br>
					<div id="accessDOM" style="display:none;">
						<table width="100%">
							<tr>
								<td>
									<div class="checkbox">
										<label><input type="checkbox" name="user_role_sale">Ventas</label>
									</div>
								</td>
								<td>
									<div class="checkbox">
										<label><input type="checkbox" name="user_role_shopping">Compras</label>
									</div>
								</td>
								<td>
									<div class="checkbox">
										<label><input type="checkbox" name="user_role_product">Productos</label>
									</div>
								</td>
								<td>
									<div class="checkbox">
										<label><input type="checkbox" name="user_role_order">Pedidos</label>
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<div class="checkbox">
										<label><input type="checkbox" name="user_role_employee">Empleados</label>
									</div>
								</td>
								<td>
									<div class="checkbox">
										<label><input type="checkbox" name="user_role_user">Usuarios</label>
									</div>
								</td>
								<td>
									<div class="checkbox">
										<label><input type="checkbox" name="user_role_provider">Proveedores</label>
									</div>
								</td>
								<td>
									<div class="checkbox">
										<label><input type="checkbox" name="user_role_customer">Clientes</label>
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<div class="checkbox">
										<label><input type="checkbox" name="user_role_note">Nota Credito</label>
									</div>
								</td>
								<td>
									<div class="checkbox">
										<label><input type="checkbox" name="user_role_account">Cuentas</label>
									</div>
								</td>
								<td>
									<div class="checkbox">
										<label><input type="checkbox" name="user_role_report">Reportes</label>
									</div>
								</td>
							</tr>
						</table><br>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success">Guardar <span class="fa fa-save"></span></button>
				</div>
			</form>
		</div>
	</div>
</div>