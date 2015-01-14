<?php
$tipdoc_mempro  =   array('name'=>'tipdoc_mempro','class'=>'search-query','id'=>'tipdoc_mempro');
$razsoc_mempro  =   array('name'=>'razsoc_mempro','class'=>'search-query','id'=>'razsoc_mempro');
$numdic_mempro  =   array('name'=>'numdic_mempro','class'=>'search-query','id'=>'numdic_mempro');
$numexp_mempro  =   array('name'=>'numexp_mempro','class'=>'search-query','id'=>'numexp_mempro');
$numpro_mempro  =   array('name'=>'numpro_mempro','class'=>'search-query','id'=>'numpro_mempro');
$telefo_mempro  =   array('name'=>'telefo_mempro','class'=>'search-query','id'=>'telefo_mempro');
$fecha_mempro   =   array('name'=>'fecha_mempro','class'=>'search-query','id'=>'fecha_mempro','class'=>'input-small');
$valobl_mempro  =   array('name'=>'valobl_mempro','class'=>'search-query','id'=>'valobl_mempro');
$formpag_mempro =   array('name'=>'formpag_mempro','class'=>'search-query','id'=>'formpag_mempro');
$fecpag_mempro  =   array('name'=>'fecpag_mempro','class'=>'search-query','id'=>'fecpag_mempro');
$opcion         =   array('name'=>'opcion','id'=>'opcion','value'=>'si','type'=>'radio');
$opcion1        =   array('name'=>'opcion','id'=>'opcion','value'=>'no','type'=>'radio');
$opcion2        =   array('name'=>'opcion','id'=>'opcion1','value'=>'si','type'=>'radio');
$opcion3        =   array('name'=>'opcion','id'=>'opcion1','value'=>'no','type'=>'radio');
$button         =   array('name'=> 'guarda_actreo','id'=>'guarda_actreo','value'=>'Guardar','content'=>'<i class=""></i> Guardar','class'=>'btn btn-success btn1');
$button1        =   array('name'=> 'cancel_actreo','id'=>'cancel_actreo','value'=>'cancelar','content'=>'<i class=""></i> Cancelar','class'=>'btn btn-success btn1');
?>
<div style="max-width: 950px;min-width: 320px;padding: 40px;width: 88%;background: none repeat scroll 0 0 #F0F0F0;margin: auto;">
    <table>
        <tr>
            <td> Tipo de Docuemnto</td><td><?= form_input($tipdoc_mempro)?></td>
            <td>Razón Social</td><td><?= form_input($razsoc_mempro)?></td>
        </tr>
        <tr>
            <td>Número de Documento</td><td><?= form_input($numdic_mempro)?></td>
            <td>Número de Expediente</td><td><?= form_input($numexp_mempro)?></td>
        </tr>
        <tr>
            <td>Nombre Promotor</td><td><?= form_input($numpro_mempro)?></td>
            <td>Teléfono</td><td><?= form_input($telefo_mempro)?></td>
        </tr>
    </table>
    <table cellpadding="10%">
        <tr>
            <td style="border-top-color:#000000; border-top-style:solid; border-top-width:1px;border-left-color:#000000; border-left-style:solid; border-left-width:1px;border-bottom-color:#000000; border-bottom-style:solid; border-bottom-width:1px;">
                <table cellpadding="15%">
                    <tr>
                        <td>Se Realizó la Audiencia?</td>
                    </tr> 
                     <tr>
                        <td>Fecha de la Audiencia</td>
                    </tr> 
                     <tr>
                        <td>Se Realizó la Lectura del Acta?</td>
                    </tr> 
                </table>
            </td>
            <td style="border-top-color:#000000; border-top-style:solid; border-top-width:1px;border-bottom-color:#000000; border-bottom-style:solid; border-bottom-width:1px;">
                <table cellpadding="5%">
                    <tr>
                        <td style="border-top-color:#000000; border-top-style:solid; border-top-width:1px;border-right-color:#000000; border-right-style:solid; border-right-width:1px;border-left-color:#000000; border-left-style:solid; border-left-width:1px;"><?= form_checkbox($opcion)?>Si</td>
                    </tr>
                    <tr>
                        <td style="border-bottom-color:#000000; border-bottom-style:solid; border-bottom-width:1px;border-right-color:#000000; border-right-style:solid; border-right-width:1px;border-left-color:#000000; border-left-style:solid; border-left-width:1px;"><?= form_checkbox($opcion1)?>No</td>
                    </tr>
                    <tr>
                        <td><?= form_input($fecha_mempro)?></td>
                    </tr>
                    <tr>
                        <td style="border-top-color:#000000; border-top-style:solid; border-top-width:1px;border-right-color:#000000; border-right-style:solid; border-right-width:1px;border-left-color:#000000; border-left-style:solid; border-left-width:1px;"><?= form_checkbox($opcion2)?>Si</td>
                    </tr>
                    <tr>
                        <td style="border-bottom-color:#000000; border-bottom-style:solid; border-bottom-width:1px;border-right-color:#000000; border-right-style:solid; border-right-width:1px;border-left-color:#000000; border-left-style:solid; border-left-width:1px;"><?= form_checkbox($opcion3)?>No</td>
                    </tr>
                </table>
            </td>
            <td style="border-top-color:#000000; border-top-style:solid; border-top-width:1px;border-bottom-color:#000000; border-bottom-style:solid; border-bottom-width:1px;border-right-color:#000000; border-right-style:solid; border-right-width:1px;">
                <table>
                    <tr>
                        <td>Motivo</td><td><textarea></textarea></td>
                    </tr>
                    <tr>
                        <td>Valor Obligación</td><td><?= form_input($valobl_mempro)?></td>
                    </tr>
                    <tr>
                        <td>Forma de Pago</td><td><?= form_input($formpag_mempro)?></td>
                    </tr>
                    <tr>
                        <td>Fecha de Pago</td><td><?= form_input($fecpag_mempro)?></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table cellpadding="15%" align="center">
        <tr>
            <td><?= form_button($button)?></td>
            <td><?= form_button($button1)?></td>
        </tr>
    </table>
</div>
<script type="text/javascript" language="javascript" charset="utf-8">  
    $("#fecha_actreo").datepicker();
jQuery(function ($) {
        $.datepicker.regional['es'] = {
            closeText: 'Cerrar',
            prevText: '<Ant',
            nextText: 'Sig>',
            currentText: 'Hoy',
            monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            dayNames: ['Domingo', 'Lunes', 'Martes', 'Mi�rcoles', 'Jueves', 'Viernes', 'S�bado'],
            dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mi�', 'Juv', 'Vie', 'Sab'],
            dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
            weekHeader: 'Sm',
            dateFormat: 'dd/mm/yy',
            firstDay: 1,
            isRTL: false,
            showMonthAfterYear: false,
            yearSuffix: ''
            
        };
        $.datepicker.setDefaults($.datepicker.regional['es']);
    });
    </script>
