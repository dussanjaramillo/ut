<?php 
// Responsable: Leonardo Molina
$DataRadio  = array ('id' => 'resp1','name'=>'resp','checked'=>'true','onclick'=> 'pruebas()','value'=> '1');
$DataRadio1 = array ('id' => 'resp1', 'name'=>'resp','onclick'=> 'nopruebas()','value'=> '0');
$fecha      = array('name'=>'fecha');
$nis        = array('name'=>'nis');
$radicado   = array('name'=>'radicado');
$file       = array('name'=> 'userfile','id' => 'imagen','class' => 'validate[required]');
$attributes = array('onsubmit' => 'return comprobarextension()', "id" => "formData");
$button     = array('name'=> 'button','id' => 'confirmar','value' => 'Confirmar','disabled'=>'true','type' => 'submit','content' => '<i class="fa fa-cloud-upload fa-lg"></i> Confirmar','class' => 'btn btn-success');
$button2    = array('name'=> 'button','id' => 'submit-button','value' => 'Generar Auto Prorroga.','type' => 'submit','content' => '<i class="fa fa-save fa-lg"></i> Prorroga','class' => 'btn btn-success');
$nAuto      = array('name'=>'nAuto','id'=>'nAuto','type'=>'hidden','value'=>$respauto->NUM_AUTOGENERADO);
$nit2       = array('name'=>'nit','id'=>'nit','type'=>'hidden','value'=>$empresa->CODEMPRESA);
$datopror   = array('name'=>'datopror','id'=>'datopror','type'=>'hidden');
$pror       = array('name'=>'pror','id'=>'pror','type'=>'text','class' => 'input-small search-query','onkeyup'=>'prorroga(this.id)');
?>

<?=form_open_multipart("autopruebas/recibirPruebaytraslado", $attributes);?>
    
<h4 style="color: orange" align="center"><?php echo "Codigo auto de pruebas: ".$codauto." <br>Nit de la empresa: ".$nit?></h4><br>


    <div style="display: block" id="divresp">
        
        <table style="width: 250px;">
            <tr>
                <td><?= form_radio($DataRadio)?></td>
                <td style="width: 250px; ">Aporto pruebas.</td>
            </tr>
            <tr>
<!--                <td><?= form_radio($DataRadio1)?></td>
               <td style="width: 250px; ">No presenta pruebas.</td>-->
               <td style="width: 50px; "></td>
            </tr>
            </table>
            <div id="subirFile" style="margin-left: 50%;float: right; display: none;">
                
            
            
            <table cellpadding="2%">
                <thead>
                <tr>
                    <th style="width: 200px">Nombre archivo</th>
                    <th style="width: 80px">Procede</th>
                    <th style="width: 80px; ">Eliminar</th>
                    <th><span id="loader" style="display: none; float: right; position: relative"><img src="<?=  base_url()?>/img/27.gif" width="40px" height="40px" /></span></th>
                </tr>
                </thead>
                <tbody id="nomdoc">
                    <tr>
                        <td><a href="<?=base_url()?>uploads/fiscalizaciones/<?=$respauto->COD_FISCALIZACION?>/autocargos/<?=$respauto->NOMBRE_DOCU_RESPUESTA?>" target="_blank"><?=$respauto->NOMBRE_DOCU_RESPUESTA?></a></td>
                    </tr>
                       <?php $i=0;foreach($pruebas as $row){
                           
                           echo '<tr id="tfile'.$i.'" class="trDoc"><td style="width: 200px;"><a href="'.  base_url().'uploads/fiscalizaciones/'.$row->COD_FISCALIZACION.'/autocargos/'.$row->NOMBRE_DOCUMENTO.'" target="_blank">'.$row->NOMBRE_DOCUMENTO.'</a></td>'
                                   . '<td style="width: 80px; text-align: center;"><input type="checkbox" name="docpruebas" values="'.$row->NOMBRE_DOCUMENTO.'"/></td>'
                                   . '<td style="width: 80px; text-align: center;"><button type="button" class="elimArch" onclick="eliminarDD(\''.$row->NOMBRE_DOCUMENTO.'\',\''.$row->COD_FISCALIZACION.'\',\''.$i.'\')"><li class="icon-remove"></li></button></td></tr>';
                            $i++;
                       }
                       ?> 
                </tbody>
            </table>
                
                <p><span id="addImg" class="btn btn-info"><i class="fa fa-cloud-upload"></i> Agregar mas...</span></p>
                
                <div class="input-append" id="arch0">
                    <input type="file" name="archivo0" id="archivo" class="btn btn-primary file_uploader">
                </div>
                <div id="file_source"></div>
            </div>
            
            <div id="sinpruebas" style="margin-left: 50%;float: right; display: none;">
                <h5 style="color: orangered">La empresa no presenta pruebas.</h5>
                
                <?php 
                    if($autogestion->COD_RESPUESTA == '891'){
                        
                    $restante = $autogestion->TERMINO_PRORROGA - $autogestion->RESTANTE;
                        
                        echo '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>'
                        . 'Actualmente existe una prorroga de ('.$autogestion->TERMINO_PRORROGA.' días) generada en la fecha '.$autogestion->FECHA_PRORROGA.'.'
                        . ' Quedan '.$restante.' días.</div>';
                    }
                ?>
                
                
                <h5>(Opcional) Por favor ingrese número de dias para <br>solicitar la prorroga.</h5>
                <table>
                    <tr>
                        <td>Tiempo Prorroga</td><td> <?= form_input($pror);?></td><td> Días.</td>
                    </tr>
                    <tr>
                        <td>
                            <button type="button" href="#" id="autopror" class="btn btn-success" disabled="true" title="Generar Auto Prórroga" data-toggle="modal" data-target="#modalprorroga" data-keyboard="false" data-backdrop="static"><i class="fa fa-file-text-o"></i> Prórroga</button>
                        </td>
                    </tr>
                </table>
            </div>
        <div id="botones" style="margin: 10px; position: fixed">
            <?= form_input($nAuto)?>
            <?= form_input($datopror)?>
                <a href="#" class="btn btn-default" data-dismiss="modal" id="cancelar">Cancelar</a>
                <?=form_button($button);?>
        </div>
        <br><br>
    </div>
