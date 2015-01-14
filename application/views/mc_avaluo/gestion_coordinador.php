<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
if (isset($message)) {
    echo $message;
}
if (isset($custom_error))
    echo $custom_error;
?>
<div id="ajax_load" class="ajax_load" style="display: none">
    <div class="preload" id="preload" ></div><img  id="load" class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
</div>
<div >
    <h3>Medidas Cautelares Avaluo -Gestion Funcionario Ejecutor</h3>
    <table id="tabla1">
        <thead>
        <th>N° PROCESO</th>
        <th>COD REGIONAL</th>
        <th>N° MEDIDA CAUTELAR</th>
        <th>FECHA MEDIDA CAUTELAR</th>
        <th>IDENTIFICACIÓN<BR>EJECUTADO</th>
        <th>EJECUTADO</th>
        <th>ESTADO</th>
        <th>GESTIONAR</th>
        </thead>
        <tbody>
            <?php
            foreach ($consulta as $data) {
                ?> 
                <tr>   
                    <td><?php echo $data['COD_PROCESOPJ'] ?></td>
                    <td><?php echo $data['NOMBRE_REGIONAL'] ?></td>
                    <td><?php echo $data['MEDIDA_CAUTELAR'] ?></td>
                    <td><?php echo $data['FECHA_MEDIDAS'] ?></td>
                    <td><?php echo $data['IDENTIFICACION'] ?></td>
                    <td><?php echo $data['EJECUTADO'] ?></td>
                    <td><?php echo $data['RESPUESTA'] ?></td> 
                    <td><center>
                <?php
//                echo $data['COD_TIPORESPUESTA'];
//                "<BR>";
                switch ($data['COD_TIPORESPUESTA']) {

                    case AUTO_AVALUOBIENES_PRE_APROBADO:
                        ?>
                                                                                            <!--                        <input class="push" type="radio" name="coordinador" onclick="coordinador('<?php echo $data['COD_AVALUO'] ?>', '<?php echo $data['COD_TIPORESPUESTA'] ?>', '<?php echo $data['COD_FISCALIZACION'] ?>', '<?php echo $data['NIT_EMPRESA'] ?>', '12', '<?php echo DOC_AUTO_ORDENA_AVALUO; ?>', '<?php echo AUTO_1; ?>', '<?php echo AUTO_ORDENA_AVALUO ?>', '<?php echo AUTO_AVALUOBIENES_RECHAZADO ?>', '<?php echo AUTO_AVALUOBIENES_APROBADO ?>')"  />-->
                        <input class="push" type="radio" name="coordinador" onclick="fvistas('<?php echo $data['MEDIDA_CAUTELAR'] ?>', '<?php echo $data['COD_PROCESO'] ?>', '<?php echo $data['COD_TIPORESPUESTA'] ?>', '<?php echo DOC_AUTO_ORDENA_AVALUO; ?>', '<?php echo AUTO_1; ?>', '<?php echo AUTO_ORDENA_AVALUO ?>', '<?php echo AUTO_AVALUOBIENES_RECHAZADO ?>', '<?php echo AUTO_AVALUOBIENES_APROBADO ?>', '<?php echo $data['AVALUOS'] ?>')"  />
                        <?php
                        break;
                    case AUTO_NOMBRA_PERITO_PRE_APROBADO:
                        ?>
                        <input class="push" type="radio" name="coordinador" onclick="fvistas('<?php echo $data['MEDIDA_CAUTELAR'] ?>', '<?php echo $data['COD_PROCESO'] ?>', '<?php echo $data['COD_TIPORESPUESTA'] ?>', '<?php echo DOC_AUTO_NOMBRA_PERITO; ?>', '<?php echo AUTO_2; ?>', '<?php echo AUTO_NOMBRA_PERITO ?>', '<?php echo AUTO_NOMBRA_PERITO_RECHAZADO ?>', '<?php echo AUTO_NOMBRA_PERITO_APROBADO ?>', '<?php echo $data['AVALUOS'] ?>')"  />
                        <?php
                        break;

                    case AUTO_DECLARA_FIRMEZA_AVALUO_P:
                        ?>
                        <input class="push" type="radio" name="coordinador" onclick="fvistas('<?php echo $data['MEDIDA_CAUTELAR'] ?>', '<?php echo $data['COD_PROCESO'] ?>', '<?php echo $data['COD_TIPORESPUESTA'] ?>', '<?php echo DOC_AUTO_DECLARA_FIRMEZA_AVALUO ?>', '<?php echo AUTO_6; ?>', '<?php echo GESTION_FIRMEZA_AVALUO ?>', '<?php echo AUTO_DECLARA_FIRMEZA_AVALUO_R ?>', '<?php echo AUTO_DECLARA_FIRMEZA_AVALUO_A ?>', '<?php echo $data['AVALUOS'] ?>')"  />
                        <?php
                        break;
                    case AUTO_APERTURA_PRUEBAS_P:
                        ?>
                        <input class="push" type="radio" name="coordinador" onclick="fvistas('<?php echo $data['MEDIDA_CAUTELAR'] ?>', '<?php echo $data['COD_PROCESO'] ?>', '<?php echo $data['COD_TIPORESPUESTA'] ?>', '<?php echo DOC_AUTO_APERTURA_PRUEBAS ?>', '<?php echo AUTO_3 ?>', '<?php echo GESTION_APERTURA_PRUEBAS ?>', '<?php echo AUTO_APERTURA_PRUEBAS_R ?>', '<?php echo AUTO_APERTURA_PRUEBAS_APROBADO ?>', '<?php echo $data['AVALUOS'] ?>')"  />
                        <?php
                        break;
                    case AUTO_CORRECION_AVALUO_P:
                        ?>
                        <input class="push" type="radio" name="coordinador" onclick="fvistas('<?php echo $data['MEDIDA_CAUTELAR'] ?>', '<?php echo $data['COD_PROCESO'] ?>', '<?php echo $data['COD_TIPORESPUESTA'] ?>', '<?php echo DOC_AUTO_CORRECCION_AVALUO ?>', '<?php echo AUTO_4 ?>', '<?php echo GESTION_CORRECCION_AVALUO ?>', '<?php echo AUTO_CORRECION_AVALUO_R ?>', '<?php echo AUTO_CORRECION_AVALUO_F ?>', '<?php echo $data['AVALUOS'] ?>')"  />
                        <?php
                        break;
                    case AUTO_RESUELVE_OBJ_AVALUO_P:
                        ?>
                        <input class="push" type="radio" name="coordinador" onclick="fvistas('<?php echo $data['MEDIDA_CAUTELAR'] ?>', '<?php echo $data['COD_PROCESO'] ?>', '<?php echo $data['COD_TIPORESPUESTA'] ?>', '<?php echo DOC_AUTO_RESUELVE_OBJECION_AVALUO ?>', '<?php echo AUTO_5 ?>', '<?php echo GESTION_RESUELVE_OBJECION ?>', '<?php echo AUTO_RESUELVE_OBJ_AVALUO_R ?>', '<?php echo AUTO_RESUELVE_OBJ_AVALUO_A ?>', '<?php echo $data['AVALUOS'] ?>')"  />
                        <?php
                        break;
                    case AUTO_DICTAMEN_TRASLADO_PRE_APROBADO:
                        ?>
                        <input class="push" type="radio" name="gestion" onclick="fvistas('<?php echo $data['MEDIDA_CAUTELAR'] ?>', '<?php echo $data['COD_PROCESO'] ?>', '<?php echo $data['COD_TIPORESPUESTA'] ?>', '<?php echo DOC_DICTAMEN_AVALUO ?>', '<?php echo AUTO_7; ?>', '<?php echo GESTION_AUTO_DICTAMEN ?>', '<?php echo AUTO_DICTAMEN_TRASLADO_APROBADO_RECHAZADO ?>', '<?php echo AUTO_DICTAMEN_TRASLADO_APROBADO ?>', '<?php echo $data['AVALUOS'] ?>')"  />
                        <?php
                        break;
                }
                ?></center>
            </td> 
            </tr>

            <?php
        }
        ?>
        </tbody>       
    </table>
    <div id="vista" style="margin-top:0px; ">
        <div id="div_contenido" style="margin-top:0px; ">
            <div id="div_respuesta"> <div id="resultado"></div></div></div>
    </div> 
    <br>
    <form id="form_bandeja" action="<?php echo base_url('index.php/bandejaunificada/index'); ?>" method="post">
        <input type="hidden" id="cod_coactivo" name="cod_coactivo" value="<?php echo $cod_coactivo; ?>">
        <button class="btn btn"> Regresar </button>
    </form>
    <script type="text/javascript" language="javascript" charset="utf-8">
        $(document).ready(function() {
            window.history.forward(-1);
        });
        window.history.forward(-1);
        function fvistas(medida_cautelar, cod_proceso, respuesta, tipo_doc, titulo, tipo_gestion, cod_devolucion, cod_aprobacion, avaluos) {
            $("#ajax_load").css("display", "block");
            var url = "<?= base_url("index.php/mc_avaluo/vistas") ?>";
            $('#resultado').load(url, {medida_cautelar: medida_cautelar, cod_proceso: cod_proceso, respuesta: respuesta, tipo_doc: tipo_doc, titulo: titulo, tipo_gestion: tipo_gestion, cod_devolucion: cod_devolucion, cod_aprobacion: cod_aprobacion, avaluos: avaluos});
        }
        function coordinador(id, respuesta, cod_fisc, nit, cod_proceso, tipo_doc, titulo, tipo_gestion, cod_devolucion, cod_aprobacion)
        {
            $("#ajax_load").css("display", "block");
            var url = "<?= base_url("index.php/mc_avaluo/documento") ?>";
            $.post(url, {id: id, respuesta: respuesta, cod_fisc: cod_fisc, nit: nit, cod_proceso: cod_proceso, tipo_doc: tipo_doc, titulo: titulo, tipo_gestion: tipo_gestion, cod_devolucion: cod_devolucion, cod_aprobacion: cod_aprobacion}, function(data) {
                $("#resultado").html(data);
            })
        }

        $('#tabla1').dataTable({
            "bJQueryUI": true,
            "sServerMethod": "POST",
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
                }
            }
        });
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