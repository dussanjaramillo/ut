<?php 
// Responsable: Leonardo Molina
$DataRadio  = array ('id' => 'resp1','name'=>'resp1','onclick'=> 'generar()','value'=> 'Generar');
$DataRadio1 = array ('id' => 'resp1', 'name'=>'resp1','onclick'=> 'cargar()','value'=> 'Cargar');
$DataRadio2 = array ('id' => 'resp1', 'name'=>'resp1','onclick'=> 'EnviarDocumento()','value'=> 'Enviar');
$fecha      = array('name'=>'fecha');
$nis        = array('name'=>'nis');
$radicado   = array('name'=>'radicado');
$file       = array('name'=> 'userfile','id' => 'imagen','class' => 'validate[required]');
$attributes = array("id" => "formData1"); 
$attributes2= array('onsubmit' => 'return comprobarextension()', "id" => "myform", "onkeypress"=>"return event.keyCode!=13");
$button     = array('name'=> 'button','id' => 'autopruebas','value' => 'Continuar','disabled' => 'true', 'type' => 'button','content' => '<i class="fa fa-pencil"></i> Continuar','class' => 'btn btn-success');
$button2    = array('name'=> 'button','id' => 'submit-button','value' => 'Guardar','type' => 'submit','content' => '<i class="fa fa-save fa-lg"></i> Guardar','class' => 'btn btn-success');
$button3    = array('name'=> 'button','id' => 'enviarDocumento','value' => 'Enviar Documento','onclick' => 'enviar_pdf_documento()','type' => 'button','content' => '<i class="fa fa-pencil"></i> Enviar Documento','class' => 'btn btn-success');

    $Cabeza = '<br>
    <div align="center">
    <img src="' . base_url('img/Logotipo_SENA.jpg') . '" width="100" height="100" />
    </div>';
    $nAuto = array('name'=>'nAuto' ,'id'=>'nAuto','type'=>'hidden','value'=>$respauto->NUM_AUTOGENERADO);
    $nAuto2= array('name'=>'nAuto2','id'=>'nAuto2','type'=>'hidden','value'=>$respauto->NUM_AUTOGENERADO);
    $nit   = array('name'=>'nit'   ,'id'=>'nit','type'=>'hidden','value'=>$empresa->CODEMPRESA); //echo $data['NOMBRE_CONCEPTO;
    $logo  = array('name'=>'logo'  ,'id'=>'logo','type'=>'hidden','value'=>$Cabeza);
    $cfisc = array('name'=>'cfisc' ,'id'=>'cfisc','type'=>'hidden','value'=>$respauto->COD_FISCALIZACION);
?>


<?= form_open(base_url('index.php/autopruebas/generarAutoPruebas'),$attributes)?>

    <?= form_input($nAuto)?>
    <?= form_input($nit)?>
    <?= form_input($logo)?>
    <?= form_input($cfisc)?>

        <h4 style="color: orange" align="center"><?php echo "Codigo auto: ".$codauto." <br>Nit de la empresa: ".$empresa->CODEMPRESA?></h4><br>
        <div>
                <p>Seleccione una opción según sea el caso.</p>
                <p><?php 
                    if($accion==1){ ?>
                    <?= form_radio($DataRadio).' Generar auto de pruebas.';?>
                    <?= form_radio($DataRadio2).' Generar documento Envio.';?>
                    <?php }
                    if($accion==2){
                    echo form_radio($DataRadio1).' Cargar documentos firmados.';
                    }
                    ?>
                </p>
        </div><br><br>
            <div style="display: none" id="divresp">
                <div id="content" style="position: relative; width: 50%;">
<!--                    <table style="width: 250px;" cellpadding="2%">
                        <tr>
                            <td><?= form_radio('resp','pruebas',false)?></td>
                            <td style="width: 250px; ">Pruebas solicitadas por el SENA.</td>
                        </tr>
                        <tr>
                            <td><?= form_radio('resp','alegato',false)?></td>
                            <td style="width: 250px; ">Traslado alegato.</td>
                        </tr>
                    </table>-->
                    <p><br></p>
                    <p><br></p>
                </div>
                <div id="documentos" style="float: right; margin-top: -100px;">
