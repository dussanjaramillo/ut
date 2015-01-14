
<textarea id="informacion" name="informacion" style="width: 100%;height: 300px;"><?php echo $texto ?></textarea>  

<div id="primer">
    <form id="form" name="form" target = "_blank"  method="post" action="<?php echo base_url('index.php/autocargos/pdf') ?>">
        <textarea id="descripcion_pdf" name="descripcion_pdf" style="width: 100%;height: 300px;display: none "><?php echo $texto ?></textarea>  
        <input type="hidden" name="nombre" id="nombre" value="">
    </form>
    <center>
        <button id="" onclick="enviar_pdf()" class="btn btn-success">PDF</button>
        <button id="" onclick="aprobar_doc()" class="btn btn-success">Enviar</button>
    </center>
</div>

<table width="100%">
    <tr>
        <td>
            <b>Observaciones Anteriores</b><p>
        </td>
    </tr>
    <tr>
        <td>
            <?php echo $traza; ?>
        </td>
    </tr>
</table>
<script>
    $('#observa').validCampoFranz(' abcdefghijklmnñopqrstuvwxyzáéiou0123456789');
    $('#enviar_obser').click(function() {
        var url = "<?php echo base_url('index.php/autopruebas/guardar_trazabilidad') ?>";
        var num_auto = "<?php echo $post['num_auto'] ?>";
        var cod_fis = "<?php echo $post['cod_fis'] ?>";
        var nit = "<?php echo $post['nit'] ?>";

        var obser = $('#obser').val();
        if (obser == "") {
            alert('Datos Incompletos');
            return false;
        }
        obser = "////" + obser + '///' + '<?php echo date("d/m/y") ?>';
        jQuery(".preload, .load").show();
        $.post(url, {num_auto: num_auto, obser: obser, nit: nit, cod_fis: cod_fis})
                .done(function(msg) {
                    alert('Los Datos Fueron Guardados Con Exito');
                    window.location.reload();
//jQuery(".preload, .load").hide();
                }).fail(function() {
            jQuery(".preload, .load").hide();
            alert("ERROR AL GUARDAR");
        });
    })
    function aprobar_doc () {
        var url = "<?php echo base_url('index.php/autopruebas/aprobar_documento_auto') ?>";
        var num_auto = "<?php echo $post['num_auto'] ?>";
        var cod_fis = "<?php echo $post['cod_fis'] ?>";
        var nit = "<?php echo $post['nit'] ?>";
        var cod_respu=1324;
        var informacion = tinymce.get('informacion').getContent();
        jQuery(".preload, .load").show();
        $.post(url, {num_auto: num_auto,cod_respu:cod_respu,cod_fis:cod_fis,nit:nit,informacion:informacion})
                .done(function(msg) {
                    alert('Los Datos Fueron Guardados Con Exito');
                    window.location.reload();
//jQuery(".preload, .load").hide();
                }).fail(function() {
            jQuery(".preload, .load").hide();
            alert("ERROR AL GUARDAR");
        });
    }

    function devolver() {
        $('#primer').hide();
        $('#observa').show();
    }
    function atras() {
        $('#primer').show();
        $('#observa').hide();
    }

    $('#resultado').dialog({
        autoOpen: true,
        modal: true,
        width: 900,
        title: "Auto de Cargos"
    });

    function enviar_pdf() {
        var informacion = tinymce.get('informacion').getContent();
            var ecuenta   = $('#ecuenta').val();
            var nit    = $('#nit').val();
            var logo   = $('#logo').val();
            var cfisc  = $('#cfisc').val();
            var nombre = "Auto"+ecuenta+"_"+nit;
            document.getElementById("nombre").value = nombre;
            document.getElementById("descripcion_pdf").value = informacion;
            $("#form").submit();
    }

    tinymce.init({
        language: 'es',
        selector: "textarea#informacion",
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


    $(".preload, .load").hide();
</script>