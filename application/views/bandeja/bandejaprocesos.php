<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
if (isset($message)) {
    echo $message;
}
if (isset($custom_error))
    echo $custom_error;
?>
<div>
    <h4 align="left">
    <i class="fa fa-male ">  <?php echo "  " .NOMBRE_USUARIO; ?></i>
</h4>
</div>
<h2><center>Bandeja Unificada</center></h2>
<span><h3 style=" text-align: center;">Procesos Cobro Coactivo</h3></span>
<table id="tabla1">
    <thead>
        <tr><th></th>
            <th>Número Proceso</th>
            <th>Fecha <br>Recepción</th>
            <th>Detalle<br>Deuda</th>
            <th>Identificación Ejecutado</th>
            <th>Nombre Ejecutado</th>
            <th>Regional</th>
            <th>Responsable</th>
            <th>Estados</th>
            <th>En Gestión por </th>
            <th>Trazabilidad</th>
            <th>Documentos</th>
            <th>Proc. Trasnv.</th>
        </tr>
    </thead> 
    <tbody>
        <?php
        if ($consulta) {
            /* PROCESOS COACTIVOS   */
            $m = 0;
            foreach ($consulta as $data) {
               ?>

                <tr>
                    <td><?php //echo $m;?></td>
                    <td>
                        <!--Si no se ha aprobado el auto que avoca conocimiento no se debe visualizar el codigo del proceso-->
                        <?php
                        $respuestas = explode("*?*", $data['CODIGOS_RESPUESTAS']);
                        $ver_proceso = FALSE;
                        foreach ($respuestas as $key => $value):
                            if (($key = !653) || ($key != 654) || ($key != 655) || ($key != 656) || ($key != 658)):
                                $ver_proceso = TRUE;
                            endif;
                        endforeach;
                        if ($ver_proceso):
                            echo $data['PROCESOPJ'];
                        else:
                            $fiscalizaciones = explode("?*)", $data['FISCALIZACIONES']);
                            for ($d = 0; $d < count($fiscalizaciones); $d++):
                                echo $fiscalizaciones[$d] . "<br>";
                            endfor;
                        endif;
                        ?></td>
                    <td><?php
                        $fechas = explode("?*", $data['FECHAS_RECEPCION']);
                        echo $fechas[0];
                        ?>
                    </td>
                    <td><a target="_blank"  class="btn btn-small btn-info" title="ver" onclick="ver_detalle_proceso('<?php echo "detalle_deuda" . $m ?>')">
                            <i class="fa fa-eye "></i>
                        </a>
                        <div id="<?php echo "detalle_deuda" . $m ?>" style="display:none">

                            <table width="431" border="1" align="center" class="table table-bordered table-striped">
                                <tr>
                                    <td colspan="7"><?php
//                                
                                        $expedientes = explode("?*", $data['NUMEROS_EXPEDIENTES']);
                                        $conceptos_deuda = explode("?*", $data['CONCEPTOS_UNIDOS']);
                                        $saldo_deuda = explode("?*", $data['SALDOS_DEUDAS']);
                                        $saldo_capital = explode("?*", $data['SALDOS_CAPITAL']);
                                        $saldo_interes = explode("?*", $data['SALDOS_INTERESES']);

                                        //Para eliminar los datos repetidos    
                                        $expedientes = array_unique($expedientes); //Para eliminar los codigos de respuestas repetidos
                                        $saldo_deuda = array_unique($saldo_deuda); //Para eliminar los codigos de respuestas repetidos
                                        $saldo_capital = array_unique($saldo_capital); //Para eliminar los nombres de respuestas repetidos
                                        $saldo_interes = array_unique($saldo_interes);

                                        $expedientes = array_values($expedientes);
                                        $saldo_deuda = array_values($saldo_deuda);
                                        $saldo_capital = array_values($saldo_capital);
                                        $saldo_interes = array_values($saldo_interes);
                                        $fiscalizaciones2 = explode("?*", $data['FISCALIZACIONES']);
                                        $sum_deuda = 0;
                                        $sum_capital = 0;
                                        $sum_interes = 0;
                                        ?>
                                <center>   </center>
                                </td></tr>
                                <tr>
                                    <td width="34"><div align="center">No. Titulo</div></td>
                                    <td width="60"><div align="center">Concepto</div></td>
                                    <td width="111"><div align="center">Saldo de <BR>la Deuda</div></td>
                                    <td width="89"><div align="center">Saldo <BR> Capital</div></td>
                                    <td width="103"><div align="center">Saldo Con<BR> Intereses</div></td>
                                    <td width="103"><div align="center">Sanción</div></td>
                                    <td width="103"><div align="center">Multa</div></td>
                                </tr>

                                <?php
                                //echo count($expedientes);
                                for ($v = 0; $v < count($expedientes); $v++):

                                    if (!empty($saldo_deuda[$v])):
                                        ?>
                                        <tr>
                                            <td width="34"><div align="center"><?php echo $fiscalizaciones2[$v]; ?></div></td>
                                            <td width="60"><div align="center"><?php echo $conceptos_deuda[0]; ?></div></td>
                                            <td width="111"><div align="center"><input type="text" readonly="readonly" name="<?php echo "p_saldo_deuda_" . $m . "_" . $v; ?>" id="<?php echo "p_saldo_deuda_" . $m . "_" . $v ?>" class="input-small uneditable-input info" value="<?php echo number_format($saldo_deuda[$v], 0, '.', '.') ?>"></div></td>
                                            <td width="89"><div align="center"><input type="text" readonly="readonly" name="<?php echo "p_saldo_capital_" . $m . "_" . $v; ?>" id="<?php echo "p_saldo_capital_" . $m . "_" . $v ?>" class="input-small uneditable-inpu info" value="<?php echo number_format($saldo_capital[$v], 0, '.', '.') ?>"></div></td>
                                            <td width="103"><div align="center"><input type="text" readonly="readonly" name="<?php echo "p_saldo_interes_" . $m . "_" . $v; ?>" id="<?php echo "p_saldo_interes_" . $m . "_" . $v ?>" class="input-small uneditable-inpu" value="<?php echo number_format($saldo_interes[$v], 0, '.', '.') ?>"></div></td>
                                            <td width="103"><div align="center"><input type="text" readonly="readonly" name="<?php echo "p_saldo_sancion_" . $m; ?>" id="<?php echo "p_saldo_sancion_" . $m ?>" class="input-small uneditable-input" value="<?php echo "0" ?>"></div></td>
                                            <td width="103"><div align="center"><input type="text" readonly="readonly" name="<?php echo "p_saldo_multa_" . $m; ?>" id="<?php echo "p_saldo_multa_" . $m ?>" class="input-small uneditable-input" value="<?php echo "0" ?>"></div></td>
                                        </tr>
                                        <?php
                                        $sum_deuda = $sum_deuda + $saldo_deuda[$v];
                                        $sum_capital = $sum_capital + $saldo_capital[$v];
                                        $sum_interes = $sum_interes + $saldo_interes[$v];
                                    endif;

                                endfor;
                                ?>

                                                                        <!--                                <tr>-->
        <?php if ($sum_deuda): ?>
                                    <td width="34" colspan="3"><div align="center">Totales</div></td>
                                    <td width="111"><div align="center"><input type="text" class="input-small uneditable-input" readonly="readonly" name="" id="" value="<?php echo number_format($sum_deuda, 0, '.', '.') ?>"></div></td>
                                    <td width="89"><div align="center"><input type="text"  class="input-small uneditable-input"readonly="readonly" name="" id="" value="<?php echo number_format($sum_capital, 0, '.', '.') ?>"></div></td>
                                    <td width="103"><div align="center"><input type="text" class="input-small uneditable-input"readonly="readonly" name="" id="" value="<?php echo number_format($sum_interes, 0, '.', '.') ?>"></div></td><!--
        <?php endif; ?>
        </tr>-->


                            </table>
                        </div>
                    </td>
                    <td><?php echo $data['IDENTIFICACION']; ?></td>
                    <td><?php echo $data['NOMBRE']; ?></td>
                    <td><?php echo $data['NOMBRE_REGIONAL']; ?></td>
                    <td><?php echo strtoupper($data['NOMBRES'] . " " . $data['APELLIDOS']); ?></td>
                    <td>     

                        <?php
                        $cantidad = count($data['ABOGADO']);
                        if ($cantidad > 0):
                            $cod_abogado = $data['ABOGADO'];
                        else:
                            $cod_abogado = 0;
                        endif;
                       // echo  $data['CODIGOS RESPUESTAS'];
                    $estado = explode("*?*", $data['CODIGOS_RESPUESTAS']);
//echo "<br>"; echo $data['RESPUESTAS_UNIDAS'];
                    $nombre = explode("*?*", $data['RESPUESTAS_UNIDAS']);
                    $estado = array_unique($estado); //Para eliminar los codigos de respuestas repetidos
                    $nombre = array_unique($nombre); //Para eliminar los nombres de respuestas repetidos
                    $estado = array_values($estado); //Armo nuevamente las posiciones del array
                    $nombre = array_values($nombre); //Armo nuevamente las posiciones del array
                    ?>
                    <div id="a" style="display:block">
                        <?php
                        $l = 0;
                        foreach ($estado as $key => $value) {

                            if ((isset($value)) && $value != ''):
                                $estado[$l] = $value;
                                $l++;
                            endif;
                        }
                        ?>
                        <select name="estados" id="estados"  onchange="f_enviar('<?php echo $conceptos_deuda[0] ?>', '<?php echo $data['PROCESOPJ'] ?>', '<?php echo $data['IDENTIFICACION'] ?>',this.value, '<?php echo $m; ?>', '<?php echo $data['COD_REGIONAL'] ?> ', '<?php echo $cod_abogado ?>', '<?php echo $data['COD_PROCESO'] ?>');" >
                            <option value="0">Seleccione el Estado</option> 
                            <?php
                            for ($i = 0; $i < count($nombre); $i++):
                                if ($estado[$i]):
                                    ?>
                                    <option value="<?php echo $estado[$i]; ?>"><?php echo $nombre[$i]; ?></option>
                                    <?php
                                endif;
                            endfor;
                            ?>
                        </select> 
                        <div class="preload" id="preload" style="display:none">
                            <img  id="load" class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" 
                                  height="128" />
                        </div>
                    </td>
                    <td>  <div id="<?php echo "responsable_" . $m; ?>"> </div></td>
                    <td> <form name="form1" id="form1" method="post" target="_blank" action="<?php echo $ruta_traza ?>">
                            <input type="hidden" id="FISCALIZACION" name="FISCALIZACION" value="<?php echo $data['COD_PROCESO']; ?>" />
                            <input type="submit" class="btn btn-info" name="Abrir" id="Abrir"  value="Abrir">
                        </form>
                    </td>   
                    <td>
                        <form name="form1" id="form1" method="post" target="_blank" action="<?php echo $ruta_expediente ?>">
                            <input type="hidden" name="cod_proceso" id="cod_proceso" value="<?php echo $data['COD_PROCESO']; ?>">
                            <button name="boton"  class="btn btn-small btn-info" type="submit" > <i class="fa fa-folder-open-o" ></i></button>
                        </form>
                    </td>
                    <td><?php //if (ID_USUARIO == $data['ABOGADO']):  ?>
                        <a target="_blank"  class="btn btn-small btn-info" title="ver" onclick=" f_procesos('<?php echo $data['COD_PROCESO'] ?>', '1');">
                            <i class="fa fa-book "></i>
                        </a>
        <?php //endif;  ?>
                    </td>
                </tr>
                <?php
                $m++;
            }
        }
        ?>

    </tbody>      