<!--                    <p><input type="file" name="nomarch" id="nomarch" class="file" onkeypress="return pulsar(event)" placeholder="Ingrese nombre documento"/>
                        <span id="addArch" class="btn btn-info"><i class="fa fa-cloud-upload"></i> Guardar</span>
                    <span id="loader" style="display: none; float: right; position: relative"><img src="<?=  base_url()?>/img/27.gif" width="40px" height="40px" /></span>
                    </p>-->
                    <table cellpadding="2%">
                        <thead>
                        <tr>
                            <th style="width: 200px">Nombre archivo</th>
<!--                            <th style="width: 80px">Procede</th>
                            <th style="width: 80px">Eliminar</th>-->
                        </tr>
                        </thead>
                        <tbody id="nomdoc">
<!--                            <tr>
                                <td><a href="<?=base_url()?>uploads/fiscalizaciones/<?=$respauto->COD_FISCALIZACION?>/autocargos/<?=$respauto->NOMBRE_DOCU_RESPUESTA?>" target="_blank"><?=$respauto->NOMBRE_DOCU_RESPUESTA?></a></td>
                            </tr>-->
                               <?php $i=0;foreach($pruebas as $row){

                                   echo '<tr id="tfile'.$i.'" class="trDoc"><td style="width: 200px;" align="center"><a href="'.  base_url().'uploads/fiscalizaciones/'.$row->COD_FISCALIZACION.'/autocargos/'.$row->NOMBRE_DOCUMENTO.'" target="_blank">'.$row->NOMBRE_DOCUMENTO.'</a></td>';
//                                           . '<td style="width: 80px; text-align: center;"><input type="checkbox" name="docpruebas" values="'.$row->NOMBRE_DOCUMENTO.'"/></td>'
//                                           . '<td style="width: 80px; text-align: center;"><button type="button" class="elimArch" onclick="eliminarDD(\''.$row->NOMBRE_DOCUMENTO.'\',\''.$row->COD_FISCALIZACION.'\',\''.$i.'\')"><li class="icon-remove"></li></button></td></tr>';
                                    $i++;
                               }
                               ?> 
                        </tbody>
                    </table>
                </div>
                <br><br>
                <div class="modal-footer" style="position: fixed">
                    <a href="#" class="btn btn-default cancelar" data-dismiss="modal" >Cancelar</a>
                    <!--<button type="button" href="<?=base_url()?>index.php/autopruebas/newTraslado/<?=$respauto->NUM_AUTOGENERADO?>/<?=$empresa->CODEMPRESA?>" style="width:135px; text-align:left;" id="autoalega" disabled="true" class="btn btn-small"  title="Traslado Alegatos" data-toggle="modal" data-target="#generarPdfAlegatos" data-keyboard="false" data-backdrop="static"><i class="fa fa-file-text-o"></i> Traslado Alegatos</button>-->
                    <?=form_button($button);?>
                </div>
                <br><br><br>

            </div>

            <!--Modales para la generacion de documentos-->

            <div class="modal hide fade in" id="modalpruebas" style="display: none; width: 60%; margin-left: -30%;">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <h4 class="modal-title">Generar Auto de Pruebas</h4>
                </div>
                <div class="modal-body2">
                    <div align="center">
                    <textarea name="textpruebas" id="textinfo2" style="width:100%; height: 300px">

        <p>&nbsp;<strong>&ldquo;Por el cual se decretan pruebas</strong>&rdquo;</p>
        <p>El Director del Servicio Nacional de Aprendizaje SENA Regional Distrito Capital, en uso de sus facultades legales y en especial las conferidas 
        por los art&iacute;culos 47 y subsiguientes del C&oacute;digo de Procedimiento Administrativo y de lo Contencioso Administrativo, 
        con el prop&oacute;sito de adelantar procedimiento sancionatorio contra la empresa <?=$empresa->NOMBRE_EMPRESA?><strong> NIT. <?=$empresa->CODEMPRESA?></strong>
        domiciliada en <?=$empresa->NOM_DEPARTAMENTO?> en la direcci&oacute;n: <?=$empresa->DIRECCION?>.</p>
        <p>&nbsp;</p>
        <p><strong>Decreta:</strong> que de acuerdo con el art&iacute;culo 48 C&oacute;digo de Procedimiento Administrativo y de lo Contencioso Administrativo en el t&eacute;rmino de diez (10) d&iacute;as h&aacute;biles la parte recurrente allegue a este despacho:</p>
        <p>&nbsp;</p>
        <div id='cargaDocumentosPruebas'></div>
        <p>&nbsp;</p>
        <p>_________________________________________________________________________________</p>
        <p>El presente Auto se expide a los&nbsp;&nbsp;</p>
        <p>&nbsp;</p>
        <p><strong>COMUN&Iacute;QUESE Y C&Uacute;MPLASE</strong></p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p><strong>JAIME GARCIA DIMOTOLI</strong></p>
        <p>Director Regional (E) Distrito capital</p>
        <p>&nbsp;</p>
        <p>Revis&oacute;: xxxxxxxxxxxxxxxx</p>
        <p>Proyect&oacute;: xxxxxxxxx</p>
        <p><strong>&nbsp;</strong></p>
        <p><strong>&nbsp;</strong></p>
        <p><strong>NOTA: SE ELIJE EL FORMATO AMARILLO SI UNA VEZ PRESENTADOS LOS DESCARGOS SE CONSIDERA PERTINENTE EL DECRETO DE LAS PRUEBAS SOLICITADAS O 
        DE OFICIO EN CASO DE SER PERTINENTES, CONDUCENTES Y &Uacute;TILES PARA EL CASO QUE NOS OCUPA.</strong></p>
        <p><strong>&nbsp;</strong></p>
        <p><strong>SI NO SE DECRETARON PRUEBAS SE PROCEDE A DAR TRASLADO PARA ALEGAR, SELECCIONANDO EL FORMATO SEG&Uacute;N CORRESPONDA AL CASO EN EL CUAL 
        PRESENTARON LOS DESCARGOS O NO.</strong> &ldquo;Por el cual se decretan pruebas&rdquo; El Director del Servicio Nacional de Aprendizaje SENA 
        Regional Distrito Capital, en uso de sus facultades legales y en especial las conferidas por los art&iacute;culos 47 y subsiguientes 
        del C&oacute;digo de Procedimiento Administrativo y de lo Contencioso Administrativo, con el prop&oacute;sito de adelantar procedimiento 
        sancionatorio contra la empresa <?=$empresa->NOMBRE_EMPRESA?> con NIT. <?=$empresa->CODEMPRESA?> domiciliada en <?=$empresa->NOM_DEPARTAMENTO?> en la direcci&oacute;n: <?=$empresa->DIRECCION?>.
        Decreta: que de acuerdo 
        con el art&iacute;culo 48 C&oacute;digo de Procedimiento Administrativo y de lo Contencioso Administrativo en el t&eacute;rmino de diez (10) 
        d&iacute;as h&aacute;biles la parte recurrente allegue a este despacho: 
        <div id='cargaDocumentosPruebas'></div>
        <p>&nbsp;</p>
        _________________________________________________________________________________ 
        El presente Auto se expide a los COMUN&Iacute;QUESE Y C&Uacute;MPLASE JAIME GARCIA DIMOTOLI Director Regional (E) 
        Distrito capital Revis&oacute;: xxxxxxxxxxxxxxxx Proyect&oacute;: xxxxxxxxx 
        NOTA: SE ELIJE EL FORMATO AMARILLO SI UNA VEZ PRESENTADOS LOS DESCARGOS SE CONSIDERA PERTINENTE EL DECRETO DE LAS PRUEBAS SOLICITADAS 
        O DE OFICIO EN CASO DE SER PERTINENTES, CONDUCENTES Y &Uacute;TILES PARA EL CASO QUE NOS OCUPA. SI NO SE DECRETARON PRUEBAS SE PROCEDE 
        A DAR TRASLADO PARA ALEGAR, SELECCIONANDO EL FORMATO SEG&Uacute;N CORRESPONDA AL CASO EN EL CUAL PRESENTARON LOS DESCARGOS O NO.</p>
                    </textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-default" id="cancelarprueba" data-dismiss="modal">Cancelar</a>
                    <a href="#" class="btn btn-primary" id="generarPdf" onclick="enviar_pdf()"><li class="fa fa-download"></li> Descargar Auto</a>
                </div>
              </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
          </div><!-- /.modal -->
  <?= form_close();?>
  
  <div class="modal hide fade in" id="generarPdfAlegatos" style="display: none; width: 60%; margin-left: -30%;">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Traslado Alegatos</h4>
        </div>
        <div class="modal-body">
            <div align="center">
                <img src="<?=  base_url()?>/img/27.gif" width="150px" height="150px" />  
            </div>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
  

  
  
       <!-- Div para subir el auto de pruebas firmado -->
