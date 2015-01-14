<?php
$attributes = array("id" => "myform");
echo form_open('remisibilidad/Crear_OficioAdministrativa', $attributes);
?>
<input type="hidden" id="expediente" name="expediente" readonly> 
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<br>
<center>
    <br>
    <h1><?php echo 'Obligaciones de la Empresa' ?></h1>
</center>
<br>
<div id="datos">
    <table id="tablaq">
        <thead>
            <tr>        
                <th>Nro. Fiscalización</th>
                <th>Nombre Obligación</th>
                <th>Liquidación</th>
                <th>Fecha de Liquidacion</th>
                <th>Valor Deuda</th>                
                <th>Seleccionar</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($titulos)) {
                foreach ($titulos as $data) {
                    ?>             
                    <tr>          
                        <td><?php echo $data['COD_FISCALIZACION']; ?></td>
                        <td><?= $data['CONCEPTO'] ?></td> 
                        <td><?= $data['NUM_LIQUIDACION'] ?></td> 
                        <td><?= $data['FECHA_LIQUIDACION'] ?></td> 
                        <td><?php echo '$ ' . number_format($data['VALOR_DEUDA'], 0, '.', '.'); ?></td> 
                        <td><input id="list_expediente" name="list_expediente[]" type="checkbox" onclick="recibir('<?= $data['COD_FISCALIZACION'] ?>');" value="<?= $data['COD_FISCALIZACION'] ?>"></td> 
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
<center>
    <?php
    echo form_button($data_1) . " ";
    echo anchor('remisibilidad/Menu_DeudaEmpresas', '<i class="fa fa-minus-circle"></i> Cancelar', 'class="btn btn-warning"');
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
        var expediente = $('#expediente').val();
        expediente = expediente + "-" + $id;
        $('#expediente').val(expediente);
    }
    $('.enviar').click(function() {
        var expediente = $('#expediente').val();
        if (expediente != '') {
            if (confirm("¿Desea Confirmar?")) {
                $('#myform').submit();
            }
        } else {
            alert('No se puede crear la resolucion por que no ha seleccionado ningun Titulo');
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
        ],
    });
</script> 