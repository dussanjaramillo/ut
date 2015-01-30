<!--Esta vista lista los procesos de avalúo que debe gestionar el abogado-->
<div id="ajax_load" class="ajax_load" style="display: none">
    <div class="preload" id="preload" ></div><img  id="load" class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
</div>
<div>
    <?php
    if (isset($message)) {
        echo $message;
    }
    if (isset($custom_error))
        echo $custom_error;
    ?>    <div id="mensaje"></div>
    <h3><center>Medidas Cautelares Avalúo - Gestión Abogado</center></h3>
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
                    // AUTO
                    case GENERAR_AUTO_OPC1:
                    case GENERAR_AUTO_OPC3:
                    case GENERAR_AUTO_OPC2:
                        ?><!--cod_avaluo, cod_fisc, nit,  cod_proceso, tipo_doc, titulo, id_plantilla,tipo_respuesta, tipo_gestion -->
                                                                                                                                                                                                                    <!--                                <input class="push" type="radio" name="gestion" onclick="auto('<?php echo $data['COD_AVALUO'] ?>', '<?php echo $data['COD_TIPORESPUESTA'] ?>', '<?php echo $data['COD_FISCALIZACION'] ?>', '<?php echo $data['NIT_EMPRESA'] ?>', '12', '<?php echo DOC_AUTO_ORDENA_AVALUO ?>', '<?php echo AUTO_1; ?>', '<?php echo PLANTILLA_AUTO_1 ?>', '<?php echo AUTO_AVALUOBIENES_GENERADO ?>', '<?php echo AUTO_ORDENA_AVALUO ?>')"  />-->
                        <input class="push" type="radio" name="gestion" onclick="fvistas('<?php echo $data['MEDIDA_CAUTELAR'] ?>', '<?php echo $data['COD_PROCESO'] ?>', '<?php echo $data['COD_TIPORESPUESTA'] ?>', '<?php echo DOC_AUTO_ORDENA_AVALUO ?>', '<?php echo AUTO_1; ?>', '<?php echo PLANTILLA_AUTO_1 ?>', '<?php echo AUTO_AVALUOBIENES_GENERADO ?>', '<?php echo AUTO_ORDENA_AVALUO ?>', '<?php echo $data['AVALUOS'] ?>')"  />
                        <?php
                        break;

                    case AUTO_AVALUOBIENES_APROBADOFIRMADO:
                        ?>
                        <input class="push" type="radio" name="gestion" onclick="fvistas('<?php echo $data['MEDIDA_CAUTELAR'] ?>', '<?php echo $data['COD_PROCESO'] ?>', '<?php echo $data['COD_TIPORESPUESTA'] ?>', '<?php echo DOC_AUTO_NOMBRA_PERITO ?>', '<?php echo AUTO_2; ?>', '<?php echo PLANTILLA_AUTO_2 ?>', '<?php echo AUTO_NOMBRA_PERITO_GENERAD0 ?>', '<?php echo AUTO_NOMBRA_PERITO ?>', '<?php echo $data['AVALUOS'] ?>')"  />
                        <?php
                        break;
                    case AUTO_NOMBRA_PERITO_APROBADOFIRMADO:
                        ?>
                        <input class="push" type="radio" name="gestion" onclick="fvistas('<?php echo $data['MEDIDA_CAUTELAR'] ?>', '<?php echo $data['COD_PROCESO'] ?>', '<?php echo $data['COD_TIPORESPUESTA'] ?>', '<?php echo DOC_DICTAMEN_AVALUO ?>', '<?php echo AUTO_7; ?>', '<?php echo PLANTILLA_AUTO_7 ?>', '<?php echo AUTO_DICTAMEN_TRASLADO_GENERADO ?>', '<?php echo AUTO_NOMBRA_PERITO ?>', '<?php echo $data['AVALUOS'] ?>')"  />
                        <?php
                        break;

                    case AUTO_DICTAMEN_TRASLADO_APROBADO_FIRMADO:
                        ?>
                        <form name="form_pruebas" id="form_pruebas" action="<?= base_url("index.php/mc_avaluo/registro_avaluo") ?>" method="post">
                            <input type="hidden" name="id" id="id" value="<?php echo $data['COD_PROCESO'] ?>">
                            <input type="hidden" name="cod_siguiente" id="cod_siguiente" value="<?php echo AVALUO_RECIBIDO ?>">
                            <input type="hidden" name="respuesta" id="respuesta" value="<?php echo $data['COD_TIPORESPUESTA'] ?>">
                            <input type="hidden" name="avaluos" id="avaluos" value="<?php echo $data['AVALUOS'] ?>">
                            <input type="hidden" name="cod_proceso" id="cod_proceso" value="<?php echo $data['COD_PROCESO'] ?>">
                            <input type="hidden" name="titulo" id="titulo" value="<?php echo DOC_AUTO_APERTURA_PRUEBAS ?>">
                            <input type="hidden" name="tipo_doc" id="tipo_doc" value="<?php echo DOC_REGISTRO_AVALUO ?>">
                            <button id="enviar" class="btn btn-info">Aceptar</button>
                        </form>
                        <?php
                        break;
                    case AVALUO_RECIBIDO:
                        ?><!--cod_avaluo, cod_fisc, nit,  cod_proceso, tipo_doc, titulo, id_plantilla,tipo_respuesta, tipo_gestion -->
                        <input class="push" type="radio" name="gestion" onclick="fvistas('<?php echo $data['MEDIDA_CAUTELAR'] ?>', '<?php echo $data['COD_PROCESO'] ?>', '<?php echo $data['COD_TIPORESPUESTA'] ?>', '<?php echo DOC_NOTIFICACION_PERSONAL ?>', '<?php echo NOTIFICACION_PERSONAL; ?>', '<?php echo PLANTILLA_NOTIFICAION_PERSONAL ?>', '<?php echo NOT_PERSONAL_G ?>', '<?php echo GESTION_NOTIFICACION_PERSONAL ?>', '<?php echo $data['AVALUOS'] ?>')"  />
                        <?php
                        break;
                    case DEUDOR_NO_OBJETO:
                    case AUTO_RESUELVE_OBJ_AVALUO_F:
                    case NOTIFICACION_CORREO_APROBADA_FIRMADA:
                    case AVALUO_RECIBO_PAGO_NO_RECIBIDO:
                        ?><!--cod_avaluo, cod_fisc, nit,  cod_proceso, tipo_doc, titulo, id_plantilla,tipo_respuesta, tipo_gestion -->
                        <input class="push" type="radio" name="gestion" onclick="fvistas('<?php echo $data['MEDIDA_CAUTELAR'] ?>', '<?php echo $data['COD_PROCESO'] ?>', '<?php echo $data['COD_TIPORESPUESTA'] ?>', '<?php echo DOC_AUTO_DECLARA_FIRMEZA_AVALUO ?>', '<?php echo AUTO_6; ?>', '<?php echo PLANTILLA_AUTO_1 ?>', '<?php echo AUTO_DECLARA_FIRMEZA_AVALUO_G ?>', '<?php echo GESTION_FIRMEZA_AVALUO ?>', '<?php echo $data['AVALUOS'] ?>')"  />
                        <?php
                        break;
                    case AUTO_DECLARA_FIRMEZA_AVALUO_R:
                        ?><!--cod_avaluo, cod_fisc, nit,  cod_proceso, tipo_doc, titulo, id_plantilla,tipo_respuesta, tipo_gestion -->
                        <input class="push" type="radio" name="gestion" onclick="fvistas('<?php echo $data['MEDIDA_CAUTELAR'] ?>', '<?php echo $data['COD_PROCESO'] ?>', '<?php echo $data['COD_TIPORESPUESTA'] ?>', '<?php echo DOC_AUTO_DECLARA_FIRMEZA_AVALUO ?>', '<?php echo AUTO_6; ?>', '<?php echo PLANTILLA_AUTO_1 ?>', '<?php echo AUTO_DECLARA_FIRMEZA_AVALUO_G ?>', '<?php echo GESTION_FIRMEZA_AVALUO ?>', '<?php echo $data['AVALUOS'] ?>')"  />
                        <?php
                        break;
                    case OBJECION_PRE_REQ_PRUEBAS:
                        ?><!--cod_avaluo, cod_fisc, nit,  cod_proceso, tipo_doc, titulo, id_plantilla,tipo_respuesta, tipo_gestion -->
                        <input class="push" type="radio" name="gestion" onclick="fvistas('<?php echo $data['MEDIDA_CAUTELAR'] ?>', '<?php echo $data['COD_PROCESO'] ?>', '<?php echo $data['COD_TIPORESPUESTA'] ?>', '<?php echo DOC_AUTO_APERTURA_PRUEBAS ?>', '<?php echo AUTO_3; ?>', '<?php echo PLANTILLA_AUTO_3 ?>', '<?php echo AUTO_APERTURA_PRUEBAS_G ?>', '<?php echo GESTION_APERTURA_PRUEBAS ?>', '<?php echo $data['AVALUOS'] ?>')"  />

                        <?php
                        break;
                    case AUTO_APERTURA_PRUEBAS_F:
                        ?><!--cod_avaluo, cod_fisc, nit,  cod_proceso, tipo_doc, titulo, id_plantilla,tipo_respuesta, tipo_gestion -->
                        <form name="form_pruebas" id="form_pruebas" action="<?= base_url("index.php/mc_avaluo/registro_pruebas") ?>" method="post">
                            <input type="hidden" name="id" id="id" value="<?php echo $data['COD_PROCESO'] ?>">
                            <input type="hidden" name="respuesta" id="respuesta" value="<?php echo $data['COD_TIPORESPUESTA'] ?>">
                            <input type="hidden" name="avaluos" id="avaluos" value="<?php echo $data['AVALUOS'] ?>">
                            <input type="hidden" name="cod_proceso" id="cod_proceso" value="<?php echo $data['COD_PROCESO'] ?>">
                            <input type="hidden" name="titulo" id="titulo" value="<?php echo DOC_AUTO_APERTURA_PRUEBAS ?>">
                            <input class="btn btn-info" type="submit"  value="Aceptar"  name="gestion" id="gestion">
                        </form>    

                        <?php
                        break;

                    case PRUEBAS_REALIZADAS_REQ_CORRECCION:
                    case OBJECION_PRE_NO_REQ_PRUEBAS:
                        ?>
                        <input class="push" type="radio" name="gestion" onclick="fvistas('<?php echo $data['MEDIDA_CAUTELAR'] ?>', '<?php echo $data['COD_PROCESO'] ?>', '<?php echo $data['COD_TIPORESPUESTA'] ?>', '<?php echo DOC_AUTO_RESUELVE_OBJECION_AVALUO ?>', '<?php echo AUTO_5; ?>', '<?php echo PLANTILLA_AUTO_5 ?>', '<?php echo AUTO_RESUELVE_OBJ_AVALUO_G ?>', '<?php echo GESTION_CORRECCION_AVALUO ?>', '<?php echo $data['AVALUOS'] ?>')"  />

                        <?php
                        break;
                    case PRUEBAS_REALIZADAS_NO_REQ_CORRECCION:
                        ?>
                        <a target="_blank"  class="btn btn-small btn-info" title="ver" onclick="f_bloqueo('711', '<?php echo $data['NOTIFICACION_EFECTIVA'] ?>', '<?php echo $data['COD_PROCESO'] ?>', '<?php echo $data['AVALUOS'] ?>', '<?php echo PRUEBAS_REALIZADAS_NO_REQ_CORRECCION ?>')">
                            <i class="fa fa-question  "></i>
                        </a>
                        <?php
                        break;
                    case AUTO_AVALUOBIENES_RECHAZADO:
                        ?>
                        <input class="push" type="radio" name="gestion" onclick="fvistas('<?php echo $data['MEDIDA_CAUTELAR'] ?>', '<?php echo $data['COD_PROCESO'] ?>', '<?php echo $data['COD_TIPORESPUESTA'] ?>', '<?php echo DOC_AUTO_ORDENA_AVALUO ?>', '<?php echo AUTO_1; ?>', '<?php echo PLANTILLA_AUTO_1 ?>', '<?php echo AUTO_AVALUOBIENES_GENERADO ?>', '<?php echo AUTO_ORDENA_AVALUO ?>', '<?php echo $data['AVALUOS'] ?>')"  />
                        <?php
                        break;

                    case AUTO_NOMBRA_PERITO_RECHAZADO:
                        ?>
                        <input class="push" type="radio" name="gestion" onclick="fvistas('<?php echo $data['MEDIDA_CAUTELAR'] ?>', '<?php echo $data['COD_PROCESO'] ?>', '<?php echo $data['COD_TIPORESPUESTA'] ?>', '<?php echo DOC_AUTO_NOMBRA_PERITO ?>', '<?php echo AUTO_2; ?>', '<?php echo PLANTILLA_AUTO_2 ?>', '<?php echo AUTO_NOMBRA_PERITO_GENERAD0 ?>', '<?php echo AUTO_NOMBRA_PERITO ?>', '<?php echo $data['AVALUOS'] ?>')"  />
                        <?php
                        break;
                    case AUTO_APERTURA_PRUEBAS_R:
                        ?>
                        <input class="push" type="radio" name="gestion" onclick="fvistas('<?php echo $data['MEDIDA_CAUTELAR'] ?>', '<?php echo $data['COD_PROCESO'] ?>', '<?php echo $data['COD_TIPORESPUESTA'] ?>', '<?php echo DOC_AUTO_APERTURA_PRUEBAS ?>', '<?php echo AUTO_3; ?>', '<?php echo PLANTILLA_AUTO_3 ?>', '<?php echo AUTO_APERTURA_PRUEBAS_G ?>', '<?php echo GESTION_APERTURA_PRUEBAS ?>', '<?php echo $data['AVALUOS'] ?>')"  />
                        <?php
                        break;
                    case AUTO_CORRECION_AVALUO_R:
                        ?>
                        <input class="push" type="radio" name="gestion" onclick="fvistas('<?php echo $data['MEDIDA_CAUTELAR'] ?>', '<?php echo $data['COD_PROCESO'] ?>', '<?php echo $data['COD_TIPORESPUESTA'] ?>', '<?php echo DOC_AUTO_CORRECCION_AVALUO ?>', '<?php echo AUTO_4; ?>', '<?php echo PLANTILLA_AUTO_4 ?>', '<?php echo AUTO_CORRECION_AVALUO_G ?>', '<?php echo GESTION_REALIZAR_PRUEBAS ?>', '<?php echo $data['AVALUOS'] ?>')"  />
                        <?php
                        break;
                    case AUTO_RESUELVE_OBJ_AVALUO_R:
                        ?>
                        <input class="push" type="radio" name="gestion" onclick="fvistas('<?php echo $data['MEDIDA_CAUTELAR'] ?>', '<?php echo $data['COD_PROCESO'] ?>', '<?php echo $data['COD_TIPORESPUESTA'] ?>', '<?php echo DOC_AUTO_RESUELVE_OBJECION_AVALUO ?>', '<?php echo AUTO_5; ?>', '<?php echo PLANTILLA_AUTO_5 ?>', '<?php echo AUTO_RESUELVE_OBJ_AVALUO_G ?>', '<?php echo GESTION_CORRECCION_AVALUO ?>', '<?php echo $data['AVALUOS'] ?>')"  />

                        <?php
                        break;
                    case AUTO_DECLARA_FIRMEZA_AVALUO_R:
                        ?>
                        <input class="push" type="radio" name="gestion" onclick="fvistas('<?php echo $data['MEDIDA_CAUTELAR'] ?>', '<?php echo $data['COD_PROCESO'] ?>', '<?php echo $data['COD_TIPORESPUESTA'] ?>', '<?php echo DOC_AUTO_RESUELVE_OBJECION_AVALUO ?>', '<?php echo AUTO_5; ?>', '<?php echo PLANTILLA_AUTO_5 ?>', '<?php echo AUTO_RESUELVE_OBJ_AVALUO_G ?>', '<?php echo GESTION_CORRECCION_AVALUO ?>', '<?php echo $data['AVALUOS'] ?>')"  />
                    <?php
                    case AUTO_DICTAMEN_TRASLADO_APROBADO_RECHAZADO:
                        ?>
                        <input class="push" type="radio" name="gestion" onclick="fvistas('<?php echo $data['MEDIDA_CAUTELAR'] ?>', '<?php echo $data['COD_PROCESO'] ?>', '<?php echo $data['COD_TIPORESPUESTA'] ?>', '<?php echo DOC_DICTAMEN_AVALUO ?>', '<?php echo AUTO_7; ?>', '<?php echo PLANTILLA_AUTO_7 ?>', '<?php echo AUTO_DICTAMEN_TRASLADO_GENERADO ?>', '<?php echo AUTO_NOMBRA_PERITO ?>', '<?php echo $data['AVALUOS'] ?>')"  />
                        <?php
                        break;
                        break;
                    case NOT_PERSONAL_ENVIADA:
                        ?>
                        <input class="push" type="radio" name="gestion" onclick="fvistas('<?php echo $data['MEDIDA_CAUTELAR'] ?>', '<?php echo $data['COD_PROCESO'] ?>', '<?php echo $data['COD_TIPORESPUESTA'] ?>', <?php echo DOC_NOTIFICACION_PERSONAL ?>, '<?php echo NOTIFICACION_PERSONAL ?>', '0', '0', '0', '<?php echo $data['AVALUOS'] ?>')"  />

                        <?php
                        break;
                    case NOTIFICACION_POR_PAGINA_WEB_PRE_APROBADA:
                        ?>                                                                                                                                                                                                                                                <!-- tipo documento-->           <!--titulo documento-->     <!--tipogestion-->                
                        <input class="push" type="radio" name="secretario" onclick="fvistas('<?php echo $data['MEDIDA_CAUTELAR'] ?>', '<?php echo $data['COD_PROCESO'] ?>', '<?php echo $data['COD_TIPORESPUESTA'] ?>', '<?php echo DOC_NOTIFICACION_WEB ?>', '<?php echo NOTIFICACION_WEB; ?>', '<?php echo GESTION_NOTIFICACION_WEB ?>', '<?php echo NOTIFICACION_POR_PAGINA_WEB_PRE_APROBADA ?>', '<?php echo NOTIFICACION_POR_PAGINA_WEB_PRE_APROBADA ?>', '<?php echo $data['AVALUOS'] ?>')"  />
                        <?php
                        break;
                    
                    case NOTIFICACION_PERSONAL_DEVUELTA:
                        ?>
                        <input class="push" type="radio" name="gestion" onclick="corregir_auto('<?php echo $data['COD_AVALUO'] ?>', '<?php echo $data['COD_TIPORESPUESTA'] ?>', '<?php echo $data['COD_FISCALIZACION'] ?>', '<?php echo $data['NIT_EMPRESA'] ?>', '12', '<?php echo DOC_NOTIFICACIONCORREO ?>', '<?php echo DOC_NOTIFICACION_CORREO; ?>', '0', '<?php echo NOTIFICACION_CORREO_GENERADA ?>', '<?php echo GESTION_AVALUO_NOTIFICACION ?>')"  />
                        <?php
                        break;
                    //Permite Adjuntar los documentos (pdf) de los autos aprobados y firmados
                    case AUTO_AVALUOBIENES_APROBADO:
                        ?><!--cod_avaluo, cod_fisc, nit,  cod_proceso, tipo_doc, titulo del documento, id_plantilla,el proximo codigo de respuesta, tipo_gestion -->  
                        <input class="push" type="radio" name="gestion" onclick="fvistas('<?php echo $data['MEDIDA_CAUTELAR'] ?>', '<?php echo $data['COD_PROCESO'] ?>', '<?php echo $data['COD_TIPORESPUESTA'] ?>', '<?php echo DOC_AUTO_ORDENA_AVALUO ?>', '<?php echo AUTO_1; ?>', '<?php echo PLANTILLA_AUTO_1 ?>', '<?php echo AUTO_AVALUOBIENES_APROBADOFIRMADO ?>', '<?php echo AUTO_ORDENA_AVALUO ?>', '<?php echo $data['AVALUOS'] ?>')"  />
                        <?php
                        break;
                    case AUTO_NOMBRA_PERITO_APROBADO:
                        ?><!--cod_avaluo, cod_fisc, nit,  cod_proceso, tipo_doc, titulo del documento, id_plantilla,el proximo codigo de respuesta, tipo_gestion -->  
                        <input class="push" type="radio" name="gestion" onclick="fvistas('<?php echo $data['MEDIDA_CAUTELAR'] ?>', '<?php echo $data['COD_PROCESO'] ?>', '<?php echo $data['COD_TIPORESPUESTA'] ?>', '<?php echo DOC_AUTO_NOMBRA_PERITO ?>', '<?php echo AUTO_2; ?>', '<?php echo PLANTILLA_AUTO_2 ?>', '<?php echo AUTO_NOMBRA_PERITO_APROBADOFIRMADO ?>', '<?php echo AUTO_NOMBRA_PERITO ?>', '<?php echo $data['AVALUOS'] ?>')"  />
                        <?php
                        break;
                    case AUTO_DECLARA_FIRMEZA_AVALUO_A:
                        ?><!--cod_avaluo, cod_fisc, nit,  cod_proceso, tipo_doc, titulo del documento, id_plantilla,el proximo codigo de respuesta, tipo_gestion -->  
                        <input class="push" type="radio" name="gestion" onclick="fvistas('<?php echo $data['MEDIDA_CAUTELAR'] ?>', '<?php echo $data['COD_PROCESO'] ?>', '<?php echo $data['COD_TIPORESPUESTA'] ?>', '<?php echo DOC_AUTO_DECLARA_FIRMEZA_AVALUO ?>', '<?php echo AUTO_6; ?>', '<?php echo PLANTILLA_AUTO_6 ?>', '<?php echo AUTO_DECLARA_FIRMEZA_AVALUO_F ?>', '<?php echo GESTION_FIRMEZA_AVALUO ?>', '<?php echo $data['AVALUOS'] ?>')"  />
                        <?php
                        break;
                    case AUTO_RESUELVE_OBJ_AVALUO_A:
                        ?><!--cod_avaluo, cod_fisc, nit,  cod_proceso, tipo_doc, titulo del documento, id_plantilla,el proximo codigo de respuesta, tipo_gestion -->  
                        <input class="push" type="radio" name="gestion" onclick="fvistas('<?php echo $data['MEDIDA_CAUTELAR'] ?>', '<?php echo $data['COD_PROCESO'] ?>', '<?php echo $data['COD_TIPORESPUESTA'] ?>', '<?php echo DOC_AUTO_RESUELVE_OBJECION_AVALUO ?>', '<?php echo AUTO_5; ?>', '<?php echo PLANTILLA_AUTO_5 ?>', '<?php echo AUTO_RESUELVE_OBJ_AVALUO_F ?>', '<?php echo GESTION_RESUELVE_OBJECION ?>', '<?php echo $data['AVALUOS'] ?>')"  />
                        <?php
                        break;
                    case AUTO_APERTURA_PRUEBAS_APROBADO:
                        ?><!--cod_avaluo, cod_fisc, nit,  cod_proceso, tipo_doc, titulo del documento, id_plantilla,el proximo codigo de respuesta, tipo_gestion -->  
                        <input class="push" type="radio" name="gestion" onclick="fvistas('<?php echo $data['MEDIDA_CAUTELAR'] ?>', '<?php echo $data['COD_PROCESO'] ?>', '<?php echo $data['COD_TIPORESPUESTA'] ?>', '<?php echo DOC_AUTO_APERTURA_PRUEBAS ?>', '<?php echo AUTO_3; ?>', '<?php echo PLANTILLA_AUTO_3 ?>', '<?php echo AUTO_APERTURA_PRUEBAS_F ?>', '<?php echo GESTION_APERTURA_PRUEBAS ?>', '<?php echo $data['AVALUOS'] ?>')"  />
                        <?php
                        break;
                    case AUTO_DICTAMEN_TRASLADO_APROBADO:
                        ?><!--cod_avaluo, cod_fisc, nit,  cod_proceso, tipo_doc, titulo del documento, id_plantilla,el proximo codigo de respuesta, tipo_gestion -->  
                        <input class="push" type="radio" name="gestion" onclick="fvistas('<?php echo $data['MEDIDA_CAUTELAR'] ?>', '<?php echo $data['COD_PROCESO'] ?>', '<?php echo $data['COD_TIPORESPUESTA'] ?>', '<?php echo DOC_DICTAMEN_AVALUO ?>', '<?php echo AUTO_7; ?>', '<?php echo 0 ?>', '<?php echo AUTO_DICTAMEN_TRASLADO_APROBADO_FIRMADO ?>', '<?php echo GESTION_AUTO_DICTAMEN ?>', '<?php echo $data['AVALUOS'] ?>')"  />
                        <?php
                        break;
                    case AVALUO_RECIBO_PAGO_RECIBIDO:
                        ?>                                
                        <!--cod_avaluo, cod_fisc, nit,  cod_proceso, tipo_doc, titulo del documento, id_plantilla,el proximo codigo de respuesta, tipo_gestion -->  
                        <input class="push" type="radio" name="gestion" onclick="fvistas('<?php echo $data['MEDIDA_CAUTELAR'] ?>', '<?php echo $data['COD_PROCESO'] ?>', '<?php echo $data['COD_TIPORESPUESTA'] ?>', '<?php echo DOC_NOTIFICACIONCORREO ?>', '<?php echo DOC_NOTIFICACION_CORREO ?>', '<?php echo PLANTILLA_AUTO_3 ?>', '<?php echo NOTIFICACION_CORREO_GENERADA ?>', '<?php echo GESTION_AVALUO_NOTIFICACION ?>', '<?php echo $data['AVALUOS'] ?>')"  />
                        <?php
                        break;
                    case NOT_PERSONAL_REVISADA_APROBADA:
                        ?>                                                                                                                                                                                                                                                <!-- tipo documento-->           <!--titulo documento-->     <!--tipogestion-->                
                        <input class="push" type="radio" name="gestion" onclick="fvistas('<?php echo $data['MEDIDA_CAUTELAR'] ?>', '<?php echo $data['COD_PROCESO'] ?>', '<?php echo $data['COD_TIPORESPUESTA'] ?>', '<?php echo DOC_NOTIFICACION_PERSONAL; ?>', '<?php echo NOTIFICACION_PERSONAL; ?>', '<?php echo GESTION_NOTIFICACION_PERSONAL ?>', '<?php echo NOT_PERSONAL_ENVIADA ?>', '<?php echo NOT_PERSONAL_ENVIADA ?>', '<?php echo $data['AVALUOS'] ?>')"  />
                        <?php
                        break;
                    case NOTIFICACION_POR_CORREO_APROBADA_FIRMADA:
                        ?>                                                                                                                                                                                                                                                <!-- tipo documento-->           <!--titulo documento-->     <!--tipogestion-->                
                        <input class="push" type="radio" name="gestion" onclick="fvistas('<?php echo $data['MEDIDA_CAUTELAR'] ?>', '<?php echo $data['COD_PROCESO'] ?>', '<?php echo $data['COD_TIPORESPUESTA'] ?>', '<?php echo DOC_NOTIFICACIONCORREO ?>', '<?php echo DOC_NOTIFICACION_CORREO; ?>', '<?php echo GESTION_NOTIFICACION_CORREO ?>', '<?php echo NOTIFICACION_POR_CORREO_ENVIADA ?>', '<?php echo NOTIFICACION_POR_CORREO_ENVIADA ?>', '<?php echo $data['AVALUOS'] ?>')"  />
                        <?php
                        break;
                    case NOTIFICACION_POR_CORREO_ENVIADA:
                        ?>                                                                                                                                                                                                                                                <!-- tipo documento-->           <!--titulo documento-->     <!--tipogestion-->                
                        <input class="push" type="radio" name="gestion" onclick="fvistas('<?php echo $data['MEDIDA_CAUTELAR'] ?>', '<?php echo $data['COD_PROCESO'] ?>', '<?php echo $data['COD_TIPORESPUESTA'] ?>', '<?php echo DOC_NOTIFICACIONCORREO; ?>', '<?php echo DOC_NOTIFICACION_CORREO ?>', '<?php echo GESTION_NOTIFICACION_CORREO ?>', '<?php echo NOTIFICACION_POR_CORREO_ENVIADA ?>', '<?php echo NOTIFICACION_POR_CORREO_ENVIADA ?>', '<?php echo $data['AVALUOS'] ?>')"  />
                        <?php
                        break;
                    //Cuando el deudor recibe la notificación personalcuenta con 10 días para dar respuesta 
                    case NOTIFICACION_PERSONAL_RECIBIDA:
                        ?>
                        <a target="_blank"  class="btn btn-small btn-info" title="ver" onclick="f_bloqueo('160', '<?php echo $data['NOTIFICACION_EFECTIVA'] ?>', '<?php echo $data['COD_PROCESO'] ?>', '<?php echo $data['AVALUOS'] ?>', '<?php echo NOTIFICACION_POR_CORREO_RECIBIDA ?>')">
                            <i class="fa fa-question  "></i>
                        </a>
                        <?php
                        break;
                    //Cuando el deudor recibe la notificación por correo cuenta con 10 días para dar respuesta 

                    case NOTIFICACION_POR_CORREO_RECIBIDA:
                        ?>
                        <a target="_blank"  class="btn btn-small btn-info" title="ver" onclick="f_bloqueo('160', '<?php echo $data['NOTIFICACION_EFECTIVA'] ?>', '<?php echo $data['COD_PROCESO'] ?>', '<?php echo $data['AVALUOS'] ?>', '<?php echo NOTIFICACION_POR_CORREO_RECIBIDA ?>')">
                            <i class="fa fa-question  "></i>
                        </a>
                    <?php
                    case NOTIFICACION_POR_CORREO_DEVUELTA:
                        ?>
                        <input class="push" type="radio" name="gestion" onclick="fvistas('<?php echo $data['MEDIDA_CAUTELAR'] ?>', '<?php echo $data['COD_PROCESO'] ?>', '<?php echo $data['COD_TIPORESPUESTA'] ?>', '<?php echo DOC_NOTIFICACION_WEB; ?>', '<?php echo NOTIFICACION_WEB ?>', '<?php echo GESTION_NOTIFICACION_CORREO ?>', '<?php echo NOTIFICACION_POR_PAGINA_WEB_GENERADA ?>', '<?php echo NOTIFICACION_POR_PAGINA_WEB_GENERADA ?>', '<?php echo $data['AVALUOS'] ?>')"  />
                        <?php
                        break;
                    case NOTIFICACION_POR_PAGINA_WEB_PUBLICADA:
                        ?>
                        <a target="_blank"  class="btn btn-small btn-info" title="ver" onclick="f_bloqueo('160', '<?php echo $data['NOTIFICACION_EFECTIVA'] ?>', '<?php echo $data['COD_PROCESO'] ?>', '<?php echo $data['AVALUOS'] ?>', '<?php echo NOTIFICACION_POR_CORREO_RECIBIDA ?>')">
                            <i class="fa fa-question  "></i>
                        </a>

                        <?php
                        break;
                    case NOTIFICACION_CORREO_APROBADA:
                        ?><!--cod_avaluo, cod_fisc, nit,  cod_proceso, tipo_doc, titulo del documento, id_plantilla,el proximo codigo de respuesta, tipo_gestion -->  
                        <input class="push" type="radio" name="gestion" onclick="fvistas('<?php echo $data['MEDIDA_CAUTELAR'] ?>', '<?php echo $data['COD_PROCESO'] ?>', '<?php echo $data['COD_TIPORESPUESTA'] ?>', '<?php echo DOC_NOTIFICACION_CORREO ?>', '<?php echo DOC_NOTIFICACION_CORREO; ?>', '<?php echo PLANTILLA_AUTO_1 ?>', '<?php echo NOTIFICACION_CORREO_APROBADA_FIRMADA ?>', '<?php echo GESTION_AVALUO_NOTIFICACION ?>', '<?php echo $data['AVALUOS'] ?>')"  />
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
    <br>
    <form id="form_bandeja" action="<?php echo base_url('index.php/bandejaunificada/index'); ?>" method="post">
        <input type="hidden" id="cod_coactivo" name="cod_coactivo" value="<?php echo $cod_coactivo; ?>">
        <button class="btn btn"> Regresar </button>
    </form>
    <div id="vista" style="margin-top:0px; ">
        <div id="div_contenido" style="margin-top:0px; ">
            <div id="div_respuesta"> <div id="resultado"></div></div></div>
    </div> 
    <div id="div_bloqueo"></div>
    <div id="resultado"></div>

    <script type="text/javascript" language="javascript" charset="utf-8">
        function f_bloqueo(gestion, fecha, codcoactivo, avaluos, respuesta)
        {
            $("#ajax_load").css("display", "block");

            var ruta = "<?php echo base_url("index.php/tiempos/bloqueo"); ?>";
            $.post(ruta, {gestion: gestion, fecha: fecha, codcoactivo: codcoactivo})
                    .done(function(msg) {
                        var texto = msg.texto;
                        var comienza = msg.comienza;
                        var vence = msg.vence;
                        if (msg.bandera == "0") {
                            vence = "Recordatorio Pendiente";
                            comienza = "Recordatorio Pendiente";
                            texto = "Recordatorio Pendiente";
                        }
                        if ($.trim(msg.vencido) == "1") {
                            var url = "<?php echo base_url("index.php/mc_avaluo/bloqueo"); ?>";
                            $('#div_bloqueo').load(url, {gestion: gestion, fecha: fecha, num_citacion: "1", texto: texto, comienza: comienza, vence: vence, avaluos: avaluos, cod_coactivo: codcoactivo, avaluos:avaluos, respuesta: respuesta});
                        } else {
                            var url = "<?php echo base_url('index.php/mc_avaluo/bloqueo_por_time'); ?>";
                            $('#div_bloqueo').load(url, {gestion: gestion, fecha: fecha, num_citacion: "1", texto: texto, comienza: comienza, vence: vence, avaluos: avaluos, cod_coactivo: codcoactivo, avaluos:avaluos, respuesta: respuesta});
                        }
                    }).fail(function(msg) {
                alert("ERROR");
            })

        }
        $(document).ready(function() {
            window.history.forward(-1);
        });
        function fvistas(medida_cautelar, cod_proceso, respuesta, tipo_doc, titulo, id_plantilla, cod_siguiente, tipo_gestion, avaluos) {
            $("#ajax_load").css('display', 'block');
            $("#load").css('display', 'block');
            $("#preload").css('display', 'block');
            var url = "<?= base_url("index.php/mc_avaluo/vistas") ?>";
            $('#resultado').load(url, {medida_cautelar: medida_cautelar, cod_proceso: cod_proceso, respuesta: respuesta, tipo_doc: tipo_doc, titulo: titulo, id_plantilla: id_plantilla, cod_siguiente: cod_siguiente, tipo_gestion: tipo_gestion, avaluos: avaluos});
        }
        function adjuntar_documento(id, respuesta, cod_fisc, nit, cod_proceso, tipo_doc, titulo, id_plantilla, cod_siguiente, tipo_gestion)
        {
            $("#ajax_load").css('display', 'block');
            var url = "<?= base_url("index.php/mc_avaluo/genera_documento") ?>";
            $('#resultado').load(url, {id: id, respuesta: respuesta, cod_fisc: cod_fisc, nit: nit, cod_proceso: cod_proceso, tipo_doc: tipo_doc, titulo: titulo, id_plantilla: id_plantilla, cod_siguiente: cod_siguiente, tipo_gestion: tipo_gestion});
        }




        function registrar_pagohonorarios(medida_cautelar, cod_proceso, respuesta, tipo_doc, titulo, id_plantilla, cod_siguiente, tipo_gestion, avaluos)
        {
            $("#ajax_load").css('display', 'block');
            var url = "<?= base_url("index.php/mc_avaluo/vistas") ?>";
            $('#resultado').load(url, {medida_cautelar: medida_cautelar, cod_proceso: cod_proceso, respuesta: respuesta, tipo_doc: tipo_doc, titulo: titulo, id_plantilla: id_plantilla, cod_siguiente: cod_siguiente, tipo_gestion: tipo_gestion, avaluos: avaluos});



        }

        function genera_notificacion(medida_cautelar, cod_proceso, respuesta, tipo_doc, titulo, id_plantilla, cod_siguiente, tipo_gestion, avaluos) {
            $("#ajax_load").css('display', 'block');
            var url = "<?= base_url("index.php/mc_avaluo/genera_notificacion") ?>";

            $('#resultado').load(url, {id: id, respuesta: respuesta, cod_fisc: cod_fisc, nit: nit, cod_proceso: cod_proceso, tipo_doc: tipo_doc, titulo: titulo, id_plantilla: id_plantilla, cod_siguiente: cod_siguiente, tipo_gestion: tipo_gestion});

        }

        function objecion(id, respuesta, cod_fisc, nit, cod_proceso)
        {
            $("#ajax_load").show();
            var url = "<?= base_url("index.php/mc_avaluo/objecion") ?>";
            $.post(url, {id: id, respuesta: respuesta, cod_fisc: cod_fisc, nit: nit, cod_proceso: cod_proceso}, function(data) {
                $("#resultado").html(data);
            })
        }
        function secretario(id, respuesta, cod_fisc, nit, cod_proceso, tipo_doc, titulo, tipo_gestion, cod_devolucion, cod_aprobacion)
        {
            $("#ajax_load").show();
            var url = "<?= base_url("index.php/mc_avaluo/vistas") ?>";
            $.post(url, {id: id, respuesta: respuesta, cod_fisc: cod_fisc, nit: nit, cod_proceso: cod_proceso, tipo_doc: tipo_doc, titulo: titulo, tipo_gestion: tipo_gestion, respuesta:respuesta, cod_devolucion: cod_devolucion, cod_aprobacion: cod_aprobacion}, function(data) {
                $("#resultado").html(data);
            })

        }
        $('#tabla1').dataTable({
            "bJQueryUI": true,
            "sServerMethod": "POST",
            "bProcessing": true,
            "sSearch": '',
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