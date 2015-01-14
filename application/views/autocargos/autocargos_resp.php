<?php 
// Responsable: Leonardo Molina
$DataRadio  = array ('id' => 'resp1','name'=>'resp1','onclick'=> 'contesta()','value'=> 'contesta');
$DataRadio1 = array ('id' => 'resp1', 'name'=>'resp1','onclick'=> 'nocontesta()','value'=> 'nocontesta');
$fecha      = array('name'=>'fecha');
$nis        = array('name'=>'nis');
$radicado   = array('name'=>'radicado');
$file       = array('name'=> 'userfile','id' => 'imagen','class' => 'validate[required]');
$attributes = array('onsubmit' => 'return comprobarextension()', "id" => "myform");
$button     = array('name'=> 'button','id' => 'submit-button','value' => 'Confirmar','type' => 'submit','content' => '<i class="fa fa-cloud-upload fa-lg"></i> Confirmar','class' => 'btn btn-success');
$button2    = array('name'=> 'button','id' => 'submit-button','value' => 'Trasladar a alegatos.','type' => 'submit','content' => '<i class="fa fa-save fa-lg"></i> Trasladar a alegatos','class' => 'btn btn-success');
?>

<?=form_open_multipart("autocargos/cargaRespuesta", $attributes);?>

    <div>
        <p>Seleccione una opción según sea el caso.</p>
        <p>
            <?= form_radio($DataRadio).'Contesta';?>
            <?= form_radio($DataRadio1).'No Contesta';?>
        </p>
    </div><br>
    
    <div style="display: none" id="divalegato">
        <div class="modal-footer">
            <a href="#" class="btn btn-default" data-dismiss="modal">Cancelar</a>
            <?=form_button($button2);?>
        </div>
    </div>
    
    <div style="display: none" id="divresp">
        <div>
        <div id="subirFile" style="float: right; position: relative; width: 50%">
            <div id="file_source"></div>
            <div class="input-append" id="arch0">
                <div >
                    <input type="file" name="archivo0" class="btn btn-primary file_uploader">
                </div>
            </div>
            <span id="addImg" class="btn btn-info"><i class="fa fa-cloud-upload"></i> Agregar mas...</span>
        </div>
        <table>
            <tr>
                <td><?= form_radio('resp','acuerdo',false)?></td>
                <td style="width: 100px; ">Contesta y esta de acuerdo</td>
                <td><p>Fecha</p> <?=  form_input($fecha);?> </td>
            </tr>
            <tr>
                <td><?= form_radio('resp','noacuerdo',false)?></td>
               <td style="width: 100px; ">Contesta y NO esta de acuerdo</td>
               <td><p>Nis</p> <?=  form_input($nis);?></td>
               <td style="width: 50px; "></td>
                
            </tr>
            <tr>
                <td><?= form_radio('resp','pruebas',false)?></td>
              <td style="width: 100px; ">Contesta con Pruebas</td>
              <td><p>No. Radicado</p> <?=  form_input($radicado);?></td><td></td>  
            </tr>
        </table>
        
        </div>
                
        
               
        <div class="modal-footer">
            <a href="#" class="btn btn-default" data-dismiss="modal">Cancelar</a>
            <?=form_button($button);?>
        </div>
    </div>

    
<?= form_close()?>
        
    
<script>

    function contesta(){
        $("#divalegato").css("display", "block");
        $('#divalegato').toggle("slow");
        $("#divresp").css("display", "none");
        $('#divresp').toggle("slow");
    }
    function nocontesta(){
        $("#divresp").css("display", "block");
        $('#divresp').toggle("slow");
        $("#divalegato").css("display", "none");
        $('#divalegato').toggle("slow");
    }

    // funcion para subir varios archivos al servidor
    (function() {
        jQuery("#addImg").on('click',addSource);
    })();

    function addSource(){
        var numItems = jQuery('.file_uploader').length;
        var template =  '<div  class="field" id="arch'+numItems+'">'+
                            '<div class="input-append">'+
                                '<div >'+
                                    '<input type="file" name="archivo'+numItems+'" id="archivo'+numItems+'"class="btn btn-primary file_uploader">'+
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
    if ($("#imagen").val() != "") {
        var archivo = $("#imagen").val();
        var extensiones_permitidas = new Array(".pdf");
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
          jQuery("#imagen").val("");
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
    
</script>

