<?php
if (isset($message)){
    echo $message;
   }
   
$id_radio           = array('name'=>'id_radio','id'=>'id_radio','type'=>'hidden');
$tip_ges            = array('name'=>'tip_ges','id'=>'tip_ges','type'=>'hidden');
$expediente         = array('name'=>'expediente','id'=>'expediente','type'=>'hidden');
$valor_exp          = array('name'=>'valor_exp','id'=>'valor_exp','type'=>'hidden');
$button             = array('name'=>'salir','id'=>'salir','value'=>'salir','content'=>'<i class=""></i> Salir','class'=>'btn btn-success btn1');

?>
<div id='regimen_modal'>
<div class="modal hide fade in" id="modal" style="display: none; width: 60%; margin-left: -30%;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><?php echo @$title; ?></h4>
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

<div style="max-width: 1015px;min-width: 320px;padding: 40px;width: 88%;background: none repeat scroll 0 0 #F0F0F0;margin: auto;">
    <div align="center"><h3>Gestionar Títulos</h3></div>    
    <br>
<table id="tablaq">
    <thead>      
       <tr>
        <th>No. Documento</th>
        <th>Razón Social</th>
        <th>N. Título</th> 
        <th>F. Radicado</th>
        <th>Num. Expediente</th>
        <th>Gestión a Realizar</th>
        <th>Estado</th>
        <th>Gestionar</th>     
      </tr>
    </thead>
    <tbody>
            <?php               
            if (@$registros != ''){
            foreach (@$registros->result_array as $value) {                
                ?>
        <tr>
            <td align="center"><?= $value['NITEMPRESA'] ?></td>
            <td align="center"><?= $value['RAZON_SOCIAL'] ?></td>
            <td align="center"><?= $value['COD_RECEPCION_TITULO'] ?></td>            
            <td align="center"><?= $value['FECHA'] ?></td>            
            <td align="center"><?= $value['NUM_PROCESO'] ?></td>                        
            <td align="center"><?= $value['COMENTARIOS'] ?></td>      
            <td align="center"><?= $value['NOMBRE_GESTION'] ?></td> 
            <td align="center"><input data-toggle="modal" data-target="#modal" type="radio" id="<?= $value['COD_REGIMENINSOLVENCIA'] ?>,<?= $value['NITEMPRESA'] ?>,<?= $value['COD_FISCALIZACION'] ?>,<?= $value['COD_TIPO_RESPUESTA'] ?>,<?= $value['COD_RECEPCION_TITULO'] ?>,<?= $value['COD_PROCESO_COACTIVO'] ?>,<?= $value['TIPO_REGIMEN'] ?>" name='rdbNit' onclick="gestionar(this.id)"></td>
        </tr>
                <?php                
            }            
          }
            ?>
    </tbody>     
</table>
    
</div>    
<?=form_input($id_radio)?><?=form_input($tip_ges)?><?=form_input($expediente)?><?=form_input($valor_exp)?>
<br>
<div align="center">
    <?= form_button($button)?>
    
</div>
<form id='frmTmp' name='frmTmp' method='post'>
    <input type="hidden" id="regimen" name="regimen">
    <input type="hidden" id="nit" name="nit">
    <input type="hidden" id="fiscalizacion" name="fiscalizacion">
    <input type="hidden" id="gestion" name="gestion">
    <input type="hidden" id="titulo" name="titulo">  
    <input type="hidden" id="tipo_regimen" name="tipo_regimen">  
