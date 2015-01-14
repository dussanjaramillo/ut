<?php
$tipdoc_notabo  =   array('name'=>'tipdoc_notabo','class'=>'search-query','id'=>'tipdoc_notabo','readonly'=>'true','value'=>$consultar_not->NOMBRETIPODOC);
$razsoc_notabo  =   array('name'=>'razsoc_notabo','class'=>'search-query input-xxlarge','id'=>'razsoc_notabo','readonly'=>'true','value'=>$consultar_not->RAZON_SOCIAL);
$numdoc_notabo  =   array('name'=>'numdic_notabo','class'=>'search-query','id'=>'numdic_notabo','readonly'=>'true','value'=>$consultar_not->CODEMPRESA);
$numexp_notabo  =   array('name'=>'numexp_notabo','class'=>'search-query','id'=>'numexp_notabo','readonly'=>'true','value'=>$consultar_not->NUM_PROCESO);
$numfol_notabo  =   array('name'=>'numfol_notabo','class'=>'search-query','id'=>'numfol_notabo');
$opcion         =   array('name'=>'opcion','id'=>'opcion','value'=>'Certificado de Representacion Legal');
$opcion1        =   array('name'=>'opcion1','id'=>'opcion1','value'=>'Certificado de Proponentes');
$opcion2        =   array('name'=>'opcion2','id'=>'opcion2','value'=>'Fotocopias del Rut');
$opcion3        =   array('name'=>'opcion3','id'=>'opcion3','value'=>'Balances Detallados');
$opcion4        =   array('name'=>'opcion4','id'=>'opcion4','value'=>'Fotocopias Declaraciones de Renta');
$opcion5        =   array('name'=>'opcion5','id'=>'opcion5','value'=>'Documentos Anexos al Proceso');
$opcion6        =   array('name'=>'opcion6','id'=>'opcion6','value'=>'Memorales');
$button         =   array('name'=> 'aceptar','id'=>'aceptr','type'=>'submit','value'=>'Aceptar','content'=>'<i class=""></i> Aceptar','class'=>'btn btn-success btn1');
?>
<div style="max-width: 950px;min-width: 320px;padding: 40px;width: 88%;background: none repeat scroll 0 0 #F0F0F0;margin: auto;">
    <?=form_open(base_url('index.php/insolvencia/geactnotabo'))?>
    <table>
        <tr>
            <td>Tipo de Documento</td><td><?= form_input($tipdoc_notabo)?></td>
            <td>Razón Social</td><td><?= form_input($razsoc_notabo)?></td>
        </tr>
        <tr>
            <td>Número de Documento</td><td><?= form_input($numdoc_notabo)?></td>
            <td>Número de Expediente</td><td><?= form_input($numexp_notabo)?></td>
        </tr>
    </table>
    <div align="center" style="background-color: #FC7323; color: #ffffff;"><h4>Check List Entrega de Proceso</h4></div>
    <table>
        <tr>
            <td>
    <table>
        <tr>
            <td>Lista de Chequeo Documentos Entregados</td>
        </tr>
        <tr>
            <td><?= form_checkbox($opcion)?>  Certificado de Representación Legal</td>
        </tr>
        <tr>
            <td><?= form_checkbox($opcion1)?>  Certificado de Proponentes</td>
        </tr>
        <tr>
            <td><?= form_checkbox($opcion2)?>  Fotocopias del Rut</td>
        </tr>
        <tr>
            <td><?= form_checkbox($opcion3)?>  Balances Detallados</td>
        </tr>
        <tr>
            <td><?= form_checkbox($opcion4)?>  Fotocopias Declaraciones de Renta</td>
        </tr>        
    </table>
            </td>
            <td style="width: 226px;"></td>
            <td>
                <table>
                    <tr>
                        <td><?= form_checkbox($opcion5)?>  Documentos Anexos al Proceso</td>
                    </tr>
                    <tr>
                        <td><?= form_checkbox($opcion6)?>  Memorales</td>
                    </tr>
                    <tr>
                        <td>Número de Folios</td><td><?= form_input($numfol_notabo)?></td>
                    </tr>
                </table>
            </td>
        </tr>
        
    </table>
    <table align="center">
        <tr>
            <td><?=form_button($button)?></td>
        </tr>
    </table>
    <?=form_close()?>
</div>

