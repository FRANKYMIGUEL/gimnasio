 
$(document).ready(function(){
    
    $('#qrcodeholder').qrcode({
        
        text    : $("#codigo").val(), // URL a enlazar
        render  : "table", // Elemento en donde se mostrará el código QR. También puedes usar la opción 'table' 
        background : "#ffffff", // Color de fondo
        foreground : "#000000", // Color de los fragmentos del código QR 
        width : 250, // Ancho
        height: 250 // Alto 
    });
    alert("ss"+$("#codigo").val());
});