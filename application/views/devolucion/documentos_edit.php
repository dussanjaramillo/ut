<?php
$dataaprobado     = array('name' => 'aprobado', 'id' => 'aprobado', 'style' => 'margin:10px');
$dataLiquid       = array('name' => 'informacion', 'id' => 'informacion', 'type' => 'hidden');
$datanotificacion = array('name' => 'notificacion', 'id' => 'notificacion', 'value' => @$plantilla, 'width' => '80%', 'heigth' => '50%', 'readonly' => 'readonly');
//$data = array('name' => 'comentarios', 'id' => 'comentarios', 'value' => @$result->COMENTARIO, 'width' => '100%', 'heigth' => '5%', 'style' => 'width:98%', 'required' => 'true');
$data             = array('name' => 'comentarios', 'id' => 'comentarios', 'value' => '', 'width' => '100%', 'heigth' => '5%', 'style' => 'width:98%', 'onKeyUp' => 'return longitudMax(this,500)' ,'required' => 'true');
$dataFisc         = array('name' => 'devolucion', 'id' => 'devolucion', 'type' => 'hidden', 'value' => @$devolucion);
$dataGroup        = array('name' => 'grupo', 'id' => 'grupo', 'type' => 'hidden', 'value' => @$grupo);
$dataUser         = array('name' => 'iduser', 'id' => 'iduser', 'type' => 'hidden', 'value' => @$iduser);
$dataNit          = array('name' => 'nit', 'id' => 'nit', 'type' => 'hidden', 'value' => $nit);
$dataTipo         = array('name' => 'tipo', 'id' => 'tipo', 'type' => 'hidden', 'value' => @$tipo);
$dataApro         = array('name' => 'verifi_aprobado', 'id' => 'verifi_aprobado', 'type' => 'hidden', 'value' => @$result->APROBADO);
$datafile         = array('name' => 'filecolilla', 'id' => 'filecolilla', 'value' => '', 'maxlength' => '10');
$dataguardar      = array('name' => 'guardar', 'id' => 'guardar', 'value' => 'Guardar', 'type' => 'button', 'content' => '<i class="fa fa-floppy-o fa-lg"></i> Guardar', 'class' => 'btn btn-success',);
$dataPDF          = array('name' => 'pdf', 'id' => 'pdf', 'value' => 'Generar PDF', 'type' => 'button', 'content' => '<i class="fa fa-file-pdf-o"></i> Generar PDF', 'class' => 'btn btn-info',);
$datacancel       = array('name' => 'button', 'id' => 'cancel-button', 'value' => 'Cancelar', 'type' => 'button', 'onclick' => 'window.location=\'' . base_url() . 'index.php/devolucion/devoluciones_generadas\';', 'content' => '<i class="fa fa-minus-circle"></i> Cancelar', 'class' => 'btn btn-warning');
$attributes       = array('class' => 'form-inline', 'id' => 'DocumentoEdit', 'name' => 'DocumentoEdit', 'method' => 'POST');
echo form_open_multipart(current_url(), $attributes);
    echo @$custom_error;
    echo form_input($dataLiquid);
    echo form_input($dataFisc);
    echo form_input($dataNit);
    echo form_input($dataTipo);
    echo form_input($dataApro);
    echo form_input($dataGroup);
    echo form_input($dataUser);
    if (@$result->APROBADO == "1") {
        $checked1 = TRUE;
    } else {
        $checked1 = FALSE;
    }
    if (@$result->APROBADO == "2") {
        $checked2 = TRUE;
    } else {
        $checked2 = FALSE;
    }
    if (@$result->APROBADO == "0") {
        $checked0 = TRUE;
    } else {
        $checked0 = FALSE;
    }
    if (@$result->APROBADO == NULL) {
        $checked0 = FALSE;
        $checked1 = FALSE;
        $checked2 = FALSE;
    }
    ?>

    <table cellspacing="0" cellspading="0" border="0" align="center">
        <tr>
            <td align='center'><?= $titulo ?></td>
        </tr>
        <tr>
            <p><td><?= form_textarea($datanotificacion); ?></td></p>
        </tr>
        <tr><td><br /></td></tr>
        <tr>
            <td>                
                <?php echo form_label('Asignar a&nbsp;<span><font color="red"><b>*</b></font></span>', 'asignando'); ?> 
                <select name="asignado" id="asignado" required>
                    <option value=''>--Seleccione--</option>
                    <?php foreach ($asignado as $row) {
                        echo '<option value="' . $row->IDUSUARIO . '">' . $row->NOMBRES . ' ' . $row->APELLIDOS . '</option>';
                    }
                    ?>
                </select>            
            </td>
        </tr>
        <tr><td><br /></td></tr>
        <tr>            
            <td><?= 'Comentarios&nbsp;&nbsp;&nbsp;(Max. 500 Caracteres)<span class="required"><font color="red"><b>*</b></font></span>' . form_textarea($data); ?></td>                                
        </tr>
        <tr><td><br></td></tr>     

        <tr>
            <td>
                <br />
                <div class="Comentarios"   style="width: 80%; border-color: black; border: 1px solid grey; margin: auto; overflow: hidden;alignment-adjust: central ;padding: 15px 50px 0">
                    <?php
                    echo form_label('<b><h3>Historial de Comentarios</h3></b><span class="required"></span>', 'lb_comentarios');
                    echo "<br>";
                    for ($i = 0; $i < sizeof($comentario); $i++) {
                        echo '<div class="alert-success" style="border-color: black; border: 1px solid grey;padding: 15px 50px 0">';
                        echo '<b>Fecha del Comentario: </b>' . $comentario[$i]["FECHA_CREACION"] . '<br>';
                        echo '<b>Hecho Por: </b>' . $comentario[$i]["NOMBRES"] . " " . $comentario[$i]["APELLIDOS"] . '<br>';
                        echo $comentario[$i]["COMENTARIO"];
                        echo "<br><br>";
                        echo '</div>';
                        echo "<br>";
                    }
                    ?>
                </div>
                <br />
            </td>
        </tr>
        <tr id="cargColilla" style="display:none">
            <td align="left">
                <p>
                    <?php
                    echo form_label('Cargar Documento', 'filecolilla');
                    echo "<br>" . form_upload($datafile);
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
                            <div id="aproved" class="span3">
                                <?php
                                echo form_label('Aprobado', 'aprobado') . "<br>";
                                echo "<div id='aprobado' style='display:none'>";
                                echo form_radio($dataaprobado, "1", $checked1) . "&nbsp;&nbsp;APROBADO<br>";
                                echo "</div>";
                                echo "<div id='preAprobado' style='display:none'>";
                                echo form_radio($dataaprobado, "2", $checked2) . "&nbsp;&nbsp;PRE - APROBADO<br>";
                                echo "</div>";
                                echo "<div id='devolver' style='display:none'>";
                                echo form_radio($dataaprobado, "0", $checked0) . "&nbsp;&nbsp;DEVOLVER";
                                echo "</div>";
                                ?>
                            </div>
                            </p>             
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td align='center'>
                <div style="display:none" id="error" class="alert alert-danger"></div>
            </td>
        </tr>
        <tr><td><br></td></tr>             
        <tr align ='center'>
            <td>
                <?php
                echo form_button($dataguardar) . "&nbsp;&nbsp;";
                echo form_button($dataPDF) . "&nbsp;&nbsp;";
                echo form_button($datacancel);
                ?>
            </td>
        </tr>
        <tr><td><br><td><br></td></tr>
        <div id="ajax_load" class="ajax_load" style="display: none">
            <div class="preload" id="preload" >
                <img  id="load" class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
            </div>
        </div>
    </table>
