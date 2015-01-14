<?php
$attributes = array("id" => "myform", "class" => "myform");
echo form_open_multipart("recepciontitulos/Soporte_Expediente", $attributes);
$fecha_hoy = date("Y/m/d H:i:s");
?>
<br><br>
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<br><br>
<div class="info" id="info">
    <?php require_once('encabezado.php'); ?>
</div>
<br> 
<br>
<div class="titulos_view" id="titulos_view">
    <?php require_once('titulosacumulados_view.php'); ?>
</div>
<div id="textarea" class="textarea"  style="width: 90%; background: white; border-color: black; border: 1px solid grey; margin: auto; overflow: hidden;alignment-adjust: central ;padding: 15px 50px 0">
    <center>
        <?php
        switch ($tipo) {
            case TIPO_2:
                $Cabeza = '
        <br>
        <table width="100%" border="0" align="center">
        <tr>
            <td width="23%" rowspan="2"><img src="' . base_url('img/Logotipo_SENA.png') . '" width="200" height="200" /></td>
            <td width="44%" height="94"><div align="center"><h4><b>' . $tipo . '</b></h4></div></td>
        </tr>
        </table>    
    ';
                break;
            default:
                $Cabeza = '
        <br>
        <table width="100%" border="0" align="center">
        <tr>
            <td width="23%" rowspan="2"><img src="' . base_url('img/Logotipo_SENA.png') . '" width="200" height="200" /></td>
            <td width="44%" height="94"><div align="center"><h4><b>' . $tipo . '</b></h4></div></td>
        </tr>
        <tr>
          <td height="50" colspan="2"><div align="center">
            <h2>"<input name="Titulo_Encabezado" value="' . $titulo_encabezado . '" type="text" id="Titulo_Encabezado" class="input-xxlarge" size="100" />"</h2>
          </div></td>
        </tr>
        </table>    
    ';
                break;
        }
        echo $Cabeza;
        ?>
    </center>
    <br>
    <div id="cuerpo"  class="cuerpo">
        <textarea id="informacion" name="informacion" style="width: 100%;height: 400px"><?php print_r($filas2); ?></textarea></div>
    <br>
</div>
<br>
<div class="Comentarios"   style="width: 90%; border-color: black; border: 1px solid grey; margin: auto; overflow: hidden;alignment-adjust: central ;padding: 15px 50px 0">
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
<br>
<div style="width: 90%; border-color: black; border: 1px solid grey; margin: auto; overflow: hidden;alignment-adjust: central ;padding: 15px 50px 0">
    <?php echo form_label('<b><h3>Asignacion del Proceso:</h3></b><span class="required"></span>', 'lb_comentarios'); ?>
    <b>Abogado de Cobro Coactivo Asignado: </b><?php echo $nombre_a; ?><br>
    <b>Secretario de Cobro Coactivo Asignado: </b><?php echo $nombre_s; ?><br>
    <b>Director o Coordinador Asignado: </b><?php echo $nombre_c; ?><br>
    <br>
</div>
<br>
<div  style="width: 90%; border-color: black; border: 1px solid grey; margin: auto; overflow: hidden;alignment-adjust: central ;padding: 15px 50px 0">
    <?php
    echo form_label('<b>Comentarios</b><span class="required"></span>', 'lb_comentarios');
    $datacomentarios = array(
        'name' => 'comentarios',
        'id' => 'comentarios',
        'maxlength' => '300',
        'class' => 'span11 comentarios',
        'rows' => '3',
        'required' => 'required'
    );
    echo form_textarea($datacomentarios);
    echo '<br>';
    echo form_label('<b>Aprobado</b><span class="required"></span>', 'lb_aprobado');
    $opcion_1 = array(
        'name' => 'id_opcion',
        'id' => 'id_opcion',
        'style' => 'margin: 10px'
    );
    ?>
    <div  style="width: 91%; border-color: black; border: 1px solid grey; margin: auto; overflow: hidden;alignment-adjust: central ;padding: 15px 50px 0">
        <?php
        echo '<div class="opciones" id= "opciones">';
        echo form_radio($opcion_1, $cod_respuesta[0]) . " Si";
        echo "<br>";
        echo form_radio($opcion_1, $cod_respuesta[1], 'checked') . " No";
        echo '</div>';
        ?>
        <br>
    </div>
    <br>


