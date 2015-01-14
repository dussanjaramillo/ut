<h1><?php echo $titulo; ?></h1>
<form id="form1" action="<?php echo base_url('index.php/reporteador/personalizar_datos') ?>" method="post">
    <table width="100%" border="0">
        <tr>
            <td >Concepto</td>
            <td>
                <?php $datos = Reporteador::concepto(null) ?>
                <?php
                foreach ($datos as $concepto) {
                    $info = str_replace("_", " ", $concepto["NOMBRE_CONCEPTO"]);
                    ?>
                    <input type="checkbox" name="concepto[]" class="concepto" value="<?php echo $concepto['COD_CPTO_FISCALIZACION'] ?>"><?php echo $info; ?><br>
                    <?php
                }
                ?>
            </td>
            <td>Liquidaci&oacute;n</td>
            <td>
                <?php $liquidacion = Reporteador::datos_tabla('LIQUIDACION') ?>
                <?php
                foreach ($liquidacion as $liquidacion2) {
                    $check = '';
                    if ($liquidacion2["COLUMN_NAME"] == 'NUM_LIQUIDACION') {
                        $check = 'checked="checked" readonly="readonly" disabled="disabled"';
                    }
                    $info = str_replace("_", " ", $liquidacion2['COLUMN_NAME']);
                    if ($info != 'COD CONCEPTO' && $info != 'FECHA VENCIMIENTO' && $info != 'BLOQUEADA') {
                        ?>
                        <input type="checkbox"  name="liquidacion[]" <?php echo $check; ?> class="liquidacion" value="LIQUIDACION.<?php echo $liquidacion2['COLUMN_NAME'] ?>"><?php echo $info ?><br>
                        <?php
                    }
                }
                ?>
            </td>
        </tr>
        <tr>
            <td>Resolucion</td>
            <td>
                <?php $resolucion = Reporteador::datos_tabla('RESOLUCION') ?>
                <?php
                foreach ($resolucion as $resolucion2) {
                    $info = str_replace("_", " ", $resolucion2['COLUMN_NAME']);
                    if ($info != 'COD ESTADO' && $info != 'NOMBRE ARCHIVO RESOLUCION' && $info != 'NUM LIQUIDACION' && $info != 'PERIODO INICIAL' && $info != 'PERIODO FINAL' && $info != 'ELABORO' && $info != 'REVISO' && $info != 'FECHA ACTUAL' && $info != 'COD PLANTILLA' && $info != 'APROBADA' && $info != 'OBSERVACIONES APROBACION' && $info != 'RESPONSABLE' && $info != 'DOCUMENTO FIRMADO' && $info != 'RUTA DOCUMENTO FIRMADO' && $info != 'TEXTO RESOLUCION' && $info != 'COD CPTO FISCALIZACION' && $info != 'COD FISCALIZACION' && $info != 'COD GESTION COBRO' && $info != 'FECHA GESTION' && $info != 'DOCINIACUERDO' && $info != 'DOCUMENTO COBRO COACTIVO' && $info != 'DETALLE COBRO COACTIVO' && $info != 'FECHA LIQUIDACION') {
                        ?>
                        <input type="checkbox" name="resolucion[]" class="resolucion" value="RESOLUCION.<?php echo $resolucion2['COLUMN_NAME'] ?>"><?php echo $info ?><br>
                        <?php
                    }
                }
                ?>
            </td>
            <td>Ejecutoria</td>
            <td>
                <?php $ejecutoria = Reporteador::datos_tabla('EJECUTORIA') ?>
                <?php
                foreach ($ejecutoria as $ejecutoria2) {
                    $info = str_replace("_", " ", $ejecutoria2['COLUMN_NAME']);
                    if ($info != 'COD FISCALIZACION' && $info != 'COD GESTION COBRO' && $info != 'DOCUMENTO PERSUASIVO' && $info != 'ESTADO' && $info != 'DOCUMENTO EJECUTORIA') {
                        ?>
                        <input type="checkbox" name="ejecutoria[]" class="ejecutoria" value="EJECUTORIA.<?php echo $ejecutoria2['COLUMN_NAME'] ?>"><?php echo $info ?><br>
                        <?php
                    }
                }
                ?>
            </td>
        </tr>
        <tr>
            <td>Revocatoria</td>
            <td>
                <?php $revocatoria = Reporteador::datos_tabla('REVOCATORIA') ?>
                <?php
                foreach ($revocatoria as $revocatoria2) {
                    $info = str_replace("_", " ", $revocatoria2['COLUMN_NAME']);
                    if ($info != 'COD RESOLUCION' 
                            && $info != ' CREACION FECHA' 
                            && $info != 'ACTUALIZA EQUIPO' 
                            && $info != 'DOC REVOCATORIA' 
                            && $info != 'DOC RESPUESTA' 
                            && $info != 'DOC RESPUESTA FIRMADO' 
                            && $info != 'COD GESTION COBRO'
                            && $info != 'ESTADO REVOCATORIA'
                            && $info != 'DOC GENERADO') {
                    ?>
                    <input type="checkbox" name="revocatoria[]" class="revocatoria" value="REVOCATORIA.<?php echo $revocatoria2['COLUMN_NAME'] ?>"><?php echo $info ?><br>
                    <?php
                    }
                }
                ?>
            </td>
        </tr>
    </table>
    <p><br><p>
    <table width="100%">
        <tr bgcolor="#5BB75B" style="color: #FFF">
            <td colspan="4"><center>CONDICIONALES</center></td>
        </tr>
        <tr>
            <td>Fechas Por Liquidacion </td><td><input type="radio" name="liquidacion_ok" class="liquidacion_ok"></td>
        </tr>
        <tr>
            <td><div class="liq_vista" >Fechas Inicial </div></td>
            <td><div class="liq_vista" ><input type="text" class="fecha" readonly="readonly" name="fecha_ini_liq" id="fecha_ini_liq"></div></td>
            <td><div class="liq_vista" >Fechas Fin</div></td>
            <td><div class="liq_vista" ><input type="text" class="fecha" readonly="readonly" name="fecha_fin_liq" id="fecha_fin_liq"></div></td>
        </tr>
        <tr>
            <td>Fechas Por Resoluci&oacute;n</td><td><input type="radio" name="resolucion_ok" class="resolucion_ok"></td>
        </tr>
        <tr>
            <td><div class="res_vista" >Fechas Inicial </div></td>
            <td><div class="res_vista" ><input type="text" class="fecha" readonly="readonly" name="fecha_ini_res" id="fecha_ini_res"></div></td>
            <td><div class="res_vista" >Fechas Fin</div></td>
            <td><div class="res_vista" ><input type="text" class="fecha" readonly="readonly" name="fecha_fin_res" id="fecha_fin_res"></div></td>
        </tr>
        <tr>
            <td>Fechas Por ejecutoria</td><td><input type="radio" name="ejecutoria_ok" class="ejecutoria_ok"></td>
        </tr>
        <tr>
            <td><div class="eje_vista" >Fechas Inicial </div></td>
            <td><div class="eje_vista" ><input type="text" class="fecha" readonly="readonly" name="fecha_ini_eje" id="fecha_ini_eje"></div></td>
            <td><div class="eje_vista" >Fechas Fin</div></td>
            <td><div class="eje_vista" ><input type="text" class="fecha" readonly="readonly" name="fecha_fin_eje" id="fecha_fin_eje"></div></td>
        </tr>
        <tr>
            <td>Fechas Por Revocatoria</td><td><input type="radio" name="revocatoria_ok" class="revocatoria_ok"></td>
        </tr>
        <tr>
            <td><div class="rev_vista" ><div id="rev_vista" >Fechas Inicial </div></td>
            <td><div class="rev_vista" ><input type="text" class="fecha" readonly="readonly" name="fecha_ini_rev" id="fecha_ini_rev"></div></td>
            <td><div class="rev_vista" >Fechas Fin</div></td>
            <td><div class="rev_vista" ><input type="text" class="fecha" readonly="readonly" name="fecha_fin_rev" id="fecha_fin_rev"></div></td>
        </tr>
    </table>


    <table width="100%" border="0">
        <tr>
            <td>
                Nit/Empresa
            </td>
            <td >
                <input type="text" id="empresa" name="empresa"><img id="preloadmini" src="<?php echo base_url('img/319.gif') ?>" width="28" height="28" />
            </td>
            <td>
                Regional
            </td>
            <td>