<?php echo form_close(); ?>
<script type="text/javascript">
    
    $(document).ready(function() {
        window.history.forward(-1);
        var grupo = $('#grupo').val();
        var verifi_aprobado = $('#verifi_aprobado').val();
        var user = $("#iduser").val();
        if (verifi_aprobado == 1)
            var read = 1;
        else
            var read = 0;
        tinymce.init({
            language: "es",
            selector: "textarea#notificacion",
            theme: "modern",
            readonly: read,
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
        tinyMCE.triggerSave();

        if (grupo == 145 && verifi_aprobado == 1) {
            $('#cargColilla').show();
            $("#asignado").val(user);
            $('#pdf').trigger('click');
            $("#asignado").attr('disabled', true)
            $('#aproved').hide();
        } else {
            $('#preAprobado').show();
        }

        if (grupo == 144 && verifi_aprobado != 1) {
            $('#aprobado').show();
            $('#devolver').show();
            $('#preAprobado').hide();
        }
        
        $('#filecolilla').change(function() {
            var file = $("#filecolilla")[0].files[0];
            var fileSize = file.size;
            var resultado = 0;
            if(fileSize > 5242880) {
                $('#filecolilla').val('');
                resultado = (fileSize/1024)/1024;
                var resultado1 = Math.round(resultado*100)/100 ; 
                alert ('El archivo a superado el limite de tamaño para adjuntos de 5Mb. Tamaño archivo de ' + resultado1 + ' Mb');
            }           
        
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
    })

    $('#guardar').click(function() {
        $('#error').html("");
        var comentarios = $('#comentarios').val();
        var notificacion = $('#notificacion').val();
        var asignado = $('#asignado').val(); 
        var filecolilla = $('#filecolilla').val();
        var verifi_aprobado = $('#verifi_aprobado').val(); 
        var aprobado = $("input[name='aprobado']:checked").val();
        var valida = true;
        var url = '<?php echo base_url('index.php/devolucion/documentos_edit') ?>';

        if (notificacion == "") {
            $('#error').show();
            $('#notificacion').focus();
            $('#error').html("<b>Por favor ingrese Información</b>");
            valida = false;
        } else if (comentarios == "") {
            $('#error').show();
            $('#comentarios').focus();
            $('#error').html("<b>Ingrese Comentarios</b>");
            valida = false;
        } else if (asignado == "") {
            $('#error').show();
            $('#asignado').focus();
            $('#error').html("<b>Por Favor Asignar usuario</b>");
            valida = false;
        } else if (aprobado == undefined) {
            $('#error').show();
            $('#aprobado').focus();
            $('#error').html("<b>Por favor validar Revisión</b>");
            valida = false;
        } else if (verifi_aprobado == 1 && filecolilla == '') {
            $('#error').show();
            $('#aprobado').focus();
            $('#error').html("<b>Por favor adjuntar archivo</b>");
            valida = false;
        }

        if (valida == true) {
            $(".ajax_load").show("slow");
            $("#asignado").removeAttr('disabled');
            $('#guardar').prop("disabled", true);
            $("#DocumentoEdit").attr("action", url);
            $('#DocumentoEdit').removeAttr('target');
            $('#DocumentoEdit').submit()
        }
    });

    $('#pdf').click(function() {
        tinyMCE.triggerSave();
        var notify = $('#notificacion').val();
        $('#informacion').val(notify);
        $("#DocumentoEdit").attr("action", "<?= base_url('index.php/devolucion/pdf') ?>");
        $('#DocumentoEdit').attr('target', '_blank');
        $('#DocumentoEdit').submit();
    });
</script>     