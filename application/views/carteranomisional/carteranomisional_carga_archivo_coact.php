<div class="center-form-large" width='850' height= '490'> 
<center>
<h3>Cargue de Archivos de Cartera No Misional</h3>
</center>
</br></br></br>

<form action="" method="post" id="uploadFile">

<center>

<table>

          <tr>
    <td>&nbsp;</td>	
    <td>&nbsp;</td>
    <td><input type="file" name="userFile" id="userFile" size="20" required="required" />

                           
                     
</td>
    <td>&nbsp;</td>
    <td></td>
    <td><select id="archivos" style="width : 100%;" multiple>
    	<?php 
    	if($lista_adjuntos!=""){
    		
	foreach ($lista_adjuntos as $key => $value) {
		?>
<option value="<?=$value?>"><?=$value?></option>		
			
			<?php }
		}
		
    	echo $lista_adjuntos?>

    </select></td>
    <td>&nbsp;</td>
  </tr> 
  
  <tr><td>&nbsp;</td>	</tr>
          <tr>
    <td>&nbsp;</td>	
    <td>&nbsp;</td>
    <td><center>

                <input type="submit" value="Carga" id="cargue" />
                </center>
</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><input type="button" value="Eliminar" id="eliminar_arch" style="width : 91%;"></td>
    <td>&nbsp;</td>
  </tr> 
  <tr><td>&nbsp;</td>	</tr> 
</table>
</center>
<input type="hidden" id="vista_flag" name="vista_flag" value="1" >
<input type="hidden" id="id_cartera" name="id_cartera" value="<?=$id_cartera?>" >

</form>

<center>
<p>
	 
 <input type='button' name='guardar' class='btn btn-success' value='GUARDAR'  id='guardar' >

   </p> 
   
   </center>
 
</div> 
  
  <form id="cartera_form" action="<?= base_url('index.php/carteranomisional/carga_archivo_coact') ?>" method="post" >
    <input type="hidden" id="vista_flag" name="vista_flag" value="1" >
    <input type="hidden" id="id_cartera_form" name="id_cartera_form" value="<?=$id_cartera ?>">
    <input type="hidden" id="tipo_cartera" name="tipo_cartera" value="<?=$tipo_cartera?>" >
    <input type="hidden" id="cod_recepcion" name="cod_recepcion" value="<?=$cod_recepcion?>" >
    <input type="hidden" id="documentos" name="documentos">
</form>

<script type="text/javascript" language="javascript" charset="utf-8">

	$('#gestion').dialog({
                autoOpen: true,
                width: 850,
                height: 490,
                modal:true,
                title:'Paso Coactivo',
                close: function() {
                  $('#gestion *').remove();
                
                
                }
            });	

   $(function() {
                $('#uploadFile').submit(function(e) {
                    e.preventDefault();
 
                    var doUploadFileMethodURL = "<?= site_url('carteranomisional/doUploadFile'); ?>/"+$("#id_cartera_form").val();
                    $.ajaxFileUpload({
                        url : doUploadFileMethodURL,
                        secureuri : false,
                        type: 'post',
                        fileElementId :'userFile',
                        dataType : 'json',
                        data : { 'title' : $('#title').val() , 'id_calamidad' : $('#id_calamidad').val()},
                        success  : function (data) {
                        	       
    var archivoCarga = data.nombre;
    var archivosSelect = $("#archivos option").val();	
	var repeticionC=0;
if (archivosSelect!=null&&archivoCarga!=null){

	$("#archivos option").each(function(){
    		if($(this).attr('value')==archivoCarga){
    			repeticionC=1;
    		}
   	    });
   }
        	    if (repeticionC==0&&archivoCarga!=""){
    	    	
    	    	$("#archivos").append("<option value=\""+archivoCarga+"\">"+archivoCarga+"</option>");
    	    }
	else{}
                        },                         
                        error  : function (data) {
alert ("El archivo no se ha podido cargar, el formato puede no ser valido");
                        }
                    });
                    //                    return false;
                });
            });
  
  $('#eliminar_arch').click(function(){
    var archivosSelect = $("#archivos").val();	
    	
    	if (archivosSelect!=null){
    	$.each(archivosSelect, function(k,v){
    		
$("#archivos option[value=\""+v+"\"]").remove();

    	    });
    	   }
    	   else{
    	   	alert ("Por favor seleccione uno de los documentos que desea eliminar");
    	   }
    });
    
      $('#guardar').click(function(){
      		var archivo_carga="";
      		var cont=0;
      		    	$("#archivos option").each(function(){
    		
    		if(cont>0){
archivo_carga=archivo_carga+"::"+$(this).attr('value');

}
else{
	archivo_carga=$(this).attr('value');
}

cont++;
    	    });
 $('#documentos').val(archivo_carga);


        var url = "<?php echo base_url('index.php/carteranomisional/carga_archivo_coact') ?>";
        var vista_flag=$('#vista_flag').val();
        var id_cartera_form=$('#id_cartera_form').val();
        var tipo_cartera=$('#tipo_cartera').val();
        var documentos=$('#documentos').val();
        var cod_recepcion=$('#cod_recepcion').val();  
        var atcoactivo=$('#id_cartera_form').val();
		var attipo=$('#tipo_cartera').val();
          	
        $.post(url, {vista_flag: vista_flag, id_cartera_form: id_cartera_form, tipo_cartera: tipo_cartera, documentos: documentos, cod_recepcion: cod_recepcion})
        	.done(function(data)
        {
       	$('#gestion').dialog('close');
   		$('#resultado').dialog('close');
       	var urlcoact="<?= base_url('index.php/carteranomisional/observaciones')?>";
		$('#resultado').load(urlcoact,{atcoactivo : atcoactivo, attipo : attipo });
       	  })
        	.fail(function(data)
        {
        alert("No se pudó cargar los archivos");
        	
        	});

    });

           function ajaxValidationCallback(status, form, json, options) {
	
}



</script>


