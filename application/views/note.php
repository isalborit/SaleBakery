<div class="container-page">
	<div class="row">
		<div class="col-md-12">
			<fieldset>
				<h3>Modulo de Notas de Crédito</h3>
				<hr>
				<table class="table table-striped table-condensed table-bordered" id="tablaNota">
					<thead>
						<tr>
							<th width="120">TIPO NOTA</th>
							<th>FECHA</th>
							<th width="100">SERIE-NUMERO</th>
							<th width="100">COMPROBANTE</th>
							<th>SUBTOTAL</th>
							<th>DESCUENTO</th>
							<th>IMPUESTO</th>
							<th>TOTAL</th>
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
							<button type="button" class="btn btn-primary" onclick="openModal('note');">Nueva Nota</button>
						</td>
					</tr>
				</table>
			</fieldset>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="modal-note">
	<div class="modal-dialog" style="width: 350px;">
		<div class="modal-content">
			<form class="formPage" id="formPage" form="note" type="register">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
					<h3 class="modal-title">Formulario de Registro</h3>
				</div>
				<div class="modal-body">
					<div class="input-group">
					  	<span class="input-group-addon" id="basic-addon" style="width:110px;"><strong>Tipo</strong></span>
					  	<select class="form-control" name="note-type" aria-describedby="basic-addon" style="width:210px;" onchange="getNoteReason(this.value);" required="">
					  		<option value="FC">Credito Factura</option>
					  		<option value="BC">Credito Boleta</option>
					  	</select>
					</div>
					<br>
					<div class="input-group">
					  	<span class="input-group-addon" id="basic-addon" style="width:110px;"><strong>Fecha</strong></span>
					  	<input type="date" name="note-date" class="form-control" value="2018-01-25" aria-describedby="basic-addon" style="width:210px;" required="">
					</div>
					<br>
					<div class="input-group">
					  	<span class="input-group-addon" id="basic-addon" style="width:110px;"><strong>Serie-Numero</strong></span>
					  	<input type="text" name="note-serie" class="form-control" placeholder="____-________" aria-describedby="basic-addon" style="width:210px;" required="" readonly="">
					</div>
					<br>
					<div class="input-group">
					  	<span class="input-group-addon" id="basic-addon" style="width:110px;"><strong>Comprobante</strong></span>
					  	<input type="text" name="note-document" class="form-control" placeholder="____-________" aria-describedby="basic-addon" style="width:210px;" maxlength="13" required="">
					</div>
					<br>
					<div class="input-group">
					  	<span class="input-group-addon" id="basic-addon" style="width:110px;"><strong>Razon motivo</strong></span>
					  	<textarea class="form-control" name="note-desc" aria-describedby="basic-addon" style="width:210px; height:80px; resize:vertical;" required=""></textarea>
					</div>
					<br>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success">Guardar <span class="fa fa-save"></span></button>
				</div>
			</form>
		</div>
	</div>
</div>