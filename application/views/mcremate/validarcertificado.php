<div align="center"><h3>Generar Certificado de Tradicion y Libertad</h3></div>
<table align="center">
    <tr>
        <td>Numero de Documento</td>
        <td><input type="text" name="ndocumento" id="ndocumento"></td>
    </tr>
    <tr align="center">
        <td colspan="2"><button id="consultar" class="btn btn-success">Consultar</button></td>
    </tr>
</table>
<script>
$('#consultar').clik(function(){
   var documento = $('#ndocumento').val();
   var url = "<?= base_url('index.php/mcremate/validardocumento') ?>";
   $.post(url,{documento : documento}, function(data){
     });
});
</script>
