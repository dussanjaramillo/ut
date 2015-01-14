
<br>
<table id="consultaliq">
 <thead>
    <tr>
	<th>PERIODO INICIAL</th>
	<th>PERIODO FINAL</th>	
     <th>CONCEPTO</th>
     <th>TIPO</th>
     <th>NUMERO</th>
     <th>FECHA EJECUTORIA</th>
     <th>ESTADO</th>
     <th>VALOR INICIAL</th>
     <th>VALOR PAGADO (ABONO)</th>     
     <th>SALDO</th>

   </tr>
 </thead>
 <tbody>
<?php
if($tipo==0)
{
foreach ($datosliq->result_array as $data) {?> 
	<tr>
<?php 
$total=0;
?>

<td><?=$data['PERIODO_INI'] ?></td>
<td><?=$data['PERIODO_FIN']  ?></td>	
<td><?=$data['NOMBRE_CONCEPTO']  ?></td>
	<?php if(!empty($data['FECHA_RESOLUCION']))
	{
	?> 
<td>Resolución</td>	
<td><?=$data['NUMERORES']  ?></td>	
<td><?=$data['FECHA_EJECUTORIA']  ?></td>	
<td><?=$data['TIPOGESTION']  ?></td>
<?php  echo '<td>'.number_format($data['VALOR_TOTAL'], 0, ',', '.').'</td>';?>	
	<?php
	$total=$data['VALOR_TOTAL'] ;
	}else{
	?> 
<td>Liquidación</td>
<td><?=$data['NUMEROLIQ']  ?></td>
<td><?=$data['FECHA_EJECUTORIA']  ?></td>		
<td>Liquidación Generada</td>
<?php  echo '<td>'.number_format($data['TOTAL_LIQUIDADO'], 0, ',', '.').'</td>';?>
	<?php
		$total=$data['TOTAL_LIQUIDADO'] ;
	}
	?> 	
<?php     
$pagado=$total-$data['SALDO_DEUDA'];
?>
<?php  echo '<td>'.number_format($pagado, 0, ',', '.').'</td>';?>	

<?php  echo '<td>'.number_format($data['SALDO_DEUDA'], 0, ',', '.').'</td>';?>	

		

</tr>
		<?php
}
}
elseif($tipo==1)
{
	foreach ($datosres->result_array as $datares) {?> 
	<tr>
<?php 
$total=0;
?>

<td><?=$datares['PERIODO_INI'] ?></td>
<td><?=$datares['PERIODO_FIN']  ?></td>	
<td><?=$datares['NOMBRE_CONCEPTO']  ?></td>
	<?php if(!empty($datares['FECHA_RESOLUCION']))
	{
	?> 
<td>Resolución</td>	
<td><?=$datares['NUMERORES']  ?></td>	
<td><?=$datares['FECHA_EJECUTORIA']  ?></td>
<td><?=$datares['TIPOGESTION']  ?></td>
<?php  echo '<td>'.number_format($datares['VALOR_TOTAL'], 0, ',', '.').'</td>';?>	

	<?php
	$total=$datares['VALOR_TOTAL'] ;
	}else{
	?> 
<td>Liquidación</td>
<td><?=$datares['NUMEROLIQ']  ?></td>	
<td><?=$datares['FECHA_EJECUTORIA']  ?></td>		
<td>Liquidación Generada</td>
<?php  echo '<td>'.number_format($datares['TOTAL_LIQUIDADO'], 0, ',', '.').'</td>';?>
	<?php
	$total=$datares['TOTAL_LIQUIDADO'] ;
	}
	?> 	

<?php     
$pagado=$total-$datares['SALDO_DEUDA'];
?>
<?php  echo '<td>'.number_format($pagado, 0, ',', '.').'</td>';?>		

<?php  echo '<td>'.number_format($datares['SALDO_DEUDA'], 0, ',', '.').'</td>';?>	
	

</tr>
		<?php
}
	
	
}
else{
	foreach ($datosliq->result_array as $data) {?> 
	<tr>
<?php 
$total=0;
?>

<td><?=$data['PERIODO_INI'] ?></td>
<td><?=$data['PERIODO_FIN']  ?></td>	
<td><?=$data['NOMBRE_CONCEPTO']  ?></td>
	<?php if(!empty($data['FECHA_RESOLUCION']))
	{
	?> 
<td>Resolución</td>	
<td><?=$data['NUMERORES']  ?></td>	
<td><?=$data['FECHA_EJECUTORIA']  ?></td>	
<td><?=$data['TIPOGESTION']  ?></td>
<?php  echo '<td>'.number_format($data['VALOR_TOTAL'], 0, ',', '.').'</td>';?>	
	<?php
	$total=$data['VALOR_TOTAL'] ;
	}else{
	?> 
<td>Liquidación</td>
<td><?=$data['NUMEROLIQ']  ?></td>
<td><?=$data['FECHA_EJECUTORIA']  ?></td>		
<td>Liquidación Generada</td>
<?php  echo '<td>'.number_format($data['TOTAL_LIQUIDADO'], 0, ',', '.').'</td>';?>
	<?php
		$total=$data['TOTAL_LIQUIDADO'] ;
	}
	?> 	
<?php     
$pagado=$total-$data['SALDO_DEUDA'];
?>
<?php  echo '<td>'.number_format($pagado, 0, ',', '.').'</td>';?>	

<?php  echo '<td>'.number_format($data['SALDO_DEUDA'], 0, ',', '.').'</td>';?>	

		

</tr>
		<?php
}
	foreach ($datosres->result_array as $datares) {?> 
	<tr>
<?php 
$total=0;
?>

<td><?=$datares['PERIODO_INI'] ?></td>
<td><?=$datares['PERIODO_FIN']  ?></td>	
<td><?=$datares['NOMBRE_CONCEPTO']  ?></td>
	<?php if(!empty($datares['FECHA_RESOLUCION']))
	{
	?> 
<td>Resolución</td>	
<td><?=$datares['NUMERORES']  ?></td>	
<td><?=$datares['FECHA_EJECUTORIA']  ?></td>
<td><?=$datares['TIPOGESTION']  ?></td>
<?php  echo '<td>'.number_format($datares['VALOR_TOTAL'], 0, ',', '.').'</td>';?>	

	<?php
	$total=$datares['VALOR_TOTAL'] ;
	}else{
	?> 
<td>Liquidación</td>
<td><?=$datares['NUMEROLIQ']  ?></td>	
<td><?=$datares['FECHA_EJECUTORIA']  ?></td>		
<td>Liquidación Generada</td>
<?php  echo '<td>'.number_format($datares['TOTAL_LIQUIDADO'], 0, ',', '.').'</td>';?>
	<?php
	$total=$datares['TOTAL_LIQUIDADO'] ;
	}
	?> 	

<?php     
$pagado=$total-$datares['SALDO_DEUDA'];
?>
<?php  echo '<td>'.number_format($pagado, 0, ',', '.').'</td>';?>		

<?php  echo '<td>'.number_format($datares['SALDO_DEUDA'], 0, ',', '.').'</td>';?>	
	

</tr>
		<?php
}
	
	}
?> 
 </tbody>  
 </table>

  

<script >


 $('#consultaliq').dataTable({
        "bJQueryUI": true,
        "sPaginationType": "full_numbers",
        "aaSorting": [[ 0, "desc" ]],

          	//"bFilter": false,
        	"aoColumns": [ 
					  { "sClass": "center" },
                      { "sClass": "center" }, 
                      { "sClass": "center" }, 
                      { "sClass": "center" },      
                      { "sClass": "center" }, 
                      { "sClass": "center" },
                      { "sClass": "center" }, 
                      { "sClass": "center" }, 
                      { "sClass": "center" }, 
              		{ "sClass": "center" },
                      ]

        
    });
        function ajaxValidationCallback(status, form, json, options) {
	
}
</script>

 
