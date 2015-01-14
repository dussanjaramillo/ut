<br><div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>
    <?php
    if (isset($message)) {
        ?>
        <?php
        echo $message;
    } else {
        echo "seleccione la empresa que desea Generarle la Resolucion";
    }
    ?>
</div>
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<br><br>
<h1>SUSTANCIAR FIN DEL PROCESO</h1>
<br>
<br><br>
<table id="tablaq">
    <thead>
        <tr>        
            <th>NIT</th>
            <th>Razon Social</th>
            <th>Concepto</th>
            <th>Instancia</th>
            <th>Representante Legal</th>
            <th>Telefono</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>
        <?php
        //var_dump($remisibilidad_seleccionados);die;
        if (!empty($remisibilidad_seleccionados)) {
            foreach ($remisibilidad_seleccionados as $data) {
                ?> 
                <tr>          
                    <td><div style="cursor: pointer" class="push" cta="1" cf="<?= $data->COD_FISCALIZACION ?>"><?= $data->CODEMPRESA ?></div></td>
                    <td><div style="cursor: pointer" class="push" cta="1" cf="<?= $data->COD_FISCALIZACION ?>"><?= $data->NOMBRE_EMPRESA ?></div></td> 
                    <td><div style="cursor: pointer" class="push" cta="1" cf="<?= $data->COD_FISCALIZACION ?>"><?= $data->NOMBRE_CONCEPTO ?></div></td> 
                    <td><div style="cursor: pointer" class="push" cta="1" cf="<?= $data->COD_FISCALIZACION ?>"><?= $data->NOMBREPROCESO ?></div></td> 
                    <td><div style="cursor: pointer" class="push" cta="1" cf="<?= $data->COD_FISCALIZACION ?>"><?= $data->REPRESENTANTE_LEGAL ?></div></td> 
                    <td><div style="cursor: pointer" class="push" cta="1" cf="<?= $data->COD_FISCALIZACION ?>"><?= $data->TELEFONO_FIJO ?></div></td> 
                    <td><div style="cursor: pointer" class="push" cta="1" cf="<?= $data->COD_FISCALIZACION ?>"><?= $data->TIPOGESTION ?></div></td> 
                </tr>
                <?php
            }
        }
        ?>
    </tbody>     
</table>
<form id="form1" action="<?= base_url('index.php/verfpagosprojuridicos/form') ?>" method="post" >
    <input type="hidden" id="COD_FISCALIZACION" name="COD_FISCALIZACION" > 
    <input type="hidden" id="COD_TIPO_AUTO" name="COD_TIPO_AUTO" > 
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
        var cod_fiscalizacion = $(this).attr('cf');
        var cod_tipo_auto = $(this).attr('cta');
        $('#COD_FISCALIZACION').val(cod_fiscalizacion);
        $('#COD_TIPO_AUTO').val(cod_tipo_auto);
        $('#form1').submit();
        setTimeout(function() {
            jQuery(".preload, .load").hide();
        }, 2000);
    });


</script> 
