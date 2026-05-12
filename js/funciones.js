//busqueda de usuarios
$(buscar_usuario());

function buscar_usuario(consultaUsuarios){
	$.ajax({
		url:'buscarUsuarios.php',
		type:'POST',
		dataType:'html',
		data: {consultaUsuarios: consultaUsuarios},
	})
	.done(function(respuesta){
		$("#datosUsuarios").html(respuesta);
	})
	.fail(function(){
		console.log("error");
	})
}

$(document).on('keyup', '#buscarUsuario', function(){
	var valor = $(this).val();
		if (valor != "") {
			buscar_usuario(valor);
		} else {
			buscar_usuario();
		}
});
$(document).ready(function(){
			var f = new Date();
			var fecha = (f.getFullYear()  + "/" + (f.getMonth() +1) + "/" +f.getDate() );
			$('#fechaUsuario').val(fecha);
});
$(document).on("click", "#insertarUsuario", function(){
	if ($("#nombreUsuario").val()=='') {
			alert('Ingresa el nombre completo del Usuario');
			$("#nombreUsuario").focus();
			return false;
		}
	if ($("#usuarioUsuario").val()=='') {
			alert('Ingresa tu usuario');
			$("#usuarioUsuario").focus();
			return false;
		}
	if ($("#contraUsuario").val()=='') {
			alert('Ingresa tu contraseña');
			$("#contraUsuario").focus();
			return false;
		}
	if ($("#repcontraUsuario").val()=='') {
			alert('Ingresa de nuevo la contraseña');
			$("#repcontraUsuario").focus();
			return false;
		}
	if ($("#contraUsuario").val()!=$("#repcontraUsuario").val()) {
			alert('Has que las contraseñas coincidan');
			$("#repcontraUsuario").focus();
			return false;
	}
		$.ajax({
				type: "POST",
				url: "funciones.php",
				data:({
					funcion: "InsertarUsuario",
					Nombres:$('#nombreUsuario').val(),
					Usuario:$('#usuarioUsuario').val(),
					Contra:$('#contraUsuario').val() ,
					Fecha:$('#fechaUsuario').val() 
				}),
				dataType: 'html',
				success:function(msg){
					alert(msg);
					window.location.reload(false);
				}
			});
});

//Mostrar menu de ventas
$(function()
  {
     $("div#mostrarV").click(function()
                         {
                            $("#menuVentas").slideDown();
                            $("#menuAdministracion").slideUp();
                            $("#menuProductos").slideUp();
                            $("#menuCompras").slideUp();
                            return false;
                         }); 
     $("div#mostrarA").click(function()
                         {
                            $("#menuAdministracion").slideDown();
                            $("#menuVentas").slideUp();
							$("#menuProductos").slideUp();
                            $("#menuCompras").slideUp();
                            return false;
                         }); 
     $("div#mostrarP").click(function()
                         {
                            $("#menuProductos").slideDown();
                            $("#menuAdministracion").slideUp();
                            $("#menuVentas").slideUp();
							$("#menuCompras").slideUp();
                            return false;
                         }); 
     $("div#subir").click(function()
                         {
                            $("#menuAdministracion").slideUp();
                            $("#menuVentas").slideUp();
                            $("#menuProductos").slideUp();
							$("#menuCompras").slideUp();
                            return false;
                         }); 
  });


//buscar clientes en datalist
$(document).on("mouseover", "#clientes", function(){
	var datos = "nom="+$.trim($(this).val());
	$.ajax({
		url:"funciones.php",
		type: "POST",
		data:({
			funcion: "DataListClientes",
			datos: datos
		}),
	})
	.done(function(res){
		$("#datosClientes").html(res);
	})
	.fail(function(){
		alert(res);
	});
});
//buscar vendedores en datalist
$(document).on("mouseover", "#vendedor", function(){
	var datos = "nom="+$.trim($(this).val());
	$.ajax({
		url:"funciones.php",
		type: "POST",
		data:({
			funcion: "DataListVendedores",
			datos: datos
		}),
	})
	.done(function(res){
		$("#datosVendedor").html(res);
	})
	.fail(function(){
		alert(res);
	});
});
//buscar productos en datalist
$(document).on("mouseover", "#Productos", function(){
	var datos = "nom="+$.trim($(this).val());
	$.ajax({
		url:"funciones.php",
		type: "POST",
		data:({
			funcion: "DataListProductos",
			datos: datos
		}),
	})
	.done(function(res){
		$("#datosProductos").html(res);
	})
	.fail(function(){
		alert(res);
	});
});

$(document).on("click", "#borrarUsuario",function(){
	var id = $(this).attr("idregistroUsuario");
	$.ajax({
		type: "POST",
		url: "funciones.php",
		data:({
		funcion: "BorrarUsuario",
		id: id
		}),
		dataType: "html",
		success: function(msg){
			alert(msg);
			window.location.reload(false);
		}
	})
	});