<?=form_open_multipart("autopruebas/addPruebasFirm", $attributes2);?>
       <div  id="divcarg" style="display: none">  
           <table>
               <tr>
                   <td>Documento Firmado de auto de pruebas</td>
                   <td>
                       <div id="subirFile" style="float: center; ">
                            <div class="input-append" id="arch0">
                                <input type="file" name="archivo0" id='documento' class="btn btn-primary file_uploader">
                            </div>
                        </div>
                   </td>
               </tr>
               <tr>
                   <td>Documento Radicado de pruebas</td>
                   <td>
                       <div id="subirFile" style="float: center; ">
                            <div class="input-append" id="arch0">
                                <input type="file" name="archivo1" id='documento1' class="btn btn-primary file_uploader">
                            </div>
                        </div>
                   </td>
               </tr>
               <tr>
                   <td>Colilla de envio</td>
                   <td>
                       <div id="subirFile" style="float: center; ">
                            <div class="input-append" id="arch0">
                                <input type="file" name="archivo2" id='documento2' class="btn btn-primary file_uploader">
                            </div>
                        </div>
                   </td>
               </tr>
           </table>
    
    <div class="modal-footer" style="position: inherit">
            <?= form_input($nAuto2)?>
            <a href="#" class="btn btn-default cancelar" data-dismiss="modal">Cancelar</a>
            <?=form_button($button2);?>
        </div>
    </div>
  <?= form_close()?>
  
