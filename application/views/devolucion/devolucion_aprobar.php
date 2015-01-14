<?php
$nit_aprodev    = array('name'=>'nit_aprodev','class'=>'search-query','id'=>'nit_aprodev','value'=>$buscar->NIT,'readonly'=>'true');
$raz_aprodev    = array('name'=>'raz_aprodev','class'=>'search-query','id'=>'raz_aprodev','value'=>$buscar->RAZON_SOCIAL,'readonly'=>'true');
$numra_aprodev  = array('name'=>'numra_aprodev','class'=>'search-query','id'=>'numra_aprodev','value'=>$buscar->NRO_RADICACION,'readonly'=>'true');
$numpla_aprodev = array('name'=>'numpla_aprodev','class'=>'search-query','id'=>'numpla_aprodev','value'=>$buscar->NRO_PLANILLA,'readonly'=>'true');
$valdev_aprodev = array('name'=>'valdev_aprodev','class'=>'search-query','id'=>'valdev_aprodev','value'=>$buscar->VALOR_DEVOLUCION,'readonly'=>'true');
$mot_aprodev    = array('name'=>'mot_aprodev','class'=>'search-query','id'=>'mot_aprodev','value'=>$buscar->MOTIVO_DEVOLUCION,'readonly'=>'true');
$si_aprobar     = array('name'=>'aprobar','id'=>'aprobar','type'=>'radio','value'=>'s','class'=>'habilitar');
$no_aprobar     = array('name'=>'aprobar','id'=>'aprobar','type'=>'radio','value'=>'n','class'=>'habilitar');
$button         = array('name'=> 'aceptar_apro','id'=>'aceptar_apro','type'=>'submit','value'=>'Aceptar','content'=>'<i class=""></i> Aceptar','class'=>'btn btn-success btn1');
$button1        = array('name'=> 'cancelar_apro','id'=>'cancelar_apro','value'=>'Cancelar','content'=>'<i class=""></i> Cancelar','class'=>'btn btn-success btn1');
?>
<div style="max-width: 886px;min-width: 320px;padding: 40px;width: 88%;background: none repeat scroll 0 0 #F0F0F0;margin: auto;">
    <ul class="nav nav-tabs">
  <li class="active"><a href="#home" data-toggle="tab">Consultar Devolucion</a></li>
</ul>
<!-- Tab panes -->
<div class="tab-content">
    <div class="tab-pane active" id="home">
        <?=form_open('devolucion/guardar_aprobacion')?>
        <table>
            <tr>
                <td>Nit</td><td><?= form_input($nit_aprodev)?></td>
                <td>Razon Social</td><td><?= form_input($raz_aprodev)?></td>
            </tr>
            <tr>
                <td>Numero de Radicado</td><td><?= form_input($numra_aprodev)?></td>
                <td>Numero de Planilla</td><td><?= form_input($numpla_aprodev)?></td>
            </tr>
            <tr>
                <td>Valor Devolucion</td><td><?= form_input($valdev_aprodev)?></td>
                <td>Motivo</td><td><?= form_input($mot_aprodev)?></td>
            </tr>
            <tr>
                <td>Aprobar</td><td><?= form_checkbox($si_aprobar);?>Si</td><td><?= form_checkbox($no_aprobar);?>No</td>
            </tr>
        </table>
        <table>
            <tr>
                <td>Comentarios</td><td><textarea name="comen_aprobar" id="comen_aprobar" style="width: 700px; height: 100px;" required="true"></textarea></td>
            </tr>
        </table>
        <table align="center" cellpadding="15%">
            <tr>
                <td><?=form_button($button)?></td><td><?=form_button($button1)?></td>
            </tr>
        </table>
        <input type="hidden" name="cod_devo" id="cod_devo" value="<?= $buscar->COD_DEVOLUCION ?>">
        <?= form_close() ?>
    </div>
</div>
</div>
<script type="text/javascript" language="javascript" charset="utf-8">
    $("#cancelar_apro").confirm({
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
    
$(function () {
$('#aceptar_apro').attr("disabled", true);
});
$('.habilitar').click(function(){ 
        var tipo_cal= $("#aprobar:checked").val();
        $('#aceptar_apro').attr("disabled", false);
    });
</script>

