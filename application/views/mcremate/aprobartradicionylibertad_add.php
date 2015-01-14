
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<?php
$attributes = array("id" => "myform");
echo form_open("mcremate", $attributes);
$fecha_hoy = date("Y/m/d H:i:s");
echo form_hidden('fecha', $fecha_hoy);
$usuario = $this->ion_auth->user()->row();
$abogado = $usuario->IDUSUARIO;
?>
<br>
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
        $data = array(
            'name' => 'cod_resolucion',
            'id' => 'cod_resolucion',
            'class' => 'input-xlarge validate[required]'
        );
        echo "<h2>$tipo</h2>";
        ?>
    </center>
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
        echo '<b>Fecha del Comentario: </b>' . $comentario[$i]["FECHA_CREACION"] . '<br>';
        echo '<b>Hecho Por: </b>' . $comentario[$i]["NOMBRES"] . " " . $comentario[$i]["APELLIDOS"] . '<br>';
        echo $comentario[$i]["COMENTARIOS"];
        echo "<br><br>";
        echo '</div>';
        echo "<br>";
    }
    ?>
</div>
<br>

<div style="width: 90%; border-color: black; border: 1px solid grey; margin: auto; overflow: hidden;alignment-adjust: central ;padding: 15px 50px 0">
    <?php echo form_label('<div style="color: red"><b><h3>Asignación del Proceso:</h3></b></div><span class="required"></span>', 'lb_comentarios'); ?>
    <b>Abogado de Cobro Coactivo Asignado: </b><?php echo $nombre_a; ?><br>
    <b>Secretario de Cobro Coactivo Asignado: </b><?php echo $nombre_s; ?><br>
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
    <?php
    echo '<div class="subir_documento" id="subir_documento">';
    echo form_label('<b>Documento Imprimible Firmado<span class="required"></span></b><br>', 'lb_cargar1');
    echo '<div class="alert-success" style="width: 91%; border-color: black; border: 1px solid grey;padding: 15px 50px 0">';
    $data = array(
        'multiple' => '',
        'name' => 'userfile[]',
        'id' => 'imagen',
        'class' => 'validate[required]'
    );
    echo form_upload($data);
    echo '<br><br>';
    echo '</div>';
    echo '<br>';
    echo '</div>';
    ?>

</div>
<center>
    <br>
    <?php
    $data_info = array(
        'name' => 'button',
        'id' => 'data_info',
        'value' => 'seleccionar',
        'content' => '<i class="fa fa-eye"></i> Información del Avaluó',
        'class' => 'btn  btn-success data_info'
    );
    $data_1 = array(
        'name' => 'button',
        'id' => 'enviar',
        'value' => 'seleccionar',
        'content' => '<i class="fa fa-paperclip"></i> Confirmar',
        'class' => 'btn  btn-warning enviar'
    );
    $data_3 = array(
        'name' => 'button',
        'id' => 'enviar',
        'value' => 'seleccionar',
        'disabled' => 'disabled',
        'content' => '<i class="fa fa-paperclip"></i> Confirmar',
        'class' => 'btn  btn-danger enviar'
    );
    $data_2 = array(
        'name' => 'button',
        'id' => 'generar',
        'value' => 'seleccionar',
        'content' => '<i class="fa fa-print"></i>Imprimir',
        'class' => 'btn btn-success'
    );
    $data_5 = array(
        'name' => 'button',
        'id' => 'ver_comentarios',
        'value' => 'seleccionar',
        'content' => '<i class="fa fa-comment"></i> Historial de Comentarios',
        'class' => 'btn btn-success'
    );
    ?>
    <div class="Boton_Agregar">
        <?php
        echo form_button($data_info) . "  " . form_button($data_1) . "  " . form_button($data_2) . "  " . form_button($data_5) . " ";
        echo anchor('mcremate/Menu_GestionRemateA', '<i class="fa fa-reply"></i> Atras', 'class="btn"');
        ?>
    </div>
    <div class="Boton_PDF">
        <?php
        echo form_button($data_info) . "  " . form_button($data_2) . "  " . form_button($data_5) . " ";
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
    $(".Boton_PDF").hide();
    $(".preload, .load").hide();
    $(".Comentarios").hide();
    $(".info").hide();
    $("#data_info").click(function() {
        $(".info").show();
    });
    jQuery("#subir_documento").hide();
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
        var director = "<?php echo $director; ?>";
        var secretario = "<?php echo $secretario; ?>";
        var abogado = "<?php echo $abogado; ?>";
        var comentado_por = "<?php echo COD_USUARIO; ?>";
        var informacion = tinymce.get('informacion').getContent();
        var nombre = "<?php echo $tipo; ?>";
        var comentario = $('#comentarios').val();
        var fiscalizacion = "<?php echo $titulo['COD_FISCALIZACION']; ?>";
        var fecha = "<?php echo $fecha_hoy; ?>";
        if (informacion == "" || nombre == "" || comentario == "") {
            alert('No se puede continuar hasta que haya completado todos los campos');
            $(".preload, .load").hide();
        } else {
            var url = '<?php echo base_url('index.php/mcremate/guardar_archivo') ?>';
            var url_documentos = '<?php echo base_url('index.php/mcremate/insertar_rem_documentos') ?>';
            var cod_avaluo = "<?php echo $cod_avaluo; ?>";
            var nombre_archivo = "<?php echo date("d-m-Y H:i:s"); ?>";
            var nombre_documento = '';
            nombre_archivo = nombre_archivo.replace(":", "_");
            nombre_archivo = nombre_archivo.replace(":", "_");
            nombre_archivo = nombre_archivo.replace(" ", "_");
            nombre_archivo = nombre_archivo + "_" + nombre;
            nombre_documento = nombre_archivo + ".txt";
            $.post(url, {nombre2: nombre, nombre: nombre_archivo, informacion: informacion, opcion: "1", fiscalizacion: fiscalizacion})
                    .done(function(msg) {
                        alert('Se ha completado el proceso con éxito!!');
                    }).fail(function(smg, fail) {
                alert('Error al guardar el Archivo');
                $(".preload, .load").hide();
            });
            $.post(url_documentos, {cod_avaluo: cod_avaluo, abogado: abogado, secretario: secretario, director: director, fecha: fecha, nombre_documento: nombre_documento, comentario: comentario, comentado_por: comentado_por, cod_respuesta: estado})
                    .done(function(msg) {
                        $(".Boton_PDF").show();
                        $(".Boton_Agregar").hide();
                        $(".preload, .load").hide();
                    }).fail(function(smg, fail) {
                $(".preload, .load").hide();
            });
        }

    });
    $('#ver_comentarios').click(function() {
        $(".Comentarios").show();
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
        } else {
            $(".preload, .load").show();
            var url = '<?php echo base_url('index.php/recepciontitulos/pdf') ?>';
            redirect_by_post(url, {
                html: informacion,
                nombre: nombre
            }, true);
            $(".preload, .load").hide();
        }
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


