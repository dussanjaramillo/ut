<?php
if (isset($message)) {
    echo $message;
}
if (isset($custom_error))
    echo $custom_error;
?>  
<table id="tabla1">
    <thead>
    <th>N° Proceso</th>
    <th>Regional</th>
    <th>Ejecutado</th>
    <th>Nit</th>
    <th>Estado</th>
    <th>Concepto</th>
    <th>Gestión</th>
</thead>
<tbody>
    <?php
    if ($consulta):
        foreach ($consulta as $data):
            ?>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <?php
        endforeach;
    endif;
    ?>

</tbody>    


</table>