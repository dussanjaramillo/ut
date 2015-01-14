
<center>
        <?php echo $custom_error; ?>

<p>   
     <?php
              foreach($motivos as $row) {
                  $select[$row->COD_MOTIVO_CITACION] = $row->NOMBRE_MOTIVO;
               }
          echo form_dropdown('motivo', $select,'','id="motivo" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('motivo','<div>','</div>');

        ?>
  </p>




</center>
<input type="hidden" id="visitas_programadas" name="visitas_programadas" value=<?=$visitas_programadas["VISITAS"]?>>
<input type="hidden" id="cod_fisc" name="cod_fisc" value=<?=$cod_fisc?>> 	
<input type="hidden" id="nit" name="nit" value=<?=$nit?>> 

 <div id="div1" >
 	

 	 <textarea id="informacion" class="info" style="width: 100%;height: 400px">
 	 	<?php 
 	 
echo $filas;

 	 	?>
 	 	 </textarea>
</div>


 	<div id="div2"> 	
 	
 	  	 <textarea id="informacion2" sclass="info2" style="width: 100%;height: 400px">
 	 	<?php 

 echo $filas2;

 	 	?>

 	 	
 	 </textarea>
 	
 	 </div>
 	 <center>
 	 </br>
 	 	 <input type='button' name='enviar' class='btn btn-success' value='Guardar Comunicado'  id='enviar' >
  <input type='button' name='pdf' class='btn btn-success' value='Generar PDF'  id='pdf' >
    <input type='button' name='pdfimp' class='btn btn-success' value='Imprimir'  id='pdfimp' >
 </center>
 
 
<script >
$(".preload, .load").hide();
$( document ).ready(function() {

//var ciudad = $("#ciudades").val();
//$('#ciudad').val(ciudad);
  $("#motivo").change(function() {
  var motiv = document.getElementById('informacion').style.display;

switch($("#motivo").val()){
	case '1':
	$("#div1").show();
	$("#div2").hide();	
	break;
	case '2':
	$("#div2").show();
	$("#div1").hide();	
	break;
}
  
  
  });


tinymce.init({
	language: 'es',
        selector: "#informacion",
        theme: "modern",
        plugins: [
            "advlist autolink lists link image charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code fullscreen",
            "insertdatetime media nonbreaking save table contextmenu directionality",
            "emoticons template paste textcolor"
        ],
        toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
        toolbar2: "print preview media | forecolor backcolor emoticons",
        image_advtab: true,
        templates: [
            {title: 'Test template 1', content: '<b>Test 1</b>'},
            {title: 'Test template 2', content: '<em>Test 2</em>'}
        ],
        autosave_ask_before_unload: false
    });
 tinymce.init({
	
        selector: "#informacion2",
        theme: "modern",
        plugins: [
            "advlist autolink lists link image charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code fullscreen",
            "insertdatetime media nonbreaking save table contextmenu directionality",
            "emoticons template paste textcolor moxiemanager"
        ],
        toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
        toolbar2: "print preview media | forecolor backcolor emoticons",
        image_advtab: true,
        templates: [
            {title: 'Test template 1', content: '<b>Test 1</b>'},
            {title: 'Test template 2', content: '<em>Test 2</em>'}
        ],
        autosave_ask_before_unload: false
    });   
 	$("#div2").hide();
    
        $('#enviar').click(function(){
    $(".preload, .load").show();	
 var	nit = $("#nit").val();
    var	cod_fisc = $("#cod_fisc").val();
    var cod_motivo = $("#motivo").val();
    var	visitas = $("#visitas_programadas").val(); 
    if($("#motivo").val()=='1')
    {
    	var informacion = tinymce.get('informacion').getContent();
    }
    else{
    	var informacion = tinymce.get('informacion2').getContent();
    }
   var url = "<?php echo base_url('index.php/fiscalizacion/guardar_comunicacion_presentarse_sena') ?>";
        $.post(url, {informacion: informacion, cod_fisc: cod_fisc, nit: nit, cod_motivo: cod_motivo, visitas: visitas })
        	.done(function(data)
        {
        alert("guardado con exito");
		$('#retorno_fisc').submit();
        	       })
        .fail(function(data)
        {
        alert("No se pudo realizar al gestión");
        	
        });

       

       
       
       });  
    
        
      });   
      
      
      $('#pdf').click(function() {
            if($("#motivo").val()=='1')
    {
    	var informacion = tinymce.get('informacion').getContent();
    }
    else{
    	var informacion = tinymce.get('informacion2').getContent();
    }
        var nombre = "<?php echo $cod_fisc ?>";
        if (informacion == "" || nombre == "") {
            alert('No se puede continuar hasta que haya realizado en cuerpo del documento');
            return false;
        }
        $(".preload, .load").show();
        var url = '<?php echo base_url('index.php/fiscalizacion/pdf') ?>';
        redirect_by_post(url, {
            html: informacion,
            nombre: nombre,
            mod: "D" 
        }, true);
        $(".preload, .load").hide();
    });
    
    $('#pdfimp').click(function() {
            if($("#motivo").val()=='1')
    {
    	var informacion = tinymce.get('informacion').getContent();
    }
    else{
    	var informacion = tinymce.get('informacion2').getContent();
    }
        var nombre = "<?php echo $cod_fisc ?>";
        if (informacion == "" || nombre == "") {
            alert('No se puede continuar hasta que haya realizado en cuerpo del documento');
            return false;
        }
        $(".preload, .load").show();
        var url = '<?php echo base_url('index.php/fiscalizacion/pdf') ?>';
        redirect_by_post(url, {
            html: informacion,
            nombre: nombre,
            mod: "I" 
        }, true);
        $(".preload, .load").hide();
        
    });
    
    function redirect_by_post(purl, pparameters, in_new_tab) {
        pparameters = (typeof pparameters == 'undefined') ? {} : pparameters;
        in_new_tab = (typeof in_new_tab == 'undefined') ? true : in_new_tab;
        var form = document.createElement("form");
        $(form).attr("id", "reg-form").attr("name", "reg-form").attr("action", purl).attr("method", "post").attr("enctype", "multipart/form-data");
        if (in_new_tab) {
            $(form).attr("target", "_blank");
        }
        $.each(pparameters, function(key) {
            $(form).append('<textarea name="' + key + '" >' + this + '</textarea>');
        });
        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);
        return false;
    }
             
                 function ajaxValidationCallback(status, form, json, options) {
	
}
</script>

 