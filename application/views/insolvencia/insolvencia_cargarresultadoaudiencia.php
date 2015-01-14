<?php

$tipdoc_carres  =   array('name'=>'tipdoc_carres','class'=>'search-query','id'=>'tipdoc_carres','value'=>$regimen->NOMBRETIPODOC,'readonly'=>'true');
$razsoc_carres  =   array('name'=>'razsoc_carres','class'=>'search-query','id'=>'razsoc_carres','value'=>$regimen->RAZON_SOCIAL,'readonly'=>'true');
$numdic_carres  =   array('name'=>'numdic_carres','class'=>'search-query','id'=>'numdic_carres','value'=>$regimen->COD_RECEPCIONTITULO,'readonly'=>'true');
$numexp_carres  =   array('name'=>'numexp_carres','class'=>'search-query','id'=>'numexp_carres','value'=>$regimen->NUM_PROCESO,'readonly'=>'true');
$numpro_carres  =   array('name'=>'numpro_carres','class'=>'search-query','id'=>'numpro_carres','value'=>$regimen->PROMOTOR,'readonly'=>'true');
$telefo_carres  =   array('name'=>'telefo_carres','class'=>'search-query','id'=>'telefo_carres','value'=>$regimen->TELEFONO,'readonly'=>'true');
$adjunt_carres  =   array('name'=>'filecolilla','class'=>'search-query','id'=>'filecolilla','type'=>'file','required'=>'true');
$valbie_carres  =   array('name'=>'valbie_carres','class'=>'search-query','id'=>'valbie_carres','type'=>'number','required'=>'true');
$detalle_carres =   array('name'=>'detalle_carres','class'=>'search-query','id'=>'detalle_carres','type'=>'text','required'=>'true');
$opcion         =   array('name'=>'opcion','id'=>'opcion','type'=>'radio','value'=>'S','required'=>'true');
$opcion1        =   array('name'=>'opcion','id'=>'opcion','type'=>'radio','value'=>'N','required'=>'true');
$button         =   array('name'=>'acepta_carres','type'=>'submit','id'=>'acepta_carres','value'=>'Aceptar','content'=>'<i class=""></i> Aceptar','class'=>'btn btn-success btn1');
$button1        =   array('name'=>'cancel_carres','id'=>'cancel_carres','value'=>'cancelar','content'=>'<i class=""></i> Cancelar','class'=>'btn btn-warning btn1');
?>
<div style="max-width: 950px;min-width: 320px;padding: 40px;width: 88%;background: none repeat scroll 0 0 #F0F0F0;margin: auto;">
<?=form_open_multipart("insolvencia/info_audiencia");?>

<input type="hidden" id='nit' name='nit' value='<?= @$nit ?>' readonly>
<input type="hidden" id='fiscalizacion' name='fiscalizacion' value='<?= @$fiscalizacion ?>' readonly>
<input type="hidden" id='regimen' name='regimen' value='<?= @$idregimen ?>' readonly>
<input type="hidden" id='gestion' name='gestion' value='<?= @$gestion ?>' readonly>
<input type="hidden" id='titulo' name='titulo' value='<?= @$titulo ?>' readonly>
<table>
    <tr>
        <td colspan='4' align="center">
            <div align="center" style="color: #ffffff;"><h3>Cargar Acta de Audiencia de Adjudicaci&oacute;n de Bienes</h3></div>
        </td>
    </tr>
        <tr>
            <td> Tipo de Docuemnto</td><td><?= form_input($tipdoc_carres)?></td>
            <td>Razón Social</td><td><?= form_input($razsoc_carres)?></td>
        </tr>
        <tr>
            <td>Número de Documento</td><td><?= form_input($numdic_carres)?></td>
            <td>Número de Expediente</td><td><?= form_input($numexp_carres)?></td>
        </tr>
        <tr>
            <td>Nombre Promotor</td><td><?= form_input($numpro_carres)?></td>
            <td>Teléfono</td><td><?= form_input($telefo_carres)?></td>
        </tr>
        <tr>
            <td></td><td>Cargar acta de audiencia</td><td><?= form_input($adjunt_carres)?></td>
        </tr>
    </table>
    <table cellpadding="5%">
        <tr>
            <td style="border-top-color: #000000; border-top-style: solid; border-top-width: 1px;border-left-color: #000000; border-left-style: solid; border-left-width: 1px;">Resultado de la Audiencia</td>
            <td style="width: 120px; border-top-color: #000000; border-top-style: solid; border-top-width: 1px;"></td>
            <td style="border-top-color: #000000; border-top-style: solid; border-top-width: 1px;"><?= form_checkbox($opcion)?>Se Adjudican Bienes  </td>
            <td style="width: 120px; border-top-color: #000000; border-top-style: solid; border-top-width: 1px;"></td>
            <td style="border-top-color: #000000; border-top-style: solid; border-top-width: 1px;border-right-color: #000000; border-right-style: solid; border-right-width: 1px;">Valor Bienes  <?php  echo form_input($valbie_carres); ?></td>
        </tr>
        <tr>
            <td style="border-bottom-color: #000000; border-bottom-style: solid; border-bottom-width: 1px;border-left-color: #000000; border-left-style: solid; border-left-width: 1px;"></td>
            <td style="width: 120px; border-bottom-color: #000000; border-bottom-style: solid; border-bottom-width: 1px;">
            <td style="border-bottom-color: #000000; border-bottom-style: solid; border-bottom-width: 1px;"><?= form_checkbox($opcion1)?>  No se Adjudican Bienes</td>
            <td style="border-bottom-color: #000000; border-bottom-style: solid; border-bottom-width: 1px;"></td>
            <td style="border-bottom-color: #000000; border-bottom-style: solid; border-bottom-width: 1px;border-right-color: #000000; border-right-style: solid; border-right-width: 1px;">Detalle Bienes  <?= form_input($detalle_carres)?></td>
        </tr>
        
    </table>
    <table cellpadding="15%" align="center">
        <tr>
            <td><?= form_button($button)?></td><td><?= form_button($button1)?></td>
        </tr>
    </table>
</div>

