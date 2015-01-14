<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<?php
$inmueble = 0;
$mueble = 0;
$vehiculo = 0;
$tipo1 = "PENDIENTE";
$tipo2 = "PENDIENTE";
$tipo3 = "PENDIENTE";
$id_prelacion1 = "";
$id_prelacion2 = "";
$id_prelacion3 = "";
$fecha1 = "";
$fecha2 = "";
$fecha3 = "";
foreach ($consulta as $info) {
    if ($info['COD_CONCURRENCIA'] == 1) {
        $mueble = $info['TIPO'];
        $tipo1 = $info['TIPOGESTION'];
        $id_prelacion1 = $info['COD_MEDIDAPRELACION'];
        $fecha1 = $info['FECHA'];
    } else if ($info['COD_CONCURRENCIA'] == 2) {
        $inmueble = $info['TIPO'];
        $tipo2 = $info['TIPOGESTION'];
        $id_prelacion2 = $info['COD_MEDIDAPRELACION'];
        $fecha2 = $info['FECHA'];
    } else if ($info['COD_CONCURRENCIA'] == 3) {
        $vehiculo = $info['TIPO'];
        $tipo3 = $info['TIPOGESTION'];
        $id_prelacion3 = $info['COD_MEDIDAPRELACION'];
        $fecha3 = $info['FECHA'];
    }
}
?>

<table width="100%" class="table table-striped">
    <tr >
        <td><b><center>BIENES</center></b></td>
        <td><b><center>ESTADO</center></b></td>
        <td><b><center>GESTI&Oacute;N</center></b></td>
    </tr>
    <tr>
        <td>Mueble</td>
        <td><?php echo $tipo1 ?></td>
        <td> <center>
            <?php
            $check = "mueble_ini";
            
            $array=array(318,367,664,339,665,343,666,355,670,351,671,359,673,955,996,994,1004,1007,1048,1051,972,963,980,983,1141,1144,973,968,985,988,647,322,676,326,1040,1043,677,362,678,335,300,304,684,682,689,687,694,692,701,699,705,704,707,331,712,710,722,720,717,715,729,727,734,732,739,738,740,374,734,732,844,324,668,679,1057,663,960,979,1044,971,998,1001,1003,1046,1045,1047,974,1002,675,1034,724,696,669,957,999,1009,997,1000,1053,978,627,340,344,354,352,358,956,995,1008,1052,964,1145,969,984,989,649,325,1042,363,683,688,336,303,691,700,706,332,1369,716,719,728,733,737,375,711);
            if((in_array($mueble, $array)==true || $mueble=='PENDIENTE') && $post['permiso'] != "1" ){
                echo 'Abogado';
            }
            $array=array(364,337,341,353,349,357,953,992,1005,1049,961,1142,981,966,986,320,324,1041,360,333,301,680,685,690,697,702,329,708,713,725,718,730,735,372);
            if(in_array($mueble, $array)==true  && $post['permiso'] != "2" ){
                echo 'Secretario';
            }
            $array=array(365,338,342,667,350,672,954,993,1006,1050,965,1143,982,967,987,321,325,361,681,686,334,302,695,698,703,330,714,723,726,731,736,373,709);
            if(in_array($mueble, $array)==true  && $post['permiso'] != "3" ){
                echo 'Funcionario Ejecutor';
            }
            
            
            //echo $mueble;
            if ($post['permiso'] == "1") {
                seleccion_abogado($mueble, $post['id'], $post['cod_fis'], $post['nit'], $id_prelacion1, $check, $fecha1);
            }
            if ($post['permiso'] == "2") {
                seleccion_secretario($mueble, $post['id'], $post['cod_fis'], $post['nit'], $id_prelacion1, $check, $fecha1);
            }
            if ($post['permiso'] == "3") {
                seleccion_coordinador($mueble, $post['id'], $post['cod_fis'], $post['nit'], $id_prelacion1, $check, $fecha1);
            }
            ?></center>
        </td>
    </tr>
    <tr>
        <td>Bienes Inmuebles</td>
        <td><?php echo $tipo2 ?></td>
        <td><center>
            <?php
            $check = "inmueble_ini";
            //echo $inmueble;
            
            $array=array(318,367,664,339,665,343,666,355,670,351,671,359,673,955,996,994,1004,1007,1048,1051,972,963,980,983,1141,1144,973,968,985,988,647,322,676,326,1040,1043,677,362,678,335,300,304,684,682,689,687,694,692,701,699,705,704,707,331,712,710,722,720,717,715,729,727,734,732,739,738,740,374,734,732,844,324,668,679,1057,663,960,979,1044,971,998,1001,1003,1046,1045,1047,974,1002,675,1034,724,696,669,957,999,1009,997,1000,1053,978,627,340,344,354,352,358,956,995,1008,1052,964,1145,969,984,989,649,325,1042,363,683,688,336,303,691,700,706,332,1369,716,719,728,733,737,375,711);
            if((in_array($inmueble, $array)==true || $inmueble=='PENDIENTE') && $post['permiso'] != "1" ){
                echo 'Abogado';
            }
            $array=array(364,337,341,353,349,357,953,992,1005,1049,961,1142,981,966,986,320,324,1041,360,333,301,680,685,690,697,702,329,708,713,725,718,730,735,372);
            if(in_array($inmueble, $array)==true && $post['permiso'] != "2" ){
                echo 'Secretario';
            }
            $array=array(365,338,342,667,350,672,954,993,1006,1050,965,1143,982,967,987,321,325,361,681,686,334,302,695,698,703,330,714,723,726,731,736,373,709);
            if(in_array($inmueble, $array)==true  && $post['permiso'] != "3" ){
                echo 'Funcionario Ejecutor';
            }
            
            if ($post['permiso'] == "1") {
                seleccion_abogado($inmueble, $post['id'], $post['cod_fis'], $post['nit'], $id_prelacion2, $check, $fecha2);
            }
            if ($post['permiso'] == "2") {
                seleccion_secretario($inmueble, $post['id'], $post['cod_fis'], $post['nit'], $id_prelacion2, $check, $fecha2);
            }
            if ($post['permiso'] == "3") {
                seleccion_coordinador($inmueble, $post['id'], $post['cod_fis'], $post['nit'], $id_prelacion2, $check, $fecha2);
            }
            ?></center>
        </td>
    </tr>
    <tr>
        <td>Veh&iacute;culo</td>
        <td><?php echo $tipo3 ?></td>
        <td><center>
            <?php
            $check = "vehiculo";
            //echo $vehiculo;
            
            $array=array(318,367,664,339,665,343,666,355,670,351,671,359,673,955,996,994,1004,1007,1048,1051,972,963,980,983,1141,1144,973,968,985,988,647,322,676,326,1040,1043,677,362,678,335,300,304,684,682,689,687,694,692,701,699,705,704,707,331,712,710,722,720,717,715,729,727,734,732,739,738,740,374,734,732,844,324,668,679,1057,663,960,979,1044,971,998,1001,1003,1046,1045,1047,974,1002,675,1034,724,696,669,957,999,1009,997,1000,1053,978,627,340,344,354,352,358,956,995,1008,1052,964,1145,969,984,989,649,325,1042,363,683,688,336,303,691,700,706,332,1369,716,719,728,733,737,375,711);
            if((in_array($vehiculo, $array)==true || $vehiculo=='PENDIENTE') && $post['permiso'] != "1" ){
                echo 'Abogado';
            }
            $array=array(364,337,341,353,349,357,953,992,1005,1049,961,1142,981,966,986,320,324,1041,360,333,301,680,685,690,697,702,329,708,713,725,718,730,735,372);
            if(in_array($vehiculo, $array)==true  && $post['permiso'] != "2" ){
                echo 'Secretario';
            }
            $array=array(365,338,342,667,350,672,954,993,1006,1050,965,1143,982,967,987,321,325,361,681,686,334,302,695,698,703,330,714,723,726,731,736,373,709);
            if(in_array($vehiculo, $array)==true &&  $post['permiso'] != "3" ){
                echo 'Funcionario Ejecutor';
            }
            
            
            if ($post['permiso'] == "1") {
                seleccion_abogado($vehiculo, $post['id'], $post['cod_fis'], $post['nit'], $id_prelacion3, $check, $fecha3);
            }
            if ($post['permiso'] == "2") {
                seleccion_secretario($vehiculo, $post['id'], $post['cod_fis'], $post['nit'], $id_prelacion3, $check, $fecha3);
            }
            if ($post['permiso'] == "3") {
                seleccion_coordinador($vehiculo, $post['id'], $post['cod_fis'], $post['nit'], $id_prelacion3, $check, $fecha3);
            }
            ?></center>
        </td>
    </tr>
</table>
<?php
$union = $vehiculo . "_" . $inmueble . "_" . $mueble;
?>
<table class="table table-striped">
    <tr>
        <?php
        switch ($union) {
            case DILIGENCIA_SECUESTRO:
                if ($post['permiso'] == "1") {
                    ?>

                    <td><b>Los procesos se encuentran en el mismo estado</b></td>
                    <td><input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $post['id'] ?>', '<?php echo $post['cod_fis'] ?>', '<?php echo $post['nit'] ?>',
                                            '<?php echo DILIGENCIA_GENERAL;
                    ?>', '<?php echo ""; ?>', '<?php echo MUEBLES_NUM; ?>', '0', '<?php echo DILIGENCIA_SECUESTRO_SECRETARIO; ?>', '<?php echo $id_prelacion1; ?>')"  /></td>
                        <?php
                    }
                    break;
                case DILIGENCIA_SECUESTRO_SECRETARIO:
                    if ($post['permiso'] == "2") {
                        ?>
                    <td><b>Los procesos se encuentran en el mismo estado</b></td>
                    <td>
                        <input class="push" type="radio" name="gestion" onclick="secretaria('<?php echo $post['id'] ?>', '<?php echo $post['cod_fis'] ?>',
                                            '<?php echo $post['nit'] ?>', '<?php echo $post['id'] ?>', '<?php echo DILIGENCIA_GENERAL; ?>', '<?php echo MUEBLES_NUM; ?>',
                                            '<?php echo DILIGENCIA_SECUESTRO_DEVOLUCION; ?>', '<?php echo $respuesta = 1; ?>', '<?php echo DILIGENCIA_SECUESTRO_COORDINADOR; ?>',
                                            '<?php echo DILIGENCIA_SECUESTRO_COORDINADOR; ?>', '<?php echo $id_prelacion1; ?>')"  />
                    </td>

                    <?php
                }
                break;
                case DILIGENCIA_SECUESTRO_DEVOLUCION:
                    if ($post['permiso'] == "1") {
                        ?>
                    <td><b>Los procesos se encuentran en el mismo estado</b></td>
                    <td>
                        <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $post['id'] ?>', '<?php echo $post['cod_fis'] ?>', '<?php echo $post['nit'] ?>',
                                        '<?php echo DILIGENCIA_GENERAL; ?>', '<?php echo ""; ?>', '<?php echo MUEBLES_NUM; ?>', '0', '<?php echo DILIGENCIA_SECUESTRO_ENVIAR_MODIFICACIONES; ?>', '<?php echo $id_prelacion1; ?>')"  />
                    </td>

                    <?php
                }
                break;
                case DILIGENCIA_SECUESTRO_COORDINADOR:
                    if ($post['permiso'] == "3") {
                        ?>
                    <td><b>Los procesos se encuentran en el mismo estado</b></td>
                    <td>
                        <input class="push" type="radio" name="gestion" onclick="coordinador('<?php echo $post['id'] ?>', '<?php echo $post['cod_fis'] ?>',
                                        '<?php echo $post['nit'] ?>', '<?php echo $post['id'] ?>', '<?php echo DILIGENCIA_GENERAL; ?>', '<?php echo MUEBLES_NUM; ?>', '<?php echo DILIGENCIA_SECUESTRO_DEVOLUCION; ?>', '0', '',
                                        '<?php echo DILIGENCIA_SECUESTRO_SUBIR_ARCHIVO; ?>', '<?php echo $id_prelacion1; ?>')"  />
                    </td>

                    <?php
                }
                break;
        }
        ?>
    </tr>
</table>
<table width="100%">
    <?php
    if ($tipo1 == "PENDIENTE" &&
            $tipo2 == "PENDIENTE" &&
            $tipo3 == "PENDIENTE") {
        ?>
        <tr>
            <td colspan="3" align="center"><hr></td>
        </tr>
        <tr>
            <td colspan="3" align="center">EL cliente no posee Inmueble, Mueble, Veh&iacute;culo</td>
        </tr>
        <tr>
            <td colspan="3" align="center"><input type="radio" id="notiene" name="notiene"></td>
        </tr>
    <?php } ?>