</div>
<center>
    <br>
    <?php
    $data_1 = array(
        'name' => 'button',
        'id' => 'enviar',
        'value' => 'seleccionar',
        'content' => '<i class="fa fa-paperclip"></i> Enviar',
        'class' => 'btn  btn-success'
    );
    $data_3 = array(
        'name' => 'button',
        'id' => 'enviar',
        'value' => 'seleccionar',
        'disabled' => 'disabled',
        'content' => '<i class="fa fa-paperclip"></i> Enviar',
        'class' => 'btn  btn-danger'
    );
    $data_2 = array(
        'name' => 'button',
        'id' => 'generar',
        'value' => 'seleccionar',
        'content' => '<i class="fa fa-print"></i>Imprimir',
        'class' => 'btn btn-info'
    );
    $data_5 = array(
        'name' => 'button',
        'id' => 'ver_comentarios',
        'value' => 'seleccionar',
        'content' => '<i class="fa fa-comment"></i> Historial de Comentarios',
        'class' => 'btn btn-info'
    );
    $data_6 = array(
        'name' => 'button',
        'id' => 'info_boton',
        'value' => 'info',
        'content' => '<i class="fa fa-eye"></i> Informacion del Ejecutado',
        'class' => 'btn btn-info'
    );
    $data_7 = array(
        'name' => 'button',
        'id' => 'cancelar',
        'value' => 'info',
        'content' => '<i class="fa fa-minus-circle"></i> Cancelar',
        'class' => 'btn btn-warning'
    );
    $data_10 = array(
        'name' => 'rechazo',
        'id' => 'rechazo',
        'value' => 'rechazo',
        'content' => '<i class="fa fa-minus-circle"></i> Gestionar Rechazo',
        'class' => 'btn btn-warning'
    );
    $data_8 = array(
        'name' => 'button',
        'id' => 'info_view',
        'value' => 'info',
        'content' => '<i class="fa fa-eye"></i> Informacion de los Titulos',
        'class' => 'btn btn-info'
    );
    $boton_titulos = array(
        'name' => 'button',
        'id' => 'boton_titulos',
        'value' => 'info',
        'content' => '<i class="fa fa-eye"></i> Informacion de los Titulos',
        'class' => 'btn btn-info'
    );
    $boton_ejecutado = array(
        'name' => 'button',
        'id' => 'boton_ejecutado',
        'value' => 'info',
        'content' => '<i class="fa fa-eye"></i> Informacion del Ejecutado',
        'class' => 'btn btn-info'
    );
    $boton_enviar = array(
        'name' => 'button',
        'id' => 'boton_enviar',
        'value' => 'seleccionar',
        'content' => '<i class="fa fa-paperclip"></i> Enviar',
        'class' => 'btn  btn-success'
    );
    $boton_rechazo = array(
        'name' => 'rechazo',
        'id' => 'boton_rechazo',
        'value' => 'rechazo',
        'content' => '<i class="fa fa-minus-circle"></i> Gestionar Rechazo',
        'class' => 'btn btn-warning'
    );
    $boton_imprimir = array(
        'name' => 'button',
        'id' => 'boton_imprimir',
        'value' => 'seleccionar',
        'content' => '<i class="fa fa-print"></i>Imprimir',
        'class' => 'btn btn-info'
    );
    $boton_comentarios = array(
        'name' => 'button',
        'id' => 'boton_comentarios',
        'value' => 'seleccionar',
        'content' => '<i class="fa fa-comment"></i> Historial de Comentarios',
        'class' => 'btn btn-info'
    );
    $boton_cancelar = array(
        'name' => 'button',
        'id' => 'boton_cancelar',
        'value' => 'info',
        'content' => '<i class="fa fa-minus-circle"></i> Cancelar',
        'class' => 'btn btn-warning'
    );
    ?>
    <div class="Boton_Agregar">
        <?php
        echo form_button($data_8) . "  " . form_button($data_6) . "  " . form_button($data_1) . "  " . form_button($data_2) . "  " . form_button($data_5) . "  " . form_button($data_7);
        ?>
    </div>
    <div class="Boton_Rechazo">
        <?php
        echo form_button($boton_titulos) . "  " . form_button($boton_ejecutado) . "  " . form_button($boton_enviar) . "  " . form_button($boton_rechazo) . "  " . form_button($boton_imprimir) . "  " . form_button($boton_comentarios) . "  " . form_button($boton_cancelar);
        ?>
    </div>
    <div class="Boton_PDF">
        <?php
        echo form_button($data_8) . "  " . form_button($data_6) . "  " . form_button($data_3) . "  " . form_button($data_2) . "  " . form_button($data_5) . "  " . form_button($data_7);
        ?>
    </div>
    <input type="hidden" id="Cabeza" value='<?php echo $Cabeza ?>'/>
    <?php
    echo form_close();
    ?>
    <br>
