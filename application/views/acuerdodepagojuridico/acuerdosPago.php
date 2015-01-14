<?php
if (isset($message)) {
    echo $message;
}
?>   
<div id="resultado"></div>
<ul class="nav nav-tabs">
    <div align='center'><h3 >FACILIDAD DE PAGO JURIDICO</h3>
        <br><?= $nombreusuario ?>
        <br><?= @$cargousuario ?></div>
    <br>
</ul>
<div id='acuerdo_modal'>
    <div class="modal hide fade in" id="modal" style="display: none; width: 60%; margin-left: -30%;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">        
                    <h4 class="modal-title"><?php echo $title; ?></h4>
                    <div id="subtitle" name="subtitle">
                        <a href="<?php echo base_url(); ?>index.php/downloads/grant.pdf" target="_blank"></a>
                    </div>
                </div>
                <div class="modal-body conn">
                    Cargando datos...
                </div>
            </div> 
        </div> 
    </div> 
</div>
<table id="tablaq">            
    <thead>
    <th align='center'>Nit</th>
    <th align='center'>Razón Social</th>
    <th align='center'>Num. Proceso</th> 
    <th align='center'>Asignado</th>
    <th align='center'>Estado</th>
</thead>
<tbody>
    <?php foreach ($acuerdosautorizar as $resolucion) { ?>
        <tr>
            <td align='center'><?= $resolucion['NITEMPRESA'] ?></td> 
            <td align='center'><?= $resolucion['NOMBRE_EMPRESA'] ?></td> 
            <td align='center'><?= $resolucion['COD_PROCESO_COACTIVO'] ?></td>            
            <td align='center'><?= $resolucion['NOMBRES'] . ' ' . $resolucion['APELLIDOS'] ?></td> 
            <td align='center'><?= $resolucion['NOMBRE_GESTION'] ?></td>            
        </tr>   
    <?php } ?>
</tbody>
</table> 
<div id="ajax_load" class="ajax_load" style="display: none">
    <div class="preload" id="preload" >
        <img  id="load" class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
    </div>
