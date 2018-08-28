<form id="formPage" baseUrl="<?=base_url();?>"></form>
<div class="row">
	<div class="col-md-5">
		<div class="row">
			<div class="col-md-12">
				<div style="background-color: #fff; min-height: 350px; overflow-y: scroll; border: 1px solid #ddd;">
					<table class="table table-condensed table-striped table-hover table-detalle-producto">
						<thead>
							<tr>
								<th width="50">Borrar</th>
								<!-- <th width="50">Item</th> -->
								<th>Producto</th>
								<th width="80">Cantidad</th>
								<th width="80">Subtotal</th>
							</tr>
						</thead>
						<tbody id="DetailProductSale"></tbody>
					</table>
				</div>
				<div class="row" style="margin-top: 5px;">
					<div class="col-md-6"></div>
					<div class="col-md-6" style="text-align: right;">
						<label style="font-size: 18px;">Total <span style="font-size: 18px;">S/.</span></label>
						<input type="number" name="neto-total" id="neto-total" value="0.00" disabled style="font-size: 18px; width: 130px; text-align: right; margin-right: 15px;">
					</div>
				</div>
			</div>
		</div>
		<div class="customer-oculto" id="base-customers">
			<div class="row">
				<div class="col-md-12"><h3>Clientes</h3></div>
				<div class="col-md-4">
					<input type="text" class="form-control" name="customer-id" id="customer-id" value="1" style="display: none;">
					<input type="text" class="form-control" name="customer-document" id="customer-document" onkeyup="loadCustomerDocSale(this.value);" placeholder="DNI / RUC">
				</div>
				<div class="col-md-8">
					<input type="text" class="form-control" name="customer-name" id="customer-name" placeholder="Nombre o Razon Social">
				</div>
			</div>
		</div>
		<br><br>
		<div class="row">
			<div class="col-md-12">
				<div class="col-md-4">
					<a href="javascript:void(0);" class="mister-botones mbcancel" id="button-cancel" btn-status="false" disabled>
						<span class="glyphicon glyphicon-remove"></span> Cancelar
					</a>
				</div>
				<div class="col-md-4">
					<a href="javascript:void(0);" class="mister-botones mbpay" id="button-ok" disabled>
						<span class="glyphicon glyphicon-ok"></span> Pagar
					</a>
				</div>
				<div class="col-md-4">
					<a href="javascript:void(0);" onclick="addCustomer();" class="mister-botones mbcustomer"> 
						<span class="glyphicon glyphicon-user"></span> Clientes
					</a>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-7">
		<div class="row">
			<div class="col-md-12">
				<div class="row" id="tp-venta"></div>
			</div>
		</div>
		<div class="row" id="resultProduct"></div>
	</div>
</div>

<!-- Modal para Pedido -->
<div class="modal fade" tabindex="-1" role="dialog" id="modal-order">
	<div class="modal-dialog" style="width: 200px;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
				<h3 class="modal-title" style="text-align: center;">Pedido</h3>
			</div>
			<div class="modal-body">
				<input type="text" id="product-id-data" style="display:none;">
				<input type="text" id="product-description-data" style="display:none;">
				<input type="text" id="product-unit-data" style="display:none;">
				<input type="text" id="product-price-data" style="display:none;">
				<fieldset>
					<input type="text" class="form-control" name="product-name" id="product-name" style="display: none;">
					<label>Precio:</label>
					<input type="number" name="product-price-base" id="product-price-base" style="display: none;">
					<input type="number" name="product-price" id="product-price" step="any" min="0" class="form-control" required style="text-align: right;">
					<br>
					<label>Cantidad:</label>
					<input type="number" name="product-amount" id="product-amount" step="any" min="0" class="form-control" required style="text-align: right;">
					<br>
					<input type="number" class="form-control" name="product-discount" id="product-discount" value="0.00" min="0" step="any" onkeyup="calculateProductSale();" disabled style="display: none;">
					<br>
					<button type="button" class="btn btn-block btn-success" onclick="addProductCart();">
				  		<span class="glyphicon glyphicon-floppy-save"></span> Guardar
				  	</button>
				</fieldset>
			</div>
		</div>
	</div>
</div>