</center>
<br>
<div id="consulta" ></div>
<script>
    $('#boton_titulos').click(function() {
        $(".titulos_view").show();
    });
    $('#boton_ejecutado').click(function() {
        $(".info").show();
    });
    $('#boton_enviar').click(function() {
        $(".preload, .load").show();
        var estado = $("input[name='id_opcion']:checked").val();
        var secretario = "<?php echo $secretario; ?>";
        var abogado = "<?php echo $abogado; ?>";
        var informacion = tinymce.get('informacion').getContent();
        var nombre = "<?php echo $tipo; ?>";
        var comentarios = $('#comentarios').val();
        var coactivo = "<?php echo $cod_coactivo; ?>";
        var encabezado = $("#Titulo_Encabezado").val();
        if (estado == "<?php echo $cod_respuesta[0]; ?>") {
            var coordinador = "<?php echo $coordinador; ?>";
            if (coordinador == '') {
                coordinador = $('#id_coordinador').val();
            }
        } else {
            var coordinador = "<?php echo $coordinador; ?>";
        }
        if (informacion == "" || nombre == "" || comentarios == "") {
            alert('No se puede continuar hasta que haya completado todos los campos');
            $(".preload, .load").hide();
        } else {
            var url = '<?php echo base_url('index.php/recepciontitulos/guardar_archivo') ?>';
            var url_documentos = '<?php echo base_url('index.php/recepciontitulos/insertar_comunicacion') ?>';
            var nombre_archivo = "<?php echo date("d-m-Y H:i:s"); ?>";
            nombre_archivo = nombre_archivo.replace(":", "_");
            nombre_archivo = nombre_archivo.replace(":", "_");
            nombre_archivo = nombre_archivo.replace(" ", "_");
            nombre_archivo = nombre_archivo + "_" + nombre;
            $.post(url, {nombre2: nombre, nombre: nombre_archivo, informacion: informacion, opcion: "1", cod_coactivo: coactivo})
                    .done(function(msg) {
                        nombre_archivo = nombre_archivo + ".txt";
                        $.post(url_documentos, {encabezado: encabezado, abogado: abogado, coordinador: coordinador, cod_coactivo: coactivo, estado: estado, secretario: secretario, nombre_documento: nombre_archivo, comentarios: comentarios})
                                .done(function(msg) {
                                    alert('Archivo <?php echo $tipo; ?> ha sido Guardado con Exito!!!');
                                    window.location = '<?= base_url('index.php/bandejaunificada/procesos') ?>';
                                }).fail(function(smg, fail) {
                        });
                    }).fail(function(smg, fail) {
                alert('Error al guardar el Archivo');
                $(".preload, .load").hide();
            });

        }

    });
    $('#boton_rechazo').click(function() {
        $(".preload, .load").show();
        var coactivo = "<?php echo $cod_coactivo; ?>";
        var url_rechazo = '<?php echo base_url('index.php/recepciontitulos/Gestionar_Rechazo') ?>';
        if (confirm("¿Gestionar El Rechazo a Instancia Administrativa?")) {
            if (confirm("¿Confirma Gestionar una solicitud de Rechazo del Titulo?")) {
                $.post(url_rechazo, {cod_coactivo: coactivo})
                        .done(function(msg) {
                            alert('Se ha Gestionado Correctamente la Solicitud de Rechazo');
                            window.location = '<?= base_url('index.php/bandejaunificada') ?>';
                        }).fail(function(smg, fail) {
                });
            }
        }
    });
    $(".Boton_Rechazo").hide();
    var naturaleza = "<?php echo $tipo ?>";
    if (naturaleza == "<?php echo TIPO_1 ?>") {
        $(".Boton_PDF").hide();
        $(".Boton_Agregar").hide();
        $(".Boton_Rechazo").show();
    }
    $(".Boton_PDF").hide();
    $(".preload, .load").hide();
    $(".Comentarios").hide();
    $(".info").hide();
    $('#info_boton').click(function() {
        $(".info").show();
    });
    $(".titulos_view").hide();
    $('#info_view').click(function() {
        $(".titulos_view").show();
    });
    $('#rechazo').click(function() {
        $(".preload, .load").show();
        var coactivo = "<?php echo $cod_coactivo; ?>";
        var url_rechazo = '<?php echo base_url('index.php/recepciontitulos/Gestionar_Rechazo') ?>';
        if (confirm("¿Desea Confirmar?")) {
            $.post(url_rechazo, {cod_coactivo: coactivo})
                    .done(function(msg) {
                        alert('Archivo <?php echo $tipo; ?> ha sido Guardado con Exito!!!');
                        window.location = '<?= base_url('index.php/bandejaunificada') ?>';
                    }).fail(function(smg, fail) {
            });
        }
    });
    jQuery("#subir_documento").hide();
    $('.opciones').click(function() {
        var estado = $("input[name='id_opcion']:checked").val();
        if (estado == "<?php echo $cod_respuesta[0]; ?>") {
            jQuery("#subir_documento").show();
        } else {
            jQuery("#subir_documento").hide();
        }
    });
    $('#cancelar').click(function() {
        window.history.back(-1);
    });
    function redirect_by_post(purl, pparameters, in_new_tab) {
        pparameters = (typeof pparameters == 'undefined') ? {} : pparameters;
        in_new_tab = (typeof in_new_tab == 'undefined') ? true : in_new_tab;
        var form = document.createElement("form");
        $(form).attr("id", "reg-form").attr("name", "reg-form").attr("action", purl).attr("method", "post").attr("enctype", "multipart/form-data");
        if (in_new_tab) {
            $(form).attr("target", "_blank");
        }
        $.each(pparameters, function(key) {
            $(form).append('<textarea name="' + key + '" >' + this + '</textarea>');
        });
        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);
        return false;
    }

    $('#enviar').click(function() {
        $(".preload, .load").show();
        var estado = $("input[name='id_opcion']:checked").val();
        var secretario = "<?php echo $secretario; ?>";
        var abogado = "<?php echo $abogado; ?>";
        var informacion = tinymce.get('informacion').getContent();
        var nombre = "<?php echo $tipo; ?>";
        var comentarios = $('#comentarios').val();
        var coactivo = "<?php echo $cod_coactivo; ?>";
        var encabezado = $("#Titulo_Encabezado").val();
        if (estado == "<?php echo $cod_respuesta[0]; ?>") {
            var coordinador = "<?php echo $coordinador; ?>";
            if (coordinador == '') {
                coordinador = $('#id_coordinador').val();
            }
        } else {
            var coordinador = "<?php echo $coordinador; ?>";
        }
        if (informacion == "" || nombre == "" || comentarios == "") {
            alert('No se puede continuar hasta que haya completado todos los campos');
            $(".preload, .load").hide();
        } else {
            var url = '<?php echo base_url('index.php/recepciontitulos/guardar_archivo') ?>';
            var url_documentos = '<?php echo base_url('index.php/recepciontitulos/insertar_comunicacion') ?>';
            var nombre_archivo = "<?php echo date("d-m-Y H:i:s"); ?>";
            nombre_archivo = nombre_archivo.replace(":", "_");
            nombre_archivo = nombre_archivo.replace(":", "_");
            nombre_archivo = nombre_archivo.replace(" ", "_");
            nombre_archivo = nombre_archivo + "_" + nombre;
            if (confirm("¿Confirma la Aprobacion de <?php echo $tipo; ?> ?")) {
                $.post(url, {nombre2: nombre, nombre: nombre_archivo, informacion: informacion, opcion: "1", cod_coactivo: coactivo})
                        .done(function(msg) {
                            nombre_archivo = nombre_archivo + ".txt";
                            $.post(url_documentos, {encabezado: encabezado, abogado: abogado, coordinador: coordinador, cod_coactivo: coactivo, estado: estado, secretario: secretario, nombre_documento: nombre_archivo, comentarios: comentarios})
                                    .done(function(msg) {
                                        alert('Archivo <?php echo $tipo; ?> ha sido Guardado con Exito!!!');
                                        window.location = '<?= base_url('index.php/bandejaunificada/procesos') ?>';
                                    }).fail(function(smg, fail) {
                            });
                        }).fail(function(smg, fail) {
                    alert('Error al guardar el Archivo');
                    $(".preload, .load").hide();
                });
            }

        }

    });
    $('#ver_comentarios').click(function() {
        $(".Comentarios").show();
    });
    $('#boton_comentarios').click(function() {
        $(".Comentarios").show();
    });

    $('#generar').click(function() {
        var tipo = '2';
        var estado = "<?php echo $tipo ?>";
        switch (estado) {
            case "<?php echo TIPO_1; ?>" :
                tipo = '2';
                break;
            default:
                tipo = '3';
                break;
        }
        var informacion = tinymce.get('informacion').getContent();
        var nombre = "<?php echo $tipo . "-" . $cod_coactivo; ?>";
        var cod_coactivo = "<?php echo $cod_coactivo ?>";
        var encabezado = $("#Titulo_Encabezado").val();
        if (informacion == "" || nombre == "" || encabezado == "") {
            alert('No se puede continuar hasta que haya realizado en cuerpo del documento');
            return false;
        }
        $(".preload, .load").show();
        var url = '<?php echo base_url('index.php/recepciontitulos/pdf') ?>';
        redirect_by_post(url, {
            html: informacion,
            nombre: nombre,
            titulo: encabezado,
            tipo: tipo,
            cod_coactivo: cod_coactivo
        }, true);
        $(".preload, .load").hide();
    });
    $('#boton_imprimir').click(function() {
        var tipo = '2';
        var estado = "<?php echo $tipo ?>";
        switch (estado) {
            case "<?php echo TIPO_1; ?>" :
                tipo = '2';
                break;
            default:
                tipo = '3';
                break;
        }
        var informacion = tinymce.get('informacion').getContent();
        var nombre = "<?php echo $tipo . "-" . $cod_coactivo; ?>";
        var cod_coactivo = "<?php echo $cod_coactivo ?>";
        var encabezado = $("#Titulo_Encabezado").val();
        if (informacion == "" || nombre == "" || encabezado == "") {
            alert('No se puede continuar hasta que haya realizado en cuerpo del documento');
            return false;
        }
        $(".preload, .load").show();
        var url = '<?php echo base_url('index.php/recepciontitulos/pdf') ?>';
        redirect_by_post(url, {
            html: informacion,
            nombre: nombre,
            titulo: encabezado,
            tipo: tipo,
            cod_coactivo: cod_coactivo
        }, true);
        $(".preload, .load").hide();
    });
    $("#preloadmini").hide();
    tinymce.init({
        language: 'es',
        selector: "textarea#informacion",
        theme: "modern",
        plugins: [
            "advlist autolink lists link  charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code fullscreen",
                    //"insertdatetime media nonbreaking save table contextmenu directionality",
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
    $(".Boton_PDF").hide();

</script> 


