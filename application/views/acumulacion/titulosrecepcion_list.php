
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<br><br>
<div class="info" id="info">
    <?php require_once('encabezado.php'); ?>
</div>
<br> 
<br>
<div class="titulos_view" id="titulos_view">
    <?php require_once('titulosacumulados_view.php'); ?>
</div>
<center>
    <br>
    <h1>Acumulación de Titulos</h1>
    <h2><?php echo 'Gestión de Actividades ' . $grupo; ?></h2>

</center>
<br>
<div id="datos">
    <table id="tablaq">
        <thead>
            <tr>        
                <th>Num. Expediente</th>
                <th>Obligación</th>
                <th>Fecha de Recepción</th>                
                <th>Proceso</th>
                <th>Estado</th>
                <th>Gestionar</th>
            </tr>
        </thead>
        <tbody>
            <?php
            //var_dump($remisibilidad_seleccionados);die;
            if (!empty($estados_seleccionados)) {
                foreach ($estados_seleccionados as $data) {
                    ?> 
                    <tr>          
                        <td><?= $data->EXPEDIENTE ?></td>
                        <td><?= $data->OBLIGACION ?></td> 
                        <td><?= $data->FECHA_RECEPCION ?></td> 
                        <td><?= $data->PROCESO ?></td> 
                        <td><?= $data->ESTADO ?></td> 
                        <td><input type="checkbox" onclick="recibir(<?= $data->COD_RECEPCIONTITULO ?>);"></td> 
                        <?php
                    }
                    ?>  
                </tr>
                <?php
            }
            ?>
        </tbody>     
    </table>
</div>
<?php
$attributes = array("id" => "myform");
echo form_open('acumulaciontitulos/Acumular_titulos', $attributes);
?>
<input type="hidden" id="identificacion" name="identificacion"> 
<input type="hidden" id="origen" name="origen"> 
<input type="hidden" id="id_titulos" name="id_titulos" readonly /> 
<?php echo form_close(); ?>
<br>
<?php
$data_1 = array(
    'name' => 'button',
    'id' => 'enviar',
    'value' => 'Enviar',
    'content' => '<i class="fa fa-floppy-o fa-lg"></i> Confirmar',
    'class' => 'btn btn-success enviar'
);
$data_2 = array(
    'name' => 'button',
    'id' => 'info_boton',
    'value' => 'info',
    'content' => '<i class="fa fa-eye"></i> Informacion del Ejecutado',
    'class' => 'btn btn-info'
);
$data_3 = array(
    'name' => 'button',
    'id' => 'info_view',
    'value' => 'info_view',
    'content' => '<i class="fa fa-eye"></i> Informacion de los Titulos',
    'class' => 'btn btn-info'
);
?>
<br>
<center>
    <?php
    echo form_button($data_2) . " " . form_button($data_3) . " " . form_button($data_1) . " ";
    echo anchor('acumulaciontitulos/ListadoEmpresas', '<i class="fa fa-minus-circle"></i> Cancelar', 'class="btn btn-warning"');
    ?>
</center>

<script type="text/javascript" language="javascript" charset="utf-8">

//generación de la tabla mediante json
    jQuery(".preload, .load").hide();
    $(".info").hide();
    $('#info_boton').click(function() {
        $(".info").show();
    });
    $(".titulos_view").hide();
    $('#info_view').click(function() {
        $(".titulos_view").show();
    });
    function recibir($id) {
        var cod_titulos = $('#id_titulos').val();
        cod_titulos = cod_titulos + "-" + $id;
        $('#id_titulos').val(cod_titulos);
    }
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
    $('.enviar').click(function() {
        var cod_titulos = $('#id_titulos').val();
        if (cod_titulos != '') {
            if (confirm("¿Desea Confirmar?")) {
                $('#myform').submit();
            }
        } else {
            alert('No se puede realizar la acumulación, no ha seleccionado ningun titulo');
        }
    });

</script> 