<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php


if (isset($message)) {
    echo $message;
}
if (isset($custom_error))
    echo $custom_error;
?>  
<h3><?php echo $titulo; ?></h3>
<h4 align="left">
    <i class="fa fa-male ">  <?php echo "  " . $nom_usuario; ?></i>
</h4>
<table id="tabla1">
    <thead>
    <th>N° Proceso</th>
    <th>Regional</th>
    <th>Ejecutado</th>
    <th>Identificación</th>
    <th>Estado</th>
    <th>Concepto</th>
    <th>Gestión</th>
</thead>
<tbody>
    <?php
    if ($consulta):
  
        foreach ($consulta as $data):
            ?>
            <tr>
                <td><?php
                    echo $data['COD_PROCESOPJ'];
                    ?>
                </td>
                <td><?php echo $data['NOMBRE_REGIONAL'] ?></td>
                <td><?php echo $data['NOMBRE'] ?></td>
                <td><?php echo $data['IDENTIFICACION'] ?></td>
                <td><?php echo $data['ESTADO'] ?></td>
                <td><?php echo $data['CONCEPTO'] ?></td>
                <td>
                    <?php
                    if (ID_USUARIO == ID_SECRETARIO):
                    switch ($data['COD_TIPO_RESPUESTA']):
                        case 186 :/* Requerimiento Acercamiento Persuasivo Generado. El secretario puede pre-aprobar o devolver el documento generado */
                            ?>
                            <button id="Abrir" class="btn btn-info" onclick="f_vistas('<?php echo $data['RUTA'] ?>', '<?php echo $data['COD_PROCESO'] ?>', '<?php echo $data['COD_COBRO'] ?>', '<?php echo $data['COD_TIPO_RESPUESTA'] ?>');" >Abrir</button>
                            <?php
                            break;
                     
                    endswitch;
                    endif;
                    if (ID_USUARIO == ID_COORDINADOR):
                    switch ($data['COD_TIPO_RESPUESTA']):
                        case 188 :/* Requerimiento Acercamiento Persuasivo Pre-aprobado. El coordinador debe aprobar o devolver el documento generado */
                            ?>
                            <button id="Abrir" class="btn btn-info" onclick="f_vistas('<?php echo $data['RUTA'] ?>', '<?php echo $data['COD_PROCESO'] ?>', '<?php echo $data['COD_COBRO'] ?>', '<?php echo $data['COD_TIPO_RESPUESTA'] ?>');" >Abrir</button>
                            <?php
                            break;
                    endswitch;
                     endif;
                      if (ID_USUARIO == $data['ABOGADO']):
                    switch ($data['COD_TIPO_RESPUESTA']):
                        case 184 :/* Titulo No proximo a prescribir, proceso para generar el requerimiento de acercamiento */
                        case 187 :/* Requerimiento Acercamiento Persuasivo Devuelto, el abogado lo debe corregir */
                            ?>
                            <button id="Abrir" class="btn btn-info" onclick="f_vistas('<?php echo $data['RUTA'] ?>', '<?php echo $data['COD_PROCESO'] ?>', '<?php echo $data['COD_COBRO'] ?>', '<?php echo $data['COD_TIPO_RESPUESTA'] ?>');" >Abrir</button>
                            <?php
                            break;
                        case 1091 :/* Requerimiento Acercamiento Persuasivo Aproado. El abogado debe adjuntar el documento */
                            ?>
                            <button id="Abrir" class="btn btn-info" onclick="f_vistas('<?php echo $data['RUTA'] ?>', '<?php echo $data['COD_PROCESO'] ?>', '<?php echo $data['COD_COBRO'] ?>', '<?php echo $data['COD_TIPO_RESPUESTA'] ?>');" >Abrir</button>
                            <?php
                            break;
                        case 190://Documento de Acercamiento Enviado
                        case 197://Acepta Obligaciones
                        case 199:
                            ?>
                            <button id="Abrir" class="btn btn-info" onclick="f_vistas('<?php echo $data['RUTA'] ?>', '<?php echo $data['COD_PROCESO'] ?>', '<?php echo $data['COD_COBRO'] ?>', '<?php echo $data['COD_TIPO_RESPUESTA'] ?>');" >Abrir</button>
                            <?php
                            break;
                           case 191:/* Requerimiento Recibido */
                            ?>
                            <button id="Abrir" class="btn btn-info" onclick="f_vistas('<?php echo $data['RUTA'] ?>', '<?php echo $data['COD_PROCESO'] ?>', '<?php echo $data['COD_COBRO'] ?>', '<?php echo $data['COD_TIPO_RESPUESTA'] ?>');" >Abrir</button>
                            <?php
                            break;
                    endswitch;
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
<br><br>
<form id="form_bandeja" action="<?php echo base_url('index.php/bandejaunificada/index'); ?>" method="post">
    <input type="hidden" id="cod_coactivo" name="cod_coactivo" value="<?php echo $cod_coactivo; ?>">
    <button class="btn btn"> Regresar </button>
</form>
<div id="resultado"></div>
<div id="ajax_load" class="ajax_load" style="display: none">
    <div class="preload" id="preload" ></div><img  id="load" class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
</div>
<script type="text/javascript" language="javascript" charset="utf-8">
    function f_vistas(ruta, cod_proceso, cod_cobro, rta_actual) {
        $("#ajax_load").show();
        var url = "<?= base_url("index.php/acercamientopersuasivo/vistas") ?>";
        $('#resultado').load(url, {ruta: ruta, cod_proceso: cod_proceso, cod_cobro: cod_cobro, cod_respuesta: rta_actual});
    }

 
    $(document).ready(function() {



        $('#tabla1').dataTable({
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
        });
    });
    function finalizar()
    {
        location.reload;
    }
</script>
<style>
    .ui-widget-overlay{z-index: 10000;}
    .ui-dialog{
        z-index: 15000;
    }
    div.preload{
        position: fixed;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        background-color: white;
        opacity: 0.8;
        z-index: 10000;
    }

    div img.load{
        position: absolute;
        left: 50%;
        top: 50%;
        margin-left: -64px;
        margin-top: -64px;
        z-index: 15000;
    }
</style>