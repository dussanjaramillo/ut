<?php 
// Responsable: Leonardo Molina
$DataRadio  = array ('id' => 'resp1','name'=>'resp1','onclick'=> 'pruebas()','value'=> '1');
$DataRadio1 = array ('id' => 'resp1', 'name'=>'resp1','onclick'=> 'nopruebas()','value'=> '0');
$fecha      = array('name'=>'fecha');
$nis        = array('name'=>'nis');
$radicado   = array('name'=>'radicado');
$file       = array('name'=> 'userfile','id' => 'imagen','class' => 'validate[required]');
$attributes = array('onsubmit' => 'return comprobarextension()', "id" => "myform");
$button     = array('name'=> 'button','id' => 'confirmar','value' => 'Confirmar','disabled'=>'true','type' => 'submit','content' => '<i class="fa fa-cloud-upload fa-lg"></i> Confirmar','class' => 'btn btn-success');
$button2    = array('name'=> 'button','id' => 'submit-button','value' => 'Trasladar a alegatos.','type' => 'submit','content' => '<i class="fa fa-save fa-lg"></i> Trasladar a alegatos','class' => 'btn btn-success');
?>

<?=form_open_multipart("recibirpruebastransalegatos/cargaRespuesta", $attributes);?>
    
<h4 style="color: orange" align="center"><?php echo "Codigo auto de pruebas: ".$codauto." <br>Nit de la empresa: ".$nit?></h4><br>


    <div style="display: block" id="divresp">
        
        
        <table style="width: 250px;">
            <tr>
                <td><?= form_radio($DataRadio)?></td>
                <td style="width: 250px; ">Aporto pruebas.</td>
            </tr>
            <tr>
                <td><?= form_radio($DataRadio1)?></td>
               <td style="width: 250px; ">No presenta pruebas.</td>
               <td style="width: 50px; "></td>
            </tr>
            
            <div id="subirFile" style="margin-left: 50%;float: right; display: none;">
                <p><span id="addImg" class="btn btn-info"><i class="fa fa-cloud-upload"></i> Agregar mas...</span></p>
                <div id="file_source"></div>
                <div class="input-append" id="arch0">
                    <input type="file" name="archivo0" class="btn btn-primary file_uploader">
                </div>

            </div>
            
            <div id="sinpruebas" style="margin-left: 50%;float: right; display: none;">
                <h5 style="color: orangered">La empresa no presenta pruebas.</h5>
            </div>
        </table>
            
        
        <div id="botones" style="margin: 10px; position: fixed">
                <a href="#" class="btn btn-default" data-dismiss="modal" id="cancelar">Cancelar</a>
                <?=form_button($button);?>
        </div>
     
    </div>


    
<?= form_close()?>
        
    
<script>

    function pruebas(){
        $("#confirmar").removeAttr("disabled");
        $("#sinpruebas").css("display", "block");
        $('#sinpruebas').toggle("slow");
        $("#subirFile").css("display", "none");
        $('#subirFile').toggle("slow");
    }
    function nopruebas(){
        $("#confirmar").removeAttr("disabled");
        $("#subirFile").css("display", "block");
        $('#subirFile').toggle("slow");
        $("#sinpruebas").css("display", "none");
        $('#sinpruebas').toggle("slow");
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
        var extensiones_permitidas = new Array(".pdf",".doc");
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
  
  $('#cancelar').on('click', function() {
        $('.modal-body').html('<div align="center"><img src="<?= base_url() ?>/img/27.gif" width="150px" height="150px" /></div>');
        $('#modal').modal('hide').removeData();
    });
    $('.close').on('click', function() {
        $('.modal-body').html('<div align="center"><img src="<?= base_url() ?>/img/27.gif" width="150px" height="150px" /></div>');
        $('#modal').modal('hide').removeData();
    });
    
</script>

