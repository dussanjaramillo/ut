<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="center-form-xlarge">
    <?php //echo $custom_error; ?>
    <div class="text-center">
        <h2>Procesos para Auto de Cierre y Terminación del Proceso</h2>
    </div>
    <?php if (isset($message)) echo $message; ?>
    <table id="tablaq">
        <thead>
            <tr>
            <th>N° PROCESO</th>
            <th>REGIONAL</th>
            <th>IDENTIFICACIÓN<BR>EJECUTADO</th>
            <th>EJECUTADO</th>
            <th>CONCEPTO</th>
            <th>ESTADO</th>
    <!--        <th>ASIGNADO A</th>-->
            <th>GESTIÓN</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($registros):

                foreach ($registros as $data):
                    ?>

                    <tr>
                    <td><?php echo $data['COD_PROCESOPJ']; ?></td>
                    <td><?php echo $data['NOMBRE_REGIONAL']; ?></td>
                    <td><?php echo $data['NIT_EMPRESA']; ?></td>
                    <td><?php echo $data['NOMBRE_EMPRESA']; ?></td>
                    <td><?php echo $data['CONCEPTO']; ?></td>
                    <td><?php echo $data['NOMBRE_GESTION']; ?></td>
                    <td>
                        <?php
                        if (ID_USUARIO == ID_SECRETARIO):
                            switch ($cod_respuesta):
                                case 1133 :
                                    ?>
                                    <form name="form1" id="form2" action="<?php echo base_url('index.php/verfpagosprojuridicos/auto'); ?>" method="post" >
                                        <input type="hidden" name="NUM_AUTOGENERADO" id="NUM_AUTOGENERADO" value="<?php echo $data['NUM_AUTOGENERADO'] ?>">
                                        <input type="hidden" name="cod_respuesta" id="cod_respuesta" value="<?php echo $cod_respuesta ?>">
                                        <input type="hidden" name="cod_coactivo" id="cod_coactivo" value="<?php echo $data['COD_PROCESO_COACTIVO'] ?>">

                                        <button name="boton"  class="btn btn-small btn-info" type="submit" > <i class="fa fa-folder-open-o" ></i></button>
                                    </form>

                                    <?php
                                    break;
                            endswitch;
                           
                        ?>
                        <?php
                        elseif (ID_USUARIO == ID_COORDINADOR):
                            switch ($cod_respuesta):
                                case 1135 :
                                    ?>
                                    <form name="form1" id="form2" action="<?php echo base_url('index.php/verfpagosprojuridicos/auto'); ?>" method="post" >
                                        <input type="hidden" name="NUM_AUTOGENERADO" id="NUM_AUTOGENERADO" value="<?php echo $data['NUM_AUTOGENERADO'] ?>">
                                        <input type="hidden" name="cod_respuesta" id="cod_respuesta" value="<?php echo $cod_respuesta ?>">
                                        <input type="hidden" name="cod_coactivo" id="cod_coactivo" value="<?php echo $data['COD_PROCESO_COACTIVO'] ?>">

                                        <button name="boton"  class="btn btn-small btn-info" type="submit" > <i class="fa fa-folder-open-o" ></i></button>
                                    </form>
                                    <?php
                                    break;
                            endswitch;
                            else:
                                ?>
                                    <form name="form1" id="form2" action="<?php echo base_url('index.php/verfpagosprojuridicos/auto'); ?>" method="post" >
                                        <input type="hidden" name="NUM_AUTOGENERADO" id="NUM_AUTOGENERADO" value="<?php echo $data['NUM_AUTOGENERADO'] ?>">
                                        <input type="hidden" name="cod_respuesta" id="cod_respuesta" value="<?php echo $cod_respuesta ?>">
                                        <input type="hidden" name="cod_coactivo" id="cod_coactivo" value="<?php echo $data['COD_PROCESO_COACTIVO'] ?>">
                                        <button name="boton"  class="btn btn-small btn-info" type="submit" > <i class="fa fa-folder-open-o" ></i></button>
                                    </form>
                                    <?php
                            endif;
                        ?>

                    </td>


                    </tr>
                <?php
                endforeach;
            endif;
            ?>
        </tbody>
    </table>
</div>
<br>
<div style="text-align: center;">  
    <form id="form_bandeja" action="<?php echo base_url('index.php/bandejaunificada/index'); ?>" method="post">
        <button class="btn btn-warning btn-lg"> Cancelar </button>

    </form></div>
<script type="text/javascript" language="javascript" charset="utf-8">
    //generación de la tabla mediante json


    function ver(id) {
	
    }

    $(document).ready(function() {



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
                "fnInfoCallback": null
            }
        });
    });
</script> 