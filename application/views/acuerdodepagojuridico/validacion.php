<h3 align="center">VALIDACIÓN DE GARANTÍAS</h3>
<h5 align="center">No de Autorizaciones Enviadas &nbsp;<?= $contador ?> </h5>
<?php $i = 1; ?>
<?php foreach ($respuesta as $res) { ?>
    <h5 align="center">Solicitud No : <?= $i ?></h5>
    <h5 align="center">Respuesta : &nbsp;<?php
        if ($res['EXCEPCION'] == 'S') {
            echo "Aceptado";
        }else if ($res['EXCEPCION'] == 'N') {
           echo "Rechazado";
        }
      ?> </h5>
    <?php $i++; ?> 
<?php } ?>
<table align="center">
    <tr>
        <td>El valor de su deuda es:</td>
        <td align="right"><b><?php
                if (!empty($totalliquidacion)) {
                    echo "$ ".$totalliquidacion;
                } else {
                    echo "0";
                }
                ?></b></td>
    </tr>
      <tr>
        <td>El valor de sus garantias es:</td>
        <td align="right"><b><?php
                if (!empty($totalgarantias)) {
                    echo "$ ".$totalgarantias;
                } else {
                    echo "0";
                }
                ?></b></td>
    </tr>
    <tr>
        <td>El valor deuda en UVT:</td>
        <td align="right"><b><?php
                if (!empty($valorTotalUvt)) {
                    echo $valorTotalUvt;
                } else {
                    echo "0";
                }
                ?></b></td>
    </tr>
    <tr>
        <td style="width: 250px">El valor de sus garantías en UVT es</td>
        <td align="right"><b><?php
                    if (!empty($total)) {
                        echo $total;
                    } else {
                        echo $total=0;
                    }
                ?></b></td>
    </tr>
</table>
    <br><br>
    
    <table align="center" id="deudores">
    <tr>
        <td colspan="2">Desea enviar autorizar para crear el acuerdo de pago sin garantía</td>
    </tr>
    <tr>
        <td align="center">
            <select id="autorizaciongarantia">
                <option value="">-Seleccionar-</option>
                <option value="1">Si</option>
                <option value="2">No</option>
            </select>
        </td>
    </tr>
    <tr class='observaciones'>
        <td>
            Observaciones
        </td>
    </tr>
    <tr class='observaciones' id='observacion'>
        <td colspan='2'><textarea id='obser' style='width: 538px; height: 143px;'></textarea>
        </td>
    </tr>
    <tr>
        <td>
            <div align="center"><button type="button" id="validar" class="btn btn-success">Enviar</button></div>
        </td>
    </tr>
    </table>
    
    
     <table align="center" id="rectificacion" style="display:none">
        <tr>
            <td>¿EL OBLIGADO PERTENECE A LA LISTA DE DEUDORES MOROSOS?</td>
            <td>
                <select style="width: 130px" id="mora" name="mora">
                    <option value="">-Seleccionar-</option>
                    <option value="1">Si</option>
                    <option value="2">No</option>
                </select>
            </td>
        </tr>
         <tr>
            <td>¿EL OBLIGADO GARANTE (CODEUDOR) PERTENECE A LA LISTA DE DEUDORES MOROSOS?</td>
            <td>
                <select style="width: 130px" id="mora1" name="mora1">
                    <option value="">-Seleccionar-</option>
                    <option value="1">Si</option>
                    <option value="2">No</option>
                </select>
            </td>
        </tr>
    </table>
    
    <form id="f1" method="post" enctype="multipart/form-data" >
        <table id="subidaarchivo" align="center" style="display:none"></table>    
            <table align="center" id="boton_adjuntar" style="display:none">
                <tr>
                    <td><button type="button" id="atras" class="btn btn-warning">Atras</button></td>
                    <td><button type="button" id="siguiente" class="btn btn-success">Siguiente</button></td>
                </tr>
        </table>
        <input type="hidden" name="nit" value="<?= $nit ?>">
        <input type="hidden" name="cod_coactivo" value="<?= $cod_coactivo ?>">
        <input type="hidden" name="acuerdo" value="<?= $acuerdo ?>">
        <input type="hidden"   id="selectMora"   name="selectMora" value="">
    </form>
<div id="ajax_load" class="ajax_load" style="display: none">
        <div class="preload" id="preload" >
            <img  id="load" class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
        </div>
</div>    
<div align='center' style="display:none" id="error" class="alert alert-danger"></div>  

