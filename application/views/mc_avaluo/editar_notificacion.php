<?php
if (@$proceso['RADICADO_ONBASE'] == NULL):
    $valueRadicado = '';
    $valuefecha = '';
else:
    $valueRadicado = $proceso['RADICADO_ONBASE'];
    $valuefecha = $proceso['FECHA_ONBASE'];
endif;
$datanotificacion = array('name' => 'informacion', 'id' => 'informacion', 'value' => $documento, 'width' => '80%', 'heigth' => '80%');
$comentarios = array('name' => 'comentarios', 'id' => 'comentarios', 'value' => set_value('comentarios'), 'width' => '100%', 'heigth' => '5%', 'style' => 'width:98%');
$datafile = array('name' => 'file', 'id' => 'file', 'value' => '', 'maxlength' => '10', 'type' => 'file');
$dataguardar = array('name' => 'guardar', 'id' => 'guardar', 'value' => 'Guardar', 'type' => 'button', 'content' => '<i class="fa fa-floppy-o fa-lg"></i> Enviar', 'class' => 'btn btn-success');
$dataadicionales = array('name' => 'adicionales', 'id' => 'adicionales', 'style' => 'margin:10px');
$dataonbase = array('name' => 'onbase', 'id' => 'onbase', 'value' => $valueRadicado, 'maxlength' => '10');
$datadevolucion = array('name' => 'devolucion', 'id' => 'devolucion', 'style' => 'margin:10px');
$dataFecha = array('name' => 'fechanotificacion', 'id' => 'fechanotificacion', 'value' => $valuefecha, 'maxlength' => '12', 'readonly' => 'readonly', 'size' => '15');
$datacancel = array('name' => 'button', 'id' => 'cancel-button', 'value' => 'Cancelar', 'type' => 'button', 'onclick' => 'window.location=\'' . base_url() . 'index.php/mc_avaluo/abogado\';', 'content' => '<i class="fa fa-undo"></i> Cancelar', 'class' => 'btn btn-warning');
$dataPDF = array('name' => 'button', 'id' => 'pdf', 'value' => 'Generar PDF', 'type' => 'button', 'content' => '<i class="fa fa-file"></i> Generar PDF', 'class' => 'btn btn-info', 'onclick' => 'f_genera_pdf()');
$attributes = array('id' => 'frmtp', 'name' => 'frmtp', 'method' => 'POST');

echo form_open_multipart("mc_avaluo/gestion_notificacion", $attributes);
?>
<?php echo form_hidden('detalle', serialize($post)); ?>
<input id="id" name='id' type='hidden' value="<?php echo $post['cod_proceso'] ?>">
<input id="nombre" name='nombre' type='hidden' value="<?php echo $post['nombre'] ?>"><!--
<input id="estado" name='estado' type='hidden' value="">
<input id="nit" name='nit' type='hidden' value="">
-->

<center>
    <div id="descripcion" class="alert alert-info"></div>
    <table>
        <tr>
            <td colspan="2">
                <table width="100%" border="0" align="center">
                    <tr>
                        <td width="23%" rowspan="2"><img src="<?= base_url('img/Logotipo_SENA.png') ?>" width="100" height="100" /></td>
                        <td width="44%" height="94">
                            <!--                        <div align="center"><h4><b></b></h4></div>-->
                        </td>
                    </tr>
