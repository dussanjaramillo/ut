<?php
$observac=$buscar->OBSERVACIONES;
$nit_detalle            =array('name'=>'nit_detalle','class'=>'search-query','id'=>'nit_detalle','readonly'=>'true','value'=>$buscar->NIT);
$razon_social           =array('name'=>'nit_detalle','class'=>'search-query','id'=>'nit_detalle','readonly'=>'true','value'=>$buscar->NOMBRE_EMPRESA);
$valordevo_detalle      =array('name'=>'valordevo_detalle','class'=>'search-query','id'=>'valordevo_detalle','readonly'=>'true','value'=>$buscar->VALOR_DEVOLUCION);
$motivo_detalle         =array('name'=>'motivo_detalle','class'=>'search-query','id'=>'motivo_detalle','readonly'=>'true','value'=>$buscar->MOTIVO_DEVOLUCION);
$periodo_detalle        =array('name'=>'periodo_detalle','class'=>'search-query','id'=>'periodo_detalle','readonly'=>'true','value'=>$buscar->PERIODO);
$fecha_detalle          =array('name'=>'fecha_detalle','class'=>'search-query','id'=>'fecha_detalle','readonly'=>'true','value'=>$buscar->FECHA_RADICACION);
$vconciliado_detalle    =array('name'=>'vconciliado_detalle','class'=>'search-query','id'=>'vconciliado_detalle','readonly'=>'true','value'=>$buscar->VALOR_CONCILIADO);
$vpagado_detalle        =array('name'=>'vpagado_detalle','class'=>'search-query','id'=>'vpagado_detalle','readonly'=>'true','value'=>$buscar->VALOR_PAGADO);
$button                 =array('name'=>'cancelar_detalle','id'=>'cancelar_detalle','content'=>'<i class=""></i> Cancelar','class'=>'btn btn-success btn1');
$button1                =array('name'=>'ingresarre_detalle','id'=>'ingresarre_detalle','content'=>'<i class=""></i> Ingresar Reembolso','class'=>'btn btn-success btn1','onclick'=>'ir_reembolso()');
$button2                =array('name'=>'actualizarsif_detalle','id'=>'actualizarsif_detalle','content'=>'<i class=""></i> Actualizar SIIF','class'=>'btn btn-success btn1');
$button3                =array('name'=>'encontabilidad_detalle','id'=>'encontabilidad_detalle','content'=>'<i class=""></i> Enviado a Contabilidad','class'=>'btn btn-success btn1');
$button4                =array('name'=>'encorreo_detalle','id'=>'encorreo_detalle','content'=>'<i class=""></i> Enviado por Correo','class'=>'btn btn-success btn1');
$razon_soci             =array('name'=>'razonsocial_pasa','id'=>'razonsocial_pasa','type'=>'hidden','value'=>$buscar->COD_DEVOLUCION);
$direccion              =array('name'=>'direccion_pasa','id'=>'direccion_pasa','type'=>'hidden','value'=>$buscar->DIRECCION);
?>
<?=form_input($razon_soci)?>
<?=form_input($direccion)?>
<table>
    <tr>
        <td><?= form_label('Nit','nit_detalle')?></td><td><?=form_input($nit_detalle)?></td>
        <td><?= form_label('Razon Social','razon_social')?></td><td><?=form_input($razon_social)?></td>
    </tr>
    <tr>
        <td><?= form_label('Valor Devolucion','valordevo_detalle')?></td><td><?=form_input($valordevo_detalle)?></td>
        <td><?= form_label('Motivo','motivo_detalle')?></td><td><?=form_input($motivo_detalle)?></td>
    </tr>
    <tr>
        <td><?= form_label('Periodo','periodo_detalle')?></td><td><?=form_input($periodo_detalle)?></td>
        <td><?= form_label('Fecha','fecha_detalle')?></td><td><?=form_input($fecha_detalle)?></td>
    </tr>
    <tr>
        <td><?= form_label('Valor Pagado','vpagado_detalle')?></td><td><?=form_input($vpagado_detalle)?></td>
        <td><?= form_label('Valor Conciliado','vconciliado_detalle')?></td><td><?=form_input($vconciliado_detalle)?></td>
    </tr>
    <tr><td><br><br><br></td></tr>
