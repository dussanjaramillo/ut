<center>
<h2>Comunicación Oficial de Visita <?=$visitas_programadas["VISITAS"]?></h2>
</center>

 <div>	
<input type="hidden" id="cod_fisc" name="cod_fisc" value=<?=$cod_fisc?>> 	
<input type="hidden" id="nit" name="nit" value=<?=$nit?>> 
<input type="hidden" id="visitas_programadas" name="visitas_programadas" value=<?=$visitas_programadas["VISITAS"]?>>
 	 <textarea id="informacion" style="width: 100%;height: 400px">
 	 	<?php 
	echo($docreemplazado);
 	 	?>

 	 </textarea>
 	
 	 </div>
 	 
 	 <br><br>
 	 <center>
 <input type='button' name='enviar' class='btn btn-success' value='Guardar Comunicado'  id='enviar' >
  <input type='button' name='pdf' class='btn btn-success' value='Generar PDF'  id='pdf' >
    <input type='button' name='pdfimp' class='btn btn-success' value='Imprimir'  id='pdfimp' >
 </center>


 
<script >
$( document ).ready(function() {
	
$(".preload, .load").hide();

tinymce.init({
	language: 'es',
        selector: "textarea",
        theme: "modern",
        plugins: [
            "advlist autolink lists link image charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code fullscreen",
            "insertdatetime media nonbreaking save table contextmenu directionality",
            "emoticons template paste textcolor"
        ],
        toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
        toolbar2: "preview media | forecolor backcolor emoticons",
        image_advtab: true,
        templates: [
            {title: 'Test template 1', content: '<b>Test 1</b>'},
            {title: 'Test template 2', content: '<em>Test 2</em>'}
        ],
        autosave_ask_before_unload: false
    });
    
    
    $('#enviar').click(function(){
    $(".preload, .load").show();	
    var	nit = $("#nit").val();
    var	cod_fisc = $("#cod_fisc").val();
    var	fecha_visita = $("#fecha_comunicacion").val();
    var	hora_inicio = $("#hora_inicio").val();
    var	hora_fin = $("#hora_fin").val();
    var	visitas = $("#visitas_programadas").val(); 
    var informacion = tinymce.get('informacion').getContent();
    var url = "<?php echo base_url('index.php/fiscalizacion/guardar_comunicacion_oficial_visita') ?>";
        $.post(url, {informacion: informacion, cod_fisc: cod_fisc, nit: nit, fecha_visita: fecha_visita, hora_inicio: hora_inicio, hora_fin: hora_fin, visitas: visitas })
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
        var informacion = tinymce.get('informacion').getContent();
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
        var informacion = tinymce.get('informacion').getContent();
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

 