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
$attributes = array(
    'id' => 'actaFrm',
    'name' => 'actaFrm',
    'class' => 'form-inline',
    'method' => 'POST',
    'enctype' => 'multipart/form-data',
);
echo form_open_multipart(current_url(), $attributes);
echo form_open_multipart(base_url(), $attributes);
?>
<center>
    <h2>Verificaci&oacute;n existencia de Recurso</h2>
</center>
<div class="center-form-large-20">
    <br>
    <br>
    <table cellspacing="0" cellspading="0" border="0" align="center">
        <tr>
            <td>
                <p>
                    <input type="hidden" name="vRuta" id="vRuta">
                    <input type="hidden" name="vUbicacion" id="vUbicacion">
                    <input type="hidden" name="mandamiento" id="mandamiento">
                    <input type="hidden" name="idmandamiento" id="idmandamiento" value="<?php echo $post['clave']; ?>">
                    <input type="hidden" name="nit" id="nit" value="<?php echo $informacion[0]['CODEMPRESA']; ?>">
                    <input type="hidden" name="cod_coactivo" id="cod_coactivo" value="<?php echo $cod_coactivo ?>">
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
    <table cellspacing="0" cellspading="0" border="0" align="center" width="55%">
        <tr>
            <td align="left">
                <p>
                    <?php
                    $checked1 = TRUE;
                    echo form_label('Presenta recurso', 'recurso') . "<br>";
                    $datarecurso = array(
                        'name' => 'recurso',
                        'id' => 'recurso',
                        'disabled' => 'disabled',
                        'style' => 'margin:10px'
                    );
                    //echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                    echo "&nbsp;" . form_radio($datarecurso, "1", $checked1) . "&nbsp;&nbsp;SI<br>";
                    echo "&nbsp;" . form_radio($datarecurso, "0") . "&nbsp;&nbsp;NO";
                    ?>
                </p>    
            </td>           
            <td>
                <div>
                    &nbsp;
                </div>
            </td>
        </tr>
        <tr id="preseRecu">
            
            <td>
                <?php
                echo form_label('Tipo de Recurso', 'presenta');
                $selecte[''] = "-- Seleccione --";
                $selecte['1'] = "CONFIRMA";
                $selecte['2'] = "CONFIRMA PARCIALMENTE";
                $selecte['3'] = "REVOCA";
                echo "<br>" . form_dropdown('presenta', $selecte, '', 'id="presenta" class="chosen" data-placeholder="seleccione..." ');
                ?>
            </td>
        </tr>

        <tr>
            <td align="left">
                <p>
                    <?php
                    echo form_label('Cargar Documento', 'filecolilla');
                    $datadoc = array(
                        'name' => 'filecolilla',
                        'id' => 'filecolilla',
                        'value' => '', //$data->DOC_COLILLA,
                        'maxlength' => '10',
                    );
                    echo "<br>" . form_upload($datadoc);
                    ?>
                </p>
            </td>
            <td>
                <p>
                    <?php
                    echo form_label('Número de folios', 'folios');
                    $datafolios = array(
                        'name' => 'folios',
                        'id' => 'folios',
                        'value' => set_value('folios'),
                        'maxlength' => '10',
                        'type' => 'number',
                    );
                    echo "<br>" . form_input($datafolios);
                    ?>
                </p>
            </td>
        </tr>
        <tr>
            <td>
                <p>
                    <?php
                    echo form_label('Fecha Onbase', 'fechaonbase');
                    $dataFechaOn = array(
                        'name' => 'fechaonbase',
                        'id' => 'fechaonbase',
                        'value' => '',
                        'maxlength' => '12',
                        'readonly' => 'readonly',
                        'size' => '15'
                    );

                    echo "<br>" . form_input($dataFechaOn) . "&nbsp;";
                    echo form_error('fechaonbase', '<div>', '</div>');

                    echo form_hidden('fechaonbased', date("d/m/Y"));
                    ?>
                </p>    
            </td>
            <td>
                <?php
                echo form_label('N&uacute;mero Onbase', 'colilla');
                $datacolilla = array(
                    'name' => 'colilla',
                    'id' => 'colilla',
                    'value' => '', //$data->DOC_COLILLA,
                    'maxlength' => '10',
                    'type' => 'number',
                );
                echo "<br>" . form_input($datacolilla);
                ?>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <p>
                    <?php
                    echo form_label('Observaci&oacute;n&nbsp;&nbsp;', 'observacion');
                    $data = array(
                        'name' => 'observacion',
                        'id' => 'observacion',
                        'value' => '',
                        'rows' => '8',
                        'cols' => '80',
                        'style' => 'width:93%'
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
                    $datacancel = array(
                        'name' => 'button',
                        'id' => 'cancel-button',
                        'value' => 'Cancelar',
                        'type' => 'button',
                        'onclick' => 'window.location=\'' . base_url() . 'index.php/bandejaunificada/procesos\';',
                        'content' => '<i class="fa fa-undo"></i> Cancelar',
                        'class' => 'btn btn-warning'
                    );
                    echo form_button($datacancel);
                    ?>
                </p>
            </td>
        </tr>
    </table>
    <?php echo form_close(); ?>

</div>

<div id="ajax_load" class="ajax_load" style="display: none">
    <div class="preload" id="preload" >
        <img  id="load" class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        window.history.forward(-1);
        $('#fechaonbase').datepicker({
            dateFormat: "dd/mm/y",
            maxDate: "0"
                    //minDate: new Date('20'+anonot, mesnot, dianot)
        });

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
                $('#error').html("<font color='red'><b>Comprueba la extensión de los archivos a subir. \nSólo se pueden subir archivos con extensiones: " + extensiones_permitidas.join() + "</b></font>");
                $("#filecolilla").focus();
            } else {
                $('#error').show();
                $('#error').html("<font color='red'><b>Archivo Subido Correctamente</b></font>");
                return 1;
            }
        });
    })

    $('#observacion').keyup(function() {
        var max_chars = 100;
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
        tinyMCE.triggerSave();
        var recurso = $('input[name="recurso"]').is(":checked");
        var observacion = $('#observacion').val();
        var presenta = $('#presenta').val();
        var folios = $('#folios').val();
        var filecolilla = $('#filecolilla').val();
        var fechaonbase = $('#fechaonbase').val();
        var colilla = $('#colilla').val();
        char_especiales(observacion);
        var valida = true;
        if (recurso == false) {
            $('#error').show();
            $('#error').html("<font color='red'><b>Seleccione Presenta recurso</b></font>");
            valida = false;
        } else if (observacion == "") {
            $('#error').show();
            $('#error').html("<font color='red'><b>Ingrese una Observaci&oacute;n</b></font>");
            valida = false;
        } else if (presenta == "") {
            $('#error').show();
            $('#error').html("<font color='red'><b>Ingrese un tipo Recurso</b></font>");
            valida = false;
        } else if (folios == "" || folios > 999) {
            $('#error').show();
            $('#folios').val('');
            $('#error').html("<font color='red'><b>Ingrese el número de Folio</b></font>");
            valida = false;
        } else if (filecolilla == "") {
            $('#error').show();
            $('#error').html("<font color='red'><b>Por Favor adjuntar el Documento</b></font>");
            valida = false;
        } else if (colilla == "") {
            $('#error').show();
            $('#error').html("<font color='red'><b>Ingrese Número Onbase</b></font>");
            valida = false;
        } else if (fechaonbase == "") {
            $('#error').show();
            $('#error').html("<font color='red'><b>Seleccione la fecha Onbase</b></font>");
            valida = false;
        }

        if (valida == true) {
            $(".ajax_load").show("slow");
            $('#guardar').prop("disabled", true);
            $('#actaFrm').submit();
            //alert ("ok");
        }
    });

//    (function() {
//        jQuery("#addImg").on('click',addSource);
//    })();

    function deleteFile(elemento) {
        $("#arch" + elemento).fadeOut("slow", function() {
            $("#arch" + elemento).remove();
        });

    }

//    function addSource(){
//        var numItems = jQuery('.file_uploader').length;
//        var template =  '<div  class="field" id="arch'+numItems+'">'+
//                            '<div class="input-append">'+
//                                '<div >'+
//                                    '<input class="file_uploader" type="file" name="archivo'+numItems+'" id="archivo'+numItems+'">'+
//                                    ' <button type="button" class="close" id="'+numItems+'" class="btn btn-success" onclick="deleteFile(this.id)">&times;</button>'+
//                                '</div>'+
//                            '</div>'+
//                        '</div>';
//        jQuery(template).appendTo('#file_source');
//        console.log(numItems);
//    }    
</script>