<form id="form" name="form" target = "_blank"  method="post" action="<?php echo base_url('index.php/autopruebas/pdf') ?>">
    <textarea id="informacion" name="informacion" style="width: 100%;height: 300px; display:none"></textarea>  
    <input type="hidden" name="nombre" id="nombre" value="">
</form>
       
       
       <div id="divenviar" style="display: none;">
            <textarea name="textinfo3" id="textinfo3" style="width:100%; height: 300px;">
                <p>&nbsp;<strong>&ldquo;Por el cual se decretan pruebas</strong>&rdquo;</p>
        <p>El Director del Servicio Nacional de Aprendizaje SENA Regional Distrito Capital, en uso de sus facultades legales y en especial las conferidas 
        por los art&iacute;culos 47 y subsiguientes del C&oacute;digo de Procedimiento Administrativo y de lo Contencioso Administrativo, 
        con el prop&oacute;sito de adelantar procedimiento sancionatorio contra la empresa <?=$empresa->NOMBRE_EMPRESA?><strong> NIT. <?=$empresa->CODEMPRESA?></strong>
        domiciliada en <?=$empresa->NOM_DEPARTAMENTO?> en la direcci&oacute;n: <?=$empresa->DIRECCION?>.</p>
        <p>&nbsp;</p>
        <p><strong>Decreta:</strong> que de acuerdo con el art&iacute;culo 48 C&oacute;digo de Procedimiento Administrativo y de lo Contencioso Administrativo en el t&eacute;rmino de diez (10) d&iacute;as h&aacute;biles la parte recurrente allegue a este despacho:</p>
        <p>&nbsp;</p>
        <div id='cargaDocumentosPruebas'></div>
        <p>&nbsp;</p>
        <p>_________________________________________________________________________________</p>
        <p>El presente Auto se expide a los&nbsp;&nbsp;</p>
        <p>&nbsp;</p>
        <p><strong>COMUN&Iacute;QUESE Y C&Uacute;MPLASE</strong></p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p><strong>JAIME GARCIA DIMOTOLI</strong></p>
        <p>Director Regional (E) Distrito capital</p>
        <p>&nbsp;</p>
        <p>Revis&oacute;: xxxxxxxxxxxxxxxx</p>
        <p>Proyect&oacute;: xxxxxxxxx</p>
        <p><strong>&nbsp;</strong></p>
        <p><strong>&nbsp;</strong></p>
        <p><strong>NOTA: SE ELIJE EL FORMATO AMARILLO SI UNA VEZ PRESENTADOS LOS DESCARGOS SE CONSIDERA PERTINENTE EL DECRETO DE LAS PRUEBAS SOLICITADAS O 
        DE OFICIO EN CASO DE SER PERTINENTES, CONDUCENTES Y &Uacute;TILES PARA EL CASO QUE NOS OCUPA.</strong></p>
        <p><strong>&nbsp;</strong></p>
        <p><strong>SI NO SE DECRETARON PRUEBAS SE PROCEDE A DAR TRASLADO PARA ALEGAR, SELECCIONANDO EL FORMATO SEG&Uacute;N CORRESPONDA AL CASO EN EL CUAL 
        PRESENTARON LOS DESCARGOS O NO.</strong> &ldquo;Por el cual se decretan pruebas&rdquo; El Director del Servicio Nacional de Aprendizaje SENA 
        Regional Distrito Capital, en uso de sus facultades legales y en especial las conferidas por los art&iacute;culos 47 y subsiguientes 
        del C&oacute;digo de Procedimiento Administrativo y de lo Contencioso Administrativo, con el prop&oacute;sito de adelantar procedimiento 
        sancionatorio contra la empresa <?=$empresa->NOMBRE_EMPRESA?> con NIT. <?=$empresa->CODEMPRESA?> domiciliada en <?=$empresa->NOM_DEPARTAMENTO?> en la direcci&oacute;n: <?=$empresa->DIRECCION?>.
        Decreta: que de acuerdo 
        con el art&iacute;culo 48 C&oacute;digo de Procedimiento Administrativo y de lo Contencioso Administrativo en el t&eacute;rmino de diez (10) 
        d&iacute;as h&aacute;biles la parte recurrente allegue a este despacho: 
        <div id='cargaDocumentosPruebas'></div>
        <p>&nbsp;</p>
        _________________________________________________________________________________ 
        El presente Auto se expide a los COMUN&Iacute;QUESE Y C&Uacute;MPLASE JAIME GARCIA DIMOTOLI Director Regional (E) 
        Distrito capital Revis&oacute;: xxxxxxxxxxxxxxxx Proyect&oacute;: xxxxxxxxx 
        NOTA: SE ELIJE EL FORMATO AMARILLO SI UNA VEZ PRESENTADOS LOS DESCARGOS SE CONSIDERA PERTINENTE EL DECRETO DE LAS PRUEBAS SOLICITADAS 
        O DE OFICIO EN CASO DE SER PERTINENTES, CONDUCENTES Y &Uacute;TILES PARA EL CASO QUE NOS OCUPA. SI NO SE DECRETARON PRUEBAS SE PROCEDE 
        A DAR TRASLADO PARA ALEGAR, SELECCIONANDO EL FORMATO SEG&Uacute;N CORRESPONDA AL CASO EN EL CUAL PRESENTARON LOS DESCARGOS O NO.</p>
            </textarea>
           <div class="modal-footer">
               <?=form_button($button3);?>
           </div>
           
       </div>
      
      
  
       
  