<!--                    <tr>
                        <td height="50" colspan="2"><div align="center">
                                <h2><input readonly value="" name="Titulo_Encabezado" type="text" id="Titulo_Encabezado" class="input-xxlarge" size="50" /></h2>
                            </div></td>
                    </tr>-->
                </table>   
            </td>
        </tr>
        <tr id="notificar">
            <td colspan="2">
                <?php echo form_textarea($datanotificacion) ?>
            </td>
        </tr>
        <tr>

            <td id="paginaWeb" style="display:none" colspan="2">
                <?php echo form_label('Generar Notificación: ', 'notificacion'); ?>
                <a href='http://www.sena.edu.co/transparencia/gestion-juridica/paginas/cobros-coactivos.aspx' class='btn-danger' target=_blank>
                    Crear Notificación Página Web
                </a>
            </td>
        </tr>    
        <tr>        
            <td colspan="2">                            
                <div class="Comentarios" style="width: 84%; border-color: black; border: 1px solid grey; margin: auto; overflow: hidden;alignment-adjust: central ;padding: 15px 50px 0">
                    <?php
                    echo form_label('<b><h2>Historial de Comentarios</h2></b><span class="required"></span>', 'lb_comentarios');
                    echo "<br>";
                    ?>

                </div>
            </td>        
        </tr>
        <tr><td><br></td></tr>
        <tr id="radicOnbase" style="display:none">
            <td id="id_onbase" style="display:block">
                <?php
                echo form_label('Numero de radicado Onbase', 'onbase');
                echo form_input($dataonbase);
                ?>
            </td>
            <td id="id_fecha_not" style="display:block">
                <?php
                echo form_label('Fecha notificaci&oacute;n', 'fechanotificacion');
                echo form_input($dataFecha) . "&nbsp;";
                ?>
                </div>
            </td>
        </tr>
        <tr style="display: none" id="tr_notificacion_efectiva">
            <td>
                <label>Fecha Notificación Efectiva</label>
                <input type="text" name="notificacion_efectiva" id="notificacion_efectiva" >
            </td>
        </tr>
        <tr id='seleccion' style="display: none;">
            <td colspan="2">
                <div class="alert-success" style="border-color: black; border: 1px solid grey;padding: 15px 50px 0">
                    <?php
                    echo form_label('<b>Revisión</b>&nbsp;&nbsp;<span class="required"></span>', 'revisado');
                    echo "Aprobado" . form_radio($dataadicionales, "1") . "&nbsp;&nbsp;";
                    echo "No Aprobado" . form_radio($dataadicionales, "0") . "&nbsp;";
                    ?>
                </div>
            </td>
        </tr>     
        <tr>
            <td id="Devol" style="display:none">
                <?php
                echo form_label('Devolucion', 'devolucion') . "<br>";
                echo "&nbsp;" . form_radio($datadevolucion, "1") . "&nbsp;&nbsp;SI<br>";
                echo "&nbsp;" . form_radio($datadevolucion, "0") . "&nbsp;&nbsp;NO";
                ?>
            </td> 
            <td id="motDevol" style="display:none">
                <?php echo form_label('Motivo devoluci&oacute;n&nbsp;&nbsp;', 'motivo'); ?>
                <select name="motivo" id="motivo">
                    <option value=''>--Seleccione--</option>
                    <?php
                    foreach ($motivos as $row) {
                        echo '<option value="' . $row->COD_MOTIVO_DEVOLUCION . '">' . $row->MOTIVO_DEVOLUCION . '</option>';
                    }
                    ?>
                </select>
            </td>
        </tr>  
        <tr id="cargColilla" style="display:block">            
            <td  colspan="2">
                <?php echo form_label('Cargar Documento', 'cargar') . "<br>"; ?>
                <?php echo form_upload($datafile); ?>

            </td>
        </tr>
        <tr><td><br></td></tr>
        <tr>
            <td colspan="2">
                <?php
                echo form_label('<b>Comentarios</b>&nbsp;&nbsp;<span class="required"><font color="red"><b>*</b></font></span>', 'comentarios');
                echo form_textarea($comentarios);
                ?>
            </td>
        </tr>
        <tr>
            <td  colspan="2">
                <div style="float:center; ">  <br><br>
                    <center>
                        <table>
                            <tr>
                                <td> <?php echo form_button($dataguardar); ?></td>
                                <td> <?php echo form_button($dataPDF); ?></td>
                                <td> <?php echo form_button($datacancel); ?></td>
                                <td><input type="button" name="ver_observaciones" id="ver_observaciones" value="Comentarios" class="btn btn-success">  </td>
                            </tr>
                        </table>
                    </center>
                </div>
                <div id="Observaciones_anteriores"  style="display: none;" >
                    <br><br>
                    <table style="width:100%; float:justify; text-align:justify;">
                        <tr><td  style="background: #f0f0f0; text-align:center; padding: 15px 50px 15px 15px; " >
                                <font style="color:#238276;"> Comentarios</font>
                                <div id="close" style="width:auto;float:right; text-align:right;">
                                    <a href="#" id="ocultar_observaciones"><font  style="color:#238276;">cerrar</font></a> 
                                </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="background: #f0f0f0;"><br>
                                <div class="alert-success" style="border-color: black; border: 1px solid grey;padding: 15px 50px 0">
                                    <?php echo $traza; ?> 
                                </div>
                                <br>
                            </td>
                        </tr>
                    </table>
                </div> 
            </td>
        </tr>
        <tr>
            <td colspan="2" >
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
<form id="form_pdf" name="form_pdf" target = "_blank"  method="post" action="<?php echo base_url('index.php/mc_avaluo/pdf') ?>">
    <textarea id="descripcion_pdf" name="descripcion_pdf" style="width: 100%;height: 300px; display:none"></textarea>  
    <input type="hidden" name="nombre_archivo" id="nombre_archivo">

