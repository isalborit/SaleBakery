<div class="row">
	<div class="col-md-12">
		<fieldset><legend>Seleccione un tipo de Reporte</legend>
			<div class="btn-group btn-group-justified" role="group">
			  	<div class="btn-group" role="group">
			  		<button type="button" class="btn btn-primary" onClick="htmlReport('reportSales');" style="border-bottom-left-radius:0px;">
			  			<span class="glyphicon glyphicon-tasks"></span> 
			  			Resumen de ventas
			  		</button>
			  	</div>
			  	<div class="btn-group" role="group">
			  		<button type="button" class="btn btn-primary" onClick="htmlReport('reportCustomers');" style="border-bottom-right-radius:0px;">
			  			<span class="glyphicon glyphicon-export"></span> 
			  			Resumen de clientes
			  		</button>
			  	</div>
			  	<div class="btn-group" role="group">
			  		<button type="button" class="btn btn-primary" onClick="htmlReport('reportCustomerSales');" style="border-bottom-right-radius:0px;">
			  			<span class="glyphicon glyphicon-export"></span> 
			  			Ventas por cliente
			  		</button>
			  	</div>
			  	<div class="btn-group" role="group">
			  		<button type="button" class="btn btn-primary" onClick="htmlReport('reportSalesType');">
			  			<span class="glyphicon glyphicon-check"></span> 
			  			Ventas por tipo de pago
			  		</button>
			  	</div>
			  	<div class="btn-group" role="group">
			  		<button type="button" class="btn btn-primary" onClick="htmlReport('reportSalesUser');">
			  			<span class="glyphicon glyphicon-user"></span> 
			  			Ventas por usuario
			  		</button>
			  	</div>
			  	<div class="btn-group" role="group">
			  		<button type="button" class="btn btn-primary" onClick="htmlReport('reportEmployee');">
			  			<span class="glyphicon glyphicon-user"></span> 
			  			Resumen de empleados
			  		</button>
			  	</div>
			</div>
		    <div class="btn-group btn-group-justified" role="group">
		        <div class="btn-group" role="group">
		            <button type="button" class="btn btn-default" onClick="htmlReport('reportShopping');" style="border-top-left-radius:0px;">
		                <span class="glyphicon glyphicon-tasks"></span> 
		                Resumen de compras
		            </button>
		        </div>
		        <div class="btn-group" role="group">
		            <button type="button" class="btn btn-default" onClick="htmlReport('reportProvider');">
		                <span class="glyphicon glyphicon-import"></span> 
		                Resumen de proveedores
		            </button>
		        </div>
		        <div class="btn-group" role="group">
		            <button type="button" class="btn btn-default" onClick="htmlReport('reportProviderShopping');">
		                <span class="glyphicon glyphicon-import"></span> 
		                Compras por proveedor
		            </button>
		        </div>
		        <div class="btn-group" role="group">
		            <button type="button" class="btn btn-default" onClick="htmlReport('reportInventary');">
		                <span class="glyphicon glyphicon-list-alt"></span> 
		                Inventario de productos
		            </button>
		        </div>
		        <div class="btn-group" role="group">
		            <button type="button" class="btn btn-default" onClick="htmlReport('reportUtility');" style="border-top-right-radius:0px;">
		                <span class="glyphicon glyphicon-list-alt"></span>
		                Utilidad por producto
		            </button>
		        </div>
		        <div class="btn-group" role="group">
		            <button type="button" class="btn btn-default" onClick="htmlReport('reportStatistic');">
		                <span class="glyphicon glyphicon-signal"></span> 
		                Cuadros estadisticos
		            </button>
		        </div>
		    </div>
		</fieldset>
		<hr>
		<div id="formRegisterEdition" baseUrl="<?=base_url();?>">
			<center>...Ningun reporte seleccionado...</center>
		</div>
	</div>
