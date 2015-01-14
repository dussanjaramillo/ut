<br>
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<br><br>
<center>
    <div style="color: #FC7323"><h1>Remisibilidad</h1></div>
    <h2><?php echo $titulo; ?></h2>
</center>
<table id="tablaq">
    <thead>
        <tr>        
            <th>Identificación</th>
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
        if (!empty($remisibilidad_seleccionados)) {
            foreach ($remisibilidad_seleccionados as $data) {
                ?> 
                <tr>
                    <td><div style="cursor: pointer" class="push" ruta="<?= $destino ?>" gr="<?= $cod_respuesta ?>" t="<?= $tipo ?>"  cp="<?= $cod_plantilla ?>" ct="<?= $data->COD_REMISIBILIDAD ?>"><?= $data->CODEMPRESA ?></div></td>
                    <td><div style="cursor: pointer" class="push" ruta="<?= $destino ?>" gr="<?= $cod_respuesta ?>" t="<?= $tipo ?>"  cp="<?= $cod_plantilla ?>" ct="<?= $data->COD_REMISIBILIDAD ?>"><?= $data->NOMBRE_EMPRESA ?></div></td> 
                    <td><div style="cursor: pointer" class="push" ruta="<?= $destino ?>" gr="<?= $cod_respuesta ?>" t="<?= $tipo ?>"  cp="<?= $cod_plantilla ?>" ct="<?= $data->COD_REMISIBILIDAD ?>"><?= $data->NOMBRE_CONCEPTO ?></div></td> 
                    <td><div style="cursor: pointer" class="push" ruta="<?= $destino ?>" gr="<?= $cod_respuesta ?>" t="<?= $tipo ?>"  cp="<?= $cod_plantilla ?>" ct="<?= $data->COD_REMISIBILIDAD ?>"><?= $data->PROCESO ?></div></td> 
                    <td><div style="cursor: pointer" class="push" ruta="<?= $destino ?>" gr="<?= $cod_respuesta ?>" t="<?= $tipo ?>"  cp="<?= $cod_plantilla ?>" ct="<?= $data->COD_REMISIBILIDAD ?>"><?= $data->REPRESENTANTE_LEGAL ?></div></td> 
                    <td><div style="cursor: pointer" class="push" ruta="<?= $destino ?>" gr="<?= $cod_respuesta ?>" t="<?= $tipo ?>"  cp="<?= $cod_plantilla ?>" ct="<?= $data->COD_REMISIBILIDAD ?>"><?= $data->TELEFONO_FIJO ?></div></td> 
                    <td><div style="cursor: pointer" class="push" ruta="<?= $destino ?>" gr="<?= $cod_respuesta ?>" t="<?= $tipo ?>"  cp="<?= $cod_plantilla ?>" ct="<?= $data->COD_REMISIBILIDAD ?>"><?= $data->RESPUESTA ?></div></td> 
                </tr>
                <?php
            }
        }
        ?>
    </tbody>     
</table>
<form id="form1" action="<?= base_url($ruta) ?>" method="post" >
    <input type="hidden" id="cod_remisibilidad" name="cod_remisibilidad" > 
    <input type="hidden" id="cod_plantilla" name="cod_plantilla" > 
    <input type="hidden" id="consecutivo" name="consecutivo" > 
    <input type="hidden" id="tipo" name="tipo" > 
    <input type="hidden" id="cod_respuesta" name="cod_respuesta" > 
    <input type="hidden" id="ruta" name="ruta" > 
</form>
<br>
<center>
    <?php echo anchor('recepciontitulos', '<i class="fa fa-repeat"></i> Regresar', 'class="btn"'); ?>
</center>

<script type="text/javascript" language="javascript" charset="utf-8">

    jQuery(".preload, .load").hide();

    $('#tablaq').dataTable({
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
        var cod_remisibilidad = $(this).attr('ct');
        $('#cod_remisibilidad').val(cod_remisibilidad);
        var cod_plantilla = $(this).attr('cp');
        $('#cod_plantilla').val(cod_plantilla);
        var consecutivo = $(this).attr('c');
        $('#consecutivo').val(consecutivo);
        var tipo = $(this).attr('t');
        $('#tipo').val(tipo);
        var cod_respuesta = $(this).attr('gr');
        $('#cod_respuesta').val(cod_respuesta);
        var destino = $(this).attr('ruta');
        $('#ruta').val(destino);
        $('#form1').submit();
        setTimeout(function() {
            jQuery(".preload, .load").hide();
        }, 2000);
    });
    jQuery(".preload, .load").hide();
</script> 