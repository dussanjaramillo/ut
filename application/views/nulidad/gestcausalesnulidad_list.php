    <br>
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<br><br>
<center>
    <h1>Nulidades</h1>
    <h2><?php echo $titulo; ?></h2>
</center>
<table id="tablaq">
    <thead>
        <tr>        
            <th>NIT</th>
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
                  
                    <td><div style="cursor: pointer" class="push" cf="<?= $data->COD_FISCALIZACION ?>"><?= $data->CODEMPRESA ?></div></td>
                    <td><div style="cursor: pointer" class="push" cf="<?= $data->COD_FISCALIZACION ?>"><?= $data->NOMBRE_EMPRESA ?></div></td> 
                    <td><div style="cursor: pointer" class="push" cf="<?= $data->COD_FISCALIZACION ?>"><?= $data->NOMBRE_CONCEPTO ?></div></td> 
                    <td><div style="cursor: pointer" class="push" cf="<?= $data->COD_FISCALIZACION ?>"><?= $data->PROCESO ?></div></td> 
                    <td><div style="cursor: pointer" class="push" cf="<?= $data->COD_FISCALIZACION ?>"><?= $data->REPRESENTANTE_LEGAL ?></div></td> 
                    <td><div style="cursor: pointer" class="push" cf="<?= $data->COD_FISCALIZACION ?>"><?= $data->TELEFONO_FIJO ?></div></td> 
                    <td><div style="cursor: pointer" class="push" cf="<?= $data->COD_FISCALIZACION ?>"><?= $data->RESPUESTA ?></div></td>
                    <td><input type="radio" onclick="enviar(<?php echo $data->COD_FISCALIZACION; ?>, '<?php echo base_url() . $ruta; ?>')"></td> 
                </tr>
                <?php
            }
        }
      
        ?>
    </tbody>     
</table>
<form id="form1" action="<?= base_url($ruta) ?>" method="post" >
    <input type="hidden" id="cod_fiscalizacion" name="cod_fiscalizacion"> 
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
            {"sClass": "center"},
        ],
    });
    $('.push').click(function() {
        $(".preload, .load").show();       
        var cod_fiscalizacion = $(this).attr('cf');
        $('#cod_fiscalizacion').val(cod_fiscalizacion);
        $('#form1').submit();
        setTimeout(function() {
            jQuery(".preload, .load").hide();
        }, 2000);
    });
    // jQuery(".preload, .load").hide();    
    
    function enviar(cod_fiscalizacion, ruta) {
        $(".preload, .load").show();
        $('#cod_fiscalizacion').val(cod_fiscalizacion);
        //$('#cod_respuesta').val(respuesta);
        $('#form1').attr("action", ruta);
        $("#form1").submit();
        setTimeout(function() {
            jQuery(".preload, .load").hide();
        }, 2000);
    }
    
</script> 
