<!--
    /**
     * Formulario que muestra las consulta de recordatorios o Timers
     * Se muestra la informacion generada por la consulta de Recordatorios o Timers
     * @author Haider Oviedo Martinez :: Thomas MTI
     * @since 26 II 2013 
     */
-->
<!--<pre>
<?php print_r($datos) ?>
</pre>-->
<?php
if ($tiponoti == 'pre') {
    ?>
    <h1>Gesti&oacute;n de Recordatorios</h1>
    <br>
    <?php
} else {
    ?>
    <h1>Gesti&oacute;n de Timers</h1>
    <?php
}
?>
<?php
if (count($datos) <= 0) {
    echo '<table id="notifica1" width="100%">';
    echo '<thead>';
    echo '<tr>';
    echo '<th>Nombre Actividad</th>';
    
    if ($tiponoti == 'pre') {
                 echo '<th>Nombre Recordatorio</th>';
                 
                }else{

                    echo '<th>Nombre Timer</th>';
                
                }
    echo '<th>Estado</th>';
    echo '<th>Roles</th>';
    echo '<th>Nombre Plantilla</th>';
    echo '<th>Tiempo</th>';
    echo '<th>Editar</th>';
    echo '<th>Eliminar</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
    echo '<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
    echo '</tbody>';
    echo '</table>';
    echo '<br>';
    ?>
    <table style="float:right">
        <td>
            <form action= "<?php echo base_url('index.php/gestionwf/notificaciones'); ?>" method="post">
                <button class="btn btn-success" style="height: 30px; width: 100px; display: block; margin: 0 auto 0 auto"><i class="fa fa-plus-circle"> Agregar</i> </button>
                <input type="hidden" name="tipogestion" id="tipogestion" value="<?php echo $gestion; ?>">
                <input type="hidden" name="tipogestion1" id="tipogestion1" value="<?php echo $gestionar; ?>">
                <input type="hidden" name="tiponoti" id="tiponoti" value="<?php echo $tiponoti; ?>">
            </form>

        <td>
            <form action= "<?php echo base_url('index.php/gestionwf/'); ?>" method="post" >
                <button class="btn btn-normal" style="height: 30px; width: 100px; display: block; margin: 0 auto 0 auto"><i class="fa fa-arrow-circle-left"> Volver</i></button>
            </form>
        </td>    
    </table>    

    <script>
        $('#notifica1').dataTable({
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            //"bFilter": false,            

        });

        function ajaxValidationCallback(status, form, json, options) {
        }

    </script>        

    <?php
} else {
    ?>

    <table id="notifica" width="100%">
        <thead>
            <tr>
                <th style="display:none">Codigo Recordatorio</th>
                <th style="display:none">Codigo Actividad</th>
                <th>Nombre Actividad</th>
    <?php
    if ($tiponoti == 'pre') {
        ?>   
                    <th>Nombre Recordatorio</th>
                    <?php
                }else{
                ?>
                    <th>Nombre Timer</th>
                <?php    
                }
                ?>
                <th>Estado</th>
                <th style="display:none">Id Rol</th>
                <th>Roles</th>
                <th style="display:none">Plantilla</th>
                <th>Nombre Plantilla</th>
                <th>Tiempo</th>
                <th>Editar</th>
                <th>Eliminar</th>

            </tr>
        </thead>
        <tbody>

    <?php
    $idcod = array();
    foreach ($datos as $col) {
        if (!in_array($col['CODRECORDATORIO'], $idcod)) {
            $idcod[] = $col['CODRECORDATORIO'];
            $oculto2 = $col['CODRECORDATORIO'];
            ?>
                    <tr id="tr_<?php echo $col['CODRECORDATORIO']; ?>" >
                        <td id='CODRECORDATORIO' style="display:none"><?php echo $col['CODRECORDATORIO']; ?></td>

                        <td style="display:none"><?php echo $col['CODACTIVIDAD']; ?></td>
                        <td><?php echo $col['TIPOGESTION']; ?></td>
                        <td><?php echo $col['NOMBRE_RECORDATORIO']; ?></td>
                        <td align="center" id='ACTIVO'>
            <?php
            if ($col['ACTIVO'] == 'S') {
                echo 'Activo';
            } else {
                echo 'Inactivo';
            }
            ?>
                        </td>
                        <td id='IDUSUARIO' style="display:none" ><?php echo $col['IDGRUPO']; ?></td>

                        <td id="NOMUSUARIO">
            <?php
            $iduser = $users = array();
            foreach ($datos as $col2) {
                if ($col['CODRECORDATORIO'] == $col2['CODRECORDATORIO']) {
                    if (!in_array($col2['IDGRUPO'], $iduser)) {
                        $users[] = $col2['NOMBREGRUPO'];
                        $iduser[] = $col2['IDGRUPO'];
                    }
                }
            }
            ?>
                            <?php
                            echo "<li style='list-style-type:circle'>";
                            echo implode("<br><li style='list-style-type:circle'>", $users);
                            ?>
                        </td>


                        <td id='CODPLANTILLA' style="display:none"><?php echo $col['CODPLANTILLA']; ?></td>

                        <td><?php echo $col['NOMBRE_PLANTILLA']; ?></td>



                        <td id='TIEMPO_NUM' ><?php echo $col['TIEMPO_NUM']; ?>
            <?php
            if ($col['TIEMPO_NUM'] == 1) {
                switch ($col['TIEMPO_MEDIDA']) {
                    case 'd':
                        echo "Dia";
                        break;
                    case 'dc':
                        echo "Dia Calendario";
                        break;
                    case 'm':
                        echo "Mes";
                        break;
                    case 'a':
                        echo "Año";
                        break;
                    case 's':
                        echo "Semana";
                        break;
                }
            } else {
                switch ($col['TIEMPO_MEDIDA']) {
                    case 'd':
                        echo "Dias";
                        break;
                    case 'dc':
                        echo "Dias Calendario";
                        break;
                    case 'm':
                        echo "Meses";
                        break;
                    case 'a':
                        echo "Años";
                        break;
                    case 's':
                        echo "Semanas";
                        break;
                }
            }
            ?></td>


            <?php echo '<td align="center"><a href="' . base_url() . 'index.php/gestionwf/editarnotificacion/' . $oculto2 . '" class="btn btn-small" title="Editar" data-toggle="modal" data-target="#modal" data-keyboard="false" data-backdrop="static"><i class="fa fa-edit" id="editar"></i></a></td>'; ?>    
                        <td align="center"><a onclick="confirmar('<?php echo $col['CODRECORDATORIO'] ?>')" href="#" class="btn btn-danger" title="Eliminar" id="eliminar"><i class="fa fa-minus-square"></i></a></td>




                    </tr>

            <?php
        }
    }
    ?>

        </tbody>    
    </table>

    <table style="float:right">
        <br>
        <td>
            <form action= "<?php echo base_url('index.php/gestionwf/notificaciones'); ?>" method="post">
                <button at="<?php echo $col['CODRECORDATORIO']; ?>" class="btn btn-success" style="height: 30px; width: 100px; display: block; margin: 0 auto 0 auto"><i class="fa fa-plus-circle"> Agregar</i></button>
                <input type="hidden" name="tipogestion" id="tipogestion" value="<?php echo $gestion; ?>">
                <input type="hidden" name="tipogestion1" id="tipogestion1" value="<?php echo $gestionar; ?>">     
                <input type="hidden" name="tiponoti" id="tiponoti" value="<?php echo $tiponoti; ?>">


            </form>
        </td>

        <td>
            <form action= "<?php echo base_url('index.php/gestionwf/'); ?>" method="post">
                <button class="btn btn-normal" style="height: 30px; width: 100px; display: block; margin: 0 auto 0 auto"><i class="fa fa-arrow-circle-left"> Volver</i></button>
            </form>
        </td>    
    </table>
    <!-- Modal External-->
    <div class="modal hide fade in" id="modal" style="display: none; width: 40%; margin-left: -20%;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <!--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>-->
                    <h4 class="modal-title">Detalle Actualizaci&oacute;n</h4>
                </div>
                <div class="modal-body" align="center">
                    <img src="<?php echo base_url(); ?>/img/27.gif" width="150px" height="150px" />
                    <!--<progress value="10" max="100" id="progressbar" style="width: 250px; height: 40px; webkit-progress-bar {background-color: gold;} "></progress>-->
                </div>
                <div class="modal-footer">
                    <!--<a href="#" class="btn btn-default" data-dismiss="modal" id="cancelar">Cancelar</a>-->
                    <!--<a href="#" class="btn btn-primary mce-text" id="text-mce_127">Guardar</a>-->
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <script>
        $('#notifica').dataTable({
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

        function confirmar(id) {
            bootbox.confirm("Esta seguro que desea eliminar el Recordatorio o Timer?", function(result) {
                if (result == true) {
                    var url = "<?php echo base_url() ?>index.php/gestionwf/delete_noti";
                    $.ajax({
                        url: url,
                        data: {"col": id},
                        type: 'POST',
                    }).done(function() {
                        window.location.reload();
                        bootbox.alert('Recordatorio o Timer Eliminado Correctamente');
                    })
                }
            });
        }

        $('#editar').click(function() {
            var progressbar = $('#progressbar'),
                    max = progressbar.attr('max'),
                    time = (500 / max) * 5,
                    value = progressbar.val();
            var loading = function() {
                value += 1;
                addValue = progressbar.val(value);
                $('.progress-value').html(value + '%');
                if (value == max) {
                    clearInterval(animate);
                }
            };
            var animate = setInterval(function() {
                loading();
            }, time);
        });

        function ajaxValidationCallback(status, form, json, options) {
        }

    </script>



    <?php
}
?>