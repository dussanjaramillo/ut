<?php if (isset($message)) echo $message; ?>

<div style="text-align: center">
    <h3>Consulta de la existencia de títulos recibidos</h3>
    <h4>(CIRCULAR JURIDICA No. 00011)</h4>
</div>

<div style="padding-top: 20px;">
    <h5>Lista de Procesos</h5>
</div>

<table id="tabla1">
    <thead>
        <tr>
            <th>Nit</th>
            <th>Empresa</th>
            <th>No Expediente</th>
            <th>Concepto</th>
            <th>Tipo Gestión</th>
            <th>Ver</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($data1->result_array as $data) {
            ?>
            <tr><td><?php echo $data["IDENTIFICACION"] ?></td> 
                <td><?php echo $data["EJECUTADO"] ?></td> 
                <td><?php echo $data["EXPEDIENTE"] ?></td> 
                <td><?php echo $data["CONCEPTO"] ?></td> 
                <td><?php //echo $data["TIPOGESTION"] ?></td> 
                <td align="center">
                    <form name="form" id="form" method="post" action="<?= base_url("index.php/recepciontitulos/listado") ?>" />
                    <input type="hidden" name="nit" id="nit" value="<?php echo $data["IDENTIFICACION"] ?>"/>
                    <input type="hidden" name="expediente" id="expediente" value="<?php echo $data["EXPEDIENTE"] ?>"/>
                    <input type="hidden" name="concepto" id="concepto" value="<?php echo $data["CONCEPTO"] ?>"/>
                    <input type="hidden" name="recepcion_id" id="recepcion_id" value="<?php echo $data["COD_RECEPCION"] ?>"/>
                    <input type="submit" name="Consultar" id="Consultar" value="Consultar"  class="btn btn-success"/>
                    </form>
            </tr>
        <?php } ?>

    </tbody>  
</table>


<script type="text/javascript" language="javascript" charset="utf-8">
    $('#tabla1').dataTable({
        "bJQueryUI": true,
        "sServerMethod": "POST",
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
            }}
    });
</script>
