$(document).ready(function() {			   
	$('#tablaClientes').DataTable( {	
		"bDeferRender": true,			
		"sPaginationType": "full_numbers",
		"ajax": {
			"url": "php/buscarClientes.php",
        	"type": "POST"
		},					
		"columns": [
			{ "data": "numero" },
			{ "data": "nombre" },
			{ "data": "direccion" },
			{ "data": "ciudad" },
			{ "data": "colonia" },
			{ "data": "cp"},
			{ "data": "telefono" },
			{ "data": "rfc" },
			{ "data": "limite" },
			{ "data": "tiempo" },
			{ "data": "observaciones" },
			{ "data": "fecha_ingreso" },
			{ "data": "fecha_cumple"},
			{ "data": "lista" },
			{ "data": "acciones"}
			],
		"oLanguage": {
            "sProcessing":     "Procesando...",
		    "sLengthMenu": 'Mostrar <select>'+
		        '<option value="10">10</option>'+
		        '<option value="20">20</option>'+
		        '<option value="30">30</option>'+
		        '<option value="40">40</option>'+
		        '<option value="50">50</option>'+
		        '<option value="-1">Todos</option>'+
		        '</select> registros',    
		    "sZeroRecords":    "No se encontraron resultados",
		    "sEmptyTable":     "Ningún dato disponible en esta tabla",
		    "sInfo":           "Mostrando del (_START_ al _END_) de un total de _TOTAL_ registros",
		    "sInfoEmpty":      "Mostrando del 0 al 0 de un total de 0 registros",
		    "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
		    "sInfoPostFix":    "",
		    "sSearch":         "Filtrar:",
		    "sUrl":            "",
		    "sInfoThousands":  ",",
		    "sLoadingRecords": "Por favor espere - cargando...",
		    "oPaginate": {
		        "sFirst":    "Primero",
		        "sLast":     "Último",
		        "sNext":     "Siguiente",
		        "sPrevious": "Anterior"
		    },
		    "oAria": {
		        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
		        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
		    }
        }
	});
});