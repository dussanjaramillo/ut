<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
if (isset($message)) {
    echo $message;
}
if (isset($custom_error))
    echo $custom_error;
?> 
<?php
/*
 * Lista los autos de terminación de proceso para un proceso coactivo
 */
?>
<h3><center>Terminación Proceso</center></h3>
<table id="tabla1">
    <thead>
    <th>N° Proceso</th>
    <th>Regional</th>
    <th>Ejecutado</th>
    <th>Identificación</th>
    <th>Estado</th>
    <th>Concepto</th>
    <th>Gestión</th>
</thead>
<tbody>
    <?php foreach ($consulta as $data): ?>
        <tr>
            <td><?php echo $data['PROCESOPJ']; ?></td>
            <td><?php echo $data['NOMBRE_REGIONAL']; ?></td> 
            <td><?php echo $data['NOMBRE']; ?></td>
            <td><?php echo $data['IDENTIFICACION']; ?></td>
            <td><?php echo $data['RESPUESTA']; ?></td>
            <td><?php echo $data['CONCEPTO']; ?></td>
            <td>
                <input class="push" type="radio" name="gestion" onclick="fvistas()"  />
            </td>
        </tr>    
    <?php endforeach; ?>
</tbody>    
</table>
<br><br>

<?php /* * Fin de la Vista */ ?>
<script>
    $('#tabla1').dataTable({
        "bJQueryUI": true,
        "sPaginationType": "full_numbers",
        "sServerMethod": "POST",
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
    });
</script>