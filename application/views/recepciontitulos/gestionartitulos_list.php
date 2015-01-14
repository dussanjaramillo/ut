<br>
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<br><br>
<center>
    <h1><div style="color: red">Recepcion, Estudio de Titulos y Avoca Conocimiento al Expediente</div></h1>
    <h2><?php echo $titulo; ?></h2>
</center>
<br><br>
<table id="tablaq">
    <thead>
        <tr>        
            <th>Codigo Regional</th>
            <th>Nombre Regional</th>
            <th>Identificacion Deudor</th>
            <th>Nombre Deudor</th>
            <th>Numero Radicado</th>
            <th>Numero de Expediente</th>
            <th>NIS</th>
            <th>Tipo Documento</th>
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
                    <td><div style="cursor: pointer" class="push" ct="<?= $data->COD_TITULO ?>"><?= $data->COD_REGIONAL ?></div></td>
                    <td><div style="cursor: pointer" class="push" ct="<?= $data->COD_TITULO ?>"><?= $data->NOMBRE_REGIONAL ?></div></td> 
                    <td><div style="cursor: pointer" class="push" ct="<?= $data->COD_TITULO ?>"><?= $data->CODEMPRESA ?></div></td> 
                    <td><div style="cursor: pointer" class="push" ct="<?= $data->COD_TITULO ?>"><?= $data->NOMBRE_EMPRESA ?></div></td> 
                    <td><div style="cursor: pointer" class="push" ct="<?= $data->COD_TITULO ?>"><?= $data->RADICADO_ONBASE ?></div></td> 
                    <td><div style="cursor: pointer" class="push" ct="<?= $data->COD_TITULO ?>"><?= $data->COD_FISCALIZACION ?></div></td> 
                    <td><div style="cursor: pointer" class="push" ct="<?= $data->COD_TITULO ?>"><?= $data->NIS ?></div></td> 
                    <td><div style="cursor: pointer" class="push" ct="<?= $data->COD_TITULO ?>"><?= $data->NOMBRE_DOCUMENTO ?></div></td> 
                    <td><div style="cursor: pointer" class="push" ct="<?= $data->COD_TITULO ?>"><?= $data->NOMBRE_GESTION ?></div></td> 
                </tr>
                <?php
            }
        }
        ?>
    </tbody>     
</table>
<form id="form1" action="<?= base_url($ruta) ?>" method="post" >
    <input type="hidden" id="cod_titulo" name="cod_titulo" > 
</form>
<br>
<center>
    <?php echo anchor('recepciontitulos', '<i class="fa fa-repeat"></i> Regresar', 'class="btn"'); ?>
</center>

<script type="text/javascript" language="javascript" charset="utf-8">

//generación de la tabla mediante json
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
            {"sClass": "center"},
            {"sClass": "center"},
        ],
    });
    $('.push').click(function() {
        $(".preload, .load").show();
        var cod_titulo = $(this).attr('ct');
        $('#cod_titulo').val(cod_titulo);
        $('#form1').submit();
        setTimeout(function() {
            jQuery(".preload, .load").hide();
        }, 2000);
    });


</script> 