</table>
<div id="procesos_transversales" style="text-align:center;display:none"  >
    <button class="btn btn-success"  style="width:210px;display: none " name="boton_traslado" id="boton_traslado" type="button" value="" onclick="enviar_proceso(this.value, '<?php echo $ruta_traslado_judicial; ?>');">Traslado Judicial </button>
    <br> <br>
    <button class="btn btn-success"  style="width:210px;display: none  " name="boton_prescripcion" id="boton_prescripcion" type="button" value="" onclick="enviar_proceso(this.value, '<?php echo $ruta_resolucion_prescripcion; ?>');">Resolución Prescripción</button>
    <br> <br>

    <button class="btn btn-success"  style="width:210px;display: none  " name="boton_nulidad" id="boton_nulidad" type="button" value="" onclick="enviar_proceso(this.value, '<?php echo $ruta_nulidad; ?>');">Nulidades</button>
    <br> <br>

    <button class="btn btn-success"  style="width:210px;display: none  " name="boton_liquidacion" id="boton_liquidacion" type="button" value="" onclick="enviar_proceso(this.value, '<?php echo $ruta_liquidacion; ?>');">Liquidación de Crédito</button>
    <br><br>
    <button class="btn btn-success"  style="width:210px;display: none  " name="boton_terminacion" id="boton_terminacion" type="button" value="" onclick="enviar_proceso(this.value, '<?php echo $ruta_terminacion; ?>');">Terminación de Proceso</button>
    <br>
    <br>
    <button class="btn btn-success"  style="width:210px;display: none  " name="boton_remisibilidad" id="boton_remisibilidad" type="button" value="" onclick="enviar_proceso(this.value, '<?php echo $ruta_remisibilidad; ?>');">Remisibilidad</button>
    <br>
