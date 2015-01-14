
<div>
    <table width='100%'>
        <tr>
            <td>RESOLUCION - TITULO</td>
            <td><input type="checkbox" name="resolucion" id="resolucion"></td>
            <td>CITACIÓN</td>
            <td><input type="checkbox" name="citacion" id="citacion"></td>
        </tr>
        <tr>
            <td>RESOLUCION RECURSO</td>
            <td><input type="checkbox" name="recurso" id="recurso"></td>
            <td>EJECUTORIA</td>
            <td><input type="checkbox" name="ejecutoria" id="ejecutoria"></td>
        </tr>
        <tr>
            <td>REVOCATORIA</td>
            <td><input type="checkbox" name="revocatoria" id="revocatoria"></td>
            <td>LIQUIDACION CTÓ.</td>
            <td><input type="checkbox" name="liquidacion" id="liquidacion"></td>
        </tr>
        <tr>
            <td colspan="4" align='center' ><button id="documento" class="btn btn-success">Obtener Documentos</button> 
                <button style="display: none" id="ver_documentos" class="btn btn-success">ver documentacion</button>
            </td>
        </tr>
    </table>
</div>
<form action="<?php echo base_url('index.php/ejecutoriaactoadmin/guardar_paso_juridico2'); ?>" method="post" onsubmit="return  correccion()" id="form1">
    <div id="resultado_documento" style="display: none"></div>
    <div id="inputs" style=""></div>
    <input type="hidden" id="autoriza" name="autoriza" value="0">
    <input type="hidden" id="informacion" name="informacion">
    <input type="hidden" id="id" name="id" value="<?php echo $post['id']; ?>">
    <input type="hidden" id="cod_fis" name="cod_fis" value="<?php echo $post['cod_fis']; ?>">
    <input type="hidden" id="cod_revocatoria" name="cod_revocatoria" value="<?php echo $post['cod_revocatoria']; ?>">
    <input type="hidden" id="nit" name="nit" value="<?php echo $post['nit']; ?>">
    <input type="hidden" id="cod_estado" name="cod_estado" value="<?php echo $post['cod_estado']; ?>">
</form>
<textarea id="datos" style="width: 100%; "><?php echo $txt; ?></textarea>
<centrar>
    <button id="generar" class="btn btn-success">Generar</button>
</centrar>
<script>
    function correccion(){
        if($('#autoriza').val()==0){
            alert('Debe seleccionar los Documentos a Enviar');
            return false;
        }else
            return true;
    }
    $('#ver_documentos').click(function() {
        $('#resultado_documento').dialog({
            autoOpen: true,
            modal: true,
            width: 700
        });
    });
    $('#documento').click(function() {
        var resolucion = $('#resolucion').is(':checked');
        var citacion = $('#citacion').is(':checked');
        var recurso = $('#recurso').is(':checked');
        var ejecutoria = $('#ejecutoria').is(':checked');
        var revocatoria = $('#revocatoria').is(':checked');
        var liquidacion = $('#liquidacion').is(':checked');
        var id = '<?php echo $post['id']; ?>';
        var cod_fis = '<?php echo $post['cod_fis']; ?>';
        
        $(".preload, .load").show();
        var url = '<?php echo base_url('index.php/ejecutoriaactoadmin/documentacion'); ?>';
        $.post(url, {liquidacion:liquidacion,cod_fis: cod_fis, id: id, resolucion: resolucion, citacion: citacion, recurso: recurso, ejecutoria: ejecutoria, revocatoria: revocatoria})
                .done(function(msg) {
                    $('#resultado_documento').html(msg.total);
                    $('#inputs').html(msg.input);
                    $('#ver_documentos').show();
                    $('#autoriza').val('1');
                    $(".preload, .load").hide();
                }).fail(function(msg) {
                    $(".preload, .load").hide();
        });
    });


    $('#resultado').dialog({
        autoOpen: true,
        modal: true,
        width: 800,
        heigth: 800
    })
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
    $('#generar2').click(function() {
        var informacion = tinymce.get('informacion').getContent();
        if (informacion == "") {
            alert("El campo texto se encuentra vacio");
            return false
        }
        $(".preload, .load").show();
        var cod_fis = '<?php echo $post['cod_fis']; ?>';
        var cod_revocatoria = '<?php echo $post['cod_revocatoria']; ?>';
        var id = '<?php echo $post['id']; ?>';
        var nit = '<?php echo $post['nit']; ?>';
        var url = "<?php echo base_url('index.php/ejecutoriaactoadmin/guardar_paso_juridico'); ?>";
        $.post(url, {cod_fis: cod_fis, cod_revocatoria: cod_revocatoria, id: id, nit: nit, informacion: informacion})
                .done(function() {
                    alert('Los datos Fueron Guardados Con Exito');
                    window.location.reload();
                }).fail(function() {
            alert('Error en la base de datos');
            $(".preload, .load").hide();
        })

    })
</script>