</div>
<script type="text/javascript" src="public/chart/Chart.min.js"></script>
<script type="text/javascript">

	function reportSalesStatistic(){
        var value = $('#year').val();
        var currency = $('#currency').val();
		$('#reportSalesStatistic').html('<canvas id="reportSalesStatisticGraph"></canvas>');
    	$.ajax({
    		type: 'POST',
    		url: baseUrl+'report/getSaleStatistic/',
    		data: 'value='+value+'&currency='+currency,
    		beforeSend: function(){
				$(document.body).append('<div class="cargando"><img src="<?=base_url('public')?>/resources/loading.gif"></div>');
			},
    		success: function(data){
    			$('.cargando').remove();
    			var datos = eval(data);
    			var e   = datos[0];
    			var f 	= datos[1];
    			var m 	= datos[2];
    			var a 	= datos[3];
    			var ma 	= datos[4];
    			var j 	= datos[5];
    			var jl	= datos[6];
    			var ag	= datos[7];
    			var s 	= datos[8];
    			var o 	= datos[9];
    			var n 	= datos[10];
    			var d 	= datos[11];
                var total = datos[12];
    			$('#total-statistic-sale').html(total);

    			var Datos = {
    				labels : ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
    				datasets : [
    					{
    						label: "Nuevos Soles",
				            fillColor: "rgba(80,180,240,0.3)",
				            strokeColor: "rgba(40,145,220,0.5)",
				            highlightFill : 'rgba(110,190,255,0.7)',
    						highlightStroke : 'rgba(50,145,220,0.9)',
    						data : [e, f, m, a, ma, j, jl, ag, s, o, n, d]
    					}
    				]
    			}
    			
    			var contexto = document.getElementById('reportSalesStatisticGraph').getContext('2d');
    			window.Barra = new Chart(contexto).Line(Datos, { responsive : true, scaleFontSize: 11, animationEasing: "easeInOutBounce", tooltipFontSize: 12, tooltipFontStyle: "normal", tooltipTitleFontStyle: "normal"});
    		}
    	});
    	return false;
	}

	function reportShoppingStatistic(){
        var value = $('#yearC').val();
        var currency = $('#currencyC').val();
		$('#reportShoppingStatistic').html('<canvas id="reportShoppingStatisticGraph"></canvas>');
    	$.ajax({
    		type: 'POST',
    		url: baseUrl+'report/getShoppingStatistic/',
            data: 'value='+value+'&currency='+currency,
    		beforeSend: function(){
				$(document.body).append('<div class="cargando"><img src="<?=base_url('public')?>/resources/loading.gif"></div>');
			},
    		success: function(data){
    			$('.cargando').remove();
    			var datos = eval(data);
    			var e   = datos[0];
    			var f 	= datos[1];
    			var m 	= datos[2];
    			var a 	= datos[3];
    			var ma 	= datos[4];
    			var j 	= datos[5];
    			var jl	= datos[6];
    			var ag	= datos[7];
    			var s 	= datos[8];
    			var o 	= datos[9];
    			var n 	= datos[10];
    			var d 	= datos[11];
    			var total = datos[12];
                $('#total-statistic-shopping').html(total);

    			var Datos = {
    				labels : ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
    				datasets : [
    					{
    						label: "Nuevos Soles",
				            fillColor: "rgba(80,180,240,0.3)",
				            strokeColor: "rgba(40,145,220,0.5)",
				            highlightFill : 'rgba(110,190,255,0.7)',
    						highlightStroke : 'rgba(50,145,220,0.9)',
    						data : [e, f, m, a, ma, j, jl, ag, s, o, n, d]
    					}
    				]
    			}
    			
    			var contexto = document.getElementById('reportShoppingStatisticGraph').getContext('2d');
    			window.Barra = new Chart(contexto).Line(Datos, { responsive : true, scaleFontSize: 11, animationEasing: "easeInOutBounce", tooltipFontSize: 12, tooltipFontStyle: "normal", tooltipTitleFontStyle: "normal"});
    		}
    	});
    	return false;
	}

</script>