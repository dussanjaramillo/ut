<?php
$tipdoc_elaaud  = array('name'=>'tipdoc_elaaud','class'=>'search-query','id'=>'tipdoc_elaaud','readonly'=>'true','value'=>$regimen->NOMBRETIPODOC);
$razsoc_elaaud  = array('name'=>'razsoc_elaaud','id'=>'razsoc_elaaud','readonly'=>'true','value'=>$regimen->RAZON_SOCIAL,'class'=>'input-xxlarge search-query');
$numdoc_elaaud  = array('name'=>'numdoc_elaaud','class'=>'search-query','id'=>'numdoc_elaaud','readonly'=>'true','value'=>$regimen->NITEMPRESA);
$numexp_elaaud  = array('name'=>'numexp_elaaud','class'=>'search-query','id'=>'numexp_elaaud','readonly'=>'true','value'=>$regimen->NUM_PROCESO);
$nompro_elaaud  = array('name'=>'nompro_elaaud','class'=>'search-query','id'=>'nompro_elaaud','readonly'=>'true','value'=>$regimen->PROMOTOR);
$telefo_elaaud  = array('name'=>'telefo_elaaud','class'=>'search-query','id'=>'telefo_elaaud','readonly'=>'true','value'=>$regimen->TELEFONO);
$regins_elaaud  = array('name'=>'regins_elaaud','class'=>'search-query','id'=>'regins_elaaud','readonly'=>'true','value'=>$regimen->COD_REGIMENINSOLVENCIA,'type'=>'hidden');
$fecha_elaaud   = array('name'=>'fecha_elaaud','id'=>'fecha_elaaud','class'=>'readonly search-query','required'=>true);
$opcion         = array('name'=>'opcion','id'=>'opcion','value'=>'si','type'=>'radio','class'=>'selec_titu','required'=>'true');
$opcion1        = array('name'=>'opcion','id'=>'opcion','value'=>'no','type'=>'radio','class'=>'selec_titu','required'=>'true');
$opcion2        = array('name'=>'opcion1','id'=>'opcion1','value'=>'1','type'=>'radio','required'=>'true');
$opcion3        = array('name'=>'opcion1','id'=>'opcion1','value'=>'2','type'=>'radio','required'=>'true');
$opcion4        = array('name'=>'opcion1','id'=>'opcion1','value'=>'3','type'=>'radio','required'=>'true');
//$buttonPDF      = array('name'=> 'pdf','id'=>'pdf','value'=>'Generar Pdf','content'=>'<i class=""></i> Generar Pdf','class'=>'btn btn-info btn1','disabled'=>'true');
$button         = array('name'=>'guarda_elaaud','id'=>'guarda_elaaud','type'=>'submit','value'=>'Guardar','content'=>'<i class=""></i> Guardar','class'=>'btn btn-success btn1');
$button1        = array('name'=>'cancel_elaaud','id'=>'cancel_elaaud','value'=>'cancelar','content'=>'<i class=""></i> Cancelar','class'=>'btn btn-warning btn1');
$datafile       = array('name'=>'filecolilla','id'=>'filecolilla','value'=>'','maxlength'=>'10','type'=>'file','required'=>'true');
$titulo         = array('name'=>'titulo','id'=>'titulo','readonly'=>'true','value'=>$cod_tit,'type'=>'hidden');
$atributos      = array('name'=>'frmtp','id'=>'frmtp','method'=>'post');
?>
<div style="max-width: 960px;min-width: 320px;padding: 40px;width: 88%;background: none repeat scroll 0 0 #F0F0F0;margin: auto;">    
    <?php echo form_open_multipart("insolvencia/generar_actaaudiencia", $atributos); 
    echo form_input($titulo);
    ?>
    <input type='hidden' value="audiencia" name='tipo_opcion' id='tipo_opcion'>
    <input type="hidden" id='num_fisca' name='num_fisca' value='<?= @$regimen->COD_FISCALIZACION ?>' readonly>
    <table>
        <tr>
            <td colspan='4' align='center'>
                <div align="center"><h3>Cargar acta de Audiencia de Resoluci&oacute;n de objeciones</h3></div>
            </td>
        </tr>
        <tr>
            <td>Tipo de Documento</td><td><?= form_input($tipdoc_elaaud)?><?= form_input($regins_elaaud)?></td>
            <td>Razón Social</td><td><?= form_input($razsoc_elaaud)?></td>
        </tr>
        <tr>
            <td>Número de Documento</td><td><?= form_input($numdoc_elaaud)?></td>
            <td>Número de Expediente</td><td><?= form_input($numexp_elaaud)?></td>
        </tr>
        <tr>
            <td>Nombre Promotor</td><td><?= form_input($nompro_elaaud)?></td>
            <td>Teléfono</td><td><?= form_input($telefo_elaaud)?></td>
        </tr>
    </table>  
    
    <table>
        <tr>
            <td>
    <table>
        <tr>
            <td>Se realizó la Audiencia?</td>
            <td><?= form_checkbox($opcion)?>Si</td>
        </tr>
        <tr>
            <td></td>
            <td><?= form_checkbox($opcion1)?>No</td>
        </tr>
        <tr id="fecha" style="display:none">
            <td>Fecha de la Audiencia</td>
            <td><?= form_input($fecha_elaaud)?></td>
        </tr>
    </table>
            </td>
            <td>
    <table>
        <tr>
