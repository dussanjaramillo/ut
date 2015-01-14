
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<br>
<center>
    <br>
    <h1>Traslado</h1>
    <h2><?php echo 'Gestión de Actividades ' . $grupo; ?></h2>

</center>
<br>
<div id="datos">
    <table id="tablaq">
        <thead>
            <tr>        
                <th>Identificación</th>
                <th>Ejecutado</th>
                <th>Concepto</th>
                <th>Representante Legal</th>
                <th>Teléfono</th>
                <th>Gestión</th>
                <th>Estado</th>
                <th>Gestionar</th>
            </tr>
        </thead>
        <tbody>
            <?php
            //var_dump($remisibilidad_seleccionados);die;
            if (!empty($estados_seleccionados)) {
                foreach ($estados_seleccionados as $data) {
                    if ($data->COD_TIPO_PROCESO === '22') {
                        $ruta = base_url('index.php/' . $data->URLGESTION);
                    } else {
                        $ruta = base_url('index.php/traslado/Crear_AutoTraslado');
                    }
                    if ($data->COD_RESPUESTA != '1258') {
                        ?> 
                        <tr>          
                            <td><div style="cursor: pointer"><?= $data->CODEMPRESA ?></div></td>
                            <td><div style="cursor: pointer"><?= $data->RAZON_SOCIAL ?></div></td> 
                            <td><div style="cursor: pointer"><?= $data->NOMBRE_CONCEPTO ?></div></td> 
                            <td><div style="cursor: pointer"><?= $data->REPRESENTANTE_LEGAL ?></div></td> 
                            <td><div style="cursor: pointer"><?= $data->TELEFONO_FIJO ?></div></td> 
                            <td><div style="cursor: pointer"><?= $data->PROCESO ?></div></td> 
                            <td><div style="cursor: pointer"><?= $data->RESPUESTA ?></div></td> 
                            <td>
                                <?php
                                switch ($data->IDGRUPO) {
                                    case 41:
                                        $validacion = $this->ion_auth->in_group(NOMBRE_SECRETARIO);
                                        break;
                                    case 42:
                                        $validacion = $this->ion_auth->in_group(NOMBRE_COORDINADOR);
                                        break;
                                    case 43:
                                        $validacion = $this->ion_auth->in_group(NOMBRE_ABOGADO);
                                        break;
                                    case 61:
                                        $validacion = $this->ion_auth->in_group(NOMBRE_DIRECTOR);
                                        break;
                                    default:
                                        $validacion = $this->ion_auth->in_group(NOMBRE_ABOGADO);
                                        break;
                                }
                                if ($validacion) {
                                    ?>                            
                                    <div style="cursor: pointer" class="push" ruta="<?php echo $ruta; ?>" cf="<?= $data->COD_FISCALIZACION ?>"><input type="radio"></div></td> 
                                <?php
                            }
                            ?>  
                        </tr>
                        <?php
                    }
                }
            }
            ?>
        </tbody>     
    </table>
</div>
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