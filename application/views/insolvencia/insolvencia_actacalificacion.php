<?php
$tipdoc_actreo  = array('name'=>'tipdoc_actreo','class'=>'search-query','id'=>'tipdoc_actreo','value'=>$regimen->NOMBRETIPODOC,'readonly'=>'true');
$razsoc_actreo  = array('name'=>'razsoc_actreo','id'=>'razsoc_actreo','value'=>$regimen->RAZON_SOCIAL,'class'=>'input-xxlarge search-query','readonly'=>'true');
$numdic_actreo  = array('name'=>'numdic_actreo','class'=>'search-query','id'=>'numdic_actreo','value'=>$regimen->CODEMPRESA,'readonly'=>'true');
$numexp_actreo  = array('name'=>'numexp_actreo','class'=>'search-query','id'=>'numexp_actreo','value'=>$regimen->COD_RECEPCIONTITULO,'readonly'=>'true');
$numpro_actreo  = array('name'=>'numpro_actreo','class'=>'search-query','id'=>'numpro_actreo','value'=>$regimen->PROMOTOR,'readonly'=>'true');
$telefo_actreo  = array('name'=>'telefo_actreo','class'=>'search-query','id'=>'telefo_actreo','value'=>$regimen->TELEFONO,'readonly'=>'true');
$regino_actreo  = array('name'=>'regino_actreo','class'=>'search-query','id'=>'regino_actreo','value'=>$regimen->COD_REGIMENINSOLVENCIA,'type'=>'hidden');
$fiscalizacion  = array('name'=>'fiscalizacion','class'=>'search-query','id'=>'fiscalizacion','value'=>$regimen->COD_FISCALIZACION,'type'=>'hidden','readonly'=>'true');
$fecha_actreo   = array('name'=>'fecha_actreo','class'=>'search-query input-small','id'=>'fecha_actreo','onkeypress'=>'return prueba(event)','disabled'=>'true');
$valobl_actreo  = array('name'=>'valobl_actreo','class'=>'search-query','id'=>'valobl_actreo','disabled'=>'true');
$formpag_actreo = array('name'=>'formpag_actreo','class'=>'search-query','id'=>'formpag_actreo','disabled'=>'true');
$fecpag_actreo  = array('name'=>'fecpag_actreo','class'=>'search-query input-small','id'=>'fecpag_actreo','disabled'=>'true','onkeypress'=>'return prueba(event)');
$opcion         = array('name'=>'opcion','id'=>'opcion','value'=>'S','type'=>'radio','class'=>'realizar','required'=>'true');
$opcion1        = array('name'=>'opcion','id'=>'opcion','value'=>'N','type'=>'radio','class'=>'realizar','required'=>'true');
$opcion2        = array('name'=>'opcion1','id'=>'opcion1','value'=>'S','type'=>'radio','disabled'=>'true');
$opcion3        = array('name'=>'opcion1','id'=>'opcion1','value'=>'N','type'=>'radio','disabled'=>'true');
$buttonPDF      = array('name'=> 'pdf','id'=>'pdf','value'=>'Generar Pdf','content'=>'<i class=""></i> Generar Pdf','class'=>'btn btn-info btn1');
$button         = array('name'=> 'guarda_actreo','id'=>'guarda_actreo','type'=>'submit','value'=>'Guardar','content'=>'<i class=""></i> Guardar','class'=>'btn btn-success btn1');
$button1        = array('name'=> 'cancel_actreo','id'=>'cancel_actreo','value'=>'cancelar','content'=>'<i class=""></i> Cancelar','class'=>'btn btn-warning btn1','onclick'=>'window.location=\''.base_url().'index.php/insolvencia/index\'');
$atributos      = array('name'=>'frmtp','id'=>'frmtp','method'=>'post');
?>
<div style="max-width: 950px;min-width: 320px;padding: 40px;width: 108%;background: none repeat scroll 0 0 #F0F0F0;margin: auto;">
    <?= form_open(base_url('index.php/insolvencia/guardar_acta_reorganizaion'),$atributos);
        echo form_input($fiscalizacion);
        ?>
           <table>
                <tr>
                    <td colspan="4">
                        <div align="center"><h3>Acta de Audiencia para Proyectar Calificación y Graduación de Creditos y derechos de Voto</h3></div>
                    </td>
                </tr>
                <tr>
                    <td> Tipo de Docuemnto</td><td><?= form_input($tipdoc_actreo)?></td>
                    <td>Razón Social</td><td><?= form_input($razsoc_actreo)?><?= form_input($regino_actreo)?></td>
                </tr>
                <tr>
                    <td>Número de Documento</td><td><?= form_input($numdic_actreo)?></td>
                    <td>Número de Expediente</td><td><?= form_input($numexp_actreo)?></td>
                </tr>
                <tr>
                    <td>Nombre Promotor</td><td><?= form_input($numpro_actreo)?></td>
                    <td>Teléfono</td><td><?= form_input($telefo_actreo)?></td>
                </tr>
            </table>
            <table>
                <tr>
                    <td>
                        <table cellpadding="15%">
                            <tr>
                                <td>Se Realizó la Audiencia?</td>
                            </tr> 
                             <tr>
                                <td>Fecha de la Audiencia</td>
                            </tr> 
                             <tr>
                                 <td>Fecha de Pago</td>
                                <!--<td>Se Realizó la Lectura del Acta?</td>-->
                            </tr> 
                        </table>
                    </td>
                    <td>
                        <table cellpadding="5%">
                    <tr>
                        <td style="border-top-color:#000000; border-top-style:solid; border-top-width:1px;border-right-color:#000000; border-right-style:solid; border-right-width:1px;border-left-color:#000000; border-left-style:solid; border-left-width:1px;"><?= form_checkbox($opcion)?>Si</td>
                    </tr>
                    <tr>
                        <td style="border-bottom-color:#000000; border-bottom-style:solid; border-bottom-width:1px;border-right-color:#000000; border-right-style:solid; border-right-width:1px;border-left-color:#000000; border-left-style:solid; border-left-width:1px;"><?= form_checkbox($opcion1)?>No</td>
                    </tr>
                    <tr>
                        <td><?= form_input($fecha_actreo)?></td>
                    </tr>
                    <tr>
                        <td><?= form_input($fecpag_actreo)?></td>
                        <!--<td style="border-top-color:#000000; border-top-style:solid; border-top-width:1px;border-right-color:#000000; border-right-style:solid; border-right-width:1px;border-left-color:#000000; border-left-style:solid; border-left-width:1px;"><?= form_checkbox($opcion2)?>Si</td>-->
                    </tr>
                    <tr>
                        <!--<td style="border-bottom-color:#000000; border-bottom-style:solid; border-bottom-width:1px;border-right-color:#000000; border-right-style:solid; border-right-width:1px;border-left-color:#000000; border-left-style:solid; border-left-width:1px;"><?= form_checkbox($opcion3)?>No</td>-->
                    </tr>
                        </table>
                    </td>
                    <td>
                         <table>
                            <tr>
                                <td>Motivo</td><td><textarea id="motivo" name="motivo" required='true'></textarea></td>
                            </tr>
                            <tr>
                                <td>Valor Obligación</td><td><?= form_input($valobl_actreo)?></td>
                            </tr>
                            <tr>
                                <td>Forma de Pago</td><td><?= form_input($formpag_actreo)?></td>
                            </tr>
                            <tr>
                                <!--<td>Fecha de Pago</td><td><?= form_input($fecpag_actreo)?></td>-->
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr id='document_memorial' style="display:none">
                    <td colspan="4">                                        
                    <?php
                    $Cabeza = '<br>
                        <div align="center">
                        <img src="' . base_url('img/Logotipo_SENA.jpg') . '" width="100" height="100" />
                        </div>';
                        $logo  = array('name'=>'logo' ,'id'=>'logo','type'=>'hidden','value'=>$Cabeza);
                    ?>
                    <div id="ela_pdf" style="max-width: 870px;min-width: 320px;padding: 40px;width: 108%;background: none repeat scroll 0 0 #F0F0F0;margin: auto;">            
                        <div align="center" style="color: #ffffff;"><h3>Cargar acta de Audiencia</h3></div>   
                    <br><?= form_input($logo)?>
                    <textarea name="memorial" id="memorial" style="width: 100%; height: 300px">

                    <table style="width: 100%" border="1">
                        <tr>
                            <td align="center">Acta Elaborado Por el Apoderado</td>
                        </tr>
                        <tr>
                            <td align="left">Número del Proceso:</td>
                        </tr>
                        <tr>
                            <td align="left">Promotor:</td>
                        </tr>
                        <tr>
                            <td align="left">Valor de la Acreencia:</td>
                        </tr>
                        </table>
                    </textarea>
                    <table align="center" cellpadding="15%">
                            <tr>
                                <td><?= form_button($buttonPDF)?></td>
                            </tr>
                        </table>
                    </div>
                  </td>
                </tr>
            </table>
            <table cellpadding="15%" align="center">
                <tr>
                    <td><?= form_button($button)?></td>
                    <td><?= form_button($button1)?></td>
                </tr>
            </table>
    <?= form_close()?>
