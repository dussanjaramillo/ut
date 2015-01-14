<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<center><h1>Consulta de Certificados</h1></center>
<div style="width: 400px;border: 1px solid #FFF;margin: 0 auto;">
    
        <table width="100%">
            <tr>
                <td>No.</td>
                <td><input type="text" name="numero" id="numero" maxlength="16"></td>
            </tr>
            <tr>
                <td>Codigo</td>
                <td><input type="text" name="codigo" id="codigo" maxlength="16"></td>
            </tr>
            <tr>
                <td colspan="2"><div id="resultado"></div></td>
            </tr>
            <tr>
                <td colspan="2" align="center"><button id="consultar" class="btn btn-success">consultar</button></td>
            </tr>
        </table>
</div>

<script>

$('#consultar').click(function(){
    var numero=$('#numero').val();
    var codigo=$('#codigo').val();
    if(numero=="" || codigo==""){
        alert('Informacion Incompleta');
        return false;
    }
    jQuery(".preload, .load").show();
    var url = "<?php echo base_url('index.php/reporteador/buscar_certificado'); ?>";
    $.post(url,{numero:numero,codigo:codigo})
            .done(function(msg){
                var inform=""
                if(msg>0){
                    inform="<a href='<?php echo base_url('uploads/fiscalizaciones/certificados/') ?>"+'/'+codigo+numero+".pdf' TARGET='_blank'>Descardar Documento</a>";
                    jQuery(".preload, .load").hide();
                }else{
                    inform="Datos no Encontrados";
                    jQuery(".preload, .load").hide();
                }
                $('#resultado').html(inform)
            }).fail(function(msg){
                
            })
})
jQuery(".preload, .load").hide();
</script>