</div>
<div id="dialog"> </div>
<form id="frmTmp" method="POST">
    <input type="hidden" id="acuerdo" name="acuerdo"> 
    <input type="hidden" id="estado" name="estado">
    <input type="hidden" id="cod_coactivo" name="cod_coactivo">        
    <input type="hidden" id="nit" name="nit">
    <input type="hidden" id="razon" name="razon">               
    <input type="hidden" id="concepto" name="concepto">    
    <input type="hidden" id="procedencia" name="procedencia">    
    <div id="resultado"> </div>
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
   
    window.onload = function()
    {
        $("#modal").modal(); 
        var nit              = <?= $nit ?>;
        var razon            = '<?= $razon ?>';    
        var cod_coactivo     = <?= $cod_coactivo ?>;  
        var estado           = <?= $gestion ?>; 
        var acuerdo          = <?= $acuerdo ?>;        
        var fecha            = '<?= $fecha ?>';      
        var concepto         = '<?= $concepto ?>';       
        var ajustado         = '<?= $ajustado ?>';
        var deuda            = <?= $deuda ?>;
        var capital          = <?= $capital ?>;
        var intereses        = <?= $intereses ?>;                    
        var procedencia      = <?= $procedencia ?>;  
        //alert (estado);
        $('#nit').val(nit);        
        $('#razon').val(razon);        
        $('#acuerdo').val(acuerdo); 
        $('#cod_coactivo').val(cod_coactivo); 
        $('#estado').val(estado); 
        $('#concepto').val(concepto); 
        $('#procedencia').val(procedencia); 
        $(".ajax_load").show("slow"); 
       
        if (estado == 201){
            //            $('#modal').remove();
            //            $('#modal').hide();
            $('#modal').modal('hide'); 
            var url     = '<?php echo base_url('index.php/acuerdodepagojuridico/crearAcuerdo') ?>';
            $("#resultado").load(
            url, {acuerdo:acuerdo,estado:estado,fecha:fecha,cod_coactivo:cod_coactivo,                        
                nit:nit,razon:razon,tipo:tipo,cod_estado:estado, procedencia:procedencia}, 
            function() {
                $(".ajax_load").hide("slow"); 
                $('#resultado').dialog({
                    autoOpen: true,
                    title:'Solicitud Facilidad de Pago', 
                    modal: true,
                    width: 600
                });
            });               
        }
        else if (estado == 1265){
            var url = "<?= base_url('index.php/acuerdodepagojuridico/crearResolucion') ?>";
            $.post(url,{acuerdo:acuerdo,estado:estado,fecha:fecha,cod_coactivo:cod_coactivo,                        
                nit:nit,razon:razon,tipo:tipo,ajustado:ajustado, procedencia:procedencia})
            .done(function(data){
                $('.conn').html(data);
                $(".ajax_load").hide("slow");
            }).fail(function (){
                //alert('Error en la consulta')
                $(".ajax_load").hide("slow");
            }) 
        }
        else if (estado == 1272){
              $.ajax({
                   type: "POST",
                   url: "<?= base_url('index.php/acuerdodepagojuridico/validaciongarantias') ?>",
                   data: {nit:nit,estado:estado,acuerdo:acuerdo,cod_coactivo:cod_coactivo, procedencia:procedencia},
                   success: 
                       function(data){
                       $('.conn').html(data);
                       $(".ajax_load").hide("slow");
                   }
               });
          }
        
        //       else if (estado == 1265 || estado == 1272 || estado == 1269){
        //           $.ajax({
        //                type: "POST",
        //                url: "<?= base_url('index.php/acuerdodepagojuridico/validaciongarantias') ?>",
        //                data: {nit:nit,estado:estado,acuerdo:acuerdo,cod_coactivo:cod_coactivo},
        //                success: 
        //                    function(data){
        //                    $('.conn').html(data);
        //                    $(".ajax_load").hide("slow");
        //                }
        //            });
        //       }
        else if (estado == 1268 || estado == 1273 || estado == 17){
            $("#frmTmp").attr("action", "<?= base_url('index.php/acuerdodepagojuridico/proyeccionafuturo') ?>");
            $('#frmTmp').submit(); 
        }else if (estado == 1259){
            var tipo    = 'bloqueo'
            var url     = '<?php echo base_url('index.php/tiempos/bloqueo') ?>';
            $("#modal").load(
            url, {                
                cod_coactivo: cod_coactivo, gestion: '512', fecha: fecha, mostrar: 'SI', si: 'guardar_verificacion', no: 'gestionarAcuerdo', parametros: 'acuerdo:' + acuerdo +';tipo:' + tipo+';nit:' + nit+';cod_coactivo:' + cod_coactivo, BD: ''}, function() {
                jQuery(".preload, .load").hide();
                //$('#modal').remove();              
            });
        }else if (estado == 1264 || estado == 1504){
            $.ajax({
                type: "POST",
                url: "<?= base_url('index.php/acuerdodepagojuridico/modificaciongarantias') ?>",
                data: {nit:nit,estado:estado,acuerdo:acuerdo,cod_coactivo:cod_coactivo,tipo:'verifica_garantias', procedencia:procedencia},
                success: 
                    function(data){
                    $('.conn').html(data);
                    $(".ajax_load").hide("slow");
                }
            });   
        }else if (estado == 16 || estado == 1496 || estado == 1400 || estado == 1399 || estado == 1406 || estado == 1407 || estado == 1414  || estado == 1503){           
            var url = "<?= base_url('index.php/acuerdodepagojuridico/crearResolucion') ?>";
            $.post(url,{acuerdo:acuerdo,estado:estado,fecha:fecha,cod_coactivo:cod_coactivo,                        
                nit:nit,razon:razon,tipo:tipo,ajustado:ajustado, procedencia:procedencia})
            .done(function(data){
                $('.conn').html(data);
                $(".ajax_load").hide("slow");
            }).fail(function (){
                //alert('Error en la consulta')
                $(".ajax_load").hide("slow");
            })  

        }else if (estado == 1274 || estado == 1263 || estado == 1267 || estado == 1296 || estado == 1301){           
            var url = "<?= base_url('index.php/acuerdodepagojuridico/validaCautelares') ?>";
            $.post(url,{acuerdo:acuerdo,estado:estado,fecha:fecha,cod_coactivo:cod_coactivo,
                nit:nit,razon:razon,tipo:tipo,ajustado:ajustado, procedencia:procedencia})
            .done(function(data){
                window.location = "<?= base_url().'index.php/bandejaunificada/procesos' ?>";
                $(".ajax_load").hide("slow");
            }).fail(function (){
                //alert('Error en la consulta')
                $(".ajax_load").hide("slow");
            })  

        }
        else if (estado == 1500){
            $('#modal').modal('hide');
            var tipo    = 'bloqueo'
            var ruta = "<?php echo base_url("index.php/tiempos/bloqueo"); ?>";
            $.post(ruta, {gestion: '736', fecha: fecha,cod_coactivo: cod_coactivo})
            .done(function(msg) {
                var texto = msg.texto;
                var comienza = msg.comienza;
                var vence = msg.vence;
                if (msg.bandera == "0") {
                    vence = "Recordatorio Pendiente";
                    comienza = "Recordatorio Pendiente";
                    texto = "Recordatorio Pendiente";
                }
                if ($.trim(msg.vencido) == "1") {
                    var url = "<?php echo base_url("index.php/acuerdodepagojuridico/bloqueo"); ?>";
                    $('#resultado').load(url, {acuerdo: acuerdo, cod_coactivo: cod_coactivo, nit: nit, estado: estado, fecha: fecha, tipo: tipo, texto: texto, comienza: comienza, vence: vence,razon:razon, ajustado:ajustado,gestion: '736'});
                } else {                    
                    var url = "<?php echo base_url('index.php/acuerdodepagojuridico/bloqueo_por_time'); ?>";
                    $('#resultado').load(url, {acuerdo: acuerdo, cod_coactivo: cod_coactivo, nit: nit, estado: estado, fecha: fecha, tipo: tipo, texto: texto, comienza: comienza, vence: vence,razon:razon, ajustado:ajustado,gestion: '736' });
                }
            }).fail(function(msg) {
                alert("ERROR");
            })
        }
//        else if (estado == 1504 || estado == 1505){
//            $('#modal').modal('hide');
//            var tipo    = 'bloqueo'
//            var ruta = "<?php echo base_url("index.php/tiempos/bloqueo"); ?>";
//            $.post(ruta, {gestion: '514', fecha: fecha,cod_coactivo: cod_coactivo})
//            .done(function(msg) {
//                var texto = msg.texto;
//                var comienza = msg.comienza;
//                var vence = msg.vence;
//                if (msg.bandera == "0") {
//                    vence = "Recordatorio Pendiente";
//                    comienza = "Recordatorio Pendiente";
//                    texto = "Recordatorio Pendiente";
//                }
//                if ($.trim(msg.vencido) == "1") {
//                    var url = "<?php echo base_url("index.php/acuerdodepagojuridico/bloqueo"); ?>";
//                    $('#resultado').load(url, {acuerdo: acuerdo, cod_coactivo: cod_coactivo, nit: nit, estado: estado, fecha: fecha, tipo: tipo, texto: texto, comienza: comienza, vence: vence,razon:razon, ajustado:ajustado,gestion: '514'});
//                } else {                    
//                    var url = "<?php echo base_url('index.php/acuerdodepagojuridico/bloqueo_por_time'); ?>";
//                    $('#resultado').load(url, {acuerdo: acuerdo, cod_coactivo: cod_coactivo, nit: nit, estado: estado, fecha: fecha, tipo: tipo, texto: texto, comienza: comienza, vence: vence,razon:razon, ajustado:ajustado,gestion: '514' });
//                }
//            }).fail(function(msg) {
//                alert("ERROR");
//            })
//        }
        else if (estado == 1394 || estado == 1395 || estado == 1396 || estado == 1397 || estado == 1399 || estado == 1401 || estado == 1402 || estado == 1403 || estado == 1404 || estado == 1408 || estado == 1409 || estado == 1497 || estado == 1499 || estado == 1498){           
            var url = "<?= base_url('index.php/acuerdodepagojuridico/editarNotificacion') ?>";
            $.post(url,{acuerdo:acuerdo,estado:estado,fecha:fecha,cod_coactivo:cod_coactivo,                      
                nit:nit,razon:razon,tipo:tipo,ajustado:ajustado, procedencia:procedencia})
            .done(function(data){
                $('.conn').html(data);
                $(".ajax_load").hide("slow");
            }).fail(function (){
                //alert('Error en la consulta')
                $(".ajax_load").hide("slow");
            })  
        }else if (estado == 1390 || estado == 1391 || estado == 1392 || estado == 1393){           
            var url = "<?= base_url('index.php/acuerdodepagojuridico/editarResolucion') ?>";
            $.post(url,{acuerdo:acuerdo,estado:estado,fecha:fecha,cod_coactivo:cod_coactivo,                      
                nit:nit,razon:razon,tipo:tipo,ajustado:ajustado, procedencia:procedencia})
            .done(function(data){
                $('.conn').html(data);
                $(".ajax_load").hide("slow");
            }).fail(function (){
                //alert('Error en la consulta')
                $(".ajax_load").hide("slow");
            })  
        }
        else if (estado == 26){
            $("#frmTmp").attr("action", "<?= base_url('index.php/acuerdodepagojuridico/acuerdomora') ?>");
            $('#frmTmp').submit();          
        }
        else if (estado == 1270 || estado == 27 || estado == 1289 || estado == 20 || estado == 1398 || estado == 1405 || estado == 1411 || estado == 1504 || estado == 1505){  
            $(".ajax_load").show("slow"); 
            var tipo = 'verifico_pago';
            $.ajax({
                type: "POST",
                url: "<?= base_url('index.php/acuerdodepagojuridico/verificar_pago') ?>",
                data: {nit:nit,estado:estado,acuerdo:acuerdo,cod_coactivo:cod_coactivo,tipo:tipo,razon:razon, procedencia:procedencia},
                success: 
                    function(data){
                    $(".ajax_load").hide("slow"); 
                    jQuery(".preload, .load").hide();
                    $('.conn').html(data);
                    $(".ajax_load").hide("slow");                    
                }
            });   
        }else if (estado == 1508){
            $("#frmTmp").attr("action", "<?= base_url('index.php/acuerdopagojuridicodoc/Firmar_AutoMotivado') ?>");
            $('#frmTmp').submit();   
        }else if (estado == 1511){
            $("#frmTmp").attr("action", "<?= base_url('index.php/acuerdopagojuridicodoc/Firmar_AutoOrdena') ?>");
            $('#frmTmp').submit();   
        }else if (estado == 1510){
            $("#frmTmp").attr("action", "<?= base_url('index.php/acuerdopagojuridicodoc/Firmar_AutoReanudacion') ?>");
            $('#frmTmp').submit();   
        }else if (estado == 1512){
            $("#frmTmp").attr("action", "<?= base_url('index.php/acuerdopagojuridicodoc/Firmar_AutoCierre') ?>");
            $('#frmTmp').submit();   
        }else if (estado == 1509){
            $("#frmTmp").attr("action", "<?= base_url('index.php/acuerdopagojuridicodoc/Firmar_ResolucionResuelve') ?>");
            $('#frmTmp').submit();   
        }else if (estado == 1275 || estado == 1278){
            $("#frmTmp").attr("action", "<?= base_url('index.php/acuerdopagojuridicodoc/Aprobar_AutoMotivado') ?>");
            $('#frmTmp').submit();   
        }else if (estado == 1276){
            $("#frmTmp").attr("action", "<?= base_url('index.php/acuerdopagojuridicodoc/Corregir_AutoMotivado') ?>");
            $('#frmTmp').submit();   
        }else if (estado == 1277){
            $("#frmTmp").attr("action", "<?= base_url('index.php/acuerdopagojuridicodoc/Subir_AutoMotivado') ?>");
            $('#frmTmp').submit();   
        }else if (estado == 1298){
            $("#frmTmp").attr("action", "<?= base_url('index.php/acuerdopagojuridicodoc/Crear_ResolucionResuelve') ?>");
            $('#frmTmp').submit();   
        }else if (estado == 1280){
            $("#frmTmp").attr("action", "<?= base_url('index.php/acuerdopagojuridicodoc/Aprobar_ResolucionResuelve') ?>");
            $('#frmTmp').submit();   
        }else if (estado == 1281){
            $("#frmTmp").attr("action", "<?= base_url('index.php/acuerdopagojuridicodoc/Corregir_ResolucionResuelve') ?>");
            $('#frmTmp').submit();   
        }else if (estado == 1283){
            $("#frmTmp").attr("action", "<?= base_url('index.php/acuerdopagojuridicodoc/Aprobar_ResolucionResuelve') ?>");
            $('#frmTmp').submit();   
        }else if (estado == 1282){
            $("#frmTmp").attr("action", "<?= base_url('index.php/acuerdopagojuridicodoc/Subir_ResolucionResuelve') ?>");
            $('#frmTmp').submit();   
        }else if (estado == 1305){
            $("#frmTmp").attr("action", "<?= base_url('index.php/acuerdopagojuridicodoc/Crear_AutoReanudacion') ?>");
            $('#frmTmp').submit();   
        }else if (estado == 1285 || estado == 1288){
            $("#frmTmp").attr("action", "<?= base_url('index.php/acuerdopagojuridicodoc/Aprobar_AutoReanudacion') ?>");
            $('#frmTmp').submit();   
        }else if (estado == 1286){
            $("#frmTmp").attr("action", "<?= base_url('index.php/acuerdopagojuridicodoc/Corregir_AutoReanudacion') ?>");
            $('#frmTmp').submit();   
        }else if (estado == 1287){
            $("#frmTmp").attr("action", "<?= base_url('index.php/acuerdopagojuridicodoc/Subir_AutoReanudacion') ?>");
            $('#frmTmp').submit();   
        }else if (estado == 1300){
            $("#frmTmp").attr("action", "<?= base_url('index.php/acuerdopagojuridicodoc/Crear_AutoOrdena') ?>");
            $('#frmTmp').submit();   
        }else if (estado == 1290){
            $("#frmTmp").attr("action", "<?= base_url('index.php/acuerdopagojuridicodoc/Aprobar_AutoOrdena') ?>");
            $('#frmTmp').submit();   
        }else if (estado == 1291){
            $("#frmTmp").attr("action", "<?= base_url('index.php/acuerdopagojuridicodoc/Corregir_AutoOrdena') ?>");
            $('#frmTmp').submit();   
        }else if (estado == 1293 || estado == 1292){
            $("#frmTmp").attr("action", "<?= base_url('index.php/acuerdopagojuridicodoc/Subir_AutoOrdena') ?>");
            $('#frmTmp').submit();   
        }else if (estado == 1304){
            $("#frmTmp").attr("action", "<?= base_url('index.php/acuerdopagojuridicodoc/Crear_AutoCierre') ?>");
            $('#frmTmp').submit();   
        }else if (estado == 1314 || estado == 1317){
            $("#frmTmp").attr("action", "<?= base_url('index.php/acuerdopagojuridicodoc/Aprobar_AutoCierre') ?>");
            $('#frmTmp').submit();   
        }else if (estado == 1315){
            $("#frmTmp").attr("action", "<?= base_url('index.php/acuerdopagojuridicodoc/Corregir_AutoCierre') ?>");
            $('#frmTmp').submit();   
        }else if (estado == 1316){
            $("#frmTmp").attr("action", "<?= base_url('index.php/acuerdopagojuridicodoc/Subir_AutoCierre') ?>");
            $('#frmTmp').submit();   
        }
//        else if (estado == 1303 || estado == 1299 || estado == 1263 || estado == 1267 || estado == 1296) {           
//            var  url = "<?= base_url('index.php/acuerdodepagojuridico/gestionar_documento_juridico') ?>"; 
//            $.post(url,{acuerdo:acuerdo,estado:estado,fecha:fecha,cod_coactivo:cod_coactivo,
//                nit:nit,razon:razon,tipo:tipo,cod_estado:estado})
//            .done(function(data){
//                $('.conn').html(data);
//                $(".ajax_load").hide("slow");
//                location.reload();
//            }).fail(function (){
//                //alert('Error en la consulta')
//                $(".ajax_load").hide("slow");
//            })            
//        }
        else if (estado == 1094){
            var  url = "<?= base_url('index.php/acuerdodepagojuridico/adjuntar') ?>";   
            //              $('#modal').remove();              
            //              $('#modal').hide();  
            $('#modal').modal('hide');
            $("#resultado").load(
            url, {acuerdo:acuerdo,estado:estado,fecha:fecha,cod_coactivo:cod_coactivo,                        
                nit:nit,razon:razon,tipo:tipo,cod_estado:estado, procedencia:procedencia}, 
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
            var url = "<?= base_url('index.php/acuerdodepagojuridico/verificar_acuerdo') ?>";
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
            }else if (estado == 1271 || estado == 1294){
                tipo = 'acto_admin';
            }else if (estado == 25 || estado == 1318){
                tipo = 'acto_fin';
            }else if (estado == 1279){
                tipo = 'presenta_recurso';
            }else if (estado == 1284){
                tipo = 'recurso_favorable';
            }else if (estado == 1302){
                tipo = 'pago_total';
            }else if (estado == 1288){
                tipo = 'fin_proceso';
            }else if (estado == 1299 || estado == 1303){
                   tipo = 'ejecutar_garantias';
            }else if (estado == 1493){
                tipo = 'incumplimiento';
            }else if (estado == 1501 || estado == 1502){
                tipo = 'nuevas_garantias';
            }else if (estado == 1506 || estado == 1507){
                tipo = 'medida';
            }
            $.post(url,{acuerdo:acuerdo,estado:estado,fecha:fecha,cod_coactivo:cod_coactivo,                        
                nit:nit,razon:razon,tipo:tipo,ajustado:ajustado,deuda:deuda,capital:capital,intereses:intereses, procedencia:procedencia})
            .done(function(data){
                $('.conn').html(data);
                $(".ajax_load").hide("slow");
            }).fail(function (){
                //alert('Error en la consulta')
                $(".ajax_load").hide("slow");
            })        
        }   
    }
</script>    