<!-- Modal de Pago -->
<div class="modal fade" tabindex="-1" role="dialog" id="modal-method-pay">
	<div class="modal-dialog" style="width:400px;">
    	<div class="modal-content">
      		<div class="modal-header">
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        		<h3>4° Informacion de venta</h3>
      		</div>
	      	<div class="modal-body">
	      		<form onSubmit="return saveSale();">
		      		<div class="input-group">
					  	<span class="input-group-addon" id="basic-addon" style="width:120px; text-align:right;"><strong>Comprobante</strong></span>
					  	<select class="form-control" aria-describedby="basic-addon" id="sale-type-document" style="width:248px;" required>
				         	<option value="" selected disabled>Elija una opcion</option>
				         	<option value="F">Factura</option>
				         	<option value="B">Boleta de venta</option>
				         	<option value="A">Sin documento</option>
				        </select>
					</div>
					<br>
					<div class="input-group">
					  	<span class="input-group-addon" style="width:120px;"><strong>Fecha</strong></span>
					  	<input type="date" class="form-control" style="width:248px;" id="sale-date" value="<?=date('Y-m-d');?>">
					</div>
					<br>
					<div class="input-group">
					  	<span class="input-group-addon" id="basic-addon5" style="width:120px; text-align:right;""><strong>Serie - Numero</strong></span>
					  	<input type="text" class="form-control" aria-describedby="basic-addon5" style="width:248px;" placeholder="____-________" id="sale-number-invoice" readonly>
					</div>
					<br>
		      		<div class="input-group">
					  	<span class="input-group-addon" id="basic-addon0" style="width:120px; text-align:right;"><strong>Tipo de venta</strong></span>
					  	<select class="form-control" aria-describedby="basic-addon0" id="sale-method-pay" style="width:248px;" onChange="typePayView(this.value);"><?=$optionMovement;?></select>
					</div>

					<div id="typeMethodView">
						<br>
			      		<div class="input-group">
						  	<span class="input-group-addon" style="width:120px; text-align:right;"><strong>Forma de pago</strong></span>
						  	<select class="form-control" id="sale-method-pay-2" style="width:248px;"><?=$optionMethod;?></select>
						</div>
					</div>
					
					<div id="typeCreditView" style="display:none;">
						<br>
			      		<div class="input-group">
						  	<span class="input-group-addon" style="width:120px; text-align:right;"><strong>Tipo de credito</strong></span>
						  	<select class="form-control" id="sale-type-credit" style="width:248px;"><option value="0">Credito a 0 dias</option></select>
						</div>
					</div>

					<br>
					<div class="input-group">
					  	<span class="input-group-addon" style="width:120px; text-align:right;""><strong>Cancelado</strong></span>
					  	<select class="form-control" style="text-align:right; width:248px;" id="sale-payed" disabled>
					  		<option value="1">Si</option>
					  		<option value="0">No</option>
					  	</select>
					</div>
					<br>
					<div class="input-group">
					  	<span class="input-group-addon" id="basic-addon1" style="width:120px; text-align:right;""><strong>Cobrar <span class="simbolExchange"><?=$simbol;?></span></strong></span>
					  	<input type="text" class="form-control" aria-describedby="basic-addon1" style="text-align:right; width:248px;" id="sale-total-charge" readonly>
					</div>
					<br>
					<div class="input-group">
					  	<span class="input-group-addon" id="basic-addon2" style="width:120px; text-align:right;""><strong>Recibido <span class="simbolExchange"><?=$simbol;?></span></strong></span>
					  	<input type="number" step="any" class="form-control" aria-describedby="basic-addon2" style="text-align:right; width:248px;" onkeyup="calculateChangeSale();" id="sale-total-received">
					</div>
					<br>
					<div class="input-group">
					  	<span class="input-group-addon" id="basic-addon3" style="width:120px; text-align:right;""><strong>Cambio <span class="simbolExchange"><?=$simbol;?></span></strong></span>
					  	<input type="text" class="form-control" aria-describedby="basic-addon3" style="text-align:right; width:248px;" id="sale-total-change" readonly>
					</div>
					<br>
					<button type="submit" class="btn btn-danger btn-block" id="button-sale-save">Guardar e imprimir venta</button>
				</form>
	      	</div>
    	</div>
 	</div>
</div>