<script>

function pulsar(e) { 
  tecla = (document.all) ? e.keyCode :e.which; 
  return (tecla!=13); 
} 

function enviar_pdf(){
    
            var informacion = tinymce.get('textinfo2').getContent();
            var nit    = $('#nit').val();
            var nAuto   = $('#nAuto').val();
            var nombre = "AutoPruebas"+nit+"_"+nAuto;
            document.getElementById("nombre").value = nombre;
            document.getElementById("informacion").value = informacion;
            $("#form").submit();
            enviar();
        }
        
        function enviar(){
            alert("Se genera Auto de pruebas.");
             $('#formData1').submit();
        }
        
        function enviar_pdf_documento(){
    
            var informacion = tinymce.get('textinfo3').getContent();
            var nit    = $('#nit').val();
            var nAuto   = $('#nAuto').val();
            var nombre = "EnvioDocPruebas"+nit+"_"+nAuto;
            document.getElementById("nombre").value = nombre;
            document.getElementById("informacion").value = informacion;
            $("#form").submit();
            var url = "<?php echo base_url('index.php/autopruebas/trazabilidad_total') ?>";
            var cod_fis='<?=$cod_fis?>';
            var nit=$('#nit').val();
            var codauto='<?=$codauto?>';
            $(".preload, .load").show();
            $.post(url,{cod_fis:cod_fis,nit:nit,cod_resolucion:codauto,informacion:informacion})
                    .done(function(){
                       alert('Los Datos Fueron Guardados con Exito') 
               window.location.reload();
//               $(".preload, .load").hide();
                    }).fail(function(){
                        $(".preload, .load").hide();
                                alert('Datos no Guardados') 
                    })
        }


        function cargar(){
            $("#divresp").css("display", "block");
            $('#divresp').toggle("slow");
            $("#divenviar").css("display", "block");
            $('#divenviar').toggle("slow");
            $("#divcarg").css("display", "none");
            $('#divcarg').toggle("slow");
        }
        function generar(){
            $("#divcarg").css("display", "block");
            $('#divcarg').toggle("slow");
            $("#divenviar").css("display", "block");
            $('#divenviar').toggle("slow");
            $("#divresp").css("display", "none");
            $('#divresp').toggle("slow");
        }
        function EnviarDocumento(){
            $("#divenviar").css("display", "none");
            $('#divenviar').toggle("slow"); 
            $("#divcarg").css("display", "block");
            $('#divcarg').toggle("slow");
            $("#divresp").css("display", "block");
            $('#divresp').toggle("slow");
        }
        
        
        
    
        $( "input[name='resp']" ).change(function() {

            if($("input[name='resp']:checked").val()){
                      if($("input[name='resp']:checked").val()== 'pruebas'){

                          $('#autopruebas').attr('disabled',false);
                          $('#autoalega').attr('disabled',true);
                      }else{

                          $('#autoalega').attr('disabled',false);
                          $('#autopruebas').attr('disabled',true);
                      }
                  }
        });
    
    
    $("#autopruebas").on('click',function (){
        
        if($("input[name='resp']:checked").val()){
            if($("input[name='resp']:checked").val()== 'pruebas'){
                var checkboxValues = "";
                var $i=1;
                $('input[name="docpruebas1[]"]').each(function() {
                checkboxValues += $i+". "+$(this).val() + "<br>";
                $i++;
                });
                $("#cargaDocumentosPruebas").html(checkboxValues);
                var ed = tinyMCE.get('textinfo2');
                ed.setContent($("#textinfo2").val().replace("<div id='cargaDocumentosPruebas'></div>", checkboxValues));
                $('#modalpruebas').modal('show');
            }else{
                $('#modalalegatos').modal('show');
            }
        }else alert("Seleccione una opción.");
        
    });
    
    $("#trasAlega").on('click',function(){
        
        $('#modal').modal('hide').removeData();
        $('#modalalegatos').modal('show');
    });
    
    // funcion para subir varios archivos al servidor
    (function() {
        jQuery("#addImg").on('click',addSource);
        jQuery("#addArch").on('click',addFile);
    })();

    function addSource(){
        var numItems = jQuery('.file_uploader').length;
        var template =  '<div  class="field" id="arch'+numItems+'">'+
                            '<div class="input-append">'+
                                '<div >'+
                                    '<input type="file" name="archivo'+numItems+'" id="archivo'+numItems+'"class="btn btn-primary file_uploader">'+
                                    ' <button type="button" class="close" id="'+numItems+'" onclick="deleteSource(this.id)">&times;</button>'+
                                '</div>'+
                            '</div>'+
                        '</div>';
        jQuery(template).appendTo('#file_source');
        console.log(numItems);
    }
    function addFile(){
        if($('#nomarch').val()!= false){
        var numItems = jQuery('.file').length;
        var template = '<tr id="narch'+numItems+'" class="file">'+
                        '<td style="width: 200px">'+$("#nomarch").val()+'</td>'+
                        '<td style="width: 80px; text-align: center;"><input type="checkbox" name="docpruebas1[]" value="'+$("#nomarch").val()+'" /></td>'+
                        '<td style="width: 80px; text-align: center;"><button type="button" id="'+numItems+'" onclick="deleteFile(this.id)"><li class="icon-remove"></li></button></td>'+
                        '</tr>';

        jQuery(template).appendTo('#nomdoc');
        console.log(numItems);
        
        $('#nomarch').val('');
    }
    
    }
    function deleteSource(elemento){
        $("#arch"+elemento).fadeOut("slow",function(){
            $("#arch"+elemento).remove();
        });
    }
    function deleteFile(elemento){
        $("#narch"+elemento).fadeOut("slow",function(){
            $("#narch"+elemento).remove();
        });
    }
    function comprobarextension() {
    if ($("#documento").val() != "") {
        var archivo = $("#documento").val();
        var extensiones_permitidas = new Array(".pdf",".jpg",".jpeg");
        var mierror = "";
        //recupero la extensión de este nombre de archivo
        var extension = (archivo.substring(archivo.lastIndexOf("."))).toLowerCase();
        //alert (extension);
        //compruebo si la extensión está entre las permitidas
        var permitida = false;
        for (var i = 0; i < extensiones_permitidas.length; i++) {
            if (extensiones_permitidas[i] == extension) {
              permitida = true;
              break;
            }
        }
        if (!permitida) {
          jQuery("#documento").val("");
          mierror = "Comprueba la extensión de los archivos a subir.\nSólo se pueden subir archivos con extensiones: " + extensiones_permitidas.join();
        }
        //si estoy aqui es que no se ha podido submitir
        if (mierror != "") {
          alert(mierror);
          return false;
        }
        return true;
    }
  }
  
    $('.cancelar').on('click', function() {
        $('.modal-body').html('<div align="center"><img src="<?= base_url() ?>/img/27.gif" width="150px" height="150px" /></div>');
        $('#modal').modal('hide').removeData();
        $('#modalalegatos').modal('hide').removeData();
        $('#modalpruebas').modal('hide').removeData();
    });
    
    $('#cancelarprueba').on('click', function() {
        //$('#modalpruebas').modal('hide');
        $('#modalpruebas').modal('hide').removeData();
    });
        
    $('.modal').on('hidden', function () {
        $('.modal-body').html('<div align="center"><img src="<?= base_url() ?>/img/27.gif" width="150px" height="150px" /></div>');
        $('#modal').modal('hide').removeData();
    });
    
 
    
    function eliminarDD(doc,fisc,tab){
        var res = confirm('Seguro desea eliminar el documento?');        
        if(res==true){
            $("#loader").css("display", "block");
            var file = doc;
            var fisc = fisc;
            var url = "<?= base_url()?>index.php/autopruebas/deleteFile";
            $.ajax({
                url: url,
                data: { "file" : file, "fisc": fisc},
                type: 'POST' 
            }).done(function() {
                $("#loader").css('display','none');
                $("#tfile"+tab).fadeOut("slow",function(){
                $("#tfile"+tab).remove();
                });
                
            });
        }
    }
    
    
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
     
    tinymce.init({
    
    selector: "textarea#textinfo2,#textinfo3",
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
     
//     tinymce.init({
//    
//    selector: "textarea#textinfo3",
//    theme:    "modern",
// plugins: [
//  "advlist autolink lists link image charmap print preview hr anchor pagebreak",
//  "searchreplace wordcount visualblocks visualchars code fullscreen",
//  "insertdatetime media nonbreaking save table contextmenu directionality",
//  "emoticons template paste textcolor moxiemanager"
// ],
// toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
// toolbar2: "print preview media | forecolor backcolor emoticons",
// image_advtab: true,
// templates: [
//  {title: 'Test template 1', content: '<b>Test 1</b>'},
//  {title: 'Test template 2', content: '<em>Test 2</em>'}
// ],
// autosave_ask_before_unload: false
//     });
$('#autopruebas').hide();
</script>