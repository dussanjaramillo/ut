<?php
if (isset($message)){
    echo $message;
   }
$tipdoc_remdoc  = array('name'=>'tipdoc_remdoc','class'=>'search-query','id'=>'tipdoc_remdoc','readonly'=>'true','value'=>$regimen->NOMBRETIPODOC,'class'=>'search-query');
$numexp_remdoc  = array('name'=>'numexp_remdoc','class'=>'search-query','id'=>'numexp_remdoc','readonly'=>'true','value'=>$regimen->NUM_PROCESO,'class'=>'search-query');
$numdoc_remdoc  = array('name'=>'numdoc_remdoc','class'=>'search-query','id'=>'numdoc_remdoc','readonly'=>'true','value'=>$regimen->NITEMPRESA ,'class'=>'search-query');
$titulo_remdoc  = array('name'=>'titulo_remdoc','class'=>'search-query','id'=>'titulo_remdoc','readonly'=>'true','value'=>$regimen->COD_RECEPCIONTITULO ,'class'=>'search-query');
$razsoc_remdoc  = array('name'=>'razsoc_remdoc','class'=>'search-query','id'=>'razsoc_remdoc','readonly'=>'true','value'=>$regimen->RAZON_SOCIAL,'class'=>'input-xxlarge search-query');
$insolv_remdoc  = array('name'=>'insolv_remdoc','class'=>'search-query','id'=>'insolv_remdoc','readonly'=>'true','value'=>$regimen->COD_REGIMENINSOLVENCIA,'class'=>'input-xxlarge search-query','type'=>'hidden');
$opcion         = array('name'=>'opcion','id'=>'opcion','value'=>'Titulo Ejecutivo o Resolucion Ejecutoria','required[]'=>'true');
$opcion1        = array('name'=>'opcion1','id'=>'opcion','value'=>'Liquidacion','required[]'=>'true');
$opcion2        = array('name'=>'opcion2','id'=>'opcion','value'=>'Poder Con Soportes Legales','required[]'=>'true');
$opcion3        = array('name'=>'opcion3','id'=>'opcion','value'=>'Acto de la Resolucion','required[]'=>'true');
$button         = array('name'=> 'acepta_remdoc','id'=>'acepta_remdoc','type'=>'submit','value'=>'Aceptar','content'=>'<i class=""></i> Aceptar','class'=>'btn btn-success btn1');
$button1        = array('name'=> 'cancel_remdoc','id'=>'cancel_remdoc','value'=>'Cancelar','content'=>'<i class=""></i> Cancelar','class'=>'btn btn-warning btn1','onclick'=>'window.location=\''.base_url().'index.php/insolvencia/remitirdocsuperintendencia\'');

?>
<div style="max-width: 970px;min-width: 320px;padding: 40px;width: 88%;background: none repeat scroll 0 0 #F0F0F0;margin: auto;">
  
<?= form_open(base_url('index.php/insolvencia/guardar_remitir_dcumento'));        ?>
    <input type='hidden' name='fiscalizacion' id='fiscalizacion' value='<?= $regimen->COD_FISCALIZACION ?>' readonly>
    <div align="center"><h3>Documentos de Crédito Para la Superintendencia</h3></div>
    <table>
        <tr>
            <td>Tipo de Documento</td><td><?= form_input($tipdoc_remdoc)?></td>
            <td>Número de Expediente</td><td><?= form_input($numexp_remdoc)?></td>
        </tr>
        <tr>
            <td>Número de Documento</td><td><?= form_input($numdoc_remdoc)?></td><td>Título</td><td><?= form_input($titulo_remdoc)?></td>
        </tr>
        <tr>
            <td>Razón Social</td><td colspan="2"><?= form_input($razsoc_remdoc)?><?= form_input($insolv_remdoc)?></td>
        </tr>
        <tr>
            <td>
                <br><br>
            </td>
        </tr>
        <tr>
            <br><br>
            <td colspan="2"><h3>Lista de Chequeo Documentos Entregados</h3></td>
        </tr>
        <tr>
            <td style="height: 20px;"></td>
        </tr>
        <tr>
            <td colspan="2"><?= form_checkbox($opcion)?>  Título Ejecutivo o Resolución Ejecutoria</td>
        </tr>
        <tr>
            <td colspan="2"><?= form_checkbox($opcion1)?>  Liquidación</td>
        </tr>
        <tr>
            <td colspan="2"><?= form_checkbox($opcion2)?>  Poder Con Soportes Legales</td>
        </tr>
        <tr>
            <td colspan="2"><?= form_checkbox($opcion3)?>  Acto de la Resolución </td>
        </tr>
    </table>
    <br>
    
    Observaciones
    <table>
        <tr>
            <td><textarea id="observ_remdoc" name="observ_remdoc" style="width: 600px; height: 100px;"required="true"></textarea></td>
        </tr>
    </table>
    <table align="center" cellpadding="15%">
        <tr>
            <td><?= form_button($button)?></td>
            <td><?= form_button($button1)?></td>
        </tr>
    </table>
    <?= form_close()?>
</div>


