<?php

if (isset($message)){
    echo $message;
   }

if ($perfil == 41){
    $perfiles = 'Secretario';
    $icono = "fa-user";
}else if ($perfil == 42){
    $perfiles = 'Coordinador';
    $icono = "fa-users";
}else if ($perfil == 43){
    $perfiles = 'Abogado';
    $icono = "fa-male";
}   
   
   
?>
<br>
    <h1>Mandamiento de Pago</h1>
<br><br>
<div style="display:none" id="alert" class="alert alert-danger">
    
</div>
<br><br>
<div class="alert alert-info">
<h4 align="left">
        <i class="fa <?php echo @$icono; ?> "> <?php echo @$perfiles ?></i> 
</h4>
</div>    
<div class="modal hide fade in" id="modal" style="display: none; width: 60%; margin-left: -30%;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Visor de Mandamientos de Pago</h4>
        <div id="subtitle" name="subtitle">
            <a href="<?php echo base_url();?>index.php/downloads/grant.pdf" target="_blank"></a>
        </div>
      </div>
      <div class="modal-body conn">
        Cargando datos...
      </div>
      <div class="modal-footer">
          <a href="#" class="btn btn-success" data-dismiss="modal">Cerrar</a>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<table id="tablaq">
    <thead>      
       <tr>
        <th>Identificaci&oacute;n</th>
        <th>Ejecutado</th>
        <th>Num. Proceso</th>        
        <th>Editar</th>
        <th>Ver</th>
        <th>Imprimir</th>
        <th>Subir doc.</th>  
        <th>Ver Estado</th>
        <th>Documentos</th>
        <th>Seleccionar</th>        
      </tr>
    </thead>
    <tbody>
            <?php   
            if (@$registros != ''){
            foreach (@$registros->result_array as $value) {                
                ?>
        <tr>
            <td align="center"><?= $value['IDENTIFICACION'] ?></td>
            <td align="center"><?= $value['NOMBRE'] ?></td>
            <td align="center"><?= $value['COD_PROCESO'] ?></td>            
            <td id="editar" class="center" align="center">
                <i clave="<?= $value['COD_MANDAMIENTOPAGO'] ?>" gestion="<?= $value['ESTADO'] ?>" nit="<?= $value['IDENTIFICACION'] ?>" razon="<?= $value['NOMBRE'] ?>" fiscalizacion="<?= $value['COD_PROCESO'] ?>" class="edit fa fa-edit" style="cursor : pointer"></i>
            </td>
            <td id="view" class="center" align="center">
                <i title="Ver" data-toggle="modal" data-target="#modal" data-keyboard="false" data-backdrop="static"  class="view fa fa-eye" clave="<?= $value['COD_PROCESO'] ?>" plantilla="<?= $value['PLANTILLA'] ?>" gestion="<?= $value['ESTADO'] ?>" style="cursor : pointer"></i>
            </td>
            <td id="imprimir" class="center" align="center">
                <i class="print fa fa-print" title="Imprimir" data-toggle="modal" data-target="#pdf" data-keyboard="false" data-backdrop="static" gestion="<?= $value['ESTADO'] ?>" clave="<?= $value['COD_PROCESO'] ?>" plantilla="<?= $value['PLANTILLA'] ?>" style="cursor : pointer"></i>
            </td>
            <td id="subir" class="center" align="center">
                <i clave="<?= $value['COD_MANDAMIENTOPAGO'] ?>" fiscalizacion="<?= $value['COD_PROCESO'] ?>" gestion="<?= $value['ESTADO'] ?>" nit="<?= $value['IDENTIFICACION'] ?>" aprobado="<?= $value['APROBADO'] ?>" class="up fa fa-arrow-up" style="cursor : pointer"></i>
            </td>
            <td class="item" align="center">
                <button class="viewGestion" title="Ver Gestion" data-toggle="modal" data-target="#viewGestion" clave="<?= $value['COD_MANDAMIENTOPAGO'] ?>" estado_gestion="<?= $value['ESTADO'] ?>" ><i class="fa fa-camera-retro fa-lg"></i></button>
            </td>
            <td align="center">
                <button class="viewDocumento" title="Ver Documentos" data-toggle="modal" data-target="#viewDocumento" clave="<?= $value['COD_MANDAMIENTOPAGO'] ?>" fiscalizacion="<?= $value['COD_FISCALIZACION'] ?>" gestion="<?= $value['ESTADO'] ?>" nit="<?= $value['NIT_EMPRESA'] ?>" ><i class="fa fa-folder-open"></i></button>
            </td>
            <td align="center"><input type="radio" id="rdbNit" name='rdbNit' gestion="<?= $value['ESTADO'] ?>" clave="<?= $value['COD_MANDAMIENTOPAGO'] ?>" nit="<?= $value['IDENTIFICACION'] ?>" class="opcion" razon="<?= $value['NOMBRE'] ?>" fiscalizacion="<?= $value['COD_PROCESO'] ?>" direccion="<?= @$value['DIRECCION']?>" cautelar="<?= $value['TIPO_EXCEPCION']?>" recurso="<?= $value['TIPO_RECURSO']?>" fecha="<?= $value['FECHA_MANDAMIENTO']?>"></td>
        </tr>
                <?php                
            }            
          }
            ?>
    </tbody>     
