<?
include("inc/conectar.php");
ini_set("memory_limit","1G");
set_time_limit(1000);
$IDVENTAS = $_GET["identradas"];
if($_GET["identradas"]=='')$IDVENTAS = 1;
$result = $consulta->query("SELECT *, cxc.importe AS ABONADO, cxc.tarjeta AS ABONADOT, cxc.observaciones AS OBSERV FROM cxc WHERE idcxc=".$_GET["identradas"]." ");
foreach ($result as $row);
$result = $consulta->query("SELECT * FROM ventas WHERE idventas=".$row["idventas"]." ");
foreach ($result as $ventas);
$REIMPRESO = '';
if($row['impreso']!=''){
	$REIMPRESO = "**********TICKET REIMPRESO**********";
}else{
	$result = $consulta->query("UPDATE cxc SET impreso='".date("Y-m-d H:i:s")."' WHERE idcxc=".$_GET["identradas"]." ");
	foreach ($result as $consul);
}

$ancho=260;
$altu=100;
?>
<style>
@media print {
.impre {display:none}
}
</style>
<link rel="icon" type="image/png" href="Graficos/favicon.ico" />
<title>Ticket Pago</title>
<div id="resultados_ticket" style="position:absolute;top:0px;left:0px;margin:0px;padding:0px;width:<?=$ancho?>px; height:<?=($con*60)+230+$altu?>px;border:0px solid; font:Arial, Helvetica, sans-serif; font-family:Arial, Helvetica, sans-serif;">
	<div style="position:absolute;top:0px;left:0px;width:<?=$ancho?>px;text-align:center; font-size:12px;">
    	<img src="img/logo.png" height="80" />
    </div>
	<div style="position:absolute;top:80px;left:0px;width:<?=$ancho?>px;text-align:center; font-size:14px;">
    	<label><b>PAGO DE NOTAS</b></label><br />
    	<LABEL><b>FOLIO PAGO: <?=str_pad($row["idcxc"], 6, "0", STR_PAD_LEFT); ?></b></LABEL>
	</div>
	<div style="position:absolute;top:115px;left:5px;font-size:12px;width:<?=$ancho?>px;text-align:left;">
		<?
		$fecha=explode(" ",$row["fecha"]);
		$ano=explode("-",$fecha[0]);
		$mes=$ano[1];
		$dia=$ano[2];
		$cliente=$row["cliente"];
		echo "<center>Fecha: ".$dia."/".$mes."/".$ano[0]." ".$fecha[1]."</center>";
		echo "<center>".substr($row['clientes'],0,37)."</center>";
		echo "<center><b>".$REIMPRESO."</b></center>";
		?>
	</div>
	<div style="position:absolute;top:160px;left:0px;width:<?=$ancho?>px;text-align:left;">
		<div style="height:16px;font-size:12px;">
			<div style="position:relative; width:<?=$ancho+1?>px; height:30px;">
            	<table width="100%" style="font-size:14px;" border="0" cellpadding="0" cellspacing="0">
                	<tr style=" color:#000; font-weight:bold;;">
                    	<td>Efectivo</td>
                    	<td>Tarjeta</td>
                    	<td align="center">&nbsp;Abono</td>
                    </tr>
                	<tr style=" color:#000; font-weight:bold;">
                    	<td>$<?=number_format($row['ABONADO'],2)?></td>
                    	<td>$<?=number_format($row['ABONADOT'],2)?></td>
                    	<td align="right">$<?=number_format($row['ABONADO']+$row['ABONADOT'],2)?></td>
                    </tr>
                	<tr style=" color:#000; font-weight:bold;;">
                    	<td>Importe</td>
                    	<td>Abono</td>
                    	<td align="center">&nbsp;Saldo</td>
                    </tr>
                	<tr style=" color:#000; font-weight:bold;">
                    	<td>$<?=number_format($ventas['importe'],2)?></td>
                    	<td>$<?=number_format($ventas['abonos'],2)?></td>
                    	<td align="right">$<?=number_format($ventas['importe']-$ventas['abonos'],2)?></td>
                    </tr>
                	<tr style=" color:#000; font-weight:bold;;">
                    	<td colspan="3">Observaciones</td>
                    </tr>
                	<tr style=" color:#000; font-weight:bold;">
                    	<td colspan="3"><?=$row['OBSERV']?></td>
                    </tr>
                </table>
                <hr />
            	<table width="100%" style="font-size:14px;" border="0" cellpadding="0" cellspacing="0">
                	<tr style=" color:#000; font-weight:bold;;">
                    	<td>Total Abonos</td>
                    	<td align="center">&nbsp;Saldo Total</td>
                    </tr>
                	<tr style=" color:#000; font-weight:bold;">
                    	<td>$<?=number_format($ventas['abonos'],2)?></td>
                    	<td align="right">$<?=number_format($ventas['importe']-$ventas['abonos'],2)?></td>
                    </tr>
                </table>
                 <hr />
			</div>
		</div>
		
	</div>
</div>
</HTML>

<script type="text/javascript" src="../js/jquery-3.2.1.min.js"></script>
<script>
$(document).ready(function(e) {
	
 cerrar() ;
  function cerrar() {
	  window.print();
	   setTimeout(window.close,3000); }
});
</script>
<?
