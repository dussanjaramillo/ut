<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<form id="form2" action="<?php echo base_url('index.php/reporteador/imprimir_BDME') ?>"  method="POST" ><!--target="_blank"-->
    <p><h1><?php echo $titulo; ?></h1><p>
    <table width="580px" style="margin: 0 auto;">
        <tr>
            <td align="right"><input type="radio" name="bdme" checked="checked" value="bdme_reporte_semestral" id="bdme_reporte_semestral"></td>
            <td>BDME REPORTE SEMESTRAL</td>
        </tr>
        <tr>
            <td align="right"><input type="radio" name="bdme" id="bdme_incumplimiento_semestral" value="bdme_incumplimiento_semestral"></td>
            <td>BDME INCUMPLIMIENTOS ACUERDO PAGO SEMESTRAL</td>
        </tr>
        <tr>
            <td align="right"><input type="radio" name="bdme" value="bdme_actualizacion" id="bdme_actualizacion"></td>
            <td>BDME ACTUALIZACION</td>
        </tr>
        <tr>
            <td align="right"><input type="radio" name="bdme" value="bdme_cancelacion_acuerdo_de_pago" id="bdme_cancelacion_acuerdo_de_pago"></td>
            <td>BDME CANCELACIONES</td>
        </tr>
        <tr>
            <td align="right"><input type="radio" name="bdme" value="bdme_retiro" id="bdme_retiro"></td>
            <td>BDME RETIROS</td>
        </tr>
        <tr>
            <td>PERIODO</td>
            <td>
                <select name="periodo" id="periodo">
                    <?php
                    $ano = date('Y');
                    $mes = 12;
//                    $mes = date('m');

                    for ($i = 0; $i < 5; $i++) {

                        for ($j = 0; $j < 2; $j++) {
                            if ($mes >= 5 && $j == 0) {
                                ?>
                                <option value="<?php echo ($ano - $i) . "-05" ?>"><?php echo ($ano - $i) . " Mayo" ?></option>
                                <?php
                            } else if (($mes >= 11) || $ano != ($ano - $i)) {
                                ?>
                                <option value="<?php echo ($ano - $i) . '-11' ?>"><?php echo ($ano - $i) . ' Noviembre' ?></option>
                                <?php
                            }
                        }
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>
                SALARIO
            </td>
            <td>
                <input type="hidden" id="valor_salario" name="valor_salario">
                <div id="resul_salario"></div>
                <input type="hidden" id="salarios_minimos" name="salarios_minimos" value="5" style="width: 20px" maxlength="2">
            </td>
        </tr>
        <tr>
            <td colspan="6" align="center">
                <button class="primer fa fa-bar-chart-o btn btn-success" align="center" id="cunsultar"> Consultar</button>   &nbsp;&nbsp;             

                <button id="pdf" class="btn btn-danger fa fa-floppy-o"> PDF</button>&nbsp;&nbsp;

                <button id="excel" class="btn btn-info fa fa-table" > Excel</button>
            </td>
        </tr>

    </table>
    <input type="hidden" id="anop" name="anop" value="0">
    <input type="hidden" id="accion" name="accion" value="0">
    <input type="hidden" id="titulo" name="titulo" value="<?php echo $titulo; ?>">
</form>


<script>
    $('#periodo').change(function() {
        salario();
    });
    function salario(){
        jQuery(".preload, .load").show();
        var url = "<?php echo base_url('index.php/reporteador/salario'); ?>";
        var salario = $('#salarios_minimos').val()
        var ano_salario = $('#periodo').val();
        ano_salario=ano_salario.split('-');
        var ano_salario_base=ano_salario[0];
        if(ano_salario[1]==5){
            ano_salario_base=ano_salario[0]-1;
            $('#anop').val(ano_salario_base+'11');
        }else if(ano_salario[1]==11){
            $('#anop').val(ano_salario[0]+'05');
        }
       
        $.post(url, {salario: salario, ano_salario: ano_salario[0]})
                .done(function(msg) {
                    jQuery(".preload, .load").hide();
                    var msg2 = msg + " * " + salario + " = " + msg * salario
                    $('#resul_salario').html(msg2);
                    $('#valor_salario').val(msg * salario);
                }).fail(function(msg) {
            jQuery(".preload, .load").hide();
            alert("Error en la consulta")
        });
    }
    salario();
    $('#excel').click(function() {
        $('#accion').val("1");
    });
    $('#pdf').click(function() {
        $('#accion').val("2");
    });
    $('#cunsultar').click(function() {
        $('#accion').val("0");
    });
//jQuery(".preload, .load").hide();
</script>
