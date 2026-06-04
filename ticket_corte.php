<?
include("inc/conectar.php");
ini_set("memory_limit","1G");
set_time_limit(1000);
$FECHA = $_GET["fecha"];
$cadena = '';
$fechas = explode("/", $FECHA);
$IDUSUARIO = $_SESSION['SISTEMA']['idusuarios'];
$USUARIO =  $_SESSION['SISTEMA']['usuario'];
$result = $consulta->query("SELECT SUM(importe),  fecha FROM ventas WHERE fecha BETWEEN '".$fechas[0]." 00:00:00' AND '".$fechas[1]." 23:59:00' AND tipo IS NULL");
foreach ($result as $row);

$result = $consulta->query("SELECT SUM(importe), fecha FROM movimientoscaja WHERE tipo='Membresia' AND fecha BETWEEN '" .$fechas[0]. " 00:00:00' AND '" .$fechas[1] . " 23:00:00'");
foreach ($result as $row_mem);
$result = $consulta->query("SELECT SUM(importe), fecha FROM movimientoscaja WHERE fecha BETWEEN '".$fechas[0]." 00:00:00' AND '".$fechas[1]." 23:59:00' AND (tipo LIKE 'Ingreso') GROUP BY tipo");
foreach ($result as $row_ingreso);
$result = $consulta->query("SELECT SUM(importe), fecha FROM movimientoscaja WHERE fecha BETWEEN '".$fechas[0]." 00:00:00' AND '".$fechas[1]." 23:59:00' AND (tipo LIKE 'Retiro') GROUP BY tipo");
foreach ($result as $row_retiro);
$ancho=260;
$pago="C";
$altu=100;
$REIMPRESO = '';
$piezas = 0;
?>

<title>Ticket Corte</title>
<meta charset="UTF-8">
<link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
	<link rel="icon" href="img/favicon.ico" type="image/x-icon">

<div id="resultados_ticket" style="position:absolute;top:0px;left:0px;margin:0px;padding:0px;width:<?=$ancho?>px; height:<?=(60)+230+$altu?>px;border:0px solid; font:Arial, Helvetica, sans-serif; font-family:Arial, Helvetica, sans-serif;">
	<div style="position:RELATIVE;top:0px;left:0px;width:<?=$ancho?>px;text-align:center; font-size:12px;">
    	<img src="img/logo.jpg" height="60" />
     </div>
	<div style="position:RELATIVE;left:0px;width:<?=$ancho?>px;text-align:center; font-size:14px;">
    	<label><b>Vital Gimnasio <br>Santa Rita #353 Arandas, Jal.</b></label><br />
    	<label>CORTE DE CAJA</label><br />
	</div>
	<div style="position:RELATIVE;left:5px;font-size:12px;width:<?=$ancho?>px;text-align:left;">
		<?
		echo "<center>Fecha Impresion: ".date("d-m-Y H:i:s")."</center>";
		echo "<center>Fecha Inicio: ".substr($FECHA,8,2)."/".substr($FECHA,5,2)."/".substr($FECHA,0,4)."</center>";
		echo "<center>Fecha Fin: ".substr($FECHA,19,2)."/".substr($FECHA,16,2)."/".substr($FECHA,11,4)."</center>";
		?>
        <center>Usuario: <?=$USUARIO?></center>

	</div>
	<?
	$total_corte = $row[0];
	$total_corte += $row_mem[0]; 	
	$total_corte += $row_ingreso[0]; 	
	$total_corte -= $row_retiro[0]; 
	$total_corte -= $row_devolucion[0]; 
	?>
	<div style="position:RELATIVE;left:0px;width:<?=$ancho?>px;text-align:left;">
		<div style="height:16px;font-size:14px;">
			<div style="position:relative; width:<?=$ancho+1?>px; height:30px;">
            	<table width="100%" style="font-size:14px;" border="0" cellpadding="0" cellspacing="0">
                	<tr style=" color:#000; font-weight:bold;;">
                    	<td width="150">Ventas:</td>
                    	<td align="right">$ <?=number_format($row[0],2)?></td>
                    </tr>
                	<tr style=" color:#000; font-weight:bold;">
                    	<td width="100">Membresias:</td>
                    	<td align="right">$ <?=number_format($row_mem[0],2)?></td>
                    </tr> 
                	<tr style=" color:#000; font-weight:bold;">
                    	<td width="150">Ingresos Efectivo:</td>
                    	<td align="right">$ <?=number_format($row_ingreso[0],2)?></td>
                    </tr>
                	<tr style=" color:#000; font-weight:bold;">
                    	<td width="150">Retiro de Efectivo:</td>
                    	<td align="right">$ <?=number_format($row_retiro[0],2)?></td>
                    </tr>
                    
                    	<td colspan="2"><hr></td>
                    </tr>
                	<tr style=" color:#000; font-weight:bold;">
                    	<td width="150">Total Corte:</td>
                    	<td align="right">$ <?=number_format($total_corte,2)?></td>
                    </tr>
                </table>
			</div>
		</div>

</div>
</HTML>

