<div>
	<fieldset><legend>Información de Compras</legend>
		<div class="row">
			<div class="col-md-12">
				<table class="tablePage" width="100%">
					<tr>
						<td>
							<div class="input-group">
							  	<span class="input-group-addon"><strong>Comprobante</strong></span>
							  	<select class="form-control" id="shopping-type" style="width: 160px;">
									<option value="F">Factura</option>
							  		<option value="B">Boleta</option>
							  		<option value="O">Otros</option>
							  	</select>
							</div>
						</td>
						<td>
							<div class="input-group">
							  	<span class="input-group-addon"><strong>Serie-Numero</strong></span>
							  	<input type="text" class="form-control" id="shopping-serie" maxlength="4" style="width: 80px;">
							  	<input type="text" class="form-control" id="shopping-number" maxlength="8" style="width: 110px;">
							</div>
						</td>
						<td>
							<div class="input-group">
							  	<span class="input-group-addon"><strong>Fecha</strong></span>
							  	<input type="date" id="shopping-date" class="form-control" value="<?=date('Y-m-d');?>" style="width: 160px;">
							</div>
						</td>
						<td>
							<div class="input-group">
								<span class="input-group-addon"><strong>Moneda</strong></span>
								<select id="shopping-currency" class="form-control" style="width: 170px;">
									<option value="PEN">Soles</option>
									<option value="DOL">Dolares</option>
								</select>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="input-group">
							  	<span class="input-group-addon"><strong>RUC Proveedor</strong></span>
							  	<input type="text" id="shopping-provider-id" value="0" class="form-control hidden" disabled>
							  	<input type="text" id="shopping-provider-code" onkeyup="searchProvider(this.value);" class="form-control" style="width: 160px;" maxlength="11">
							</div>
						</td>
						<td>
							<div class="input-group">
							  	<span class="input-group-addon"><strong>Proveedor</strong></span>
							  	<input type="text" id="shopping-provider-name" class="form-control" style="width: 190px;" readonly>
							</div>
						</td>
						<td>
							<div class="input-group">
							  	<span class="input-group-addon"><strong>Tipo de compra</strong></span>
							  	<select id="shopping-movement" class="form-control" style="width: 160px;" onchange="typePayViewShopping(this.value);">
									<?=$optionType;?>
								</select>
							</div>
						</td>
						<td class="typeMethodViewShopping">
							<div class="input-group">
							  	<span class="input-group-addon"><strong>Forma de pago</strong></span>
								  	<select class="form-control" id="shopping-method-payment" style="width:170px;">
									<?=$optionShape;?>
								</select>
							</div>
						</td>
						<td class="typeCreditViewShopping" style="display:none;">
							<div class="input-group">
								<span class="input-group-addon"><strong>Tipo de credito</strong></span>
							  	<select class="form-control" id="shopping-type-credit" style="width: 170px;">
									<option value="0">Credito a 0 dias</option>
								</select>
							</div>
						</td>
					</tr>
				</table>
			</div>
		</div>
		<br>
		<div class="row">
			<div class="col-md-12">
				<div style="max-height:310px; overflow:auto;">
					<table class="table table-condensed table-bordered table-hover detalle-compra" id="tableDetailMovement" width="100%">
						<form id="formPageShopping" form="shoppingDetail" movement="shopping" baseUrl="<?=base_url();?>" onSubmit="shoppingDetail(); return false;">
							<thead>
								<tr>
									<th colspan="12" style="text-align:center;">
										<label class="radio-inline">Busqueda por:</label>
										<label class="radio-inline"><input type="radio" name="product_search_shopping" value="1">Codigo</label>&nbsp;&nbsp;&nbsp;
										<label class="radio-inline"><input type="radio" name="product_search_shopping" value="2" checked>Descripcion</label>
									</th>
								</tr>
								<tr>
									<th style="display:none;">Id</th>
									<th>Codigo</th>
									<th>Descripcion</th>
									<th style="text-align: center;">Unidad</th>
									<th style="text-align: center;">Precio</th>
									<th style="display:none;">Igv</th>
									<th style="text-align: center;">Cantidad</th>
									<th style="text-align: center;">Flete/Transp.</th>
									<th style="text-align: center;">Precio Min</th>
									<th style="text-align: center;">Precio x Mayor</th>
									<th style="text-align: right;">Subtotal</th>
									<th style="width:60px;">Opcion</th>
								</tr>
								<tr>
									<td style="display:none;">
										<input type="text" name="head-id">
									</td>
									<td width="140">
										<input type="text" name="head-code" class="form-control" onKeyUp="productMovementCode(this.value, 'shopping', 'code')" style="text-transform:uppercase;" disabled>
									</td>
									<td>
										<input type="text" name="head-description" id="head-description-shopping" class="form-control" autofocus required>
									</td>
									<td width="100">
										<input type="text" name="head-unit" class="form-control" readonly>
									</td>
									<td width="110">
										<input type="number" step="any" min="0" name="head-price" onKeyup="calculateSubtotal();" onClick="calculateSubtotal();" class="form-control" style="text-align:right;" value="0.00">
									</td>
									<td width="90">
										<input type="number" step="any" name="head-amount" onKeyup="calculateSubtotal();" onClick="calculateSubtotal();" min="0" class="form-control"  style="text-align:right;" value="1" required>
									</td>
									<td width="100">
										<input type="text" name="head-transporte" id="head-transporte" class="form-control" value="0.00" style="text-align:right;">
									</td>
									<td width="90">
										<input type="number" name="head-price1" id="head-price1" class="form-control" value="0.00" min="0" step="0.01" style="text-align:right;">
									</td>
									<td width="90">
										<input type="number" name="head-price2" id="head-price2" class="form-control" value="0.00" min="0" step="0.01" style="text-align:right;">
									</td>
									<td width="150">
										<input type="text" name="head-subtotal" class="form-control" style="text-align:right;" disabled>
									</td>
									<td align="center">
										<button type="submit" class="btn btn-default btn-block" data-toggle="tooltip" id="shopping-add" disabled title="Agregar"><span class="glyphicon glyphicon-shopping-cart"></span></button>
									</td>
								</tr>
							</thead>
						</form>
						<tbody></tbody>
					</table>
				</div>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-md-12">
				<table class="tablePage" align="right">
					<tr>
						<td width="220">
							<div class="input-group">
							  	<span class="input-group-addon" id="basic-addon3"><strong>Total</strong></span>
							  	<input type="text" id="final-total" style="text-align:right;" class="form-control" aria-describedby="basic-addon3" value="0.00" readonly>
							</div>
						</td>
						<td width="100"><button class="btn btn-success btn-block" data-toggle="tooltip" id="saveMovement" onClick="saveShopping();" disabled title="Guardar">Guardar <span class="glyphicon glyphicon-floppy-save"></span></button></td>
						<td width="100"><a href="<?=base_url();?>shopping" class="btn btn-default btn-block" data-toggle="tooltip" title="Nuevo">Nuevo <span class="glyphicon glyphicon-flash"></span></a></td>
						<td width="100"><button class="btn btn-primary btn-block" onClick="openRecordShopping();" data-toggle="tooltip" title="Historial">Historial <span class="glyphicon glyphicon-folder-open"></span></button></td>
					</tr>
				</table>
			</div>
		</div>
	</fieldset>
