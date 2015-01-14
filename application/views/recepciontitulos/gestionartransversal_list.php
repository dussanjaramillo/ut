<br>
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<br><br>
<center>
    <h1><div style="color: #FC7323">Recepcion, Estudio de Titulos y Avoca Conocimiento al Expediente</div></h1>
    <h2><?php echo $titulo; ?></h2>
</center>
<br><br>
<table id="tablaq">
    <thead>
        <tr>        
            <th>Identificación</th>
            <th>Ejecutado</th>
            <th>Concepto</th>
            <th>Instancia</th>
            <th>Representante Legal</th>
            <th>Teléfono</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>
        <?php
        //var_dump($remisibilidad_seleccionados);die;
        if (!empty($titulos_seleccionados)) {
            foreach ($titulos_seleccionados as $data) {
                ?> 
                <tr>
                    <td><div style="cursor: pointer" class="push" gr="<?= $cod_respuesta ?>" t="<?= $tipo ?>" c="<?= $consecutivo ?>" cp="<?= $cod_plantilla ?>" ct="<?= $data->COD_RECEPCIONTITULO ?>"><?= $data->CODEMPRESA ?></div></td>
                    <td><div style="cursor: pointer" class="push" gr="<?= $cod_respuesta ?>" t="<?= $tipo ?>" c="<?= $consecutivo ?>" cp="<?= $cod_plantilla ?>" ct="<?= $data->COD_RECEPCIONTITULO ?>"><?= $data->NOMBRE_EMPRESA ?></div></td> 
                    <td><div style="cursor: pointer" class="push" gr="<?= $cod_respuesta ?>" t="<?= $tipo ?>" c="<?= $consecutivo ?>" cp="<?= $cod_plantilla ?>" ct="<?= $data->COD_RECEPCIONTITULO ?>"><?= $data->NOMBRE_CONCEPTO ?></div></td>  
                    <td><div style="cursor: pointer" class="push" gr="<?= $cod_respuesta ?>" t="<?= $tipo ?>" c="<?= $consecutivo ?>" cp="<?= $cod_plantilla ?>" ct="<?= $data->COD_RECEPCIONTITULO ?>"><?= $data->NOMBREPROCESO ?></div></td>  
                    <td><div style="cursor: pointer" class="push" gr="<?= $cod_respuesta ?>" t="<?= $tipo ?>" c="<?= $consecutivo ?>" cp="<?= $cod_plantilla ?>" ct="<?= $data->COD_RECEPCIONTITULO ?>"><?= $data->REPRESENTANTE_LEGAL ?></div></td> 
                    <td><div style="cursor: pointer" class="push" gr="<?= $cod_respuesta ?>" t="<?= $tipo ?>" c="<?= $consecutivo ?>" cp="<?= $cod_plantilla ?>" ct="<?= $data->COD_RECEPCIONTITULO ?>"><?= $data->TELEFONO_FIJO ?></div></td>  
                    <td><div style="cursor: pointer" class="push" gr="<?= $cod_respuesta ?>" t="<?= $tipo ?>" c="<?= $consecutivo ?>" cp="<?= $cod_plantilla ?>" ct="<?= $data->COD_RECEPCIONTITULO ?>"><?= $data->NOMBRE_GESTION ?></div></td>
                </tr>
                <?php
            }
        }
        ?>
    </tbody>     
</table>
<form id="form1" action="<?= base_url($ruta) ?>" method="post" >
    <input type="hidden" id="cod_titulo" name="cod_titulo" > 
    <input type="hidden" id="cod_plantilla" name="cod_plantilla" > 
    <input type="hidden" id="consecutivo" name="consecutivo" > 
    <input type="hidden" id="tipo" name="tipo" > 
    <input type="hidden" id="cod_respuesta" name="cod_respuesta" > 
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
        var cod_titulo = $(this).attr('ct');
        $('#cod_titulo').val(cod_titulo);
        var cod_plantilla = $(this).attr('cp');
        $('#cod_plantilla').val(cod_plantilla);
        var consecutivo = $(this).attr('c');
        $('#consecutivo').val(consecutivo);
        var tipo = $(this).attr('t');
        $('#tipo').val(tipo);
        var cod_respuesta = $(this).attr('gr');
        $('#cod_respuesta').val(cod_respuesta);
        $('#form1').submit();
        setTimeout(function() {
            jQuery(".preload, .load").hide();
        }, 2000);
    });
    jQuery(".preload, .load").hide();
</script> 