<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<center><h1><?php echo $titulo; ?></h1></center>
<br>
<p>
<form id="form1" action="<?php echo base_url('index.php/reporteador/imprimir_certificacion') ?>" method="post" onsubmit="return confirmar()">
    <div style="border: 1px solid #fff;width:800px;margin: 0 auto;padding: 20px;">
        <table width="100%">
            <tr>
                <td>Tipo de Certificaci&oacute;n</td>
                <td>
                    <select name="tipo_certificacion" id="tipo_certificacion">
                        <option value="1">Certificación Estado de Cuenta a la fecha</option>
                        <option value="2">Certificación Estado de Cuenta Anual</option>
                        <option value="3">Certificación Deuda Saldada</option>
                        <option value="4">Certificación Deuda Saldada para liberación</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Identificaci&oacute;n</td>
                <td >
                    <input type="text" id="empleado" name="empleado" value="<?php echo isset($_POST['empleado']) ? $_POST['empleado'] : '' ?>">
                    <img id="preloadmini9" src="<?php echo base_url('img/319.gif') ?>" width="28" height="28" />
                    <button id="buscar" class="btn btn-success" onclick="generar(2)">Buscar</button>
                </td>
            </tr>
            <tr>
                <td>Tipo de Cartera</td>
                <td >
                    <select id="concepto2" name="concepto2">
                        <option value=""></option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Aclaraciones</td>
                <td>
                    <div  id="dialogos"><textarea name="aclaraciones" id="aclaraciones"></textarea></div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
            <center>
                <input type="hidden"  id="envio" name="envio" value="0">
                <input type="hidden"  id="accion" name="accion" value="<?php echo $input; ?>">
                <button class="btn btn-success" onclick="generar(1)">Generar</button>
            </center>
            </td>
            </tr>
        </table>
    </div>
</p>
</form>
<script>
    tinymce.init({
        language: 'es',
        selector: "textarea",
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
    function generar(dato) {
        $('#envio').val(dato);
    }

    function confirmar() {
        var envio = $('#envio').val();
        var empleado = $('#empleado').val();
        var concepto2 = $('#concepto2').val();
        if (envio == 2) {
            return false
        }
        if (empleado == "") {
            alert('Campo Identificacion Obligatorio');
            return false;
        }
        if (concepto2 == "") {
            alert('Campo Tipo Cartera Obligatorio');
            return false;
        }
        return true;
    }
    $('#empleado').validCampoFranz(' abcdefghijklmnñopqrstuvwxyzáéiou0123456789');
    $("#empleado").autocomplete({
        source: "<?php echo base_url("index.php/reporteador/autocompleteempleado") ?>",
        minLength: 3,
        search: function(event, ui) {
            $("#preloadmini9").show();
        },
        response: function(event, ui) {
            $("#preloadmini9").hide();
        }
    });
    $("#buscar").click(function() {
        var idusuario = $('#empleado').val();
        if (idusuario == "") {
            alert('Campo Identificación Vacío');
            $('#concepto2').html('');
            $('#id_deuda').html('');
            return false;
        }
        jQuery(".preload, .load").show();
        var url = "<?php echo base_url("index.php/reporteador/burcar_no_misional") ?>";
        $.post(url, {idusuario: idusuario})
                .done(function(msg) {
                    console.log(msg);
                    $('#concepto2').html(msg);
                    $('#id_deuda').html('');
                    jQuery(".preload, .load").hide();
                }).fail(function(msg) {
            alert('Datos no Encontrados');
            jQuery(".preload, .load").hide();
        })
        return false;
    });
function ajaxValidationCallback(){
    
}
    

    $("#preloadmini9").hide();
    jQuery(".preload, .load").hide();
</script>