<script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
<script>
$(document).ready(function(e) {
  cerrar() ;
  function cerrar() {
	  window.print();
	   setTimeout(window.close,3000); }
});
</script>
<?
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////																			////////////////////////
/////////////////				CONVIERTE NUMEROS A LETRAS									////////////////////////
/////////////////																			////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function num2letras($num, $fem = false, $dec = true) {
   $matuni[2]  = "dos";
   $matuni[3]  = "tres";
   $matuni[4]  = "cuatro";
   $matuni[5]  = "cinco";
   $matuni[6]  = "seis";
   $matuni[7]  = "siete";
   $matuni[8]  = "ocho";
   $matuni[9]  = "nueve";
   $matuni[10] = "diez";
   $matuni[11] = "once";
   $matuni[12] = "doce";
   $matuni[13] = "trece";
   $matuni[14] = "catorce";
   $matuni[15] = "quince";
   $matuni[16] = "dieciseis";
   $matuni[17] = "diecisiete";
   $matuni[18] = "dieciocho";
   $matuni[19] = "diecinueve";
   $matuni[20] = "veinte";
   $matunisub[2] = "dos";
   $matunisub[3] = "tres";
   $matunisub[4] = "cuatro";
   $matunisub[5] = "quin";
   $matunisub[6] = "seis";
   $matunisub[7] = "sete";
   $matunisub[8] = "ocho";
   $matunisub[9] = "nove";

   $matdec[2] = "veint";
   $matdec[3] = "treinta";
   $matdec[4] = "cuarenta";
   $matdec[5] = "cincuenta";
   $matdec[6] = "sesenta";
   $matdec[7] = "setenta";
   $matdec[8] = "ochenta";
   $matdec[9] = "noventa";
   $matsub[3]  = 'mill';
   $matsub[5]  = 'bill';
   $matsub[7]  = 'mill';
   $matsub[9]  = 'trill';
   $matsub[11] = 'mill';
   $matsub[13] = 'bill';
   $matsub[15] = 'mill';
   $matmil[4]  = 'millones';
   $matmil[6]  = 'billones';
   $matmil[7]  = 'de billones';
   $matmil[8]  = 'millones de billones';
   $matmil[10] = 'trillones';
   $matmil[11] = 'de trillones';
   $matmil[12] = 'millones de trillones';
   $matmil[13] = 'de trillones';
   $matmil[14] = 'billones de trillones';
   $matmil[15] = 'de billones de trillones';
   $matmil[16] = 'millones de billones de trillones';

   //Zi hack
   $float=explode('.',$num);
   $num=$float[0];

   $num = trim((string)@$num);
   if ($num[0] == '-') {
      $neg = 'menos ';
      $num = substr($num, 1);
   }else
      $neg = '';
   while ($num[0] == '0') $num = substr($num, 1);
   if ($num[0] < '1' or $num[0] > 9) $num = '0' . $num;
   $zeros = true;
   $punt = false;
   $ent = '';
   $fra = '';
   for ($c = 0; $c < strlen($num); $c++) {
      $n = $num[$c];
      if (! (strpos(".,'''", $n) === false)) {
         if ($punt) break;
         else{
            $punt = true;
            continue;
         }

      }elseif (! (strpos('0123456789', $n) === false)) {
         if ($punt) {
            if ($n != '0') $zeros = false;
            $fra .= $n;
         }else

            $ent .= $n;
      }else

         break;

   }
   $ent = '     ' . $ent;
   if ($dec and $fra and ! $zeros) {
      $fin = ' coma';
      for ($n = 0; $n < strlen($fra); $n++) {
         if (($s = $fra[$n]) == '0')
            $fin .= ' cero';
         elseif ($s == '1')
            $fin .= $fem ? ' una' : ' un';
         else
            $fin .= ' ' . $matuni[$s];
      }
   }else
      $fin = '';
   if ((int)$ent === 0) return 'Cero ' . $fin;
   $tex = '';
   $sub = 0;
   $mils = 0;
   $neutro = false;
   while ( ($num = substr($ent, -3)) != '   ') {
      $ent = substr($ent, 0, -3);
      if (++$sub < 3 and $fem) {
         $matuni[1] = 'una';
         $subcent = 'as';
      }else{
         $matuni[1] = $neutro ? 'un' : 'uno';
         $subcent = 'os';
      }
      $t = '';
      $n2 = substr($num, 1);
      if ($n2 == '00') {
      }elseif ($n2 < 21)
         $t = ' ' . $matuni[(int)$n2];
      elseif ($n2 < 30) {
         $n3 = $num[2];
         if ($n3 != 0) $t = 'i' . $matuni[$n3];
         $n2 = $num[1];
         $t = ' ' . $matdec[$n2] . $t;
      }else{
         $n3 = $num[2];
         if ($n3 != 0) $t = ' y ' . $matuni[$n3];
         $n2 = $num[1];
         $t = ' ' . $matdec[$n2] . $t;
      }
      $n = $num[0];
      if ($n == 1) {
         $t = ' ciento' . $t;
      }elseif ($n == 5){
         $t = ' ' . $matunisub[$n] . 'ient' . $subcent . $t;
      }elseif ($n != 0){
         $t = ' ' . $matunisub[$n] . 'cient' . $subcent . $t;
      }
      if ($sub == 1) {
      }elseif (! isset($matsub[$sub])) {
         if ($num == 1) {
            $t = ' mil';
         }elseif ($num > 1){
            $t .= ' mil';
         }
      }elseif ($num == 1) {
         $t .= ' ' . $matsub[$sub] . '?n';
      }elseif ($num > 1){
         $t .= ' ' . $matsub[$sub] . 'ones';
      }
      if ($num == '000') $mils ++;
      elseif ($mils != 0) {
         if (isset($matmil[$sub])) $t .= ' ' . $matmil[$sub];
         $mils = 0;
      }
      $neutro = true;
      $tex = $t . $tex;
   }
   $tex = $neg . substr($tex, 1) . $fin;
   //Zi hack --> return ucfirst($tex);
   $end_num=ucfirst($tex).' pesos '.substr($float[1],0,2).'/100 M.N.';
   return $end_num;
}
?>