</form>
<script>
    //ver observaciones anteriores si existen
    $("#ocultar_observaciones").click(function() {
        $("#Observaciones_anteriores").hide();
    });
    $("#ver_observaciones").click(function() {
        $("#Observaciones_anteriores").show();
    });

    //agregar nuevas observaciones
    $("#agregar_observaciones").click(function() {
        $("#observaciones_nuevas").show();
    });
    $("#ocultar_obser_nueva").click(function() {
        $("#observaciones_nuevas").hide();
    });
    tinymce.init({
        language: "es",
        selector: "textarea#informacion",
        theme: "modern",
        //readonly: readonly,
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
    var estado = 0;
    var readonly = 0;
    estado = '<?php echo $post['respuesta'] ?>';
    if (estado == 393) {
        readonly = 1;
    }

    function base64_encode(data)
    {
        var b64 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';
        var o1, o2, o3, h1, h2, h3, h4, bits, i = 0,
                ac = 0,
                enc = '',
                tmp_arr = [];
        if (!data)
        {
            return data;
        }
        do
        {
            o1 = data.charCodeAt(i++);
            o2 = data.charCodeAt(i++);
            o3 = data.charCodeAt(i++);
            bits = o1 << 16 | o2 << 8 | o3;
            h1 = bits >> 18 & 0x3f;
            h2 = bits >> 12 & 0x3f;
            h3 = bits >> 6 & 0x3f;
            h4 = bits & 0x3f;
            tmp_arr[ac++] = b64.charAt(h1) + b64.charAt(h2) + b64.charAt(h3) + b64.charAt(h4);
        }
        while (i < data.length);
        enc = tmp_arr.join('');
        var r = data.length % 3;
        return (r ? enc.slice(0, r - 3) : enc) + '==='.slice(r || 3);
    }

    function f_genera_pdf() {

        var url = "<?php echo base_url('index.php/mc_avaluo/pdf') ?>";
        var informacion = tinymce.get('informacion').getContent();

        if (informacion == '')
        {
            mierror = 'Debe ingresar el contenido de la notificación';
            document.getElementById("respuesta").innerHTML = mierror;
            return false;
        }
        var nit = "<?php echo $consulta[0]['IDENTIFICACION'] ?>";
        var nombre_archivo = "<?php echo date("d-m-Y H:i:s"); ?>";
        var nombre_archivo = nombre_archivo.replace(":", "_");
        var nombre_archivo = nombre_archivo.replace(":", "_");
        var nombre_archivo = nombre_archivo.replace(" ", "_");
        var nombre_archivo = nombre_archivo + "_" + nit;
        document.getElementById("nombre_archivo").value = nombre_archivo;
        document.getElementById("descripcion_pdf").value = base64_encode(informacion);
        $("#form_pdf").submit();
    }
    $(document).ready(function() {

        $('#resultado').dialog({
            autoOpen: true,
            width: 900,
            height: 450,
            modal: true,
            title: "<?= $post['titulo'] ?>",
            close: function() {
                $('#resultado *').remove();
                jQuery(".preload, .load").hide();

            }

        });
        $('#fechanotificacion').datepicker({
            dateFormat: "dd/mm/yy",
            maxDate: "0"
        });
        $('#notificacion_efectiva').datepicker({
            dateFormat: "dd/mm/yy",
            maxDate: "0"
        });

        var estado = <?php echo $post['respuesta'] ?>;

        switch (estado) {
        
            case 1403:/*NOTIFICACION POR CORREO RECHAZADA*/
                $("#seleccion").hide();
                $("#cargColilla").hide();
                break;
            case 393:
                var info = 'La Notificación Personal ha sido aprobada por favor imprimala para que sea firmada e ingrese los datos de envió de la notificación.'
                $("#descripcion").html(info);
                $("#seleccion").hide();
                $("#cargColilla").hide();
                $("#radicOnbase").show();
                break;
            case 1424:
                var info = 'La Notificación por correo ha sido aprobada por favor imprimala para que sea firmada e ingrese los datos de envió de la notificación.'
                $("#descripcion").html(info);
                $("#seleccion").hide();
                $("#cargColilla").hide();
                $("#radicOnbase").show();
                break;
            case 394:
                var info = 'La Notificación personal  ha sido enviada por favor ingrese los datos de la respuesta al enviar la notificación.'
                $("#descripcion").html(info);
                $("#seleccion").hide();
                $("#cargColilla").show();
                $("#radicOnbase").show();
                $("#Devol").show();
                $("#radicOnbase").hide();
//                $("#onbase").attr('readonly', true);
//                $("#fechanotificacion").attr('readonly', true);
                $("#tr_notificacion_efectiva").show();

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
            case 1425:
                var info = 'La Notificación por correo ha sido enviada por favor ingrese los datos de la respuesta al enviar la notificación.'
                $("#descripcion").html(info);
                $("#seleccion").hide();
                $("#cargColilla").show();
                $("#radicOnbase").show();
                $("#Devol").show();
                $("#onbase").attr('readonly', true);
                $("#fechanotificacion").attr('readonly', true);
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
            case 1427:
                var info = 'La Notificación Por Correo Al Deudor Informando El Resultado Del Avaluo ha sido Devuelta por favor genere la Notificación Por página web.';
                $("#descripcion").html(info);
                $("#seleccion").hide();
                $("#cargColilla").hide();
                $("#notificar").show();
                $('#paginaWeb').show();
                $('#pdfButon').hide();
                break;
            case 1418:
                var info = 'La Notificación Por Pagina Web Al Deudor Informando El Resultado Del Avaluo ha sido Generada.'
                $("#descripcion").html(info);
                $("#seleccion").hide();
                $("#cargColilla").hide();
                $("#notificar").show();
                $('#paginaWeb').show();
                $('#pdfButon').hide();
                break;
            case 1419:
                var info = 'La Notificación por página web ha sido pre-aprobada.'
                $("#descripcion").html(info);
                $("#seleccion").hide();
                $("#cargColilla").show();
                $("#notificar").show();
                $('#paginaWeb').show();
                $('#pdfButon').hide();
                $('#id_onbase').hide();
                $('#radicOnbase').show();
                $('#id_fecha_not').show();

                $('#file').change(function() {
                    var extensiones_permitidas = new Array(".jpg", ".png", ".gif");
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

        }

    })

    $(".Comentarios").hide();
    $('#ver_comentarios').click(function() {
        $(".Comentarios").show();
    });

    $('input[name="devolucion"]').change(function() {
        if ($('input[name="devolucion"]:checked').val() == '0') {
            $('#motDevol').hide('slow');
            $('#motivo').val('');
        } else {
            ($('input[name="devolucion"]:checked').val() == '1')
            $('#motDevol').show();
        }
    });

    $('#guardar').click(function() {
        var estado = '<?php echo $post['respuesta'] ?>';
        var comentarios = $('#comentarios').val();
        var file = $('#file').val();
        var adicionales = $("input[name='adicionales']:checked").val();
        var onbase = $('#onbase').val();
        var motivo = $('#motivo').val();
        var fechanotificacion = $('#fechanotificacion').val();
        var devolucion = $('input[name="devolucion"]:checked').val();
        var tr_notificacion_efectiva = $("#tr_notificacion_efectiva").val();

        var valida = true;
  
        switch (estado) {
            case 1394:
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
            case 1396:
                if (comentarios == '') {
                    $('#error').show();
                    $('#error').html('Por favor Ingresar Comentarios');
                    $('#comentarios').focus();
                    valida = false;
                }
                break;
            case 393:
                if (comentarios == '') {
                    $('#error').show();
                    $('#error').html('Por favor Ingresar Comentarios');
                    $('#comentarios').focus();
                    valida = false;
                } else if (onbase == '') {
                    $('#error').show();
                    $('#error').html('Por favor Ingresar Número Onbase');
                    $('#onbase').focus();
                    valida = false;
                } else if (fechanotificacion == '') {
                    $('#error').show();
                    $('#error').html('Por favor Ingresar Fecha de Envío');
                    $('#fechanotificacion').focus();
                    valida = false;
                }
                break;
            case 394:
                if (comentarios == '') {
                    $('#error').show();
                    $('#error').html('Por favor Ingresar Comentarios');
                    $('#comentarios').focus();
                    valida = false;
                } else if (devolucion == undefined) {
                    $('#error').show();
                    $('#error').html('Por favor Seleccionar la opción correspondiente');
                    $('#devolucion').focus();
                    valida = false;
                } else if (file == '') {
                    $('#error').show();
                    $('#error').html('Por favor adjuntar archivo PDF');
                    $('#file').focus();
                    valida = false;
                } else if (devolucion == 1 && motivo == '') {
                    $('#error').show();
                    $('#error').html('Por favor Seleccionar Motivo de Devolucón');
                    $('#motivo').focus();
                    valida = false;
                }
                break;
            case 1419:
                if (tr_notificacion_efectiva == '') {
                    $('#error').show();
                    $('#error').html('Por favor ingresa la fecha en la que se hace efectiva la notificación.');
                    $('#adicionales').focus();
                    valida = false;
                }
                else if (file == '') {
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

        }

        if (valida == true) {
            var url = "<?= base_url('index.php/mc_avaluo/gestion_notificacion') ?>";
//            var detalle = "<?php echo serialize($post); ?>";
            //
            //
            // $(".ajax_load,.preload").css('display','block');
            //var informacion = tinymce.get('notificacion').getContent();
            var res = confirm('Seguro desea guardar la información ?');
            if (res == true) {
                var formData = new FormData($("#frmtp")[0]);
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    //una vez finalizado correctamente
                    success: function(data) {
                        $('.conn').html(data);
                        jQuery(".preload, .load").hide();
                    $('#resultado').dialog('close');
                   // $('#resultado *').remove();

                    location.reload();

                    },
                    //si ha ocurrido un error
                    error: function() {
                        $("#ajax_load").hide();
                        alert("Ha Ocurrido un Error");
                    }
                });
            }
        }
    })

</script>