<?php
$attributes = array("id" => "myform");
echo form_open("recepciontitulos", $attributes);
$fecha_hoy = date("Y/m/d H:i:s");
echo form_hidden('fecha', $fecha_hoy);
$abogado = COD_USUARIO;
$coordinador = ID_COORDINADOR;
$secretario  = ID_SECRETARIO;
?>
<br><br>
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<br><br>
<div class="info" id="info">
    <?php require_once('encabezado.php'); ?>
</div>
<br> 
<div id="textarea" class="textarea"  style="width: 90%; background: white; border-color: black; border: 1px solid grey; margin: auto; overflow: hidden;alignment-adjust: central ;padding: 15px 50px 0">
    <?php
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
    echo $Cabeza;
    ?>
    <center>
        <?php
        $data = array(
            'name' => 'cod_resolucion',
            'id' => 'cod_resolucion',
            'class' => 'input-xlarge validate[required]'
        );
        ?>
    </center>
    <br>
    <div id="cuerpo"  class="cuerpo">
        <textarea id="informacion" name="informacion" style="width: 100%;height: 400px"><?php print_r($filas2); ?></textarea></div>
    <br>
</div>
<br>
<div class="Comentarios" style="width: 90%; border-color: black; border: 1px solid grey; margin: auto; overflow: hidden;alignment-adjust: central ;padding: 15px 50px 0">
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
<div   style="width: 90%; border-color: black; border: 1px solid grey; margin: auto; overflow: hidden;alignment-adjust: central ;padding: 15px 50px 0">
    <?php
    echo form_label('<b>Comentarios* </b><span class="required"></span>', 'lb_comentarios');
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
        'content' => '<i class="fa fa-paperclip"></i> Corregir',
        'class' => 'btn  btn-success'
    );
    $data_2 = array(
        'name' => 'button',
        'value' => 'seleccionar',
        'disabled' => 'disabled',
        'content' => '<i class="fa fa-print"></i>Imprimir',
        'class' => 'btn btn-info'
    );
    $data_3 = array(
        'name' => 'button',
        'id' => 'enviar',
        'value' => 'seleccionar',
        'disabled' => 'disabled',
        'content' => '<i class="fa fa-paperclip"></i> Corregir',
        'class' => 'btn  btn-danger enviar'
    );
    $data_4 = array(
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
    ?>

    <div class="Boton_Agregar">
        <?php
        echo form_button($data_1) . "  " . form_button($data_4) . " " . form_button($data_5) . " " . form_button($data_7);
        ?>
    </div>
    <div class="Boton_PDF">
        <?php
        echo form_button($data_3) . "  " . form_button($data_4) . " " . form_button($data_5) . " " . form_button($data_7);
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
    $('#ver_comentarios').click(function() {
        $(".Comentarios").show();
    });
    $(".info").hide();
    $('#info_boton').click(function() {
        $(".info").show();
    });
    $('#cancelar').click(function() {
        window.location = "<?= base_url().'index.php/bandejaunificada/procesos' ?>";
    });
    
    $('#comentarios').keyup(function() {
        var max_chars = 300;
        var chars = $(this).val().length;
        var diff = max_chars - chars;
        if (diff <= 0){
            $('#error').show();
            $('#error').html('Excedio el limite de carácteres en el campo comentarios');
            $('#enviar').attr('disabled',true);   
        }else{
            $('#enviar').attr('disabled',false); 
            $('#error').hide();
        }
    }); 
    
    function char_especiales(string){
        var comentarios=string.replace(/[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi,'');
        $('#comentarios').val(comentarios);
    } 
    
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
        var estado = <?php echo $cod_respuesta[0]; ?>;
        var informacion = tinymce.get('informacion').getContent();
        var nombre = "<?php echo $tipo; ?>";
        var comentarios = $('#comentarios').val();
        char_especiales(comentarios);
        var secretario = "<?php echo $secretario; ?>";
        var coordinador = "<?php echo $coordinador; ?>";
        var abogado = "<?php echo $abogado; ?>";
        var cod_coactivo = "<?php echo $cod_coactivo; ?>";
        var encabezado = $("#Titulo_Encabezado").val();
        if (informacion == "" || nombre == "" || comentarios == "") {
            alert('No se puede continuar hasta que haya completado todos los campos');
            $(".preload, .load").hide();
        } else {
            var url = '<?php echo base_url('index.php/acuerdopagojuridicodoc/guardar_archivo') ?>';
            var url_documentos = '<?php echo base_url('index.php/acuerdopagojuridicodoc/insertar_comunicacion') ?>';
            var nombre_archivo = "<?php echo date("d-m-Y H:i:s"); ?>";
            nombre_archivo = nombre_archivo.replace(":", "_");
            nombre_archivo = nombre_archivo.replace(":", "_");
            nombre_archivo = nombre_archivo.replace(" ", "_");
            nombre_archivo = nombre_archivo + "_" + nombre;
            $.post(url, {nombre2: nombre, nombre: nombre_archivo, informacion: informacion, opcion: "1", cod_coactivo: cod_coactivo,asignar:<?= $coordinador ?>})
                    .done(function(msg) {
                        nombre_archivo = nombre_archivo + ".txt";
                        $.post(url_documentos, {encabezado: encabezado, abogado: abogado, coordinador: coordinador, cod_coactivo: cod_coactivo, estado: estado, secretario: secretario, nombre_documento: nombre_archivo, comentarios: comentarios,asignar:<?= $coordinador ?>})
                                .done(function(msg) {
                                    alert('Archivo <?php echo $tipo; ?> ha sido Guardado con Exito!!!');
                                    $(".preload, .load").hide();
                                    $(".Boton_PDF").show();
                                    $(".Boton_Agregar").hide();
                                    window.history.back(-1);
                                }).fail(function(smg, fail) {
                        });
                    }).fail(function(smg, fail) {
                alert('Error al guardar el Archivo');
            });

        }
    });
    $('#generar').click(function() {
        var tipo = '2';
        var informacion = tinymce.get('informacion').getContent();
        var nombre = "<?php echo $tipo . "-" . $cod_coactivo; ?>";
        var encabezado = $("#Titulo_Encabezado").val();
        var estado = "<?php echo $cod_respuesta[0]; ?>";
        if (estado == "<?php echo RESOLUCION_QUE_RESUELVE_CORREGIDO; ?>") {
            tipo = '1';
        }
        if (informacion == "" || nombre == "" || encabezado == "") {
            alert('No se puede continuar hasta que haya realizado en cuerpo del documento');
            return false;
        }
        $(".preload, .load").show();
        var url = '<?php echo base_url('index.php/acuerdopagojuridicodoc/pdf') ?>';
        redirect_by_post(url, {
            html: informacion,
            nombre: nombre,
            titulo: encabezado,
            tipo: tipo
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


