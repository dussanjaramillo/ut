<?php
if (isset($message)) {
    echo $message;
}
?>

<span><h4 style="color:#5bb75b; text-align: center;">Titulos Ejecutivos</h4></span>

<form name="form1" method="post" id="form1" action="<?php echo $ruta ?>">
    <table id="tabla1">
        <thead>
            <tr><th>Número Proceso</th>
                <th>Identificación</th>
                <th>Ejecutado</th>
                <th>Regional</th>
                <th>Concepto </th>
                <th>Fecha</th>
                <th>Gestion</th>
            </tr>
        </thead> 
        <tbody>

            <?php
            if ($consulta) :
                foreach ($consulta as $data) :
                    ?> 
                    <tr>   
                        <td><?php echo $data['COD_PROCESO'] ?></td>
                        <td><?php echo $data['IDENTIFICACION'] ?></td>
                        <td><?php echo $data['NOMBRE'] ?></td>
                        <td><?php echo $data['NOMBRE_REGIONAL'] ?></td>
                        <td><?php echo $data['NOMBRE_CONCEPTO'] ?></td>
                        <td><?php echo $data['FECHA_CONSULTAONBASE'] ?></td>
                        <td>
                            <form name="form1" id="form1" method="post" action="<?php echo $ruta ?>">
                                <input type="hidden" name="id_recepcion" id="id_recepcion" value="<?php echo $data['COD_RECEPCIONTITULO'] ?>" />
                                <input type="submit" name="Consultar" id="Consultar" value="Consultar" class="btn-success" />
                            </form>
                        </td>
                    </tr>
                <?php
                endforeach;
            endif;
            ?>
        </tbody>      
    </table>


</form>
<div style="text-align: center;padding: 5px 0px 0px 0px;"   >
    <form id="form2" name="form2"  method="post" action="<?php echo $this->data['ruta_bandeja']; ?>">
        <input type="hidden" name="cod_titulo" id="cod_titulo" value="<?php echo $proceso; ?>">
        <input type="submit" name="cancelar" id="cancelar" class="btn btn-warning"value=" Cancelar">
    </form>
</div>
<script type="text/javascript" language="javascript" charset="utf-8">
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

    function f_buscar()
    {
        var num_documento = $("#num_documento").val();
        var num_proceso = $("#num_proceso").val();

    }
    window.history.forward(-1);
</script> <?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