</form>

    <script type="text/javascript" language="javascript" charset="utf-8"> 
        var oTable;
        var gestion ='';
        var gestion2='';
        
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

    
    function gestionar(id){
        var datos           = id.split(',');
        var regimen         = datos[0];
        var nit             = datos[1];
        var fiscalizacion   = datos[2];
        var gestion         = datos[3];
        var titulo          = datos[4];
        var coactivo        = datos[5];
        var tipo_regimen    = datos[6];
        $('#regimen').val(regimen);
        $('#nit').val(nit);
        $('#fiscalizacion').val(fiscalizacion);            
        $('#gestion').val(gestion);
        $('#titulo').val(titulo);
        $('#tipo_regimen').val(tipo_regimen);
        //alert (gestion+'-'+fiscalizacion+'-'+tipo_regimen+'-'+coactivo);
        if (gestion == 780){
            $(".ajax_load").show("slow");              
            $("#frmTmp").attr("action", "<?= base_url('index.php/insolvencia/ordenar_remision')?>");
            $('#frmTmp').submit();            
        }else if(gestion == 779 || gestion == 611){
            $(".ajax_load").show("slow");              
            $("#frmTmp").attr("action", "<?= base_url('index.php/insolvencia/remitir_dcumento')?>");
            $('#frmTmp').submit();
        }else if(gestion == 596 || gestion == 816){
            $(".ajax_load").show("slow");              
            $("#frmTmp").attr("action", "<?= base_url('index.php/insolvencia/asignar_abogado')?>");
            $('#frmTmp').submit();
        }else if (gestion == 783 || gestion == 806 || gestion == 785){
            $(".ajax_load").show("slow");              
            $("#frmTmp").attr("action", "<?= base_url('index.php/insolvencia/designar_abogado')?>");
            $('#frmTmp').submit();
        }else if (gestion == 818 || gestion == 609 || gestion == 614 || gestion == 1351){
            $(".ajax_load").show("slow");              
            $("#frmTmp").attr("action", "<?= base_url('index.php/insolvencia/revisar_proyeto')?>");
            $('#frmTmp').submit();
        }else if (gestion == 598 && tipo_regimen == 0){
            $(".ajax_load").show("slow");              
            $("#frmTmp").attr("action", "<?= base_url('index.php/insolvencia/acta_reorganizacion')?>");
            $('#frmTmp').submit();
        }else if (gestion == 598 && tipo_regimen == 1){
            $(".ajax_load").show("slow");              
            $("#frmTmp").attr("action", "<?= base_url('index.php/insolvencia/acta_calificacion')?>");
            $('#frmTmp').submit();
        }else if (gestion == 1339){
            $(".ajax_load").show("slow");              
            $("#frmTmp").attr("action", "<?= base_url('index.php/insolvencia/objetar_pruebas')?>");
            $('#frmTmp').submit();
        }else if (gestion == 819){
            $(".ajax_load").show("slow");              
            $("#frmTmp").attr("action", "<?= base_url('index.php/insolvencia/elaborar_audiencia')?>");
            $('#frmTmp').submit();            
        }else if (gestion == 822 && tipo_regimen == 0){
            $(".ajax_load").show("slow");              
            $("#frmTmp").attr("action", "<?= base_url('index.php/insolvencia/acta_reorganizacion')?>");
            $('#frmTmp').submit();            
        }
        else if (gestion == 822 && tipo_regimen == 1){
            $(".ajax_load").show("slow");              
            $("#frmTmp").attr("action", "<?= base_url('index.php/insolvencia/cargar_resultado')?>");
            $('#frmTmp').submit();            
        }
        else if (gestion == 603 && tipo_regimen == 0){
            $(".ajax_load").show("slow");              
            $("#frmTmp").attr("action", "<?= base_url('index.php/insolvencia/generar_memorial')?>");
            $('#frmTmp').submit();            
        }else if (gestion == 778){
            $(".ajax_load").show("slow");              
            $("#frmTmp").attr("action", "<?= base_url('index.php/insolvencia/index')?>");
            $('#frmTmp').submit();            
        }else if (gestion == 601 && tipo_regimen == 1){
            $(".ajax_load").show("slow");              
            $("#frmTmp").attr("action", "<?= base_url('index.php/insolvencia/cargar_resultado')?>");
            $('#frmTmp').submit();            
        }else if (gestion == 1350 || gestion == 608){
            $(".ajax_load").show("slow");              
            $("#frmTmp").attr("action", "<?= base_url('index.php/insolvencia/notifica_abogado')?>");
            $('#frmTmp').submit();            
        }else if (gestion == 837 || gestion == 593 || (gestion == 603 || tipo_regimen == 1) || (gestion == 601 || tipo_regimen == 0) || gestion == 838 || gestion == 1342 || gestion == 1343 || gestion == 604 || gestion == 1347 || gestion == 1348 || gestion == 1349){            
            $(".ajax_load").show("slow");              
             $.ajax({
                    type: "POST",
                    url: "<?= base_url('index.php/insolvencia/verificar_regimen')?>",
                    data: {regimen:regimen,nit:nit,fiscalizacion:fiscalizacion,gestion:gestion,titulo:titulo,coactivo:coactivo},
                    success: 
                        function(data){
                        $('.conn').html(data);
                        $(".ajax_load").hide("slow");
                    }
                });  
        }
    }
        
        $("#salir").confirm({
        title:"Confirmacion",
        text:"Esta Seguro de Salir?",
        confirm: function(button) {
            location.href="<?=base_url('index.php/')?>";       
        },
        cancel: function(button) {
            
        },
        confirmButton: "SI",
        cancelButton: "NO"
    });
 
    $(function() {
        $('#modal').on('hidden', function() {
            $('.conn').val("");
            $('#modal').modal('hide').removeData();
        });
    });

</script>
