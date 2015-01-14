<form action="<?php echo base_url('index.php/ejecutoriaactoadmin/guardar_paso_juridico2'); ?>" method="post" onsubmit="return  correccion()" id="form1">
    <div id="resultado_documento" style="display: none"></div>
    <div id="inputs" style=""></div>
    <input type="hidden" id="autoriza" name="autoriza" value="0">
    <input type="hidden" id="informacion" name="informacion">
    <input type="hidden" id="id" name="id" value="<?php echo $post['id']; ?>">
    <input type="hidden" id="cod_fis" name="cod_fis" value="<?php echo $post['cod_fis']; ?>">
    <input type="hidden" id="nit" name="nit" value="<?php echo $post['nit']; ?>">
    <input type="hidden" id="cod_estado" name="cod_estado" value="">
</form>
<textarea id="datos" style="width: 100%;height:300px "><?php echo $txt; ?></textarea>
<centrar>
    <button id="generar" class="btn btn-success">Generar</button>
</centrar>
<script>
    function correccion(){
        var informacion = tinymce.get('datos').getContent();
        if(informacion==''){
            alert('El campo texto se encuentra vacio');
            return false;
        }else
            return true;
    }


    $('#resultado').dialog({
        autoOpen: true,
        modal: true,
        width: 800,
        title:"Envio a Coactivo"
    });
    $(".preload, .load").hide();
    tinymce.init({
        language: 'es',
        selector: "textarea#datos",
        theme: "modern",
        plugins: [
            "advlist autolink lists link  charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code fullscreen",
                    //    "insertdatetime media nonbreaking save table contextmenu directionality",
                    //  "emoticons template paste textcolor moxiemanager"
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
    $('#generar').click(function() {
        var informacion = tinymce.get('datos').getContent();
        $('#informacion').val(informacion);
        $('#form1').submit();
    });
</script>