<?php
if (isset($message)) {
    echo $message;
}
?>
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<br>
<center>
    <br>
    <h1>Devoluciones - Direccion General</h1>
    <?php
    if ($this->ion_auth->in_group(NOMBRE_COORDIANDOR_GRYC)) {        
        ?>
        <h2><?php echo 'Gestión de Actividades Coordinador de Grupo de Recaudo y Cartera'; ?></h2>
        <?php
    } else if ($this->ion_auth->in_group(NOMBRE_LIDER_DEVOLUCION)) {
        ?>
        <h2><?php echo 'Gestión de Actividades Lider de Devolución'; ?></h2>
        <?php
    } else if ($this->ion_auth->in_group(NOMBRE_TECNICO_CARTERA)) {
        ?>
        <h2><?php echo 'Gestión de Actividades Tecnico/Profesional de Cartera de Recaudo'; ?></h2>
        <?php
    }
    ?>


</center>
<br>
<div id="datos">
    <table id="tablaq">
        <thead>
            <tr>        
                <th>NIT</th>
                <th>Empresa</th>
                <th>Motivo Devolución</th>
                <th>Concepto</th>
                <th>Estado</th>
                <th>Gestionar</th>
            </tr>
        </thead>
        <tbody>
            <?php
            //var_dump($remisibilidad_seleccionados);die;
            if (!empty($estados_seleccionados)) {
                foreach ($estados_seleccionados as $data) {
                    switch ($grupo) {
                        case COORDIANDOR_GRYC:
                            $validacion = true;
                            break;
                        case LIDER_DEVOLUCION:
                            $validacion = true;
                            break;
                        case TECNICO_CARTERA:
                            $validacion = true;
                            break;
                        default :
                            $validacion =false;
                            break;
                    }
                    if ($validacion) {
                        ?>             
                        <tr>          
                            <td><?= $data->CODEMPRESA ?></td>
                            <td><?= $data->RAZON_SOCIAL ?></td> 
                            <td><?= $data->NOMBRE_MOTIVO ?></td> 
                            <td><?= $data->NOMBRE_CONCEPTO ?></td> 
                            <td><?= $data->NOMBRE_GESTION ?></td> 
                            <td><input type="radio" onclick="enviar(<?php echo $data->COD_DEVOLUCION; ?>, '<?php echo base_url() . 'index.php/' . $data->URLGESTION; ?>', '<?php echo $data->COD_RESPUESTA; ?>', '<?php echo $data->NIT; ?>', '<?php echo $data->COD_CONCEPTO_RECAUDO; ?>')"></td> 

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
    <input type="hidden" id="cod_devolucion" name="cod_devolucion" readonly /> 
    <input type="hidden" id="cod_respuesta" name="respuesta" readonly /> 
    <input type="hidden" id="nit" name="nit" readonly /> 
    <input type="hidden" id="concepto" name="concepto" readonly /> 
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
        ],
    });
    function enviar(cod_devolucion, ruta, respuesta, nit,concepto) {
        $(".preload, .load").show();
        $('#cod_devolucion').val(cod_devolucion);
        $('#cod_respuesta').val(respuesta);
        $('#nit').val(nit);
        $('#concepto').val(concepto);
        $('#form1').attr("action", ruta);
        $("#form1").submit();
        setTimeout(function() {
            jQuery(".preload, .load").hide();
        }, 2000);
    }


</script> 