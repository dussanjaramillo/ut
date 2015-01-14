<?php
echo "dddddddddddddddd";
$datanotificacion = array('name' => 'notificacion', 'id' => 'notificacion', 'value' => @$plantilla, 'width' => '80%', 'heigth' => '50%');
$comentarios = array('name' => 'comentarios', 'id' => 'comentarios', 'value' => set_value('comentarios'), 'width' => '100%', 'heigth' => '5%', 'style' => 'width:98%');
$datafile = array('name' => 'file', 'id' => 'file', 'value' => '', 'maxlength' => '10', 'type' => 'file');
$dataonbase = array('name' => 'onbase', 'id' => 'onbase', 'value' => NULL, 'maxlength' => '10');
$dataFecha = array('name' => 'fechanotificacion', 'id' => 'fechanotificacion', 'value' => NULL, 'maxlength' => '12', 'readonly' => 'readonly', 'size' => '15');
$dataguardar = array('name' => 'guardar', 'id' => 'guardar', 'value' => 'Guardar', 'type' => 'button', 'content' => '<i class="fa fa-floppy-o fa-lg"></i> Guardar', 'class' => 'btn btn-success');
$dataadicionales = array('name' => 'adicionales', 'id' => 'adicionales', 'style' => 'margin:10px');
$datacancel = array('name' => 'button', 'id' => 'cancel-button', 'value' => 'Cancelar', 'type' => 'button', 'onclick' => 'window.location=\'' . base_url() . 'index.php/bandejaunificada/procesos\';', 'content' => '<i class="fa fa-undo"></i> Cancelar', 'class' => 'btn btn-warning');
$dataPDF = array('name' => 'button', 'id' => 'pdf', 'value' => 'Generar PDF', 'type' => 'button', 'content' => '<i class="fa fa-file"></i> Generar PDF', 'class' => 'btn btn-info');
$attributes = array('id' => 'frmtp', 'name' => 'frmtp', 'method' => 'POST');

