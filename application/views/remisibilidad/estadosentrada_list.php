<br><div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>
    <?php
    if (isset($message)) {
        ?>
        <?php
        echo $message;
    } else {
        echo "Seleccione La Empresa A La Cual Desea Registrarle La Remisibilidad";
    }
    ?>
</div>
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<br><br>
<center>
    <div style="color: #FC7323"><h1>REMISIBILIDAD</h1></div>
    <h2><?php echo $titulo; ?></h2>
</center>
<br>

<table id="tablaq">
    <thead>
        <tr>        
            <th>NIT</th>
            <th>Ejecutado</th>
            <th>Concepto</th>
            <th>Proceso</th>
            <th>Representante Legal</th>
            <th>Teléfono</th>
            <th>Gestión</th>
        </tr>
    </thead>
    <tbody>
        <?php
        //var_dump($remisibilidad_seleccionados);die;
        if (!empty($remisibilidad_seleccionados)) {
            foreach ($remisibilidad_seleccionados as $data) {
                ?> 
                <tr>          
                    <td><div style="cursor: pointer" class="push" cr="<?= $data->COD_REMISIBILIDAD ?>"><?= $data->CODEMPRESA ?></div></td>
                    <td><div style="cursor: pointer" class="push" cr="<?= $data->COD_REMISIBILIDAD ?>"><?= $data->NOMBRE_EMPRESA ?></div></td> 
                    <td><div style="cursor: pointer" class="push" cr="<?= $data->COD_REMISIBILIDAD ?>"><?= $data->NOMBRE_CONCEPTO ?></div></td> 
                    <td><div style="cursor: pointer" class="push" cr="<?= $data->COD_REMISIBILIDAD ?>"><?= $data->PROCESO ?></div></td> 
                    <td><div style="cursor: pointer" class="push" cr="<?= $data->COD_REMISIBILIDAD ?>"><?= $data->REPRESENTANTE_LEGAL ?></div></td> 
                    <td><div style="cursor: pointer" class="push" cr="<?= $data->COD_REMISIBILIDAD ?>"><?= $data->TELEFONO_FIJO ?></div></td> 
                    <td><div style="cursor: pointer" class="push" cr="<?= $data->COD_REMISIBILIDAD ?>"><?= $data->RESPUESTA ?></div></td> 
                </tr>
                <?php
            }
        }
        ?>
    </tbody>     
</table>
<form id="form1" action="<?= base_url($ruta) ?>" method="post" >
    <input type="hidden" id="cod_remisibilidad" name="cod_remisibilidad" > 
</form>
<br>


<script type="text/javascript" language="javascript" charset="utf-8">

//generación de la tabla mediante json
    jQuery(".preload, .load").hide();

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
            {"sClass": "center"},
        ],
    });
    $('.push').click(function() {
        $(".preload, .load").show();
        var cod_remisibilidad = $(this).attr('cr');
        $('#cod_remisibilidad').val(cod_remisibilidad);
        $('#form1').submit();
        setTimeout(function() {
            jQuery(".preload, .load").hide();
        }, 2000);
    });


</script> 