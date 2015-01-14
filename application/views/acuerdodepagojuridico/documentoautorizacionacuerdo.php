
<div id='cargaarchivo'>
 <form id='cargar' action="<?= base_url('index.php/acuerdodepagojuridico/subirDocumento') ?>" method="post" enctype="multipart/form-data" >
     <input type="hidden" id="nit" name="nit" value="<?= $nit ?>"> 
     <input type="hidden" id="cod_coactivo" name="cod_coactivo" value="<?= $cod_coactivo ?>"> 
     <input type="hidden" id="acuerdo" name="acuerdo" value="<?= $acuerdo ?>">
    <table id='archivo'>
        <tr>
            <td style='width: 200px'><b>Nit</b></td>
            <td id='nit' style='width: 200px'><?= $nit ?></td>
        </tr>
        <tr>
            <td style='width: 200px'><b>Razón Social</b></td>
            <td style='width: 200px' id='razon'><?= $razon ?></td>
        </tr>
        <tr>
            <td><b>Documento</b></td>
            <td><input type="file" name="file" id="file" ></td>
        </tr>
        <tr>
            <td colspan="2" align="right"><input type="button" name="guardar" id="guardar" value="Guardar" class="btn btn-success"></td>
        </tr>
    </table>
     </form>
    <div align='center' style="display:none" id="error" class="alert alert-danger"></div>
</div>  
<div id="ajax_load" class="ajax_load" style="display: none">
        <div class="preload" id="preload" >
            <img  id="load" class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
        </div>
</div>
<script>       
    $('#guardar').click(function(){
        var input = document.getElementById('file');
        var file = input.files[0];
        if (file.size > parseInt(5000000)){
            $('#error').show();
            $('#error').html("<font color='red'><b>El Archivo Excede el Limite de Almacenamiento</b></font>");
        }else {
            if($('#file').val() != ""){
            var extensiones_permitidas = new Array(".pdf"); 
            var archivo = $('#file').val();
            var extension = (archivo.substring(archivo.lastIndexOf("."))).toLowerCase();
            var permitida = false;

            for (var i = 0; i < extensiones_permitidas.length; i++) {
                if (extensiones_permitidas[i] == extension) {
                permitida = true;
                break;
                }
            } 
                if (!permitida) {           
                    $('#file').val('');
                    $('#error').show();
                    $('#error').html("<font color='red'><b>Comprueba la extensi�n de los archivos a subir. \nS�lo se pueden subir archivos con extensiones: " + extensiones_permitidas.join()+"</b></font>");
                    $("#file").focus();
                }else{
                    $(".ajax_load").show("slow");                                               
                    $('#error').show();
                    $('#error').html("<font color='red'><b>Archivo Subido Correctamente</b></font>");
                    $('#cargar').submit();
                    $(".ajax_load").hide();      
                } 
     }else{
           if($('#alertadocumento').length == 0)
           $('#error').show();
           $('#error').html("<font color='red'><b>Por favor ingresar Documento</b></font>");           
           return false;
       }
        }

    });        

</script>    
    