</table>
    <table>
        <tr>
            <td align="center"><?= form_label('<b>Observaciones</b>','observaciones_detalle')?></td>    
        </tr>
        <tr>
            <td><textarea name="observa_detalle" id="observa_detalle" style="width:500px; height: 170px;" readonly="true" value="hola" ><?php echo $buscar->OBSERVACIONES?></textarea></td>
        </tr>
    </table>
<table align="center" cellpadding="15%">
    <tr>
        <td><?= form_button($button);?></td>
        <td><?= form_button($button1);?></td>
        <td><?= form_button($button2);?></td>
    </tr>
    
</table>
<table align="center" cellpadding="15%">
    <tr>
        <td colspan="1"><a href="<?=base_url('index.php/devolucion/envio_contabilidad')?>/<?= $buscar->COD_DEVOLUCION?>/<?=$buscar->NRO_RADICACION?>" id="Gestionar" data-toggle="modal" data-target="#modal_contabilidad" data-keyboard="false" data-backdrop="static" title="Gestionar"><?= form_button($button3);?></a></td>
        <td><a href="<?=base_url('index.php/devolucion/envio_porcorreo')?>/<?= $buscar->COD_DEVOLUCION?>/<?=$buscar->NRO_RADICACION?>" id="Gestionar" data-toggle="modal" data-target="#modal_correo" data-keyboard="false" data-backdrop="static" title="Gestionar"><?= form_button($button4);?></a></td>     
    </tr>
</table>

<div class="modal hide fade in" id="modal_contabilidad" style="display: none; width: 60%; margin-left: -30%; height: 550px;">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Enviado a Contabilidad</h4>
        </div>
        <div class="modal-body" >
            <div align="center">
          <img src="<?=  base_url()?>/img/27.gif" width="150px" height="150px" />
            </div>
        </div>
            <?=form_button($buttonmodal);?> 
        </div>
      </div>
    </div>
  </div>
  
  <div class="modal hide fade in" id="modal_correo" style="display: none; width: 60%; margin-left: -30%; height: 550px;">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Enviado por Correo</h4>
        </div>
        <div class="modal-body" >
            <div align="center">
          <img src="<?=  base_url()?>/img/27.gif" width="150px" height="150px" />
            </div>
        </div>
<!--          <div class="modal-footer">
            <a href="#" class="btn btn-default" data-dismiss="modal">Cancelar</a>
            <?=form_button($buttonmodal);?> 

        </div>-->
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->

<script type="text/javascript" language="javascript" charset="utf-8">   
    $("#cancelar_detalle").confirm({
        title:"Confirmacion",
        text:"¿Esta seguro de cancelar?",
        confirm: function(button) {
            $('.modal-body').html('<div align="center"><img src="<?= base_url() ?>/img/27.gif" width="150px" height="150px" /></div>');
            $('#modal_detalle').modal('hide').removeData();        
        },
        cancel: function(button) {
            
        },
        confirmButton: "SI",
        cancelButton: "NO"
    });
    
 tinymce.init({    
    selector: "textarea",
    theme:    "modern",
    plugins: [
     "advlist autolink lists link image charmap print preview hr anchor pagebreak",
     "searchreplace wordcount visualblocks visualchars code fullscreen",
     "insertdatetime media nonbreaking save table contextmenu directionality",
     "emoticons template paste textcolor moxiemanager"
    ],
    toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
    toolbar2: "print preview media | forecolor backcolor emoticons",
    image_advtab: true,
    templates: [
     {title: 'Test template 1', content: '<b>Test 1</b>'},
     {title: 'Test template 2', content: '<em>Test 2</em>'}
    ],
    autosave_ask_before_unload: false
 });
    
     
    
    
     function ir_reembolso(){
         location.href='<?=base_url()?>index.php/devolucion/reembolso/'+$("#razonsocial_pasa").val();
     }

</script>