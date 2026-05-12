// JavaScript Document
	function Totales(){
		var total = 0;
		var importe = 0;
		var iva = 0;
		var cant_row=0;
		$(".detalle_productos").each(function(index, element) {
			var cant_row = Quita_Moneda($(this).find(".cantidad").val());
			var precio = Quita_Moneda($(this).find(".precio").val());
			var lista = Quita_Moneda($(this).find(".precio").attr("lista"));
			var descuento = Quita_Moneda($(this).find(".descuento").val());
			//alert(lista);
			if(cant_row<=25){
				//CAMBIAMOS A PRECIO 2 
				if(lista==1 && cant_row>12){
					precio = $(this).find(".precios_listas option[value=2]").text();
				}else{
					precio = $(this).find(".precios_listas option[value="+lista+"]").text();
				}
			//lista2
			}else if(cant_row>=26 && cant_row<=50){
				if(lista<3){
					precio = $(this).find(".precios_listas option[value=3]").text();
				}else{
					precio = $(this).find(".precios_listas option[value="+lista+"]").text();
				}
			//lista3
			}else if(cant_row>=51){
				if(lista<4){
					precio = $(this).find(".precios_listas option[value=4]").text();
				}else{
					precio = $(this).find(".precios_listas option[value="+lista+"]").text();
				}
			//lista4
			}
			if(descuento>0){
				var precio_row = (precio*(descuento/100));
				precio = precio-precio_row;
			}
			
			$(this).find(".precio").val(Formato_Moneda(precio,2));
			var importe = Quita_Moneda(precio)*cant_row;
			$(this).find(".importe").val(Formato_Moneda(importe,2));
			total += precio*cant_row;
		});
		$("#efectivo").val(Formato_Moneda(total,2));
		$("#total").val(Formato_Moneda(total,2));
		$("#total_m").val(Formato_Moneda(total,2));
		$("#total_p").val(Formato_Moneda(total,2));
		$("#subtotal").val(Formato_Moneda(total-iva,2));
	}
	function Formato_Moneda(n,c,d,t){
		var c = isNaN(c = Math.abs(c)) ? 2 : c, 
		d = d == undefined ? "." : d, 
		t = t == undefined ? "," : t, 
		s = n < 0 ? "-" : "", 
		i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", 
		j = (j = i.length) > 3 ? j % 3 : 0;
		return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
	}
	function Quita_Moneda(n){
		n=String(n);
		var s=parseFloat(n.replace(",","").replace("$",""));
		if(isNaN(s))s=0;
		return s;
	}
	function Carga_Caja(tipo){
		if(Quita_Moneda($("#total").val())==0){
			alertify.alert("Punto de Venta ","Ingresa un Producto");	
			$('#myModal_caja').modal('hide');
			 $('#cierra_venta').click();
			$("#Productos").focus();
			return false;
		}
		if($("#pago option:selected")=="Credito"){
			if(Quita_Moneda($("#total").val())<=Quita_Moneda($("#disponible_credito").val())){
				$("#efectivo").val(0);
				return false;
			}else{
				alertify.alert("Punto de Venta ","Credito No Disponible Venta de Contado");	
				return false;
			}
		}
		if($("#clientes").val()=='' && tipo=='Ticket'){
			$("#clientes").val('C000001-VENTA DE MOSTRADOR');
		}
		if($("#clientes").val()==''){
			alertify.alert("Punto de Venta ","Ingresa un Cliente");	
			//$('#myModal_caja').modal('hide');
			return false;
		}
		$("#efectivo").focus().select();
		//$('#myModal_caja').modal('show');
		$("#myModalLabel").text(tipo);
	}
	$(document).on("click","#tabla_detalles .eliminar",function(){
		var id = $(this).attr("aleatorio");
		alertify.confirm('Estas Seguro de Eliminar el Producto', '', function(){
			$(".detalle_productos").each(function(index, element) {
				if($(this).attr("aleatorio")==id){
					$(this).remove();
				}
			});	
		//$("#tabla_detalles #"+id)..remove();
		 alertify.success('Borrando');
		 Totales();
		  }
			, function(){ alertify.error('Cancelado')});			
	});   
	$(document).on("click","#salir",function(){
		alertify.confirm('Pregunta del Sistema','¿Estas seguro de Cancelar la Venta?', function(){ 
	
			window.location="index.php";
		}, function(){ 
		//alertify.error('Cancelado')
		});
	});   		
	$(document).on("keydown",".cantidad",function(e) {
		if(e.which == 13) {
			if($(this).val()>0){
				var precio = Quita_Moneda($(this).parent().parent().parent().find(".precio").val());
				var importe = $(this).val()*precio;
				$(this).parent().parent().parent().find(".importe").val(Formato_Moneda(importe,2));
			}else{
				$(this).val(Formato_Moneda(1,2));
			}
		Totales();	
		}
	}); 
	$(document).on("keydown",".descuento",function(e) {
		if(e.which == 13) {
			var descuento = Quita_Moneda($(this).val());
			var cantidad = Quita_Moneda($(this).parent().parent().parent().find(".cantidad").val());
			var precio = Quita_Moneda($(this).parent().parent().parent().find(".precio").attr("precio"));
			var importe = $(this).val()*precio;
			if(descuento>0){
				importe = (precio*(descuento/100))*cantidad;
				var precio_row = (precio*(descuento/100));
				precio = precio-precio_row;
				$(this).parent().parent().parent().find(".importe").val(Formato_Moneda(importe,2));
				$(this).parent().parent().parent().find(".precio").val(Formato_Moneda(precio,2));
			}else{
				$(this).val(Formato_Moneda(0,2));
				$(this).parent().parent().parent().find(".precio").val(Formato_Moneda(precio,2));
			}
		Totales();	
		}
	}); 
	$(document).on("blur",".descuento",function(e) {
		if(e.which == 13) {
			var descuento = Quita_Moneda($(this).val());
			var cantidad = Quita_Moneda($(this).parent().parent().parent().find(".cantidad").val());
			var precio = Quita_Moneda($(this).parent().parent().parent().find(".precio").attr("precio"));
			var importe = $(this).val()*precio;
			if(descuento>0){
				importe = (precio*(descuento/100))*cantidad;
				var precio_row = (precio*(descuento/100));
				precio = precio-precio_row;
				$(this).parent().parent().parent().find(".importe").val(Formato_Moneda(importe,2));
				$(this).parent().parent().parent().find(".precio").val(Formato_Moneda(precio,2));
			}else{
				$(this).val(Formato_Moneda(0,2));
				$(this).parent().parent().parent().find(".precio").val(Formato_Moneda(precio,2));
			}
		Totales();	
		}
	}); 
	
	$(document).on("blur",".cantidad",function(e) {
		if(e.which == 13) {
			if($(this).val()>0){
				var precio = Quita_Moneda($(this).parent().parent().parent().find(".precio").val());
				var importe = $(this).val()*precio;
				$(this).parent().parent().parent().find(".importe").val(Formato_Moneda(importe,2));
			}else{
				$(this).val(Formato_Moneda(1,2));
			}
		Totales();	
		}
	}); 
	$(document).on("keyup",".cantidad",function(e) {
    		e.preventDefault();
		 // alert(e.which + ": " + String.fromCharCode(e.which));
		  if(e.which==46){
		 	 var id = $(this).parent().parent().parent().attr("codigo");
			 $("#"+id).remove();
			  Totales();
			  $("#Productos").focus();
		  }else if(e.which==38){
			  $("#Productos").focus();
		  }else if(e.which==39){
			  $(this).parent().parent().parent().find(".descuento").select();
		  }else if(e.which==37){
			  $(this).parent().parent().parent().find(".descuento").select();
		  }else if(e.which==13){
			 $("#Productos").select(); 
		  }
		  //derecha 39 izquierda 37
	});
	$(document).on("keyup",".descuento",function(e) {
    		e.preventDefault();
		  if(e.which==37){
			  $(this).parent().parent().parent().find(".cantidad").select();
		  }else if(e.which==39){
			  $(this).parent().parent().parent().find(".cantidad").select();
		  }else if(e.which==13){
			 $("#Productos").select(); 
		  }
	});
	$(document).on("keyup","#Productos",function(e) {
		e.preventDefault();
		//alert(e.which + ": " + String.fromCharCode(e.which));
		if(e.which == 13) {
			if($("#Productos").val()!=''){
				Agrega_Productos($("#cantidad").val(),$("#Productos").val());	
			}
		}
	});
	$(document).on("keyup","body",function(e) {
		e.preventDefault();
		//alert(e.which + ": " + String.fromCharCode(e.which));
//		alert(e.which);
		if(e.which==27){
			$("#Productos").focus();
		}
		//$('#myModal').modal('hide');
		return false;
	});
