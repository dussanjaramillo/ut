<h1>Plantilla</h1>


 <div>
 	 <textarea id="informacion" style="width: 100%;height: 400px"></textarea>
 	
 	 </div>
 <input type='button' name='enviar' class='btn btn-success' value='Enviar'  id='enviar' >
 
<script >


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
    var informacion = tinymce.get('informacion').getContent();
    var url = "<?php echo base_url('index.php/fiscalizacion/guardar_archivo') ?>";
        $.post(url, {informacion: informacion})
                .done(function(msg) {
                    
                })
                .fail(function(xhr) {
                    $(".preload, .load").hide();
                    alert("Los datos no fueron guardados");
                });
                });
                
                    function ajaxValidationCallback(status, form, json, options) {
	
}
</script>

 