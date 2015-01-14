<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
if (isset($message)) {
    echo $message;
}
if (isset($custom_error))
    echo $custom_error;
?>
<span><h4 style="color:#5bb75b; text-align: center;">Procesos Jurídicos</h4></span>
<form name="form1" method="post" id="form1" action="<?php echo $ruta ?>">
    <table id="tabla1">
        <thead>
            <tr>
                <th>Código Proceso</th>
                <th>Identificación Ejecutado</th>
                <th>Nombre Ejecutado</th>
                <th>Regional</th>
                <th>Nombre Proceso</th> 
                <th>Estado</th>
                <th>Fecha</th>
                <th>Documentos</th>
            </tr>
        </thead> 
        <tbody>
            <?php
            if ($consulta) {
//                echo "<PRE>";
//                print_r($consulta);
//                echo "</pre>";die();
                foreach ($consulta as $data) {
                    ?> 
                    <tr> 
                        <td><?php echo $data['COD_PROCESO'] ?></td>
                        <td><?php echo $data['IDENTIFICACION'] ?></td>
                        <td><?php echo $data['EJECUTADO'] ?></td>
                        <td><?php echo $data['NOMBRE_REGIONAL'] ?></td>
                        <td><?php echo $data['TIPO_PROCESO'] ?></td>
                        <td><?php echo $data['RESPUESTA'] ?></td>
                        <td>
                            <form name="form1" id="form1" method="post" target="blank" action="<?php echo $ruta ?>">
                                <input type="hidden" name="cod_coactivo" id="cod_coactivo" value="<?php echo $data['COD_PROCESO_COACTIVO'] ?>" />
                                <button name="boton" type="submit" > <i class="fa fa-folder-open-o" ></i></button>
                            </form>
                        </td>
                    </tr>
                    <?php
                }
            }
            ?>
        </tbody>      
    </table>
</form>
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
    // window.history.forward(-1);
</script> <?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