<?php $datos = Reporteador::regional() ?>
                <select id="regional" name="regional">
                    <option value="-1">Todos...</option>
<?php
foreach ($datos as $regional) {
    ?>
                        <option value="<?php echo $regional['COD_REGIONAL'] ?>"><?php echo $regional["NOMBRE_REGIONAL"] ?></option>
    <?php
}
?>
                </select>
            </td>
        </tr>

        <tr>
        <input type="hidden" id="accion" name="accion" value="0">
        <input type="hidden" id="name_reporte" name="name_reporte" value="">
        <td colspan="6" align="center">
            <button class="primer fa fa-bar-chart-o btn btn-success" align="center" id="cunsultar"> Consultar</button>   &nbsp;&nbsp;             

            <button id="pdf" class="btn btn-danger fa fa-floppy-o"> PDF</button>&nbsp;&nbsp;

            <button id="excel" class="btn btn-info fa fa-table" > Excel</button>
        </td>
        </tr>
        <tr>
            <td>
                <div style="display: none">
                    Numero de Liquidacion
                </div>
            </td>
            <td>
                <div style="display: none">
                    <input id="num_liquidacion" type="text" name="num_liquidacion">
                </div>
            </td>
            <td>
                <div style="display: none">
                    Nit
                </div>
            </td>
            <td >
                <div style="display: none">
                    <input type="text" id="nit" name="nit">
                </div>
            </td>
        </tr>
    </table>
</form>

<script>
    $('.liquidacion_ok').click(function() {
        $('.liq_vista').show();
    });
    $('.resolucion_ok').click(function() {
        $('.res_vista').show();
    });
    $('.ejecutoria_ok').click(function() {
        $('.eje_vista').show();
    });
    $('.revocatoria_ok').click(function() {
        $('.rev_vista').show();
    });
    $('.rev_vista').hide();
    $('.eje_vista').hide();
    $('.res_vista').hide();
    $('.liq_vista').hide();

    $(".fecha").datepicker({
        buttonText: 'Fecha',
        buttonImage: '<?php echo base_url() . "img/calendario.png" ?>',
        buttonImageOnly: true,
        numberOfMonths: 1,
        dateFormat: 'dd/mm/yy'
    });
    $("#empresa").autocomplete({
        source: "<?php echo base_url("index.php/reporteador/autocompleteemrpesas") ?>",
        minLength: 3,
        search: function(event, ui) {
            $("#preloadmini").show();
        },
        response: function(event, ui) {
            $("#preloadmini").hide();
        }
    });
    $("#preloadmini").hide();
    $('#excel').click(function() {
        $('#accion').val("1");
    });
    $('#pdf').click(function() {
        $('#accion').val("2");
    });
    $('#cunsultar').click(function() {
        $('#accion').val("0");
    });
</script>
<style>
    /*    table tr td:hover {
            background: #f9f9f9;
        }*/

    table tr {
        border: 1px solid #f9f9f9;
        -webkit-border-radius: 15px;
        -moz-border-radius: 15px;
        border-radius: 15px;
    }
</style>