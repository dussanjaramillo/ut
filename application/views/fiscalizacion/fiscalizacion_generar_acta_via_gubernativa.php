

<p></p><p></p><p></p>

 <div>
 	
<input type="hidden" id="cod_fisc" name="cod_fisc" value=<?=$cod_fisc?>> 	
<input type="hidden" id="nit" name="nit" value=<?=$nit?>> 
 	 <textarea id="informacion" style="width: 100%;height: 400px">
 	 	<?php 
echo $informereemplazado;
 	 	?>
 	 </textarea>
 	
 	 </div>
 	 <center>
 	 	<br>
 	 	<br>
 <input type='button' name='enviaracta' class='btn btn-success' value='Guardar Acta'  id='enviaracta' >
   <input type='button' name='pdf' class='btn btn-success' value='Generar PDF'  id='pdf' >
    <input type='button' name='pdfimp' class='btn btn-success' value='Imprimir'  id='pdfimp' >
 </center>
 

 
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
 
 
<script >
$( document ).ready(function() {
	
$(".preload, .load").hide();
//var ciudad = $("#ciudades").val();
//$('#ciudad').val(ciudad);
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
    
    
    $('#enviaracta').click(function(){
    	
$(".preload, .load").show();
    	
    var	nit = $("#nit").val();
    var	cod_fisc = $("#cod_fisc").val();
    var informacion = tinymce.get('informacion').getContent();
    var url = "<?php echo base_url('index.php/fiscalizacion/guardar_acta_gubernativa') ?>";
    var urlfisc = "<?php echo base_url('index.php/fiscalizacion') ?>";
        $.post(url, {informacion: informacion, cod_fisc: cod_fisc, nit: nit })
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
 