<?php
$tipdoc_asigabo = array('name'=>'tipdoc_remdoc','class'=>'search-query','id'=>'tipdoc_remdoc','readonly'=>'true','value'=>$regimen->NOMBRETIPODOC,'class'=>'search-query');
$numexp_asigabo = array('name'=>'numexp_remdoc','class'=>'search-query','id'=>'numexp_remdoc','readonly'=>'true','value'=>$regimen->NUM_PROCESO,'class'=>'search-query');
$numdoc_asigabo = array('name'=>'numdoc_remdoc','class'=>'search-query','id'=>'numdoc_remdoc','readonly'=>'true','value'=>$regimen->NITEMPRESA ,'class'=>'search-query');
$titulo_asigabo = array('name'=>'titulo_remdoc','class'=>'search-query','id'=>'titulo_remdoc','readonly'=>'true','value'=>$regimen->COD_RECEPCIONTITULO ,'class'=>'search-query');
$razsoc_asigabo = array('name'=>'razsoc_remdoc','class'=>'search-query','id'=>'razsoc_remdoc','readonly'=>'true','value'=>$regimen->RAZON_SOCIAL,'class'=>'input-xxlarge search-query');
$fecasi_asigabo = array('name'=>'fecasi_asigabo','class'=>'search-query','id'=>'fecasi_asigabo','class'=>'input-small','readonly'=>'true','value'=>$hoy); 
$insolv_asigabo = array('name'=>'insolv_remdoc','class'=>'search-query','id'=>'insolv_remdoc','readonly'=>'true','value'=>$regimen->COD_REGIMENINSOLVENCIA,'class'=>'input-xxlarge search-query','type'=>'hidden');
$num_fiscaliza  = array('name'=>'num_fiscaliza','type'=>'hidden','class'=>'search-query','id'=>'num_fiscaliza','readonly'=>'true','value'=>$regimen->COD_FISCALIZACION);
$opcion         = array('name'=>'opcion','id'=>'opcion','value'=>'Titulo Ejecutivo o Resolucion Ejecutoria');
$opcion1        = array('name'=>'opcion1','id'=>'opcion','value'=>'Liquidacion');
$opcion2        = array('name'=>'opcion2','id'=>'opcion','value'=>'Poder Con Soportes Legales');
$opcion3        = array('name'=>'opcion3','id'=>'opcion','value'=>'Acto de la Resolucion');
$button         = array('name'=>'acepta_remdoc','id'=>'acepta_remdoc','type'=>'submit','value'=>'Aceptar','content'=>'<i class=""></i> Aceptar','class'=>'btn btn-success btn1');
$button1        = array('name'=>'cancel_remdoc','id'=>'cancel_remdoc','value'=>'Cancelar','content'=>'<i class=""></i> Cancelar','class'=>'btn btn-warning btn1','onclick'=>'window.location=\''.base_url().'index.php/insolvencia/designarabogado\'');
$formulario     = array('name'=>'frmtp','id'=>'frmtp','method'=>'post');
?>
<div style="max-width: 870px;min-width: 320px;padding: 40px;width: 88%;background: none repeat scroll 0 0 #F0F0F0;margin: auto;">
    <?= form_open(base_url('index.php/insolvencia/guardar_asignar_abogado'),$formulario)?>
    <table>
        <tr>
            <td colspan="4">
                <div align="center"><h3>Designar Abogado</h3></div>
            </td>
        </tr>
        <tr>
            <td>Tipo de Documento</td><td><?= form_input($tipdoc_asigabo)?><?= form_input($num_fiscaliza)?></td>
            <td>Número de Expediente</td><td><?= form_input($numexp_asigabo)?></td>
        </tr>
        <tr>
            <td>Número de Documento</td><td><?= form_input($numdoc_asigabo)?></td>
            <td>Título</td><td><?= form_input($titulo_asigabo)?><?= form_input($insolv_asigabo)?></td>
        </tr>
    </table>
    <table>
        <tr>
            <td>Razón Social</td><td><?= form_input($razsoc_asigabo)?></td>
        </tr>
    </table>
    <table>
        <tr>
            <td>Asignar a:</td>
        </tr>
        <tr>
            <td><select name="abogasi_asigabo" id="abogasi_asigabo" required='true'>
            <option value=''>--Seleccione--</option>
                <?php foreach($usuarios as $row){
                    echo '<option value="'.$row->IDUSUARIO.'">'.$row->NOMBRES." ".$row->APELLIDOS.'</option>';}?>
        </select></td>
        <td>Fecha de Asignación</td><td><?=form_input($fecasi_asigabo)?></td>
        </tr>
        <tr>
            <td>Comentarios</td>
        </tr>
        
    </table>
    <table>
        <tr>
            <td><textarea id="com_asigabo" name="com_asigabo" style="width:500px; height: 200px" required='true'></textarea></td>
        </tr>
    </table>
    <table align="center" cellpadding="15%">
        <tr>
            <td><?=form_button($button)?></td>
            <td><?=form_button($button1)?></td>
        </tr>
    </table>
    <?=form_close()?>
</div>
<script type="text/javascript" language="javascript" charset="utf-8">
//    $('#acepta_remdoc').click(function(){
//        var asignar = $('#abogasi_asigabo').val();
//        var comenta = $('#com_asigabo').val();
//        if (asignar == ''){
//            
//        }
//    })
    
//    function prueba(e){
//         tecla = (document.all) ? e.keyCode : e.which; 
//    if (tecla==8) return true; // backspace
//    if (tecla==32) return true; // espacio
//    if (e.ctrlKey && tecla==86) { return true;} //Ctrl v
//    if (e.ctrlKey && tecla==67) { return true;} //Ctrl c
//    if (e.ctrlKey && tecla==88) { return true;} //Ctrl x
// 
//    patron = /[a-zA-Z]/; //patron
// 
//    te = String.fromCharCode(tecla); 
//    return patron.test(te); // prueba de patron
//    }
//    $("#fecasi_asigabo").datepicker();
//jQuery(function ($) {
//        $.datepicker.regional['es'] = {
//            closeText: 'Cerrar',
//            prevText: '<Ant',
//            nextText: 'Sig>',
//            currentText: 'Hoy',
//            monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
//            monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
//            dayNames: ['Domingo', 'Lunes', 'Martes', 'Mi�rcoles', 'Jueves', 'Viernes', 'S�bado'],
//            dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mi�', 'Juv', 'Vie', 'S�b'],
//            dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'S�'],
//            weekHeader: 'Sm',
//            dateFormat: 'dd/mm/yy',
//            firstDay: 1,
//            isRTL: false,
//            showMonthAfterYear: false,
//            yearSuffix: ''
//            
//        };
//        $.datepicker.setDefaults($.datepicker.regional['es']);
//    });
</script>
