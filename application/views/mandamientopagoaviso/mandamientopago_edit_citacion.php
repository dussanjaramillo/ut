<script type="text/javascript">
    tinymce.init({
        language: "es",
        selector: "textarea#notificacion",
        theme: "modern",
        plugins: [
            "advlist autolink lists link image charmap print preview hr anchor pagebreak",
            "searchreplace visualblocks wordcount visualchars code fullscreen",
            "insertdatetime media nonbreaking save table contextmenu directionality",
                    //"emoticons template paste textcolor moxiemanager"
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
</script>
<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!-- <div class="center-form"> -->
<?php
$data = array();
$data = $result;
 echo $estado = $data->COD_ESTADO;
$attributes = array('id' => 'citacionFrm', 'name' => 'citacionFrm', 'class' => 'form-inline', 'method' => 'POST', 'enctype' => 'multipart/form-data',);
echo form_open_multipart(current_url(), $attributes);
echo "<center>" . $titulo . "</center>";
@$rutaInac = base_url() . $inactivo->DOC_FIRMADO . $inactivo->NOMBRE_DOC_CARGADO;
?>
<?php echo $custom_error; ?>        
<div class="center-form-large-20">
    <br>
    <br>
    <table cellspacing="0" cellspading="0" border="0" align="center">
        <tr>
            <td>
                <p>
                    <input type="hidden" name="vRuta" id="vRuta">
                    <input type="hidden" name="vUbicacion" id="vUbicacion">
                    <input type="hidden" name="nit" id="nit" value="<?php echo $informacion[0]['CODEMPRESA']; ?>">
                    <input type="hidden" name="cod_coactivo" id="cod_coactivo" value="<?php echo $cod_coactivo ?>">
                    <input type="hidden" name="id_estado" id="id_estado" value="<?php echo $data->COD_ESTADO; ?>">
                    <input type="hidden" name="mandamiento" id="mandamiento" value="">
                    <input type='hidden' value='<?php echo $permiso[0]['IDGRUPO'] ?>' name='perfil' id='perfil'>
                    <input type='hidden' value='<?php echo $user->IDUSUARIO ?>' name='iduser' id='iduser'>
                    <input type="hidden" name="tipo" id="tipo" value="<?php echo $tipo ?>">
            </td>
        </tr>
        <tr>
            <?php
            $this->load->view('mandamientopago/cabecera', $this->data);
            ?>
        </tr>
    </table>
    <br>
    <br>
    <table cellspacing="0" cellspading="0" border="0" align="center">
        <input type="hidden" id="clave" name="clave" value="<?php echo @$data->COD_MANDAMIENTOPAGO; ?>">
        <input type="hidden" id="notifica" name="notifica" value="<?php echo @$data->COD_AVISONOTIFICACION; ?>">
        <input type="hidden" id="codigo" name="codigo" value="">
        <?php
        if (count($inactivo) > 0) {
            echo "<tr>
                        <td align='right' colspan='2'>
                            <a href='$rutaInac' class='btn-success' target=_blank>
                                Citaci&oacute;n Anterior
                            </a>
                        </td>
                    </tr>";
        }
        ?> 
        <tr>
            <td colspan="2">
                <p>
                    <?php
                    $datanotificacion = array(
                        'name' => 'notificacion',
                        'id' => 'notificacion',
                        'value' => @$plantilla,
                        'width' => '80%',
                        'heigth' => '50%'
                    );
                    echo form_textarea($datanotificacion);
                    echo form_error('notificacion', '<div>', '</div>');
                    ?>
                </p>
            </td>
        </tr>
        <tr id="selectAsigna" style="display:block">
            <td>
                <p>
                <div id="revised" style="display:block" align='left'>
                    <?php
                    if ($data->COD_ESTADO == 2) {
                        $checked1 = TRUE;
                        $checked2 = FALSE;
                    }
                    if ($data->COD_ESTADO == 3) {
                        $checked1 = FALSE;
                        $checked2 = TRUE;
                    }
                    if ($data->COD_ESTADO != 3 && $data->COD_ESTADO != 2) {
                        $checked2 = FALSE;
                        $checked1 = FALSE;
                    }
                    echo "<br>";
                    echo form_label('Revisado', 'revisado') . "<br>";
                    $datarevisado = array(
                        'name' => 'revisado',
                        'id' => 'revisado',
                        'checked' => FALSE,
                        'style' => 'margin:10px;'
                    );
                    if($estado==1):
                         
                          echo form_radio($datarevisado, "APROBADO", $checked1) . "&nbsp;PRE-APROBADO<br>";
                          
                    else:
                          echo form_radio($datarevisado, "APROBADO", $checked1) . "&nbsp;&nbsp;APROBADO<br>";
                    endif;
                   
                    echo form_radio($datarevisado, "DEVOLVER", $checked2) . "&nbsp;&nbsp;DEVOLVER<br><br><br>";

                    echo form_error('revisado', '<div>', '</div>');
                    ?>    
                </div>
                </p>
            </td>
        </tr>
        <tr id="radicOnbase" style="display:none" align='center'>
            <td>
                <?php
                if ($data->NUM_RADICADO_ONBASE == '0') {
                    $onbase = set_value('onbase');
                } else {
                    $onbase = $data->NUM_RADICADO_ONBASE;
                }
                echo form_label('Numero de radicado Onbase', 'onbase');
                $dataonbase = array(
                    'name' => 'onbase',
                    'id' => 'onbase',
                    'value' => $onbase,
                    'maxlength' => '10',
                    'type' => 'number',
                );
                echo "<br>" . form_input($dataonbase);
                echo form_error('onbase', '<div>', '</div>');
                ?>
            </td>
            <td>
                <div>    
                    <?php
                    echo form_label('Fecha notificaci&oacute;n', 'fechanotificacion');
                    echo "<br>";
                    $dataFecha = array(
                        'name' => 'fechanotificacion',
                        'id' => 'fechanotificacion',
                        'value' => $data->FECHA_NOTIFICACION,
                        'maxlength' => '12',
                        'size' => '15'
                    );

                    echo form_input($dataFecha) . "&nbsp;";

                    echo form_hidden('fechanotificaciond', date("d/m/Y"));
                    ?>
                </div>
            </td>
        </tr>
        <tr id="numColilla" style="display:none" align='center'>
            <td>
<?php
echo form_label('Fecha Onbase', 'fechaonbase');
$dataFechaOn = array(
    'name' => 'fechaonbase',
    'id' => 'fechaonbase',
    'value' => $data->FECHA_ONBASE,
    'maxlength' => '12',
    'size' => '15'
);

echo "<br>" . form_input($dataFechaOn) . "&nbsp;";
echo form_error('fechaonbase', '<div>', '</div>');
echo form_hidden('fechaonbased', date("d/m/Y"));
?>
            </td>
            <td>
                <div>
<?php
echo form_label('N&uacute;mero de colilla correo', 'colilla');
$datacolilla = array(
    'name' => 'colilla',
    'id' => 'colilla',
    'value' => '',
    'maxlength' => '10',
);
echo "<br>" . form_input($datacolilla);
?>
                </div>
            </td>
        </tr>
        <tr>
            <td id="Devol" style="display:none" align='left'>
<?php
echo "<br>" . form_label('Devolucion', 'devolucion') . "<br>";
$datadevolucion = array(
    'name' => 'devolucion',
    'id' => 'devolucion',
    'checked' => FALSE,
    'style' => 'margin:10px'
);
echo "&nbsp;" . form_radio($datadevolucion, "1") . "&nbsp;&nbsp;SI<br>";
echo "&nbsp;" . form_radio($datadevolucion, "0") . "&nbsp;&nbsp;NO";
?>
            </td>
            <td id="motDevol" style="display:none" align='center'>
                <div>
                <?php
                echo form_label('Motivo devoluci&oacute;n&nbsp;&nbsp;', 'motivo');
                $selecte[''] = "-- Seleccione --";
                foreach ($motivos as $row) {
                    $selecte[$row->COD_MOTIVO_DEVOLUCION] = $row->MOTIVO_DEVOLUCION;
                }

                echo "<br>" . form_dropdown('motivo', $selecte, $data->COD_MOTIVODEVOLUCION, 'id="motivo" class="chosen" data-placeholder="seleccione..." ');

                echo form_error('motivo', '<div>', '</div>');
                ?>
                </div>
            </td>
        </tr>       
        <tr id="cargColilla" style="display:none">
            <td align="left">
                <p>
<?php
echo form_label('Cargar colilla', 'filecolilla');
$datafile = array(
    'name' => 'filecolilla',
    'id' => 'filecolilla',
    'value' => '',
    'maxlength' => '10',
);
echo "<br>" . form_upload($datafile);
?>
                </p>
            </td>

        </tr>           
        <tr id="cargDocumento" style="display:none">
            <td align="left">
                <p>
<?php
echo form_label('Cargar Documento', 'doccolilla');
$datadoc = array(
    'name' => 'doccolilla',
    'id' => 'doccolilla',
    'value' => '',
    'maxlength' => '10',
);
echo "<br>" . form_upload($datadoc);
?>
                </p>
            </td>
            <td align="left">
                <p>
                <div class="span3">
<?php
$results = array();
$cRuta = $rutaDocumento;
//echo $cRuta."<br>";
@$handler = opendir($cRuta);
if ($handler) {
    while ($file = readdir($handler)) {
        if ($file != '.' && $file != '..')
            $results[] = $file;
    }
    closedir($handler);
}
if (count($results) > 0) {
    ?>
                        <div align="left">
                        <?php
                        $cCadena = "";
                        //echo "Lista de Adjuntos<br>";                                                
                        for ($x = 0; $x < count($results); $x++) {
                            if ($results[$x] == $data->NOMBRE_DOC_CARGADO . ".pdf") {
                                $cCadena = "&nbsp;";
                                $cCadena .= ($x + 1) . ". <a href='../../" . $cRuta . "{$results[$x]}' target=_blank>&nbsp;Documento"; // $results[$x]
                                $cCadena .= "</a><br>";
                                echo $cCadena;
                            }
                        }
                        echo "<br>";
                        ?>
                        </div>
                        <?php } ?>
                </div>
                </p>
            </td>
        </tr>   
        <tr id="idComu" style="display:none">
            <td colspan="2">
                <p>
<?php
echo form_label('C&oacute;digo comunicaci&oacute;n', 'comunicacion');
$datacomunica = array(
    'name' => 'comunicacion',
    'id' => 'comunicacion',
    'value' => $data->COD_COMUNICACION,
    'maxlength' => '50'
);
echo "<br>" . form_input($datacomunica);
echo form_error('comunicacion', '<div>', '</div>');
?>
                </p>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <p>
<?php
echo form_label('Observaci&oacute;n&nbsp;&nbsp;<span class="required"><font color="red"><b>*</b></font></span>', 'observacion');
echo "<br>";
$data = array(
    'name' => 'observacion',
    'id' => 'observacion',
    'value' => $data->OBSERVACIONES,
    'width' => '100%',
    'heigth' => '5%',
    'style' => 'width:98%'
);

echo "&nbsp;&nbsp;" . form_textarea($data);
echo form_error('observacion', '<div><b><font color="red">', '</font></b></div>');
?>
                </p>
            </td>
        </tr>
        <tr>
            <td align="center" colspan="2">
                <p>
                <div style="display:none" id="error" class="alert alert-danger"></div>
                </p>
                <p>
<?php
$dataguardar = array(
    'name' => 'button',
    'id' => 'guardar',
    'value' => 'Guardar',
    'type' => 'button',
    'content' => '<i class="fa fa-floppy-o fa-lg"></i> Guardar',
    'class' => 'btn btn-success'
);
echo form_button($dataguardar) . "&nbsp;&nbsp;";
$dataPDF = array(
    'name' => 'button',
    'id' => 'pdf',
    'value' => 'Generar PDF',
    'type' => 'button',
    'content' => '<i class="fa fa-file"></i> Generar PDF',
    'class' => 'btn btn-info'
);
echo form_button($dataPDF) . "&nbsp;&nbsp;";
$datacancel = array(
    'name' => 'button',
    'id' => 'cancel-button',
    'value' => 'Cancelar',
    'type' => 'button',
    'onclick' => 'window.location=\'' . base_url() . 'index.php/mandamientopago/nits\';',
    'content' => '<i class="fa fa-undo"></i> Cancelar',
    'class' => 'btn btn-warning'
);
echo form_button($datacancel);
?>
                </p>
            </td>
        </tr>
    </table>
    <input type="hidden" name="tipo_documento" id="tipo_documento" value="3" >
    <input type="hidden" name="titulo_doc" id="titulo_doc" >
<?php echo form_close(); ?>

</div>

<div id="ajax_load" class="ajax_load" style="display: none">
    <div class="preload" id="preload" >
        <img  id="load" class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $("#fechanotificacion").datepicker({
            dateFormat: "yy/mm/dd",
            changeMonth: true,
            maxDate: "0",
            changeYear: true,
        });
    });
    $(document).ready(function() {
        window.history.forward(-1);
        var fechanot = $('#fechanotificacion').val();

        $('#filecolilla').change(function() {
            var extensiones_permitidas = new Array(".pdf");
            var archivo = $('#filecolilla').val();
            var extension = (archivo.substring(archivo.lastIndexOf("."))).toLowerCase();
            var permitida = false;

            for (var i = 0; i < extensiones_permitidas.length; i++) {
                if (extensiones_permitidas[i] == extension) {
                    permitida = true;
                    break;
                }
            }
            if (!permitida) {
                $('#filecolilla').val('');
                $('#error').show();
                $('#error').html("<b>Comprueba la extensión de los archivos a subir. \nSólo se pueden subir archivos con extensiones: " + extensiones_permitidas.join() + "</b>");
                $("#filecolilla").focus();
            } else {
                $('#error').show();
                $('#error').html("<b>Archivo Subido Correctamente</b>");
                return 1;
            }
        });

        $('#doccolilla').change(function() {
            var extensiones_permitidas = new Array(".pdf");
            var archivo = $('#doccolilla').val();
            var extension = (archivo.substring(archivo.lastIndexOf("."))).toLowerCase();
            var permitida = false;

            for (var i = 0; i < extensiones_permitidas.length; i++) {
                if (extensiones_permitidas[i] == extension) {
                    permitida = true;
                    break;
                }
            }
            if (!permitida) {
                $('#doccolilla').val('');
                $('#error').show();
                $('#error').html("<b>Comprueba la extensión de los archivos a subir. \nSólo se pueden subir archivos con extensiones: " + extensiones_permitidas.join() + "</b>");
                $("#doccolilla").focus();
            } else {
                $('#error').show();
                $('#error').html("<b>Archivo Subido Correctamente</b>");
                return 1;
            }
        });
        $('#fechaonbase').datepicker({
            dateFormat: "dd/mm/y",
            maxDate: "0"
        });
        //var estado = $('#id_estado').val();
        var estado = "<?= $estado ?>";
        var perfil = $("#perfil").val();
        var user = $("#iduser").val();
        var tipo = $("#tipo").val();

        switch (estado) {

            case '1':
                $('#revised').show();
                $('#selectAsigna').show();
                break;
            case '3':
                $('#revised').hide();
                $('#selectAsigna').hide();
                break;
            case '2':
                if (tipo == 'acta' || tipo == 'actaexc' || tipo == 'actaRec' || tipo == 'actaresosa') {
                    $('#cargDocumento').show();
                    $('#revised').hide();
                } else {
                    $('#radicOnbase').show();
                    $('#revised').hide();
                }
                break;
            case '5':
                $('#radicOnbase').show();
                $('#numColilla').show();
                $('#cargColilla').show();
                $('#cargDocumento').show();
                $('#idComu').show();
                $('#Devol').show();
                $('#revised').hide();
                break;
        }

        $('input[name="devolucion"]').change(function() {
            if ($('input[name="devolucion"]:checked').val() == '0') {
                $('#motDevol').hide('slow');
                $('#motivo').val('');
            } else {
                ($('input[name="devolucion"]:checked').val() == '1')
                $('#motDevol').show();
            }
        });

        $('#observacion').keyup(function() {
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
            var comentarios = string.replace(/[^a-zA-Z 0-9.]+/g, ' ');
            $('#observacion').val(comentarios);
        }

        $('#guardar').click(function() {
            $('#error').html("");
            tinyMCE.triggerSave();
            var clave = $('#clave').val();
            $('#codigo').val(clave);
            var estado = $('#id_estado').val();
            var onbase = $('#onbase').val();
            var observ = $('#observacion').val();
            var fechaonb = $('#fechaonbase').val();
            var devolucion = $('input[name="devolucion"]:checked').val();
            var motivo = $('#motivo').val();
            var doccolilla = $("#doccolilla").val();
            var filecolilla = $("#filecolilla").val();
            var revisado = $("input[name='revisado']:checked").val();
            char_especiales(observ);
            var valida = true;
            var tipo = $('#tipo').val();
            var url = '<?php echo base_url('index.php/mandamientopago/') ?>';
            if (tipo == 'citacion') {
                url = url + '/editCit';
            } else if (tipo == 'citacionex') {
                url = url + '/editCitex';
            } else if (tipo == 'correo') {
                url = url + '/editCor';
            } else if (tipo == 'correoexc') {
                url = url + '/editCorreoex';
            } else if (tipo == 'citacionrec') {
                url = url + '/editCitrec';
            } else if (tipo == 'acta') {
                url = url + '/editActa';
            } else if (tipo == 'actaexc') {
                url = url + '/editActaExc';
            } else if (tipo == 'actaRec') {
                url = url + '/editActaRec';
            } else if (tipo == 'resosa') {
                url = url + '/editCitResosa';
            } else if (tipo == 'correoresosa') {
                url = url + '/editCorResosa';
            } else if (tipo == 'actaresosa') {
                url = url + '/editActaResosa';
            }
            //validaciones

            if (observ == "") {
                $('#error').show();
                $('#error').html("<b>Ingrese Comentarios</b>");
                valida = false;
            }
            switch (estado) {
                default :
                    if (revisado == undefined && valida == true) {
                        $('#error').show();
                        $('#error').html("<b>Por favor validar Revisión</b>");
                        valida = false;
                        break;
                    } else {
                        if (revisado == 'APROBADO' && valida == true) {
                            $('#id_estado').val('2');
                        } else if (revisado == 'DEVOLVER' && valida == true) {
                            $('#id_estado').val('3');
                        }
                    }
                    break;
                case '2':
                    if (tipo == 'acta' || tipo == 'actaexc' || tipo == 'actaRec' || tipo == 'actaresosa') {
                        if (observ == "") {
                            $('#error').show();
                            $('#error').html("<b>Ingrese Comentarios</b>");
                            valida = false;
                            break;
                        } else if (doccolilla == '') {
                            $('#error').show();
                            $('#error').html("<b>Por Favor adjuntar el Documento</b>");
                            $("#doccolilla").focus();
                            valida = false;
                            break;
                        }
                        if (valida == true) {
                            $('#id_estado').val('6');
                        }
                    } else {
                        if (onbase == '') {
                            $('#error').show();
                            $('#error').html("<b>Ingrese N&uacute;mero Onbase</b>");
                            $("#onbase").focus();
                            valida = false;
                            break;
                        }
                        if (valida == true) {
                            $('#id_estado').val('5');
                        }
                    }

                    break;
                case '3':
                    if (observ == "") {
                        $('#error').show();
                        $('#error').html("<b>Ingrese Comentarios</b>");
                        valida = false;
                        break;
                    }
                    if (valida == true) {
                        $('#id_estado').val('1');
                    }
                    break;
                case '5':
                    if (tipo == 'acta' || tipo == 'actaexc' || tipo == 'actaRec') {
                        if (observ == "") {
                            $('#error').show();
                            $('#error').html("<b>Ingrese Comentarios</b>");
                            valida = false;
                            break;
                        } else if (doccolilla == '') {
                            $('#error').show();
                            $('#error').html("<b>Por Favor adjuntar el Documento</b>");
                            $("#doccolilla").focus();
                            valida = false;
                            break;
                        }
                    } else {
                        if (observ == "") {
                            $('#error').show();
                            $('#error').html("<b>Ingrese Comentarios</b>");
                            valida = false;
                            break;
                        } else if (doccolilla == '') {
                            $('#error').show();
                            $('#error').html("<b>Por Favor adjuntar el Documento</b>");
                            $("#doccolilla").focus();
                            valida = false;
                            break;
                        } else if (fechaonb == '') {
                            $('#error').show();
                            $('#error').html("<b>Ingrese Fecha onbase</b>");
                            $("#fechaonbase").focus();
                            valida = false;
                            break;
                        } else if (devolucion == undefined) {
                            $('#error').show();
                            $('#error').html("<b>Seleccione el campo Devolución</b>");
                            $("#devolucion").focus();
                            valida = false;
                            break;
                        } else if (devolucion == 1 && motivo == '') {
                            $('#error').show();
                            $('#error').html("<b>Seleccione motivo de Devolución</b>");
                            $("#motivo").focus();
                            valida = false;
                            break;
                        }
                    }
                    if (valida == true && motivo != '') {
                        $('#id_estado').val('7');
                    } else {
                        $('#id_estado').val('6');
                    }
                    break;
            }

            if (valida == true) {
                $(".ajax_load").show("slow");
                $('#guardar').prop("disabled", true);
                $("#citacionFrm").attr("action", url);
                $('#citacionFrm').removeAttr('target');
                $('#citacionFrm').submit();
                //alert ('ok');
            }
        });
    });

    $('#pdf').click(function() {
        tinyMCE.triggerSave();
        var notify = $('#notificacion').val();
        $('#mandamiento').val(notify);
        $("#citacionFrm").attr("action", "<?= base_url('index.php/mandamientopago/pdf') ?>");
        $('#citacionFrm').attr('target', '_blank');
        $('#citacionFrm').submit();
    });


</script>