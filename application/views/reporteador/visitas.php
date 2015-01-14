<p>
<center><h1>Certificados</h1></center>
<p><br>
<p><br>
<form id="form1" action="<?php echo base_url('index.php/reporteador/imprimir_certificacion') ?>" method="post">
    <table width="300px" border="0" style="margin: 0 auto;">
        <tr>
            <td>
                Nit/Empresa
            </td>
            <td >
                <input type="text" id="empresa" name="empresa"><img id="preloadmini" src="<?php echo base_url('img/319.gif') ?>" width="28" height="28" />
            </td>
        </tr>
        <tr>
            <td align="center" colspan="2">
                <input type="hidden"  id="accion" name="accion" value="<?php echo $input; ?>">
                <button class="btn btn-success">Enviar</button>
            </td>
        </tr>
    </table>
</form>
<script>
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
</script>