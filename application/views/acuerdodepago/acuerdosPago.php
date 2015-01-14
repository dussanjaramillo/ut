<?php
if (isset($message)){
    echo $message;
   }
?>   
<div id="resultado"></div>
<ul class="nav nav-tabs">
  <div align='center'><h3 >FACILIDAD DE PAGO</h3>
    <br><?= $nombreusuario ?>
    <br><?= @$cargousuario ?></div>
<br>
</ul>
<div id='acuerdo_modal'>
<div class="modal hide fade in" id="modal" style="display: none; width: 60%; margin-left: -30%;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><?php echo $title; ?></h4>
        <div id="subtitle" name="subtitle">
            <a href="<?php echo base_url();?>index.php/downloads/grant.pdf" target="_blank"></a>
        </div>
      </div>
      <div class="modal-body conn">
        Cargando datos...
      </div>
      <div class="modal-footer">
          <a href="#" class="btn btn-default" data-dismiss="modal"><i class="fa fa-minus-circle"></i> Cerrar</a>
      </div>
    </div> 
  </div> 
</div> 
</div>
<table id="tablaq">            
    <thead>
        <th align='center'>Nit</th>
        <th align='center'>Razón Social</th>
        <th align='center'>Resolución</th>
        <th align='center'>Liquidación</th>        
        <th align='center'>Asignado</th>
        <th align='center'>Estado</th>
        <th align='center'>Documento</th>
    </thead>
    <tbody>
        <?php foreach($acuerdosautorizar->result_array as $resolucion){?>
        <tr>
           <td align='center'><?= $resolucion['NITEMPRESA'] ?></td> 
           <td align='center'><?= $resolucion['NOMBRE_EMPRESA'] ?></td> 
           <td align='center'><?= $resolucion['NRO_RESOLUCION'] ?></td>            
           <td align='center'><?= $resolucion['NRO_LIQUIDACION'] ?></td>
           <td align='center'><?= $resolucion['NOMBRES'].' '.$resolucion['APELLIDOS'] ?></td> 
           <td align='center'><?= $resolucion['NOMBRE_GESTION'] ?></td>            
           <td align="center">
               <?php
                //if ($resolucion['USUARIO_ASIGNADO'] == $ID_USER):
                    ?>
                 <input type="radio" data-toggle="modal" data-target="#modal" name="<?= $resolucion['NITEMPRESA'] ?>,<?= $resolucion['NOMBRE_EMPRESA'] ?>,<?= $resolucion['NRO_RESOLUCION'] ?>,<?= $resolucion['NRO_LIQUIDACION'] ?>,<?= $resolucion['COD_RESPUESTA'] ?>,<?= $resolucion['NRO_ACUERDOPAGO'] ?>,<?= $resolucion["FECHA_CREACION"] ?>,<?= $resolucion['COD_FISCALIZACION'] ?>,<?= $resolucion['COD_CONCEPTO_COBRO'] ?>,<?= $resolucion['COD_RESOLUCION'] ?>,<?= $resolucion['DETALLE_COBRO_COACTIVO'] ?>,<?= $resolucion['ACUERDO_AJUSTADO'] ?>" id='rdbNit' onclick='accion(this.name)'>   
               <?php
             //   endif;
               ?>               
           </td>
        </tr>   
        <?php } ?>
    </tbody>
</table> 
<div id="ajax_load" class="ajax_load" style="display: none">
        <div class="preload" id="preload" >
            <img  id="load" class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
        </div>
</div>
<div id="dialog"></div>
<form id="frmTmp" method="POST">
    <input type="hidden" id="acuerdo" name="acuerdo"> 
    <input type="hidden" id="estado" name="estado">
    <input type="hidden" id="fiscalizacion" name="fiscalizacion">    
    <input type="hidden" id="liquidacion" name="liquidacion">        
    <input type="hidden" id="nit" name="nit">
    <input type="hidden" id="razon" name="razon">               
    <input type="hidden" id="resolucion" name="resolucion">    
    <input type="hidden" id="concepto" name="concepto">    