</table>
<div id="resul"></div>
<script>
    id = "<?php echo $post['id'] ?>";
    cod_fis = "<?php echo $post['cod_fis'] ?>";
    nit = "<?php echo $post['nit'] ?>";
    titulo = "<?php echo $post['titulo'] ?>";

    jQuery(".preload, .load").hide();
    $('#resultado').dialog({
        autoOpen: true,
        width: 800,
        modal: true,
        title: "<?php echo $post['titulo']; ?>",
        close: function() {
            $('#resultado *').remove();
        }
    });

    $('#mueble_ini').click(function() {
        var chek = document.getElementById("mueble_ini").checked;
        if (chek == true) {
            direccion("1", "Muebles");
        }
    });
    $('#inmueble_ini').click(function() {
        var chek = document.getElementById("inmueble_ini").checked;
        if (chek == true) {
            direccion("2", "Inmuebles");
        }
    });
    $('#vehiculo').click(function() {
        var chek = document.getElementById("vehiculo").checked;
        if (chek == true) {
            direccion("3", "Vehiculo");
        }
    });
    function direccion(dato, detalle) {
//        $('#resultado *').remove();
//        $('#resultado').dialog("close");
        jQuery(".preload, .load").show();
        var url = "<?php echo base_url('index.php/mcinvestigacion/prelacion_detalle') ?>";
        $('#resul').load(url, {dato: dato, detalle: detalle, nit: nit, id: id, cod_fis: cod_fis, titulo: titulo});
    }
    function llego(id, cod_fis, nit, id, titulo, gestion, id_prelacion, fecha, tipo_doc) {
        $(".preload, .load").show();
        var ruta = "<?php echo base_url("index.php/tiempos/bloqueo"); ?>";
        $.post(ruta, {gestion: 71, fecha: fecha})
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
                        var url = "<?php echo base_url("index.php/mcinvestigacion/bloqueo"); ?>";
                        $('#resultado').load(url, {id: id, tipo_doc: tipo_doc, cod_fis: cod_fis, nit: nit, titulo: titulo, id_prelacion: id_prelacion, gestion: gestion, fecha: fecha, texto: texto, comienza: comienza, vence: vence});
                    } else {
                        var url = "<?php echo base_url('index.php/mcinvestigacion/bloqueo_por_time'); ?>";
                        $('#resultado').load(url, {id: id, tipo_doc: tipo_doc, cod_fis: cod_fis, nit: nit, titulo: titulo, id_prelacion: id_prelacion, gestion: gestion, fecha: fecha, texto: texto, comienza: comienza, vence: vence});
                    }
                }).fail(function(msg) {
            alert("ERROR");
        });
    }
    $('#notiene').click(function() {
        var r = confirm('¿No se encontraron bienes desea reiniciar el proceso?');
        if (r == true) {
            reiniciar_proceso();
        }
    });
    function proceso_reinicio(id, cod_fis, nit, id) {
        var r = confirm('¿El proceso de medidas cautelares se va a reiniciar?');
        if (r == true) {
            reiniciar_proceso2(id, cod_fis, nit, id);
        }
    }
    function reiniciar_proceso() {
        $(".preload, .load").show();
        var ruta = "<?php echo base_url("index.php/mcinvestigacion/reiniciar_proceso"); ?>";
        $.post(ruta, {id: id, cod_fis: cod_fis, nit: nit})
                .done(function(msg) {
                    window.location.reload();
                }).fail(function(msg) {
            $(".preload, .load").hide();
            alert("ERROR");
        });
    }
    function reiniciar_proceso2(id, cod_fis, nit, id) {
        $(".preload, .load").show();
        var ruta = "<?php echo base_url("index.php/mcinvestigacion/reiniciar_proceso"); ?>";
        $.post(ruta, {id: id, cod_fis: cod_fis, nit: nit})
                .done(function(msg) {
                    window.location.reload();
                }).fail(function(msg) {
            $(".preload, .load").hide();
            alert("ERROR");
        });
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

<?php

function seleccion_abogado($identificador, $id, $cod_fis, $nit, $id_prelacion1, $check, $fecha) {
//    echo $identificador."***";
    switch ($identificador) {
        case MUEBLES_INICIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>',
                                        '<?php echo MUEBLES; ?>', '<?php echo ""; ?>', '<?php echo MUEBLES_NUM; ?>', '0', '<?php echo MUEBLES_SECRETARIO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        case MUEBLES_DEVOLUCION:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>',
                                        '<?php echo MUEBLES; ?>', '<?php echo ""; ?>', '<?php echo MUEBLES_NUM; ?>', '0', '<?php echo MUEBLES_ENVIAR_MODIFICACIONES; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////MUEBLES_COM_SECUESTRO//////////////////////
        case MUEBLES_COM_SECUESTRO_INICIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>',
                                        '<?php echo MUEBLES_COM_SECUESTRO; ?>', '<?php echo ""; ?>', '<?php echo MUEBLES_COM_SECUESTRO_NUM; ?>', '0',
                                        '<?php echo MUEBLES_COM_SECUESTRO_SECRETARIO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        case MUEBLES_COM_SECUESTRO_DEVOLUCION:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>',
                                        '<?php echo MUEBLES_COM_SECUESTRO; ?>',
                                        '<?php echo ""; ?>', '<?php echo MUEBLES_COM_SECUESTRO_NUM; ?>', '0',
                                        '<?php echo MUEBLES_COM_SECUESTRO_ENVIAR_MODIFICACIONES; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////MUEBLES_DES_SECUESTRO//////////////////////
        case MUEBLES_DES_SECUESTRO_INICIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>',
                                        '<?php echo MUEBLES_DES_SECUESTRO; ?>', '<?php echo ""; ?>', '<?php echo MUEBLES_DES_SECUESTRO_NUM; ?>', '0',
                                        '<?php echo MUEBLES_DES_SECUESTRO_SECRETARIO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        case MUEBLES_DES_SECUESTRO_DEVOLUCION:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>',
                                        '<?php echo MUEBLES_DES_SECUESTRO; ?>',
                                        '<?php echo ""; ?>', '<?php echo MUEBLES_DES_SECUESTRO_NUM; ?>', '0',
                                        '<?php echo MUEBLES_DES_SECUESTRO_ENVIAR_MODIFICACIONES; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////MUEBLES_RESPUESTA//////////////////////
        case MUEBLES_RESPUESTA_INICIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>',
                                        '<?php echo MUEBLES_RESPUESTA; ?>', '<?php echo ""; ?>', '<?php echo MUEBLES_RESPUESTA_NUM; ?>', '0',
                                        '<?php echo MUEBLES_RESPUESTA_SECRETARIO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        case MUEBLES_RESPUESTA_DEVOLUCION:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>',
                                        '<?php echo MUEBLES_RESPUESTA; ?>',
                                        '<?php echo ""; ?>', '<?php echo MUEBLES_RESPUESTA_NUM; ?>', '0',
                                        '<?php echo MUEBLES_RESPUESTA_ENVIAR_MODIFICACIONES; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////MUEBLES_COMISORIO//////////////////////
        case MUEBLES_COMISORIO_INICIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>',
                                        '<?php echo MUEBLES_COMISORIO; ?>', '<?php echo ""; ?>', '<?php echo MUEBLES_COMISORIO_NUM; ?>', '0',
                                        '<?php echo MUEBLES_COMISORIO_SECRETARIO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        case MUEBLES_COMISORIO_DEVOLUCION:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>',
                                        '<?php echo MUEBLES_COMISORIO; ?>',
                                        '<?php echo ""; ?>', '<?php echo MUEBLES_COMISORIO_NUM; ?>', '0',
                                        '<?php echo MUEBLES_COMISORIO_ENVIAR_MODIFICACIONES; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////MUEBLES_FECHA//////////////////////
        case MUEBLES_FECHA_INICIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>',
                                        '<?php echo MUEBLES_FECHA; ?>', '<?php echo ""; ?>', '<?php echo MUEBLES_FECHA_NUM; ?>', '0',
                                        '<?php echo MUEBLES_FECHA_SECRETARIO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        case MUEBLES_FECHA_DEVOLUCION:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>',
                                        '<?php echo MUEBLES_FECHA; ?>',
                                        '<?php echo ""; ?>', '<?php echo MUEBLES_FECHA_NUM; ?>', '0',
                                        '<?php echo MUEBLES_FECHA_ENVIAR_MODIFICACIONES; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////MUEBLES_DILIGENCIA//////////////////////
        case MUEBLES_DILIGENCIA_INICIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>',
                                        '<?php echo MUEBLES_DILIGENCIA; ?>', '<?php echo ""; ?>', '<?php echo MUEBLES_DILIGENCIA_NUM; ?>', '0',
                                        '<?php echo MUEBLES_DILIGENCIA_SECRETARIO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        case MUEBLES_DILIGENCIA_DEVOLUCION:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>',
                                        '<?php echo MUEBLES_DILIGENCIA; ?>',
                                        '<?php echo ""; ?>', '<?php echo MUEBLES_DILIGENCIA_NUM; ?>', '0',
                                        '<?php echo MUEBLES_DILIGENCIA_ENVIAR_MODIFICACIONES; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////MUEBLES_ORDEN//////////////////////
        case MUEBLES_ORDEN_INICIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>',
                                        '<?php echo MUEBLES_ORDEN; ?>', '<?php echo ""; ?>', '<?php echo MUEBLES_ORDEN_NUM; ?>', '0',
                                        '<?php echo MUEBLES_ORDEN_SECRETARIO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        case MUEBLES_ORDEN_DEVOLUCION:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>',
                                        '<?php echo MUEBLES_ORDEN; ?>',
                                        '<?php echo ""; ?>', '<?php echo MUEBLES_ORDEN_NUM; ?>', '0',
                                        '<?php echo MUEBLES_ORDEN_ENVIAR_MODIFICACIONES; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////INMUEBLES_ORDEN//////////////////////
        case INMUEBLES_ORDEN_INICIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>',
                                        '<?php echo INMUEBLES_ORDEN; ?>', '<?php echo ""; ?>', '<?php echo INMUEBLES_ORDEN_NUM; ?>', '0',
                                        '<?php echo INMUEBLES_ORDEN_SECRETARIO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        case INMUEBLES_ORDEN_DEVOLUCION:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>',
                                        '<?php echo INMUEBLES_ORDEN; ?>',
                                        '<?php echo ""; ?>', '<?php echo INMUEBLES_ORDEN_NUM; ?>', '0',
                                        '<?php echo INMUEBLES_ORDEN_ENVIAR_MODIFICACIONES; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////VEHICULO_ORDEN//////////////////////
        case VEHICULO_ORDEN_INICIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>',
                                        '<?php echo VEHICULO_ORDEN; ?>', '<?php echo ""; ?>', '<?php echo VEHICULO_ORDEN_NUM; ?>', '0',
                                        '<?php echo VEHICULO_ORDEN_SECRETARIO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        case VEHICULO_ORDEN_DEVOLUCION:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>',
                                        '<?php echo VEHICULO_ORDEN; ?>',
                                        '<?php echo ""; ?>', '<?php echo VEHICULO_ORDEN_NUM; ?>', '0',
                                        '<?php echo VEHICULO_ORDEN_ENVIAR_MODIFICACIONES; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////MUEBLES_PROYECTAR_AUTO//////////////////////
        case MUEBLES_PROYECTAR_AUTO_INICIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>',
                                        '<?php echo MUEBLES_PROYECTAR_AUTO; ?>', '<?php echo ""; ?>', '<?php echo MUEBLES_PROYECTAR_AUTO_NUM; ?>', '0',
                                        '<?php echo MUEBLES_PROYECTAR_AUTO_SECRETARIO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        case MUEBLES_PROYECTAR_AUTO_DEVOLUCION:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>',
                                        '<?php echo MUEBLES_PROYECTAR_AUTO; ?>',
                                        '<?php echo ""; ?>', '<?php echo MUEBLES_PROYECTAR_AUTO_NUM; ?>', '0',
                                        '<?php echo MUEBLES_PROYECTAR_AUTO_ENVIAR_MODIFICACIONES; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////INMUEBLES_PROYECTAR_AUTO//////////////////////
        case INMUEBLES_PROYECTAR_AUTO_INICIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>',
                                        '<?php echo INMUEBLES_PROYECTAR_AUTO; ?>', '<?php echo ""; ?>', '<?php echo INMUEBLES_PROYECTAR_AUTO_NUM; ?>', '0',
                                        '<?php echo INMUEBLES_PROYECTAR_AUTO_SECRETARIO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        case INMUEBLES_PROYECTAR_AUTO_DEVOLUCION:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>',
                                        '<?php echo INMUEBLES_PROYECTAR_AUTO; ?>',
                                        '<?php echo ""; ?>', '<?php echo INMUEBLES_PROYECTAR_AUTO_NUM; ?>', '0',
                                        '<?php echo INMUEBLES_PROYECTAR_AUTO_ENVIAR_MODIFICACIONES; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////VEHICULO_PROYECTAR_AUTO//////////////////////
        case VEHICULO_PROYECTAR_AUTO_INICIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>',
                                        '<?php echo VEHICULO_PROYECTAR_AUTO; ?>', '<?php echo ""; ?>', '<?php echo VEHICULO_PROYECTAR_AUTO_NUM; ?>', '0',
                                        '<?php echo VEHICULO_PROYECTAR_AUTO_SECRETARIO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        case VEHICULO_PROYECTAR_AUTO_DEVOLUCION:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>',
                                        '<?php echo VEHICULO_PROYECTAR_AUTO; ?>',
                                        '<?php echo ""; ?>', '<?php echo VEHICULO_PROYECTAR_AUTO_NUM; ?>', '0',
                                        '<?php echo VEHICULO_PROYECTAR_AUTO_ENVIAR_MODIFICACIONES; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////MUEBLES_PROYECTAR_RESPUESTA//////////////////////
        case MUEBLES_PROYECTAR_RESPUESTA_INICIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>',
                                        '<?php echo MUEBLES_PROYECTAR_RESPUESTA; ?>', '<?php echo ""; ?>', '<?php echo MUEBLES_PROYECTAR_RESPUESTA_NUM; ?>', '0',
                                        '<?php echo MUEBLES_PROYECTAR_RESPUESTA_SECRETARIO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        case MUEBLES_PROYECTAR_RESPUESTA_DEVOLUCION:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>',
                                        '<?php echo MUEBLES_PROYECTAR_RESPUESTA; ?>',
                                        '<?php echo ""; ?>', '<?php echo MUEBLES_PROYECTAR_RESPUESTA_NUM; ?>', '0',
                                        '<?php echo MUEBLES_PROYECTAR_RESPUESTA_ENVIAR_MODIFICACIONES; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////INMUEBLES_PROYECTAR_RESPUESTA//////////////////////
        case INMUEBLES_PROYECTAR_RESPUESTA_INICIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>',
                                        '<?php echo INMUEBLES_PROYECTAR_RESPUESTA; ?>', '<?php echo ""; ?>', '<?php echo INMUEBLES_PROYECTAR_RESPUESTA_NUM; ?>', '0',
                                        '<?php echo INMUEBLES_PROYECTAR_RESPUESTA_SECRETARIO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        case INMUEBLES_PROYECTAR_RESPUESTA_DEVOLUCION:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>',
                                        '<?php echo INMUEBLES_PROYECTAR_RESPUESTA; ?>',
                                        '<?php echo ""; ?>', '<?php echo INMUEBLES_PROYECTAR_RESPUESTA_NUM; ?>', '0',
                                        '<?php echo INMUEBLES_PROYECTAR_RESPUESTA_ENVIAR_MODIFICACIONES; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////INMUEBLE//////////////////////
        case INMUEBLES_INICIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>',
                                        '<?php echo INMUEBLES; ?>', '<?php echo ""; ?>', '<?php echo INMUEBLES_NUM; ?>', '0', '<?php echo INMUEBLES_SECRETARIO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        case INMUEBLES_DEVOLUCION:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>', '<?php echo INMUEBLES; ?>',
                                        '<?php echo ""; ?>', '<?php echo INMUEBLES_NUM; ?>', '0', '<?php echo INMUEBLES_ENVIAR_MODIFICACIONES; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////INMUEBLES_EMBARGO//////////////////////
        case INMUEBLES_EMBARGO_INICIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>',
                                        '<?php echo INMUEBLES_EMBARGO; ?>', '<?php echo ""; ?>', '<?php echo INMUEBLES_EMBARGO_NUM; ?>', '0',
                                        '<?php echo INMUEBLES_EMBARGO_SECRETARIO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        case INMUEBLES_EMBARGO_DEVOLUCION:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>',
                                        '<?php echo INMUEBLES_EMBARGO; ?>',
                                        '<?php echo ""; ?>', '<?php echo INMUEBLES_EMBARGO_NUM; ?>', '0', '<?php echo INMUEBLES_EMBARGO_ENVIAR_MODIFICACIONES; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////VEHICULO_EMBARGO_OFICIO//////////////////////
        case VEHICULO_EMBARGO_OFICIO_INICIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>',
                                        '<?php echo VEHICULO_EMBARGO_OFICIO; ?>', '<?php echo ""; ?>', '<?php echo VEHICULO_EMBARGO_OFICIO_NUM; ?>', '0',
                                        '<?php echo VEHICULO_EMBARGO_OFICIO_SECRETARIO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        case VEHICULO_EMBARGO_OFICIO_DEVOLUCION:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>',
                                        '<?php echo VEHICULO_EMBARGO_OFICIO; ?>',
                                        '<?php echo ""; ?>', '<?php echo VEHICULO_EMBARGO_OFICIO_NUM; ?>', '0', '<?php echo INMUEBLES_EMBARGO_ENVIAR_MODIFICACIONES; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////INMUEBLES_FECHA//////////////////////
        case INMUEBLES_FECHA_INICIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>',
                                        '<?php echo INMUEBLES_FECHA; ?>', '<?php echo ""; ?>', '<?php echo INMUEBLES_FECHA_NUM; ?>', '0',
                                        '<?php echo INMUEBLES_FECHA_SECRETARIO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        case INMUEBLES_FECHA_DEVOLUCION:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>',
                                        '<?php echo INMUEBLES_FECHA; ?>',
                                        '<?php echo ""; ?>', '<?php echo INMUEBLES_FECHA_NUM; ?>', '0', '<?php echo INMUEBLES_FECHA_ENVIAR_MODIFICACIONES; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////INMUEBLES_SECUESTRO//////////////////////
        case INMUEBLES_SECUESTRO_INICIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>',
                                        '<?php echo INMUEBLES_SECUESTRO; ?>', '<?php echo ""; ?>', '<?php echo INMUEBLES_SECUESTRO_NUM; ?>', '0',
                                        '<?php echo INMUEBLES_SECUESTRO_SECRETARIO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        case INMUEBLES_SECUESTRO_DEVOLUCION:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>',
                                        '<?php echo INMUEBLES_SECUESTRO; ?>',
                                        '<?php echo ""; ?>', '<?php echo INMUEBLES_SECUESTRO_NUM; ?>', '0', '<?php echo INMUEBLES_SECUESTRO_ENVIAR_MODIFICACIONES; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////INMUEBLES_AUTO_SECUESTRO//////////////////////
        case INMUEBLES_AUTO_SECUESTRO_INICIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>',
                                        '<?php echo INMUEBLES_AUTO_SECUESTRO; ?>', '<?php echo ""; ?>', '<?php echo INMUEBLES_AUTO_SECUESTRO_NUM; ?>', '0',
                                        '<?php echo INMUEBLES_AUTO_SECUESTRO_SECRETARIO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        case INMUEBLES_AUTO_SECUESTRO_DEVOLUCION:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>',
                                        '<?php echo INMUEBLES_AUTO_SECUESTRO; ?>',
                                        '<?php echo ""; ?>', '<?php echo INMUEBLES_AUTO_SECUESTRO_NUM; ?>', '0', '<?php echo INMUEBLES_AUTO_SECUESTRO_ENVIAR_MODIFICACIONES; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////INMUEBLES_COMISION_SECUESTRO//////////////////////
        case INMUEBLES_COMISION_SECUESTRO_INICIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>',
                                        '<?php echo INMUEBLES_COMISION_SECUESTRO; ?>', '<?php echo ""; ?>', '<?php echo INMUEBLES_COMISION_SECUESTRO_NUM; ?>', '0',
                                        '<?php echo INMUEBLES_COMISION_SECUESTRO_SECRETARIO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        case INMUEBLES_COMISION_SECUESTRO_DEVOLUCION:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>',
                                        '<?php echo INMUEBLES_COMISION_SECUESTRO; ?>',
                                        '<?php echo ""; ?>', '<?php echo INMUEBLES_COMISION_SECUESTRO_NUM; ?>', '0',
                                        '<?php echo INMUEBLES_COMISION_SECUESTRO_ENVIAR_MODIFICACIONES; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////INMUEBLES_DESP_COM_SECUESTRO//////////////////////
        case INMUEBLES_DESP_COM_SECUESTRO_INICIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>',
                                        '<?php echo INMUEBLES_DESP_COM_SECUESTRO; ?>', '<?php echo ""; ?>', '<?php echo INMUEBLES_DESP_COM_SECUESTRO_NUM; ?>', '0',
                                        '<?php echo INMUEBLES_DESP_COM_SECUESTRO_SECRETARIO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        case INMUEBLES_DESP_COM_SECUESTRO_DEVOLUCION:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>',
                                        '<?php echo INMUEBLES_DESP_COM_SECUESTRO; ?>',
                                        '<?php echo ""; ?>', '<?php echo INMUEBLES_DESP_COM_SECUESTRO_NUM; ?>', '0',
                                        '<?php echo INMUEBLES_DESP_COM_SECUESTRO_ENVIAR_MODIFICACIONES; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////INMUEBLES_DOCUMENTO_SECUESTRO//////////////////////
        case INMUEBLES_DOCUMENTO_SECUESTRO_INICIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>',
                                        '<?php echo INMUEBLES_DOCUMENTO_SECUESTRO; ?>', '<?php echo ""; ?>', '<?php echo INMUEBLES_DOCUMENTO_SECUESTRO_NUM; ?>', '0',
                                        '<?php echo INMUEBLES_DOCUMENTO_SECUESTRO_SECRETARIO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        case INMUEBLES_DOCUMENTO_SECUESTRO_DEVOLUCION:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>',
                                        '<?php echo INMUEBLES_DOCUMENTO_SECUESTRO; ?>',
                                        '<?php echo ""; ?>', '<?php echo INMUEBLES_DOCUMENTO_SECUESTRO_NUM; ?>', '0',
                                        '<?php echo INMUEBLES_DOCUMENTO_SECUESTRO_ENVIAR_MODIFICACIONES; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////INMUEBLES_COMISORIO_SECUESTRO//////////////////////
        case INMUEBLES_COMISORIO_SECUESTRO_INICIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>',
                                        '<?php echo INMUEBLES_COMISORIO_SECUESTRO; ?>', '<?php echo ""; ?>', '<?php echo INMUEBLES_COMISORIO_SECUESTRO_NUM; ?>', '0',
                                        '<?php echo INMUEBLES_COMISORIO_SECUESTRO_SECRETARIO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        case INMUEBLES_COMISORIO_SECUESTRO_DEVOLUCION:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>',
                                        '<?php echo INMUEBLES_COMISORIO_SECUESTRO; ?>',
                                        '<?php echo ""; ?>', '<?php echo INMUEBLES_COMISORIO_SECUESTRO_NUM; ?>', '0',
                                        '<?php echo INMUEBLES_COMISORIO_SECUESTRO_ENVIAR_MODIFICACIONES; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////VEHICULO//////////////////////
        case VEHICULO_INICIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>',
                                        '<?php echo VEHICULO; ?>', '<?php echo ""; ?>', '<?php echo VEHICULO_NUM; ?>', '0', '<?php echo VEHICULO_SECRETARIO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        case VEHICULO_DEVOLUCION:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>', '<?php echo VEHICULO; ?>', '<?php echo ""; ?>', '<?php echo VEHICULO_NUM; ?>',
                                        '0', '<?php echo VEHICULO_ENVIAR_MODIFICACIONES; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////VEHICULO_EMBARGO_INICIO//////////////////////
        case VEHICULO_EMBARGO_INICIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>',
                                        '<?php echo VEHICULO_EMBARGO; ?>', '<?php echo ""; ?>', '<?php echo VEHICULO_EMBARGO_NUM; ?>', '0', '<?php echo VEHICULO_EMBARGO_SECRETARIO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        case VEHICULO_EMBARGO_DEVOLUCION:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>',
                                        '<?php echo VEHICULO_EMBARGO; ?>', '<?php echo ""; ?>', '<?php echo VEHICULO_EMBARGO_NUM; ?>',
                                        '0', '<?php echo VEHICULO_EMBARGO_ENVIAR_MODIFICACIONES; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////VEHICULO_SECUESTRO//////////////////////
        case VEHICULO_SECUESTRO_INICIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>',
                                        '<?php echo VEHICULO_SECUESTRO; ?>', '<?php echo ""; ?>', '<?php echo VEHICULO_SECUESTRO_NUM; ?>', '0',
                                        '<?php echo VEHICULO_SECUESTRO_SECRETARIO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        case VEHICULO_SECUESTRO_DEVOLUCION:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>',
                                        '<?php echo VEHICULO_SECUESTRO; ?>', '<?php echo ""; ?>', '<?php echo VEHICULO_SECUESTRO_NUM; ?>',
                                        '0', '<?php echo VEHICULO_SECUESTRO_ENVIAR_MODIFICACIONES; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////VEHICULO_DESPACHO//////////////////////
        case VEHICULO_DESPACHO_INICIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>',
                                        '<?php echo VEHICULO_DESPACHO; ?>', '<?php echo ""; ?>', '<?php echo VEHICULO_DESPACHO_NUM; ?>', '0',
                                        '<?php echo VEHICULO_DESPACHO_SECRETARIO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        case VEHICULO_DESPACHO_DEVOLUCION:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>',
                                        '<?php echo VEHICULO_DESPACHO; ?>', '<?php echo ""; ?>', '<?php echo VEHICULO_DESPACHO_NUM; ?>',
                                        '0', '<?php echo VEHICULO_DESPACHO_ENVIAR_MODIFICACIONES; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////VEHICULO_COMISION//////////////////////
        case VEHICULO_COMISION_INICIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>',
                                        '<?php echo VEHICULO_COMISION; ?>', '<?php echo ""; ?>', '<?php echo VEHICULO_COMISION_NUM; ?>', '0',
                                        '<?php echo VEHICULO_COMISION_SECRETARIO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        case VEHICULO_COMISION_DEVOLUCION:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>',
                                        '<?php echo VEHICULO_COMISION; ?>', '<?php echo ""; ?>', '<?php echo VEHICULO_COMISION_NUM; ?>',
                                        '0', '<?php echo VEHICULO_COMISION_ENVIAR_MODIFICACIONES; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////VEHICULO_INCORPORANDO//////////////////////
        case VEHICULO_INCORPORANDO_INICIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>',
                                        '<?php echo VEHICULO_INCORPORANDO; ?>', '<?php echo ""; ?>', '<?php echo VEHICULO_INCORPORANDO_NUM; ?>', '0',
                                        '<?php echo VEHICULO_INCORPORANDO_SECRETARIO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        case VEHICULO_INCORPORANDO_DEVOLUCION:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>',
                                        '<?php echo VEHICULO_INCORPORANDO; ?>', '<?php echo ""; ?>', '<?php echo VEHICULO_INCORPORANDO_NUM; ?>',
                                        '0', '<?php echo VEHICULO_INCORPORANDO_ENVIAR_MODIFICACIONES; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////VEHICULO_FECHA//////////////////////
        case VEHICULO_FECHA_INICIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>',
                                        '<?php echo VEHICULO_FECHA; ?>', '<?php echo ""; ?>', '<?php echo VEHICULO_FECHA_NUM; ?>', '0',
                                        '<?php echo VEHICULO_FECHA_SECRETARIO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        case VEHICULO_FECHA_DEVOLUCION:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>',
                                        '<?php echo VEHICULO_FECHA; ?>', '<?php echo ""; ?>', '<?php echo VEHICULO_FECHA_NUM; ?>',
                                        '0', '<?php echo VEHICULO_FECHA_ENVIAR_MODIFICACIONES; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////VEHICULO_DILIGENCIA//////////////////////
        case VEHICULO_DILIGENCIA_INICIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>',
                                        '<?php echo VEHICULO_DILIGENCIA; ?>', '<?php echo ""; ?>', '<?php echo VEHICULO_DILIGENCIA_NUM; ?>', '0',
                                        '<?php echo VEHICULO_DILIGENCIA_SECRETARIO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        case VEHICULO_DILIGENCIA_DEVOLUCION:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>',
                                        '<?php echo VEHICULO_DILIGENCIA; ?>', '<?php echo ""; ?>', '<?php echo VEHICULO_DILIGENCIA_NUM; ?>',
                                        '0', '<?php echo VEHICULO_DILIGENCIA_ENVIAR_MODIFICACIONES; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////VEHICULO_OPOSICION//////////////////////
        case VEHICULO_OPOSICION_INICIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>',
                                        '<?php echo VEHICULO_OPOSICION; ?>', '<?php echo ""; ?>', '<?php echo VEHICULO_OPOSICION_NUM; ?>', '0',
                                        '<?php echo VEHICULO_OPOSICION_SECRETARIO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        case VEHICULO_OPOSICION_DEVOLUCION:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>',
                                        '<?php echo VEHICULO_OPOSICION; ?>', '<?php echo ""; ?>', '<?php echo VEHICULO_OPOSICION_NUM; ?>',
                                        '0', '<?php echo VEHICULO_OPOSICION_ENVIAR_MODIFICACIONES; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////VEHICULO_FECHA//////////////////////
        case VEHICULO_FECHA_INICIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>',
                                        '<?php echo VEHICULO_FECHA; ?>', '<?php echo ""; ?>', '<?php echo VEHICULO_FECHA_NUM; ?>', '0',
                                        '<?php echo VEHICULO_FECHA_SECRETARIO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        case VEHICULO_FECHA_DEVOLUCION:
            ?>
            <input class="push" type="radio" name="gestion" onclick="gestion('<?php echo $id ?>', '<?php echo $cod_fis ?>', '<?php echo $nit ?>',
                                        '<?php echo VEHICULO_FECHA; ?>', '<?php echo ""; ?>', '<?php echo VEHICULO_FECHA_NUM; ?>',
                                        '0', '<?php echo VEHICULO_FECHA_ENVIAR_MODIFICACIONES; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////////////VEHICULO_EMBARGO_INICIO//////////
        case RTA_VEHICULO_COMISIONAR1:
            ?>
            <input class="push" type="radio" name="gestion" onclick="llego('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo PRESETO; ?>',
                                        '<?php echo RTA_VEHICULO_COMISIONAR1; ?>', '<?php echo $id_prelacion1; ?>', '<?php echo $fecha; ?>', '<?php echo VEHICULO_DESPACHO_NUM; ?>')"  />

            <?php
            break;
        case RTA_INMUEBLE_COMISIONAR1:
            ?>
            <input class="push" type="radio" name="gestion" onclick="llego('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo PRESETO; ?>',
                                        '<?php echo RTA_INMUEBLE_COMISIONAR1; ?>', '<?php echo $id_prelacion1; ?>', '<?php echo $fecha; ?>', '<?php echo INMUEBLES_DOCUMENTO_SECUESTRO_NUM; ?>')"  />

            <?php
            break;
        case RTA_MUEBLE_COMISIONAR1:
            ?>
            <input class="push" type="radio" name="gestion" onclick="llego('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo PRESETO; ?>',
                                        '<?php echo RTA_MUEBLE_COMISIONAR1; ?>', '<?php echo $id_prelacion1; ?>', '<?php echo $fecha; ?>', '<?php echo MUEBLES_RESPUESTA_NUM; ?>')"  />

            <?php
            break;
        case INMUEBLE_COMISIONAR1:
            ?>
            <input class="push" type="radio" name="gestion" onclick="comision('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo COMISIONAR; ?>',
                                        '<?php echo INMUEBLE_COMISIONAR1; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        case VEHICULO_COMISIONAR1:
            ?>
            <input class="push" type="radio" name="gestion" onclick="comision('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo COMISIONAR; ?>',
                                        '<?php echo VEHICULO_COMISIONAR1; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        case MUEBLE_COMISIONAR1:
            ?>
            <input class="push" type="radio" name="gestion" onclick="comision('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo COMISIONAR; ?>',
                                        '<?php echo MUEBLE_COMISIONAR1; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        case MUEBLE_OPOSICION:
            ?>
            <input class="push" type="radio" name="gestion" onclick="oposicion('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo OPOSICION; ?>',
                                        '<?php echo MUEBLE_OPOSICION; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        case INMUEBLE_OPOSICION:
            ?>
            <input class="push" type="radio" name="gestion" onclick="oposicion('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo OPOSICION; ?>',
                                        '<?php echo INMUEBLE_OPOSICION; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        case VEHICULO_OPOSICION2:
            ?>
            <input class="push" type="radio" name="gestion" onclick="oposicion('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo OPOSICION; ?>',
                                        '<?php echo VEHICULO_OPOSICION2; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        case MUEBLE_FAVORABLE:
            ?>
            <input class="push" type="radio" name="gestion" onclick="favorable('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo FAVORABLE; ?>',
                                        '<?php echo MUEBLE_FAVORABLE; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        case MUEBLE_FAVORABLE2:
            ?>
            <input class="push" type="radio" name="gestion" onclick="favorable2('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo FAVORABLE; ?>',
                                        '<?php echo MUEBLE_FAVORABLE2; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        case INMUEBLE_FAVORABLE2:
            ?>
            <input class="push" type="radio" name="gestion" onclick="favorable2('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo FAVORABLE_SENA; ?>',
                                        '<?php echo INMUEBLE_FAVORABLE2; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        case INMUEBLE_FAVORABLE:
            ?>
            <input class="push" type="radio" name="gestion" onclick="favorable('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo FAVORABLE; ?>',
                                        '<?php echo INMUEBLE_FAVORABLE; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        case VEHICULO_FAVORABLE:
            ?>
            <input class="push" type="radio" name="gestion" onclick="favorable('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo FAVORABLE; ?>',
                                        '<?php echo VEHICULO_FAVORABLE; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        case VEHICULO_FAVORABLE2:
            ?>
            <input class="push" type="radio" name="gestion" onclick="favorable2('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo FAVORABLE_SENA; ?>',
                                        '<?php echo VEHICULO_FAVORABLE2; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        case VEHICULO_INSISTIR:
            ?>
            <input class="push" type="radio" name="gestion" onclick="insistir('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo INSISTIR; ?>',
                                        '<?php echo VEHICULO_INSISTIR; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        case MUEBLE_INSISTIR:
            ?>
            <input class="push" type="radio" name="gestion" onclick="insistir('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo INSISTIR; ?>',
                                        '<?php echo MUEBLE_INSISTIR; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        case INMUEBLE_INSISTIR:
            ?>
            <input class="push" type="radio" name="gestion" onclick="insistir('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo INSISTIR; ?>',
                                        '<?php echo INMUEBLE_INSISTIR; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        case INMUEBLES_OK://id, cod_fis, nit, id_mc, titulo,id_prelacion
            ?>
            <input class="push" type="radio" name="gestion" onclick="presenta('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo COMISIONAR; ?>',
                                        '<?php echo INMUEBLE_EMBARGO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        case VEHICULO_OK://id, cod_fis, nit, id_mc, titulo,id_prelacion
            ?>
            <input class="push" type="radio" name="gestion" onclick="presenta('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo COMISIONAR; ?>',
                                        '<?php echo VEHICULO_EMBARGO2; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        case VEHICULO_RPESENTA_SUBIR_ARCHIVO:
            ?>
            <a href="javascript:" title="Subir Documento" class="fa fa-archive" onclick="subir_acta('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo base_url('index.php/mcinvestigacion/subir_documento_doc1'); ?>',
                                        '<?php echo VEHICULO_RPESENTA; ?>', '<?php echo VEHICULO_RPESENTA_NUM; ?>', '',
                                        '<?php echo VEHICULO_INCORPORANDO_INICIO; ?>', '<?php echo $id_prelacion1; ?>')"></a>

            <?php
            break;
        case INMUEBLES_RPESENTA_SUBIR_ARCHIVO:
            ?>
            <a href="javascript:" title="Subir Documento" class="fa fa-archive" onclick="subir_acta('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo base_url('index.php/mcinvestigacion/subir_documento_doc1'); ?>',
                                        '<?php echo INMUEBLES_RPESENTA; ?>', '<?php echo INMUEBLES_RPESENTA_NUM; ?>', '',
                                        '<?php echo INMUEBLES_COMISORIO_SECUESTRO_INICIO; ?>', '<?php echo $id_prelacion1; ?>')"></a>

            <?php
            break;
        case MUEBLES_RPESENTA_SUBIR_ARCHIVO:
            ?>
            <a href="javascript:" title="Subir Documento" class="fa fa-archive" onclick="subir_acta('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo base_url('index.php/mcinvestigacion/subir_documento_doc1'); ?>',
                                        '<?php echo MUEBLES_RPESENTA; ?>', '<?php echo MUEBLES_RPESENTA_NUM; ?>', '',
                                        '<?php echo MUEBLES_COMISORIO_INICIO; ?>', '<?php echo $id_prelacion1; ?>')"></a>

            <?php
            break;
        case MUEBLES_LEVANTAR_ACTA_SUBIR_ARCHIVO:
            ?>
            <a href="javascript:" title="Subir Documento" class="fa fa-archive" onclick="subir_acta('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo base_url('index.php/mcinvestigacion/subir_documento_doc1'); ?>',
                                        '<?php echo MUEBLES_LEVANTAR_ACTA; ?>', '<?php echo MUEBLES_LEVANTAR_ACTA_NUM; ?>', '',
                                        '<?php echo MUEBLE_OPOSICION;//MUEBLES_LEVANTAR_ACTA_SUBIR_ARCHIVO2; ?>', '<?php echo $id_prelacion1; ?>')"></a>

            <?php
            break;
        case MUEBLES_LEVANTAR_ACTA_SUBIR_ARCHIVO2:
            ?>
            <a href="javascript:" title="" class="icon-repeat" onclick="proceso_reinicio('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo base_url('index.php/mcinvestigacion/subir_documento_doc1'); ?>',
                                        '<?php echo MUEBLES_LEVANTAR_ACTA; ?>', '<?php echo MUEBLES_LEVANTAR_ACTA_NUM; ?>', '',
                                        '<?php echo MUEBLE_OPOSICION; ?>', '<?php echo $id_prelacion1; ?>')"></a>

            <?php
            break;
        case INMUEBLES_LEVANTAR_ACTA_SUBIR_ARCHIVO2:
            ?>
            <a href="javascript:" title="" class="icon-repeat" onclick="proceso_reinicio('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo base_url('index.php/mcinvestigacion/subir_documento_doc1'); ?>',
                                        '<?php echo INMUEBLES_LEVANTAR_ACTA; ?>', '<?php echo INMUEBLES_LEVANTAR_ACTA_NUM; ?>', '',
                                        '<?php echo INMUEBLE_OPOSICION; ?>', '<?php echo $id_prelacion1; ?>')"></a>

            <?php
            break;
        case MUEBLES_DOCUMENTO_ACTA_SUBIR_ARCHIVO:
            ?>
            <a href="javascript:" title="Subir Documento" class="fa fa-archive" onclick="subir_acta('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo base_url('index.php/mcinvestigacion/subir_documento_doc1'); ?>',
                                        '<?php echo MUEBLES_DOCUMENTO_ACTA; ?>', '<?php echo MUEBLES_DOCUMENTO_ACTA_NUM; ?>', '',
                                        '<?php echo MUEBLE_FAVORABLE2; ?>', '<?php echo $id_prelacion1; ?>')"></a>

            <?php
            break;
        case INMUEBLES_DOCUMENTO_ACTA_SUBIR_ARCHIVO:
            ?>
            <a href="javascript:" title="Subir Documento" class="fa fa-archive" onclick="subir_acta('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo base_url('index.php/mcinvestigacion/subir_documento_doc1'); ?>',
                                        '<?php echo INMUEBLES_DOCUMENTO_ACTA; ?>', '<?php echo INMUEBLES_DOCUMENTO_ACTA_NUM; ?>', '',
                                        '<?php echo INMUEBLE_FAVORABLE2; ?>', '<?php echo $id_prelacion1; ?>')"></a>

            <?php
            break;
        case VEHICULO_DOCUMENTO_ACTA_SUBIR_ARCHIVO:
            ?>
            <a href="javascript:" title="Subir Documento" class="fa fa-archive" onclick="subir_acta('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo base_url('index.php/mcinvestigacion/subir_documento_doc1'); ?>',
                                        '<?php echo VEHICULO_DOCUMENTO_ACTA; ?>', '<?php echo VEHICULO_DOCUMENTO_ACTA_NUM; ?>', '',
                                        '<?php echo VEHICULO_FAVORABLE2; ?>', '<?php echo $id_prelacion1; ?>')"></a>

            <?php
            break;
        case INMUEBLES_LEVANTAR_ACTA_SUBIR_ARCHIVO:
            ?>
            <a href="javascript:" title="Subir Documento" class="fa fa-archive" onclick="subir_acta('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo base_url('index.php/mcinvestigacion/subir_documento_doc1'); ?>',
                                        '<?php echo INMUEBLES_LEVANTAR_ACTA; ?>', '<?php echo INMUEBLES_LEVANTAR_ACTA_NUM; ?>', '',
                                        '<?php echo INMUEBLE_OPOSICION; ?>', '<?php echo $id_prelacion1; ?>')"></a>

            <?php
            break;
        // se suben todo tipo de documentos
        case MUEBLES_SUBIR_ARCHIVO:
            ?>
            <a href="javascript:" title="Subir Documento" class="fa fa-archive" onclick="subir_acta('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo base_url('index.php/mcinvestigacion/subir_documento_doc1'); ?>',
                                        '<?php echo MUEBLES; ?>', '<?php echo MUEBLES_NUM; ?>', '',
                                        '<?php echo MUEBLE_COMISIONAR1; ?>', '<?php echo $id_prelacion1; ?>')"></a>

            <?php
            break;
        case MUEBLES_COM_SECUESTRO_SUBIR_ARCHIVO:
            ?>
            <a href="javascript:" title="Subir Documento" class="fa fa-archive" onclick="subir_acta('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo base_url('index.php/mcinvestigacion/subir_documento_doc1'); ?>',
                                        '<?php echo MUEBLES_COM_SECUESTRO; ?>', '<?php echo MUEBLES_COM_SECUESTRO_NUM; ?>', '',
                                        '<?php echo MUEBLES_DES_SECUESTRO_INICIO; ?>', '<?php echo $id_prelacion1; ?>')"></a>

            <?php
            break;

        case MUEBLES_DES_SECUESTRO_SUBIR_ARCHIVO:
            ?>
            <a href="javascript:" title="Subir Documento" class="fa fa-archive" onclick="subir_acta('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo base_url('index.php/mcinvestigacion/subir_documento_doc1'); ?>',
                                        '<?php echo MUEBLES_DES_SECUESTRO; ?>', '<?php echo MUEBLES_DES_SECUESTRO_NUM; ?>', '',
                                        '<?php echo RTA_MUEBLE_COMISIONAR1; ?>', '<?php echo $id_prelacion1; ?>')"></a>

            <?php
            break;

        case MUEBLES_RESPUESTA_SUBIR_ARCHIVO:
            ?>
            <a href="javascript:" title="Subir Documento" class="fa fa-archive" onclick="subir_acta('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo base_url('index.php/mcinvestigacion/subir_documento_doc1'); ?>',
                                        '<?php echo MUEBLES_RESPUESTA; ?>', '<?php echo MUEBLES_RESPUESTA_NUM; ?>', '',
                                        '<?php echo RTA_MUEBLE_COMISIONAR1; ?>', '<?php echo $id_prelacion1; ?>')"></a>

            <?php
            break;

        case MUEBLES_COMISORIO_SUBIR_ARCHIVO:
            ?>
            <a href="javascript:" title="Subir Documento" class="fa fa-archive" onclick="subir_acta('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo base_url('index.php/mcinvestigacion/subir_documento_doc1'); ?>',
                                        '<?php echo MUEBLES_COMISORIO; ?>', '<?php echo MUEBLES_COMISORIO_NUM; ?>', '',
                                        '<?php echo VIKY_AVALUO; //MUEBLES_FECHA_INICIO; ?>', '<?php echo $id_prelacion1; ?>')"></a>

            <?php
            break;
        case MUEBLES_FECHA_SUBIR_ARCHIVO:
            ?>
            <a href="javascript:" title="Subir Documento" class="fa fa-archive" onclick="subir_acta('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo base_url('index.php/mcinvestigacion/subir_documento_doc1'); ?>',
                                        '<?php echo MUEBLES_FECHA; ?>', '<?php echo MUEBLES_FECHA_NUM; ?>', '',
                                        '<?php echo MUEBLES_DILIGENCIA_INICIO; ?>', '<?php echo $id_prelacion1; ?>')"></a>

            <?php
            break;
        case MUEBLES_DILIGENCIA_SUBIR_ARCHIVO:
            ?>
            <a href="javascript:" title="Subir Documento" class="fa fa-archive" onclick="subir_acta('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo base_url('index.php/mcinvestigacion/subir_documento_doc1'); ?>',
                                        '<?php echo MUEBLES_DILIGENCIA; ?>', '<?php echo MUEBLES_DILIGENCIA_NUM; ?>', '',
                                        '<?php echo MUEBLES_LEVANTAR_ACTA_SUBIR_ARCHIVO; ?>', '<?php echo $id_prelacion1; ?>')"></a>

            <?php
            break;
        case MUEBLES_ORDEN_SUBIR_ARCHIVO:
            ?>
            <a href="javascript:" title="Subir Documento" class="fa fa-archive" onclick="subir_acta('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo base_url('index.php/mcinvestigacion/subir_documento_doc1'); ?>',
                                        '<?php echo MUEBLES_ORDEN; ?>', '<?php echo MUEBLES_ORDEN_NUM; ?>', '',
                                        '<?php echo MUEBLES_LEVANTAR_ACTA_SUBIR_ARCHIVO2; ?>', '<?php echo $id_prelacion1; ?>')"></a>

            <?php
            break;
        case INMUEBLES_ORDEN_SUBIR_ARCHIVO:
            ?>
            <a href="javascript:" title="Subir Documento" class="fa fa-archive" onclick="subir_acta('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo base_url('index.php/mcinvestigacion/subir_documento_doc1'); ?>',
                                        '<?php echo INMUEBLES_ORDEN; ?>', '<?php echo INMUEBLES_ORDEN_NUM; ?>', '',
                                        '<?php echo INMUEBLES_LEVANTAR_ACTA_SUBIR_ARCHIVO2; ?>', '<?php echo $id_prelacion1; ?>')"></a>

            <?php
            break;
        case VEHICULO_ORDEN_SUBIR_ARCHIVO:
            ?>
            <a href="javascript:" title="Subir Documento" class="fa fa-archive" onclick="subir_acta('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo base_url('index.php/mcinvestigacion/subir_documento_doc1'); ?>',
                                        '<?php echo VEHICULO_ORDEN; ?>', '<?php echo VEHICULO_ORDEN_NUM; ?>', '',
                                        '<?php echo INMUEBLES_LEVANTAR_ACTA_SUBIR_ARCHIVO2; ?>', '<?php echo $id_prelacion1; ?>')"></a>

            <?php
            break;
        case MUEBLES_PROYECTAR_AUTO_SUBIR_ARCHIVO:
            ?>
            <a href="javascript:" title="Subir Documento" class="fa fa-archive" onclick="subir_acta('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo base_url('index.php/mcinvestigacion/subir_documento_doc1'); ?>',
                                        '<?php echo MUEBLES_PROYECTAR_AUTO; ?>', '<?php echo MUEBLES_PROYECTAR_AUTO_NUM; ?>', '',
                                        '<?php echo MUEBLES_PROYECTAR_RESPUESTA_INICIO; ?>', '<?php echo $id_prelacion1; ?>')"></a>

            <?php
            break;
        case VEHICULO_PROYECTAR_AUTO_SUBIR_ARCHIVO:
            ?>
            <a href="javascript:" title="Subir Documento" class="fa fa-archive" onclick="subir_acta('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo base_url('index.php/mcinvestigacion/subir_documento_doc1'); ?>',
                                        '<?php echo VEHICULO_PROYECTAR_AUTO; ?>', '<?php echo VEHICULO_PROYECTAR_AUTO_NUM; ?>', '',
                                        '<?php echo VEHICULO_OPOSICION_INICIO; ?>', '<?php echo $id_prelacion1; ?>')"></a>

            <?php
            break;
        case MUEBLES_PROYECTAR_RESPUESTA_SUBIR_ARCHIVO:
            ?>
            <a href="javascript:" title="Subir Documento" class="fa fa-archive" onclick="subir_acta('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo base_url('index.php/mcinvestigacion/subir_documento_doc1'); ?>',
                                        '<?php echo MUEBLES_PROYECTAR_RESPUESTA; ?>', '<?php echo MUEBLES_PROYECTAR_RESPUESTA_NUM; ?>', '',
                                        '<?php echo MUEBLE_FAVORABLE; ?>', '<?php echo $id_prelacion1; ?>')"></a>

            <?php
            break;
        case INMUEBLES_PROYECTAR_AUTO_SUBIR_ARCHIVO:
            ?>
            <a href="javascript:" title="Subir Documento" class="fa fa-archive" onclick="subir_acta('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo base_url('index.php/mcinvestigacion/subir_documento_doc1'); ?>',
                                        '<?php echo INMUEBLES_PROYECTAR_AUTO; ?>', '<?php echo INMUEBLES_PROYECTAR_AUTO_NUM; ?>', '',
                                        '<?php echo INMUEBLES_PROYECTAR_RESPUESTA_INICIO; ?>', '<?php echo $id_prelacion1; ?>')"></a>

            <?php
            break;
        case INMUEBLES_PROYECTAR_RESPUESTA_SUBIR_ARCHIVO:
            ?>
            <a href="javascript:" title="Subir Documento" class="fa fa-archive" onclick="subir_acta('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo base_url('index.php/mcinvestigacion/subir_documento_doc1'); ?>',
                                        '<?php echo INMUEBLES_PROYECTAR_RESPUESTA; ?>', '<?php echo INMUEBLES_PROYECTAR_RESPUESTA_NUM; ?>', '',
                                        '<?php echo INMUEBLE_FAVORABLE; ?>', '<?php echo $id_prelacion1; ?>')"></a>

            <?php
            break;
        case INMUEBLES_SUBIR_ARCHIVO:
            ?>
            <a href="javascript:" title="Subir Documento" class="fa fa-archive" onclick="subir_acta('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo base_url('index.php/mcinvestigacion/subir_documento_doc1'); ?>', '<?php echo INMUEBLES; ?>', '<?php echo INMUEBLES_NUM; ?>', '',
                                        '<?php echo INMUEBLES_OK; ?>', '<?php echo $id_prelacion1; ?>')"></a>

            <?php
            break;
        case INMUEBLES_EMBARGO_SUBIR_ARCHIVO:
            ?>
            <a href="javascript:" title="Subir Documento" class="fa fa-archive" onclick="subir_acta('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo base_url('index.php/mcinvestigacion/subir_documento_doc1'); ?>',
                                        '<?php echo INMUEBLES_EMBARGO; ?>', '<?php echo INMUEBLES_EMBARGO_NUM; ?>', '',
                                        '<?php echo INMUEBLES_FECHA_INICIO; ?>', '<?php echo $id_prelacion1; ?>')"></a>

            <?php
            break;
        case VEHICULO_EMBARGO_OFICIO_SUBIR_ARCHIVO:
            ?>
            <a href="javascript:" title="Subir Documento" class="fa fa-archive" onclick="subir_acta('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo base_url('index.php/mcinvestigacion/subir_documento_doc1'); ?>',
                                        '<?php echo VEHICULO_EMBARGO_OFICIO; ?>', '<?php echo VEHICULO_EMBARGO_OFICIO_NUM; ?>', '',
                                        '<?php echo VEHICULO_FECHA_INICIO; ?>', '<?php echo $id_prelacion1; ?>')"></a>

            <?php
            break;
        case INMUEBLES_FECHA_SUBIR_ARCHIVO:
            ?>
            <a href="javascript:" title="Subir Documento" class="fa fa-archive" onclick="subir_acta('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo base_url('index.php/mcinvestigacion/subir_documento_doc1'); ?>',
                                        '<?php echo INMUEBLES_FECHA; ?>', '<?php echo INMUEBLES_FECHA_NUM; ?>', '',
                                        '<?php echo INMUEBLES_LEVANTAR_ACTA_SUBIR_ARCHIVO; ?>', '<?php echo $id_prelacion1; ?>')"></a>

            <?php
            break;
        case INMUEBLES_COMISION_SECUESTRO_SUBIR_ARCHIVO:
            ?>
            <a href="javascript:" title="Subir Documento" class="fa fa-archive" onclick="subir_acta('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo base_url('index.php/mcinvestigacion/subir_documento_doc1'); ?>',
                                        '<?php echo INMUEBLES_COMISION_SECUESTRO; ?>', '<?php echo INMUEBLES_COMISION_SECUESTRO_NUM; ?>', '',
                                        '<?php echo INMUEBLES_DESP_COM_SECUESTRO_INICIO; ?>', '<?php echo $id_prelacion1; ?>')"></a>

            <?php
            break;
        case INMUEBLES_DESP_COM_SECUESTRO_SUBIR_ARCHIVO:
            ?>
            <a href="javascript:" title="Subir Documento" class="fa fa-archive" onclick="subir_acta('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo base_url('index.php/mcinvestigacion/subir_documento_doc1'); ?>',
                                        '<?php echo INMUEBLES_DESP_COM_SECUESTRO; ?>', '<?php echo INMUEBLES_DESP_COM_SECUESTRO_NUM; ?>', '',
                                        '<?php echo RTA_INMUEBLE_COMISIONAR1; ?>', '<?php echo $id_prelacion1; ?>')"></a>

            <?php
            break;
        case INMUEBLES_SECUESTRO_SUBIR_ARCHIVO:
            ?>
            <a href="javascript:" title="Subir Documento" class="fa fa-archive" onclick="subir_acta('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo base_url('index.php/mcinvestigacion/subir_documento_doc1'); ?>',
                                        '<?php echo INMUEBLES_SECUESTRO; ?>', '<?php echo INMUEBLES_SECUESTRO_NUM; ?>', '',
                                        '<?php echo INMUEBLE_COMISIONAR1; ?>', '<?php echo $id_prelacion1; ?>')"></a>

            <?php
            break;
        case INMUEBLES_AUTO_SECUESTRO_SUBIR_ARCHIVO:
            ?>
            <a href="javascript:" title="Subir Documento" class="fa fa-archive" onclick="subir_acta('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo base_url('index.php/mcinvestigacion/subir_documento_doc1'); ?>',
                                        '<?php echo INMUEBLES_AUTO_SECUESTRO; ?>', '<?php echo INMUEBLES_AUTO_SECUESTRO_NUM; ?>', '',
                                        '<?php echo INMUEBLE_COMISIONAR1; ?>', '<?php echo $id_prelacion1; ?>')"></a>

            <?php
            break;
        case INMUEBLES_DOCUMENTO_SECUESTRO_SUBIR_ARCHIVO:
            ?>
            <a href="javascript:" title="Subir Documento" class="fa fa-archive" onclick="subir_acta('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo base_url('index.php/mcinvestigacion/subir_documento_doc1'); ?>',
                                        '<?php echo INMUEBLES_DOCUMENTO_SECUESTRO; ?>', '<?php echo INMUEBLES_DOCUMENTO_SECUESTRO_NUM; ?>', '',
                                        '<?php echo RTA_INMUEBLE_COMISIONAR1; ?>', '<?php echo $id_prelacion1; ?>')"></a>

            <?php
            break;
        case INMUEBLES_COMISORIO_SECUESTRO_SUBIR_ARCHIVO:
            ?>
            <a href="javascript:" title="Subir Documento" class="fa fa-archive" onclick="subir_acta('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo base_url('index.php/mcinvestigacion/subir_documento_doc1'); ?>',
                                        '<?php echo INMUEBLES_COMISORIO_SECUESTRO; ?>', '<?php echo INMUEBLES_COMISORIO_SECUESTRO_NUM; ?>', '',
                                        '<?php echo VIKY_AVALUO; //INMUEBLES_FECHA_INICIO; ?>', '<?php echo $id_prelacion1; ?>')"></a>

            <?php
            break;
        case VEHICULO_SUBIR_ARCHIVO:
            ?>
            <a href="javascript:" title="Subir Documento" class="fa fa-archive" onclick="subir_acta('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo base_url('index.php/mcinvestigacion/subir_documento_doc1'); ?>',
                                        '<?php echo VEHICULO; ?>', '<?php echo VEHICULO_NUM; ?>', '',
                                        '<?php echo VEHICULO_OK; ?>', '<?php echo $id_prelacion1; ?>')"></a>

            <?php
            break;
        case VEHICULO_EMBARGO_SUBIR_ARCHIVO:
            ?>
            <a href="javascript:" title="Subir Documento" class="fa fa-archive" onclick="subir_acta('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo base_url('index.php/mcinvestigacion/subir_documento_doc1'); ?>',
                                        '<?php echo VEHICULO_EMBARGO; ?>', '<?php echo VEHICULO_EMBARGO_NUM; ?>', '',
                                        '<?php echo VEHICULO_EMBARGO_ENTREGA_SUBIR_ARCHIVO; ?>', '<?php echo $id_prelacion1; ?>')"></a>

            <?php
            break;
        case VEHICULO_EMBARGO_ENTREGA_SUBIR_ARCHIVO:
            ?>
            <a href="javascript:" title="Subir Documento" class="fa fa-archive" onclick="subir_acta('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo base_url('index.php/mcinvestigacion/subir_documento_doc1'); ?>',
                                        '<?php echo VEHICULO_EMBARGO_ENTREGA; ?>', '<?php echo VEHICULO_EMBARGO_ENTREGA_NUM; ?>', '',
                                        '<?php echo VEHICULO_SECUESTRO_INICIO; ?>', '<?php echo $id_prelacion1; ?>')"></a>

            <?php
            break;
        case VEHICULO_COMISION_SUBIR_ARCHIVO:
            ?>
            <a href="javascript:" title="Subir Documento" class="fa fa-archive" onclick="subir_acta('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo base_url('index.php/mcinvestigacion/subir_documento_doc1'); ?>',
                                        '<?php echo VEHICULO_COMISION; ?>', '<?php echo VEHICULO_COMISION_NUM; ?>', '',
                                        '<?php echo RTA_VEHICULO_COMISIONAR1; ?>', '<?php echo $id_prelacion1; ?>')"></a>

            <?php
            break;
        case VEHICULO_DESPACHO_SUBIR_ARCHIVO:
            ?>
            <a href="javascript:" title="Subir Documento" class="fa fa-archive" onclick="subir_acta('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo base_url('index.php/mcinvestigacion/subir_documento_doc1'); ?>',
                                        '<?php echo VEHICULO_DESPACHO; ?>', '<?php echo VEHICULO_DESPACHO_NUM; ?>', '',
                                        '<?php echo RTA_VEHICULO_COMISIONAR1; ?>', '<?php echo $id_prelacion1; ?>')"></a>

            <?php
            break;
        case VEHICULO_INCORPORANDO_SUBIR_ARCHIVO:
            ?>
            <a href="javascript:" title="Subir Documento" class="fa fa-archive" onclick="subir_acta('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo base_url('index.php/mcinvestigacion/subir_documento_doc1'); ?>',
                                        '<?php echo VEHICULO_DESPACHO; ?>', '<?php echo VEHICULO_DESPACHO_NUM; ?>', '',
                                        '<?php echo VIKY_AVALUO; ?>', '<?php echo $id_prelacion1; ?>')"></a>

            <?php
            break;
        case VEHICULO_FECHA_SUBIR_ARCHIVO:
            ?>
            <a href="javascript:" title="Subir Documento" class="fa fa-archive" onclick="subir_acta('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo base_url('index.php/mcinvestigacion/subir_documento_doc1'); ?>',
                                        '<?php echo VEHICULO_FECHA; ?>', '<?php echo VEHICULO_FECHA_NUM; ?>', '',
                                        '<?php echo VEHICULO_DILIGENCIA_SUBIR_ARCHIVO; //VEHICULO_DILIGENCIA_INICIO; ?>', '<?php echo $id_prelacion1; ?>')"></a>

            <?php
            break;
        case VEHICULO_DILIGENCIA_SUBIR_ARCHIVO:
            ?>
            <a href="javascript:" title="Subir Documento" class="fa fa-archive" onclick="subir_acta('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo base_url('index.php/mcinvestigacion/subir_documento_doc1'); ?>',
                                        '<?php echo VEHICULO_DILIGENCIA; ?>', '<?php echo VEHICULO_DILIGENCIA_NUM; ?>', '',
                                        '<?php echo VEHICULO_OPOSICION2; //VEHICULO_OPOSICION_INICIO; ?>', '<?php echo $id_prelacion1; ?>')"></a>

            <?php
            break;
        case VEHICULO_OPOSICION_SUBIR_ARCHIVO:
            ?>
            <a href="javascript:" title="Subir Documento" class="fa fa-archive" onclick="subir_acta('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo base_url('index.php/mcinvestigacion/subir_documento_doc1'); ?>',
                                        '<?php echo VEHICULO_OPOSICION; ?>', '<?php echo VEHICULO_OPOSICION_NUM; ?>', '',
                                        '<?php echo VEHICULO_FAVORABLE; ?>', '<?php echo $id_prelacion1; ?>')"></a>

            <?php
            break;
        case VEHICULO_SECUESTRO_SUBIR_ARCHIVO:
            ?>
            <a href="javascript:" title="Subir Documento" class="fa fa-archive" onclick="subir_acta('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo base_url('index.php/mcinvestigacion/subir_documento_doc1'); ?>',
                                        '<?php echo VEHICULO_SECUESTRO; ?>', '<?php echo VEHICULO_SECUESTRO_NUM; ?>', '',
                                        '<?php echo VEHICULO_COMISIONAR1; ?>', '<?php echo $id_prelacion1; ?>')"></a>

            <?php
            break;
        case 0:
            ?>
            <input type="checkbox" id="<?php echo $check; ?>" name="<?php echo $check; ?>">
        <?php
    }
}

