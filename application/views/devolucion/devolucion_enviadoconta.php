<?php
$hoy                = date("d/m/Y");
$numrad_envioconta  = array('name'=>'numrad_envioconta','class'=>'search-query','id'=>'numrad_envioconta','value'=>$numero);
$codigos            = array('name'=>'codigos','class'=>'search-query','id'=>'codigos','value'=>$codigo,'type'=>'hidden');
$fecha              = array('name'=>'fecha','class'=>'search-query','id'=>'fecha','value'=>$hoy,'type'=>'hidden');
$button             = array('name'=> 'aceptar_conta','id'=>'aceptar_conta','type'=>'submit','value'=>'Aceptar','content'=>'<i class=""></i> Aceptar','class'=>'btn btn-success btn1');
?>
<div style="max-width: 686px;min-width: 320px;padding: 40px;width: 88%;background: none repeat scroll 0 0 #F0F0F0;margin: auto;">
   <?= form_open('devolucion/guardar_envio_contabilidad')?>
    <table cellpadding="20%">
        <tr>
            <td>Numero de Radicado</td><td><?= form_input($numrad_envioconta)?></td>
        </tr>
    </table>
    <table>
        <tr>
            <td>Observaciones</td><td><textarea name="obser_contabilidad" id="obser_contabilidad" required="true" style="width: 600px; height: 100px;"></textarea></td>
        </tr>
        <tr>
            <td><?= form_button($button)?></td>
        </tr>
    </table>
    <?= form_input($codigos)?>
    <?= form_input($fecha)?>
    <?= form_close()?>
</div>
<script type="text/javascript" language="javascript" charset="utf-8">

     </script>