<br>
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<br><br>
<center>
    <div><h1>Nulidad</h1></div>
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
            <th>Estado</th>
            <th>Gestionar</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (!empty($nulidad_seleccionada)) {
            foreach ($nulidad_seleccionada as $data) {
                ?> 
                <tr>
                    <td><div style="cursor: pointer" class="push" ruta="<?= $destino ?>" gr="<?= $cod_respuesta ?>" t="<?= $tipo ?>"  cp="<?= $cod_plantilla ?>" ct="<?= $data->COD_NULIDAD ?>"><?= $data->CODEMPRESA ?></div></td>
                    <td><div style="cursor: pointer" class="push" ruta="<?= $destino ?>" gr="<?= $cod_respuesta ?>" t="<?= $tipo ?>"  cp="<?= $cod_plantilla ?>" ct="<?= $data->COD_NULIDAD ?>"><?= $data->NOMBRE_EMPRESA ?></div></td> 
                    <td><div style="cursor: pointer" class="push" ruta="<?= $destino ?>" gr="<?= $cod_respuesta ?>" t="<?= $tipo ?>"  cp="<?= $cod_plantilla ?>" ct="<?= $data->COD_NULIDAD ?>"><?= $data->NOMBRE_CONCEPTO ?></div></td> 
                    <td><div style="cursor: pointer" class="push" ruta="<?= $destino ?>" gr="<?= $cod_respuesta ?>" t="<?= $tipo ?>"  cp="<?= $cod_plantilla ?>" ct="<?= $data->COD_NULIDAD ?>"><?= $data->PROCESO ?></div></td> 
                    <td><div style="cursor: pointer" class="push" ruta="<?= $destino ?>" gr="<?= $cod_respuesta ?>" t="<?= $tipo ?>"  cp="<?= $cod_plantilla ?>" ct="<?= $data->COD_NULIDAD ?>"><?= $data->REPRESENTANTE_LEGAL ?></div></td> 
                    <td><div style="cursor: pointer" class="push" ruta="<?= $destino ?>" gr="<?= $cod_respuesta ?>" t="<?= $tipo ?>"  cp="<?= $cod_plantilla ?>" ct="<?= $data->COD_NULIDAD ?>"><?= $data->TELEFONO_FIJO ?></div></td> 
                    <td><div style="cursor: pointer" class="push" ruta="<?= $destino ?>" gr="<?= $cod_respuesta ?>" t="<?= $tipo ?>"  cp="<?= $cod_plantilla ?>" ct="<?= $data->COD_NULIDAD ?>"><?= $data->RESPUESTA ?></div></td> 
                    <td><input type="radio" onclick="enviar(<?php echo $data->COD_NULIDAD; ?>, '<?php echo base_url() . $ruta; ?>', '<?php echo $tipo ?>', '<?php echo $cod_plantilla ?>', '<?php echo $cod_respuesta ?>')"></td> 
                </tr>
                <?php
            }
        }
        ?>
    </tbody>     
</table>
<form id="form1" action="<?= base_url($ruta) ?>" method="post" >
    <input type="hidden" id="cod_nulidad" name="cod_nulidad" > 
    <input type="hidden" id="cod_plantilla" name="cod_plantilla" > 
    <input type="hidden" id="consecutivo" name="consecutivo" > 
    <input type="hidden" id="tipo" name="tipo" > 
    <input type="hidden" id="cod_respuesta" name="cod_respuesta" > 
    <input type="hidden" id="ruta" name="ruta" value=<?php echo $destino ?> > 
</form>
<br>
<center>
    <?php echo anchor('nulidad', '<i class="fa fa-repeat"></i> Regresar', 'class="btn"'); ?>
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
            {"sClass": "center"},
        ],
    });
    $('.push').click(function() {
        $(".preload, .load").show();
        var cod_nulidad = $(this).attr('ct');
        $('#cod_nulidad').val(cod_nulidad);
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
    // jQuery(".preload, .load").hide();
    
    function enviar(cod_nulidad, ruta, tipo, cod_plantilla, cod_respuesta) {
        $(".preload, .load").show();
        $('#cod_nulidad').val(cod_nulidad);
        $('#cod_plantilla').val(cod_plantilla);
        $('#tipo').val(tipo);
        $('#cod_respuesta').val(cod_respuesta);
        $('#form1').attr("action", ruta);
        $("#form1").submit();
        setTimeout(function() {
            jQuery(".preload, .load").hide();
        }, 2000);
    }
</script> 