</div>

<!-- Modal de Informacion de Compras -->
<div class="modal fade" tabindex="-1" role="dialog" id="modalRecordShopping">
	<div class="modal-dialog" style="width:1200px;">
    	<div class="modal-content">
      		<div class="modal-header">
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        		<h4 class="modal-title">Historial de compras</h4>
      		</div>
	      	<div class="modal-body">
	      		<fieldset><legend>Busqueda</legend>
	      			<form id="formSearchShopping">
		      			<table>
		      				<tr>
		      					<td style="width:60px; padding-right:10px;">Desde: </td>
		      					<td><input type="date" name="shopping-from" class="form-control" style="width:170px;" value="<?=date('Y-m-d');?>" required></td>
		      					<td style="width:60px; text-align:right; padding-right:10px;">Hasta: </td>
		      					<td><input type="date" name="shopping-to" class="form-control" style="width:170px;" value="<?=date('Y-m-d');?>" required></td>
		      					<td style="padding-left:10px;"><button type="submit" class="btn btn-success">Buscar <span class="glyphicon glyphicon-search"></span></button></td>
		      					<td style="padding-left:10px; display:none;"><h4 class="text-primary" id="totalAmountSearchShopping"></h4></td>
		      				</tr>
		      			</table>
	      			</form>
	      		</fieldset>
	      		<br>
	      		<fieldset><legend>Resultados</legend>
		        	<table class="table table-bordered table-striped table-condensed table-hover sortable">
		        		<thead>
		        			<tr>
		        				<th width="60">Tipo</th>
		        				<th width="60">Estado</th>
		        				<th width="120">Serie-Numero</th>
		        				<th width="135">Fecha</th>
		        				<th>Proveedor</th>
		        				<th>Tipo</th>
		        				<th>Moneda</th>
		        				<th width="90">Subtotal</th>
		        				<th width="90">Descuento</th>
		        				<th width="90">Impuesto</th>
		        				<th width="90">Total</th>
		        				<th width="90">Accion</th>
		        			</tr>
		        		</thead>
		        		<tbody id="dataRecordShopping"></tbody>
		        	</table>
		        </fieldset>
	      	</div>
    	</div>
 	</div>
</div>

<!-- Detalle Compras -->
<div class="modal fade" tabindex="-1" role="dialog" id="modalRecordShoppingDetail">
	<div class="modal-dialog" style="width:1000px;">
    	<div class="modal-content">
      		<div class="modal-header">
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        		<h4 class="modal-title">Detalle de Compras</h4>
      		</div>
	      	<div class="modal-body" id="dataRecordShoppingDetail"></div>
    	</div>
 	</div>
</div>