function seleccion_secretario($identificador, $id, $cod_fis, $nit, $id_prelacion1, $check, $fecha) {
    switch ($identificador) {
        case MUEBLES_SECRETARIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="secretaria('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo MUEBLES; ?>', '<?php echo MUEBLES_NUM; ?>',
                                        '<?php echo MUEBLES_DEVOLUCION; ?>', '<?php echo $respuesta = 1; ?>', '<?php echo MUEBLES_COORDINARO; ?>',
                                        '<?php echo MUEBLES_COORDINARO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////MUEBLES_COM_SECUESTRO//////////////////////
        case MUEBLES_COM_SECUESTRO_SECRETARIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="secretaria('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo MUEBLES_COM_SECUESTRO; ?>', '<?php echo MUEBLES_COM_SECUESTRO_NUM; ?>',
                                        '<?php echo MUEBLES_COM_SECUESTRO_DEVOLUCION; ?>', '<?php echo $respuesta = 1; ?>', '<?php echo MUEBLES_COM_SECUESTRO_COORDINARO; ?>',
                                        '<?php echo MUEBLES_COM_SECUESTRO_COORDINARO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////MUEBLES_COM_SECUESTRO//////////////////////
        case MUEBLES_COM_SECUESTRO_SECRETARIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="secretaria('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo MUEBLES_COM_SECUESTRO; ?>', '<?php echo MUEBLES_COM_SECUESTRO_NUM; ?>',
                                        '<?php echo MUEBLES_COM_SECUESTRO_DEVOLUCION; ?>', '<?php echo $respuesta = 1; ?>', '<?php echo MUEBLES_COM_SECUESTRO_COORDINARO; ?>',
                                        '<?php echo MUEBLES_COM_SECUESTRO_COORDINARO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////MUEBLES_DES_SECUESTRO//////////////////////
        case MUEBLES_DES_SECUESTRO_SECRETARIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="secretaria('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo MUEBLES_DES_SECUESTRO; ?>', '<?php echo MUEBLES_DES_SECUESTRO_NUM; ?>',
                                        '<?php echo MUEBLES_DES_SECUESTRO_DEVOLUCION; ?>', '<?php echo $respuesta = 1; ?>', '<?php echo MUEBLES_DES_SECUESTRO_COORDINARO; ?>',
                                        '<?php echo MUEBLES_DES_SECUESTRO_COORDINARO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////MUEBLES_RESPUESTA//////////////////////
        case MUEBLES_RESPUESTA_SECRETARIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="secretaria('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo MUEBLES_RESPUESTA; ?>', '<?php echo MUEBLES_RESPUESTA_NUM; ?>',
                                        '<?php echo MUEBLES_RESPUESTA_DEVOLUCION; ?>', '<?php echo $respuesta = 1; ?>', '<?php echo MUEBLES_RESPUESTA_COORDINARO; ?>',
                                        '<?php echo MUEBLES_RESPUESTA_COORDINARO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////MUEBLES_COMISORIO//////////////////////
        case MUEBLES_COMISORIO_SECRETARIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="secretaria('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo MUEBLES_COMISORIO; ?>', '<?php echo MUEBLES_COMISORIO_NUM; ?>',
                                        '<?php echo MUEBLES_COMISORIO_DEVOLUCION; ?>', '<?php echo $respuesta = 1; ?>', '<?php echo MUEBLES_COMISORIO_COORDINARO; ?>',
                                        '<?php echo MUEBLES_COMISORIO_COORDINARO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////MUEBLES_FECHA//////////////////////
        case MUEBLES_FECHA_SECRETARIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="secretaria('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo MUEBLES_FECHA; ?>', '<?php echo MUEBLES_FECHA_NUM; ?>',
                                        '<?php echo MUEBLES_FECHA_DEVOLUCION; ?>', '<?php echo $respuesta = 1; ?>', '<?php echo MUEBLES_FECHA_COORDINARO; ?>',
                                        '<?php echo MUEBLES_FECHA_COORDINARO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////MUEBLES_DILIGENCIA//////////////////////
        case MUEBLES_DILIGENCIA_SECRETARIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="secretaria('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo MUEBLES_DILIGENCIA; ?>', '<?php echo MUEBLES_DILIGENCIA_NUM; ?>',
                                        '<?php echo MUEBLES_DILIGENCIA_DEVOLUCION; ?>', '<?php echo $respuesta = 1; ?>', '<?php echo MUEBLES_DILIGENCIA_COORDINARO; ?>',
                                        '<?php echo MUEBLES_DILIGENCIA_COORDINARO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////MUEBLES_ORDEN//////////////////////
        case MUEBLES_ORDEN_SECRETARIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="secretaria('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo MUEBLES_ORDEN; ?>', '<?php echo MUEBLES_ORDEN_NUM; ?>',
                                        '<?php echo MUEBLES_ORDEN_DEVOLUCION; ?>', '<?php echo $respuesta = 1; ?>', '<?php echo MUEBLES_ORDEN_COORDINARO; ?>',
                                        '<?php echo MUEBLES_ORDEN_COORDINARO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////INMUEBLES_ORDEN//////////////////////
        case INMUEBLES_ORDEN_SECRETARIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="secretaria('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo INMUEBLES_ORDEN; ?>', '<?php echo INMUEBLES_ORDEN_NUM; ?>',
                                        '<?php echo INMUEBLES_ORDEN_DEVOLUCION; ?>', '<?php echo $respuesta = 1; ?>', '<?php echo INMUEBLES_ORDEN_COORDINARO; ?>',
                                        '<?php echo INMUEBLES_ORDEN_COORDINARO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////VEHICULO_ORDEN//////////////////////
        case VEHICULO_ORDEN_SECRETARIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="secretaria('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo VEHICULO_ORDEN; ?>', '<?php echo VEHICULO_ORDEN_NUM; ?>',
                                        '<?php echo VEHICULO_ORDEN_DEVOLUCION; ?>', '<?php echo $respuesta = 1; ?>', '<?php echo VEHICULO_ORDEN_COORDINARO; ?>',
                                        '<?php echo VEHICULO_ORDEN_COORDINARO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////MUEBLES_PROYECTAR_AUTO//////////////////////
        case MUEBLES_PROYECTAR_AUTO_SECRETARIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="secretaria('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo MUEBLES_PROYECTAR_AUTO; ?>', '<?php echo MUEBLES_PROYECTAR_AUTO_NUM; ?>',
                                        '<?php echo MUEBLES_PROYECTAR_AUTO_DEVOLUCION; ?>', '<?php echo $respuesta = 1; ?>', '<?php echo MUEBLES_PROYECTAR_AUTO_COORDINARO; ?>',
                                        '<?php echo MUEBLES_PROYECTAR_AUTO_COORDINARO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////VEHICULO_PROYECTAR_AUTO//////////////////////
        case VEHICULO_PROYECTAR_AUTO_SECRETARIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="secretaria('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo VEHICULO_PROYECTAR_AUTO; ?>', '<?php echo VEHICULO_PROYECTAR_AUTO_NUM; ?>',
                                        '<?php echo VEHICULO_PROYECTAR_AUTO_DEVOLUCION; ?>', '<?php echo $respuesta = 1; ?>', '<?php echo VEHICULO_PROYECTAR_AUTO_COORDINARO; ?>',
                                        '<?php echo VEHICULO_PROYECTAR_AUTO_COORDINARO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////INMUEBLES_PROYECTAR_AUTO//////////////////////
        case INMUEBLES_PROYECTAR_AUTO_SECRETARIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="secretaria('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo INMUEBLES_PROYECTAR_AUTO; ?>', '<?php echo INMUEBLES_PROYECTAR_AUTO_NUM; ?>',
                                        '<?php echo INMUEBLES_PROYECTAR_AUTO_DEVOLUCION; ?>', '<?php echo $respuesta = 1; ?>', '<?php echo INMUEBLES_PROYECTAR_AUTO_COORDINARO; ?>',
                                        '<?php echo INMUEBLES_PROYECTAR_AUTO_COORDINARO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////MUEBLES_PROYECTAR_RESPUESTA//////////////////////
        case MUEBLES_PROYECTAR_RESPUESTA_SECRETARIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="secretaria('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo MUEBLES_PROYECTAR_RESPUESTA; ?>', '<?php echo MUEBLES_PROYECTAR_RESPUESTA_NUM; ?>',
                                        '<?php echo MUEBLES_PROYECTAR_RESPUESTA_DEVOLUCION; ?>', '<?php echo $respuesta = 1; ?>', '<?php echo MUEBLES_PROYECTAR_RESPUESTA_COORDINARO; ?>',
                                        '<?php echo MUEBLES_PROYECTAR_RESPUESTA_COORDINARO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////INMUEBLES_PROYECTAR_RESPUESTA//////////////////////
        case INMUEBLES_PROYECTAR_RESPUESTA_SECRETARIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="secretaria('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo INMUEBLES_PROYECTAR_RESPUESTA; ?>', '<?php echo INMUEBLES_PROYECTAR_RESPUESTA_NUM; ?>',
                                        '<?php echo INMUEBLES_PROYECTAR_RESPUESTA_DEVOLUCION; ?>', '<?php echo $respuesta = 1; ?>', '<?php echo INMUEBLES_PROYECTAR_RESPUESTA_COORDINARO; ?>',
                                        '<?php echo INMUEBLES_PROYECTAR_RESPUESTA_COORDINARO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////INMUEBLE//////////////////////
        case INMUEBLES_SECRETARIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="secretaria('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo INMUEBLES; ?>', '<?php echo INMUEBLES_NUM; ?>',
                                        '<?php echo INMUEBLES_DEVOLUCION; ?>', '<?php echo $respuesta = 1; ?>', '<?php echo INMUEBLES_COORDINARO; ?>',
                                        '<?php echo INMUEBLES_SUBIR_ARCHIVO; //INMUEBLES_COORDINARO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////INMUEBLES_EMBARGO//////////////////////
        case INMUEBLES_EMBARGO_SECRETARIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="secretaria('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo INMUEBLES_EMBARGO; ?>', '<?php echo INMUEBLES_EMBARGO_NUM; ?>',
                                        '<?php echo INMUEBLES_EMBARGO_DEVOLUCION; ?>', '<?php echo $respuesta = 1; ?>', '<?php echo INMUEBLES_EMBARGO_COORDINARO; ?>',
                                        '<?php echo INMUEBLES_EMBARGO_COORDINARO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////VEHICULO_EMBARGO_OFICIO//////////////////////
        case VEHICULO_EMBARGO_OFICIO_SECRETARIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="secretaria('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo VEHICULO_EMBARGO_OFICIO; ?>', '<?php echo VEHICULO_EMBARGO_OFICIO_NUM; ?>',
                                        '<?php echo VEHICULO_EMBARGO_OFICIO_DEVOLUCION; ?>', '<?php echo $respuesta = 1; ?>', '<?php echo VEHICULO_EMBARGO_OFICIO_SUBIR_ARCHIVO; ?>',
                                        '<?php echo VEHICULO_EMBARGO_OFICIO_SUBIR_ARCHIVO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////INMUEBLES_FECHA//////////////////////
        case INMUEBLES_FECHA_SECRETARIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="secretaria('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo INMUEBLES_FECHA; ?>', '<?php echo INMUEBLES_FECHA_NUM; ?>',
                                        '<?php echo INMUEBLES_FECHA_DEVOLUCION; ?>', '<?php echo $respuesta = 1; ?>', '<?php echo INMUEBLES_FECHA_COORDINARO; ?>',
                                        '<?php echo INMUEBLES_FECHA_COORDINARO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////INMUEBLES_SECUESTRO//////////////////////
        case INMUEBLES_SECUESTRO_SECRETARIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="secretaria('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo INMUEBLES_SECUESTRO; ?>', '<?php echo INMUEBLES_SECUESTRO_NUM; ?>',
                                        '<?php echo INMUEBLES_SECUESTRO_DEVOLUCION; ?>', '<?php echo $respuesta = 1; ?>', '<?php echo INMUEBLES_SECUESTRO_COORDINARO; ?>',
                                        '<?php echo INMUEBLES_SECUESTRO_COORDINARO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////INMUEBLES_AUTO_SECUESTRO//////////////////////
        case INMUEBLES_AUTO_SECUESTRO_SECRETARIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="secretaria('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo INMUEBLES_AUTO_SECUESTRO; ?>', '<?php echo INMUEBLES_AUTO_SECUESTRO_NUM; ?>',
                                        '<?php echo INMUEBLES_AUTO_SECUESTRO_DEVOLUCION; ?>', '<?php echo $respuesta = 1; ?>', '<?php echo INMUEBLES_AUTO_SECUESTRO_COORDINARO; ?>',
                                        '<?php echo INMUEBLES_AUTO_SECUESTRO_COORDINARO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////INMUEBLES_COMISION_SECUESTRO//////////////////////
        case INMUEBLES_COMISION_SECUESTRO_SECRETARIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="secretaria('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo INMUEBLES_COMISION_SECUESTRO; ?>', '<?php echo INMUEBLES_COMISION_SECUESTRO_NUM; ?>',
                                        '<?php echo INMUEBLES_COMISION_SECUESTRO_DEVOLUCION; ?>', '<?php echo $respuesta = 1; ?>', '<?php echo INMUEBLES_COMISION_SECUESTRO_COORDINARO; ?>',
                                        '<?php echo INMUEBLES_COMISION_SECUESTRO_COORDINARO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////INMUEBLES_DESP_COM_SECUESTRO//////////////////////
        case INMUEBLES_DESP_COM_SECUESTRO_SECRETARIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="secretaria('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo INMUEBLES_DESP_COM_SECUESTRO; ?>', '<?php echo INMUEBLES_DESP_COM_SECUESTRO_NUM; ?>',
                                        '<?php echo INMUEBLES_DESP_COM_SECUESTRO_DEVOLUCION; ?>', '<?php echo $respuesta = 1; ?>', '<?php echo INMUEBLES_DESP_COM_SECUESTRO_COORDINARO; ?>',
                                        '<?php echo INMUEBLES_DESP_COM_SECUESTRO_COORDINARO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////INMUEBLES_DOCUMENTO_SECUESTRO//////////////////////
        case INMUEBLES_DOCUMENTO_SECUESTRO_SECRETARIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="secretaria('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo INMUEBLES_DOCUMENTO_SECUESTRO; ?>', '<?php echo INMUEBLES_DOCUMENTO_SECUESTRO_NUM; ?>',
                                        '<?php echo INMUEBLES_DOCUMENTO_SECUESTRO_DEVOLUCION; ?>', '<?php echo $respuesta = 1; ?>', '<?php echo INMUEBLES_DOCUMENTO_SECUESTRO_COORDINARO; ?>',
                                        '<?php echo INMUEBLES_DOCUMENTO_SECUESTRO_COORDINARO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////INMUEBLES_COMISORIO_SECUESTRO//////////////////////
        case INMUEBLES_COMISORIO_SECUESTRO_SECRETARIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="secretaria('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo INMUEBLES_COMISORIO_SECUESTRO; ?>', '<?php echo INMUEBLES_COMISORIO_SECUESTRO_NUM; ?>',
                                        '<?php echo INMUEBLES_COMISORIO_SECUESTRO_DEVOLUCION; ?>', '<?php echo $respuesta = 1; ?>', '<?php echo INMUEBLES_COMISORIO_SECUESTRO_COORDINARO; ?>',
                                        '<?php echo INMUEBLES_COMISORIO_SECUESTRO_COORDINARO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////VEHICULO//////////////////////
        case VEHICULO_SECRETARIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="secretaria('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo VEHICULO; ?>', '<?php echo VEHICULO_NUM; ?>',
                                        '<?php echo VEHICULO_DEVOLUCION; ?>', '<?php echo $respuesta = 1; ?>', '<?php echo VEHICULO_COORDINARO; ?>',
                                        '<?php echo VEHICULO_SUBIR_ARCHIVO; //VEHICULO_COORDINARO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////VEHICULO_EMBARGO//////////////////////
        case VEHICULO_EMBARGO_SECRETARIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="secretaria('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo VEHICULO_EMBARGO; ?>', '<?php echo VEHICULO_EMBARGO_NUM; ?>',
                                        '<?php echo VEHICULO_EMBARGO_DEVOLUCION; ?>', '<?php echo $respuesta = 1; ?>', '<?php echo VEHICULO_EMBARGO_COORDINARO; ?>',
                                        '<?php echo VEHICULO_EMBARGO_SUBIR_ARCHIVO; //VEHICULO_EMBARGO_COORDINARO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////VEHICULO_SECUESTRO//////////////////////
        case VEHICULO_SECUESTRO_SECRETARIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="secretaria('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo VEHICULO_SECUESTRO; ?>', '<?php echo VEHICULO_SECUESTRO_NUM; ?>',
                                        '<?php echo VEHICULO_SECUESTRO_DEVOLUCION; ?>', '<?php echo $respuesta = 1; ?>', '<?php echo VEHICULO_SECUESTRO_COORDINARO; ?>',
                                        '<?php echo VEHICULO_SECUESTRO_COORDINARO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////VEHICULO_SECUESTRO//////////////////////
        case VEHICULO_COMISION_SECRETARIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="secretaria('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo VEHICULO_COMISION; ?>', '<?php echo VEHICULO_COMISION_NUM; ?>',
                                        '<?php echo VEHICULO_COMISION_DEVOLUCION; ?>', '<?php echo $respuesta = 1; ?>', '<?php echo VEHICULO_COMISION_COORDINARO; ?>',
                                        '<?php echo VEHICULO_COMISION_COORDINARO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////VEHICULO_INCORPORANDO//////////////////////
        case VEHICULO_INCORPORANDO_SECRETARIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="secretaria('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo VEHICULO_INCORPORANDO; ?>', '<?php echo VEHICULO_INCORPORANDO_NUM; ?>',
                                        '<?php echo VEHICULO_INCORPORANDO_DEVOLUCION; ?>', '<?php echo $respuesta = 1; ?>', '<?php echo VEHICULO_INCORPORANDO_COORDINARO; ?>',
                                        '<?php echo VEHICULO_INCORPORANDO_COORDINARO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////VEHICULO_DESPACHO//////////////////////
        case VEHICULO_DESPACHO_SECRETARIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="secretaria('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo VEHICULO_DESPACHO; ?>', '<?php echo VEHICULO_DESPACHO_NUM; ?>',
                                        '<?php echo VEHICULO_DESPACHO_DEVOLUCION; ?>', '<?php echo $respuesta = 1; ?>', '<?php echo VEHICULO_DESPACHO_COORDINARO; ?>',
                                        '<?php echo VEHICULO_DESPACHO_COORDINARO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////VEHICULO_FECHA//////////////////////
        case VEHICULO_FECHA_SECRETARIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="secretaria('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo VEHICULO_FECHA; ?>', '<?php echo VEHICULO_FECHA_NUM; ?>',
                                        '<?php echo VEHICULO_FECHA_DEVOLUCION; ?>', '<?php echo $respuesta = 1; ?>', '<?php echo VEHICULO_FECHA_COORDINARO; ?>',
                                        '<?php echo VEHICULO_FECHA_COORDINARO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////VEHICULO_DILIGENCIA//////////////////////
        case VEHICULO_DILIGENCIA_SECRETARIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="secretaria('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo VEHICULO_DILIGENCIA; ?>', '<?php echo VEHICULO_DILIGENCIA_NUM; ?>',
                                        '<?php echo VEHICULO_DILIGENCIA_DEVOLUCION; ?>', '<?php echo $respuesta = 1; ?>', '<?php echo VEHICULO_DILIGENCIA_COORDINARO; ?>',
                                        '<?php echo VEHICULO_DILIGENCIA_COORDINARO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////VEHICULO_OPOSICION//////////////////////
        case VEHICULO_OPOSICION_SECRETARIO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="secretaria('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo VEHICULO_OPOSICION; ?>', '<?php echo VEHICULO_OPOSICION_NUM; ?>',
                                        '<?php echo VEHICULO_OPOSICION_DEVOLUCION; ?>', '<?php echo $respuesta = 1; ?>', '<?php echo VEHICULO_OPOSICION_COORDINARO; ?>',
                                        '<?php echo VEHICULO_OPOSICION_COORDINARO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
    }
}