</form>
<script>
     $('#tablaq').dataTable({
        "bJQueryUI": true,
        "bPaginate": true,
        "oLanguage": {
            "sProcessing": "Procesando...",
            "bServerSide": true,
            "sServerMethod": "POST", 
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix": "",
            "sSearch": "Buscar:",
            "sUrl": "",
            "sInfoThousands": ",",
            "sLoadingRecords": "Cargando...",
            "sPaginationType": "full_numbers",
            "iDisplayLength": 25,
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            }
        }
   });
   
   function accion (val){
       var datos = val.split(',');
       var nit              = datos[0];
       var razon            = datos[1];
       var resolucion       = datos[2];       
       var liquidacion      = datos[3];  
       var estado           = datos[4]; 
       var acuerdo          = datos[5];        
       var fecha            = datos[6];
       var fiscalizacion    = datos[7];
       var concepto         = datos[8];
       var idresolucion     = datos[9];
       var detalleCobro     = datos[10];
       var ajustado         = datos[11];
       $('#resolucion').val(resolucion);   
       $('#nit').val(nit);        
       $('#razon').val(razon);        
       $('#resolucion1').val(resolucion);  
       $('#acuerdo').val(acuerdo); 
       $('#fiscalizacion').val(fiscalizacion); 
       $('#liquidacion').val(liquidacion); 
       $('#estado').val(estado); 
       $('#concepto').val(concepto); 
       $(".ajax_load").show("slow"); 
       
       if (estado == 1265 || estado == 1272 || estado == 1269){
           $.ajax({
                type: "POST",
                url: "<?= base_url('index.php/acuerdodepago/validaciongarantias')?>",
                data: {nit:nit,resolucion:resolucion,estado:estado,acuerdo:acuerdo,fiscalizacion:fiscalizacion,liquidacion:liquidacion},
                success: 
                    function(data){
                    $('.conn').html(data);
                    $(".ajax_load").hide("slow");
                }
            });
       }else if (estado == 1268 || estado == 1273 || estado == 17){
            $("#frmTmp").attr("action", "<?= base_url('index.php/acuerdodepago/proyeccionafuturo')?>");
            $('#frmTmp').submit(); 
       }else if (estado == 1259){
           var tipo    = 'bloqueo'
            var url     = '<?php echo base_url('index.php/tiempos/bloqueo') ?>';
            $("#modal").load(
                url, {                
                codfiscalizacion: fiscalizacion, gestion: '512', fecha: fecha, mostrar: 'SI', si: 'guardar_verificacion', no: 'gestionarAcuerdo', parametros: 'acuerdo:' + acuerdo +';tipo:' + tipo+';nit:' + nit+';fiscalizacion:' + fiscalizacion, BD: ''}, function() {
                jQuery(".preload, .load").hide();
                //$('#modal').remove();              
            });
       }else if (estado == 1264){
            $.ajax({
                type: "POST",
                url: "<?= base_url('index.php/acuerdodepago/modificaciongarantias')?>",
                data: {nit:nit,resolucion:resolucion,estado:estado,acuerdo:acuerdo,fiscalizacion:fiscalizacion,liquidacion:liquidacion,tipo:'verifica_garantias'},
                success: 
                    function(data){
                    $('.conn').html(data);
                    $(".ajax_load").hide("slow");
                }
            });   
       }else if (estado == 1293){
            $.ajax({
                type: "POST",
                url: "<?= base_url('index.php/acuerdodepagojuridico/Subir_ResolucionFacilidadPago')?>",
                data: {nit:nit,resolucion:resolucion,estado:estado,acuerdo:acuerdo,fiscalizacion:fiscalizacion,liquidacion:liquidacion,tipo:'verifica_garantias'},
                success: 
                    function(data){
                    $('.conn').html(data);
                    $(".ajax_load").hide("slow");
                }
            });   
       }
//       else if (estado == 16){
//            $("#frmTmp").attr("action", "<?= base_url('index.php/acuerdodepago/proyectoacuerdodepago')?>");
//            $('#frmTmp').submit();   
//       }
       else if (estado == 16 || estado == 1400 || estado == 1399 || estado == 1406 || estado == 1407 || estado == 1414  || estado == 1274){           
             var url = "<?= base_url('index.php/acuerdodepago/crearResolucion')?>";
             $.post(url,{acuerdo:acuerdo,estado:estado,fecha:fecha,fiscalizacion:fiscalizacion,liquidacion:liquidacion,                        
                        nit:nit,razon:razon,resolucion:resolucion,tipo:tipo,ajustado:ajustado})
                    .done(function(data){
                        $('.conn').html(data);
                        $(".ajax_load").hide("slow");
                    }).fail(function (){
                        //alert('Error en la consulta')
                        $(".ajax_load").hide("slow");
                    })  

       }
        else if (estado == 1390 || estado == 1391 || estado == 1392){           
             var url = "<?= base_url('index.php/acuerdodepago/editarResolucion')?>";
             $.post(url,{acuerdo:acuerdo,estado:estado,fecha:fecha,fiscalizacion:fiscalizacion,liquidacion:liquidacion,                        
                        nit:nit,razon:razon,resolucion:resolucion,tipo:tipo,ajustado:ajustado})
                    .done(function(data){
                        $('.conn').html(data);
                        $(".ajax_load").hide("slow");
                    }).fail(function (){
                        //alert('Error en la consulta')
                        $(".ajax_load").hide("slow");
                    })  

       }
       else if (estado == 1394 || estado == 1395 || estado == 1396 || estado == 1397 || estado == 1399 || estado == 1401 || estado == 1402 || estado == 1403 || estado == 1404 || estado == 1408 || estado == 1409){           
        var url = "<?= base_url('index.php/acuerdodepago/editarNotificacion')?>";
        $.post(url,{acuerdo:acuerdo,estado:estado,fecha:fecha,fiscalizacion:fiscalizacion,liquidacion:liquidacion,                        
                   nit:nit,razon:razon,resolucion:resolucion,tipo:tipo,ajustado:ajustado})
               .done(function(data){
                   $('.conn').html(data);
                   $(".ajax_load").hide("slow");
               }).fail(function (){
                   //alert('Error en la consulta')
                   $(".ajax_load").hide("slow");
               })  
       }
       else if (estado == 26){
            $("#frmTmp").attr("action", "<?= base_url('index.php/acuerdodepago/acuerdomora')?>");
            $('#frmTmp').submit();          
       }
       else if (estado == 1270 || estado == 27 || estado == 1289 || estado == 20 || estado == 1398 || estado == 1405 || estado == 1411){  
           $(".ajax_load").show("slow"); 
           var tipo = 'verifico_pago';
            $.ajax({
                type: "POST",
                url: "<?= base_url('index.php/acuerdodepago/verificar_pago')?>",
                data: {nit:nit,resolucion:resolucion,estado:estado,acuerdo:acuerdo,fiscalizacion:fiscalizacion,tipo:tipo,liquidacion:liquidacion,razon:razon},
                success: 
                    function(data){
                    $(".ajax_load").hide("slow"); 
                    jQuery(".preload, .load").hide();
                    $('.conn').html(data);
                    $(".ajax_load").hide("slow");                    
                }
            });   
       }else if (estado == 1275 || estado == 1278){
            $("#frmTmp").attr("action", "<?= base_url('index.php/acuerdopagodoc/Firmar_AutoMotivado')?>");
            $('#frmTmp').submit();   
       }else if (estado == 1276){
            $("#frmTmp").attr("action", "<?= base_url('index.php/acuerdopagodoc/Corregir_AutoMotivado')?>");
            $('#frmTmp').submit();   
       }else if (estado == 1277){
            $("#frmTmp").attr("action", "<?= base_url('index.php/acuerdopagodoc/Subir_AutoMotivado')?>");
            $('#frmTmp').submit();   
       }else if (estado == 1298){
            $("#frmTmp").attr("action", "<?= base_url('index.php/acuerdopagodoc/Crear_ResolucionResuelve')?>");
            $('#frmTmp').submit();   
       }else if (estado == 1280){
            $("#frmTmp").attr("action", "<?= base_url('index.php/acuerdopagodoc/Firmar_ResolucionResuelve')?>");
            $('#frmTmp').submit();   
       }else if (estado == 1281){
            $("#frmTmp").attr("action", "<?= base_url('index.php/acuerdopagodoc/Corregir_ResolucionResuelve')?>");
            $('#frmTmp').submit();   
       }else if (estado == 1283){
            $("#frmTmp").attr("action", "<?= base_url('index.php/acuerdopagodoc/Firmar_ResolucionResuelve')?>");
            $('#frmTmp').submit();   
       }else if (estado == 1282){
            $("#frmTmp").attr("action", "<?= base_url('index.php/acuerdopagodoc/Subir_ResolucionResuelve')?>");
            $('#frmTmp').submit();   
       }else if (estado == 1305){
            $("#frmTmp").attr("action", "<?= base_url('index.php/acuerdopagodoc/Crear_AutoReanudacion')?>");
            $('#frmTmp').submit();   
       }else if (estado == 1285 || estado == 1288){
            $("#frmTmp").attr("action", "<?= base_url('index.php/acuerdopagodoc/Firmar_AutoReanudacion')?>");
            $('#frmTmp').submit();   
       }else if (estado == 1286){
            $("#frmTmp").attr("action", "<?= base_url('index.php/acuerdopagodoc/Corregir_AutoReanudacion')?>");
            $('#frmTmp').submit();   
       }else if (estado == 1287){
            $("#frmTmp").attr("action", "<?= base_url('index.php/acuerdopagodoc/Subir_AutoReanudacion')?>");
            $('#frmTmp').submit();   
       }else if (estado == 1300){
            $("#frmTmp").attr("action", "<?= base_url('index.php/acuerdopagodoc/Crear_AutoOrdena')?>");
            $('#frmTmp').submit();   
       }else if (estado == 1290){
            $("#frmTmp").attr("action", "<?= base_url('index.php/acuerdopagodoc/Firmar_AutoOrdena')?>");
            $('#frmTmp').submit();   
       }else if (estado == 1291){
            $("#frmTmp").attr("action", "<?= base_url('index.php/acuerdopagodoc/Corregir_AutoOrdena')?>");
            $('#frmTmp').submit();   
       }else if (estado == 1293 || estado == 1292){
            $("#frmTmp").attr("action", "<?= base_url('index.php/acuerdopagodoc/Subir_AutoOrdena')?>");
            $('#frmTmp').submit();   
       }else if (estado == 1304){
            $("#frmTmp").attr("action", "<?= base_url('index.php/acuerdopagodoc/Crear_AutoCierre')?>");
            $('#frmTmp').submit();   
       }else if (estado == 1314 || estado == 1317){
            $("#frmTmp").attr("action", "<?= base_url('index.php/acuerdopagodoc/Firmar_AutoCierre')?>");
            $('#frmTmp').submit();   
       }else if (estado == 1315){
            $("#frmTmp").attr("action", "<?= base_url('index.php/acuerdopagodoc/Corregir_AutoCierre')?>");
            $('#frmTmp').submit();   
       }else if (estado == 1316){
            $("#frmTmp").attr("action", "<?= base_url('index.php/acuerdopagodoc/Subir_AutoCierre')?>");
            $('#frmTmp').submit();   
       }else if (estado == 1303 || estado == 1299 || estado == 1263 || estado == 1267 || estado == 1296 ) {           
           var  url = "<?= base_url('index.php/acuerdodepago/gestionar_documento_juridico')?>";   
              $('#modal').remove();              
              $("#resultado").load(
                url, {acuerdo:acuerdo,estado:estado,fecha:fecha,cod_fis:fiscalizacion,liquidacion:liquidacion,                        
                nit:nit,razon:razon,id:idresolucion,tipo:tipo,cod_estado:estado}, 
                function() {
                    $('#resultado').dialog({
                        autoOpen: true,
                        modal: true,
                        width: 700
                    });
                });            
       }else if (estado == 1094){
           var  url = "<?= base_url('index.php/acuerdodepago/adjuntar')?>";   
              $('#modal').remove();              
              $("#resultado").load(
                url, {acuerdo:acuerdo,estado:estado,fecha:fecha,cod_fis:fiscalizacion,liquidacion:liquidacion,                        
                nit:nit,razon:razon,id:idresolucion,tipo:tipo,cod_estado:estado,documentos:detalleCobro}, 
                function() {
                    $(".ajax_load").hide("slow"); 
                    $('#resultado').dialog({
                        autoOpen: true,
                        modal: true,
                        width: 500
                    });
                });   
       }else { 
           $(".ajax_load").show("slow"); 
           var url = "<?= base_url('index.php/acuerdodepago/verificar_acuerdo')?>";
           if (estado == 1173 || estado == 1295){
                   tipo = 'condiciones';
               }else if (estado == 1297){
                   tipo = 'solicitud_ajuste';
               }else if (estado == 1261){
                   tipo = 'pago_minimo';
               }else if (estado == 1262){
                   tipo = 'garantias'
               }else if (estado == 1266){
                   tipo = 'autoriza_garantias'
               }
               //else if (estado == 20){
               //    tipo = 'deudor_acuerdo';
               //}
               else if (estado == 1271 || estado == 1301 || estado == 1294){
                   tipo = 'acto_admin';
               }else if (estado == 25 || estado == 1318){
                   tipo = 'acto_fin';
               }else if (estado == 1279){
                   tipo = 'presenta_recurso';
               }
//               else if (estado == 1299 || estado == 1303){
//                   tipo = 'ejecutar_garantias';
//               }
               else if (estado == 1284){
                   tipo = 'recurso_favorable';
               }else if (estado == 1302){
                   tipo = 'pago_total';
               }else if (estado == 1288){
                   tipo = 'fin_proceso';
               }else if (estado == 1112){
                   tipo = 'envio_coactivo';
               }
               $.post(url,{acuerdo:acuerdo,estado:estado,fecha:fecha,fiscalizacion:fiscalizacion,liquidacion:liquidacion,                        
                        nit:nit,razon:razon,resolucion:resolucion,tipo:tipo,ajustado:ajustado})
                    .done(function(data){
                        $('.conn').html(data);
                        $(".ajax_load").hide("slow");
                    }).fail(function (){
                        //alert('Error en la consulta')
                        $(".ajax_load").hide("slow");
                    })        
       }                     
    }
   
//   $(function() {
//        $('#modal').on('hidden', function() {
//            $('.conn').val("");
//            $('#modal').modal('hide').removeData();
//        });
//    });
</script>    
    