<script>
    $(document).ready(function() {   
        var totalgarantias  = <?= $total ?>;
        if ('<?= $valorTotalUvt ?>' == ''){
            var liquidacion = 0;
        }else {
            var liquidacion     = '<?= $valorTotalUvt ?>';
        }                
        
        if (totalgarantias != 0 && liquidacion != 0 && totalgarantias >= liquidacion){
            $('#rectificacion').show();
            $('#subidaarchivo').show();
            $('#boton_adjuntar').show();
            $('#deudores').hide();
            
        }               
    });
    
   $('#validar').click(function() {
    $(".ajax_load").show("slow");
    var autorizacion    = $('#autorizaciongarantia').val();
    var observacion     = $('#obser').val();
    var cod_coactivo    = <?= $cod_coactivo ?>;
    var nit             = "<?= $nit ?>";
    var acuerdo         = "<?= $acuerdo ?>";
    var cantSolicitudes = <?= $contador ?>;
    var url = "<?= base_url('index.php/acuerdodepagojuridico/guardaexcepcion') ?>";       
    if (autorizacion == ''){
        $("#error").show();
        $("#error").focus();
        $("#error").html('Por favor seleccionar la opcion correspondiente');
        $(".autorizaciongarantia").focus();
        $(".ajax_load").hide();
    }else if (observacion == ''){
        $("#error").show();
        $("#error").focus();
        $("#error").html('Por favor Ingrese alguna Observación');
        $(".obser").focus();
        $(".ajax_load").hide();
    }else {
        if (autorizacion == 1){                        
                $.post(url, {observacion: observacion, cod_coactivo: cod_coactivo, nit:nit, autorizacion:autorizacion, acuerdo:acuerdo, cantSolicitudes:cantSolicitudes}, function(data) {
                window.location = "<?= base_url().'index.php/bandejaunificada/procesos' ?>";
                $(".ajax_load").hide();
                if (cantSolicitudes >= 2){
                 alert ('No Es Posible Efectuar Mas Autorizaciones');   
                }
                });
        }else {            
            if(confirm('Esta Seguro de no Autorizar la Garantía?')){
                $(".ajax_load").show("slow");
                $.post(url, {observacion: observacion, cod_coactivo: cod_coactivo, nit:nit, autorizacion:autorizacion, acuerdo:acuerdo, cantSolicitudes:cantSolicitudes}, function(data) {
                window.location = "<?= base_url().'index.php/bandejaunificada/procesos' ?>";
                $(".ajax_load").hide();
                if (cantSolicitudes >= 2){
                 alert ('No Es Posible Efectuar Mas Autorizaciones');   
                }
                });
            }else{
                close();          
                $(".ajax_load").hide();
            } 
                
        }
        
    }        
}); 
    //$('#generaarchivo').remove();
   $('#mora').change(function() {
        if ($(this).val() == 2) {
            $('#generaarchivo').remove(); 
            var archivo = "<tr id='generaarchivo'><td>Archivo certificando que el cliente no esta reportado en el BDME</td><td><input type='file' id='archivo' name='archivo'></td></tr>";
            $('#subidaarchivo').append(archivo);
        } else {
            $('#generaarchivo').remove(); 
            var archivo = "<tr id='generaarchivo'><td>Archivo certificando que el cliente no esta reportado en el BDME</td><td><input type='file' id='archivo' name='archivo'></td></tr>";
            $('#subidaarchivo').append(archivo);
                
        }
    });
    
    $('#mora1').change(function() {
        if ($(this).val() == 2) {
            $('#generaarchivo').remove(); 
            var archivo = "<tr id='generaarchivo'><td>Archivo certificando que el cliente no esta reportado en el BDME</td><td><input type='file' id='archivo' name='archivo'></td></tr>";
            $('#subidaarchivo').append(archivo);
        } else {
            $('#generaarchivo').remove(); 
            var archivo = "<tr id='generaarchivo'><td>Archivo certificando que el cliente no esta reportado en el BDME</td><td><input type='file' id='archivo' name='archivo'></td></tr>";
            $('#subidaarchivo').append(archivo);
                
        }
    });
    
    $('#atras').click(function() {
        window.location = "<?= base_url().'index.php/bandejaunificada/procesos' ?>";
    });
    
    $('#siguiente').click(function() {
        var respuesta = $('#mora').val();
        var respuesta1 = $('#mora1').val();        
        var archivo = $('#archivo').val();
        $('#selectMora').val(respuesta);
        if (respuesta == ''){
            $('#error').show();
            $('#error').html('Por favor Seleccionar');
            $('#mora').focus();
        }else if (respuesta1 == ''){
            $('#error').show();
            $('#error').html('Por favor Seleccionar');
            $('#mora1').focus();
        }if (archivo == ''){
            $('#error').show();
            $('#error').html('Por favor Adjuntar Archivo');
        }else {
            $(".ajax_load").show(); 
            if (respuesta == 2 && respuesta1 == 2) {
                var url = "<?= base_url('index.php/acuerdodepagojuridico/acuerdo') ?>";
                $('#f1').attr('action', url);
                $('#f1').submit();
            } else if (respuesta == 1 || respuesta1 == 1) {
                $('#selectMora').val(respuesta);
                var url = "<?= base_url('index.php/acuerdodepagojuridico/mora') ?>";
                $('#f1').attr('action', url);
                $('#f1').submit();
            }
        }
    });
    
</script>