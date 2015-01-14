
<!-- Visualiza el detalle de los remates para un proceso coactivo-->
<h2><center>Bandeja Unificada</center></h2>

<span><h3 style=" text-align: center;">Remate</h3></span>
<table id="tabla1">
    <thead>
        <tr><th></th>
            <th>Número Proceso</th>
            <th>Identificación Ejecutado</th>
            <th>Nombre Ejecutado</th>
            <th>Regional</th>
            <th>Estado</th>
            <th>Gestión</th>
        </tr>
    </thead> 
    <tbody>
        <?php
        if ($remate) {
            $n = 0;
            /* PROCESOS DE REMATE */
            foreach ($remate as $data2) {
                ?> 
                <tr><td><?php // echo $n;                                                          ?></td>
                    <td><?php echo $data2['PROCESOPJ']; ?></td>
                    <td><?php echo $data2['IDENTIFICACION']; ?></td>
                    <td><?php echo $data2['NOMBRE']; ?></td>
                    <td><?php echo $data2['NOMBRE_REGIONAL']; ?></td>
                    <td>
                        <?php echo $data2['PROCESOPJ']; ?>
                    </td>
                    <td>
                        
                        <form name="form_remate" id="form_remate" method="post" action="<?php  if(!empty($data2['PARAMETRO'])):echo base_url().'index.php/mcremate/'.$data2['PARAMETRO']; else:       echo base_url().'index.php/mcremate/Crear_TradicionyLibertad';                endif;?>" >
                            <input type="hidden" name="cod_avaluo" id="cod_avaluo" value="<?php echo $data2['COD_AVALUO'] ?>">
                            <input type="hidden" name="cod_proceso_remate" id="cod_proceso_remate" value="<?php echo $data2['PROCESO'] ?>">
                               <input type="hidden" name="cod_coactivo" id="cod_coactivo" value="<?php echo $data2['COD_PROCESO_COACTIVO'] ?>">
                            <button name="boton"  class="btn btn-small btn-info" type="submit" > <i class="fa fa-eye" ></i></button>
                        </form>
                    </td>
                </tr>
                <?php
                $n++;
            }
        }
        ?>

    </tbody>      
</table>
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
