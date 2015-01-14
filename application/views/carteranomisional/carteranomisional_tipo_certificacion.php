 <div>	


 	 <textarea id="informacion" style="width: 100%;height: 400px">
 	 	<?php 
	echo($docreemplazado);
 	 	?>

 	 </textarea>
 	
 	 </div>
 	 
 	 <br><br>
 	 <center>
 <input type='button' name='enviar' class='btn btn-success' value='Guardar Comunicado'  id='enviar' >
 </center>


 
<script >
$( document ).ready(function() {
	
$(".preload, .load").hide();

tinymce.init({
        selector: "textarea",
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
    
    
    $('#enviar').click(function(){
    	
    });         
      });   
                 function ajaxValidationCallback(status, form, json, options) {
	
}
             
</script>

 