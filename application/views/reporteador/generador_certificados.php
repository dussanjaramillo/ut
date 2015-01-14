<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<h1>Certificados Fic</h1>
<table style="margin: 0 auto;">
    <tr>
        <td>Certificado: </td>
        <td>
            <select id="certificado" name="certificado">
                <option value="-1"></option>
                <option value="certificados3">certificados3</option>
                <option value="certificados7">Pagos Ordinarios/NIT-Nro. Obra</option>
                <option value="certificados8">Pagos Ordinarios/Nro Transacción</option>
                <option value="certificados9">Pagos Ordinarios/NIT-Periodo</option>
                <option value="certificados10">Pagos Ordinarios/Obra</option>
                <option value="certificados11">certificados11</option>
                <option value="certificados12">Pagos Ordinarios/Nro. Referencia Dispersión</option>
                <option value="certificados13">Liquidaciones-Resoluciones/NIT</option>
                <option value="certificados14">certificados14</option>
                <option value="certificados15">Liquidaciones-Resoluciones/Nro. Liquidacion</option>
                <option value="certificados16">Pagos Ordinarios/NIT-Periodo</option>
            </select>
        </td>
    </tr>
    <tr>
        <td colspan="2">
    <center><button id="cargar" class="btn btn-success">Cargar</button></center>
</td>
</tr>
</table>
<hr>

<div id="resultado"></div>

<script>
    $('#cargar').click(function() {
        var certificado = $('#certificado').val();
        if (certificado == '-1') {
            alert('No se ha seleccionado el certificado');
            return false;
        }
        jQuery(".preload, .load").show();
        var url = "<?php echo base_url("index.php/reporteador/") ?>";
        $.post(url + '/' + certificado, {certificado: certificado})
                .done(function(msg) {
                    $('#resultado').html(msg)
                    jQuery(".preload, .load").hide();
                }).fail(function(msg) {
            alert('Error de conexión');
            jQuery(".preload, .load").hide();
        })
    })

    jQuery(".preload, .load").hide();
</script>