<!--            <td rowspan="3" style="border-right-color:#000000; border-right-style:solid; border-right-width:1px;">Objeciones</td>
            <td><?= form_checkbox($opcion2)?></td><td>No se Incluyó la Acreencia del Sena</td>-->
        </tr>
        <tr>
<!--            <td><?= form_checkbox($opcion3)?></td>
            <td>Se Incluyó Pero no Corresponde a la Cantidad que Realmente se le Debe al Sena</td>-->
        </tr>
        <tr>
<!--            <td><?= form_checkbox($opcion4)?></td>
            <td style="border-bottom-color:#000000; border-bottom-style:solid; border-bottom-width:1px;">Se Incluyo Pero no le Dieron la Prelacion Legal que le Corresponde a la Acreencia del Sena</td>-->
        </tr>
    </table>
            </td>
    </tr>
            <tr id='document_memorial' style="display:none">
            <td colspan="4">                                        
            <?php
//            $Cabeza = '<br>
//                <div align="center">
//                <img src="' . base_url('img/Logotipo_SENA.jpg') . '" width="100" height="100" />
//                </div>';
//                $logo  = array('name'=>'logo' ,'id'=>'logo','type'=>'hidden','value'=>$Cabeza);
//            ?>
            <!--<div id="ela_pdf" style="max-width: 870px;min-width: 320px;padding: 40px;width: 88%;background: none repeat scroll 0 0 #F0F0F0;margin: auto;">-->
            <!--<div align="center" style="color: #ffffff;"><h3>Cargar acta de audiencia de resoluci&oacute;n de objeciones</h3></div>-->   

            <!--<br>-->
            <?php 
//            echo form_input($logo);
            ?>
<!--            <textarea name="memorial" id="memorial" style="width: 100%; height: 300px">

            <table style="width: 100%" border="1">
                <tr>
                    <td align="center">Acta Elaborada Por el Apoderado</td>
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
            </textarea>-->
            <table align="center" cellpadding="15%">
                    <tr>
                        <!--<td><?= form_button($buttonPDF)?></td>-->
                    </tr>
                    <tr id='cargarColilla' style="display: none">
                        <td>
                            <div><b>Adjuntar Documento</b></div>
                            <div><?= form_upload($datafile);?></div><br>
                        </td>
                    </tr>
                </table>
            </div>
          </td>
        </tr>
    </table>
    
    <table align="center" cellpadding="15%">
        <tr>
            <td><?= form_button($button)?></td>
            <td><?= form_button($button1)?></td>
            
        </tr>
    </table>
    <?=form_close()?>
</div>
<script type="text/javascript">
    $(document).ready(function() { 
         $(document).ready(function(){
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
        });
        
        
        $('#fecha_elaaud').datepicker({
              dateFormat: "dd/mm/y",
              maxDate: "0"
          });
    })
    
    $('.selec_titu').click(function(){
        $("#pdf").attr('disabled',false);
    });
    
    $(".readonly").keydown(function(e){
        e.preventDefault();
    });
     
    $('input[name="opcion"]').change(function() { 
        if ($('input[name="opcion"]:checked').val() == 'si') {
            $('#fecha').show();
            $('#document_memorial').show();
            $('#cargarColilla').show();  
            $("#fecha_elaaud").attr('required',true);
        } else if ($('input[name="opcion"]:checked').val() == 'no') {
            $('#fecha').hide();
            $('#document_memorial').hide();
            $('#cargarColilla').hide();
            $("#fecha_elaaud").val('');
            $("#fecha_elaaud").attr('required',false);
            $("#filecolilla").attr('required',false);
        } 

    })
    
      
    $('#pdf').on('click',function(){        
        var informacion    = tinymce.get('memorial').getContent();    
        var url = "<?=base_url()?>index.php/insolvencia/pdfRevocatoria";
        $('#frmtp').attr("action", url);
        $('#frmtp').attr('target', '_blank');
        $('#frmtp').submit();
        $('#frmtp').removeAttr('target');
        $('#frmtp').attr("action", "resultado_pruebas");
    }); 
    
</script>

