
<h1>Acta de Entrega a Via Administrativa</h1>

<CENTER>

<table>
<tr><td>&nbsp;</td>	</tr>
          <tr>
    <td><b>NIT:</td>
    <td><?=$empresa['CODEMPRESA']?></td>
    <td>&nbsp;</td>	
    <td><b>RAZÓN SOCIAL:</td>
    <td><?=$empresa['NOMBRE_EMPRESA']?></td>
    <td>&nbsp;</td>
  </tr> 
  <tr><td>&nbsp;</td>	</tr> 
     <tr>
    <td><b>TELÉFONO FIJO:</td>
    <td><?=$empresa['TELEFONO_FIJO']?></td>
    <td>&nbsp;</td>	
    <td><b>REPRESENTANTE LEGAL:&nbsp;&nbsp;</td>
    <td><?=$empresa['REPRESENTANTE_LEGAL']?></td>
    <td>&nbsp;</td>
  </tr> 
   <tr><td>&nbsp;</td>	</tr> 
  <tr>
    <td><b>TELÉFONO CELULAR:</td>
    <td><?=$empresa['TELEFONO_CELULAR']?></td>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td><b>DIRECCIÓN:</td>
    <td><?=$empresa['DIRECCION']?></td>
    <td>&nbsp;</td>
  </tr> 
  <tr><td>&nbsp;</td>	</tr> 
  
    <tr>
    <td><b>E-MAIL:</td>
    <td><?=$empresa['CORREOELECTRONICO']?></td>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td><b>ACTIVIDAD ECONÓMICA:</td>
    <td><?=$empresa['ACTIVIDADECONOMICA']?></td>
    <td>&nbsp;</td>
  </tr> 
  <tr><td>&nbsp;</td>	</tr> 
  
    <tr>
    <td><b>NÚMERO EMPLEADOS:</td>
    <td><?=$empresa['NUM_EMPLEADOS']?></td>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td></td>
    <td></td>
    <td>&nbsp;</td>
  </tr> 
  <tr><td>&nbsp;</td>	</tr> 
      
</table>	 	

</CENTER>
<p></p><p></p><p></p>


 <div>
 	
<input type="hidden" id="cod_fisc" name="cod_fisc" value=<?=$cod_fisc?>> 	
<input type="hidden" id="nit" name="nit" value=<?=$nit?>> 

<?php
$documentos[1]= "Certificado de Representacion Legal";
$documentos[2]= "Certificado de Proponentes";
$documentos[3]= "Fotocopia del RUT";
$documentos[4]= "Balances Detallados";
$documentos[5]= "Fotocopias Declaraciones de Renta";
$documentos[6]= "Certificación Firmada por representante legal del monto de vacaciones pagadas";
$documentos[7]= "Copia tarjeta profesional revisor fiscal";
$documentos[8]= "Documentos anexos al proceso";
?>


 	 
<center> 	 
 	<table border="1">
  <tr>
    <th colspan="2" scope="row"><p>&nbsp;Acta de Entrega Via Administrativa</p>
      <p align="left">&nbsp;No de expediente: <output name="exp" id="exp" size="15" value="<?=$cod_fisc?>" type="text" style="text-align:right;"><?=$cod_fisc?></p>
    <div id="alerta1"></div>	
    <p align="left">&nbsp;Lista de Chequeo documentos Expediente</p></th>
  </tr>
  <tr>
    <th scope="row"><p align="left">
     &nbsp;<input type="checkbox" id="1" name="1" value="<?=$documentos[1]?>"/>

      <?php echo $documentos[1]?>   </p>
    <p align="left">
      &nbsp;<input type="checkbox" id="2" checked="checked" value="<?=$documentos[2]?>"/>
<?php echo $documentos[2]  ?> </p>
    <p align="left">
      &nbsp;<input type="checkbox" id="3" checked="checked" value="<?=$documentos[3]?>"/> 
      <?php echo $documentos[3]  ?></p>
    <p align="left">
      &nbsp;<input type="checkbox" id="4" checked="checked" value="<?=$documentos[4]?>"/> 
      <?php echo $documentos[4]  ?></p>
    <p align="left">
      &nbsp;<input type="checkbox" id="5" checked="checked" value="<?=$documentos[5]?>"/> 
      <?php echo $documentos[5]  ?>
</p></th>
    <th width="381" scope="row"><div align="left">
      <p>
        &nbsp;<input type="checkbox" id="6" checked="checked" value="<?=$documentos[6]?>"/>
        <?php echo $documentos[6]  ?></p>
      <p>
        &nbsp;<input type="checkbox" id="7" checked="checked" value="<?=$documentos[7]?>"/>
<?php echo $documentos[7]  ?></p>
      <p>
        &nbsp;<input type="checkbox" id="8" checked="checked" value="<?=$documentos[8]?>"/>
        <?php echo $documentos[8]  ?></p>
      
      <p>No de folios <input name="folio" id="folio" size="5" value="" type="text" style="text-align:right;" onkeypress ="escape(this);" onblur = "escape(this);" onkeyup = "escape(this);"></p>
      <div id="alerta2"></div>	
      <p>&nbsp;</p>
    </div></th>
  </tr>