echo form_open_multipart("acuerdodepagojuridico/guardarResolucion", $attributes);
?>
<input id="informacion" name='informacion' type='hidden'>
<input id="cod_coactivo" name='cod_coactivo' type='hidden' value="<?= $cod_coactivo ?>">
<input id="estado" name='estado' type='hidden' value="<?= $estado ?>">
<input id="nit" name='nit' type='hidden' value="<?= $nit ?>">
<input id="acuerdo" name='acuerdo' type='hidden' value="<?= $acuerdo ?>">
<center>
    <table>
        <tr>
            <td>
                <table width="100%" border="0" align="center">
                    <tr>
                        <td width="23%" rowspan="2"><img src="<?= base_url('img/Logotipo_SENA.png') ?>" width="100" height="100" /></td>
                        <td width="44%" height="94"><div align="center"><h4><b><?= $titulo ?></b></h4></div></td>
                    </tr>
                    <tr>
                        <td height="50" colspan="2"><div align="center">
                                <h2><input readonly value="<?= $proceso['TITULO_ENCABEZADO'] ?>" name="Titulo_Encabezado" type="text" id="Titulo_Encabezado" class="input-xxlarge" size="50" /></h2>
                            </div></td>
                    </tr>
                </table>   
            </td>
        </tr>
        <tr>
            <td>
                <?= form_textarea($datanotificacion) ?>
            </td>
        </tr>
        <tr><td><br><input type="button" id="ver_comentarios" value='Ver Comentarios' class='btn btn-info'><br></td></tr>    
        <tr>
            <td>                            
                <div class="Comentarios" style="width: 84%; border-color: black; border: 1px solid grey; margin: auto; overflow: hidden;alignment-adjust: central ;padding: 15px 50px 0">
                    <?php
                    echo form_label('<b><h2>Historial de Comentarios</h2></b><span class="required"></span>', 'lb_comentarios');
                    echo "<br>";
                    for ($i = 0; $i < sizeof($comentario); $i++) {
                        echo '<div class="alert-success" style="border-color: black; border: 1px solid grey;padding: 15px 50px 0">';
                        echo '<b>Fecha del Comentario: </b>' . $comentario[$i]["CREACION_FECHA"] . '<br>';
                        echo '<b>Hecho Por: </b>' . $comentario[$i]["NOMBRES"] . " " . $comentario[$i]["APELLIDOS"] . '<br>';
                        echo $comentario[$i]["COMENTARIO"];
                        echo "<br><br>";
                        echo '</div>';
                        echo "<br>";
                    }
                    ?>

                </div>
            </td>
        </tr>
        <tr><td><br></td></tr>
        <tr id='seleccion'>
            <td>
                <div class="alert-success" style="border-color: black; border: 1px solid grey;padding: 15px 50px 0">

                    <?php
                    switch ($estado) {
                        case 1390:
                            echo form_label('<div id="revision"></div>', 'revisado');
                            echo "Pre - Aprobado" . form_radio($dataadicionales, "1") . "&nbsp;&nbsp;";
                            echo "No Pre - Aprobado" . form_radio($dataadicionales, "0") . "&nbsp;";
                            break;
                        default:
                            echo form_label('<div id="revision"></div>', 'revisado');
                            echo "Aprobado" . form_radio($dataadicionales, "1") . "&nbsp;&nbsp;";
                            echo "No Aprobado" . form_radio($dataadicionales, "0") . "&nbsp;";
                            break;
                    }
                    ?>
                </div>
            </td>
        </tr>
        <tr id="radicOnbase" style="display:none">
            <td>
                <?php
                echo form_label('Numero de radicado Onbase', 'onbase');
                echo form_input($dataonbase);
                ?>
            </td>
            <td>
                <?php
                echo form_label('Fecha notificaci&oacute;n', 'fechanotificacion');
                echo form_input($dataFecha) . "&nbsp;";
                ?>
                </div>
            </td>
        </tr>
        <tr id="cargColilla" style="display:none">
            <td align="left">
                <?= form_upload($datafile); ?>
            </td>
        </tr>
        <tr><td><br></td></tr>
        <tr>
            <td>
                <?php
                echo form_label('<b>Comentarios</b>&nbsp;&nbsp;<span class="required"><font color="red"><b>*</b></font></span>', 'comentarios');
                echo form_textarea($comentarios)
                ?>
            </td>
        </tr>
        <tr>
            <td align='center'>
                <?php
                echo form_button($dataguardar) . "&nbsp;";
                echo form_button($dataPDF) . "&nbsp;";
                echo form_button($datacancel);
                ?>
            </td>
        </tr>
        <tr>
            <td colspan='2' align='center'>
                <br><br>
                <div style="display:none" id="error" class="alert alert-danger"></div>
            </td>
        </tr>    
    </table>
    <div id="ajax_load" class="ajax_load" style="display: none">
        <div class="preload" id="preload" >
            <img  id="load" class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
        </div>
    </div>
