<br><br>
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<br><br>
<?php
$attributes = array("id" => "myform");
echo form_open("mcremate", $attributes);
$fecha_hoy = date("Y/m/d H:i:s", strtotime("-6 hour"));
echo form_hidden('fecha', $fecha_hoy);
$usuario = $this->ion_auth->user()->row();
$abogado = $usuario->IDUSUARIO;
?>
<div class="info" id="info">
    <?php require_once('encabezado.php'); ?>
</div>
<br>
<div id="textarea" class="textarea"  style="width: 90%; background: white; border-color: black; border: 1px solid grey; margin: auto; overflow: hidden;alignment-adjust: central ;padding: 15px 50px 0">
    <?php
    $Cabeza = '<br>
    <div align="center">
    <img src="' . base_url('img/Logotipo_SENA.png') . '" width="248" height="239" />
    <br><br>
    </div>';
    echo $Cabeza;
    ?>
    <center>
        <?php
        echo "<h2>$tipo</h2>";
        ?>
    </center>
    <div id="cuerpo"  class="cuerpo">
        <textarea id="informacion" name="informacion" style="width: 100%;height: 400px"><?php print_r($filas2); ?></textarea></div>
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
        'class' => 'span11 comentarios validate[required]',
        'rows' => '3',
        'required' => 'required'
    );
    echo form_textarea($datacomentarios);
    echo '<br>';
    echo form_label('<b>Asignar a:<span class="required"></span></b>', 'id_abogado');
    if (!empty($secretario)) {
        foreach ($secretario as $row) {
            $select[$row->IDUSUARIO] = $row->NOMBRES . ' ' . $row->APELLIDOS;
        }
        echo form_dropdown('id_secretario', $select, '', 'id="id_secretario" class="span3" placeholder="seleccione..." ');
    } else {
        echo form_label('No existen abogados de cobro coactivo en la Region');
    }
    ?>
    <br><br>

</div>
<center>
    <br>
    <?php
    $data_1 = array(
        'name' => 'button',
        'id' => 'enviar',
        'value' => 'seleccionar',
        'content' => '<i class="fa fa-paperclip"></i> Elaborar',
        'class' => 'btn  btn-warning enviar'
    );
    $data_2 = array(
        'name' => 'button',
        'value' => 'seleccionar',
        'disabled' => 'disabled',
        'content' => '<i class="fa fa-folder-open"></i> Generar PDF',
        'class' => 'btn btn-success'
    );
    $data_3 = array(
        'name' => 'button',
        'id' => 'enviar',
        'value' => 'seleccionar',
        'disabled' => 'disabled',
        'content' => '<i class="fa fa-paperclip"></i> Elaborar',
        'class' => 'btn  btn-danger enviar'
    );
    $data_4 = array(
        'name' => 'button',
        'id' => 'generar',
        'value' => 'seleccionar',
        'content' => '<i class="fa fa-folder-open"></i> Generar PDF',
        'class' => 'btn btn-success'
    );
    $data_5 = array(
        'name' => 'button',
        'id' => 'info_boton',
        'value' => 'info',
        'content' => '<i class="fa fa-eye"></i> Informacion del Avaluo',
        'class' => 'btn btn-success info_boton'
    );
    ?>

    <div class="Boton_Agregar">
        <?php
        echo form_button($data_5) . "  " . form_button($data_1) . "  " . form_button($data_2) . " ";
        echo anchor('mcremate/Menu_GestionRemateA', '<i class="fa fa-reply"></i> Atras', 'class="btn"');
        ?>
    </div>
    <div class="Boton_PDF">
        <?php
        echo form_button($data_5) . "  " . form_button($data_3) . "  " . form_button($data_4) . " ";
        echo anchor('mcremate/Menu_GestionRemateA', '<i class="fa fa-reply"></i> Atras', 'class="btn"');
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
    $(".info").hide();
    $(".Boton_PDF").hide();
    $(".preload, .load").hide();
    $('#info_boton').click(function() {
        $(".info").show();
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
        var cod_avaluo = "<?php echo $cod_avaluo; ?>";
        var enviado_a = "<?php echo COD_USUARIO; ?>";
        var revisado_por = $('#id_secretario').val();
        var fecha = "<?php echo $fecha_hoy; ?>";
        var nombre_documento = '';
        var comentario = $('#comentarios').val();
        var comentado_por = "<?php echo COD_USUARIO; ?>";
        var informacion = tinymce.get('informacion').getContent();
        var fiscalizacion = "<?php echo $titulo['COD_FISCALIZACION']; ?>";
        var nombre = "<?php echo $tipo; ?>";
        var cod_respuesta = "<?php echo $cod_respuesta; ?>";
        if (informacion == "" || nombre == "" || comentario == "") {
            alert('No se puede continuar hasta que haya completado todos los campos');
            $(".preload, .load").hide();
        } else {
            var url = '<?php echo base_url('index.php/mcremate/guardar_archivo') ?>';
            var url_documentos = '<?php echo base_url('index.php/mcremate/insertar_rem_documentos') ?>';
            var nombre_archivo = "<?php echo date("d-m-Y H:i:s"); ?>";
            nombre_archivo = nombre_archivo.replace(":", "_");
            nombre_archivo = nombre_archivo.replace(":", "_");
            nombre_archivo = nombre_archivo.replace(" ", "_");
            nombre_archivo = nombre_archivo + "_" + nombre;
            $.post(url, {nombre2: nombre, nombre: nombre_archivo, informacion: informacion, opcion: "1", fiscalizacion: fiscalizacion})
                    .done(function(msg) {
                        alert('Archivo <?php echo $tipo; ?> ha sido Guardado con Exito!!!');
                    }).fail(function(smg, fail) {
                alert('Error al guardar el Archivo');
                $(".preload, .load").hide();
            });
            nombre_archivo = nombre_archivo + ".txt";
            $.post(url_documentos, {cod_avaluo: cod_avaluo, abogado: enviado_a, secretario: revisado_por, fecha: fecha, nombre_documento: nombre_archivo, comentario: comentario, comentado_por: comentado_por, cod_respuesta: cod_respuesta})
                    .done(function(msg) {
                        $(".preload, .load").hide();
                        $(".Boton_PDF").show();
                        $(".Boton_Agregar").hide();
                    }).fail(function(smg, fail) {
                $(".preload, .load").hide();
            });
        }
    });
    $('#generar').click(function() {
        var informacion = tinymce.get('informacion').getContent();
        var Cabeza = $('#Cabeza').val();
        var Consecutivo = '<br><div align="center"><br><h2><?php echo $tipo; ?></h2><br></div>';
        var nombre = "<?php echo $tipo . "-" . $cod_avaluo; ?>";
        informacion = Cabeza + Consecutivo + informacion;
        if (informacion == "" || nombre == "") {
            alert('No se puede continuar hasta que haya realizado en cuerpo del documento');
            return false;
        }
        $(".preload, .load").show();
        var url = '<?php echo base_url('index.php/mcremate/pdf') ?>';
        redirect_by_post(url, {
            html: informacion,
            nombre: nombre
        }, true);
        $(".preload, .load").hide();
    });
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
    $("#preloadmini").hide();
</script> 


