    
<?php
$dataonbase = array('name' => 'onbase', 'id' => 'onbase', 'type' => 'number', 'required' => 'true');
$dataidenti = array('name' => 'identificacion', 'id' => 'identificacion', 'type' => 'number', 'required' => 'true', 'class' => 'opcional');
$tipo_devol = array('name' => 'tipo_devolucion', 'id' => 'tipo_devolucion', 'type' => 'hidden', 'value' => $tipo_devolucion);
$code_empre = array('name' => 'code_empre', 'id' => 'code_empre', 'type' => 'hidden');
$dataradicado = array('name' => 'fecha_radicado', 'id' => 'fecha_radicado', 'type' => 'text', 'required' => 'true');
$data = array('name' => 'comentarios', 'id' => 'comentarios', 'value' => set_value('comentarios'), 'width' => '100%', 'heigth' => '5%', 'style' => 'width:98%', 'onKeyUp' => 'return longitudMax(this,200)', 'required' => 'true');
$dataguardar = array('name' => 'button', 'id' => 'guardar', 'value' => 'Guardar', 'type' => 'button', 'content' => '<i class="fa fa-floppy-o fa-lg"></i> Guardar', 'class' => 'btn btn-success');
$datacancel = array('name' => 'button', 'id' => 'cancel-button', 'value' => 'Cancelar', 'type' => 'button', 'onclick' => 'window.location=\'' . base_url() . 'index.php/devolucion/devoluciones_generadas\';', 'content' => '<i class="fa fa-minus-circle"></i> Cancelar', 'class' => 'btn btn-warning');
$attributes = array('name' => 'devolucionFrm', 'id' => 'devolucionFrm', 'class' => 'form-inline', 'method' => 'POST', 'enctype' => 'multipart/form-data');
echo form_open_multipart(current_url(), $attributes);
echo form_input($tipo_devol);
echo form_input($code_empre);
?>
<div class="caja_negra" id="caja_negra" style="width: 60%; background: #F8F8F8; border-color: black; border: 1px solid grey; margin: auto; overflow: hidden;alignment-adjust: central ;padding: 15px 50px 0">
    <br>
    <table align='center'>
        <tr align='center'>
            <td align='center' colspan='2'><?= $titulo ?></td>
        </tr><tr><td><br></td></tr>
        <tr>
            <td><b>Radicado Onbase</b><span><font color="red"><b>*</b></font></span></td>
            <td><?= form_input($dataonbase) ?></td>                    
        </tr><tr><td><br></td></tr>   
        <tr>
            <td><b>Identificacion</b><span><font color="red"><b>*</b></font></span></td>
            <td><?= form_input($dataidenti) ?><img style="display:none" id="loadm" src="<?php echo base_url('img/319.gif') ?>" width="28" height="28" /></td>                    
        </tr><tr><td><br></td></tr>   
        <tr>
            <td><b>Fecha Radicado</b><span><font color="red"><b>*</b></font></span></td>
            <td><?= form_input($dataradicado) ?></td>                    
        </tr><tr><td><br></td></tr> 
        <?php
        if (REGIONAL_USER != '1') {
            ?>
            <tr>            
                <td>                
                    <?php echo form_label('<b>Asignar a&nbsp</b><span><font color="red"><b>*</b></font></span>', 'asignando'); ?> 
                </td>
                <td>
                    <select name="asignado" id="asignado" required>
                        <option value=''>--Seleccione--</option>
                        <?php
                        foreach ($asignado as $row) {
                            echo '<option value="' . $row->IDUSUARIO . '">' . $row->NOMBRES . ' ' . $row->APELLIDOS . '</option>';
                        }
                        ?>
                    </select>            
                </td>            
            </tr><tr><td><br></td></tr>    
            <?php
        }
        ?>
        <tr>            
            <td>                
                <?php echo form_label('<b>Concepto&nbsp</b><span><font color="red"><b>*</b></font></span>', 'conceptos'); ?> 
            </td>
            <td>
                <select name="conceptos" id="conceptos" required>
                    <option value=''>--Seleccione--</option>
                    <?php
                    foreach ($conceptos as $rows) {
                        echo '<option value="' . $rows->COD_CONCEPTO . '">' . $rows->NOMBRE_CONCEPTO . '</option>';
                    }
                    ?>
                </select>            
            </td>            
        </tr><tr><td><br></td></tr>  
        <tr>            
            <td>                
                <?php echo form_label('<b>Motivo de Devolución&nbsp</b><span><font color="red"><b>*</b></font></span>', 'devoluciones'); ?> 
            </td>
            <td>
                <select name="devoluciones" id="devoluciones" required>
                    <option value=''>--Seleccione--</option>
                    <?php
                    foreach ($devoluciones as $rowsd) {
                        echo '<option value="' . $rowsd->COD_MOT_DEVOLUCION_DINERO . '">' . $rowsd->NOMBRE_MOTIVO . '</option>';
                    }
                    ?>
                </select>            
            </td>            
        </tr><tr><td><br></td></tr>
        <tr>
        <p>
            <td align='left'><?= '<b>Comentarios&nbsp;</b><span class="required"><font color="red"><b>*</b></font></span>' ; ?></td>                    
            <td align='right'> (Max. 200 Caracteres)&nbsp;</td>
        </p>            
    </tr>
    <tr>
        <td colspan='2'><?=
            form_textarea($data); ?>
        </td>
        
    </tr>
    <tr><td><br></td></tr> 
<tr>
    <td colspan='2' align='center'>
        <div style="display:none" id="error" class="alert alert-danger"></div>
    </td>
</tr>
<tr align='center'>
    <td align='center' colspan='2'>
        <?=
        form_button($dataguardar) . "&nbsp;&nbsp;";
        echo form_button($datacancel);
        ?>
    </td>
</tr>
</table>
<br>
</div>
<div id="ajax_load" class="ajax_load" style="display: none">
    <div class="preload" id="preload" >
        <img  id="load" class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
    </div>
</div>
<?php echo form_close(); ?>
<script type="text/javascript" charset="UTF-8">
    $("#identificacion").autocomplete({
        source: "<?php echo base_url("index.php/devolucion/buscar_empresa") ?>",
        minLength: 1,
        search: function(event, ui) {
            $("#loadm").show();
        },
        response: function(event, ui) {
            $("#loadm").hide();
        }
    });

    $("#identificacion").blur(function() {
        var nit = $("#identificacion").val();
        var url = "<?php echo base_url("index.php/devolucion/traerempresa") ?>";
        var codempresa = '';
        $.ajax({
            type: "POST",
            url: url,
            data: {nit: nit},
            success:
                    function(data) {
                        var cantidad = data.length;
                        if (cantidad == 4) {
                            codempresa = 0;
                        } else {
                            codempresa = 1;
                        }
                        $('#code_empre').val(codempresa);
                    }
        });
    });
    $("#fecha_radicado").datepicker({
        dateFormat: "yy/mm/dd",
        dayNamesMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
        dayNamesShort: ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"],
        monthNames:
                ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio",
                    "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
        monthNamesShort:
                ["Ene", "Feb", "Mar", "Abr", "May", "Jun",
                    "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
        maxDate: "0"
    });
    
   
    $('#guardar').click(function() {
        var onbase = $('#onbase').val();
        var asignado = $('#asignado').val();
        var comentarios = $('#comentarios').val();
        var identificacion = $('#identificacion').val();
        var fecha_radicado = $('fecha_radicado').val();
        var conceptos = $('#conceptos').val();
        var devoluciones = $('#devoluciones').val();
        var code_empre = $('#code_empre').val();
        var url = '<?php echo base_url('index.php/devolucion/guardar_devolucion') ?>';
        var valida = true;

        if (onbase == '') {
            $('#error').show();
            $('#onbase').focus();
            $('#error').html("<font color='red'><b>Ingrese el valor Onbase</b></font>");
            valida = false;
        } else if (identificacion == '') {
            $('#error').show();
            $('#identificacion').focus();
            $('#error').html("<font color='red'><b>Por Favor Ingrese Identificación de Solicitante</b></font>");
            valida = false;
        } else if (code_empre == 0) {
            $('#error').show();
            $('#identificacion').focus();
            $('#error').html("<font color='red'><b>La Empresa no se ha ingresado en la base de datos</b></font>");
            valida = false;
        } else if (fecha_radicado == '') {
            $('#error').show();
            $('#num_radicado').focus();
            $('#error').html("<font color='red'><b>Por Favor Ingrese Fecha de Radicado</b></font>");
            valida = false;
        } else if (asignado == '') {
            $('#error').show();
            $('#asignado').focus();
            $('#error').html("<font color='red'><b>Por Favor Asignar la Comunicación</b></font>");
            valida = false;
        } else if (conceptos == '') {
            $('#error').show();
            $('#conceptos').focus();
            $('#error').html("<font color='red'><b>Por Favor Seleccionar el Concepto</b></font>");
            valida = false;
        } else if (comentarios == '') {
            $('#error').show();
            $('#comentarios').focus();
            $('#error').html("<font color='red'><b>Por Favor Ingrese Comentarios</b></font>");
            valida = false;
        } else if (devoluciones == '') {
            $('#error').show();
            $('#devoluciones').focus();
            $('#error').html("<font color='red'><b>Por Favor Seleccione el Motivo de Devolución</b></font>");
            valida = false;
        }

        if (valida == true) {
            $(".ajax_load").show("slow");
            $('#guardar').prop("disabled", true);
            $("#devolucionFrm").attr("action", url);
            $('#devolucionFrm').removeAttr('target');
            $('#devolucionFrm').submit();
        }
    });
</script>
