$(function(){
	$('#formLogin').on('submit', function(){
		var data = $(this).serialize();
		$.ajax({
			type: 'POST',
			url: baseUrl+'login/logeo',
			data: data,
			success: function(response){
				var array = eval(response);
				var status = array[0];
				/*var area = array[1];*/
				if(status == 'ok'){
					alert('Bienvenido');
					document.location.href = baseUrl + 'home'; //POR DEFECTO
				}else if(status == 'null'){
					$('.loading').remove();
					alert('Su usuario no esta activo.');
				}
				else if(status == 'login'){
					$('.loading').remove();
					alert('Su usuario y/o contraseña son incorrectos.');
				}
			}
		});
		return false;
	});

});

