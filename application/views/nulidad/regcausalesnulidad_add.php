<?php
$attributes = array("id" => "myform");
echo form_open_multipart("nulidad/Verificar_Causales", $attributes);
echo form_hidden('cod_coactivo_nulidad', $cod_coactivo);
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
<br><br>
<div class="info" id="info">
    <?php require_once('encabezado.php'); ?>
</div>
<br> 
<div class="caja_negra" id="caja_negra" style="width: 90%; border-color: black; border: 1px solid grey; margin: auto; overflow: hidden;alignment-adjust: central ;padding: 15px 50px 0">
    <br>   
    <center>    
        <h3>Estudio de Nulidad</h3><br>
        <div class="condicion" id="condicion" style="width: 90%; border-color: black; border: 1px solid grey; margin: auto; overflow: hidden;alignment-adjust: central ;padding: 15px 50px 15px">
            <?php
            $data = array(
                'name' => 'peticion',
                'id' => 'peticion',
                'value' => '0',
                'style' => 'margin:10px',
                'onclick' => 'visualizar(\'0\')'
            );

            echo (form_radio($data) . 'A Petición De Parte');
            $data = array(
                'name' => 'peticion',
                'id' => 'peticion',
                'value' => '1',
                'style' => 'margin:10px',
                'onclick' => 'visualizar(\'1\')'
            );

            echo (form_radio($data) . 'De Oficio');
            ?>
        </div>
        <br>
        <div class="nulidad" id="nulidad" style="width: 90%; background: #F9F9F9; border-color: black; border: 1px solid grey; margin: auto; overflow: hidden;alignment-adjust: central ;padding: 15px 50px 15px">
            <?php
            $data = array(
                'name' => 'procede',
                'id' => 'procede',
                'value' => '0',
                'style' => 'margin:10px',
            );
            echo (form_radio($data) . 'Procede');
            $data = array(
                'name' => 'procede',
                'id' => 'procede',
                'value' => '1',
                'style' => 'margin:10px',
            );

            echo (form_radio($data) . 'No Procede');
            ?>
        </div>
        <br>
        <div class="carga" id="carga">
            <?php
            $data = array(
                'name' => 'cargar',
                'id' => 'cargar',
                'content' => '<i class="fa fa-cloud-upload"></i> Anexar Soporte de la Nulidad',
                'class' => 'btn btn-success cargar'
            );
            echo form_button($data);
            ?>
            <br><br>
            <div id="carga_archivo" class="alert-success"></div>
            <br>
        </div>
    </center>
</div>

<table class="table table-striped" style="width: 98.7%; border-color: black; border: 1px solid grey; margin: auto; overflow: hidden;alignment-adjust: central" width="90%" border="1" align="center" cellspacing="0" cellpadding="0">
    <tr>
        <td width="9%" ><center><b>Validado</b></center></td>
<td width="91%"><center><b>Causales de Nulidad</b></center></td>
</tr>
<?php
if (!empty($causales)) {
    foreach ($causales as $data) {
        ?>
        <tr style=" border: 1px solid grey">
            <td style=" border: 1px solid grey"><div align="center"><br>
                    <?php echo' <input type="checkbox" name="causales[]" id="causales[]" value="' . $data["COD_CAUSAL_NULIDAD"] . '" />' ?>
                </div><br></td>
            <td style=" border: 1px solid grey"><?php echo $data["NOMBRE_CAUSAL"]; ?></td>
        </tr>
        <?php
    }
}
?>
</table>
<br><br>
<center>
    <?php
    $data = array(
        'name' => 'button',
        'id' => 'Enviar',
        'value' => 'seleccionar',
        'content' => '<i class="fa fa-floppy-o"></i> Guardar',
        'class' => 'btn btn-success Enviar'
    );
    $data_5 = array(
        'name' => 'button',
        'id' => 'info_boton',
        'value' => 'info',
        'content' => '<i class="fa fa-eye"></i> Informacion del Ejecutado',
        'class' => 'btn btn-primary info_boton'
    );
    echo form_button($data_5) . "  " . form_button($data);
    ?>
    <?php echo anchor('remisibilidad', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?> 
    <?php echo form_close(); ?>
    <br>
</center>
<br>
<div id="consulta" ></div>
<script>
    $("#preloadmini").hide();
    $(".info").hide();
    $(".nulidad").hide();
    $("#carga").hide();
    jQuery(".preload, .load").hide();
    var cantidad = 0;
    $('#info_boton').click(function() {
        $(".info").show();
    });
    function visualizar(valor) {
        $("#nulidad").show();
        $("#carga").show();
    }
    $('#Enviar').click(function() {
        if (cantidad > 0) {
            if ($("#userfile").val().length > 0) {
                $('#myform').submit();
                setTimeout(function() {
                    alert("La nulidad fue registrada correctamente");
                    jQuery(".preload, .load").hide();
                }, 2000);
            } else {
                alert("Debe adjuntar el documento que soporta la Nulidad");
            }
        } else {
            alert("Debe adjuntar el documento que soporta la Nulidad");
        }

    });
    $("#cargar").click(function() {
        cantidad++;
        if (cantidad > 3) {
            alert('Solo se Admiten Tres Documentos Adjuntos');
        } else {
            caja = document.createElement("input");
            caja.setAttribute("type", "file");
            caja.setAttribute("id", "userfile");
            caja.setAttribute("name", "userfile[]");
            document.getElementById("carga_archivo").appendChild(caja);
        }
    });
</script> 