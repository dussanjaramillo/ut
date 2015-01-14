<?php
$attributes = array("id" => "myform");
echo form_open($ruta, $attributes);
echo form_hidden('cod_devolucion', $cod_devolucion);
?>
<input type="hidden" id="id_planillas" name="id_planillas" readonly> 
<input type="hidden" id="valor_planilla" name="valor_planilla" readonly> 
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<br>
<center>
    <br>
    <h1><?php echo $concepto->NOMBRE_CONCEPTO; ?></h1>
    <h2><?php echo 'Pagos de Planilla Unica'; ?></h2>
</center>
<br>
<div id="datos">
    <table id="tablaq">
        <thead>
            <tr>        
                <th>Nro. Radicación</th>
                <th>Fecha Radicación</th>
                <th>Nro. Planilla</th>
                <th>Periodo Pagado</th>
                <th>Fecha de Pago</th>
                <th>Valor Pagado</th>
                <th>Seleccionar</th>
            </tr>
        </thead>
        <tbody>
            <?php
            //var_dump($remisibilidad_seleccionados);die;
            if (!empty($estados_seleccionados)) {
                foreach ($estados_seleccionados as $data) {
                    ?>             
                    <tr>          
                        <td><?= $data->NRO_RADICACION ?></td>
                        <td><?= $data->FECHA_RADICACION ?></td> 
                        <td><?= $data->COD_PLANILLAUNICA ?></td> 
                        <td><?= $data->PERIDO_PAGO ?></td> 
                        <td><?= $data->FECHA__PAGO ?></td> 
                        <td><?php echo '$ ' . number_format($data->TOTAL_APORTES, 0, '.', '.'); ?></td> 
                        <td><input id="list_planillas" name="list_planillas[]" type="checkbox" onclick="recibir(<?= $data->COD_PLANILLAUNICA ?>);" value="<?= $data->COD_PLANILLAUNICA ?>"></td> 
                    </tr>
                    <?php
                }
            }
            ?>
        </tbody>     
    </table>
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

<?php
switch ($concepto->COD_CONCEPTO) {
    case 1:
        ?>
        <div  style="width: 80%; border-color: black; border: 1px solid grey; margin: auto; overflow: hidden;alignment-adjust: central ;padding: 15px 50px 0">
            <center>
                <b>Descuento para Pymes:</b>
                <select id="descuento" name="descuento" class="span5">
                    <option value="0.75">Setenta y cinco por ciento (75%) para el primer año de operación</option>
                    <option value="0.50">Cincuenta por ciento (50%) para el segundo año de operación</option>
                    <option value="0.25"> Veinticinco por ciento (25%) para el tercer año de operación</option>
                </select>
                <br>
            </center>
            <br>
        </div>
        <br>
        <?php
        break;
}
?>
<center>
    <?php
    echo form_button($data_1) . " ";
    echo anchor('devolucion/Menu_GestionDevoluciones', '<i class="fa fa-minus-circle"></i> Cancelar', 'class="btn btn-warning"');
    ?>
</center>

<?php echo form_close(); ?>

<script type="text/javascript" language="javascript" charset="utf-8">

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
        var cod_planillas = $('#id_planillas').val();
        if (cod_planillas != '') {
            if (confirm("¿Desea Confirmar?")) {
                $('#myform').submit();
            }
        } else {
            alert('No se puede calcular la devolucion, no ha seleccionado ninguna planilla');
        }
    });
    $('#tablaq').dataTable({
        "bJQueryUI": true,
        "sPaginationType": "full_numbers",
        "oLanguage": {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix": "",
            "sSearch": "Buscar:",
            "sUrl": "",
            "sInfoThousands": ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "fnInfoCallback": null,
        },
        "sServerMethod": "POST",
        "aoColumns": [
            {"sClass": "center"}, /*id 0*/
            {"sClass": "center"},
            {"sClass": "center"},
            {"sClass": "center"},
            {"sClass": "center"},
            {"sClass": "center"},
            {"sClass": "center"},
        ],
    });
</script> 