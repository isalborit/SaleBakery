$(document).ready(function(){
	var idioma = {
        "sProcessing":     "Procesando...",
        "sLengthMenu":     "Mostrar _MENU_ registros",
        "sZeroRecords":    "No se encontraron resultados",
        "sEmptyTable":     "Ningún dato disponible en esta tabla",
        "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
        "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
        "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
        "sInfoPostFix":    "",
        "sSearch":         "Buscar:",
        "sUrl":            "",
        "sInfoThousands":  ",",
        "sLoadingRecords": "Cargando...",
        "oPaginate":
        {
            "sFirst":    "Primero",
            "sLast":     "Último",
            "sNext":     "Siguiente",
            "sPrevious": "Anterior"
        },
        "oAria":
        {
            "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
        }
    }

    $.ui.autocomplete.prototype._renderItem = function (ul, item){
        item.label = item.label.replace(new RegExp("(?![^&;]+;)(?!<[^<>]*)(" + $.ui.autocomplete.escapeRegex(this.term) + ")(?![^<>]*>)(?![^&;]+;)", "gi"), '<strong style="color:#0174DF;">$1</strong>');
        return $("<li></li>").data("item.autocomplete", item).append('<a>' + item.label + "</a>").appendTo(ul);
    };

    $('[data-toggle="tooltip"]').tooltip(); 

    $('#customer-document').focus();
    $('#pVenta1').attr('checked', 'checked');
    $("#sale-type-document option[value='F']").hide();

    timeView();

    /* starPage de DataTables*/
	$('#tablaProducto').dataTable({
		'language' : idioma,
		'ajax' : baseUrl + 'product/start',
		'order' : []
	});
    $('#tablaProveedor').dataTable({
        'language' : idioma,
        'ajax' : baseUrl + 'provider/start',
        'order' : []
    });
    $('#tablaCliente').dataTable({
        'language' : idioma,
        'ajax' : baseUrl + 'customer/start',
        'order' : []
    });
    $('#tablaEmpleado').dataTable({
        'language' : idioma,
        'ajax' : baseUrl + 'employee/start',
        'order' : []
    });
    $('#tablaUsuario').dataTable({
        'language' : idioma,
        'ajax' : baseUrl + 'user/start',
        'order' : []
    });
    $('#tablaNota').dataTable({
        'language' : idioma,
        'ajax' : baseUrl + 'note/start',
        'order' : []
    });
    $('#tablaPagos').dataTable({
        'language' : idioma,
        'ajax' : baseUrl + 'pay/start',
        'order' : []
    });
    $('#tablaCobros').dataTable({
        'language' : idioma,
        'ajax' : baseUrl + 'charge/start',
        'order' : []
    });
    $('#tablaPedido').dataTable({
        'language' : idioma,
        'ajax' : baseUrl + 'order/start',
        'order' : []
    });

    var page = document.location.href;
    var valpage = page.substr(28);
    if(valpage == 'sale'){
        $.ajax({
            type: 'POST',
            url: baseUrl + 'sale/startCategory',
            beforeSend: function(){
                $(document.body).append('<span class="loading"><div></div></span>');
            },
            success: function(response){
                var array = eval(response);
                var cat = array[0];
                $('.loading').remove();
                $('#tp-venta').html(cat);
            }
        });
    }

    $('[name=customer-name]').autocomplete({
        source: baseUrl + 'sale/loadCustomerNameSale',
        select: function(event, ui){
            var id = ui.item.cli1;
            var doc = ui.item.cli2;
            var name = ui.item.cli3;
            var direction = ui.item.cli4;

            $('[name=customer-id]').val(id);
            $('[name=customer-document]').val(doc);
            $('[name=customer-direction]').val(direction);
        }
    });

    $('#head-description-shopping').autocomplete({
        source: baseUrl+'shopping/searchProductDescription',
        select: function(event, ui){
            var id = ui.item.id;
            var barcode = ui.item.barcode;
            var unit = ui.item.unit; 
            var price = ui.item.price;
            var tax = ui.item.tax; 
            $('[name=head-id]').val(id);
            $('[name=head-code]').val(barcode);
            $('[name=head-unit]').val(unit);
            $('[name=head-price]').val(price);
            $('[name=head-price]').select();
            $('#shopping-add').removeAttr('disabled');
        }
    });

    /* Filtro de Radios */
    $('[name=product_search_shopping]').on('click', function(){
        var value = $(this).val();
        if(value == 1){
            //CODE
            $('[name=head-code]').attr('onKeyUp', "productMovementCode(this.value, 'shopping', 'code')");
            $('[name=head-code]').removeAttr('disabled');
            $('[name=head-description]').attr('readonly', 'readonly');
            $('[name=head-code]').focus();
        }else if(value == 2){
            //DESCRIPTION
            $('[name=head-code]').attr('disabled', 'disabled');
            $('[name=head-description]').removeAttr('readonly');
            $('[name=head-description]').focus();
        }
    });

    /* Formularios */
    $('#formPage').on('submit', function(){
        var data = $(this).serialize();
        var form = $(this).attr('form');
        var type = $(this).attr('type');
        var ask = confirm('¿Desea guardar este registro?');

        if (ask == true){
            switch(form){
                case 'product':
                    if (type == 'register'){
                        var url = baseUrl + 'product/register';
                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: data,
                            beforeSend: function(){
                                $(document.body).append('<span class="loading"><div></div></span>');
                            },
                            success: function(response){
                                $('.loading').remove();
                                var value = eval(response);
                                var status = value[0];
                                if (status == 'Ok'){
                                    $('#tablaProducto').DataTable().ajax.reload();
                                    $('#formPage')[0].reset();
                                    $('#pVenta1').attr('checked', 'checked');
                                    alert('Producto registrado con exito.');
                                    $('#name').focus();
                                }else{
                                    alert('Error, intentelo mas tarde.');
                                }
                            }
                        });
                        return false;
                    }else if(type == 'edition'){
                        var url = baseUrl + 'product/edition/updateData';
                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: data,
                            beforeSend: function(){
                                $(document.body).append('<span class="loading"><div></div></span>');
                            },
                            success: function(response){
                                $('.loading').remove();
                                var value = eval(response);
                                var status = value[0];
                                if (status == 'Ok'){
                                    $('#tablaProducto').DataTable().ajax.reload();
                                    $('#formPage')[0].reset();
                                    $('#pVenta1').attr('checked', 'checked');
                                    alert('Producto editado con exito.');
                                    $('#modal-product').modal('hide');
                                }else{
                                    alert('Error, intentelo mas tarde.');
                                }
                            }
                        });
                        return false;
                    }
                break;
                case 'provider':
                    if (type == 'register'){
                        var url = baseUrl + 'provider/register';
                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: data,
                            beforeSend: function(){
                                $(document.body).append('<span class="loading"><div></div></span>');
                            },
                            success: function(response){
                                $('.loading').remove();
                                var value = eval(response);
                                var status = value[0];
                                if (status == 'Ok'){
                                    $('#tablaProveedor').DataTable().ajax.reload();
                                    $('#formPage')[0].reset();
                                    alert('Proveedor registrado con exito.');
                                    $('#document').focus();
                                }else{
                                    alert('Error, intentelo mas tarde.');
                                }
                            }
                        });
                        return false;
                    }else if(type == 'edition'){
                        var url = baseUrl + 'provider/edition/updateData';
                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: data,
                            beforeSend: function(){
                                $(document.body).append('<span class="loading"><div></div></span>');
                            },
                            success: function(response){
                                $('.loading').remove();
                                var value = eval(response);
                                var status = value[0];
                                if (status == 'Ok'){
                                    $('#tablaProveedor').DataTable().ajax.reload();
                                    $('#formPage')[0].reset();
                                    alert('Proveedor editado con exito.');
                                    $('#modal-provider').modal('hide');
                                }else{
                                    alert('Error, intentelo mas tarde.');
                                }
                            }
                        });
                        return false;
                    }
                break;
                case 'customer':
                    if (type == 'register'){
                        var url = baseUrl + 'customer/register';
                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: data,
                            beforeSend: function(){
                                $(document.body).append('<span class="loading"><div></div></span>');
                            },
                            success: function(response){
                                $('.loading').remove();
                                var value = eval(response);
                                var status = value[0];
                                if (status == 'Ok'){
                                    $('#tablaCliente').DataTable().ajax.reload();
                                    $('#formPage')[0].reset();
                                    alert('Cliente registrado con exito.');
                                    $('#document').focus();
                                }else{
                                    alert('Error, intentelo mas tarde.');
                                }
                            }
                        });
                        return false;
                    }else if(type == 'edition'){
                        var url = baseUrl + 'customer/edition/updateData';
                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: data,
                            beforeSend: function(){
                                $(document.body).append('<span class="loading"><div></div></span>');
                            },
                            success: function(response){
                                $('.loading').remove();
                                var value = eval(response);
                                var status = value[0];
                                if (status == 'Ok'){
                                    $('#tablaCliente').DataTable().ajax.reload();
                                    $('#formPage')[0].reset();
                                    alert('Cliente editado con exito.');
                                    $('#modal-customer').modal('hide');
                                }else{
                                    alert('Error, intentelo mas tarde.');
                                }
                            }
                        });
                        return false;
                    }
                break;
                case 'employee':
                    if (type == 'register'){
                        var url = baseUrl + 'employee/register';
                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: data,
                            beforeSend: function(){
                                $(document.body).append('<span class="loading"><div></div></span>');
                            },
                            success: function(response){
                                $('.loading').remove();
                                var value = eval(response);
                                var status = value[0];
                                if (status == 'Ok'){
                                    $('#tablaEmpleado').DataTable().ajax.reload();
                                    $('#formPage')[0].reset();
                                    alert('Empleado registrado con exito.');
                                    $('#document').focus();
                                }else{
                                    alert('Error, intentelo mas tarde.');
                                }
                            }
                        });
                        return false;
                    }else if(type == 'edition'){
                        var url = baseUrl + 'employee/edition/updateData';
                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: data,
                            beforeSend: function(){
                                $(document.body).append('<span class="loading"><div></div></span>');
                            },
                            success: function(response){
                                $('.loading').remove();
                                var value = eval(response);
                                var status = value[0];
                                if (status == 'Ok'){
                                    $('#tablaEmpleado').DataTable().ajax.reload();
                                    $('#formPage')[0].reset();
                                    alert('Empleado editado con exito.');
                                    $('#modal-employee').modal('hide');
                                }else{
                                    alert('Error, intentelo mas tarde.');
                                }
                            }
                        });
                        return false;
                    }
                break;
                case 'user':
                    if (type == 'register'){
                        var url = baseUrl + 'user/register';
                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: data,
                            beforeSend: function(){
                                $(document.body).append('<span class="loading"><div></div></span>');
                            },
                            success: function(response){
                                $('.loading').remove();
                                var value = eval(response);
                                var status = value[0];
                                if (status == 'Ok'){
                                    $('#tablaUsuario').DataTable().ajax.reload();
                                    $('#formPage')[0].reset();
                                    alert('Usuario registrado con exito.');
                                }else if(status == 'Exists'){
                                    alert('Ya existe un usuario para este Empleado.');
                                }else{
                                    alert('Error, intentelo mas tarde.');
                                }
                            }
                        });
                        return false;
                    }
                break;
                case 'note':
                    if (type == 'register'){
                        var url = baseUrl + 'note/register';
                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: data,
                            beforeSend: function(){
                                $(document.body).append('<span class="loading"><div></div></span>');
                            },
                            success: function(response){
                                $('.loading').remove();
                                var value = eval(response);
                                var status = value[0];
                                if (status == 'Ok'){
                                    $('#tablaNota').DataTable().ajax.reload();
                                    $('#formPage')[0].reset();
                                    alert('Nota de Crédito registrada con exito.');
                                    $('#modal-note').modal('hide');
                                }else if(status == 'Error'){
                                    alert('Error, Comprobante no válido.');
                                }else if(status == 'Null'){
                                    alert('Error, intentelo mas tarde.');
                                }
                            }
                        });
                        return false;
                    }
                break;
            }
        }else{
            return false;
        }
    });

    $('#formPage2').on('submit', function(){
        var data = $(this).serialize();
        var form = $(this).attr('form');
        var type = $(this).attr('type');
        var ask = confirm('¿Desea guardar este registro?');

        if (ask == true){
            switch(form){
                case 'type-employee':
                    var url = baseUrl + 'employee/registerType';
                    if (type == 'register'){
                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: data,
                            beforeSend: function(){
                                $(document.body).append('<span class="loading"><div></div></span>');
                            },
                            success: function(response){
                                $('.loading').remove();
                                var array = eval(response);
                                var status = array[0];
                                var value = array[1];
                                if (status == 'Ok'){
                                    $('#formPage2')[0].reset();
                                    $('#history-type-employee tbody').prepend(value);
                                    alert('Tipo empleado registrado con exito.');
                                }else{
                                    alert('Error, intentelo mas tarde.');
                                }
                            }
                        });
                        return false;
                    }else if(type == 'edition'){
                        var url = baseUrl + 'employee/EditionTypeEmployee/updateData';
                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: data,
                            beforeSend: function(){
                                $(document.body).append('<span class="loading"><div></div></span>');
                            },
                            success: function(response){
                                $('.loading').remove();
                                var value = eval(response);
                                var status = value[0];
                                var html = value[1];
                                var ids = value[2];
                                if(status == 'Ok'){
                                    $('#reg-'+ ids).replaceWith(html);
                                    $('#formPage2')[0].reset();
                                    alert('Tipo Empleado editado con exito');
                                }else if(status == 'sql'){
                                    alert('Error, intentelo mas tarde.');                           
                                }
                            }
                        });
                        return false;
                    }
                break;
                case 'category':
                    if (type == 'register'){
                        var formData = new FormData($('#formPage2')[0]);
                        var url = baseUrl + 'product/registerCategory';
                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: formData,
                            cache: false,
                            contentType: false,
                            processData: false,
                            beforeSend: function(){
                                $(document.body).append('<span class="loading"><div></div></span>');
                            },
                            success: function(response){
                                $('.loading').remove();
                                var array = eval(response);
                                var status = array[0];
                                var value = array[1];
                                if (status == 'ok'){
                                    $('#formPage2')[0].reset();
                                    $('#history-category tbody').prepend(value);
                                    alert('Categoria registrada con exito.');
                                }else{
                                    alert('Error, intentelo mas tarde.');
                                }
                            }
                        });
                        return false;
                    }else if(type == 'edition'){
                        var url = baseUrl + 'product/editionCategory/updateData';
                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: data,
                            beforeSend: function(){
                                $(document.body).append('<span class="loading"><div></div></span>');
                            },
                            success: function(response){
                                $('.loading').remove();
                                var value = eval(response);
                                var status = value[0];
                                var html = value[1];
                                var ids = value[2];
                                if(status == 'Ok'){
                                    $('#reg-'+ ids).replaceWith(html);
                                    $('#formPage2')[0].reset();
                                    alert('Categoria editada con exito');
                                }else if(status == 'sql'){
                                    alert('Error, intentelo mas tarde.');                           
                                }
                            }
                        });
                        return false;
                    }
                break;
            }
        }else{
            return false;
        }
    });

    $('#formPage3').on('submit', function(){
        var data = $(this).serialize();
        var form = $(this).attr('form');
        var type = $(this).attr('type');
        var ask = confirm('¿Desea guardar este registro?');

        if (ask == true){
            switch(form){
                case 'area':
                    if (type == 'register'){
                        var url = baseUrl + 'employee/registerArea';
                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: data,
                            beforeSend: function(){
                                $(document.body).append('<span class="loading"><div></div></span>');
                            },
                            success: function(response){
                                $('.loading').remove();
                                var array = eval(response);
                                var status = array[0];
                                var value = array[1];
                                if (status == 'Ok'){
                                    $('#formPage3')[0].reset();
                                    $('#history-area tbody').prepend(value);
                                    alert('Area registrada con exito.');
                                }else{
                                    alert('Error, intentelo mas tarde.');
                                }
                            }
                        });
                        return false;
                    }else if(type == 'edition'){
                        var url = baseUrl + 'employee/editionArea/updateData';
                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: data,
                            beforeSend: function(){
                                $(document.body).append('<span class="loading"><div></div></span>');
                            },
                            success: function(response){
                                $('.loading').remove();
                                var value = eval(response);
                                var status = value[0];
                                var html = value[1];
                                var ids = value[2];
                                if(status == 'Ok'){
                                    $('#reg-'+ ids).replaceWith(html);
                                    $('#formPage3')[0].reset();
                                    alert('Area editada con exito');
                                }else if(status == 'sql'){
                                    alert('Error, intentelo mas tarde.');                           
                                }
                            }
                        });
                        return false;
                    }
                break;
                case 'mark':
                    if (type == 'register'){
                        var url = baseUrl + 'product/registerMark';
                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: data,
                            beforeSend: function(){
                                $(document.body).append('<span class="loading"><div></div></span>');
                            },
                            success: function(response){
                                $('.loading').remove();
                                var array = eval(response);
                                var status = array[0];
                                var value = array[1];
                                if (status == 'Ok'){
                                    $('#formPage3')[0].reset();
                                    $('#history-mark tbody').prepend(value);
                                    alert('Marca registrada con exito.');
                                }else{
                                    alert('Error, intentelo mas tarde.');
                                }
                            }
                        });
                        return false;
                    }else if(type == 'edition'){
                        var url = baseUrl + 'product/editionMark/updateData';
                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: data,
                            beforeSend: function(){
                                $(document.body).append('<span class="loading"><div></div></span>');
                            },
                            success: function(response){
                                $('.loading').remove();
                                var value = eval(response);
                                var status = value[0];
                                var html = value[1];
                                var ids = value[2];
                                if(status == 'Ok'){
                                    $('#reg-'+ ids).replaceWith(html);
                                    $('#formPage3')[0].reset();
                                    alert('Marca editada con exito');
                                }else if(status == 'sql'){
                                    alert('Error, intentelo mas tarde.');                           
                                }
                            }
                        });
                        return false;
                    }
                break;
            }
        }else{
            return false;
        }
    });
    
    /* Formulario para Pedidos */
    $('#formOrder').on('submit', function(){
        var id = $('input[name=idProduct]').val();
        var amount = $('input[name="amountOrder"]').val();
        var price = $('input[name="priceOrder"]').val();
        var cont = $('#processOrder > tbody tr').length;
        $.ajax({
            type: 'POST',
            url: baseUrl + 'order/addOrder',
            data: 'id='+id+'&amount='+amount+'&price='+price+'&cont='+cont,
            beforeSend: function(){
                $(document.body).append('<span class="loading"><div></div></span>');
            },
            success: function(response){
                $('.loading').remove();
                var array = eval(response);
                var row = array[0];
                $('#processOrder > tbody').append(row);
                $('#formOrder')[0].reset();
                $('#modal-add-order').modal('hide');
            }
        });
        return false;
    });
    /* Formulario para Cuentas bancarias */
    $('#formAccount').on('submit', function(){
        var id = $('input[name=id]').val();
        var bank = $('input[name="bank"]').val();
        var account = $('input[name="account"]').val();
        $.ajax({
            type: 'POST',
            url: baseUrl + 'pay/addAccount',
            data: 'id='+id+'&bank='+bank+'&account='+account,
            beforeSend: function(){
                $(document.body).append('<span class="loading"><div></div></span>');
            },
            success: function(response){
                $('.loading').remove();
                if (response > 0){
                    alert('Datos Bancarias registrados con exito.');
                    $('#formAccount')[0].reset();
                    $('#modal-add-account').modal('hide');
                    $('#tablaPagos').DataTable().ajax.reload();
                }else{
                    alert('Error, intentelo mas tarde.');
                }
            }
        });
        return false;
    });

    /* Formulario para Actualizar Precios */
    $('#formPrice').on('submit', function(){
        var id = $('input[name=idPriceP]').val();
        var priceP = $('input[name="price1"]').val();
        var priceT = $('input[name="price2"]').val();
        $.ajax({
            type: 'POST',
            url: baseUrl + 'product/updatePrice',
            data: 'id='+id+'&priceP='+priceP+'&priceT='+priceT,
            beforeSend: function(){
                $(document.body).append('<span class="loading"><div></div></span>');
            },
            success: function(response){
                $('.loading').remove();
                if (response > 0){
                    $('#formPrice')[0].reset();
                    $('#tablaProducto').DataTable().ajax.reload();
                    $('#modal-price').modal('hide');
                    alert('Precios actualizados con exito.');
                }else{
                    alert('No se actualizaron los precios, intentelo mas tarde.');
                }
            }
        });
        return false;
    });

    /* Formularios para Configuracion */
    $('#formSettingsPassword').on('submit', function(){
        var question = confirm('¿Desea actualizar su contraseña?');
        if(question == true){
            var pass = $('[name=pass-2]').val();
            var repass = $('[name=pass-3]').val();
            if(pass == repass){
                var data = $(this).serialize();
                $.ajax({
                    type: 'POST',
                    url: baseUrl+'setting/updatePassword',
                    data: data,
                    beforeSend: function(){
                        $(document.body).append('<span class="loading"><div></div></span>');
                    },
                    success: function(response){
                        $('.loading').remove();
                        if(response == 'ok'){
                            alert('Contraseña correctamente actualizada.');
                        }else if(response == 'password'){
                            alert('La contraseña actual no es correcta.');
                            $('[name=pass-1]').focus();
                        }else if(response == 'sql'){
                            alert('Problemas al actualizar la contraseña, intentelo mas tarde.');
                        }
                    }
                });
                return false;
            }else{
                alert('Las contraseñas no son iguales, confirme porfavor.');
                $('[name=pass-3]').focus();
                return false;
            }
        }else{
            return false;
        }
    });

    $('#formSettingsSeries').on('submit', function(){
        var question = confirm('¿Desea actualizar las series?');
        if(question == true){
            var data = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: baseUrl+'setting/updateSeries',
                data: data,
                beforeSend: function(){
                    $(document.body).append('<span class="loading"><div></div></span>');
                },
                success: function(response){
                    $('.loading').remove();
                    if(response == 'ok'){
                        alert('Series correctamente actualizadas.');
                    }else if(response == 'sql'){
                        alert('Problemas al actualizadar las series, intentelo mas tarde.');
                    }
                }
            });
            return false;
        }else{
            return false;
        }
    });

    $('#formSettingsMoney').on('submit', function(){
        var question = confirm('¿Desea actualizar los gastos economicos?');
        if(question == true){
            var data = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: baseUrl+'setting/updateDataMoney',
                data: data,
                beforeSend: function(){
                    $(document.body).append('<span class="loading"><div></div></span>');
                },
                success: function(response){
                    $('.loading').remove();
                    if(response == 'ok'){
                        alert('Gastos correctamente actualizados.');
                    }else if(response == 'sql'){
                        alert('Problemas al actualizadar los gastos, intentelo mas tarde.');
                    }
                }
            });
            return false;
        }else{
            return false;
        }
    });

    /* Busqueda de Ventas */
    $('#formSearchSale').on('submit', function(){
        var data = $(this).serialize();
        var doc = $('[name=sale-doc]').val();
        if(/-/.test(doc) || doc.length == 0){
            $.ajax({
                type: 'POST',
                url: baseUrl+'sale/searchSaleByDate',
                data: data,
                beforeSend: function(){
                    $(document.body).append('<span class="loading"><div></div></span>');
                },
                success: function(response){
                    var array = eval(response);
                    $('.loading').remove();
                    $('#dataRecordSale').html(array[0]);
                    $('#totalAmountSearchSale').html(array[1]);
                }
            });
            return false;
        }else{
            alert('El formato de documento es ____-________');
            return false;
        }
    });

    /* Busqueda de Compras */
    $('#formSearchShopping').on('submit', function(){
        var data = $(this).serialize();
        $.ajax({
            type: 'POST',
            url: baseUrl+'shopping/searchShoppingByDate',
            data: data,
            beforeSend: function(){
                $(document.body).append('<span class="loading"><div></div></span>');
            },
            success: function(response){
                var array = eval(response);
                $('.loading').remove();
                $('#dataRecordShopping').html(array[0]);
                $('#totalAmountSearchShopping').html(array[1]);
            }
        });
        return false;
    });
	
});
/* Reloj */
function timeView(){
    var date = new Date()
    var hour = date.getHours()
    var min = date.getMinutes()
    var seg = date.getSeconds()
    if(hour>12){
        var during = ' pm';
        hour = hour - 12;
    }else{
        var during = ' am';
    }
    if(hour < 10){
        hour = '0' + hour;
    }
    if(min < 10){
        min = '0' + min;
    }
    if(seg < 10){
        seg = '0' + seg;
    }

    var months = new Array ("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

    var viewDate = date.getDate() + ' de ' + months[date.getMonth()] + ' de ' + date.getFullYear();
    var viewTime = hour + ':' + min + ':' + seg + '' + during;

    $('#timeView').html(viewTime);
    $('#dateView').html(viewDate);
    setTimeout('timeView()',1000);
}
/* Agregar Ceros a una numeracion */
function addZeros(number, length){
    var my_string = ''+number;
    while(my_string.length<length) {
        my_string = '0'+my_string;
    }
    return my_string;
}

/* Ventanas Modales */
function openModalVisor(){
    $('#mb-prod').empty();
    $.ajax({
        type: 'POST',
        url: baseUrl + 'sale/loadCategoryVisor',
        beforeSend: function(){
            $(document.body).append('<span class="loading"><div></div></span>');
        },
        success: function(response){
            var array = eval(response);
            var visor = array[0];
            $('.loading').remove();
            $('#mb-categ .row').html(visor);
            // $('[name=marks]').html('<option value="">Elija una opcion</option>' + mark);
            $('#modal-visor').modal({
                show: true,
                backdrop: 'static'
            });
            $('#modal-visor').on('shown.bs.modal', function(){
                $('[name=name]').focus();
            });
        }
    });
    
}
function openModal(type){
    $('.formPage')[0].reset();
    $('.formPage').attr('type', 'register');

    switch(type){
        case 'product':
            $.ajax({
                type: 'POST',
                url: baseUrl + 'product/loadCategoryModal',
                beforeSend: function(){
                    $(document.body).append('<span class="loading"><div></div></span>');
                },
                success: function(response){
                    var array = eval(response);
                    var cate = array[0];
                    var mark = array[1];
                    $('.loading').remove();
                    $('[name=cate]').html('<option value="">Elija una opcion</option>' + cate);
                    $('[name=marks]').html('<option value="">Elija una opcion</option>' + mark);
                    $('#modal-product').modal({
                        show: true,
                        backdrop: 'static'
                    });
                    $('#modal-product').on('shown.bs.modal', function(){
                        $('[name=name]').focus();
                    });
                }
            });
        break;
        case 'provider':
            $('#modal-provider').modal({
                show: true,
                backdrop: 'static'
            });
            $('#modal-provider').on('shown.bs.modal', function(){
                $('[name=document]').focus();
            });
        break;
        case 'customer':
            $('#modal-customer').modal({
                show: true,
                backdrop: 'static'
            });
            $('#modal-customer').on('shown.bs.modal', function(){
                $('[name=document]').focus();
            });
        break;
        case 'employee':
            $.ajax({
                type: 'POST',
                url: baseUrl + 'employee/loadOptionEmployee',
                beforeSend: function(){
                    $(document.body).append('<span class="loading"><div></div></span>');
                },
                success: function(response){
                    $('.loading').remove();
                    var array = eval(response);
                    var opt1 = array[0];
                    var opt2 = array[1];
                    
                    $('[name=temp]').html('<option value="">Elija una opcion</option>' + opt1);
                    $('[name=area]').html('<option value="">Elija una opcion</option>' + opt2);
                    $('#modal-employee').modal({
                        show: true,
                        backdrop: 'static'
                    });
                    $('#modal-employee').on('shown.bs.modal', function(){
                        $('[name=document]').focus();
                    });
                }
            });
        break;
        case 'type-employee':
            $.ajax({
                type: 'POST',
                url: baseUrl + 'employee/loadTypeEmployee',
                beforeSend: function(){
                    $(document.body).append('<span class="loading"><div></div></span>');
                },
                success: function(response){
                    $('.loading').remove();
                    $('#history-type-employee tbody').html(response);
                    $('#modal-type-employee').modal({
                        show: true,
                        backdrop: 'static'
                    });
                    $('#modal-type-employee').on('shown.bs.modal', function(){
                        $('[name=nameT]').focus();
                    });
                }
            });
        break;
        case 'area':
            $.ajax({
                type: 'POST',
                url: baseUrl + 'employee/loadArea',
                beforeSend: function(){
                    $(document.body).append('<span class="loading"><div></div></span>');
                },
                success: function(response){
                    $('.loading').remove();
                    $('#history-area tbody').html(response);
                    $('#modal-area').modal({
                        show: true,
                        backdrop: 'static'
                    });
                    $('#modal-area').on('shown.bs.modal', function(){
                        $('[name=nameA]').focus();
                    });
                }
            });
        break;
        case 'category':
            $.ajax({
                type: 'POST',
                url: baseUrl + 'product/loadCategory',
                beforeSend: function(){
                    $(document.body).append('<span class="loading"><div></div></span>');
                },
                success: function(response){
                    $('.loading').remove();
                    $('#history-category tbody').html(response);
                    $('#modal-category').modal({
                        show: true,
                        backdrop: 'static'
                    });
                    $('#modal-category').on('shown.bs.modal', function(){
                        $('[name=nameC]').focus();
                    });
                }
            });
        break;
        case 'user':
            $('#modal-user').modal({
                show: true,
                backdrop: 'static'
            });
            $('#modal-user').on('shown.bs.modal', function () {
              $('[name=document]').focus();
            });
        break;
        case 'note':
            var serie = 5;
            $.ajax({
                type: 'POST',
                url: baseUrl +'setting/getSerie/'+serie,
                beforeSend: function(){
                    $(document.body).append('<span class="loading"><div></div></span>');
                },
                success: function(response){
                    $('.loading').remove();
                    $('[name=note-serie]').val(response);
                    $('#modal-note').modal({
                        show: true,
                        backdrop: 'static'
                    });
                    $('#modal-note').on('shown.bs.modal', function () {
                      $('#sale-type-document').focus();
                    });
                }
            });
        break;
        case 'mark':
            $.ajax({
                type: 'POST',
                url: baseUrl + 'product/loadMark',
                beforeSend: function(){
                    $(document.body).append('<span class="loading"><div></div></span>');
                },
                success: function(response){
                    $('.loading').remove();
                    $('#history-mark tbody').html(response);
                    $('#modal-mark').modal({
                        show: true,
                        backdrop: 'static'
                    });
                    $('#modal-mark').on('shown.bs.modal', function(){
                        $('[name=nameM]').focus();
                    });
                }
            });
        break;
    }
}

/* Modal para Configuracion */
function openModalSettings(){
    $('#modalSettings').modal({
        show: true,
        backdrop: 'static'
    });
}

function editPage(id, type){
    switch(type){
        case 'employee':
            $.ajax({
                type: 'POST',
                url: baseUrl+'employee/edition/bringData',
                data: 'id='+id,
                beforeSend: function(){
                    $(document.body).append('<span class="loading"><div></div></span>');
                },
                success: function(response){
                    $('.loading').remove();
                    var array = eval(response);
                    $('[name=id]').val(array[0]);
                    $('[name=document]').val(array[1]);
                    $('[name=name]').val(array[2]);
                    $('[name=lastname]').val(array[3]);
                    $('[name=direction]').val(array[4]);
                    $('[name=phone]').val(array[5]);
                    $('[name=sex]').val(array[6]);

                    $('[name=temp]').html('<option value="">Elija una opcion</option>' + array[7]);
                    $('[name=area]').html('<option value="">Elija una opcion</option>' + array[8]);

                    $('#formPage').attr('type', 'edition');

                    $('#modal-employee').modal({
                        show: true,
                        backdrop: 'static'
                    });
                }
            });
        break;
        case 'provider':
            $.ajax({
                type: 'POST',
                url: baseUrl+'provider/edition/bringData',
                data: 'id='+id,
                beforeSend: function(){
                    $(document.body).append('<span class="loading"><div></div></span>');
                },
                success: function(response){
                    $('.loading').remove();
                    var array = eval(response);
                    $('[name=id]').val(array[0]);
                    $('[name=document]').val(array[1]);
                    $('[name=name]').val(array[2]);
                    $('[name=direction]').val(array[3]);
                    $('[name=phone]').val(array[4]);
                    $('[name=email]').val(array[5]);

                    $('#formPage').attr('type', 'edition');

                    $('#modal-provider').modal({
                        show: true,
                        backdrop: 'static'
                    });
                }
            });
        break;
        case 'customer':
            $.ajax({
                type: 'POST',
                url: baseUrl+'customer/edition/bringData',
                data: 'id='+id,
                beforeSend: function(){
                    $(document.body).append('<span class="loading"><div></div></span>');
                },
                success: function(response){
                    $('.loading').remove();
                    var array = eval(response);
                    $('[name=id]').val(array[0]);
                    $('[name=document]').val(array[1]);
                    $('[name=name]').val(array[2]);
                    $('[name=direction]').val(array[3]);
                    $('[name=phone]').val(array[4]);

                    $('#formPage').attr('type', 'edition');

                    $('#modal-customer').modal({
                        show: true,
                        backdrop: 'static'
                    });
                }
            });
        break;
        case 'product':
            $.ajax({
                type: 'POST',
                url: baseUrl+'product/edition/bringData',
                data: 'id='+id,
                beforeSend: function(){
                    $(document.body).append('<span class="loading"><div></div></span>');
                },
                success: function(response){
                    $('.loading').remove();
                    var array = eval(response);
                    $('[name=id]').val(array[0]);
                    $('[name=name]').val(array[1]);
                    $('[name=unit]').val(array[2]);
                    $('[name=stockM]').val(array[3]);
                    $('[name=stockR]').val(array[4]);

                    $('[name=cate]').html('<option value="">Elija una opcion</option>' + array[5]);
                    $('[name=cate] option[value="'+ array[6] +'"]').attr('selected', true);

                    $('[name=marks]').html('<option value="">Elija una opcion</option>' + array[7]);
                    $('[name=marks] option[value="'+ array[8] +'"]').attr('selected', true);

                    $('#formPage').attr('type', 'edition');

                    $('#modal-product').modal({
                        show: true,
                        backdrop: 'static'
                    });
                }
            });
        break;
        case 'category':
            $.ajax({
                type: 'POST',
                url: baseUrl+'product/editionCategory/bringData',
                data: 'id='+id,
                beforeSend: function(){
                    $(document.body).append('<span class="loading"><div></div></span>');
                },
                success: function(response){
                    $('.loading').remove();
                    var array = eval(response);
                    $('[name=id-cat]').val(array[0]);
                    $('[name=nameC]').val(array[1]);
                    $('[name=file_cat]').attr('disabled', true);

                    $('#formPage2').attr('type', 'edition');
                    $('[name=nameC]').focus();
                }
            });
        break;
        case 'type-employee':
            $.ajax({
                type: 'POST',
                url: baseUrl+'employee/EditionTypeEmployee/bringData',
                data: 'id='+id,
                beforeSend: function(){
                    $(document.body).append('<span class="loading"><div></div></span>');
                },
                success: function(response){
                    $('.loading').remove();
                    var array = eval(response);
                    $('[name=id-type]').val(array[0]);
                    $('[name=nameT]').val(array[1]);

                    $('#formPage2').attr('type', 'edition');
                    $('[name=nameT]').focus();
                }
            });
        break;
        case 'area':
            $.ajax({
                type: 'POST',
                url: baseUrl+'employee/EditionArea/bringData',
                data: 'id='+id,
                beforeSend: function(){
                    $(document.body).append('<span class="loading"><div></div></span>');
                },
                success: function(response){
                    $('.loading').remove();
                    var array = eval(response);
                    $('[name=id-area]').val(array[0]);
                    $('[name=nameA]').val(array[1]);

                    $('#formPage3').attr('type', 'edition');
                    $('[name=nameA]').focus();
                }
            });
        break;
        case 'mark':
            $.ajax({
                type: 'POST',
                url: baseUrl+'product/EditionMark/bringData',
                data: 'id='+id,
                beforeSend: function(){
                    $(document.body).append('<span class="loading"><div></div></span>');
                },
                success: function(response){
                    $('.loading').remove();
                    var array = eval(response);
                    $('[name=id-mark]').val(array[0]);
                    $('[name=nameM]').val(array[1]);

                    $('#formPage3').attr('type', 'edition');
                    $('[name=nameM]').focus();
                }
            });
        break;
    }
}
/* Eliminar registros */
function deletePage(id, type){
    var ask = confirm('¿Desea eliminar este registro');
    switch(type){
        case 'product':
            if (ask == true){
                $.ajax({
                    type: 'POST',
                    url: baseUrl + 'product/delete/' + id,
                    beforeSend: function(){
                        $(document.body).append('<span class="loading"><div></div></span>');
                    },
                    success: function(response){
                        $('.loading').remove();
                        if(response > 0){
                            alert('Producto eliminado con exito');
                            $('#tablaProducto').DataTable().ajax.reload();
                        }else{
                            alert('Error, intentelo mas tarde.');
                        }
                    }
                });
            }else{
                return false;
            }
        break;
        case 'provider':
            if (ask == true){
                $.ajax({
                    type: 'POST',
                    url: baseUrl + 'provider/delete/' + id,
                    beforeSend: function(){
                        $(document.body).append('<span class="loading"><div></div></span>');
                    },
                    success: function(response){
                        $('.loading').remove();
                        if(response > 0){
                            alert('Proveedor eliminado con exito');
                            $('#tablaProveedor').DataTable().ajax.reload();
                        }else{
                            alert('Error, intentelo mas tarde.');
                        }
                    }
                });
            }else{
                return false;
            }
        break;
        case 'customer':
            if (ask == true){
                $.ajax({
                    type: 'POST',
                    url: baseUrl + 'customer/delete/' + id,
                    beforeSend: function(){
                        $(document.body).append('<span class="loading"><div></div></span>');
                    },
                    success: function(response){
                        $('.loading').remove();
                        if(response > 0){
                            alert('Cliente eliminado con exito');
                            $('#tablaCliente').DataTable().ajax.reload();
                        }else{
                            alert('Error, intentelo mas tarde.');
                        }
                    }
                });
            }else{
                return false;
            }
        break;
        case 'employee':
            if (ask == true){
                $.ajax({
                    type: 'POST',
                    url: baseUrl + 'employee/delete/' + id,
                    beforeSend: function(){
                        $(document.body).append('<span class="loading"><div></div></span>');
                    },
                    success: function(response){
                        $('.loading').remove();
                        if(response > 0){
                            alert('Empleado eliminado con exito');
                            $('#tablaEmpleado').DataTable().ajax.reload();
                        }else{
                            alert('Error, intentelo mas tarde.');
                        }
                    }
                });
            }else{
                return false;
            }
        break;
        case 'shopping':
            if (ask == true){
                $.ajax({
                    type: 'POST',
                    url: baseUrl + 'shopping/delete/' + id,
                    beforeSend: function(){
                        $(document.body).append('<span class="loading"><div></div></span>');
                    },
                    success: function(response){
                        $('.loading').remove();
                        if(response > 0){
                            $('#reg-'+id).remove();
                        }else{
                            alert('Problemas del sistema al eliminar esta compra.');
                        }
                    }
                });
            }else{
                return false;
            }
        break;
        case 'sale':
            if (ask == true){
                $.ajax({
                    type: 'POST',
                    url: baseUrl + 'sale/delete/' + id,
                    beforeSend: function(){
                        $(document.body).append('<span class="loading"><div></div></span>');
                    },
                    success: function(response){
                        $('.loading').remove();
                        if(response > 0){
                            $('#reg-'+id).remove();
                        }else{
                            alert('Problemas del sistema al eliminar esta venta.');
                        }
                    }
                });
            }else{
                return false;
            }
        break;
        /* Falta */
        case 'category':
            if (ask == true){
                $.ajax({
                    type: 'POST',
                    url: baseUrl + 'product/deleteCategory/' + id,
                    beforeSend: function(){
                        $(document.body).append('<span class="loading"><div></div></span>');
                    },
                    success: function(response){
                        $('.loading').remove();
                        if(response > 0){
                            $('#reg-'+id).remove();
                        }else{
                            alert('Problemas del sistema al eliminar esta categoria.');
                        }
                    }
                });
            }else{
                return false;
            }
        break;
        case 'user':
            if (ask == true){
                $.ajax({
                    type: 'POST',
                    url: baseUrl + 'user/delete/' + id,
                    beforeSend: function(){
                        $(document.body).append('<span class="loading"><div></div></span>');
                    },
                    success: function(response){
                        $('.loading').remove();
                        if(response > 0){
                            alert('Usuario eliminado con exito');
                            $('#tablaUsuario').DataTable().ajax.reload();
                        }else{
                            alert('Error, intentelo mas tarde.');
                        }
                    }
                });
            }else{
                return false;
            }
        break;
    }
}
/* Cargas */
function loadCustomerDocSale(value){
    $.ajax({
        type: 'POST',
        url: baseUrl + 'sale/loadCustomerDocSale',
        data: 'value=' + value, 
        success: function(response){
            var array = eval(response);
            var id = array[0];
            var doc = array[1];
            var name = array[2];
            var direc = array[3];
            var status = array[4];

            if (status > 0){
                $('[name=customer-id]').val(id);
                $('[name=customer-name]').val(name);
                $('[name=customer-direction]').val(direc);
            }else{
                $('#customer-id').val(0);
            }
        }
    });
}

function loadEmployeeDoc(){
    var value = $('[name=document]').val();
    $.ajax({
        type: 'POST',
        url: baseUrl + 'user/loadEmployeeDoc',
        data: 'value=' + value, 
        success: function(response){
            var array = eval(response);
            var id = array[0];
            var name = array[1];
            var status = array[2];

            if (status > 0){
                $('[name=id]').val(id);
                $('[name=nameEmployee]').val(name);
                $('[name=name]').removeAttr('disabled');
                $('[name=role]').removeAttr('disabled');
                $('[name=pass]').removeAttr('disabled');
                $('[name=name]').focus();
            }else{
                return false;
            }
        }
    });
}

function searchUserData(value){
    if(value.length > 0){
        var baseUrl = $('#formRegisterEdition').attr('baseUrl');
        $.ajax({
            type: 'POST',
            url: baseUrl+'user/searchData',
            data: 'value='+value,
            success: function(response){
                $('#sale-customer-name').val(response);
            }
        });
    }
}

function productMovementCode(code, type, search){
    if(code.length > 0){
        switch(type){
            case 'shopping':
                $.ajax({
                    type: 'POST',
                    url: baseUrl+'shopping/searchProductCode/'+search,
                    data: 'code='+code,
                    success: function(response){
                        var array = eval(response);
                        if(array[0] > 0){
                            $('[name=head-id]').val(array[0]);
                            $('[name=head-description]').val(array[1]);
                            $('[name=head-unit]').val(array[2]);
                            $('[name=head-price]').val(array[3]);
                            $('#shopping-add').removeAttr('disabled');
                        }else{
                            $('[name=head-id]').val('');
                            $('[name=head-description]').val('');
                            $('[name=head-unit]').val('');
                            $('[name=head-price]').val('');
                            $('#shopping-add').attr('disabled', 'disabled');
                        }
                    }
                });
            break;
        }
    }
}

/* Calculos Matematicos */
function calculateProductSale(){
    var amount = $('[name=product-amount]').val();
    var price = $('[name=product-price]').val();
    var subtotal = amount*price;
    var subtotalVal = subtotal.toFixed(2);
    $('[name=product-subtotal]').val(subtotalVal);

    var discount = $('[name=product-discount]').val();
    var total = subtotal-discount;
    var totalVal = total.toFixed(2);
    $('[name=product-total]').val(totalVal);
}
function calculateProductSaleTotal(){
    var subtotal = $('[name=product-total]').val();
    var price = $('[name=product-price]').val();
    var amount = subtotal/price;
    var amountVal = amount.toFixed(4);
    $('[name=product-amount]').val(amountVal);
    $('[name=product-subtotal]').val(subtotal);
}
function calculateSubtotal(){
    var price = $('[name=head-price]').val();
    var amount = $('[name=head-amount]').val();
    var result = price*amount;
    var result = result.toFixed(2);
    $('[name=head-subtotal]').val(result);
}
function updateFinalShopping(){
    // SUMATORIA DE LOS SUBTOTALES MENOS DESCUENTOS //
    var subs = document.getElementsByClassName('body-subtotal');
    var itemCount = subs.length;
    var result_subtotal = 0;
    var totalRows = 0;
    for(var i = 0; i < itemCount; i++){
        totalRows = totalRows + 1;
        result_subtotal = result_subtotal +  parseFloat(subs[i].value);
    }
    // SUMATORIA DE LOS DESCUENTOS //
    result_descuento = 0;

    var show_total = Math.round((result_subtotal+result_descuento)*100)/100;
    var show_descuento = Math.round((result_descuento)*100)/100; 
    var show_neto = Math.round((result_subtotal)*100)/100;

    $('#final-subtotal').val(show_total.toFixed(2));
    $('#final-total').val(show_neto.toFixed(2));
}
function updateFinalSale(){
    // SUMATORIA DE LOS SUBTOTALES MENOS DESCUENTOS //
    var subs = document.getElementsByClassName('body-subtotal');
    var itemCount = subs.length;
    var result_subtotal = 0;
    var totalRows = 0;
    for(var i = 0; i < itemCount; i++){
        totalRows = totalRows + 1;
        result_subtotal = result_subtotal +  parseFloat(subs[i].value);
    }

    // SUMATORIA DE LOS DESCUENTOS //
    var subsDesc = document.getElementsByClassName('body-descu');
    var itemCountDesc = subsDesc.length;
    var result_descuento = 0;
    var totalRowsDesc = 0;
    for(var j = 0; j < itemCountDesc; j++){
        totalRowsDesc = totalRowsDesc + 1;
        result_descuento = result_descuento +  parseFloat(subsDesc[j].value);
    }
    /////////////////////////////

    var dGlobal = 0;

    var show_total = Math.round((result_subtotal+result_descuento)*100)/100;
    var show_descuento = Math.round((result_descuento)*100)/100; 
    var show_neto = Math.round((result_subtotal)*100)/100;
    var show_neto_neto = Math.round((result_subtotal-dGlobal)*100)/100;

    $('#final-subtotal').val(show_total.toFixed(2));
    //$('#igv-total').val(show_igv);
    $('#final-discount').val(show_descuento.toFixed(2));
    $('#final-total').val(show_neto.toFixed(2));
    $('#neto-total').val(show_neto_neto.toFixed(2));
}

function calculatePrice(priceCompra, priceTransporte, impuestoRenta, gastosAdmin, porcentaje){
    var pventa;

    if(porcentaje == 1){
        pventa = parseFloat((15 * priceCompra) / 100);
    }else if(porcentaje == 2){
        pventa = parseFloat((20 * priceCompra) / 100);
    }else if(porcentaje == 3){
        pventa = parseFloat((25 * priceCompra) / 100);
    }else if(porcentaje == 4){
        pventa = parseFloat((30 * priceCompra) / 100);
    }
    
    var total = parseFloat(priceCompra) + parseFloat(priceTransporte) + parseFloat(impuestoRenta) + parseFloat(gastosAdmin) + pventa;

    $('#priceV').val(total.toFixed(2));
}

function calculateChangeSale(){
    var total = parseFloat($('#sale-total-charge').val());
    var received = parseFloat($('#sale-total-received').val());
    var change = received-total;
    change = change.toFixed(2);
    $('#sale-total-change').val(change);
}

function activarRadio(porcentaje){
    if(porcentaje == 1){
        $('#pVenta1').attr('checked', 'checked');
        $('#pVenta2').removeAttr('checked', 'checked');
        $('#pVenta3').removeAttr('checked', 'checked');
        $('#pVenta4').removeAttr('checked', 'checked');
    }else if(porcentaje == 2){
        $('#pVenta2').attr('checked', 'checked');
        $('#pVenta1').removeAttr('checked', 'checked');
        $('#pVenta3').removeAttr('checked', 'checked');
        $('#pVenta4').removeAttr('checked', 'checked');
    }else if(porcentaje == 3){
        $('#pVenta3').attr('checked', 'checked');
        $('#pVenta1').removeAttr('checked', 'checked');
        $('#pVenta2').removeAttr('checked', 'checked');
        $('#pVenta4').removeAttr('checked', 'checked');
    }else if(porcentaje == 4){
        $('#pVenta4').attr('checked', 'checked');
        $('#pVenta1').removeAttr('checked', 'checked');
        $('#pVenta2').removeAttr('checked', 'checked');
        $('#pVenta3').removeAttr('checked', 'checked');
    }

    calculateTotalPrice();
}

function calculateTotalPrice(){
    var priceCompra = $('#priceC').val();
    var priceTransporte = $('#priceT').val();
    var impuestoRenta = $('#impuestoR').val();
    var gastosAdmin = $('#gastosA').val();
    var porcentaje = $('[name=pVenta]:checked').val();

    calculatePrice(priceCompra, priceTransporte, impuestoRenta, gastosAdmin, porcentaje);
}

/* Funcion para Accesos */
function accessDOM(value){
    if(value == 'ADMIN'){
        $('#accessDOM').hide();
    }else if(value == 'USER'){
        $('#accessDOM').show();
    }
}

/* Modulo de Compras */
function typePayViewShopping(value){
    if(value == 1){
        $.ajax({
            type: 'POST',
            url: baseUrl+'shopping/loadMethods/echo',
            beforeSend: function(){
                $(document.body).append('<span class="loading"><div></div></span>');
            },
            success: function(response){
                $('.loading').remove();
                $('#shopping-type-credit').html('<option value="0"></option>');
                $('.typeCreditViewShopping').hide();
                $('.typeMethodViewShopping').show();
                $('#shopping-method-payment').html(response);
            }
        });
    }else if(value == 2){
        $.ajax({
            type: 'POST',
            url: baseUrl+'shopping/loadCredits',
            beforeSend: function(){
                $(document.body).append('<span class="loading"><div></div></span>');
            },
            success: function(response){
                $('.loading').remove();
                $('#shopping-type-credit').html(response);
                $('.typeCreditViewShopping').show();
                $('#shopping-type-credit').focus();
                $('#shopping-payed').val(0);
                $('.typeMethodViewShopping').hide();
                $('#shopping-method').html('<option value="0"></option>');
            }
        });
    }
}

function searchProvider(value){
    if(value.length > 0){
        $.ajax({
            type: 'POST',
            url: baseUrl + 'shopping/searchProvider',
            data: 'value=' + value,
            success: function(response){
                var array = eval(response);
                $('#shopping-provider-id').val(array[0]);
                $('#shopping-provider-name').val(array[1]);
            }
        });
        return false;
    }
}

function shoppingDetail(){
    var id = $('[name=head-id]').val();
    var codeBarcode = $('[name=head-code]').val();
    var description = $('[name=head-description]').val();
    var unit = $('[name=head-unit]').val();
    var price = $('[name=head-price]').val();
    var desc = $('[name=head-desc]').val();
    var amount = $('[name=head-amount]').val();
    var transp = $('[name=head-transporte]').val();
    var price1 = $('[name=head-price1]').val();
    var price2 = $('[name=head-price2]').val();

    var subtotal = price * amount;
    subtotal = subtotal.toFixed(2);
    if(price.length > 0){
        if(amount.length > 0){
            if(amount > 0){

                var check = $('[name=product_search_shopping]:checked').val();

                $('#saveMovement').attr('onClick', 'saveShopping();');
                var row = '<tr id="detailReg-'+id+'"><td style="display:none;"><input type="text" name="body-id[]" class="body-id" value="'+id+'" readonly></td><td><input type="text" name="body-code-barcode[]" value="'+codeBarcode+'" style="text-transform:uppercase;" class="form-control" readonly></td><td><input type="text" name="body-description[]" value="'+description+'" class="form-control" readonly></td><td><input type="text" name="body-unit[]" value="'+unit+'" class="form-control" readonly></td><td><input type="text" name="body-price[]" value="'+price+'" class="form-control bodyPrice" style="text-align:right;" readonly></td><td><input type="text" name="body-amount[]" value="'+amount+'" class="form-control bodyAmount" style="text-align:right;" onKeyUp="changeAmountTable(this.value, '+id+')" onClick="changeAmountTable(this.value, '+id+');" readonly></td><td><input type="text" min="0" name="body-transporte[]" value="'+transp+'" class="form-control body-transporte" style="text-align:right;" readonly></td><td><input type="text" min="0" name="body-price1[]" value="'+price1+'" class="form-control body-price1" style="text-align:right;" readonly></td><td><input type="text" min="0" name="body-price2[]" value="'+price2+'" class="form-control body-price2" style="text-align:right;" readonly></td><td><input type="text" min="0" name="body-subtotal[]" value="'+subtotal+'" class="form-control body-subtotal" style="text-align:right;" readonly></td><td align="center" style="background-color:#eee;"><button class="btn btn-default buttonDelete" style="width:60px; height:28px; float:left;" title="Eliminar" onClick="removeProductDetail('+id+')"><span class="glyphicon glyphicon-remove-sign"></span></button></td></tr>';
                $('#tableDetailMovement tbody').prepend(row);
                $('[form=shoppingDetail]')[0].reset();

                updateFinalShopping();

                $('#shopping-add').attr('disabled', 'disabled');

                if(check == '2'){
                    $('[name=head-description]').focus();
                    $('input:radio[name=product_search_shopping][value="2"]').prop('checked', true);
                }else{
                    $('[name=head-code]').focus();
                    $('input:radio[name=product_search_shopping][value="1"]').prop('checked', true);
                }

                //SUMATORIA DE LOS SUBTOTALES MENOS DESCUENTOS
                var subs = document.getElementsByClassName('body-subtotal');
                var itemCount = subs.length;
                var totalRows = 0;
                for(var i = 0; i < itemCount; i++){
                    totalRows = totalRows + 1;
                }
                if(totalRows > 0){
                    $('#saveMovement').removeAttr('disabled');
                }
                /////////////////////////////

            }else{
                $('[name=head-amount]').focus();
            }
        }else{
            $('[name=head-amount]').focus();
        }
    }else{
        $('[name=head-price]').focus();
    }
}

function saveShopping(){
    var question = confirm('¿Desea guardar esta compra?');
    if(question == true){
        var v1 = $('#shopping-serie').val();
        var v2 = $('#shopping-number').val();
        var v3 = $('#shopping-provider-code').val();
        if((v1.length * v2.length) > 0){
            if (v3.length > 0){
                var idArray = new Array();
                $("input[name*='body-id']").each(function() {idArray.push($(this).val());});
                var priceArray = new Array();
                $("input[name*='body-price']").each(function() {priceArray.push($(this).val());});
                var amountArray = new Array();
                $("input[name*='body-amount']").each(function() {amountArray.push($(this).val());});
                var unitArray = new Array();
                $("input[name*='body-unit']").each(function() {unitArray.push($(this).val());});
                var transpArray = new Array();
                $("input[name*='body-transporte']").each(function() {transpArray.push($(this).val());});
                var priceMinArray = new Array();
                $("input[name*='body-price1']").each(function() {priceMinArray.push($(this).val());});
                var priceMaxArray = new Array();
                $("input[name*='body-price2']").each(function() {priceMaxArray.push($(this).val());});

                var date = $('#shopping-date').val(); /*Fecha*/
                var idProv = $('#shopping-provider-id').val(); /*Id Proveedor*/
                var type = $('#shopping-type').val(); /*Tipo Compra (Factura-Boleta-Otros)*/
                var serie = $('#shopping-serie').val(); /*Serie comprobante*/
                var number = $('#shopping-number').val(); /*Numero comprobante*/
                var mov = $('#shopping-movement').val(); /*Movimiento (Contado-Credito)*/
                var credit = $('#shopping-type-credit').val(); /*Tipo credito*/
                var method = $('#shopping-method-payment').val(); /*Forma de pago*/
                var currency = $('#shopping-currency').val(); /*Moneda*/

                $.ajax({
                    type: 'POST',
                    url: baseUrl+'shopping/saveShopping',
                    data: 'id='+idArray+'&price='+priceArray+'&amount='+amountArray+'&unit='+unitArray+'&transp='+transpArray+'&price1='+priceMinArray+'&price2='+priceMaxArray+'&date='+date+'&idProv='+idProv+'&type='+type+'&serie='+serie+'&number='+number+'&mov='+mov+'&credit='+credit+'&method='+method+'&currency='+currency,
                    beforeSend: function(){
                        $(document.body).append('<span class="loading"><div></div></span>');
                    },
                    success: function(response){
                        $('.loading').remove();
                        if(response == 'ok'){
                            alert('La compra fue registrada con exito.');
                            $('.form-control').attr('disabled', 'disabled');
                            $('#shopping-add').attr('disabled', 'disabled');
                            $('.buttonDelete').attr('disabled', 'disabled');
                            $('#saveMovement').attr('disabled', 'disabled');
                            $('#shopping-type-credit').attr('disabled', 'disabled');
                            $('#shopping-method').attr('disabled', 'disabled');
                            $('#shopping-currency').attr('disabled', 'disabled');
                        }else if(response == 'repeat'){
                            alert('Error.. El numero de factura ya existe).');
                        }else if(response == 'error'){
                            alert('Problemas del sistema al registrar esta compra.');
                        }
                    }
                });
                return false;
            }else{
                alert('Ingrese RUC del Proveedor.');
                $('#shopping-provider-code').focus();
            }
        }else{
            alert('Ingrese la serie y numero del comprobante de compra.');
            $('#shopping-serie').focus();
        }
    }else{
        return false;
    }
}

/* Modulo de Ventas */
function addProductCart(){
    var description = $('#product-name').val();
    var id = $('#product-id-data').val();
    var unit = $('#product-unit-data').val();
    var price = $('#product-price').val();
    var amount = $('#product-amount').val();
    var desc = $('#product-discount').val();
    var pbase = $('[name=product-price-base]').val();

    if(desc.length == 0){
        desc = 0;
    }
    var subtotal = ((price * amount) - desc).toFixed(2);
    if(amount.length > 0){
        if(amount > 0){
            // var idPro = document.getElementsByClassName('body-id');
            // var itemCountPro = idPro.length;
            // var nItem = 1;
            // for(var i = 0; i < itemCountPro; i++){
            //     nItem++;
            // }
            // nItem = addZeros(nItem, 3);

            if(id > 0){
                var row = '<tr id="detailReg-'+id+'"><td align="center" style="background-color:#eee;"><button class="btn btn-default btn-block buttonDelete" style="height:28px; padding:4px;" title="Eliminar" onClick="removeProductDetail('+id+')"><span class="glyphicon glyphicon-remove-sign"></span></button></td><td style="display:none;"><input type="text" name="body-id[]" class="body-id" value="'+id+'" readonly></td><td><input type="text" name="body-description[]" value="'+description+'" class="form-control" readonly></td><td style="display:none;"><input type="text" name="body-unit[]" value="'+unit+'" class="form-control" readonly></td><td style="display:none;"><input type="text" name="body-price[]" value="'+price+'" class="form-control bodyPrice" style="text-align:right;" readonly></td><td style="display: none;"><input type="text" name="body-pbase[]" value="'+pbase+'" class="form-control bodyPbase" style="text-align:right;" readonly></td><td><input type="text" name="body-amount[]" value="'+amount+'" class="form-control bodyAmount" style="text-align:right;" readonly></td><td style="display:none;"><input type="text" name="body-descu[]" class="body-descu form-control" style="text-align:right;" value="'+desc+'"></td><td><input type="text" min="0" name="body-subtotal[]" value="'+subtotal+'" class="form-control body-subtotal" style="text-align:right;" readonly></td></tr>';
                $('#DetailProductSale').append(row);
                $('#product-name').val('');
                buttonStatusCancelPay('true');
                cleanProductSale();
                updateFinalSale();
                $('#modal-order').modal('hide');
            }
        }else{
            $('#product-amount').select();
        }
    }else{
        $('#product-amount').select();
    }
}
function openModalPaySale(){
    var dato = $('#customer-document').val();

    if(dato.length == 11){
        $("#sale-type-document option[value='B']").hide();
        $("#sale-type-document option[value='A']").hide();
        $("#sale-type-document option[value='F']").show();
        $('#sale-type-document').val('F');
    }else if (dato.length == 8 || dato.length == 9){
        $("#sale-type-document option[value='F']").hide();
        $("#sale-type-document option[value='B']").show();
        $("#sale-type-document option[value='A']").show();
        $('#sale-type-document').val('B');
    }else if($('#customer-id').val() == 1){
        $("#sale-type-document option[value='B']").hide();
        $("#sale-type-document option[value='F']").hide();
        $("#sale-type-document option[value='A']").show();
        $('#sale-type-document').val('A');
    }

    var value = $('#sale-type-document').val();
    if(value == 'B'){
        var serie = 2;
    }else if(value == 'F'){
        var serie = 1;
    }else if(value == 'A'){
        var serie = 3;
    }else{
        var serie = 0;
    }

    $.ajax({
        type: 'POST',
        url: baseUrl +'setting/getSerie/'+serie,
        beforeSend: function(){
            $(document.body).append('<span class="loading"><div></div></span>');
        },
        success: function(response){
            $('.loading').remove();
            var total = $('#neto-total').val();
            $('#sale-total-charge').val(total);
            $('#sale-total-received').val(total);
            $('#sale-number-invoice').val(response);
            $('#modal-method-pay').modal({
                show: true,
                backdrop: 'static'
            });
            $('#sale-total-change').val('0.00');
            $('#typeCreditView').hide();
            $('#sale-method-pay').val(1);
            $('#modal-method-pay').on('shown.bs.modal', function () {
              $('#sale-type-document').focus();
            });
        }
    });
}
$('#sale-type-document').on('change', function(){
    var value = $(this).val();
    
    if(value == 'B'){
        //$('#type-guide-r').hide();
        var serie = 2;
    }else if(value == 'F'){
        //$('#type-guide-r').show();
        var serie = 1;
    }else if(value == 'A'){
        //$('#type-guide-r').hide();
        var serie = 3;
    }

    $.ajax({
        type: 'POST',
        url: baseUrl+'setting/getSerie/'+serie,
        beforeSend: function(){
            $(document.body).append('<span class="loading"><div></div></span>');
        },
        success: function(response){
            $('.loading').remove();
            $('#sale-number-invoice').val(response);
        }
    });
});
function typePayView(value){
    if(value == 1){
        $.ajax({
            type: 'POST',
            url: baseUrl+'sale/loadMethods/echo',
            beforeSend: function(){
                $(document.body).append('<span class="loading"><div></div></span>');
            },
            success: function(response){
                $('.loading').remove();
                $('#sale-type-credit').html('<option value="0"></option>');
                $('#typeCreditView').hide();
                $('#sale-payed').val(1);
                $('#typeMethodView').show();
                $('#sale-method-pay-2').html(response);
            }
        });
    }else if(value == 2){
        $.ajax({
            type: 'POST',
            url: baseUrl+'sale/loadCredits',
            beforeSend: function(){
                $(document.body).append('<span class="loading"><div></div></span>');
            },
            success: function(response){
                $('.loading').remove();
                $('#sale-type-credit').html(response);
                $('#typeCreditView').show();
                $('#sale-type-credit').focus();
                $('#sale-payed').val(0);
                $('#typeMethodView').hide();
                $('#sale-method-pay-2').html('<option value="0"></option>');
            }
        });
    }
}
function cancelPaySale(){
    var question = confirm('¿Desea cancelar la venta?');
    if(question == true){
        location.href = 'sale';
    }else{
        return false;
    }
}

    /* Estado de botones en Ventas */
function buttonStatusCancelPay(status){
    $('#button-ok').attr('btn-status', status);
    $('#button-voucher-ok').attr('btn-status', status);
    $('#button-cancel').attr('btn-status', status);
    if(status == "true"){
        $('#button-ok').removeAttr('disabled').attr('onClick', "openModalPaySale();");
        $('#button-cancel').removeAttr('disabled').attr('onClick', "cancelPaySale();");

        if($('[name=optradioCustomer][value=1]').is(':checked')){
            $('#button-voucher-ok').attr('disabled','disabled');
        }else{
            $('#button-voucher-ok').removeAttr('disabled').attr('onClick', "openModalVoucher();");
        }
    }else if(status == "false"){
        $('#button-ok').attr('disabled', 'disabled').removeAttr('onClick');
        $('#button-voucher-ok').attr('disabled', 'disabled').removeAttr('onClick');
        $('#button-cancel').attr('disabled', 'disabled').removeAttr('onClick'); 
    }
}

    /* Limpiar los campos*/
function cleanProductSale(){
    // $('#product-amount').attr('disabled', 'disabled');
    // $('#product-discount').attr('disabled', 'disabled');
    // $('#product-price').attr('disabled', 'disabled');
    $('#add-product-button').attr('disabled', 'disabled');
    $('#add-product-button').attr('btn-status', "false");

    $('#product-amount').val('0.00');
    $('#product-price').val('0.00');
    $('#product-subtotal').val('0.00');
    $('#product-discount').val('0.00');
    $('#product-total').val('0.00');

    $('#product-id-data').val(0);
    $('#product-description-data').val('');
    $('#product-unit-data').val('');
    $('#product-price-data').val('');
}

/* Eliminar item de venta*/
function removeProductDetail(id){
    $('#detailReg-'+id).remove();

    var idPro = document.getElementsByClassName('body-id');
    var itemCountPro = idPro.length;
    var totalId = 0;
    for(var i = 0; i < itemCountPro; i++){
        totalId = totalId + 1;
    }
    if(totalId == 0){
        buttonStatusCancelPay("false");
        $('#button-ok').attr('disabled', 'disabled').removeAttr('onClick');
        $('#button-cancel').attr('disabled', 'disabled').removeAttr('onClick');
        $('#saveMovement').attr('disabled', 'disabled');
        $('#saveMovement').removeAttr('onClick');
    }
    updateFinalSale();
}

/* Guardar Venta */
function saveSale(){
    var baseUrl = $('#formPage').attr('baseUrl');
    var cliId = $('#customer-id').val();
    var cliName = $('#customer-name').val();
    var cliRuc = $('#customer-document').val();
    var cliDirection = $('#customer-direction').val();
    var tipoV = $('#sale-type-document').val();

    var orders = $('#orders').val();
    if (orders < 1){
        var order = 0;
    }else{
        var order = orders;
    }

    if(tipoV == 'B'){
        if(cliRuc.length > 9){
            alert('Para boleta, solo se acepta clientes con DNI o Carnet de Ext., ingrese otro.');
            $('#modal-method-pay').modal('hide');
            $('#customer-document').focus();
        }else{
            var question = confirm('¿Desea guardar esta venta?');
        }
    }else if(tipoV == 'F'){
        if(cliRuc.length == 11){
            var question = confirm('¿Desea guardar esta venta?');
        }else{
            alert('Para factura, solo se acepta clientes con RUC, ingrese otro.');
            $('#modal-method-pay').modal('hide');
            $('#customer-document').focus();
        }
    }else{
        var question = confirm('¿Desea guardar esta venta?');
    }

    /* Solucionar esta parte */
    if (cliId == 1){
        $("#sale-type-document option[value='F']").show();
        /*$('#customer-id').val(0);*/
        if(question == true){
            var idArray = new Array();
            $("input[name*='body-id']").each(function() {idArray.push($(this).val());});
            var priceArray = new Array();
            $("input[name*='body-price']").each(function() {priceArray.push($(this).val());});
            var unitArray = new Array();
            $("input[name*='body-unit']").each(function() {unitArray.push($(this).val());});
            var descArray = new Array();
            $("input[name*='body-descu']").each(function() {descArray.push($(this).val());});
            var amountArray = new Array();
            $("input[name*='body-amount']").each(function() {amountArray.push($(this).val());});
             var pbaseArray = new Array();
            $("input[name*='body-pbase']").each(function() {pbaseArray.push($(this).val());});

            var mov = $('#sale-method-pay').val();
            var payed = $('#sale-payed').val();
            var received = $('#sale-total-received').val();
            var numberSale = $('#sale-number-invoice').val();
            var doc = $('#sale-type-document').val();
            
            var credit = $('#sale-type-credit').val();
            var method = $('#sale-method-pay-2').val();
            var date = $('#sale-date').val();

            $.ajax({
                type: 'POST',
                url: baseUrl+'sale/saveSale',
                data: 'id='+idArray+'&price='+priceArray+'&pbase='+pbaseArray+'&unit='+unitArray+'&desc='+descArray+'&amount='+amountArray+'&cliId='+cliId+'&cliName='+cliName+'&cliRuc='+cliRuc+'&cliDirection='+cliDirection+'&mov='+mov+'&received='+received+'&doc='+doc+'&payed='+payed+'&numberSale='+numberSale+'&credit='+credit+'&method='+method+'&date='+date+'&order='+order,
                beforeSend: function(){
                    $(document.body).append('<span class="loading"><div></div></span>');
                },
                success: function(response){
                    var array = eval(response);
                    var status = array[0];
                    var id = array[1];
                    if(status == 'ok'){
                        if(doc != 'A'){
                            window.open('sale/printTicket/'+id, 'Ticket de Venta', 'width=735, height=600');
                        }
                        location.href = 'sale';
                    }else if(status == 'saleNumber'){
                        alert('El numero de comprobante ya existe, ingrese otro.');
                        $('.loading').remove();
                    }else if(status == 'sale'){
                        alert('Error al registrar la venta, intentelo mas tarde.');
                        $('.loading').remove();
                    }
                }
            });
            return false;
        }else{
            return false;
        }
    }else{
        if(question == true){
            var idArray = new Array();
            $("input[name*='body-id']").each(function() {idArray.push($(this).val());});
            var priceArray = new Array();
            $("input[name*='body-price']").each(function() {priceArray.push($(this).val());});
            var unitArray = new Array();
            $("input[name*='body-unit']").each(function() {unitArray.push($(this).val());});
            var descArray = new Array();
            $("input[name*='body-descu']").each(function() {descArray.push($(this).val());});
            var amountArray = new Array();
            $("input[name*='body-amount']").each(function() {amountArray.push($(this).val());});
             var pbaseArray = new Array();
            $("input[name*='body-pbase']").each(function() {pbaseArray.push($(this).val());});

            var mov = $('#sale-method-pay').val();
            var payed = $('#sale-payed').val();
            var received = $('#sale-total-received').val();
            var numberSale = $('#sale-number-invoice').val();
            var doc = $('#sale-type-document').val();
            
            var credit = $('#sale-type-credit').val();
            var method = $('#sale-method-pay-2').val();
            var date = $('#sale-date').val();

            $.ajax({
                type: 'POST',
                url: baseUrl+'sale/saveSale',
                data: 'id='+idArray+'&price='+priceArray+'&pbase='+pbaseArray+'&unit='+unitArray+'&desc='+descArray+'&amount='+amountArray+'&cliId='+cliId+'&cliName='+cliName+'&cliRuc='+cliRuc+'&cliDirection='+cliDirection+'&mov='+mov+'&received='+received+'&doc='+doc+'&payed='+payed+'&numberSale='+numberSale+'&credit='+credit+'&method='+method+'&date='+date+'&order='+order,
                beforeSend: function(){
                    $(document.body).append('<span class="loading"><div></div></span>');
                },
                success: function(response){
                    var array = eval(response);
                    var status = array[0];
                    var id = array[1];
                    if(status == 'ok'){
                        if(doc != 'A'){
                            window.open('sale/printTicket/'+id, 'Ticket de Venta', 'width=735, height=600');
                        }
                        location.href = 'sale';
                    }else if(status == 'saleNumber'){
                        alert('El numero de comprobante ya existe, ingrese otro.');
                        $('.loading').remove();
                    }else if(status == 'sale'){
                        alert('Error al registrar la venta, intentelo mas tarde.');
                        $('.loading').remove();
                    }
                }
            });
            return false;
        }else{
            return false;
        }
    }
}

/* Informacion Ventas */
function openRecordSale(){
    $.ajax({
        type: 'POST',
        url: baseUrl+'sale/loadRecordSaleToday',
        beforeSend: function(){
            $(document.body).append('<span class="loading"><div></div></span>');
        },
        success: function(response){
            var array = eval(response);
            $('.loading').remove();
            $('#dataRecordSale').html(array[0]);
            $('#totalAmountSearchSale').html(array[1]);
            $('#modalRecordSale').modal({
                show: true,
                backdrop:'static'
            });
        }
    });
}
/* Detalle Ventas */
function seeDetailSale(value){
    var baseUrl = $('#formPage').attr('baseUrl');
    $.ajax({
        type: 'POST',
        url: baseUrl+'sale/loadRecordSaleDetail',
        data: 'id='+value,
        beforeSend: function(){
            $(document.body).append('<span class="loading"><div></div></span>');
        },
        success: function(response){
            $('.loading').remove();
            $('#dataRecordSaleDetail').html(response);
            $('#modalRecordSaleDetail').modal({
                show: true,
                backdrop:'static'
            });
        }
    });
}

/* Informacion Compras */
function openRecordShopping(){
    $.ajax({
        type: 'POST',
        url: baseUrl+'shopping/loadRecordShoppingToday',
        beforeSend: function(){
            $(document.body).append('<span class="loading"><div></div></span>');
        },
        success: function(response){
            var array = eval(response);
            $('.loading').remove();
            $('#dataRecordShopping').html(array[0]);
            $('#totalAmountSearchShopping').html(array[1]);
            $('#modalRecordShopping').modal({
                show: true,
                backdrop:'static'
            });
            $('[name=shopping-from]').val(array[2]);
            $('[name=shopping-to]').val(array[2]);
        }
    });
}
/* Detalle Compras */
function seeDetailShopping(value){
    $.ajax({
        type: 'POST',
        url: baseUrl+'shopping/loadRecordShoppingDetail',
        data: 'id='+value,
        beforeSend: function(){
            $(document.body).append('<span class="loading"><div></div></span>');
        },
        success: function(response){
            $('.loading').remove();
            $('#dataRecordShoppingDetail').html(response);
            $('#modalRecordShoppingDetail').modal({
                show: true,
                backdrop:'static'
            });
        }
    });
}

/* Nota de Credito */
function getNoteReason(type){
    switch(type){
        case 'FC':
            var serie = 5;
        break;
        case 'BC':
            var serie = 6;
        break;
    }
    $.ajax({
        type: 'POST',
        url: baseUrl+'setting/getSerie/'+serie,
        beforeSend: function(){
            $(document.body).append('<span class="loading"><div></div></span>');
        },
        success: function(response){
            $('[name=note-serie]').val(response);
            $('.loading').remove();
            /*$.ajax({
                type: 'POST',
                url: baseUrl+'notes/getNoteReason/'+type+'/echo',
                success: function(data){
                    $('.loading').remove();
                    $('[name=note-5]').html(data);
                }
            });
            */
            return false;
        }
    });
}

/* Impresion */
function printTicket(id){
    window.open('sale/printTicket/'+id, 'Ticket de Venta', 'width=735, height=600');
}


/* Reportes */
function htmlReport(type){
    $.ajax({
        type: 'POST',
        url: baseUrl+'report/'+type,
        beforeSend: function(){
            $(document.body).append('<span class="loading"><div></div></span>');
        },
        success: function(response){
            $('.loading').remove();
            $('#formRegisterEdition').html(response);
        }
    });
}
/* Reporte Venta */
function processReportSales(){
    var baseUrl = $('#formRegisterEdition').attr('baseUrl');
    var from = $('[name=sale-from]').val();
    var to = $('[name=sale-to]').val();
    var currency = $('[name=currency]').val();
    $.ajax({
        type: 'POST',
        url: baseUrl+'report/processReportSales',
        data: 'from='+from+'&to='+to+'&currency='+currency,
        beforeSend: function(){
            $(document.body).append('<span class="loading"><div></div></span>');
        },
        success: function(response){
            $('.loading').remove();
            $('#dataRecordSale').html(response);
        }
    });
}
function processReportSalesExcel(){
    var baseUrl = $('#formRegisterEdition').attr('baseUrl');
    var from = $('[name=sale-from]').val();
    var to = $('[name=sale-to]').val();
    var currency = $('[name=currency]').val();
    window.open(baseUrl+'report/processReportSalesExcel/'+from+'/'+to+'/'+currency);
}

/* Reporte Compra */
function processReportShopping(){
    var baseUrl = $('#formRegisterEdition').attr('baseUrl');
    var from = $('[name=shopping-from]').val();
    var to = $('[name=shopping-to]').val();
    var currency = $('[name=currency]').val();
    $.ajax({
        type: 'POST',
        url: baseUrl+'report/processReportShopping',
        data: 'from='+from+'&to='+to+'&currency='+currency,
        beforeSend: function(){
            $(document.body).append('<span class="loading"><div></div></span>');
        },
        success: function(response){
            $('.loading').remove();
            $('#dataRecordSale').html(response);
        }
    });
}
function processReportShoppingExcel(){
    var baseUrl = $('#formRegisterEdition').attr('baseUrl');
    var from = $('[name=shopping-from]').val();
    var to = $('[name=shopping-to]').val();
    var currency = $('[name=currency]').val();
    window.open(baseUrl+'report/processReportShoppingExcel/'+from+'/'+to+'/'+currency);
}

/* Reporte Ventas por Tipo */
function processReportSalesType(){
    var baseUrl = $('#formRegisterEdition').attr('baseUrl');
    var mov = $('#sale-movement').val();
    var from = $('[name=sale-from]').val();
    var to = $('[name=sale-to]').val();
    var currency = $('[name=currency]').val();
    $.ajax({
        type: 'POST',
        url: baseUrl+'report/processReportSalesType',
        data: 'mov='+mov+'&from='+from+'&to='+to+'&currency='+currency,
        beforeSend: function(){
            $(document.body).append('<span class="loading"><div></div></span>');
        },
        success: function(response){
            $('.loading').remove();
            $('#dataRecordSale').html(response);
        }
    });
}
function processReportSalesTypeExcel(){
    var baseUrl = $('#formRegisterEdition').attr('baseUrl');
    var mov = $('#sale-movement').val();
    var from = $('[name=sale-from]').val();
    var to = $('[name=sale-to]').val();
    var currency = $('[name=currency]').val();
    window.open(baseUrl+'report/processReportSalesTypeExcel/'+mov+'/'+from+'/'+to+'/'+currency);
}

/* Reporte de Ventas por Cliente */
function processReportSalesCustomer(){
    var baseUrl = $('#formRegisterEdition').attr('baseUrl');
    var customer = $('#customer').val();
    var from = $('[name=sale-from]').val();
    var to = $('[name=sale-to]').val();
    var currency = $('[name=currency]').val();
    $.ajax({
        type: 'POST',
        url: baseUrl+'report/processReportSalesCustomer',
        data: 'customer='+customer+'&from='+from+'&to='+to+'&currency='+currency,
        beforeSend: function(){
            $(document.body).append('<span class="loading"><div></div></span>');
        },
        success: function(response){
            $('.loading').remove();
            $('#dataRecordSale').html(response);
        }
    });
}
function processReportSalesCustomerExcel(){
    var baseUrl = $('#formRegisterEdition').attr('baseUrl');
    var customer = $('#customer').val();
    var from = $('[name=sale-from]').val();
    var to = $('[name=sale-to]').val();
    var currency = $('[name=currency]').val();
    window.open(baseUrl+'report/processReportSalesCustomerExcel/'+customer+'/'+from+'/'+to+'/'+currency);
}

/* Reporte Compras por Proveedor */
function processReportShoppingProvider(){
    var baseUrl = $('#formRegisterEdition').attr('baseUrl');
    var provider = $('#customer').val();
    var from = $('[name=sale-from]').val();
    var to = $('[name=sale-to]').val();
    var currency = $('[name=currency]').val();
    $.ajax({
        type: 'POST',
        url: baseUrl+'report/processReportShoppingProvider',
        data: 'provider='+provider+'&from='+from+'&to='+to+'&currency='+currency,
        beforeSend: function(){
            $(document.body).append('<span class="loading"><div></div></span>');
        },
        success: function(response){
            $('.loading').remove();
            $('#dataRecordSale').html(response);
        }
    });
}
function processReportShoppingProviderExcel(){
    var baseUrl = $('#formRegisterEdition').attr('baseUrl');
    var provider = $('#customer').val();
    var from = $('[name=sale-from]').val();
    var to = $('[name=sale-to]').val();
    var currency = $('[name=currency]').val();
    window.open(baseUrl+'report/processReportShoppingProviderExcel/'+provider+'/'+from+'/'+to+'/'+currency);
}

/* Reporte de Ventas por Usuario */
function processReportSalesUser(){
    var baseUrl = $('#formRegisterEdition').attr('baseUrl');
    var customer = $('#customer').val();
    var from = $('[name=sale-from]').val();
    var to = $('[name=sale-to]').val();
    var currency = $('[name=currency]').val();
    $.ajax({
        type: 'POST',
        url: baseUrl+'report/processReportSalesUser',
        data: 'customer='+customer+'&from='+from+'&to='+to+'&currency='+currency,
        beforeSend: function(){
            $(document.body).append('<span class="loading"><div></div></span>');
        },
        success: function(response){
            $('.loading').remove();
            $('#dataRecordSale').html(response);
        }
    });
}
function processReportSalesUserExcel(){
    var baseUrl = $('#formRegisterEdition').attr('baseUrl');
    var customer = $('#customer').val();
    var from = $('[name=sale-from]').val();
    var to = $('[name=sale-to]').val();
    var currency = $('[name=currency]').val();
    window.open(baseUrl+'report/processReportSalesUserExcel/'+customer+'/'+from+'/'+to+'/'+currency);
}

/* Reporte de Empleados */
function processReportEmployee(){
    var baseUrl = $('#formRegisterEdition').attr('baseUrl');
    var from = $('[name=emp-from]').val();
    var to = $('[name=emp-to]').val();
    $.ajax({
        type: 'POST',
        url: baseUrl+'report/processReportEmployee',
        data: 'from='+from+'&to='+to,
        beforeSend: function(){
            $(document.body).append('<span class="loading"><div></div></span>');
        },
        success: function(response){
            $('.loading').remove();
            $('#dataRecordSale').html(response);
        }
    });
}
function processReportEmployeeExcel(){
    var baseUrl = $('#formRegisterEdition').attr('baseUrl');
    var from = $('[name=emp-from]').val();
    var to = $('[name=emp-to]').val();
    window.open(baseUrl+'report/processReportEmployeeExcel/'+from+'/'+to);
}

/* Reporte de Utilidad */
function processReportUtility(){
    var baseUrl = $('#formRegisterEdition').attr('baseUrl');
    $.ajax({
        type: 'POST',
        url: baseUrl+'report/processReportUtility',
        beforeSend: function(){
            $(document.body).append('<span class="loading"><div></div></span>');
        },
        success: function(response){
            $('.loading').remove();
            $('#dataRecordSale').html(response);
        }
    });
}
function processReportUtilityExcel(){
    var baseUrl = $('#formRegisterEdition').attr('baseUrl');
    window.open(baseUrl+'report/processReportUtilityExcel');
}

/* Reporte Resumen de Clientes */
function processReportResumenCustomer(){
    var baseUrl = $('#formRegisterEdition').attr('baseUrl');
    var from = $('[name=value-from]').val();
    var to = $('[name=value-to]').val();
    var type = $('[name=value-type]').val();
    $.ajax({
        type: 'POST',
        url: baseUrl+'report/processReportResumenCustomer',
        data: 'from='+from+'&to='+to+'&type='+type,
        beforeSend: function(){
            $(document.body).append('<span class="loading"><div></div></span>');
        },
        success: function(response){
            $('.loading').remove();
            $('#dataRecordSale').html(response);
        }
    });
}
function processReportResumenCustomerExcel(){
    var baseUrl = $('#formRegisterEdition').attr('baseUrl');
    var from = $('[name=value-from]').val();
    var to = $('[name=value-to]').val();
    var type = $('[name=value-type]').val();
    window.open(baseUrl+'report/processReportResumenCustomerExcel/'+from+'/'+to+'/'+type);
}

/* Reporte Resumen de Proveedores */
function processReportResumenProviders(){
    var baseUrl = $('#formRegisterEdition').attr('baseUrl');
    var from = $('[name=value-from]').val();
    var to = $('[name=value-to]').val();
    var type = $('[name=value-type]').val();
    $.ajax({
        type: 'POST',
        url: baseUrl+'report/processReportResumenProviders',
        data: 'from='+from+'&to='+to+'&type='+type,
        beforeSend: function(){
            $(document.body).append('<span class="loading"><div></div></span>');
        },
        success: function(response){
            $('.loading').remove();
            $('#dataRecordSale').html(response);
        }
    });
}
function processReportResumenProvidersExcel(){
    var baseUrl = $('#formRegisterEdition').attr('baseUrl');
    var from = $('[name=value-from]').val();
    var to = $('[name=value-to]').val();
    var type = $('[name=value-type]').val();
    window.open(baseUrl+'report/processReportResumenProvidersExcel/'+from+'/'+to+'/'+type);
}

/* Reporte de Inventario */
function processReportInventary(type){
    var baseUrl = $('#formRegisterEdition').attr('baseUrl');
    var type = $('[name=sale-type]').val();
    var model = $('[name=sale-model]').val();
    var from = $('[name=sale-from]').val();
    var to = $('[name=sale-to]').val();
    $.ajax({
        type: 'POST',
        url: baseUrl+'report/processReportInventary',
        data: 'from='+from+'&to='+to+'&type='+type+'&model='+model,
        beforeSend: function(){
            $(document.body).append('<span class="loading"><div></div></span>');
        },
        success: function(response){
            $('.loading').remove();
            $('#spaceInventary').html(response);
        }
    });
}
function processReportInventaryExcel(type){
    var baseUrl = $('#formRegisterEdition').attr('baseUrl');
    var type = $('[name=sale-type]').val();
    var model = $('[name=sale-model]').val();
    var from = $('[name=sale-from]').val();
    var to = $('[name=sale-to]').val();
    window.open(baseUrl+'report/processReportInventaryExcel/'+type+'/'+model+'/'+from+'/'+to);
}

function downloadExcelShopping(id){
    var baseUrl = $('#formRegisterEdition').attr('baseUrl');
    window.open(baseUrl+'report/downloadExcelShopping/'+id);
}

function downloadExcelSale(id){
    var baseUrl = $('#formRegisterEdition').attr('baseUrl');
    window.open(baseUrl+'report/downloadExcelSale/'+id);
}

function changePrice(id){
    $.ajax({
        type: 'POST',
        url: baseUrl + 'product/loadPriceProduct',
        data: 'id=' + id,
        beforeSend: function(){
            $(document.body).append('<span class="loading"><div></div></span>');
        },
        success: function(response){
            var array = eval(response);
            $('.loading').remove();
            var p1 = array[0];
            var p2 = array[1];
            $('#price1').val(p1);
            $('#price2').val(p2);
            $('#idPriceP').val(id);
            $('#modal-price').modal({
                show: true,
                backdrop: 'static'
            });
            $('#modal-price').on('shown.bs.modal', function(){
                $('#price1').select();
            });
        }
    });
}
function orderDetail(id){
    $.ajax({
        type: 'POST',
        url: baseUrl + 'order/orderDetail',
        data: 'id='+id,
        beforeSend: function(){
            $(document.body).append('<span class="loading"><div></div></span>');
        },
        success: function(response){
            $('.loading').remove();
            var array = eval(response);
            var row = array[0];
            $('#DetailProductSale').append(row);
            buttonStatusCancelPay('true');
            cleanProductSale();
            updateFinalSale();
        }
    });
}

function openModalOrder(){
    var ask = $('#processOrder > tbody tr').length;

    if (ask > 0){
        $('#modal-order').modal({
            show: true,
            backdrop: 'static'
        });
        $('#modal-order').on('shown.bs.modal', function(){
        $('[name=customerOrder]').focus();
    });
    }else{
        alert('No agrego ningún producto');
    }
}

function addCartOrder(id){
    $('#modal-add-order').modal({
        show: true,
        backdrop: 'static'
    });
    $('#modal-add-order').on('shown.bs.modal', function(){
        $('[name=amountOrder]').focus();
    });
    $('input[name=idProduct]').val(id);
}

function saveOrder(){
     var question = confirm('¿Desea procesar este pedido?');
     if(question == true){
        var idArray = new Array();
        $("input[name*='body-id']").each(function() {idArray.push($(this).val());});
        var amountArray = new Array();
        $("input[name*='body-amount']").each(function() {amountArray.push($(this).val());});
        var priceArray = new Array();
        $("input[name*='body-price']").each(function() {priceArray.push($(this).val());});

        var customer = $("input[name='customerOrder']").val();

        $.ajax({
                type: 'POST',
                url: baseUrl+'order/saveOrder',
                data: 'id='+idArray+'&amount='+amountArray+'&price='+priceArray+'&customer='+customer,
                beforeSend: function(){
                    $(document.body).append('<span class="loading"><div></div></span>');
                },
                success: function(response){
                    var array = eval(response);
                    var status = array[0];
                    var id = array[1];
                    if(status == 'ok'){
                        window.open('order/printTicket/'+id, 'Nota de Pedido', 'width=735, height=500');
                        location.href = 'order';
                    }else if(status == 'number'){
                        alert('El numero de comprobante ya existe, ingrese otro.');
                        $('.loading').remove();
                    }else if(status == 'error'){
                        alert('Error al registrar la venta, intentelo mas tarde.');
                        $('.loading').remove();
                    }
                }
            });
            return false;

    }else{
        return false;
    }
}

function cancelCharge(id){
    var question = confirm('¿Desea cancelar este Cobro?');
    if(question == true){
        $.ajax({
            type: 'POST',
            url: baseUrl+'charge/cancel/' + id,
            beforeSend: function(){
                $(document.body).append('<span class="loading"><div></div></span>');
            },
            success: function(response){
                $('.loading').remove();
                if(response > 0){
                    alert('Cobro cancelado con exito');
                    $('#tablaCobros').DataTable().ajax.reload();
                }else{
                    alert('Error, intentelo mas tarde.');
                }
            }
        });
        return false;
    }else{
        return false;
    }
}

function cancelPay(id){
    var question = confirm('¿Desea cancelar este Pago?');
    if(question == true){
        $.ajax({
            type: 'POST',
            url: baseUrl+'pay/cancel/' + id,
            beforeSend: function(){
                $(document.body).append('<span class="loading"><div></div></span>');
            },
            success: function(response){
                $('.loading').remove();
                if(response > 0){
                    alert('Pago cancelado con exito');
                    $('#tablaPagos').DataTable().ajax.reload();
                }else{
                    alert('Error, intentelo mas tarde.');
                }
            }
        });
        return false;
    }else{
        return false;
    }
}

function addAccount(id){
    $('#modal-add-account').modal({
        show: true,
        backdrop: 'static'
    });
    $('#modal-add-account').on('shown.bs.modal', function(){
        $('[name=bank]').focus();
    });
    $('[name=id]').val(id);
}

function voucherPay(id){
    window.open('pay/printVoucher/'+id, 'Ticket de Pago', 'width=735, height=600');
}

function selectCategory(id){
    $.ajax({
        type: 'POST',
        url: baseUrl+'sale/productCategoryVisor/' + id,
        beforeSend: function(){
            $(document.body).append('<span class="loading"><div></div></span>');
        },
        success: function(response){
            $('.loading').remove();
            var array = eval(response);
            $('#resultProduct').html(array[0]);
        }
    });
}

function clickTable(id){
    $.ajax({
        type: 'POST',
        url: baseUrl+'sale/loadProductSaleVisor/' + id,
        beforeSend: function(){
            $(document.body).append('<span class="loading"><div></div></span>');
        },
        success: function(response){
            $('.loading').remove();
            var array = eval(response);
            var status = array[0];
            var id = array[1];
            var name = array[2];
            var unit = array[3];
            var price = array[4];
            var ref = array[5];
            var precio_sf = parseFloat(price);
            var precio = precio_sf.toFixed(2);

            if (status > 0){
                $('#modal-order').modal({
                    show: true,
                    backdrop: 'static'
                });
                $('#modal-order').on('shown.bs.modal', function(){
                    $('[name=product-amount]').focus();
                    $('#product-name').val(name);
                    $('#product-id-data').val(id);
                    $('#product-description-data').val(name);
                    $('#product-unit-data').val(unit);
                    $('#product-price-data').val(price);
                    $('[name=product-price-base]').val(ref);
                    $('[name=product-price]').val(precio);
                });
               
                // $('[name=product-amount]').removeAttr('disabled');
                // $('[name=product-price]').removeAttr('disabled');
                // $('[name=product-discount]').removeAttr('disabled');
                // $('[name=product-amount]').val('1.00');
                // $('[name=product-discount]').val('0.00');
                // $('[name=product-total]').val('0.00');
                // $('#add-product-button').removeAttr('disabled');
                // $('#add-product-button').attr('btn-status', 'true');
                // $('[name=product-price]').select();
                // $('#modal-visor').modal('hide');
            }
        }
    });
}

function addCustomer(){
    $('#base-customers').removeClass('customer-oculto');
    $('#customer-document').focus();
}