<div align="center"><h2>GESTIÓN DE RESULTADOS</h2></div>
<br>
<table align="center">
    <tr>
        <td><input type="radio" name="radio" class="radio" id="informe" value="1">Informe de Visitas</td>
    </tr>
    <tr>
        <td><input type="radio" name="radio" class="radio" id="asignacion" value="2">Asignación Fiscalización</td>
    </tr>
    <tr>
        <td><input type="radio" name="radio" class="radio" id="estado" value="3">Estado Fiscalización</td>
    </tr>
    <tr>
        <td><input type="radio" name="radio" class="radio" id="reporte" value="4">Reporte Abogados</td>
    </tr>
</table>
<br>
<table align="center">
    <tr>
        <td><button name="generar" id="generarcontrol" class="btn btn-success">Generar</button></td>
    </tr>
</table>
<form id="control1">
<input type="hidden" name="opcion1" id="opcion1">
</form>
<script>
 $('.radio').click(function(){
       var radio = $(this).val();
     $('#opcion1').val(radio);       
    });
    $('#generarcontrol').click(function(){
       var opcion = $('#opcion1').val(); 
       if(opcion == 1){
        var url = "<?=  base_url('index.php/reportes/informevisitas')?>";
        $('#control1').attr('action',url);
        $('#control1').submit();
        
       } 
       if(opcion == 2){
        var url = "<?= base_url('index.php/reportes/asignacionfiscalizacion')?>" 
        $('#control1').attr('action',url);
        $('#control1').submit();
       }
       if(opcion == 3){
        var url = "<?= base_url('index.php/reportes/estadofiscalizacion')?>" 
        $('#control1').attr('action',url);
        $('#control1').submit();
       }
       if(opcion == 4){
        var url = "<?= base_url('index.php/reportes/reportesabogado')?>" 
        $('#control1').attr('action',url);
        $('#control1').submit();
       }
    });
</script>