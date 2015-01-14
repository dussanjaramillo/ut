<div align="center"><h2>DOCUMENTOS EN LINEA</h2></div>
<br>
<table align="center" style="width: 300px; height: 150px;">
    <tr>
        <td><input type="radio" name="radio" id="liquidacion" value="1" class="radio">Liquidación</td>
    </tr>
    <tr>
        <td><input type="radio" name="radio" id="actos" value="2" class="radio">Resolución/Actos Administrativos</td>
    </tr>
    <tr>
        <td><input type="radio" name="radio" id="cartera" value="3" class="radio">Cartera presunta y real</td>
    </tr>
    <tr>
        <td><input type="radio" name="radio" id="acuerdo" value="4" class="radio">Acuerdo de pago en persuasivo</td>
    </tr>
    <tr>
        <td><input type="radio" name="radio" id="contratos" value="5" class="radio">Contratos FIC</td>
    </tr>
</table>
<br>
<table align="center">
    <tr>
        <td align="center"><button name="generar" id="generar" class="btn btn-success">Generar</button></td>
    </tr>
</table>
<form id="linea">
    <input type="hidden" name="opcion" id="opcion">
</form>
<script>
    $('.radio').click(function(){
      var radio = $(this).val(); 
      $('#opcion').val(radio);
    });
    $('#generar').click(function(){
       var opcion = $('#opcion').val(); 
       if(opcion == 1){
        var url = "<?=  base_url('index.php/reportes/reporteliquidacion')?>";
        $('#linea').attr('action',url);
        $('#linea').submit();
        
       } 
       if(opcion == 2){
        var url = "<?= base_url('index.php/reportes/reporteresolucion')?>" 
        $('#linea').attr('action',url);
        $('#linea').submit();
       }
       if(opcion == 3){
        var url = "<?= base_url('index.php/reportes/reportecartera')?>" 
        $('#linea').attr('action',url);
        $('#linea').submit();
       }
       if(opcion == 4){
        var url = "<?= base_url('index.php/reportes/reporteacuerdos')?>" 
        $('#linea').attr('action',url);
        $('#linea').submit();
       }
       if(opcion == 5){
        var url = "<?= base_url('index.php/reportes/reportescontrato')?>" 
        $('#linea').attr('action',url);
        $('#linea').submit();
       }
       });
</script>
