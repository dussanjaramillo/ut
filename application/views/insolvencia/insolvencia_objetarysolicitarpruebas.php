<?php
$tipdoc_objpru  = array('name'=>'tipdoc_objpru','class'=>'search-query','id'=>'tipdoc_objpru','readonly'=>'true','value'=>$regimen->NOMBRETIPODOC);
$razsoc_objpru  = array('name'=>'razsoc_objpru','id'=>'razsoc_objpru','readonly'=>'true','value'=>$regimen->RAZON_SOCIAL,'class'=>'input-xxlarge search-query');
$numdoc_objpru  = array('name'=>'numdoc_objpru','class'=>'search-query','id'=>'numdoc_objpru','readonly'=>'true','value'=>$regimen->NITEMPRESA);
$numexp_objpru  = array('name'=>'numexp_objpru','class'=>'search-query','id'=>'numexp_objpru','readonly'=>'true','value'=>$regimen->NUM_PROCESO);
$codreg_objpru  = array('name'=>'codreg_objpru','class'=>'search-query','id'=>'codreg_objpru','readonly'=>'true','value'=>$regimen->COD_REGIMENINSOLVENCIA,'type'=>'hidden');
$opcion_objpru  = array('name'=>'opcion','id'=>'opcion','type'=>'radio','value'=>'si','required'=>'true');
$opcion1_objpru = array('name'=>'opcion','id'=>'opcion','type'=>'radio','value'=>'no','required'=>'true');
$buttonPDF      = array('name'=> 'pdf','id'=>'pdf','value'=>'Generar Pdf','content'=>'<i class=""></i> Generar Pdf','class'=>'btn btn-info btn1','disabled'=>'true');
$button         = array('name'=>'acepta_objpru','id'=>'acepta_objpru','type'=>'submit','value'=>'Aceptar','content'=>'<i class=""></i> Aceptar','class'=>'btn btn-success btn1');
$button1        = array('name'=>'cancel_objpru','id'=>'cancel_objpru','value'=>'Cancelar','content'=>'<i class=""></i> Cancelar','class'=>'btn btn-warning btn1','onclick'=>'window.location=\''.base_url().'index.php/insolvencia/objetarpruebas\'');
$datafile       = array('name'=>'filecolilla','id'=>'filecolilla','value'=>'','maxlength'=>'10','type'=>'file','required'=>'true');
$motivo1        = array('name'=>'motivo[]', 'id'=>'motivo1','type'=>'checkbox','value'=>'no_incluye','class'=>'selec_titu');
$motivo2        = array('name'=>'motivo[]', 'id'=>'motivo2','type'=>'checkbox','value'=>'no_corresponde','class'=>'selec_titu');
$motivo3        = array('name'=>'motivo[]', 'id'=>'motivo3','type'=>'checkbox','value'=>'no_prelacion','class'=>'selec_titu');

$tipomotivo     = array('name'=>'tipomotivo', 'id'=>'tipomotivo', 'type'=>'hidden');
$atributos      = array('name'=>'frmtp','id'=>'frmtp','method'=>'post');
?>
<div style="max-width: 1050px;min-width: 320px;padding: 40px;width: 88%;background: none repeat scroll 0 0 #F0F0F0;margin: auto;">
<?php echo form_open_multipart("insolvencia/resultado_pruebas", $atributos); 
    echo form_input($tipomotivo);
?>
    <input type="hidden" id='num_fisca' name='num_fisca' value='<?= @$regimen->COD_FISCALIZACION ?>' readonly>
    <table>
        <tr>
            <td colspan="4">
                <div align="center"><h3>Elaborar Memorial de Objeción y Solicitud de Pruebas</h3></div>
            </td>
        </tr>
        <tr>
            <td>Tipo de Documento</td><td><?= form_input($tipdoc_objpru)?></td>
            <td>Razón Social</td><td> <?= form_input($razsoc_objpru)?></td>
        </tr>
        <tr>
            <td>Número de Documento</td><td><?= form_input($numdoc_objpru)?></td>
            <td>Número de Expediente</td><td><?= form_input($numexp_objpru)?><?= form_input($codreg_objpru)?></td>
        </tr>   
        <tr>
            <td>Elaborar Memorial de Objeción y Solicitud de Pruebas</td><td><?= form_checkbox($opcion_objpru)?>Si</td><td><?= form_checkbox($opcion1_objpru)?>No</td>
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
            <div id="ela_pdf" style="max-width: 870px;min-width: 320px;padding: 40px;width: 88%;background: none repeat scroll 0 0 #F0F0F0;margin: auto;">
                <!--<div align="center" style="color: #ffffff;"><h3>Resultado de La Objeci&oacute;n</h3></div>-->   

            <table>
                <tr>
                    <td rowspan="3" style="border-right-color:#000000; border-right-style:solid; border-right-width:1px;">Motivo</td><td><?= form_checkbox($motivo1)?>-</td><td>No se Incluyo la Acreencia del Sena </td>
                </tr>
                <tr>
                    <td><?= form_checkbox($motivo2)?>-</td><td>Se incluyó pero no corresponde a la cantidad que realmente se debe al SENA</td>
                </tr>
                <tr>
                    <td><?= form_checkbox($motivo3)?>-</td><td>Se incluyó pero no le dieron la prelación legal que le corresponde a la acreencia del SENA. </td>
                </tr>
            </table>
            <br><?= form_input($logo)?>
            <textarea name="memorial" id="memorial" style="width: 100%; height: 300px">

            <table style="width: 100%" border="1">
                <tr>
                    <td align="center">Memorial Elaborado Por el Apoderado</td>
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
    <?= form_close()?>
</div>
<script>
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
  
    $('.selec_titu').click(function(){
        $("#pdf").attr('disabled',false);
    });
      
  
  $('input[name="opcion"]').change(function() {
      var boton = $('input[name="opcion"]:checked').val();
      if (boton == 'si'){
          $('#document_memorial').show();
          $('#cargarColilla').show();    
      }else {
          $('#document_memorial').hide();
          $('#cargarColilla').hide();
          $("#filecolilla").attr('required',false);
          $('#opcion').val('');
      }
  }); 
  
    $('#pdf').on('click',function(){        
        var informacion    = tinymce.get('memorial').getContent();       
        var url = "<?=base_url()?>index.php/insolvencia/pdfRevocatoria";
        $('#frmtp').attr("action", url);
        $('#frmtp').attr('target', '_blank');
        $('#frmtp').submit();
        $('#frmtp').removeAttr('target');
        $('#frmtp').attr("action", "resultado_pruebas");
    }); 
    
     $('#acepta_objpru').click(function(){
        tinyMCE.triggerSave();                
        var checkboxValues = "";
        $('input[name="motivo[]"]:checked').each(function() {
                checkboxValues += $(this).val() + "-";
        });
        //eliminamos la última coma.
        checkboxValues = checkboxValues.substring(0, checkboxValues.length-1);
        $('#tipomotivo').val(checkboxValues);
        //si todos los checkbox están seleccionados devuelve 1,2,3,4,5
    }) 
        
</script>    
