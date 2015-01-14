<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<div id="container" align="center">
    <p>
    <h1><?php echo $titulo; ?></h1>

</div>
<div >
    <form id="form1" action="<?php echo base_url('index.php/reporteador/reporte_exogenas') ?>" method="POST">
        <input type="hidden" name="vista" id="vista" value="<?php echo $vista; ?>">
        <table width="80%" border="0" style="margin: 0 auto;">
            <tr>
                <td colspan="4" align="right" style="padding-right: 20px;">Valor base para generación de Informe</td>
                <td colspan="2" align="left"><input type="text" name="monto" id="monto" /></td>
            </tr>
            <tr>
                <td>Concepto</td>
                <td>
                    <select id="reporte" name="reporte">
                        <?php
                        echo $desplegable;
                        ?>
                    </select>
                </td>
                <td></td>
                <td></td>
                <td>AÑO</td>
                <td>
                    <div class="ano">
                        <select  name="ano" id="ano" >
                            <?php
                            $ano = date('Y');
                            for ($i = 0; $i < 5; $i++) {
                                ?>
                                <option value="<?php echo $ano - $i ?>"><?php echo $ano - $i ?></option>
                                    <!--<input type="checkbox" name="regional[]" value="<?php echo $regional['COD_REGIONAL'] ?>"><?php echo $regional["NOMBRE_REGIONAL"] ?><br>-->
                                <?php
                            }
                            ?>
                        </select> 
                    </div>
                </td>
            </tr>
            <tr>
                <td align='right'>
                    <input type="radio" value="1" name="tipo" checked="checked  ">
                </td>
                <td>
                    Ingresos recibidos
                </td>
                <td align='right'>
                    <input type="radio" value="2" name="tipo">
                </td>
                <td>
                    Saldos de cuentas por cobrar
                </td>
                <td align='right'>
                    <input type="radio" value="3" name="tipo">
                </td>
                <td>
                    Saldos de cuentas otras cuentas por cobrar
                </td>
            </tr>
            <tr>
                <td colspan="6" align="center">
            <button class="primer2 fa fa-bar-chart-o btn btn-success" align="center" id="cunsultar"> Consultar</button>   &nbsp;&nbsp;             

            <!--<button id="pdf" class="btn btn-info fa fa-floppy-o"> PDF</button>&nbsp;&nbsp;-->

            <button id="excel" class="btn btn-info fa fa-table" > Excel</button>
        </td>
            </tr>
        </table>
        <input type="hidden" id="accion" name="accion" value="0">
        <input type="hidden" id="name_reporte" name="name_reporte" value="Reporte_Exogenas">
    </form>
</div>
<div id="resultados"></div>
<script>
    $('#excel').click(function() {
        $('#accion').val("1");
    });
    $('#pdf').click(function() {
        $('#accion').val("2");
    });
    $('#cunsultar').click(function() {
        $('#accion').val("0");
    });
    function enviar() {
        var accion = $('#accion').val();
        if (accion == 1 || accion == 2)
            return true;
        else
            return false;
    }
    function ajaxValidationCallback(status, form, json, options) {

    }
    $('.primer').click(function() {
        $("#table thead *").remove();
        $("#table tbody *").remove();
        $('#accion').val("3");
        jQuery(".preload, .load").show();
        var url = "<?php echo base_url('index.php/reporteador/reporte_exogenas'); ?>";
        $.post(url, $('#form1').serialize())
                .done(function(msg) {
                    jQuery(".preload, .load").hide();
                    $('#resultados').html(msg);
                }).fail(function(msg) {
            jQuery(".preload, .load").hide();
            alert('Datos No Encontrados');
        })
    });
    jQuery(".preload, .load").hide();
</script>