<p><br>
<center><h1><?php if ($input == 1 || $input == 2 || $input == 17)
    echo $titulo;
?>
    </h1></center>

<p><br>
<form id="form1" action="<?php echo base_url('index.php/reporteador/imprimir_certificacion') ?>" method="post" onsubmit="return confirmar()">
    <input type="hidden" name="vista" id="vista" value="<?php echo $vista; ?>">
    <input type="hidden" name="name_reporte" id="name_reporte" value="<?php echo $titulo; ?>">
    <table width="350px" border="0" style="margin: 0 auto;">
<?php if ($input != 8 && $input != 9 && $input != 10 && $input != 15) { ?>
            <tr>
                <td>
                    Nit/Empresa
                </td>
                <td >
                    <input type="text" id="empresa" name="empresa"><img id="preloadmini" src="<?php echo base_url('img/319.gif') ?>" width="28" height="28" />
                </td>
            </tr>
        <?php } ?>
<?php if ($input == 8 || $input == 9) { ?>
            <tr>
                <td>
                    Nro. Transacción
                </td>
                <td >
                    <input type="text" id="transac" name="transac">
                </td>
            </tr>
        <?php } ?>
<?php if ($input == 2 || $input == 3 || $input == 17) { ?>
            <tr>
                <td>
                    Año
                </td>
                <td  >
                    <select name="ano" id="ano">
                        <?php
                        $ano = date('Y');
                        for ($i = 0; $i < 5; $i++) {
                            ?>
                            <option value="<?php echo $ano - $i ?>"><?php echo $ano - $i ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </td>
            </tr>
        <?php } ?>
<?php if ($input == 7 || $input == 10) { ?>
            <tr>
                <td>
                    Licencia y/u obra
                </td>
                <td >
                    <input type="text" name="obra" id="obra">
                </td>
            </tr>
        <?php } ?>
<?php if ($input == 13 || $input == 15) { ?>
            <tr>
                <td>
                    Liquidaci&oacute;n / Resoluci&oacute;n
                </td>
                <td >
                    <input type="text" name="num_proceso" id="num_proceso">
                </td>
            </tr>
<?php } ?>
<?php if ($input == 16) { ?>
            <tr>
                <td>
                    Periodo
                </td>
                <td >
                    <input type="text" class="periodo" readonly id="periodo" name="periodo" value="<?php echo date('Y').'-'.  date('n') ?>">
                </td>
            </tr>
<?php } ?>
        <tr>
            <td align="center" colspan="2">
                <input type="hidden"  id="accion" name="accion" value="<?php echo $input; ?>">
                <button class="btn btn-success">Generar</button>
            </td>
        </tr>
    </table>
</form>
<script>

    function confirmar() {

        if ($('#empresa').val() == '') {
            alert('Campo Nit Obligatorio');
            return false;
        } 
        if ($('#obra').val() == '') {
            alert('Campo Licencia y/u obra Obligatorio');
            return false;
        } 
        if ($('#transac').val() == '') {
            alert('Campo Nro. Transacción Obligatorio');
            return false;
        } 
        if ($('#num_proceso').val() == '') {
            alert('Campo Liquidación / Resolución  Obligatorio');
            return false;
        } 
        if ($('#periodo').val() == '') {
            alert('Campo Liquidación / Resolución  Obligatorio');
            return false;
        } 
        return true;
    }

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
    $(".periodo").datepicker({
        buttonText: 'Fecha',
        buttonImageOnly: true,
        numberOfMonths: 1,
        dateFormat: 'yy-mm',
        showOn: 'button',
        buttonText: 'Seleccione una fecha',
                buttonImage: '<?php echo base_url() . "img/calendario.png" ?>',
        buttonImageOnly: true,
                numberOfMonths: 1,
    });
</script>