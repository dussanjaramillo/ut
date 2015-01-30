
<?php
$data = array();
$data = $result;
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<?php
$attributes = array('class' => 'form-inline', 'id' => 'MandamientoEdit', 'name' => 'MandamientoEdit', 'method' => 'POST');
echo form_open_multipart(current_url(), $attributes);
echo form_hidden('id', $data->COD_MANDAMIENTOPAGO);
echo "<center>" . $titulo . "</center>";
echo $custom_error;
?>       
<table cellspacing="0" cellspading="0" border="0" align="center">
    <tr>    
    <input type="hidden" name="nit" id="nit" value="<?php echo $informacion[0]['CODEMPRESA']; ?>">
    <input type="hidden" name="cod_coactivo" id="cod_coactivo" value="<?php echo $cod_coactivo ?>">
    <input type="hidden" name="verifi_aprobado" id="verifi_aprobado" value="<?php echo $data->APROBADO; ?>"> 
    <input type="hidden" name="verifi_revisado" id="verifi_revisado" value="<?php echo $data->REVISADO; ?>">
    <input type="hidden" id="clave" name="clave" value="<?= $data->COD_MANDAMIENTOPAGO ?>">
    <input type="hidden" id="codigo" name="codigo" value="">            
    <input type="hidden" name="tipo" id="tipo" value="<?php echo $tipo ?>">
    <input type="hidden" name="gestion" id="gestion" value="<?php echo $gestion ?>">            
    <input type='hidden' value='<?php echo $user->IDUSUARIO ?>' name='iduser' id='iduser'>
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
    <tr>
        <td>
            <p>            
                <?php
                $datamandamiento = array('name' => 'mandamiento', 'id' => 'mandamiento', 'value' => @$plantilla, 'width' => '80%', 'heigth' => '50%');
                echo form_textarea($datamandamiento);
                echo form_error('mandamiento', '<div>', '</div>');
                ?>
            </p>
        </td>
    </tr>
    <tr id="numColilla" style="display:none">
        <td align="left">
            <p>
                <?php
                echo form_label('Cargar Documento&nbsp;&nbsp;<span class="required"><font color="red"><b>*</b></font></span>', 'filecolilla');
                $datafile = array('name' => 'filecolilla', 'id' => 'filecolilla', 'value' => '', 'maxlength' => '10',);
                echo "<br>" . form_upload($datafile);
                ?>
            </p>
        </td>
    </tr>
    <tr id='datoResolucion' style="display:none">
        <td>
            <table>
                <tr>
                    <td>
                        <?php
                        echo form_label('Fecha Onbase', 'fechaonbase');

                        $dataFechaOn = array('name' => 'fechaonbase', 'id' => 'fechaonbase', 'value' => '', 'maxlength' => '12', 'size' => '15');

                        echo "<br>" . form_input($dataFechaOn) . "&nbsp;";
                        echo form_error('fechaonbase', '<div>', '</div>');
                        echo form_hidden('fechaonbased', date("d/m/Y"));
                        ?>  
                    </td>
                    <td>
                        <?php
                        echo form_label('N&uacute;mero Onbase', 'colilla');
                        $datacolilla = array('name' => 'colilla', 'id' => 'colilla', 'value' => '', 'maxlength' => '10', 'type' => 'number',);
                        echo "<br>" . form_input($datacolilla);
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php
                        echo form_label('Fecha Resolución<span class="required"><font color="red"><b>*</b></font></span>', 'fecharesolucion');
                        $dataFechaOn = array('name' => 'fecharesolucion', 'id' => 'fecharesolucion', 'value' => '', 'maxlength' => '12', 'readonly' => 'readonly', 'size' => '15');
                        echo "<br>" . form_input($dataFechaOn) . "&nbsp;";
                        echo form_error('fecharesolucion', '<div>', '</div>');
                        echo form_hidden('fecharesolucioned', date("d/m/Y"));
                        ?>  
                    </td>
                    <td>
                        <?php
                        echo form_label('N&uacute;mero Resolución<span class="required"><font color="red"><b>*</b></font></span>', 'colilla');
                        $datacolilla = array('name' => 'numero_resolucion', 'id' => 'numero_resolucion', 'value' => '', 'maxlength' => '10', 'type' => 'number',);
                        echo "<br>" . form_input($datacolilla);
                        ?>
                    </td>
                </tr>
            </table>                            
        </td>
    </tr>
    <tr>
        <td>
            <p>
                <?php
                echo form_label('Comentarios&nbsp;&nbsp;<span class="required"><font color="red"><b>*</b></font></span>', 'comentarios');
                echo "<br>";
                $datacoment = array('name' => 'comentarios', 'id' => 'comentarios', 'value' => $data->COMENTARIOS, 'rows' => '7', 'cols' => '80', 'style' => 'width:98%');
                echo "&nbsp;&nbsp;" . form_textarea($datacoment);
                echo form_error('comentarios', '<div><b><font color="red">', '</font></b></div>');
                ?>
            </p>
        </td>
    </tr>
    <tr>
        <td align="center">
            <table border ="0">
                <tr>
                    <td>
                        <p>
                        <div id="revised" class="span3" style="display:block;">
                                                      <?php
                             echo form_label('Revisado', 'revisados')."<br>";
                             if ($data->REVISADO == "REVISADO") {
                             $checked1 = TRUE;
                             }else {
                              $checked1 = FALSE;
                             }
                             if ($data->REVISADO == "DEVOLVER") {
                                $checked2 = TRUE;
                                }else {
                                 $checked2 = FALSE;
                                }
                             $datarevisado = array('name'=>'revisado','id'=>'revisado','style'=>'margin:10px');
                             echo form_radio($datarevisado,"REVISADO",$checked1)."&nbsp;&nbsp;PRE-APROBAR<br>";
                             echo "<div id='divdevol'>";
                             echo form_radio($datarevisado,'DEVOLVER',$checked2).'&nbsp;&nbsp;DEVOLVER<br><br><br>';
                             echo "</div>";
                            ?>    
                        </div>
                        </p>
                    </td>
                    <td>
                        <p>
                        <div id="aproved" class="span3">
                            <?php
                            $data->APROBADO == "REVISADO";
                            echo form_label('Aprobado', 'aprobado') . "<br>";
                            if ($data->APROBADO == "REVISADO") {
                                $checked1 = TRUE;
                            } else {
                                $checked1 = FALSE;
                            }
                            if ($data->APROBADO == "APROBADO") {
                                $checked2 = TRUE;
                            } else {
                                $checked2 = FALSE;
                            }
                            if ($data->APROBADO == "RECHAZADO") {
                                $checked3 = TRUE;
                            } else {
                                $checked3 = FALSE;
                            }

                            $dataaprobado = array('name' => 'aprobado', 'id' => 'aprobado', 'style' => 'margin:10px');
                            echo '<div id="REVISADO">';

                            echo form_radio($dataaprobado, "REVISADO", $checked1) . "&nbsp;&nbsp;APROBADO<br>";

                            echo '</div>';
                            echo '<div id="APROBADO" style=display:none>';
                            echo form_radio($dataaprobado, "APROBADO", $checked2) . "&nbsp;&nbsp;APROBADO Y FIRMADO<br>";
                            echo '</div>';
                            echo '<div id="RECHAZADO">';
                            echo form_radio($dataaprobado, "RECHAZADO", $checked3) . "&nbsp;&nbsp;DEVOLVER";
                            echo '</div>';

                            echo form_error('aprobado', '<div>', '</div>');
                            $permisoUser = 0;
                            foreach ($permiso as $user) {
                                $var = $user['IDGRUPO'];
                                $permisoUser = $var . '-' . $permisoUser;
                            }
                            $permisoUser = substr($permisoUser, 0, -2);
                            echo "
                                     <td>
                                     <input type='hidden' value='" . $user['IDUSUARIO'] . "' name='iduser' id='iduser'>
                                     </td>";
                            ?>
                        </div>
                        </p>             
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td align="center">
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
                    'content' => '<i class="fa fa-file-pdf-o"></i> Generar PDF',
                    'class' => 'btn btn-info'
                );
                echo form_button($dataPDF) . "&nbsp;&nbsp;";
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
<input type="hidden" name="tipo_documento" id="tipo_documento" value="3" >
    <input type="hidden" name="titulo_doc" id="titulo_doc" >
