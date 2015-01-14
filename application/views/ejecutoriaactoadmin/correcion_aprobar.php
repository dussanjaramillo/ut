<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<div id="uno">
    <textarea id="informacion" name="informacion" style="height: 400px"><?php echo $texto; ?></textarea>
    <div align="center">
        <!--<button id="devolver">Devoluci&oacute;n</button>-->
        <button id="enviar" class="btn btn-success">Enviar</button>
    </div>
</div>

<div>
    <?php echo $comentario; ?>
</div>
<!--<div id="dos" style="display: none">
    Observaciones:<br>
    <textarea id="informacion" name="informacion" style="width: 100%"></textarea>
    <br>
    <button id="devolver2">Devoluci&oacute;n</button>
    <button id="atras">Atras</button>
    
</div>-->

<script>
    $('#resultado').dialog({
        autoOpen: true,
        modal: true,
        width: 700,
        height: 600
//    buttons:[{
//            id:'guarda'
//    }]
    });

    id = "<?php echo $post['id'] ?>";
    cod_fis = "<?php echo $post['cod_fis'] ?>";
    concepto = "<?php echo $post['concepto'] ?>";
    nit = "<?php echo $post['nit'] ?>";
    id_resolucion = "<?php echo $post['id_resolucion'] ?>";
    documento = "<?php echo $documento ?>";

    $('#devolver').click(function() {
        $('#uno').hide();
        $('#dos').show();
    });
    $('#atras').click(function() {
        $('#dos').hide();
        $('#uno').show();
    });


    $('#enviar').click(function() {
    $(".preload, .load").show();
        var url = "<?php echo base_url('index.php/ejecutoriaactoadmin/pre_aprobar'); ?>";
        var informacion = tinymce.get('informacion').getContent();
        $.post(url, {id: id, nit: nit, concepto: concepto, cod_fis: cod_fis, id_resolucion: id_resolucion, documento: documento,informacion:informacion})
                .done(function() {
                    alert('Los datos fueron guardados con exito');
                    window.location.reload();
                }).fail(function() {
            alert('ERROR EN LA BASE DE DATOS');
            $(".preload, .load").hide();
        });
    });



    $('#devolver2').click(function() {
        $(".preload, .load").show();
        var informacion = $('#informacion').val();
        var url = "<?php echo base_url('index.php/ejecutoriaactoadmin/devolucion'); ?>";
        $.post(url, {id: id, nit: nit, concepto: concepto, cod_fis: cod_fis, informacion: informacion, id_resolucion: id_resolucion})
                .done(function() {
                    alert('Los datos fueron guardados con exito');
                    window.location.reload();
                }).fail(function() {
            $(".preload, .load").hide();
            alert('ERROR EN LA BASE DE DATOS')
        });
    });

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