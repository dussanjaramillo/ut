<br><div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>
    <?php
    if (isset($message)) {
        ?>
        <?php
        echo $message;
    } else {
        echo "Seleccione el proceso a retornar";
    }
    ?>
</div>
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<br><br>
<center>
    <h1>NULIDADES</h1>
    <h2><?php echo $titulo; ?></h2>
</center>
<br>

<table id="tablaq">
    <thead>
        <tr>        
            <th>N. Proceso</th>
            <th>Gestión</th>
            <th>Estado</th>
            <th>Seleccionar</th>
        </tr>
    </thead>
    <tbody>
        <?php
        //var_dump($nulidad_seleccionados);die;
        if (!empty($nulidad_seleccionada)) {
            foreach ($nulidad_seleccionada as $data) {
                ?> 
                <tr>          
                    <td><div style="cursor: pointer" class="push" cp="<?= $data->COD_TIPO_PROCESO ?>" cr="<?= $data->COD_RESPUESTA ?>" f="<?= $data->COD_PROCESO_COACTIVO ?>"><?= $data->COD_PROCESOPJ ?></div></td>
                    <td><div style="cursor: pointer" class="push" cp="<?= $data->COD_TIPO_PROCESO ?>" cr="<?= $data->COD_RESPUESTA ?>" f="<?= $data->COD_PROCESO_COACTIVO ?>"><?= $data->TIPO_PROCESO ?></div></td> 
                    <td><div style="cursor: pointer" class="push" cp="<?= $data->COD_TIPO_PROCESO ?>" cr="<?= $data->COD_RESPUESTA ?>" f="<?= $data->COD_PROCESO_COACTIVO ?>"><?= $data->NOMBRE_GESTION ?></div></td> 
                    <td><input type="radio" onclick="redireccionar(<?= $data->COD_TIPO_PROCESO ?>,<?= $data->COD_RESPUESTA ?>,<?= $data->COD_PROCESO_COACTIVO ?>)"/></td>
                </tr>
                <?php
            }
        }
        ?>
    </tbody>     
</table>
<form id="form1" action="<?= base_url($ruta) ?>" method="post" >
    <input type="hidden" id="cod_coactivo" name="cod_coactivo" >    
    <input type="hidden" id="cod_respuesta" name="cod_respuesta" > 
    <input type="hidden" id="cod_gestion" name="cod_gestion" > 
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
        ],
    });
    function redireccionar(cod_gestion,cod_respuesta,cod_coactivo) {
        $(".preload, .load").show();
        $('#cod_respuesta').val(cod_respuesta);
        $('#cod_gestion').val(cod_gestion);
        $('#cod_coactivo').val(cod_coactivo);
        $('#form1').submit();
        setTimeout(function() {
            jQuery(".preload, .load").hide();
        }, 2000);
    }   


</script> 