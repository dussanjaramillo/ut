<br><br>
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<br><br>
<div class="caja_negra" id="caja_negra" style="width: 90%; border-color: black; border: 1px solid grey; margin: auto; overflow: hidden;alignment-adjust: central ;padding: 15px 50px 0">
    <?php
    $attributes = array("id" => "myform");
    echo form_open("remisibilidad/Generar_FichaRegistro", $attributes);
    $fecha_hoy = date("Y/m/d H:i:s", strtotime("-6 hour"));
    echo form_hidden('fecha', $fecha_hoy);
    ?>
    <span class="span2"><label for="nit">NIT</label></span>
    <span class="span3"><?php
        $data = array(
            'name' => 'nit',
            'id' => 'nit',
            'value' => $remisibilidad['CODEMPRESA'],
            'readonly' => 'readonly'
        );
        echo form_input($data);
        ?> 
        <img id="preloadmini" src="<?php echo base_url('img/319.gif') ?>" width="28" height="28" />
    </span>
    <span class="span2"><label for="razonsocial">Razon Social</label></span>
    <span class="span3">
        <?php
        $data = array(
            'name' => 'razonsocial',
            'id' => 'razonsocial',
            'class' => 'input-xlarge',
            'value' => $remisibilidad['NOMBRE_EMPRESA'],
            'readonly' => 'readonly'
        );
        echo form_input($data);
        ?>       
    </span><br><br>
    <span class="span2"><label for="concepto">Concepto</label></span>
    <span class="span3">
        <select name="concepto" id="concepto" readonly="readonly"><option val="<?php echo $remisibilidad['COD_CPTO_FISCALIZACION']; ?>"><?php echo $remisibilidad['NOMBRE_CONCEPTO']; ?></option></select>
    </span>
    <span class="span2"><label for="instancia">Instancia</label></span>
    <span class="span3">
        <select name="instancia" id="instancia" readonly="readonly"><option val="<?php echo $remisibilidad['CODPROCESO']; ?>"><?php echo $remisibilidad['NOMBREPROCESO']; ?></option></select>
    </span><br><br>
    <span class="span2"><label for="representante_legal">Representante Legal</label></span>
    <span class="span3"><?php
        $data = array(
            'name' => 'representante_legal',
            'id' => 'representante_legal',
            'class' => 'input-xlarge',
            'value' => $remisibilidad['REPRESENTANTE_LEGAL'],
            'readonly' => 'readonly'
        );
        echo form_input($data);
        ?></span>
    <span class="span2"><label for="telefono">Telefono</label></span>
    <span class="span3"><?php
        $data = array(
            'name' => 'telefono',
            'id' => 'telefono',
            'value' => $remisibilidad['TELEFONO_FIJO'],
            'readonly' => 'readonly'
        );
        echo form_input($data);
        ?></span><br><br>
    <span class="span2"><label for="cuenta_contable">Cuenta Contable:</label></span>
    <span class="span3"><?php
        $data = array(
            'name' => 'cuenta_contable',
            'id' => 'cuenta_contable',
            'value' => $remisibilidad['NOMBRE_CTA_CONTABLE'],
            'readonly' => 'readonly'
        );
        echo form_input($data);
        ?></span>
    <span class="span2"><label for="cod_cuenta_contable">Codigo Cuenta Contable:</label></span>
    <span class="span3"><?php
        $data = array(
            'name' => 'cod_cuenta_contable',
            'id' => 'cod_cuenta_contable',
            'value' => $remisibilidad['CODIGO_CTA_CONTABLE'],
            'readonly' => 'readonly'
        );
        echo form_input($data);
        ?></span><br><br>
    <span class="span2"><label for="asientocontable">Asiento Contable:</label></span>
    <span class="span3"><?php
        $data = array(
            'name' => 'asiento_contable',
            'id' => 'asiento_contable',
            'value' => $remisibilidad['NUM_ASIENTO_CONTABLE'],
            'readonly' => 'readonly'
        );
        echo form_input($data);
        ?></span>
    <span class="span2"><label for="fecha_asiento">Fecha del Asiento:</label></span>
    <span class="span3"><?php
        $data = array(
            'name' => 'fecha_asiento',
            'id' => 'fecha_asiento',
            'value' => $remisibilidad['FECHA_ASIENTO_CONTABLE'],
            'readonly' => 'readonly'
        );
        echo form_input($data);
        ?></span><br><br>    
    <span class="span2"><label for="">Estado</label></span>
    <span class="span3">
        <select name="estado" id="estado" readonly="readonly" class="input-xxlarge"><option val="<?php echo $remisibilidad['COD_GESTION']; ?>"><?php echo $remisibilidad['TIPOGESTION']; ?></option></select>
    </span><br>
