<div id="form1">
    <center>
        <table width="100%">
            <tr>
                <td colspan="2" align="center">Seleccione por favor la favorabilidad del recurso para el deudor</td>
            </tr>    
            <tr>
                <td align="center" width="50%">Favorable deudor</td>
                <td align="center" width="50%">Favorable sena</td>
            </tr>    
            <tr>
                <td align="center" width="50%"><input type="radio" name="favorable" value="100" onclick="favorable(this);" /></td>
                <td align="center" width="50%"><input type="radio" name="favorable" value="" onclick="favorable(this);" /></td>
            </tr>    
        </table>
    </center>
</div>
<div id="texto" style="display: none">
    <textarea id="informacion" style="width: 100%;height: 400px"></textarea>
    <center><button id="enviar4">Enviar</button></center>
</div>
<div id="citacion_notificacion" style="display: none;">
    <table width="100%">
        <tr>
            <td>Citaci&oacute;n Notificaci&oacute;n Personal</td>
            <td><input type="radio" id="radio1" class="radios2" name="radio" value="1" ></td>
        </tr>
        <?php if ($consulta[0]['AUTORIZA_NOTIFIC_EMAIL'] == "s" || $consulta[0]['AUTORIZA_NOTIFIC_EMAIL'] == "S") { ?>
            <tr>
                <td>Citaci&oacute;n Notificaci&oacute;n Correo Electr&oacute;nico</td>
                <td><input type="radio" id="radio2" class="radios2" name="radio" value="2" ></td>
            </tr>
        <?php } ?>
        <tr>
            <td>Citaci&oacute;n Notificaci&oacute;n Por Aviso</td>
            <td><input type="radio" id="radio3" class="radios2" name="radio" value="3" ></td>
        </tr>
    </table> 
</div>
<script>
    $('#resultado').dialog({
        autoOpen: true,
        width: 500,
        title: "Subir Archivo",
        height: 200,
        modal: true,
        close: function() {
            $('#resultado *').remove();
        }
    });
    $(".preload, .load").hide();
    function favorable(dato) {
        if (dato.value == "100")
            ok();
        else
            otro(dato.value);
    }
    function ok() {
        $('#form1').hide();
        $('#citacion_notificacion').show();
        $('#resultado').dialog({
            width: 350,
            height: 150,
        });
    }
    $('.radios2').click(function() {
        $('#form1').hide();
        $('#texto').show();
        $('#resultado').dialog({
            width: 850,
            height: 650,
        });
    });
    function otro(favorable) {
        var url = "<?php echo base_url('index.php/resolucion/devolver_a_citacion') ?>";
        var id = "<?php echo $post['id'] ?>";
        var num_recurso = "<?php echo $consulta[0]['NUM_RECURSO'] ?>";
        $.post(url, {id: id, favorable: favorable, num_recurso: num_recurso})
                .done(function(msg) {
                    $('#resul').html(msg);
                    alert("Los datos fueron guardados con exito");
                    window.location.reload();
                })
                .fail(function(xhr) {
                    alert("Los datos no fueron guardados");
                });
    }
    $('#enviar4').click(function() {
        var url = "<?php echo base_url('index.php/resolucion/guardar_citacion_favorabilidad') ?>";
        var id = "<?php echo $consulta[0]['COD_RESOLUCION'] ?>";
        var num_recurso = "<?php echo $consulta[0]['NUM_RECURSO'] ?>";
        var nit =<?php echo $consulta[0]['NITEMPRESA'] ?>;
        var nombre_archivo = "<?php echo date("d-m-Y H:i:s"); ?>";
        var nombre_archivo = nombre_archivo.replace(":", "_");
        var nombre_archivo = nombre_archivo.replace(":", "_");
        var nombre_archivo = nombre_archivo.replace(" ", "_");
        var nombre_archivo = nombre_archivo + nit;
        var informacion = tinymce.get('informacion').getContent();
        $.post(url, {id: id,num_recurso:num_recurso,nombre:nombre_archivo,infor:informacion})
                .done(function(msg) {
                    $('#resul').html(msg);
                    alert("Los datos fueron guardados con exito");
                    window.location.reload();
                })
                .fail(function(xhr) {
                    alert("Los datos no fueron guardados");
                });
    })
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
    function soloNumero(e) {
        var keynum = window.event ? window.event.keyCode : e.which;
        if ((keynum == 8) || (keynum == 46))
            return true;
        return false;
//        return /\d/.test(String.fromCharCode(keynum));
//return false;
    }
</script>