<?= form_close()?>
<?= form_input($nit2)?>
<div class="modal hide fade in" id="modalprorroga" style="display: none; width: 60%; margin-left: -30%;">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Generar Auto de Prórroga</h4>
        </div>
        <div class="modal-body2">
            <div align="center">
            <textarea name="textprorroga" id="textinfo" style="width:100%; height: 300px">
            Auto de prorroga.
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

<form id="form" name="form" target = "_blank"  method="post" action="<?php echo base_url('index.php/autopruebas/pdf') ?>">
    <textarea id="informacion" name="informacion" style="width: 100%;height: 300px; display:none"></textarea>  
    <input type="hidden" name="nombre" id="nombre" value="">
</form>
    
<script>


function prorroga(e){
        if($( "#pror" ).val()){
            $('#autopror').attr("disabled", false);
        }
        else{
            $('#autopror').attr("disabled", true);
        }
    }


    function enviar_pdf(){
        if(!$('#pror').val()){
            alert("Ingrese el término de la prórroga.");
        }else{
            var informacion = tinymce.get('textinfo').getContent();
            var nit    = $('#nit').val();
            var nAuto   = $('#nAuto').val();
            var nombre = "AutoPruebas"+nit+"_"+nAuto;
            document.getElementById("nombre").value = nombre;
            document.getElementById("informacion").value = informacion;
            $("#form").submit();
            $("#datopror").val('pro');
            enviar();
        }
    }

    function enviar(){
        alert("Se genera Auto de prórroga.");
         $('#formData').submit();
    }

    function pruebas(){
        $("#confirmar").removeAttr("disabled");
        $("#sinpruebas").css("display", "block");
        $('#sinpruebas').toggle("slow");
        $("#subirFile").css("display", "none");
        $('#subirFile').toggle("slow");
    }
    pruebas();
    function nopruebas(){
        $("#confirmar").removeAttr("disabled");
        $("#subirFile").css("display", "block");
        $('#subirFile').toggle("slow");
        $("#sinpruebas").css("display", "none");
        $('#sinpruebas').toggle("slow");
    }

    $('#pror').keydown(function(e) {    
      if ((e.keyCode < 48 || e.keyCode > 57) && (e.keyCode < 96 || e.keyCode > 105) && e.keyCode != 8 && e.keyCode != 9)  
          e.preventDefault();  
    });

    // funcion para subir varios archivos al servidor
    (function() {
        jQuery("#addImg").on('click',addSource);
    })();

    function addSource(){
        var numItems = jQuery('.file_uploader').length;
        var template =  '<div  class="field" id="arch'+numItems+'">'+
                            '<div class="input-append">'+
                                '<div >'+
                                    '<input type="file" name="archivo'+numItems+'" id="archivo" class="btn btn-primary file_uploader">'+
                                    ' <button type="button" class="close" id="'+numItems+'" onclick="deleteFile(this.id)">&times;</button>'+
                                '</div>'+
                            '</div>'+
                        '</div>';
        jQuery(template).appendTo('#file_source');
        console.log(numItems);
    }
    function deleteFile(elemento){
        $("#arch"+elemento).fadeOut("slow",function(){
            $("#arch"+elemento).remove();
        });
    }
    
    function comprobarextension() {
       
    if ($("#archivo").val() != "") {
        var archivo = $("#archivo").val();
        var extensiones_permitidas = new Array(".pdf",".jpg","jpeg");
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
          jQuery("#archivo").val("");
          mierror = "Comprueba la extensión de los archivos a subir.\nSólo se pueden subir archivos con extensiones: " + extensiones_permitidas.join();
        }
        //si estoy aqui es que no se ha podido submitir
        if (mierror != "") {
          alert(mierror);
          return false;
        }
        return true;
    }else{
        if($("input[name='resp']:checked").val()==1){
         alert("El campo documento, no puede ser vacio en esta opción.");
         return false;
     }
    }
  }
  
  $('#cancelar').on('click', function() {
        $('.modal-body').html('<div align="center"><img src="<?= base_url() ?>/img/27.gif" width="150px" height="150px" /></div>');
        $('#modal').modal('hide').removeData();
    });
    $('.close').on('click', function() {
        $('.modal-body').html('<div align="center"><img src="<?= base_url() ?>/img/27.gif" width="150px" height="150px" /></div>');
        $('#modal').modal('hide').removeData();
    });
    
    function eliminarDD(doc,fisc,tab){
        var res = confirm('Seguro desea eliminar?');        
        if(res==true){
            $("#loader").css("display", "block");
            var file = doc;
            var fisc = fisc;
            var url = "<?= base_url()?>index.php/autopruebas/deleteFile";
//            alert(file+" "+fisc+" "+tab);
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
    
    tinymce.init({
    
    selector: "textarea#textinfo",
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
</script>