<?php echo form_close(); ?>
<div id="ajax_load" class="ajax_load" style="display: none">
    <div class="preload" id="preload" >
        <img  id="load" class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        var gestion = <?php echo $gestion ?>;
        var aprobado = $('#verifi_aprobado').val();
        var revisado = $('#verifi_revisado').val();
        $('#fechaonbase').datepicker({
            dateFormat: "dd/mm/y",
            maxDate: "0"
        });
        $('#fecharesolucion').datepicker({
            dateFormat: "dd/mm/y",
            maxDate: "0"
        });

        if (gestion == 209 || gestion == 1022 || gestion == 1085) {
            var readonly = 1;
        } else {
            var readonly = 0;
        }

        tinymce.init({
            language: "es",
            selector: "textarea#mandamiento",
            theme: "modern",
            readonly: readonly,
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

        switch (gestion) {
            case 205:
                $('#aproved').hide();
                break
            case 264:
                $('#aproved').hide();
                jQuery("input[name=revisado]").attr("checked", false)
                break
            case 246:
                $('#aproved').hide();
                jQuery("input[name=revisado]").attr("checked", false)
                break
            case 1018:
                $('#aproved').hide();
                jQuery("input[name=revisado]").attr("checked", false)
                break
            case 206:
                jQuery("input[name=revisado]").attr("disabled", true)
                jQuery("input[name=aprobado]").attr("checked", false)
                $('#revised').hide();
                break;
            case 247:
                jQuery("input[name=revisado]").attr("disabled", true)
                jQuery("input[name=aprobado]").attr("checked", false)
                $('#revised').hide();
                break;
            case 265:
                jQuery("input[name=revisado]").attr("disabled", true)
                jQuery("input[name=aprobado]").attr("checked", false)
                $('#revised').show();
                break;
            case 1019:
                jQuery("input[name=revisado]").attr("disabled", true)
                jQuery("input[name=aprobado]").attr("checked", false)
                $('#revised').hide();
                break;
            case 207:
                if (revisado == 'DEVOLVER' || aprobado == 'RECHAZADO') {
                    $('#aproved').hide();
                    $('#divdevol').hide();
                    $('#revised').hide();
                }
                break;
            case 248:
                if (revisado == 'DEVOLVER' || aprobado == 'RECHAZADO') {
                    $('#aproved').hide();
                    $('#divdevol').hide();
                    $('#revised').hide();
                }
                break;
            case 266:
                if (revisado == 'DEVOLVER' || aprobado == 'RECHAZADO') {
                    $('#aproved').hide();
                    $('#divdevol').hide();
                    $('#revised').hide();
                }
                break;
            case 1020:
                if (revisado == 'DEVOLVER' || aprobado == 'RECHAZADO') {
                    $('#aproved').hide();
                    $('#divdevol').hide();
                    $('#revised').hide();
                }
                break;
            case 1022:
                $('#REVISADO').hide();
                $('#RECHAZADO').hide();
                $('#revised').hide();
                $('#aproved').hide();
                $('#numColilla').show();
                $('#datoResolucion').show();
                break;
            case 209:
                $('#REVISADO').hide();
                $('#RECHAZADO').hide();
                $('#revised').hide();
                $('#aproved').hide();
                $('#numColilla').show();
                $('#datoResolucion').show();
                break;
            case 743:
                $('#REVISADO').hide();
                $('#RECHAZADO').hide();
                $('#revised').hide();
                $('#aproved').hide();
                $('#numColilla').show();
                $('#datoResolucion').show();
                break;
            case 1085:
                $('#REVISADO').hide();
                $('#RECHAZADO').hide();
                $('#revised').hide();
                $('#aproved').hide();
                $('#numColilla').show();
                $('#datoResolucion').show();
                break;
        }
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
            $('#error').html("<font color='red'><b>Archivo Subido Correctamente</b></font>");
            $('#aproved').show();
            $('#APROBADO').show();
            return 1;
        }
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
        var comentarios = string.replace(/[^a-zA-Z 0-9.]+/g, ' ');
        $('#comentarios').val(comentarios);
    }
    $('#guardar').click(function() {
        $('#error').html("");
        var coment = $('#comentarios').val();
        var clave = $('#clave').val();
        var colilla = $('#filecolilla').val();
        var resolucion = $('#colilla').val();
        var fecharesol = $('#fechaonbase').val();
        var aprobado = $("input[name='aprobado']:checked").val();
        var revisado = $("input[name='revisado']:checked").val();
        var gestion = <?php echo $gestion ?>;
        var tipo = $('#tipo').val();
        char_especiales(coment);
        var valida = true;
        var url = '<?php echo base_url('index.php/mandamientopago/') ?>';
        if (tipo == 'mandamiento') {
            url = url + '/edit';
        } else if (tipo == 'adelante') {
            url = url + '/editRes';
        } else if (tipo == 'excepcion') {
            url = url + '/editExc';
        } else if (tipo == 'levanta') {
            url = url + '/editLevanta';
        } else if (tipo == 'recurso') {
            url = url + '/editRec';
        }
        //alert (+aprobado+"***"+revisado+"***"+gestion)
        $('#codigo').val(clave);
        if (coment == "") {
            $('#error').show();
            $('#error').html("<b>Ingrese Comentarios</b>");
            valida = false;
        } else if ((gestion == 205 || gestion == 1018 || gestion == 264 || gestion == 246) && revisado == undefined) {
            $('#error').show();
            $('#error').html("<b>Por favor validar Revisión</b>");
            valida = false;
        } else if ((gestion == 209 || gestion == 1022 || gestion == 1085 || gestion == 743) && colilla == '') {
            $('#error').show();
            $('#error').html("<b>Por favor adjuntar Archivo</b>");
            valida = false;
        } else if ((gestion == 209 || gestion == 1022 || gestion == 1085 || gestion == 743) && aprobado != 'APROBADO') {
            $('#error').show();
            $('#error').html("<b>Por favor validar Revisión</b>");
            valida = false;
        } else if ((gestion == 206 || gestion == 1019 || gestion == 265 || gestion == 247) && aprobado == undefined) {
            $('#error').show();
            $('#error').html("<b>Por favor validar Revisión</b>");
            valida = false;
        } else if ((gestion == 209 || gestion == 743) && resolucion == '') {
            $('#error').show();
            $('#error').html("<b>Por favor ingresar Resolución</b>");
            valida = false;
        } else if ((gestion == 209 || gestion == 743) && fecharesol == '') {
            $('#error').show();
            $('#error').html("<b>Por favor ingresar fecha de Resolución</b>");
            valida = false;
        }

        if (valida == true) {
            $(".ajax_load").show("slow");
            $('#guardar').prop("disabled", true);
            $("#asignado").removeAttr('disabled');
            $("#MandamientoEdit").attr("action", url);
            $('#MandamientoEdit').removeAttr('target');
            $('#MandamientoEdit').submit()
            //alert('ok');
        }
    });

    $('#pdf').click(function() {
        var notify = $('#notificacion').val();
        $('#mandamiento').val(notify);
        $('#MandamientoEdit').attr("action", "<?= base_url('index.php/mandamientopago/pdf') ?>");
        $('#MandamientoEdit').attr('target', '_blank');
        $('#MandamientoEdit').submit();
    });
</script>