</center>
<?php echo form_close(); ?>
<script type="text/javascript">
    var estado = <?= $estado ?>;
    var readonly = 0;
    if (estado == 1390 || estado == 1391 || estado == 1392 || estado == 1393) {
        readonly = 1;
    } else {
        readonly = 0;
    }
    tinymce.init({
        language: "es",
        selector: "textarea#notificacion",
        theme: "modern",
        readonly: 1,
        plugins: [
            "advlist autolink lists link image charmap print preview hr anchor pagebreak",
            "searchreplace visualblocks wordcount visualchars code fullscreen",
            "insertdatetime media nonbreaking save table contextmenu directionality",
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

    $(document).ready(function() {
        var estado = <?= $estado ?>;
        switch (estado) {
            case 1393:
                $("#seleccion").hide();
                $("#cargColilla").show();
                $('#file').change(function() {
                    var extensiones_permitidas = new Array(".pdf");
                    var archivo = $('#file').val();
                    var extension = (archivo.substring(archivo.lastIndexOf("."))).toLowerCase();
                    var permitida = false;

                    for (var i = 0; i < extensiones_permitidas.length; i++) {
                        if (extensiones_permitidas[i] == extension) {
                            permitida = true;
                            break;
                        }
                    }
                    if (!permitida) {
                        $('#file').val('');
                        $('#error').show();
                        $('#error').html("<font color='red'><b>Comprueba la extensión de los archivos a subir. \nSólo se pueden subir archivos con extensiones: " + extensiones_permitidas.join() + "</b></font>");
                        $("#file").focus();
                    } else {
                        $('#error').html("<font color='red'><b>Archivo Subido Correctamente</b></font>");
                        return 1;
                    }
                });
                break;
            case 1392:
                $("#seleccion").hide();
                $("#cargColilla").hide();
                break;
            case 1391:
                $("#revision").html('<b>Revisión por parte del Ejecutor</b>&nbsp;&nbsp;<span class="required"></span>');
                break;
            case 1390:
                $("#revision").html('<b>Revisión por parte del Secretario</b>&nbsp;&nbsp;<span class="required"></span>');
                break;
        }

    })

    $(".Comentarios").hide();
    $('#ver_comentarios').click(function() {
        $(".Comentarios").show();
    });

    $('#comentarios').keyup(function() {
        var max_chars = 200;
        var chars = $(this).val().length;
        var diff = max_chars - chars;
        if (diff <= 0) {
            $('#error').show();
            $('#error').html('Excedio el limite de carácteres en el campo comentarios');
            $('#guardar').attr('disabled', true);
        } else {
            $('#guardar').attr('disabled', false);
            $('#error').hide();
        }
    });

    function char_especiales(string) {
        var comentarios = string.replace(/[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi, '');
        $('#comentarios').val(comentarios);
    }
    $('#guardar').click(function() {
        tinyMCE.triggerSave();
        var estado = <?= $estado ?>;
        var comentarios = $('#comentarios').val();
        var file = $('#file').val();
        var adicionales = $("input[name='adicionales']:checked").val();
        var valida = true;
        char_especiales(comentarios);
        switch (estado) {
            case 1390:
                if (comentarios == '') {
                    $('#error').show();
                    $('#error').html('Por favor Ingresar Comentarios');
                    $('#comentarios').focus();
                    valida = false;
                } else if (adicionales == undefined) {
                    $('#error').show();
                    $('#error').html('Por favor Seleccionar la opción correspondiente');
                    $('#adicionales').focus();
                    valida = false;
                }
                break;
            case 1391:
                if (comentarios == '') {
                    $('#error').show();
                    $('#error').html('Por favor Ingresar Comentarios');
                    $('#comentarios').focus();
                    valida = false;
                } else if (adicionales == undefined) {
                    $('#error').show();
                    $('#error').html('Por favor Seleccionar la opción correspondiente');
                    $('#adicionales').focus();
                    valida = false;
                }
                break;
            case 1393:
                if (file == '') {
                    $('#error').show();
                    $('#error').html('Por favor adjuntar algun archivo');
                    $('#adicionales').focus();
                    valida = false;
                } else if (comentarios == '') {
                    $('#error').show();
                    $('#error').html('Por favor Ingresar Comentarios');
                    $('#comentarios').focus();
                    valida = false;
                }
                break;
            case 1392:
                if (comentarios == '') {
                    $('#error').show();
                    $('#error').html('Por favor Ingresar Comentarios');
                    $('#comentarios').focus();
                    valida = false;
                }
                break;
        }

        if (valida == true) {
            var url = "<?= base_url('index.php/acuerdodepagojuridico/guardarResolucion') ?>";
            $(".ajax_load").show("slow");
            $('#guardar').attr('disabled', true);
            $.post(url, {adicionales: adicionales, acuerdo:<?= $acuerdo ?>, estado:<?= $estado ?>, cod_coactivo:<?= $cod_coactivo ?>, nit:<?= $nit ?>})
                    .done(function(data) {
                        $('.conn').html(data);
                        window.location = '<?= base_url('index.php/bandejaunificada') ?>';
                        $(".ajax_load").hide("slow");
                    }).fail(function() {
                //alert('Error en la consulta')
                $(".ajax_load").hide("slow");
            })
            //$('#frmtp').submit();
        }
    })

    $('#pdf').click(function() {
        tinyMCE.triggerSave();
        var url = "<?= base_url('index.php/acuerdodepagojuridico/pdf') ?>";
        var url2 = "<?= base_url('index.php/acuerdodepagojuridico/guardarResolucion') ?>";
        var notify = $('#notificacion').val();
        $('#informacion').val(notify);
        $('#frmtp').attr('target', '_blank');
        $('#frmtp').attr("action", url);
        $('#frmtp').submit();
        $('#frmtp').removeAttr('target');
        $('#frmtp').attr("action", url2);
    });

</script>