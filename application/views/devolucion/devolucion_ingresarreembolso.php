<?php
if (isset($message)){
    echo $message;
   }
$nit_reembolso          =  array('name'=>'nit_reembolso','class'=>'search-query','id'=>'nit_reembolso','readonly'=>'true','value'=>$devoluciones->NIT);
$razonsocial_reembolso  =  array('name'=>'razonsocial_reembolso','class'=>'search-query','id'=>'razonsocial_reembolso','readonly'=>'true','value'=>$devoluciones->RAZON_SOCIAL);
$direccion_reembolso    =  array('name'=>'direccion_reembolso','class'=>'search-query','id'=>'direccion_reembolso','readonly'=>'true','value'=>$devoluciones->DIRECCION);
$numrad_reembolso       =  array('name'=>'numrad_reembolso','class'=>'search-query','id'=>'numrad_reembolso','readonly'=>'true','value'=>$devoluciones->NRO_RADICACION);
$fecrad_reembolso       =  array('name'=>'fecrad_reembolso','class'=>'search-query','id'=>'fecrad_reembolso','readonly'=>'true','value'=>$devoluciones->FECHA_RADICACION);
$numpla_reembolso       =  array('name'=>'numpla_reembolso','class'=>'search-query','id'=>'numpla_reembolso','readonly'=>'true','value'=>$devoluciones->NRO_PLANILLA);
$valdev_reembolso       =  array('name'=>'valdev_reembolso','class'=>'search-query','id'=>'valdev_reembolso','readonly'=>'true','value'=>$devoluciones->VALOR_DEVOLUCION);
$motivo_reembolso       =  array('name'=>'motivo_reembolso','class'=>'search-query','id'=>'motivo_reembolso','readonly'=>'true','value'=>$devoluciones->COD_MOTIVO_DEVOLUCION);
$concepto_reembolso     =  array('name'=>'concepto_reembolso','class'=>'search-query','id'=>'concepto_reembolso','readonly'=>'true','value'=>$devoluciones->COD_CONCEPTO);
$tikcet_reembolso       =  array('name'=>'tikcet_reembolso','class'=>'search-query','id'=>'tikcet_reembolso','readonly'=>'true','value'=>$devoluciones->IDTICKET);
$informante_reembolso   =  array('name'=>'informante_reembolso','class'=>'search-query','id'=>'informante_reembolso','readonly'=>'true','value'=>$devoluciones->INFORMANTE);
$cargo_reembolso        =  array('name'=>'cargo_reembolso','class'=>'search-query','id'=>'cargo_reembolso','readonly'=>'true','value'=>$devoluciones->CARGO);
$periodo_reembolso      =  array('name'=>'periodo_reembolso','id'=>'periodo_reembolso','readonly'=>'true','value'=>$devoluciones->PERIODO);
$fecha_reembolso        =  array('name'=>'fecha_reembolso','class'=>'search-query','id'=>'fecha_reembolso','readonly'=>'true','value'=>$devoluciones->FECHA_REGISTRO);
$valorpagado_reembolso  =  array('name'=>'valorpagado_reembolso','class'=>'search-query','id'=>'valorpagado_reembolso','readonly'=>'true','value'=>$devoluciones->VALOR_PAGADO);
$valorconci_reembolso   =  array('name'=>'valorconci_reembolso','class'=>'search-query','id'=>'valorconci_reembolso','readonly'=>'true','value'=>$devoluciones->VALOR_CONCILIADO);
$button                 =  array('name'=>'aceptar_reembolso','id'=>'aceptar_reembolso','type'=>'submit','content'=>'<i class=""></i> Aceptar','class'=>'btn btn-success btn1');
$button1                =  array('name'=>'cancelar_reembolso','id'=>'cancelar_reembolso','content'=>'<i class=""></i> Cancelar','class'=>'btn btn-success btn1');
$codigodevo_reembolso   =  array('name'=>'codigodevo_reembolso','id'=>'codigodevo_reembolso','type'=>'hidden','value'=>$devoluciones->COD_DEVOLUCION);
$enviadoconta_reembolso =  array('name'=>'enviadoconta_reembolso','id'=>'enviadoconta_reembolso','type'=>'hidden','value'=>$devoluciones->ENVIADO_CONTABILIDAD);
?>
<div style="max-width: 886px;min-width: 320px;padding: 40px;width: 88%;background: none repeat scroll 0 0 #F0F0F0;margin: auto;padding-top: 30px;">
<?=form_open('devolucion/guardar_reembolso')?>
    <?=form_input($codigodevo_reembolso);?>
    <?=form_input($enviadoconta_reembolso);?>
    <table>
            <tr>
                <td><?=form_label('Nit','nit');?></td><td><?=form_input($nit_reembolso);?></td>
                <td><?=form_label('Razon Social','rsocial');?></td><td><?=form_input($razonsocial_reembolso);?></td>
                <td><?=form_label('Direccion','direccion');?></td><td><?=form_input($direccion_reembolso);?></td>
        </tr>
        <tr colspan="3">
            <td><?=form_label('Numero de Radicación','num_rad');?></td><td><?=form_input($numrad_reembolso);?></td>
        </tr>
        <tr>
            <td><?=form_label('Fecha de Radicacion','fec_rad');?></td><td><?=form_input($fecrad_reembolso);?></td>
        </tr>
        <tr>
               <td><?=form_label('Numero de Planilla','num_plan');?></td><td><?=form_input($numpla_reembolso);?></td>
        </tr>
        <tr>
            <td><?=form_label('Valor Devolucion','val_dev');?></td><td><?=form_input($valdev_reembolso);?></td>
        </tr>
        <tr>
            <td><?=form_label('Motivo','moti');?></td>
            <td><?=form_input($motivo_reembolso);?></td>
        </tr>
        <tr>
            <td><?=form_label('Concepto','conce');?></td>
            <td><?=form_input($concepto_reembolso);?></td>
            <td><?=form_label('Tikcet Id','tikcet');?></td><td><?=form_input($tikcet_reembolso);?></td>
        </tr>
        <tr>
            <td><?=form_label('Informante','infor');?></td><td><?=form_input($informante_reembolso);?></td>
        </tr>
          <tr>
              <td><?=form_label('Cargo','carg');?></td><td><?=form_input($cargo_reembolso);?></td>
        </tr>
        <tr>
            <td><?=form_label('Periodo','peri');?></td><td><?=form_input($periodo_reembolso);?></td>
        </tr>
        <tr>
            <td><?=form_label('Fecha','fech');?></td><td><?=form_input($fecha_reembolso);?></td>
        </tr>
        <tr>
            <td><?=form_label('Valor Pagado','Valor_pag');?></td><td><?=form_input($valorpagado_reembolso);?></td>
        </tr>
        <tr>
            <td><?=form_label('Valor Conciliado','valor_con');?></td><td><?=form_input($valorconci_reembolso);?></td>
        </tr>
        </table> 
    <table>
        <tr>
            <td><?=form_label('Observaciones','observaciones');?></td><td><textarea name="observa_detalle" id="observa_detalle" style="width: 700px;" readonly="true" required> </textarea></td>
        </tr>
    </table>
    <table>
        <tr>
            <td><?=form_label('Datos del Reembolso','observaciones');?></td>
        </tr>
        <tr>
            <td><textarea name="datos_reembolso" id="datos_reembolso" required="required" style="width: 700px; height: 100px;"> </textarea></td>
        </tr>
    </table>
    <table align="center" cellpadding="15%"><tr><td><?=form_button($button)?></td><td><?=form_button($button1)?></td></tr></table>
    <?=form_close()?>
</div>

<script type="text/javascript" language="javascript" charset="utf-8">   
    
    tinymce.init({
    
    selector: "textarea#datos_reembolso",
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
     
     $("#cancelar_reembolso").confirm({
        title:"Confirmacion",
        text:"¿Esta seguro de cancelar?",
        confirm: function(button) {
            location.href="<?=base_url('index.php/devolucion/devoluciones_generadas')?>";       
        },
        cancel: function(button) {
            
        },
        confirmButton: "SI",
        cancelButton: "NO"
    });
    
     function ir_reembolso(){
         //alert($('#nit_detalle').val());
         
         location.href='<?=base_url()?>index.php/devolucion/reembolso/'+$("#razonsocial_pasa").val();
     }

</script>

