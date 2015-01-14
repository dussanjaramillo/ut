<?php 
if (isset($message)){
    echo $message;
   }
?>
 
<h1>Cargue de Archivos de Cartera No Misional</h1>
</br></br></br>


<form action="" method="post" id="uploadFile">

<center>

<table>

          <tr>
    <td>&nbsp;</td>	
    <td>&nbsp;</td>
    <td><input type="file" name="userFile" id="userFile" size="20" required="required"/>

                           
                     
</td>
    <td>&nbsp;</td>
    <td></td>
    <td><select id="archivos" style="width : 100%;" multiple>
    	

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
  
  <form id="cartera_form" action="<?= base_url('index.php/carteranomisional/carga_archivo_cart') ?>" method="post" >
    <input type="hidden" id="vista_flag" name="vista_flag" value="1" >
    <input type="hidden" id="id_cartera_form" name="id_cartera_form" value="<?=$id_cartera ?>">
    <input type="hidden" id="tipo_cartera" name="tipo_cartera" value="<?=$tipo_cartera?>" >
    <input type="hidden" id="agregar" name="agregar" value="<?=$agregar?>" >
    <input type="hidden" id="documentos" name="documentos">
</form>

<script type="text/javascript" language="javascript" charset="utf-8">


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

 $('#cartera_form').submit();

    });

           function ajaxValidationCallback(status, form, json, options) {
	
}



</script>