</div>
<br> 
<div id="textarea"  style="width: 90%; background: white; border-color: black; border: 1px solid grey; margin: auto; overflow: hidden;alignment-adjust: central ;padding: 15px 50px 0">
    <?php
    $Cabeza = '<br>
    <div align="center">
    <img src="' . base_url('img/Logotipo_SENA.jpg') . '" width="248" height="239" />
    <br><h2>Concepto Remisibilidad</h2>
    </div>';
    echo $Cabeza;
    ?>
    <center>
        <div id="codigo" class="codigo" style="alignment-adjust: central">
            <?php
            $data = array(
                'name' => 'cod_condicion',
                'id' => 'cod_condicion',
                'value' => 'CR'.$cod_remisibilidad,
                'class' => 'input-xlarge',
                'readonly' => 'readonly'
            );
            echo "<h2>Concepto N° </h2>" . form_input($data);
            ?>
        </div>
    </center>
    <textarea id="informacion" name="informacion" style="width: 100%;height: 400px"></textarea>
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
    echo form_label('<b>Procede</b><span class="required"></span>', 'lb_procede');
    $opcion_1 = array(
        'name' => 'id_opcion',
        'id' => 'id_opcion',
        'style' => 'margin: 10px'
    );
    ?>
    <div  style="width: 91%; border-color: black; border: 1px solid grey; margin: auto; overflow: hidden;alignment-adjust: central ;padding: 15px 50px 0">
        <?php
        echo form_radio($opcion_1, 's', 'checked') . " Procede";
        echo "<br>";
        echo form_radio($opcion_1, 'n') . " No Procede";
        ?>
        <br><br>
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
        'content' => '<i class="fa fa-paperclip"></i> Guardar Cambios',
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
        'content' => '<i class="fa fa-paperclip"></i> Guardar Cambios',
        'class' => 'btn  btn-danger enviar'
    );
    $data_4 = array(
        'name' => 'button',
        'id' => 'generar',
        'value' => 'seleccionar',
        'content' => '<i class="fa fa-folder-open"></i> Generar PDF',
        'class' => 'btn btn-success'
    );
    ?>
    <div class="Boton_Agregar">
        <?php
        echo form_button($data_1) . "  " . form_button($data_2) . " ";
        echo anchor('remisibilidad/Lista_Remisibilidad_Sin_Concepto', '<i class="fa fa-reply"></i> Atras', 'class="btn"');
        ?>
    </div>
    <div class="Boton_PDF">
        <?php
        echo form_button($data_3) . "  " . form_button($data_4) . " ";
        echo anchor('remisibilidad/Lista_Remisibilidad_Sin_Concepto', '<i class="fa fa-reply"></i> Atras', 'class="btn"');
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
    $(".codigo").hide();
    $(".Boton_PDF").hide();
    $(".preload, .load").hide();
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
        var informacion = tinymce.get('informacion').getContent();
        var nombre = "Concepto_Remisibilidad";
        var procede = $("input[name='id_opcion']:checked").val();
        var comentarios = $('#comentarios').val();
        var consecutivo = $('#cod_condicion').val();
        var fecha = "<?php echo $fecha_hoy; ?>";
        if (informacion == "" || nombre == "") {
            alert('No se puede continuar hasta que haya realizado el Concepto de la Remisibilidad');
            return false;
        }
        $(".preload, .load").show();
        var url = '<?php echo base_url('index.php/remisibilidad/guardar_archivo') ?>';
        var url_remisibilidad = '<?php echo base_url('index.php/remisibilidad/insertar_rem_concepto') ?>';
        var cod_remisibilidad = "<?php echo $remisibilidad['COD_REMISIBILIDAD']; ?>";
        var nombre_archivo = "<?php echo date("d-m-Y H:i:s"); ?>";
        nombre_archivo = nombre_archivo.replace(":", "_");
        nombre_archivo = nombre_archivo.replace(":", "_");
        nombre_archivo = nombre_archivo.replace(" ", "_");
        nombre_archivo = nombre_archivo + "_" + nombre;
        $.post(url, {nombre2: nombre, nombre: nombre_archivo, informacion: informacion, opcion: "1"})
                .done(function(msg) {
                    alert('Archivo de Concepto de Remisibilidad Guardado con Exito!!!');
                    $(".codigo").show();
                }).fail(function(smg, fail) {
            alert('Error al guardar el Archivo');
            $(".preload, .load").hide();
        });
        $.post(url_remisibilidad, {cod_remisibilidad: cod_remisibilidad, nombre_documento: nombre_archivo, comentarios: comentarios, procede: procede, fecha: fecha, consecutivo:consecutivo})
                .done(function(msg) {
                    $(".preload, .load").hide();
                    $(".Boton_PDF").show();
                    $(".Boton_Agregar").hide();
                }).fail(function(smg, fail) {
            alert('Error al Guardar el Concepto');
            $(".preload, .load").hide();
        });
    });
    $('#generar').click(function() {
        var informacion = tinymce.get('informacion').getContent();
        var Cabeza = $('#Cabeza').val();
        var Consecutivo = '<br><div align="center"><br><h2>RESOLUCION N° ' + $('#cod_condicion').val() + '</h2><br></div>';
        //var Pie = $('#Pie').val();
        var nombre = "Concepto_Remisibilidad";
        informacion = Cabeza + Consecutivo+ informacion;
        if (informacion == "" || nombre == "") {
            alert('No se puede continuar hasta que haya realizado el Concepto de la Remisibilidad');
            return false;
        }
        $(".preload, .load").show();
        var url = '<?php echo base_url('index.php/remisibilidad/pdf') ?>';
        redirect_by_post(url, {
            html: informacion,
            nombre: nombre
        }, true);


        $(".preload, .load").hide();

    })
    $("#preloadmini").hide();
    tinymce.init({
        selector: "textarea#informacion",
        theme: "modern",
        plugins: [
            "advlist autolink lists link image charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code fullscreen",
            "insertdatetime media nonbreaking save table contextmenu directionality",
            "emoticons template paste textcolor moxiemanager"
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