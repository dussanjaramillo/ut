<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<div class="center-form-large">



<center>
<h2>Registro Cobro Beneficiarios Doble Mesada Pensional</h2>
<table>
  
  <tr>
  	<td>&nbsp;</td>	
  	
  	</tr>
  	
  	    <tr>
  	<td>&nbsp;</td>
    <td>Identificación</td>
    <td><input type="text" id="cedula" name="cedula" style="width : 91%;" required="required" readonly="readonly" value="<?=$detalleCart["IDENTIFICACION"]?>"></td>
    <td>&nbsp;</td>
    <td>Identificación Deuda</td>
    <td><input type="text" id="id_deuda" name="id_deuda" style="width : 91%;" readonly="readonly" value="<?=$detalleCart["COD_CARTERA_NOMISIONAL"]?>"></td>
    <td>&nbsp;</td>
  </tr>
  
   <tr>
	<td>&nbsp;</td>
    <td>Apellidos Empleado&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</td>
    <td><input type="text" id="apellidos" style="width : 91%;" readonly="readonly" required="required" value="<?=$detalleCart["APELLIDOS"]?>"></td>
    <td>&nbsp;</td>
    <td>Nombres Empleado</td>
    <td><input type="text" id="nombres" style="width : 91%;" readonly="readonly" required="required" value="<?=$detalleCart["NOMBRES"]?>"></td>
    <td>&nbsp;</td>
  </tr>
  
  </table>
 
  <b>Beneficiarios</b>
 </br>
<table width=100% id="tablaq">
 <thead>
<tr>
     <th>Seleccionar</th>    
     <th>Identificación</th>
     <th>Nombre y Apellidos</th>
      <th>Parentesco</th>    
      <th>Porcentaje a Distribuir</th>
     <th>Valor a Distribuir</th>
   
   
    </tr>
 </thead>
<tbody>
  <?php
foreach ($beneficiarios->result_array as $data) {?> 
	<tr>
<td><center><input class='atcnm' type=checkbox id="2"></center></td>	
<td><?=$data['IDENTIFICACION_BENEFICIARIO']  ?></td>
<td><?=$data['NOMBRES']  ?></td>	
<td><?=$data['PARENTESCO']  ?></td>	
<td><input type="text" id="porcentaje_dist" name="porcentaje_dist" maxlength="10" style="width : 91%;" required="required" readonly="readonly"></td>
<td><input type="text" id="valor_dist" name="valor_dist" maxlength="10" style="width : 91%;" required="required" readonly="readonly" onkeypress ="num(this);" onblur = "num(this);" onkeyup = "num(this);"></td>
</tr>
<?php
} ?>

 </tbody>
  </table>

<table>
  
  <tr>
  	<td>&nbsp;</td>	
  	
  	</tr>
  	
  	    <tr>
  	<td>&nbsp;</td>
    <td>&nbsp;</td>
   <td>&nbsp;</td>
   <td>&nbsp;</td>
    <td>Saldo a Distribuir</td>
    <td align="right"><input type="text" id="saldo_deuda" name="saldo_deuda" value="<?=$detalleCart['SALDO_DEUDA']?>" readonly="readonly" style="width : 85%;"></td>
    <td>&nbsp;</td>
  </tr>
  </table>


</center>  
<input type="hidden" id="vista_flag" name="vista_flag" value='1' >        

      
<p>
 <center>	 
 <input type='button' name='aceptar' class='btn btn-success' value='Aceptar'  id='aceptar' >
 <input type='button' name='cancelar' class='btn btn-success' value='Cancelar'  id='cancelar' >

 </center>
   </p> 

    
</div>


<script type="text/javascript" language="javascript" charset="utf-8">

	$('.atcnm').click(function(){
		var cod_cartera = $(this).attr('atcnm');
		$('#id_cartera').val(cod_cartera);
	});
	

       $('.atcnm').change(function(){
	var chek = document.getElementById($(this).attr('atcnm')).checked;

	if(chek==true)
	{
		$(this).('#porcentaje_dist').attr("disabled",false);     	
		$(this).('#porcentaje_dist').attr("readonly",false);  
   }
    else{
    	$(this).('#porcentaje_dist').attr("disabled",true);     	
    	$(this).('#porcentaje_dist').attr("readonly",true);
    }
       });

 function num(c){
  c.value = c.value.replace(/[A-z-\/\*\,\ \;\:\{\}\[\]\^\`\¨\´\+\~\°\|\¬\!\¡\'\?\¿\=\)\(\&\%\$\#\"\<\>\Ñ\ñ\+]/, '');
  // x = c.value;
    // c.value = x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    var num = c.value.replace(/\./g,'');
  if(!isNaN(num))
  {
   num = num.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
   num = num.split('').reverse().join('').replace(/^[\.]/,'');
   c.value = num;
  }
 }
 
 
 

$('#valor_dist').keypress(function(e){
	
	var keynum = window.event ? window.event.keyCode : e.which;
	keynum2="";
	var info=$('#valor_dist').val();
	

	
if((info.length==10 && (keynum != 8&&keynum != 0)) || (keynum==44 && info.length==0) )
	return false;
		
if ((keynum>47 && keynum<58) || keynum == 8 || keynum == 0 || keynum == 13 || keynum == 82 ) 
return true;
return /\d/.test(String.fromCharCode(keynum));
});

$('#valor_dist').change(function(){
var info=$('#valor_dist').val()
num_acta = document.getElementById("porcentaje_dist").value;
 	    if( !(/[A-z-\/\*\,\ \;\:\{\}\[\]\^\`\¨\´\+\~\°\|\¬\!\¡\'\?\¿\=\)\(\&\%\$\#\"\<\>\Ñ\ñ\+]/.test(num_acta)) )
		{}
  else{
$('#valor_dist').val("");
  }
});     


$('#porcentaje_dist').keypress(function(e){
	
	
	var keynum = window.event ? window.event.keyCode : e.which;
	keynum2="";
	var info=$('#porcentaje_dist').val();
	
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

 $('#porcentaje_dist').change(function(){
num_acta = document.getElementById("porcentaje_dist").value;
 	    if( !(/[A-z-\/\*\.\ \;\:\{\}\[\]\^\`\¨\´\+\~\°\|\¬\!\¡\'\?\¿\=\)\(\&\%\$\#\"\<\>\Ñ\ñ\+]/.test(num_acta)) )
		{}
  else{
$('#porcentaje_dist').val("");
  }
});
	
 

$('#tablaq').dataTable({
        "bJQueryUI": true,
        "sPaginationType": "full_numbers",
        "oLanguage": {
            "sProcessing":     "Procesando...",
            "sLengthMenu":     "Mostrar _MENU_ registros",
            "sZeroRecords":    "No se encontraron resultados",
            "sEmptyTable":     "Ningún dato disponible en esta tabla",
            "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix":    "",
            "sSearch":         "Buscar:",
            "sUrl":            "",
            "sInfoThousands":  ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
            "sFirst":    "Primero",
            "sLast":     "Último",
            "sNext":     "Siguiente",
            "sPrevious": "Anterior"
             },
    "fnInfoCallback": null,
            },

          	"bLengthChange": false,
        	"aoColumns": [ 


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
