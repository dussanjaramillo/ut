
<textarea id="considerando2" class="testo" name="considerando2"><?php echo $post['consi']; ?></textarea><p>
<center>
    <button id="considerando_enviar" align="center" class="btn btn-primary">Guardar</button>
</center>

<script>

$(".preload, .load").hide();
    $('#resultado').dialog({
        autoOpen: true,
        width: 800,
        modal: true,
        title: 'Considerando',
        close: function() {
            $('#resultado *').remove();
        }
    });
    
    tinymce.init({
        selector: "textarea.testo",
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
    
    $('#considerando_enviar').click(function(){
       var considerando = tinymce.get('considerando2').getContent();
       $('#considerando').val(considerando);
       $('#resultado *').remove();
       $('#resultado').dialog('close');
    });
    
</script>
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />