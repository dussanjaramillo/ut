<?php
$attributes = array("id" => "myform");
echo form_open("devolucion/Ingreso_DevolucionPersonas", $attributes);
echo form_hidden('cod_devolucion', $cod_devolucion);
?>
<input type="hidden" id="id_planillas" name="id_planillas" readonly> 
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<br>
<center>
    <br>
    <h1><?php echo $concepto->NOMBRE_CONCEPTO; ?></h1>
    <h2><?php echo 'Pagos de Planilla Unica'; ?></h2>
</center>
<br>
<div class="alert alert-warning alert-dismissable">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>¡Cuidado!</strong> para acceder a cada empleado debe dar click en la planilla correspondiente.
</div>
<div   style=" background: white; width: 80%; border-color: black; border: 1px solid grey; margin: auto; overflow: hidden;alignment-adjust: central ;padding: 15px 50px 0">
    <div class="accordion" id="accordion2">
        <?php
        for ($i = 1; $i < sizeof($planillas); $i++) {
            ?>
            <div class="accordion-group">
                <div class="accordion-heading">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#<?php echo $i; ?>">
                        <?php echo 'Planilla N° ' . $planillas[$i] ?>
                    </a>
                </div>
                <div id="<?php echo $i; ?>" class="accordion-body collapse in">
                    <div class="accordion-inner">
                        <?php
                        if (sizeof($personas_planillas[$i]) > 1) {
                            ?>
                            <table width="100%" border="1" align="center" class="table table-bordered table-striped">
                                <tr>
                                    <td><div align="center"><strong>Identificación</strong></div></td>
                                    <td><div align="center"><strong>Primer Apellido</strong></div></td>
                                    <td><div align="center"><strong>Segundo Apellido</strong></div></td>
                                    <td><div align="center"><strong>Primer Nombre</strong></div></td>
                                    <td><div align="center"><strong>Segundo Nombre</strong></div></td>
                                    <td><div align="center"><strong>IBC</strong></div></td>
                                    <td><div align="center"><strong>Aporte Obligatorio</strong></div></td>
                                    <td><div align="center"><strong>Seleccionar</strong></div></td>
                                </tr>
                                <?php
                                for ($j = 1; $j < sizeof($personas_planillas[$i]); $j++) {
                                    ?>
                                    <tr>
                                        <td><?php echo $personas_planillas[$i][$j]->N_IDENT_COTIZ; ?></td>
                                        <td><?php echo $personas_planillas[$i][$j]->PRIMER_APELLIDO; ?></td>
                                        <td><?php echo $personas_planillas[$i][$j]->SEGUN_APELLIDO; ?></td>
                                        <td><?php echo $personas_planillas[$i][$j]->PRIMER_NOMBRE; ?></td>
                                        <td><?php echo $personas_planillas[$i][$j]->SEGUN_NOMBRE; ?></td>
                                        <td><?php echo "$" . number_format($personas_planillas[$i][$j]->ING_BASE_COTIZ, 0, '.', '.'); ?></td>
                                        <td><?php echo "$" . number_format($personas_planillas[$i][$j]->APORTE_OBLIG, 0, '.', '.'); ?></td>
                                        <?php
                                        
                                        if ($concepto->COD_CONCEPTO == '44') {
                                            if ($personas_planillas[$i][$j]->ING_BASE_COTIZ < $salario) {
                                                ?>                                        
                                                <td><div align="center"><input readonly="readonly" checked="checked" type="checkbox" name="personas[]" id="personas" value="<?php echo $personas_planillas[$i][$j]->N_IDENT_COTIZ . ';' . $personas_planillas[$i][$j]->COD_PLANILLAUNICA . ';' . $personas_planillas[$i][$j]->ING_BASE_COTIZ . ';' . $personas_planillas[$i][$j]->APORTE_OBLIG; ?>; ?>" /></div></td>
                                            <?php } else { ?>
                                                <td><div align="center"><input readonly="readonly" type="checkbox" name="personas[]" id="personas" value="<?php echo $personas_planillas[$i][$j]->N_IDENT_COTIZ . ';' . $personas_planillas[$i][$j]->COD_PLANILLAUNICA . ';' . $personas_planillas[$i][$j]->ING_BASE_COTIZ . ';' . $personas_planillas[$i][$j]->APORTE_OBLIG; ?>; ?>" /></div></td>
                                            <?php } ?>      
                                        <?php } else { ?>
                                            <td><div align="center"><input type="checkbox" name="personas[]" id="personas" value="<?php echo $personas_planillas[$i][$j]->N_IDENT_COTIZ . ';' . $personas_planillas[$i][$j]->COD_PLANILLAUNICA . ';' . $personas_planillas[$i][$j]->ING_BASE_COTIZ . ';' . $personas_planillas[$i][$j]->APORTE_OBLIG; ?>; ?>" /></div></td>
                                                <?php } ?>
                                    </tr>

                                    <?php
                                }
                                ?>
                            </table>
                            <?php
                        } else {
                            echo '<b>La Planilla no Tiene Detalle</b>';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>

    </div>





</div>
<?php
$data_1 = array(
    'name' => 'button',
    'id' => 'enviar',
    'value' => 'Enviar',
    'content' => '<i class="fa fa-floppy-o fa-lg"></i> Confirmar',
    'class' => 'btn btn-success enviar'
);
?>
<br>


<center>
    <?php
    echo form_button($data_1) . " ";
    echo anchor('devolucion/Menu_GestionDevoluciones', '<i class="fa fa-minus-circle"></i> Cancelar', 'class="btn btn-warning"');
    ?>
</center>

<?php echo form_close(); ?>

<script type="text/javascript" language="javascript" charset="utf-8">
    $(".collapse").collapse('hide');
    //generación de la tabla mediante json
    jQuery(".preload, .load").hide();
    $('#cancelar').click(function() {
        window.history.back(-1);
    });

    function recibir($id) {
        var cod_planillas = $('#id_planillas').val();
        cod_planillas = cod_planillas + "-" + $id;
        $('#id_planillas').val(cod_planillas);
    }

    $('.enviar').click(function() {
        var check = $("input[type='checkbox']:checked").length;
        if (check == 0) {
            alert('No se puede calcular la devolucion, no ha seleccionado ningun Empleado');
        } else {
            if (confirm("¿Desea Confirmar?")) {
                $('#myform').submit();
            }
        }
    });
</script> 