</div>
<script type="text/javascript" language="javascript" charset="utf-8"> 
     $(document).ready(function(){
        tinymce.init({    
            selector: "textarea#memorial",
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
    });
    
    
    $('.realizar').change(function(){            
            if($(this).is(":checked")) {
                var h=$("input[name='opcion']:checked").val();
                if(h=="S"){
                    //$("input[name='opcion']").attr('disabled',false); 
                    $('#document_memorial').show();
                    $("input[name='opcion1']").attr('disabled',false); 
                    $("#fecpag_actreo").attr('disabled',false);
                    $("#formpag_actreo").attr('disabled',false);
                    $("#valobl_actreo").attr('disabled',false);
                    $("#fecha_actreo").attr('disabled',false);
                    $('#fecha_actreo').attr('required',true);
                    $('#opcion1').attr('required',true);
                    $('#valobl_actreo').attr('required',true);
                    $('#formpag_actreo').attr('required',true);
                    $('#fecpag_actreo').attr('required',true);
                }
                else{
                   //$("input[name='opcion']").attr('disabled',true); 
                   $("input[name='opcion1']").attr('disabled',true);
                   $("#fecpag_actreo").attr('disabled',true);
                   $("#formpag_actreo").attr('disabled',true);
                   $("#valobl_actreo").attr('disabled',true);
                   $("#fecha_actreo").attr('disabled',true);
                   $('#document_memorial').hide();
                }
                
                }
             });
     
     $('#pdf').on('click',function(){        
        var informacion    = tinymce.get('memorial').getContent();       
        var url = "<?=base_url()?>index.php/insolvencia/pdfRevocatoria";
        $('#frmtp').attr("action", url);
        $('#frmtp').attr('target', '_blank');
        $('#frmtp').submit();
        $('#frmtp').removeAttr('target');
        $('#frmtp').attr("action", "guardar_acta_reorganizaion");
        $('#pdf').attr('disabled',true);        
            tinymce.init({    
            selector: "textarea#memorial",
            readonly: 1            
            });
    });
    
    function prueba(e){
         tecla = (document.all) ? e.keyCode : e.which; 
    if (tecla==8) return true; // backspace
    if (tecla==32) return true; // espacio
    if (e.ctrlKey && tecla==86) { return true;} //Ctrl v
    if (e.ctrlKey && tecla==67) { return true;} //Ctrl c
    if (e.ctrlKey && tecla==88) { return true;} //Ctrl x
 
    patron = /[a-zA-Z]/; //patron
 
    te = String.fromCharCode(tecla); 
    return patron.test(te); // prueba de patron
    }
    $("#fecha_actreo").datepicker();
    $("#fecpag_actreo").datepicker();
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
