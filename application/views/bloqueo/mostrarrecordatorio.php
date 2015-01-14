<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<!--
    /**
     * Formulario que muestra las consulta de recordatorios
     * Se muestra la informacion generada por la consulta de Recordatorios o Timers
     * @author Haider Oviedo Martinez :: Thomas MTI
     * @since 04 III 2014 
     */
-->
<?php
    if (count($datos) <= 0) {
        echo '<h1>No Hay Recordatorios Para el Usuario</h1>';
        echo '<br>';
        echo '<table id="recuerda1" width="100%" style="f">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Texto Recordatorio</th>';
        echo '<th>Numero Gesti&oacute;n Cobro</th>';
        echo '<th>Fecha Creaci&oacute;n</th>';
        echo '<th>Fecha Vencimiento</th>';
        echo '<th>Estado</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        echo '<tr><td></td><td></td><td></td><td></td><td></td></tr>';
        echo '</tbody>';
        echo '</table>';
        echo '<br>';
        ?>
        <script>
            $('#recuerda1').dataTable({
                "bJQueryUI": true,
                "sPaginationType": "full_numbers",
                //"bFilter": false,            

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
                        "sFirst": "|Primero|",
                        "sLast": "|Último|",
                        "sNext": "|Siguiente|",
                        "sPrevious": "|Anterior|"
                    },
                    "fnInfoCallback": null,
                },
            });
        </script> 
    <?php
    
    }else{
 ?>
        <table id="recuerda" width="100%" style="f">
            <thead>
                <tr>
                    <th>Proceso y Gesti&oacute;n</th>
                    <th>C&oacute;digo de Gesti&oacute;n</th>
                    <th>Fecha de Creaci&oacute;n</th>
                    <th>Fecha de Vencimiento</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
<?php

    $oculto = $datos[0]['IDUSUARIO'];
    $oculto1 = $datos[0]['NOMBRES'];
    $oculto2 = $datos[0]['APELLIDOS'];
?>    
    <h3>Recordatorios para el usuario 
    <?php //echo $oculto; echo' '; 
    echo $oculto1." ".$oculto2; ?>
    </h3>
<?php    
foreach($datos as $col){    
    if(!empty($col['URLGESTION'])){
        $gestion = "<a href='".base_url().'index.php/'.$col['URLGESTION']."'>".$col['TIPOGESTION']."</a>";
    }else{
        $gestion = $col['TIPOGESTION'];
    }
    
    if(!empty($col['NOMBRE_GESTION'])){
        $respuesta = "<ul><li>".$col['NOMBRE_GESTION']."</li></ul>";
    }else{
        $respuesta = "";
    }
?>
                <tr id="tr_<?= $col['IDUSUARIO'] ?>" >
                    <td id='TEXTO'><ul><li><?= $col['NOMBREPROCESO'] ?><ul><li><?= $gestion ?><?= $respuesta ?></li></ul></ul></td>
                    <!--td id='TEXTO'><?= $col['TEXTO'] ?></td-->
                    <td id="COD_GESTION_COBRO"><?= $col['COD_GESTION_COBRO'] ?></td>
                    <td id="FECHA_CREACION"><?= $col['FECHA_CREACION'] ?></td>
                    <td id="FECHA_VENCIMIENTO"><?= $col['FECHA_VENCIMIENTO'] ?></td>
                    <td align="center" id='ACTIVO'><?php
                        if ($col['ACTIVO'] == 'S') {
                            echo 'Activo';
                        } else {
                            echo 'Inactivo';
                        }
                        ?>
                    </td>
                </tr>
                <?php
                    }
                ?>
            </tbody>    
        </table>
        <script>
            $('#recuerda').dataTable({
                "bJQueryUI": true,
                "sPaginationType": "full_numbers",
                //"bFilter": false, 

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
                        "sFirst": "|Primero|",
                        "sLast": "|Último|",
                        "sNext": "|Siguiente|",
                        "sPrevious": "|Anterior|"
                    },
                    "fnInfoCallback": null,
                },
            });

        </script>
<?php
    }   
?>