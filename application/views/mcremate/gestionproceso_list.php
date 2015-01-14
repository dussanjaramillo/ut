<br><div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>
    <?php
    if (isset($message)) {
        ?>
        <?php
        echo $message;
    } else {
        echo "Seleccione la empresa en cualquier parte de la fila";
    }
    ?>
</div>
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<br>
<center>
    <br>
    <h1><div style="color: #FC7323">Medidas Cautelares - Remate</div></h1>
    <h2><?php echo 'Gestión de Actividades '.$grupo; ?></h2>
    
</center>
<br>
<div id="datos">
    <table id="tablaq">
        <thead>
            <tr>        
                <th>NIT</th>
                <th>Razón Social</th>
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
            if (!empty($estados_seleccionados)) {
                foreach ($estados_seleccionados as $data) {
                    ?> 
                    <tr>          
                        <td><div style="cursor: pointer" class="push" ruta="<?= base_url("index.php/mcremate/" . $data->PARAMETRO) ?>" cf="<?= $data->COD_AVALUO ?>"><?= $data->CODEMPRESA ?></div></td>
                        <td><div style="cursor: pointer" class="push" ruta="<?= base_url("index.php/mcremate/" . $data->PARAMETRO) ?>" cf="<?= $data->COD_AVALUO ?>"><?= $data->NOMBRE_EMPRESA ?></div></td> 
                        <td><div style="cursor: pointer" class="push" ruta="<?= base_url("index.php/mcremate/" . $data->PARAMETRO) ?>" cf="<?= $data->COD_AVALUO ?>"><?= $data->NOMBRE_CONCEPTO ?></div></td> 
                        <td><div style="cursor: pointer" class="push" ruta="<?= base_url("index.php/mcremate/" . $data->PARAMETRO) ?>" cf="<?= $data->COD_AVALUO ?>">Remate</div></td> 
                        <td><div style="cursor: pointer" class="push" ruta="<?= base_url("index.php/mcremate/" . $data->PARAMETRO) ?>" cf="<?= $data->COD_AVALUO ?>"><?= $data->REPRESENTANTE_LEGAL ?></div></td> 
                        <td><div style="cursor: pointer" class="push" ruta="<?= base_url("index.php/mcremate/" . $data->PARAMETRO) ?>" cf="<?= $data->COD_AVALUO ?>"><?= $data->TELEFONO_FIJO ?></div></td> 
                        <td><div style="cursor: pointer" class="push" ruta="<?= base_url("index.php/mcremate/" . $data->PARAMETRO) ?>" cf="<?= $data->COD_AVALUO ?>"><?= $data->NOMBRE_GESTION ?></div></td> 
                    </tr>
                    <?php
                }
            }
            ?>
        </tbody>     
    </table>
</div>
<form id="form1" action="" method="post" >   
     <input type="hidden" id="cod_avaluo" name="cod_avaluo" > 
</form>
<br>
<center>
    <?php echo anchor('remisibilidad/', '<i class="fa fa-repeat"></i> Regresar', 'class="btn"'); ?>
</center>

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
        var cod_avaluo = $(this).attr('cf');
        $('#cod_avaluo').val(cod_avaluo);
        var ruta = $(this).attr('ruta');
        $('#form1').attr("action", ruta);
        $("#form1").submit();
        setTimeout(function() {
            jQuery(".preload, .load").hide();
        }, 2000);
    });


</script> 