<div id='dpila' align='center'><h4>GENERAR CERTIFICADOS:</h4></div>
<table align='center'>
    <tr>
        <td width='50px'><h6>NIT:</h6></td>
        <td width='200px'><?= $nit1 ?></td>
        <td width='100px'><h6>RAZÓN SOCIAL:</h6></td>
        <td width='300px'><?= $razonsocial1 ?></td>
    </tr>
</table>
<table id='fgenerar' align='center'>
    <tr>
        <td>Tipo de Certificado</td>
        <td colspan="3">
            <select name="tipo" id="tipo" style="width: 568px;">
                <option value="">-Seleccionar-</option>
                <option value="1">Certificacion Tributaria por Vigencia</option>
                <option value="2">Certificado de Estado FIC por Periodo</option>
                <option value="3">Certificado de Estado a la Fecha por Número de Obra</option>
                <option value="4">Certificado de Estado FIC a la Fecha por Número de Resolución o Liquidación</option>
                <option value="5">Certificado de Estado a la Fecha de Aportes</option>
                <option value="6">Certificado de Deuda</option>
            </select>
        </td>
    </tr>
</table>
<br>
<br>
<table align='center'>
    <tr>
        <td width='165px'><button id='visualizar' class="btn btn-success">Visualizar Certificado</button></td> 
        <td width='140px'><button id='enviarcertificado' class="btn btn-success">Enviar Certificado</button></td>  
        <td><button id='cancelar1' class="btn btn-success">Cancelar</button></td>  
    </tr>
</table>
<div id='enviar' class="enviar"></div>
<div id='enviarcertificado1' class="enviar"></div>
<style>
    .ui-widget-overlay{z-index: 10000;}
    .ui-dialog{
        z-index: 15000;
    }
</style>
<script>
    
    $('#generar').click(function(){
        var nit = "<?= $nit1 ?>";
        var tipo = $('#tipo').val();
        var periodoini = $('#periodoini').val();
        var periodofin = $('#periodofin').val();
        var url = "<?= base_url('index.php/pila/periodosgenerados')?>"
        $.post(url,{nit : nit, tipo : tipo, periodoini : periodoini, periodofin : periodofin},
        function(data){
//            console.log(data);
        });
        });
     
     $('#visualizar').click(function(){
         var certificado = $('#tipo').val();
         var nit = "<?= $nit1 ?>";
         if(certificado == 1){
        var url = "<?= base_url('index.php/pila/certificadotributario') ?>";
        }
        if(certificado == 2){
        var url = "<?= base_url('index.php/pila/certificadoperiodo') ?>";   
        }
        if(certificado == 3){
        var url = "<?= base_url('index.php/pila/certificadoobra') ?>";   
        }
        if(certificado == 4){
        var url = "<?= base_url('index.php/pila/certificadoresolucion') ?>";   
        }
        if(certificado == 5){
        var url = "<?= base_url('index.php/pila/certificadoaportes') ?>";   
        }
        if(certificado == 6){
        var url = "<?= base_url('index.php/pila/certificadodeuda') ?>";   
        }
         $('#enviarcertificado1').load(url,{certificado : certificado, nit : nit}, function(data){ 
         $('#enviarcertificado1').dialog({
               autoOpen : true,
               width : 1100,
               height : 1000,
               modal : true
           }); 
     });
     });
    
    $(document).ready(function() {
        $('#periodos').dataTable({
            "sDom": 'T<"clear">lfrtip',
            "oTableTools": {
//            "sSwfPath": "/swf/copy_csv_xls_pdf.swf"
                "sSwfPath": "<?= base_url('/swf/copy_csv_xls_pdf.swf') ?>",
                "sRowSelect": "multi"
//                "aButtons": ["select_all", "select_none"]
            }
        });
    });
$('#enviarcertificado').click(function (){
                    $('#enviar').dialog({
                        width : 400,
                        height : 300,
                        modal : true
                    });
                 var url = "<?=  base_url('index.php/pila/enviarcertificado')?>";
                 $('#enviar').load(url);
                });
                

  
 $('.periodosgenerar').datepicker( {
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'MM yy',
        onClose: function(dateText, inst) { 
            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).datepicker('setDate', new Date(year, month, 1));
        }
    });
    
</script>