</table>
</center>

 	 </div>
 	 <br>
<center>	 
 <input type='button' name='enviar' class='btn btn-success' value='Crear Acta'  id='enviar' >
 </center>
 
  <form id="retorno_fisc" action="<?= base_url('index.php/fiscalizacion/datatable') ?>" method="post" >
    <input type="hidden" id="cod_asignacion_fisc" name="cod_asignacion_fisc" value=<?=$cod_asignacion_fisc?>> 
    <input type="hidden" id="cod_nit" name="cod_nit" value=<?=$nit?>>
</form> 
 
 
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />

 
 <div id='generar_acta'></div>
<script >
function escape(c){
  	 	    if( !(/[/\*\,\.\;\:\{\}\[\]\^\`\¨\´\+\~\°\|\¬\!\¡\'\?\¿\=\)\(\&\%\$\#\"\<\>\Ñ\ñ\+]/.test(c.value)) )
		{
			$("#alerta").hide();
		}
  			else{
  	$("#alerta").show();
       	$('#alerta').html('<p class="text-warning"><i class="fa fa-warning"></i> Caracter Invalido <p>');
       	$('#descripcion').val("");
      $("#descripcion").focus();
      return false;
  				}
}
$(".preload, .load").hide();
$( document ).ready(function() {

$('#folio').keypress(function(e){
	
	
	var keynum = window.event ? window.event.keyCode : e.which;
	keynum2="";
	var info=$('#folio').val();
	
	for(var i=0;i<info.length;i++){
		if(info[i]==","){
			keynum2=103;
			i=info.length;
		}
	}
	
if((info.length==5 && (keynum != 8&&keynum != 0)) || (keynum==44 && keynum2!=103 && info.length==4)|| (keynum==44 && info.length==0)
|| ((keynum!=44&&keynum!=8&&keynum!=0&&keynum!=13&&keynum!=0&&keynum!=82) && info.length==2 && keynum2!=103) )
	return false;
		
if ((keynum>47 && keynum<58) || keynum == 8 || (keynum==44 && keynum2!=103) || keynum == 0 || keynum == 13 || keynum == 82 ) 
return true;
return /\d/.test(String.fromCharCode(keynum));
});
$('#folio').change(function(){
num_acta = document.getElementById("folio").value;
 	    if( !(/[A-z-\/\*\,\.\ \;\:\{\}\[\]\^\`\¨\´\+\~\°\|\¬\!\¡\'\?\¿\=\)\(\&\%\$\#\"\<\>\Ñ\ñ\+]/.test(num_acta)) )
		{}
  else{
$('#folio').val("");
  }
});    
//var ciudad = $("#ciudades").val();
//$('#ciudad').val(ciudad);
 $('#folio').change(function(){

 	    if ($('#folio').val().indexOf('%') != -1 || $('#folio').val().indexOf('<') != -1) 
       { 	
       	$("#alerta").show();
       	$('#alerta').html('<p class="text-warning"><i class="fa fa-warning"></i> Caracter Invalido "%|<"<p>');
       	$('#folio').val("");
      $("#folio").focus();
       return false;

  }
  else{
  	$("#alerta").hide();
  }

});

    	

    
    $('#enviar').click(function(){
    	
    	    	    if ($("#exp").val().length < 1) 
       {
      $('#alerta1').html('<p class="text-warning"><i class="fa fa-warning"></i> Campo No. Expediente Obligatorio<p>');
      $("#exp").focus();
            return false;
  }
        	
    	    if ($("#folio").val().length < 1) 
       {
      $('#alerta2').html('<p class="text-warning"><i class="fa fa-warning"></i> Campo Folio Obligatorio<p>');
      $("#folio").focus();
       return false;
       }
  
  $("#alerta1").hide();
    $("#alerta2").hide();
    $(".preload, .load").show();
    	j=0;
    	var doc = new Array();;
    for(i=1;i<9;i++){
    	if(document.getElementById(i).checked==true)
    	{
    		doc[j]=document.getElementById(i).value;
    		    j++;		
    	}
    	
    }
    	
    	
    var	nit = $("#nit").val();
    var	cod_fisc = $("#cod_fisc").val();
    var	exp = $("#exp").val();
    var	folio = $("#folio").val();
    var urlacta = "<?php echo base_url('index.php/fiscalizacion/generar_acta_via_gubernativa') ?>";
        $('#generar_acta').load(urlacta, {cod_fisc: cod_fisc, nit: nit, doc: doc, exp: exp, folio: folio });
       
       });         
      });   
            
                function ajaxValidationCallback(status, form, json, options) {
	
}
             
</script>

<style>
    div.preload{
        position: fixed;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        background-color: white;
        opacity: 0.8;
        z-index: 10000;
    }

    div img.load{
        position: absolute;
        left: 50%;
        top: 50%;
        margin-left: -64px;
        margin-top: -64px;
        z-index: 15000;
    }
</style>