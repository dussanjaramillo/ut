
<br>
<div id="consultapagosimp">

<CENTER>
<h1>Detalle Pagos</h1>
	 <strong>
NIT&nbsp: &nbsp<?=$empresas['CODEMPRESA']?>	 &nbsp &nbsp &nbsp RAZON SOCIAL&nbsp:  &nbsp<?=$empresas['NOMBRE_EMPRESA']?>  	<br>
 	 </strong>
</CENTER>
<table id="consultapagos" class="table table-bordered" border="1">
 <thead class="btn-success">
    <tr>
	<th>AÑO</th>
	<th>MES</th>	
								
	<?php
	switch($concepto){
							
	case 1:?>
     <th>APORTES PARAFISCALES</th>
     <th>IM APORTES PARAFISCALES</th>
		<?php
		break;
    case 2:?>
     <th>FIC</th>
     <th>IM FIC</th>
	    <?php
	    break;
		
		case 3:?>
     <th>MONETIZACIÓN</th>
     <th>IM MONETIZACIÓN</th>
         <?php
        break;

        
	default;?>
	
     <th>APORTES PARAFISCALES</th>
     <th>IM APORTES PARAFISCALES</th>
     <th>FIC</th>
     <th>IM FIC</th>
     <th>MONETIZACIÓN</th>
     <th>IM MONETIZACIÓN</th>
	<?php
	break;
							}	?>

     
   </tr>
 </thead>
 <tbody>