</table>
<br>

<div align="center" id="error"></div>
<br>
<div id="ajax_load" class="ajax_load" style="display: none">
        <div class="preload" id="preload" >
            <img  id="load" class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
        </div>
</div>

<form id="frmTmp" method="POST">
    <input type="hidden" id="nit" name="nit">
    <input type="hidden" id="cod_coactivo" name="cod_coactivo">
    <input type="hidden" id="nombre" name="nombre">
    <input type="hidden" id="clave" name="clave">
    <input type="hidden" id="gestion" name="gestion">
    <input type="hidden" id="perfil" name="perfil" value="<?php echo $perfil ?>">
    <input type="hidden" id="plantilla" name="plantilla">
    <input type="hidden" id="mandamiento" name="mandamiento">
    <input type="hidden" id="estado_gestion" name="estado_gestion">
    <input type="hidden" id="persuasivo" name="persuasivo">
    <input type="hidden" id="direccion" name="direccion">
    <input type="hidden" id="cautelar" name="cautelar" value="<?php echo $excepcion ?>">
    <input type="hidden" id="aprobado" name="aprobado">
    <input type="hidden" id="recurso" name="recurso">
    <input type="hidden" id="fecha" name="fecha">
    <div id="bloqueos"></div>
</form>

<script type="text/javascript" language="javascript" charset="utf-8"> 
 window.onload = function ()
{
        $('#gestion').val(<?= $cod_respuesta ?>);
        $('#nit').val(<?= $nit ?>);
        $('#cod_coactivo').val('<?= $cod_coactivo ?>');
        $('#nombre').val('<?= $razon ?>');
        $('#clave').val('<?= $mandamiento ?>');        
        $('#direccion').val('<?= $direccion ?>');        
        $('#recurso').val(<?= $recurso ?>);
        $('#fecha').val('<?= $fecha ?>');
        $('#perfil').val('<?= $perfil ?>');
        var gestion     = '<?= $cod_respuesta ?>';
        var direccion   = '<?= $direccion ?>';
        var nit         = '<?= $nit ?>';
        var cod_coactivo = '<?= $cod_coactivo ?>';
        var name        = '<?= $razon ?>';
        var mandamiento = '<?= $mandamiento ?>';
        var cautelar    = $('#cautelar').val();;
        var recurso     = '<?= $recurso ?>';
        var perfil      = '<?php echo $perfil ?>';
        var fecha       = '<?= $fecha ?>';
        //alert (cod_coactivo+'-'+gestion);
            if (gestion == '204'){
                $(".ajax_load").show("slow");
                $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/add')?>");
                $('#frmTmp').submit();
            }else if (gestion == '205' || gestion == '0' || gestion == '207' || gestion == '206' || gestion == '209'){
            $(".ajax_load").show("slow");
            $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/edit')?>");
            $('#frmTmp').submit();
            }else if (gestion == '208'){
                if(direccion == '') {
                    $(".ajax_load").show("slow");
                    $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/aviso')?>");
                    $('#frmTmp').submit();
                }else{
                    $(".ajax_load").show("slow");
                    $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/citacion')?>");
                    $('#frmTmp').submit();
                }                    
            }else if (gestion == '220' || gestion == '221' || gestion == '744' || gestion == '952'){
          $(".ajax_load").show("slow");
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/editCit')?>");
          $('#frmTmp').submit();
       }else if (gestion == '228' || gestion == '741' || gestion == '742'){
          $(".ajax_load").show("slow");
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/editAvi')?>");
          $('#frmTmp').submit();
       }else if (gestion == '222' || gestion == '223'){
           $(".ajax_load").show("slow");
            var url1 = "<?= base_url('index.php/mandamientopago/correo')?>";
            var url2 = "<?= base_url('index.php/mandamientopago/acta')?>";
               $.ajax({
                     type: "POST",
                     url: "gestion",
                     data: {estado_gestion: gestion, nit: nit, cod_coactivo: cod_coactivo, name: name, clave: mandamiento,
                     perfil: perfil, url1: url1, url2: url2},
                     success: 
                         function(data){
                         $('.conn').html(data);
                         $(".ajax_load").hide("slow");
                     }
                 });                              
        }
//        else if (gestion == 1459 || gestion == 1458){
//            $(".ajax_load").show("slow");
//            if (gestion == 1459){
//                var valor = 'correo';
//            }else {
//                var valor = 'acta';
//            }            
//            var url     = '<?php echo base_url('index.php/tiempos/bloqueo') ?>';
//            $("#bloqueos").load(url,
//                {codcoactivo: cod_coactivo, gestion: '112', fecha: fecha, mostrar: 'SI', si: valor, no: 'nits', parametros: 'cod_coactivo:'+cod_coactivo, BD: ''}, function() {   
//                    
//                });
//        }
        else if (gestion == '210' || gestion == '211' || gestion == '951' || gestion == '950'){
          $(".ajax_load").show("slow")
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/editActa')?>");
          $('#frmTmp').submit();
       }else if (gestion == '215'){
          $(".ajax_load").show("slow")
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/pagoEdit')?>");
          $('#frmTmp').submit();
       }else if (gestion == '216' || gestion == '217' || gestion == '958' || gestion == '959'){
          $(".ajax_load").show("slow");
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/editCor')?>");
          $('#frmTmp').submit();
       }else if (gestion == '218'){
          $(".ajax_load").show("slow");
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/pagina')?>");
          $('#frmTmp').submit();
       }else if (gestion == '213' || gestion == '212' || gestion == '227' || gestion == '229' || gestion == '219'){
          $(".ajax_load").show("slow");
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/pago')?>");
          $('#frmTmp').submit();
       }else if (gestion == '214'){                    
          $('#alert').show();
          $('#alert').html("<b>Pago Efectuado en Proceso N° "+cod_coactivo+"</b>");
       }else if (gestion == '224' || gestion == '225' || gestion == '226'){
          $(".ajax_load").show("slow");
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/editPag')?>");
          $('#frmTmp').submit();
       }else if (gestion == '235' || gestion == '256'){
          $(".ajax_load").show("slow");
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/addRes')?>");
          $('#frmTmp').submit();
       }else if (gestion == '236' || gestion == '237'){
          $(".ajax_load").show("slow");
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/addExc')?>");
          $('#frmTmp').submit();
       }else if (gestion == '1021'){
          $(".ajax_load").show("slow");
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/citacionex')?>");
          $('#frmTmp').submit();
       }else if (gestion == '238' || gestion == '239' || gestion == '1032' || gestion == '1033'){
          $(".ajax_load").show("slow");
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/editCitex')?>");
          $('#frmTmp').submit();
       }else if (gestion == '252' || gestion == '253' || gestion == '254' || gestion == '1058' || gestion == '1337' || gestion == '1338'){
          $(".ajax_load").show("slow"); 
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/editLevanta')?>");
          $('#frmTmp').submit();
       }else if (gestion == '255'){
          $('#alert').show();
          $('#alert').html("<b>Levantamiento de Medidas Cautelares Aprobado y Firmado, Proceso N° "+cod_coactivo+"</b>");
          //$("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/finProceso')?>");
          //TERMINACION DE PROCESO
       }else if (gestion == '659' || gestion == '660' || gestion == '1038' || gestion == '1039'){
          $(".ajax_load").show("slow");
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/editActaExc')?>");
          $('#frmTmp').submit();
       }else if (gestion == '240' || gestion == '241'){  
           var url1 = "<?= base_url('index.php/mandamientopago/correoExc')?>";
           var url2 = "<?= base_url('index.php/mandamientopago/actaExc')?>";
           $(".ajax_load").show("slow");
              $.ajax({
                    type: "POST",
                    url: "gestion",
                    data: {estado_gestion: gestion, nit: nit, cod_coactivo: cod_coactivo, name: name, clave: mandamiento,
                    perfil: perfil, url1: url1, url2: url2},
                    success: function(data){
                        $('.conn').html(data);
                        $(".ajax_load").hide("slow");
                    }
                });                              
        }else if (gestion == '242' || gestion == '243' || gestion == '1070' || gestion == '1071'){
           $(".ajax_load").show("slow");
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/editCorreoex')?>");
          $('#frmTmp').submit();
       }else if (gestion == '244'){
           $(".ajax_load").show("slow");
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/paginaExc')?>");
          $('#frmTmp').submit();
       }else if (gestion == '230' || gestion == '231' || gestion == '1080' || gestion == '1081'){
           $(".ajax_load").show("slow");
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/edPaginaExc')?>");
          $('#frmTmp').submit();
       }else if (cautelar != 1 && (gestion == '661' || gestion == '662' || gestion == '232' || gestion == '233' || gestion == '245') ){
          $(".ajax_load").show("slow");
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/medidas')?>");
          $('#frmTmp').submit();
       }else if (cautelar == 1 && (gestion == '661' || gestion == '662' || gestion == '232' || gestion == '233' || gestion == '245') ){
          $(".ajax_load").show("slow");
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/recurso')?>");
          $('#frmTmp').submit();
       }else if (gestion == '258' || gestion == '259'){
          $(".ajax_load").show("slow");
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/addRec')?>");
          $('#frmTmp').submit();
       }else if (gestion == '257'){
          $(".ajax_load").show("slow");
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/recursoEdit')?>");
          $('#frmTmp').submit();
       }else if (gestion == '260' || gestion == '261' || gestion == '1086' || gestion == '1087'){
          $(".ajax_load").show("slow");
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/editCitrec')?>");
          $('#frmTmp').submit();
       }else if (gestion == '262' || gestion == '263'){  
           var url1 = "<?= base_url('index.php/mandamientopago/addEdicto')?>";
           var url2 = "<?= base_url('index.php/mandamientopago/actaRec')?>";
           $(".ajax_load").show("slow");
              $.ajax({
                    type: "POST",
                    url: "gestion",
                    data: {estado_gestion: gestion, nit: nit, cod_coactivo: cod_coactivo, name: name, clave: mandamiento,
                    perfil: perfil, url1: url1, url2: url2},
                    success: function(data){
                        $('.conn').html(data);
                        $(".ajax_load").hide("slow");
                    }
                });                              
        }else if (gestion == '264' || gestion == '265' || gestion == '266' || gestion == '1085'){
          $(".ajax_load").show("slow");
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/editRec')?>");
          $('#frmTmp').submit();
       }else if (gestion == '267'){
          $(".ajax_load").show("slow");
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/citacionrec')?>");
          $('#frmTmp').submit();
       }else if (gestion == '272' || gestion == '273' || gestion == '274'){
          $(".ajax_load").show("slow");
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/editActaRec')?>");
          $('#frmTmp').submit();
       }else if (gestion == '268' || gestion == '269' || gestion == '1088' || gestion == '1089' || gestion == '270'){
          $(".ajax_load").show("slow");
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/editEdicto')?>");
          $('#frmTmp').submit();
       }else if (gestion == '741' || gestion == '742'){
          $(".ajax_load").show("slow");
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/edPaginaRec')?>");
          $('#frmTmp').submit();
       }else if (gestion == '246' || gestion == '247' || gestion == '248' || gestion == '743'){
          $(".ajax_load").show("slow");
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/editRes')?>");
          $('#frmTmp').submit();
       }else if(gestion == '249' || gestion == '1439'){//
          $(".ajax_load").show("slow");
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/citacionResosa')?>");
          $('#frmTmp').submit();
       }else if(gestion == '1433' || gestion == '1435' || gestion == '1436'){//
          $(".ajax_load").show("slow");
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/editCitResosa')?>");
          $('#frmTmp').submit();
       }else if (gestion == '1437' || gestion == '1438' || gestion == '263'){  
           var url1 = "<?= base_url('index.php/mandamientopago/correoResosa')?>";
           var url2 = "<?= base_url('index.php/mandamientopago/actaResosa')?>";
           $(".ajax_load").show("slow");
              $.ajax({
                    type: "POST",
                    url: "gestion",
                    data: {estado_gestion: gestion, nit: nit, cod_coactivo: cod_coactivo, name: name, clave: mandamiento,
                    perfil: perfil, url1: url1, url2: url2},
                    success: function(data){
                        $('.conn').html(data);
                        $(".ajax_load").hide("slow");
                    }
                });                              
        }else if(gestion == '1446'){
          $(".ajax_load").show("slow");
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/correoResosa')?>");
          $('#frmTmp').submit();
       }else if(gestion == '1440' || gestion == '1441' || gestion == '1442' || gestion == '1443'){
          $(".ajax_load").show("slow");
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/editCorResosa')?>");
          $('#frmTmp').submit();
       }else if (gestion == '1444' || gestion == '1450'){
          $(".ajax_load").show("slow");
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/actaResosa')?>");
          $('#frmTmp').submit();
       }else if (gestion == '1445'){
          $(".ajax_load").show("slow");
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/paginaResosa')?>");
          $('#frmTmp').submit();
       }else if(gestion == '1447' || gestion == '1449' || gestion == '1450'){
          $(".ajax_load").show("slow");
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/edPaginaResosa')?>");
          $('#frmTmp').submit();
       }else if(gestion == 1454 || gestion == 1456 || gestion == 1455){
          $(".ajax_load").show("slow");
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/editActaResosa')?>");
          $('#frmTmp').submit();
       }else if (gestion == '1457'){
           $(".ajax_load").show("slow");
           $.ajax({
                type: "POST",
                url: "verificaMedida",
                data: {gestion: gestion, nit: nit, cod_coactivo: cod_coactivo, name: name, clave: mandamiento},
                success: function(data){
                    $(".ajax_load").hide("slow");
                    window.location = '<?= base_url('index.php/mandamientopago/nits')?>';                    
                }
            });
       }
       else if(gestion == '1321'){
          $('#alert').show();
          $('#alert').html("<b>Ya Existia Medida Cautelar en el Proceso N° "+cod_coactivo+". Por favor Continuar en liquidación</b>");
       }else if(gestion == '1322'){
          $('#alert').show();
          $('#alert').html("<b>La Medida Cautelar del Proceso N° "+cod_coactivo+" fue creada</b>");
       }
       else if (gestion == '1018' || gestion =='1019' || gestion =='1020' || gestion =='1022'){
          $(".ajax_load").show("slow");
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/editExc')?>");
          $('#frmTmp').submit();
       }else if (recurso == 1 && (gestion == '275' || gestion == '271') ){
          $(".ajax_load").show("slow");
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/medidas')?>");
          $('#frmTmp').submit();
       }else if (recurso != 1 && (gestion == '275' || gestion == '271') ){
          $(".ajax_load").show("slow");
          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/addRes')?>");
          $('#frmTmp').submit();          
       }else if (gestion == '250' || gestion == '251'){
          $('#alert').show();
          $('#alert').html("<b>Ejecutar el Auto de Cierre en el Proceso N° "+cod_coactivo+". Por favor Continuar</b>");
//          $(".ajax_load").show("slow");
//          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/autosCierre')?>");
//          $('#frmTmp').submit();// se genera el proceso de levantar medidas cautelares
//          $("#frmTmp").attr("action", "<?= base_url('index.php/mandamientopago/levanta')?>");
//          $('#frmTmp').submit();// se genera el proceso de levantar medidas cautelares
          //TERMINACION DE PROCESO
          //var url = "<?= base_url('index.php/verificarpagos/crearAutosCierre')?>";
//          var url = "<?= base_url('index.php/mandamientopago/autosCierre')?>";
//            $.post(url,{cod_coactivo:cod_coactivo,gestion:gestion,fecha:fecha,clave:mandamiento,nit:nit})
//                .done(function(data){
//                    $('.conn').html(data);
//                    $(".ajax_load").hide("slow");
//                })          
       }    
}         
 
    $('#tablaq').dataTable({
        "bJQueryUI": true,
        "oLanguage": {
            "sProcessing": "Procesando...",
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
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            }
        }
   });
     
  
      
</script>