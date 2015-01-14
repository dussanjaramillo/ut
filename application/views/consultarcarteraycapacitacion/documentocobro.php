<!-- Responsable: Leonardo Molina-->
<?php
    $Cabeza = '<br>
    <div align="center">
    <img src="' . base_url('img/Logotipo_SENA.jpg') . '" width="100" height="100" />
    </div>';
    
    $nit  = array('name'=>'nit'  ,'id'=>'nit','type'=>'hidden','value'=>$registros->NIT_EMPRESA); //echo $data['NOMBRE_CONCEPTO;
    $logo = array('name'=>'logo' ,'id'=>'logo','type'=>'hidden','value'=>$Cabeza);
    $cfisc= array('name'=>'cfisc','id'=>'cfisc','type'=>'hidden','value'=>$registros->COD_FISCALIZACION);
    $num_resol= array('name'=>'num_resol','id'=>'num_resol','type'=>'hidden','value'=>$registros->NUMERO_RESOLUCION);
    $cresp= array('name'=>'cresp','id'=>'cresp','type'=>'hidden','value'=>$registros->COD_RESPUESTA);
    $nejec= array('name'=>'nejec','id'=>'nejec','type'=>'hidden','value'=>$registros->COD_EJECUTORIA);
    
    
    $dnit = $registros->NIT_EMPRESA;
    
?>

<?=$Cabeza?>  
<!-- 102- 186-->
<?php if($registros->COD_RESPUESTA != '186'){?>


<div id="documPersuasivo">
<textarea name="content" id="informacion" style="width:100%; height: 300px">
<p>Se&ntilde;or</p>
<p>NOMBRE</p>
<p>CARGO (si aplica)</p>
<p>RAZ&Oacute;N SOCIAL (si aplica)</p>
<p>NIT/ C&eacute;dula</p>
<p>&nbsp;</p>
<p>Respetado se&ntilde;or:</p>
<p>Le informamos que contamos con un t&iacute;tulo ejecutivo en firme (especificar el acto que lo profiri&oacute;- acto administrativo o liquidaci&oacute;n de la deuda) en donde consta que el aportante (especificar nombre del aportante) le adeuda al subsistema XXXX de la Protecci&oacute;n Social, aportes por un valor de XXXX m&aacute;s los intereses que se generen hasta la fecha del pago. Este valor corresponde a los periodos XXXX a XXXX.</p>
<p>Es por esto que le recordamos que debe realizar el pago inmediatamente a trav&eacute;s de la Planilla</p>
<p>Lo invitamos a cancelar y as&iacute; evitar el inicio del (Cobro administrativo Coactivo o judicial) correspondiente, el cual le generar&iacute;a un costo adicional al final del proceso as&iacute; como la pr&aacute;ctica de medidas cautelares como el embargo, secuestro y remate de los bienes o activos patrimoniales.</p>
<p>Cordial Saludo,</p>
<p>&nbsp;</p>
<p>Integrada de Liquidaci&oacute;n de Aportes PILA.</p>
<p>&nbsp;</p>
<p>Nombre</p>
<p>Indicar Administradora</p>
<p>Direcci&oacute;n</p>
<p>Tel&eacute;fono</p>
<p>&nbsp;</p>
    </textarea>
<div class="modal-footer">
        <a href="#" class="btn btn-default" id="cancelar" data-dismiss="modal">Cancelar</a>
        <a href="#" class="btn btn-primary" id="generarPdf"><li class="fa fa-download"></li> Descargar documento</a>
    </div>
     <div id="mensaje" style="display: none;">Documento de cobro generado!</div>
</div>

<?php }?>

<br>

<div id="carguePersuasivo" style="display: none">
    <?= form_open_multipart(base_url('index.php/consultarcarteraycapacitacion/addDocPersuasivo'))?>
    <table>
        <tr>
            <td>Cargue de archivo</td>
            <td><input type="file" name="archivo0" id='documento1' class="btn btn-primary file_uploader"></td>
        </tr>
    </table>
    
    <div class="modal-footer">
        <a href="#" class="btn btn-default" id="cancelar" data-dismiss="modal">Cancelar</a>
        <button type="sybmit" class="btn btn-primary" id="persuasivo"><li class="fa fa-save"></li> Guardar cambios.</button>
    </div>
    <?= form_input($nit)?>
    <?= form_input($logo)?>
    <?= form_input($cfisc)?>
    <?= form_input($num_resol)?>
    <?= form_input($cresp)?>
    <?= form_input($nejec)?>
    <?= form_close()?>
</div>

    <script type="text/javascript">
        
 tinymce.init({
    
    selector: "textarea#informacion",
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
    
      $(document).ready(function() {
          if($('#cresp').val()=='186'){
            $('#carguePersuasivo').css('display','block');
            
        }  
      });
    
    function redirect_by_post(purl, pparameters, in_new_tab) {
            pparameters = (typeof pparameters == 'undefined') ? {} : pparameters;
            in_new_tab = (typeof in_new_tab == 'undefined') ? true : in_new_tab;
            var form = document.createElement("form");
            $(form).attr("id", "reg-form").attr("name", "reg-form").attr("action", purl).attr("method", "post").attr("enctype", "multipart/form-data");
            if (in_new_tab) {
                $(form).attr("target", "_blank");
            }
            $.each(pparameters, function(key) {
                $(form).append('<textarea name="' + key + '" >'+this+'</textarea>');
            });
            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
            return false;
        }
     
        $('#generarPdf').on('click',function(){
            var informacion = tinymce.get('informacion').getContent();
            var nit    = $('#nit').val();
            var logo   = $('#logo').val();
            var cfisc  = $('#cfisc').val();
            var cresp  = $('#cresp').val();
            var nejec  = $('#nejec').val();
            var num_resol  = $('#num_resol').val();
            var nombre = "DocCobro_"+nit;
            informacion = logo+informacion;
            var url    = "<?=base_url()?>index.php/consultarcarteraycapacitacion/pdf";
            redirect_by_post(url, {
               informacion: informacion,
               nombre: nombre,
               cfisc: cfisc,
               nit: nit,
               cresp:cresp,
               nejec:nejec,
               num_resol:num_resol
            }, true);
            //$('#modal').modal('hide').removeData();
            //$("#mensaje").dialog({width: 200, height: 100, show: "scale", hide: "scale", resizable: "false", position: "center", modal: "true"});
            //location.href="<?base_url('index.php/consultarcarteraycapacitacion')?>";
            $('#carguePersuasivo').css('display','block');
            $('#documPersuasivo').css('display','none');
        });
        
        $('#cancelar').on('click', function() {
        $('.modal-body').html('<div align="center"><img src="<?= base_url() ?>/img/27.gif" width="150px" height="150px" /></div>');
        $('#modal').modal('hide').removeData();
    });
    $('.close').on('click', function() {
        $('.modal-body').html('<div align="center"><img src="<?= base_url() ?>/img/27.gif" width="150px" height="150px" /></div>');
        $('#modal').modal('hide').removeData();
    });
    </script>
</form>
        

