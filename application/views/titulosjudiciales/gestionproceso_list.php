
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<br>
<center>
    <br>
    <h2>Lista de Ejecutados Trasladados desde Jurisdicción Coactiva</h2>
    <h2><?php echo 'Registrar Titulos'; ?></h2>

</center>
<br>
<?php
echo anchor(base_url() . 'index.php/procesojudicial/Registrar_Titulo', '<i class="icon-star"></i> Crear Nuevo Titulo', 'class="btn btn-large  btn-primary"');
?>
<br><br>
<div id="datos">
    <table id="tablaq">
        <thead>
            <tr>        
                <th>Identificación</th>
                <th>Nombre del Deudor</th>
                <th>Telefono</th>
                <th>Direccion del Inmueble</th>
                <th>Fecha Escritura</th>
                <th>Número Escritura</th>
                <th>Gestionar</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($estados_seleccionados)) {
                foreach ($estados_seleccionados as $data) {
                    ?> 
                    <tr>          
                        <td><div style="cursor: pointer"><?= $data->IDENTIFICACION ?></div></td>
                        <td><div style="cursor: pointer"><?= $data->NOMBRES . ' '. $data->APELLIDOS ?></div></td> 
                        <td><div style="cursor: pointer"><?= $data->TELEFONO ?></div></td> 
                        <td><div style="cursor: pointer"><?= $data->DIRECCION ?></div></td> 
                        <td><div style="cursor: pointer"><?= $data->FECHA_ESCRITURA ?></div></td> 
                        <td><div style="cursor: pointer"><?= $data->NUMERO_ESCRITURA ?></div></td> 
                        <td><div style="cursor: pointer" class="push" ruta="<?php echo $ruta; ?>" cf="<?= $data->COD_CARTERA_NOMISIONAL ?>"><input type="radio"></div></td> 
                    </tr>
                    <?php
                }
            }
            ?>
        </tbody>     
    </table>
</div>
<br>
<center>
    <?php echo anchor('procesojudicial/', '<i class="fa fa-repeat"></i> Regresar', 'class="btn"'); ?>
</center>
<form id="form1" action="" method="post" >   
    <input type="hidden" id="cod_fiscalizacion" name="cod_fiscalizacion" > 
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
        var cod_fiscalizacion = $(this).attr('cf');
        $('#cod_fiscalizacion').val(cod_fiscalizacion);
        var ruta = $(this).attr('ruta');
        $('#form1').attr("action", ruta);
        $("#form1").submit();
        setTimeout(function() {
            jQuery(".preload, .load").hide();
        }, 2000);
    });


</script> 