function seleccion_coordinador($identificador, $id, $cod_fis, $nit, $id_prelacion1, $check, $fecha) {
    switch ($identificador) {
        case MUEBLES_COORDINARO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="coordinador('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo MUEBLES; ?>', '<?php echo MUEBLES_NUM; ?>', '<?php echo MUEBLES_DEVOLUCION; ?>', '0', '',
                                        '<?php echo MUEBLES_SUBIR_ARCHIVO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;

        /////////////MUEBLES_COM_SECUESTRO//////////////////////
        case MUEBLES_COM_SECUESTRO_COORDINARO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="coordinador('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo MUEBLES_COM_SECUESTRO; ?>',
                                        '<?php echo MUEBLES_COM_SECUESTRO_NUM; ?>', '<?php echo MUEBLES_COM_SECUESTRO_DEVOLUCION; ?>', '0', '',
                                        '<?php echo MUEBLES_COM_SECUESTRO_SUBIR_ARCHIVO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;

        /////////////MUEBLES_DES_SECUESTRO//////////////////////
        case MUEBLES_DES_SECUESTRO_COORDINARO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="coordinador('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo MUEBLES_DES_SECUESTRO; ?>',
                                        '<?php echo MUEBLES_DES_SECUESTRO_NUM; ?>', '<?php echo MUEBLES_DES_SECUESTRO_DEVOLUCION; ?>', '0', '',
                                        '<?php echo MUEBLES_DES_SECUESTRO_SUBIR_ARCHIVO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;

        /////////////MUEBLES_RESPUESTA//////////////////////
        case MUEBLES_RESPUESTA_COORDINARO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="coordinador('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo MUEBLES_RESPUESTA; ?>',
                                        '<?php echo MUEBLES_RESPUESTA_NUM; ?>', '<?php echo MUEBLES_RESPUESTA_DEVOLUCION; ?>', '0', '',
                                        '<?php echo MUEBLES_RESPUESTA_SUBIR_ARCHIVO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;

        /////////////MUEBLES_COMISORIO//////////////////////
        case MUEBLES_COMISORIO_COORDINARO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="coordinador('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo MUEBLES_COMISORIO; ?>',
                                        '<?php echo MUEBLES_COMISORIO_NUM; ?>', '<?php echo MUEBLES_COMISORIO_DEVOLUCION; ?>', '0', '',
                                        '<?php echo MUEBLES_COMISORIO_SUBIR_ARCHIVO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;

        /////////////MUEBLES_FECHA//////////////////////
        case MUEBLES_FECHA_COORDINARO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="coordinador('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo MUEBLES_FECHA; ?>',
                                        '<?php echo MUEBLES_FECHA_NUM; ?>', '<?php echo MUEBLES_FECHA_DEVOLUCION; ?>', '0', '',
                                        '<?php echo MUEBLES_FECHA_SUBIR_ARCHIVO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;

        /////////////MUEBLES_DILIGENCIA//////////////////////
        case MUEBLES_DILIGENCIA_COORDINARO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="coordinador('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo MUEBLES_DILIGENCIA; ?>',
                                        '<?php echo MUEBLES_DILIGENCIA_NUM; ?>', '<?php echo MUEBLES_DILIGENCIA_DEVOLUCION; ?>', '0', '',
                                        '<?php echo MUEBLES_DILIGENCIA_SUBIR_ARCHIVO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////MUEBLES_ORDEN//////////////////////
        case MUEBLES_ORDEN_COORDINARO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="coordinador('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo MUEBLES_ORDEN; ?>',
                                        '<?php echo MUEBLES_ORDEN_NUM; ?>', '<?php echo MUEBLES_ORDEN_DEVOLUCION; ?>', '0', '',
                                        '<?php echo MUEBLES_ORDEN_SUBIR_ARCHIVO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////INMUEBLES_ORDEN//////////////////////
        case INMUEBLES_ORDEN_COORDINARO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="coordinador('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo INMUEBLES_ORDEN; ?>',
                                        '<?php echo INMUEBLES_ORDEN_NUM; ?>', '<?php echo INMUEBLES_ORDEN_DEVOLUCION; ?>', '0', '',
                                        '<?php echo INMUEBLES_ORDEN_SUBIR_ARCHIVO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////VEHICULO_ORDEN//////////////////////
        case VEHICULO_ORDEN_COORDINARO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="coordinador('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo VEHICULO_ORDEN; ?>',
                                        '<?php echo VEHICULO_ORDEN_NUM; ?>', '<?php echo VEHICULO_ORDEN_DEVOLUCION; ?>', '0', '',
                                        '<?php echo VEHICULO_ORDEN_SUBIR_ARCHIVO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////MUEBLES_PROYECTAR_AUTO//////////////////////
        case MUEBLES_PROYECTAR_AUTO_COORDINARO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="coordinador('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo MUEBLES_PROYECTAR_AUTO; ?>',
                                        '<?php echo MUEBLES_PROYECTAR_AUTO_NUM; ?>', '<?php echo MUEBLES_PROYECTAR_AUTO_DEVOLUCION; ?>', '0', '',
                                        '<?php echo MUEBLES_PROYECTAR_AUTO_SUBIR_ARCHIVO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////VEHICULO_PROYECTAR_AUTO//////////////////////
        case VEHICULO_PROYECTAR_AUTO_COORDINARO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="coordinador('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo VEHICULO_PROYECTAR_AUTO; ?>',
                                        '<?php echo VEHICULO_PROYECTAR_AUTO_NUM; ?>', '<?php echo VEHICULO_PROYECTAR_AUTO_DEVOLUCION; ?>', '0', '',
                                        '<?php echo VEHICULO_PROYECTAR_AUTO_SUBIR_ARCHIVO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////INMUEBLES_PROYECTAR_AUTO//////////////////////
        case INMUEBLES_PROYECTAR_AUTO_COORDINARO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="coordinador('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo INMUEBLES_PROYECTAR_AUTO; ?>',
                                        '<?php echo INMUEBLES_PROYECTAR_AUTO_NUM; ?>', '<?php echo INMUEBLES_PROYECTAR_AUTO_DEVOLUCION; ?>', '0', '',
                                        '<?php echo INMUEBLES_PROYECTAR_AUTO_SUBIR_ARCHIVO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
        /////////////MUEBLES_DILIGENCIA//////////////////////
        case MUEBLES_PROYECTAR_RESPUESTA_COORDINARO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="coordinador('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo MUEBLES_PROYECTAR_RESPUESTA; ?>',
                                        '<?php echo MUEBLES_PROYECTAR_RESPUESTA_NUM; ?>', '<?php echo MUEBLES_PROYECTAR_RESPUESTA_DEVOLUCION; ?>', '0', '',
                                        '<?php echo MUEBLES_PROYECTAR_RESPUESTA_SUBIR_ARCHIVO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;

        /////////////MUEBLES_DILIGENCIA//////////////////////
        case INMUEBLES_PROYECTAR_RESPUESTA_COORDINARO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="coordinador('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo INMUEBLES_PROYECTAR_RESPUESTA; ?>',
                                        '<?php echo INMUEBLES_PROYECTAR_RESPUESTA_NUM; ?>', '<?php echo INMUEBLES_PROYECTAR_RESPUESTA_DEVOLUCION; ?>', '0', '',
                                        '<?php echo INMUEBLES_PROYECTAR_RESPUESTA_SUBIR_ARCHIVO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;

        /////////////INMUEBLE//////////////////////
        case INMUEBLES_COORDINARO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="coordinador('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo INMUEBLES; ?>', '<?php echo INMUEBLES_NUM; ?>', '<?php echo INMUEBLES_DEVOLUCION; ?>', '0', '',
                                        '<?php echo INMUEBLES_SUBIR_ARCHIVO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;

        /////////////INMUEBLES_EMBARGO//////////////////////
        case INMUEBLES_EMBARGO_COORDINARO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="coordinador('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo INMUEBLES_EMBARGO; ?>',
                                        '<?php echo INMUEBLES_EMBARGO_NUM; ?>', '<?php echo INMUEBLES_EMBARGO_DEVOLUCION; ?>', '0', '',
                                        '<?php echo INMUEBLES_EMBARGO_SUBIR_ARCHIVO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;

        /////////////INMUEBLES_FECHA//////////////////////
        case INMUEBLES_FECHA_COORDINARO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="coordinador('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo INMUEBLES_FECHA; ?>',
                                        '<?php echo INMUEBLES_FECHA_NUM; ?>', '<?php echo INMUEBLES_FECHA_DEVOLUCION; ?>', '0', '',
                                        '<?php echo INMUEBLES_FECHA_SUBIR_ARCHIVO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;

        /////////////INMUEBLES_COMISION_SECUESTRO//////////////////////
        case INMUEBLES_COMISION_SECUESTRO_COORDINARO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="coordinador('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo INMUEBLES_COMISION_SECUESTRO; ?>',
                                        '<?php echo INMUEBLES_COMISION_SECUESTRO_NUM; ?>', '<?php echo INMUEBLES_COMISION_SECUESTRO_DEVOLUCION; ?>', '0', '',
                                        '<?php echo INMUEBLES_COMISION_SECUESTRO_SUBIR_ARCHIVO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;

        /////////////INMUEBLES_DESP_COM_SECUESTRO//////////////////////
        case INMUEBLES_DESP_COM_SECUESTRO_COORDINARO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="coordinador('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo INMUEBLES_DESP_COM_SECUESTRO; ?>',
                                        '<?php echo INMUEBLES_DESP_COM_SECUESTRO_NUM; ?>', '<?php echo INMUEBLES_DESP_COM_SECUESTRO_DEVOLUCION; ?>', '0', '',
                                        '<?php echo INMUEBLES_DESP_COM_SECUESTRO_SUBIR_ARCHIVO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;

        /////////////INMUEBLES_SECUESTRO//////////////////////
        case INMUEBLES_SECUESTRO_COORDINARO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="coordinador('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo INMUEBLES_SECUESTRO; ?>',
                                        '<?php echo INMUEBLES_SECUESTRO_NUM; ?>', '<?php echo INMUEBLES_SECUESTRO_DEVOLUCION; ?>', '0', '',
                                        '<?php echo INMUEBLES_SECUESTRO_SUBIR_ARCHIVO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;

        /////////////INMUEBLES_AUTO_SECUESTRO//////////////////////
        case INMUEBLES_AUTO_SECUESTRO_COORDINARO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="coordinador('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo INMUEBLES_AUTO_SECUESTRO; ?>',
                                        '<?php echo INMUEBLES_AUTO_SECUESTRO_NUM; ?>', '<?php echo INMUEBLES_AUTO_SECUESTRO_DEVOLUCION; ?>', '0', '',
                                        '<?php echo INMUEBLES_AUTO_SECUESTRO_SUBIR_ARCHIVO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;

        /////////////INMUEBLES_DOCUMENTO_SECUESTRO//////////////////////
        case INMUEBLES_DOCUMENTO_SECUESTRO_COORDINARO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="coordinador('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo INMUEBLES_DOCUMENTO_SECUESTRO; ?>',
                                        '<?php echo INMUEBLES_DOCUMENTO_SECUESTRO_NUM; ?>', '<?php echo INMUEBLES_DOCUMENTO_SECUESTRO_DEVOLUCION; ?>', '0', '',
                                        '<?php echo INMUEBLES_DOCUMENTO_SECUESTRO_SUBIR_ARCHIVO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;

        /////////////INMUEBLES_COMISORIO_SECUESTRO//////////////////////
        case INMUEBLES_COMISORIO_SECUESTRO_COORDINARO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="coordinador('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo INMUEBLES_COMISORIO_SECUESTRO; ?>',
                                        '<?php echo INMUEBLES_COMISORIO_SECUESTRO_NUM; ?>', '<?php echo INMUEBLES_COMISORIO_SECUESTRO_DEVOLUCION; ?>', '0', '',
                                        '<?php echo INMUEBLES_COMISORIO_SECUESTRO_SUBIR_ARCHIVO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;

        /////////////VEHICULO//////////////////////
        case VEHICULO_COORDINARO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="coordinador('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo VEHICULO; ?>', '<?php echo VEHICULO_NUM; ?>', '<?php echo VEHICULO_DEVOLUCION; ?>', '0', '',
                                        '<?php echo VEHICULO_SUBIR_ARCHIVO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;

        /////////////VEHICULO_EMBARGO//////////////////////
        case VEHICULO_EMBARGO_COORDINARO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="coordinador('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo VEHICULO_EMBARGO; ?>', '<?php echo VEHICULO_EMBARGO_NUM; ?>',
                                        '<?php echo VEHICULO_EMBARGO_DEVOLUCION; ?>', '0', '',
                                        '<?php echo VEHICULO_EMBARGO_SUBIR_ARCHIVO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;

        /////////////VEHICULO_COMISION//////////////////////
        case VEHICULO_COMISION_COORDINARO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="coordinador('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo VEHICULO_COMISION; ?>', '<?php echo VEHICULO_COMISION_NUM; ?>',
                                        '<?php echo VEHICULO_COMISION_DEVOLUCION; ?>', '0', '',
                                        '<?php echo VEHICULO_COMISION_SUBIR_ARCHIVO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;

        /////////////VEHICULO_DESPACHO//////////////////////
        case VEHICULO_DESPACHO_COORDINARO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="coordinador('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo VEHICULO_DESPACHO; ?>', '<?php echo VEHICULO_DESPACHO_NUM; ?>',
                                        '<?php echo VEHICULO_DESPACHO_DEVOLUCION; ?>', '0', '',
                                        '<?php echo VEHICULO_DESPACHO_SUBIR_ARCHIVO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;

        /////////////VEHICULO_INCORPORANDO//////////////////////
        case VEHICULO_INCORPORANDO_COORDINARO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="coordinador('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo VEHICULO_INCORPORANDO; ?>', '<?php echo VEHICULO_INCORPORANDO_NUM; ?>',
                                        '<?php echo VEHICULO_INCORPORANDO_DEVOLUCION; ?>', '0', '',
                                        '<?php echo VEHICULO_INCORPORANDO_SUBIR_ARCHIVO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;

        /////////////VEHICULO_FECHA//////////////////////
        case VEHICULO_FECHA_COORDINARO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="coordinador('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo VEHICULO_FECHA; ?>', '<?php echo VEHICULO_FECHA_NUM; ?>',
                                        '<?php echo VEHICULO_FECHA_DEVOLUCION; ?>', '0', '',
                                        '<?php echo VEHICULO_FECHA_SUBIR_ARCHIVO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;

        /////////////VEHICULO_DILIGENCIA//////////////////////
        case VEHICULO_DILIGENCIA_COORDINARO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="coordinador('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo VEHICULO_DILIGENCIA; ?>', '<?php echo VEHICULO_DILIGENCIA_NUM; ?>',
                                        '<?php echo VEHICULO_DILIGENCIA_DEVOLUCION; ?>', '0', '',
                                        '<?php echo VEHICULO_DILIGENCIA_SUBIR_ARCHIVO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;

        /////////////VEHICULO_OPOSICION//////////////////////
        case VEHICULO_OPOSICION_COORDINARO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="coordinador('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo VEHICULO_OPOSICION; ?>', '<?php echo VEHICULO_OPOSICION_NUM; ?>',
                                        '<?php echo VEHICULO_OPOSICION_DEVOLUCION; ?>', '0', '',
                                        '<?php echo VEHICULO_OPOSICION_SUBIR_ARCHIVO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;

        /////////////VEHICULO_SECUESTRO//////////////////////
        case VEHICULO_SECUESTRO_COORDINARO:
            ?>
            <input class="push" type="radio" name="gestion" onclick="coordinador('<?php echo $id ?>', '<?php echo $cod_fis ?>',
                                        '<?php echo $nit ?>', '<?php echo $id ?>', '<?php echo VEHICULO_SECUESTRO; ?>', '<?php echo VEHICULO_SECUESTRO_NUM; ?>',
                                        '<?php echo VEHICULO_SECUESTRO_DEVOLUCION; ?>', '0', '',
                                        '<?php echo VEHICULO_SECUESTRO_SUBIR_ARCHIVO; ?>', '<?php echo $id_prelacion1; ?>')"  />

            <?php
            break;
    }
}
?>