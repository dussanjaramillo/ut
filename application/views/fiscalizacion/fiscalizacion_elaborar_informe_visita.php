
<p></p>


 <div>
 	
<input type="hidden" id="cod_fisc" name="cod_fisc" value=<?=$cod_fisc?>> 	
<input type="hidden" id="nit" name="nit" value=<?=$nit?>> 
<input type="hidden" id="cod_gestion" name="cod_gestion" value=<?=$cod_gestion?>> 
 	 <textarea id="informacion" style="width: 100%;height: 400px">
 	 	<?php 
echo $informereemplazado;

 	 	?>

 	 	
 	 </textarea>
 	
 	 </div>
 	 <center>
 <input type='button' name='enviar' class='btn btn-success' value='Enviar'  id='enviar' >
 </center>
 
 
<script >
$( document ).ready(function() {
$(".preload, .load").hide();

 tinymce.init({
        language: 'es',
        selector: "textarea#informacion",
        theme: "modern",
        plugins: [
            "advlist autolink lists link  charmap print preview hr anchor pagebreak",
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

    
    $('#enviar').click(function(){
    	  $(".preload, .load").show();	
    var	nit = $("#nit").val();
    var	cod_fisc = $("#cod_fisc").val();
    var	cod_gestion = $("#cod_gestion").val();
    var informacion = tinymce.get('informacion').getContent();
       var url = "<?php echo base_url('index.php/fiscalizacion/guardar_informe_visita') ?>";
    var urlfisc = "<?php echo base_url('index.php/fiscalizacion') ?>";
        $.post(url, {informacion: informacion, cod_fisc: cod_fisc, nit: nit, cod_gestion:cod_gestion })
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
          function ajaxValidationCallback(status, form, json, options) {
	
}
             
</script>

 