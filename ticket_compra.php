<?php
include("inc/conectar.php");
ini_set("memory_limit","1G");
set_time_limit(1000);
$IDVENTAS = $_GET["idcompras"];
if($_GET["idcompras"]=='')$IDVENTAS = 1;
$result = $consulta->query("SELECT * FROM compras WHERE idcompras=".$_GET["idcompras"]."");
foreach ($result as $row);
$result2 = $consulta->query("SELECT * FROM proveedores WHERE idproveedores=".$row['idproveedores']);
foreach ($result2 as $clientes);

$con=($con[0]=="")?0:$con[0];

?>
<link rel="icon" type="image/png" href="Graficos/favicon.ico" />
<title>Ticket</title>
<div id="resultados_ticket" style="position:absolute;top:0px;left:0px;margin:0px;padding:0px;width:<?=$ancho?>px; height:<?=($con*60)+230+$altu?>px;border:0px solid; font:Arial, Helvetica, sans-serif; font-family:Arial, Helvetica, sans-serif;">
	<div style="position:RELATIVE;top:0px;left:0px;width:<?=$ancho?>px;text-align:center; font-size:12px;">
    	<img src="img/logo.png" height="80" />
    </div>
	<div style="position:RELATIVE;left:0px;width:<?=$ancho?>px;text-align:center; font-size:14px;">
      <label>Av. Del herradero #1189 <br>Col. La herradura, Arandas, Jal. </label><br />
      <label>3481328125</label><br />
    	<label><b>Compra No. : <?=str_pad($row["folio"], 6, "0", STR_PAD_LEFT); ?></b></LABEL>
	</div>
	<div style="position:RELATIVE;left:5px;font-size:12px;width:<?=$ancho?>px;text-align:left;">
		<?php
		$fecha=explode(" ",$row["fecha"]);
		$ano=explode("-",$fecha[0]);
		$mes=$ano[1];
		$dia=$ano[2];
		$cliente=$row["cliente"];
		echo "<center>Fecha: ".$dia."/".$mes."/".$ano[0]." ".$fecha[1]."</center>";
		echo "<center>Proveedor: ".$row['clie'].' '.substr($row['clientes'],0,37)."</center>";
		?>
	</div>
	<div style="position:RELATIVE;left:0px;width:<?=$ancho?>px;text-align:left;">
		<div style="height:16px;font-size:12px;">
			<div style="position:relative; width:<?=$ancho+1?>px; height:30px;">
            	<table width="100%" style="font-size:12px;" border="0" cellpadding="0" cellspacing="0">
                	<tr style=" color:#000; font-weight:bold;;">
                    	<td width="100">Cantidad</td>
                    	<td>Descripcion</td>
                    	<td align="center">&nbsp;Precio</td>
                    	<td width="100" align="right">Importe</td>
                    </tr>
                </table>
			</div>
		</div>
		<div style="text-align:center;"><hr></div>
        <table width="100%" style="font-size:12px;" border="0" cellpadding="0" cellspacing="0">
            <?
			$resulta = $consulta->query("SELECT * FROM detallecompras WHERE idcompras=".$IDVENTAS);
			foreach ($resulta as $reg){
				$Impo=($reg["cantidad"]*($reg["precio"]));
				$precio = $reg["precio"];
				$total+=$Impo;
					$CARTON = $reg['cantidad'];
					$cartones += $CARTON;
				?>
                    <tr>
                    	<td align="center" colspan="5"><?=$reg["articulo"]." ".strtoupper(utf8_decode($reg["productos"]))?></td>
                    </tr>
                    <tr>
                    	<td align="center" colspan="5"><?=strtoupper($reg["observaciones"])?></td>
                    </tr>
                    <tr>
                      <td width="40" align="center"><?=$reg["cantidad"]?></td>
                   	  <td align="center"><b>$<?=number_format($precio,2)?></b></td>
                   	  <td align="right"><b><?="$".number_format($Impo, 2,'.',',')?></b></td>
                    </tr>
			<?
			}
			?>
         </table>
            <?
			$SUBTOTAL=$total;
			$SUBTOTALD=$SUBTOTAL;
			$TOTAL=$total;
			if($row["descuento"]>0){
				echo '<div style="text-align:left; width:100%; font-size:16px; text-align:right;">Descuento %  '.number_format($row["descuento"],2,".",",").'</span></div>';
			}
			if($row["efectivo"]>0 and ($row["tipo"]=="credito" or $row["tipo"]=="apartado")){
				echo '<div style="text-align:left; width:100%; font-size:16px; text-align:right;">Abono en Efectivo $ '.number_format($row["efectivo"],2,".",",").'</span></div>';
			}elseif($row["efectivo"]>0 and $row["tipo"]=="efectivo"){
				echo '<div style="text-align:left; width:100%; font-size:16px; text-align:right;">Pago en Efectivo $ '.number_format($row["efectivo"],2,".",",").'</span></div>';
			}
			if($row["tarjeta"]>0){
				echo '<div style="text-align:left; width:100%; font-size:14px; text-align:right;">Pago con Tarjeta $&nbsp; '.number_format($row["tarjeta"]-$row["comision_tarjeta"],2,".",",").'</span></div>';
				echo '<div style="text-align:left; width:100%; font-size:14px; text-align:right;">Comision Tarjeta $&nbsp; '.number_format($row["comision_tarjeta"],2,".",",").'</span></div>';
				echo '<div style="text-align:left; width:100%; font-size:14px; text-align:right;">Total Tarjeta $&nbsp; '.number_format($row["tarjeta"],2,".",",").'</span></div>';
			}
			
			?>
         
         <?
         if($row["tipo"]=="apartado" or $row["tipo"]=="credito"){
			 echo '<div style="text-align:left; width:100%; font-size:16px; text-align:right;"><b>Total &nbsp;&nbsp;&nbsp;<span style="float:right;"> $&nbsp;'.number_format($row["importe"],2,'.',',').'</span></b></div>';
				echo '<div style="text-align:left; width:100%; font-size:16px; text-align:right;">Resta $'.number_format($row["importe"]-$row["efectivo"]-($row["tarjeta"]-$row["comision_tarjeta"]),2,".",",").'</span></div>';
			}
			if($row["tipo"]=="contado"){
				echo '<div style="text-align:left; width:100%; font-size:16px; text-align:right;"><b>Total Venta &nbsp;&nbsp;&nbsp;<span style="float:right;"> $&nbsp;'.number_format($row["importe"],2,'.',',').'</span></b></div>';
			?>
			<div style="text-align:left; font-size:12px;">*<?=num2letras($TOTAL)?>*</div>
            <?php
			}
			?>
			<div style="text-align:left;font-size:12px; ">&nbsp;Capturado por: <?=$row["usuarios"]?></div>
            <label style="text-align:left;font-size:12px; ">
         <center><strong></strong></center>
      </LABEL>
      <div >
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
<?php
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