<?php
    
    for($x = $year['inicio']; $x <= $year['fin']; ++$x) :
	
	if($concepto==99){
	echo '<tr><td colspan="8" style="background-color: #f9f9f9;" border="1">'.$x.'</td></tr>'."\n\t\t";
	}
	else{
		echo '<tr><td colspan="4" style="background-color: #f9f9f9;" border="1">'.$x.'</td></tr>'."\n\t\t";
    
	}
	
      for($y = $mes[$x]['mesinicio']; $y <= $mes[$x]['mesfin']; ++$y) :
        echo '<tr><td border="1">&nbsp;</td><td border="1">'.Fiscalizacion::mes2($y).'</td>';
		  			if($y<10){
		$mesres='0'.$y;
			}
			else{
			$mesres=$y;	
			}
          if($concepto==99){
	
		  
		
		if(!empty($pago['1'.$x.$mesres])){
			$pago['1'.$x.$mesres]=$pago['1'.$x.$mesres]+0;
          echo '<td border="1">'.number_format($pago['1'.$x.$mesres], 0, ',', '.').'</td>';
	}
		  else 
			  {
			  echo '<td border="1">0</td>';  	
			  }
			  
		if(!empty($pagoim['1'.$x.$mesres])){
			$pagoim['1'.$x.$mesres]=$pagoim['1'.$x.$mesres]+0;
          echo '<td border="1">'.number_format($pagoim['1'.$x.$mesres], 0, ',', '.').'</td>';
	}
		  else 
			  {
			  echo '<td border="1">0</td>';  	
			  }
	if(!empty($pago['2'.$x.$mesres])){
		$pago['2'.$x.$mesres]=$pago['2'.$x.$mesres]+0;
          echo '<td border="1">'.number_format($pago['2'.$x.$mesres], 0, ',', '.').'</td>';
	}
		  else 
			  {
			  echo '<td border="1">0</td>';  	
			  }
if(!empty($pagoim['2'.$x.$mesres])){
	$pagoim['2'.$x.$mesres]=$pagoim['2'.$x.$mesres]+0;
          echo '<td border="1">'.number_format($pagoim['2'.$x.$mesres], 0, ',', '.').'</td>';
	}
		  else 
			  {
			  echo '<td border="1">0</td>';  	
			  }
			  
	if(!empty($pago['3'.$x.$mesres])){
		$pago['3'.$x.$mesres]=$pago['3'.$x.$mesres]+0;
          echo '<td border="1">'.number_format($pago['3'.$x.$mesres], 0, ',', '.').'</td>';
	}
		  else 
			  {
			  echo '<td border="1">0</td>';  	
			  }
if(!empty($pagoim['3'.$x.$mesres])){
	$pagoim['3'.$x.$mesres]=$pagoim['3'.$x.$mesres]+0;
          echo '<td border="1">'.number_format($pagoim['3'.$x.$mesres], 0, ',', '.').'</td>';
	}
		  else 
			  {
			  echo '<td border="1">0</td>';  	
			  }
		  }
	else{

		
		if(!empty($pago[$concepto.$x.$mesres])){
				$pago[$concepto.$x.$mesres]=$pago[$concepto.$x.$mesres]+0;
          echo '<td border="1">'.number_format($pago[$concepto.$x.$mesres], 0, ',', '.').'</td>';
	}
		  else 
			  {
			  echo '<td border="1">0</td>';  	
			  }
		  
          if(!empty($pagoim[$concepto.$x.$mesres])){
          	$pagoim[$concepto.$x.$mesres]=$pagoim[$concepto.$x.$mesres]+0;
          echo '<td border="1">'.number_format($pagoim[$concepto.$x.$mesres], 0, ',', '.').'</td>';
	}
		  else 
			  {
			  echo '<td border="1">0</td>';  	
			  }  
	}
              
      endfor;
	  
	  	if($concepto==99){
      echo '<tr><td colspan="2" style="background-color: #f9f9f9;" border="1">TOTAL</td>';
      
      if(!empty($pagoanio['1'.$x])){
      	$pagoanio['1'.$x]=$pagoanio['1'.$x]+0;
          echo '<td style="background-color: #f9f9f9;" border="1">'.number_format($pagoanio['1'.$x], 0, ',', '.').'</td>';
	}
		  else 
			  {
      echo '<td style="background-color: #f9f9f9;" border="1">0</td>';
			  }
			  
	 if(!empty($pagoanioim['1'.$x])){
	 	
		$pagoanioim['1'.$x]=$pagoanioim['1'.$x]+0;
          echo '<td style="background-color: #f9f9f9;" border="1">'.number_format($pagoanioim['1'.$x], 0, ',', '.').'</td>';
	}
		  else 
			  {
      echo '<td style="background-color: #f9f9f9;" border="1">0</td>';
			  }
    if(!empty($pagoanio['2'.$x])){
    	$pagoanio['2'.$x]=$pagoanio['2'.$x]+0;
          echo '<td style="background-color: #f9f9f9;" border="1">'.number_format($pagoanio['2'.$x], 0, ',', '.').'</td>';
	}
		  else 
			  {
      echo '<td style="background-color: #f9f9f9;" border="1">0</td>';
			  }

		
	 if(!empty($pagoanioim['2'.$x])){
	 	$pagoanioim['2'.$x]=$pagoanioim['2'.$x]+0;
          echo '<td style="background-color: #f9f9f9;" border="1">'.number_format($pagoanioim['2'.$x], 0, ',', '.').'</td>';
	}
		  else 
			  {
      echo '<td style="background-color: #f9f9f9;" border="1">0</td>';
			  }

	  
	  if(!empty($pagoanio['3'.$x])){
	  	$pagoanio['3'.$x]=$pagoanio['3'.$x]+0;
          echo '<td style="background-color: #f9f9f9;" border="1">'.number_format($pagoanio['3'.$x], 0, ',', '.').'</td>';
	}
		  else 
			  {
      echo '<td style="background-color: #f9f9f9;" border="1">0</td> '."\n\t\t";
			  }
			  
			 	 if(!empty($pagoanioim['3'.$x])){
			 	 	
					$pagoanioim['3'.$x]=$pagoanioim['3'.$x]+0;
          echo '<td style="background-color: #f9f9f9;" border="1">'.number_format($pagoanioim['3'.$x], 0, ',', '.').'</td>';
	}
		  else 
			  {
      echo '<td style="background-color: #f9f9f9;" border="1">0</td>';
			  }
	  
	  
			
	}
	else{
      echo '<tr><td colspan="2" style="background-color: #f9f9f9;" border="1">TOTAL</td>';
      		if(!empty($pagoanio[$concepto.$x])){
      			$pagoanio[$concepto.$x]=$pagoanio[$concepto.$x]+0;
          echo '<td style="background-color: #f9f9f9;" border="1">'.number_format($pagoanio[$concepto.$x], 0, ',', '.').'</td>';
	}
		  else 
			  {
      echo '<td style="background-color: #f9f9f9;" border="1">0</td>';
			  }
	  
	  	 if(!empty($pagoanioim[$concepto.$x])){
	  	 	$pagoanioim[$concepto.$x]=$pagoanioim[$concepto.$x]+0;
          echo '<td style="background-color: #f9f9f9;" border="1">'.number_format($pagoanioim[$concepto.$x], 0, ',', '.').'</td>'."\n\t\t";;
	}
		  else 
			  {
      echo '<td style="background-color: #f9f9f9;" border="1">0</td>'."\n\t\t";;
			  }
    
	}
			
			

      echo '</tr>'."\n\t\t";
    endfor;
    ?>
 </tbody>  
 </table>

</div>
<center>
	
		<input type='button' name='imprimir_tabla' class='btn btn-success' value='IMPRIMIR'  id='imprimir_tabla'>

	<input type='button' name='consultar_detalle_liquidacion' class='btn btn-success' value='LIQUIDACIONES - RESOLUCIONES'  id='consultar_detalle_liquidacion'>

	<input type='button' name='export' class='btn btn-success' value='EXPORTAR'  id='export'>

</center>
  
  <form id="exportar_xls" action="<?= base_url('index.php/fiscalizacion/exportarxl') ?>" method="post" target ="_blank" >

    <input type="hidden" id="datos_envio" name="datos_envio" value="">
</form> 
<script >

$('#imprimir_tabla').click(function(){
var ficha = document.getElementById('consultapagosimp');
var ventimp = window.open("");
ventimp.document.write( ficha.innerHTML );
ventimp.document.close();
ventimp.print( );
ventimp.close();


});

$('#consultar_detalle_liquidacion').click(function(){
	$(".preload, .load").show();
 $('#detalle_liquidacion').submit();


});


$('#export').click(function(){
var	concepto_id = $("#concepto_id").val();
 $("#datos_envio").val( $("<div>").append( $("#consultapagos").eq(0).clone()).html());
     $("#exportar_xls").submit();


});
        function ajaxValidationCallback(status, form, json, options) {
	
}
</script>

 
