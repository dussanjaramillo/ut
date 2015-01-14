<?php
$attributes = array("id" => "myform");
echo form_open_multipart("remisibilidad/Generar_FichaRegistro", $attributes);
$fecha_hoy = date("Y/m/d H:i:s", strtotime("-6 hour"));
echo form_hidden('fecha', $fecha_hoy);
echo form_hidden('expediente', serialize($expediente));
echo form_hidden('cod_remisibilidad', $cod_remisibilidad);
?>
<br><br>
<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>
    <?php
    if (isset($message)) {
        echo $message;
    } else {
        echo "Solo se admiten archivos en PDF";
    }
    ?>
</div>   
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<br> 
<div class="caja_negra" id="caja_negra" style="width: 90%; border-color: black; border: 1px solid grey; margin: auto; overflow: hidden;alignment-adjust: central ;padding: 15px 50px 0">
    <br>
    <span class="span2"><label for="cuenta_contable">Cuenta Contable:</label></span>
    <span class="span3"><?php
        $data = array(
            'name' => 'cuenta_contable',
            'id' => 'cuenta_contable',
            'class' => 'validate[required]',
            'required => required'
        );
        echo form_input($data);
        ?></span>
    <span class="span2"><label for="cod_cuenta_contable">Codigo Cuenta Contable:</label></span>
    <span class="span3"><?php
        $data = array(
            'name' => 'cod_cuenta_contable',
            'id' => 'cod_cuenta_contable',
            'class' => 'validate[required, custom[onlyNumber], maxSize[12]]'
        );
        echo form_input($data);
        ?></span><br><br>
    <span class="span2"><label for="asientocontable">Asiento Contable:</label></span>
    <span class="span3"><?php
        $data = array(
            'name' => 'asiento_contable',
            'id' => 'asiento_contable',
            'class' => 'validate[required]'
        );
        echo form_input($data);
        ?></span>
    <span class="span2"><label for="fecha_asiento">Fecha del Asiento:</label></span>
    <span class="span3"><?php
        $data = array(
            'name' => 'fecha_asiento',
            'id' => 'fecha_asiento',
            'class' => 'validate[required]'
        );
        echo form_input($data);
        ?></span><br><br>    
    <center>
        <br> 
        <?php
        $data = array(
            'name' => 'cargar',
            'id' => 'cargar',
            'content' => '<i class="fa fa-cloud-upload"></i> Anexar Acta de Recomendación',
            'class' => 'btn btn-success cargar'
        );
        echo form_button($data);
        ?>
        <br><br>
        <div id="carga_archivo" class="alert-success"></div>
    </center>
</div>

<table class="table table-striped" style="width: 98.7%; border-color: black; border: 1px solid grey; margin: auto; overflow: hidden;alignment-adjust: central" width="90%" border="1" align="center" cellspacing="0" cellpadding="0">
    <tr>
        <td width="9%" ><center><b>Validado</b></center></td>
<td width="91%"><center><b>Motivos de la Depuración Contable</b></center></td>
</tr>
<?php
if (!empty($condiciones)) {
    $i = 0;
    foreach ($condiciones as $data) {
        ?>
        <tr style=" border: 1px solid grey">
            <td style=" border: 1px solid grey"><div align="center"><br>
                    <?php echo' <input type="checkbox" name="condicion' . $i . '" id="condicion' . $i . '" value="' . $data["COD_CONDICONREMISIBILIDAD"] . '-' . $data["NOMBRE_CONDICION"] . '" />' ?>
                </div><br></td>
            <td style=" border: 1px solid grey"><?php echo $data["NOMBRE_CONDICION"]; ?></td>
        </tr>
        <?php
        $i++;
    }
    echo form_hidden('cc', $i);
}
?>
</table>
<br><br>
<div   style="width: 90%; border-color: black; border: 1px solid grey; margin: auto; overflow: hidden;alignment-adjust: central ;padding: 15px 50px 0">
    <?php
    echo form_label('<center><b>DOCUMENTOS ANEXOS QUE SOPORTAN LA SOLICITUD DE REMISIBILIDAD</b></center><span class="required"></span>', 'lb_comentarios');
    $datadocumentos = array(
        'name' => 'documentos',
        'id' => 'documentos',
        'maxlength' => '2000',
        'class' => 'span11 documentos validate[required]',
        'rows' => '3',
        'required' => 'required'
    );
    echo form_textarea($datadocumentos);
    echo '<br>';
    ?>  
    <br>
</div>
<br><br>
<center>
    <?php
    $data = array(
        'name' => 'button',
        'id' => 'generar',
        'value' => 'seleccionar',
        'type' => 'submit',
        'content' => '<i class="fa fa-floppy-o"></i> Guardar',
        'class' => 'btn btn-success generar'
    );
    echo form_button($data);
    ?>
    <?php echo anchor('remisibilidad', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?> 
    <?php echo form_close(); ?>
    <br>
</center>
<br>
<div id="consulta" ></div>
<script>
    $("#preloadmini").hide();
    jQuery(".preload, .load").hide();
    $('#info_boton').click(function() {
        $(".info").show();
    });
    var cantidad_documentos = 0;
    $("#cargar").click(function() {
        if (cantidad_documentos == 3) {
            alert('Solo se permite la subida de tres archivos');
        } else {
            caja = document.createElement("input");
            caja.setAttribute("type", "file");
            caja.setAttribute("id", "userfile[]");
            caja.setAttribute("name", "userfile[]");
            document.getElementById("carga_archivo").appendChild(caja);
            cantidad_documentos++;
        }
    });
    $("#fecha_asiento").datepicker({
        dateFormat: "yy/mm/dd",
        maxDate: "0"
    });
</script> 