</div>
<div style="text-align:center; display:none" >
    <form name="form_proceso" method="post" id="form_proceso" target="_blank" action="" >
        <form name="form_proceso" method="post" id="form_proceso" target="_blank" action="" >
        <input type="text" name="cod_coactivo_traslado" id="cod_coactivo_traslado2" >
        <input type="text" name="cod_coactivo_prescripcion" id="cod_coactivo_prescripcion2">
        <input type="text" name="cod_coactivo_liquidacion" id="cod_coactivo_liquidacion">
        <input type="text" name="cod_coactivo_nulidad" id="cod_coactivo_nulidad">
        <input type="text" name="cod_coactivo_terminacion" id="cod_coactivo_terminacion">
        <input type="text" name="cod_coactivo_remisibilidad" id="cod_coactivo_remisibilidad">
        <input type="text" name="cod_coactivo_facilidad" id="cod_coactivo_facilidad">
    </form>

    </form>

</div>
<script type="text/javascript" language="javascript" charset="utf-8">

    /*Función que actualiza la página*/
    function finalizar() {
        location.reload();
    }
    /*Función para visualizar el detalle de la deuda de un procesos en una ventana modal*/
    /*El párametro que recibe el id del div que contiene el detalle de la deuda*/
    function ver_detalle_proceso(id) {

        $("#" + id).show();

        $('#' + id).dialog({
            autoOpen: true,
            width: 900,
            height: 450,
            modal: true,
            title: "<?php echo "Detalle Deuda"; ?>",
            close: function() {
                // $('#'+id+' *').remove();

            }

        });
    }
    /*Al seleccionar un estado del proceso coactivo, se consulta el responsable de realizar la gestión */
    function f_enviar(concepto, expediente, nit,cod_respuesta, i, regional, cod_abogado, cod_proceso)
    {

        $("#preload").show();
        var url = "<?= base_url("index.php/bandejaunificada/Funcionarios") ?>";
        $.post(url, {concepto: concepto, expediente: expediente, nit: nit,cod_respuesta: cod_respuesta, regional: regional, cod_abogado: cod_abogado, cod_proceso: cod_proceso}, function(data) {
            $("#responsable_" + i).html(data);
            $("#preload").hide();
        })
    }
    /*Funcion que envia los titulos a la url correspondiente para realizar la gestion*/
    function f_enviar_titulos(concepto, expediente, nit, cod_respuesta, i, regional, cod_abogado, cod_proceso)
    {
        $("#preload").show();
        var url = "<?= base_url("index.php/bandejaunificada/Funcionarios") ?>";
        $.post(url, {concepto: concepto, expediente: expediente, nit: nit, cod_respuesta: cod_respuesta, regional: regional, cod_abogado: cod_abogado, cod_proceso: cod_proceso, recepcion_id: cod_proceso}, function(data) {
            $("#responsabletitulos_" + i).html(data);
            $("#preload").hide();
        })
    }
    /*Función que envia un proceso a los diferentes procesos transversales*/
    /*Recibe el cod del proceso */
     function enviar_proceso(cod_coactivo, url_formulario) {
        $("#cod_coactivo_liquidacion").val(cod_coactivo);
        $("#cod_coactivo_traslado2").val(cod_coactivo);
        $("#cod_coactivo_prescripcion2").val(cod_coactivo);
        $("#cod_coactivo_nulidad").val(cod_coactivo);
        $("#cod_coactivo_terminacion").val(cod_coactivo);
        $("#cod_coactivo_remisibilidad").val(cod_coactivo);
        $("#cod_coactivo_facilidad").val(cod_coactivo);
        $("#form_proceso").attr("action", url_formulario);
        $("#form_proceso").submit();
    }


    /*Visualiza ventana modal para seleccionar el proceso transversal*/
    function f_procesos(cod_coactivo, visualiza) {
        if (visualiza == 1) {
            $("#boton_traslado").show();
            $("#boton_prescripcion").show();
            $("#boton_liquidacion").show();
            $("#boton_nulidad").show();
            $("#boton_terminacion").show();
            $("#boton_remisibilidad").show();

        }
        $("procesos_transversales").show();
        $("#boton_traslado").val(cod_coactivo);
        $("#boton_prescripcion").val(cod_coactivo);
        $("#boton_liquidacion").val(cod_coactivo);
        $("#boton_terminacion").val(cod_coactivo);
        $("#boton_nulidad").val(cod_coactivo);
         $("#boton_remisibilidad").val(cod_coactivo);
        $('#procesos_transversales').dialog({
            autoOpen: true,
            width: 300,
            height: 450,
            modal: true,
            title: "<?php echo "Procesos Transversales"; ?>",
            close: function() {
                // $('#procesos_transversales *').remove();
                $('#procesos_transversales').hide();
            }

        });

    }
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
</script>

<style>
    .div_procesos_transversales{
        padding-top: 5px;
        position: absolute;
        left: 50%;
        top: 50%;
        margin-left: -64px;
        margin-top: -64px;
        z-index: 15000;
    }
    tabla1 {
        table-layout:fixed;
    }
    td{
        overflow:hidden;
        text-overflow: ellipsis;
    }

</style>

