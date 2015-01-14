<?php 
// Responsable: Leonardo Molina
$DataRadio  = array('name'=> 'resp1','id' => 'resp1','onclick'=> 'contesta()','value'=> '92');
$DataRadio1 = array('name'=> 'resp1','id' => 'resp1', 'onclick'=> 'nocontesta()','value'=> '93');
$fecha      = array('name'=> 'fecha','readonly'=>'readonly','id'=>'fecha','required'=>'required','class'=>'input-medium');
$nis        = array('name'=> 'nis','required'=>'required','class'=>'input-medium','maxlength'=>"10");
$radicado   = array('name'=> 'radicado','required'=>'required','class'=>'input-medium','maxlength'=>"10");
$ndoc       = array('name'=> 'ndoc','required'=>'required','class'=>'input-medium');
$tdoc       = array('name'=> 'tdoc','required'=>'required','class'=>'input-medium');
$npresenta  = array('name'=> 'npresenta','required'=>'required','class'=>'input-medium');
$file       = array('name'=> 'userfile','id' => 'imagen','class' => 'validate[required]');
$button     = array('name'=> 'button','id' => 'submit-button','value' => 'Confirmar','type' => 'submit','content' => '<i class="fa fa-cloud-upload fa-lg"></i> Confirmar','class' => 'btn btn-success');
$button2    = array('name'=> 'button','id' => 'submit-button','value' => 'Trasladar a alegatos.','type' => 'submit','content' => '<i class="fa fa-save fa-lg"></i> Trasladar a alegatos','class' => 'btn btn-success');
$numauto    = array('name'=> 'numauto','name'=>'numauto','type'=>'hidden','value'=>$codauto);
$attributes = array('onsubmit' => 'return comprobarextension()', "id" => "myform", 'class'=>'validar_form');

?>

<?=form_open_multipart("autopruebas/cargaRespuesta", $attributes);?>

    <div>
        <p>Seleccione una opción según sea el caso.</p>
        <p>
            <?= form_radio($DataRadio).'Contesta';?>
            <?= form_radio($DataRadio1).'No Contesta';?>
        </p>
    </div>
    
    <div style="display: none" id="divalegato">
        <div class="modal-footer">
            <a href="#" class="btn btn-default cancelar" data-dismiss="modal">Cancelar</a>
            <button type="button" href="<?=base_url()?>index.php/autopruebas/newTraslado/<?=$respauto->NUM_AUTOGENERADO?>/<?=$respauto->CODEMPRESA?>" style="width:135px; text-align:left;" id="autoalega" class="btn btn-small"  title="Traslado Alegatos" data-toggle="modal" data-target="#generarPdfAlegatos" data-keyboard="false" data-backdrop="static"><i class="fa fa-file-text-o"></i> Traslado Alegatos</button>
        </div>
    </div>
    
    <div style="display: none" id="divresp">
        <div style="height: auto;" >
        <div id="subirFile" style="float: right; position: relative; width: 50%; height: auto;">
            <!--<p><span id="addImg" class="btn btn-info btn1"><i class="fa fa-cloud-upload"></i> Agregar mas...</span></p>-->
            <p><i class="fa fa-cloud-upload"> <input type="button" id="addImg" class="btn btn-info btn1" value="Agregar mas..." border="0" /></i></p>
            <p>Agregue el documento de descargos, para poder subir las pruebas.</p>
            <div class="input-append" id="arch0">
                <input type="file" name="archivo0" id="archivo0" onchange="habboton()" class="btn btn-primary file_uploader">
            </div>
            <div id="file_source"></div>
            <div class="modal-footer" style="position: static;">
                <?=  form_input($numauto);?>
                <a href="#" class="btn btn-default cancelar" data-dismiss="modal" >Cancelar</a>
                <?=form_button($button);?>
            </div>
        </div>
        <table>
            <tr>
                <td><?= form_radio('resp','94',false)?></td>
                <td style="width: 100px; ">Contesta y esta de acuerdo</td>
                <td><p>Fecha</p> <?=  form_input($fecha);?> </td>
            </tr>
            <tr>
                <td><?= form_radio('resp','95',false)?></td>
               <td style="width: 100px; ">Contesta y NO esta de acuerdo</td>
               <td><p>Nis</p> <?=  form_input($nis);?></td>
               <td style="width: 50px; "></td>
                
            </tr>
            <tr>
                <td><?= form_radio('resp','96',false)?></td>
              <td style="width: 100px; ">Contesta con Pruebas</td>
              <td><p>No. Radicado</p> <?=  form_input($radicado);?></td><td></td>  
            </tr>
            <tr>
                <td></td><td></td>
                <td><p>Nombre Presenta</p> <?=  form_input($npresenta);?></td>
            </tr>
            
            <tr>
                <td></td><td><p>Cargo:</p></td>
                <td><select class="form-control input-medium" name="cargo" id="cargo">
                    <option>Seleccione Cargo</option>
                    <option value="representante">Representante Legal</option>
                    <option value="apoderado">Apoderado</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td></td><td><p>Seleccione:</p><select class="form-control input-medium" name="tdoc" id="respons">
                    <option>Tipo Documento</option>
                <?php foreach($tipoDocumento as $row){echo '<option value="'.$row->CODTIPODOCUMENTO.'">'.$row->NOMBRETIPODOC.'</option>';}?></select></td>
                <td><p>Numero Documento</p> <?=  form_input($ndoc);?></td>
            </tr>
        </table>
        
        </div>
        <br>
        
    </div>

    
<?= form_close()?>
        
    
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



<script>
    $(".validar_form").submit( function(){
        var validation=0;
            var select = $("#respons").val();
            var cargo  = $("#cargo").val();
            if(select == "Tipo Documento"){
                alert("Debe seleccionar un Tipo de documento");
                validation++;
            }
            if(cargo == "Seleccione Cargo"){
                alert("Debe seleccionar un Cargo");
                validation++;
            }
            if(validation == 2){
                return false;
            }else return true;
        });
    $(function (){
        $("#fecha").datepicker();
        $("#archivo0").attr('disabled',true);
    });
    function habboton(){
        $("#addImg").attr('disabled',false);
    }
    
    function contesta(){
        $("#divalegato").css("display", "block");
        $('#divalegato').toggle("slow");
        $("#divresp").css("display", "none");
        $('#divresp').toggle("slow");
        $("#addImg").attr('disabled',true);
    }
    function nocontesta(){
        $("#divresp").css("display", "block");
        $('#divresp').toggle("slow");
        $("#divalegato").css("display", "none");
        $('#divalegato').toggle("slow");
    }
    
    
    
    
    $('input[name="resp"]').change(function(){
            if($(this).is(":checked")) {
                var h=$("input[name='resp']:checked").val();
//                if(h==96){
                    $("#archivo0").attr('disabled',false);
//                }else{
//                    $("#archivo0").attr('disabled',true);
//                }
              }
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
  
    $('.cancelar').on('click', function() {
        $('.modal-body').html('<div align="center"><img src="<?= base_url() ?>/img/27.gif" width="150px" height="150px" /></div>');
        $('#modal').modal('hide').removeData();
    });
    $('.close').on('click', function() {
        $('.modal-body').html('<div align="center"><img src="<?= base_url() ?>/img/27.gif" width="150px" height="150px" /></div>');
        $('#modal').modal('hide').removeData();
    });
    
    jQuery(function (cash) {
        $.datepicker.regional['es'] = {
            closeText: 'Cerrar',
            prevText: '<Ant',
            nextText: 'Sig>',
            currentText: 'Hoy',
            monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            dayNames: ['Domingo', 'Lunes', 'Martes', 'Mi rcoles', 'Jueves', 'Viernes', 'S bado'],
            dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mi ', 'Juv', 'Vie', 'S b'],
            dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'S '],
            weekHeader: 'Sm',
            dateFormat: 'dd/mm/yy',
            firstDay: 1,
            isRTL: false,
            showMonthAfterYear: false,
            yearSuffix: ''
            //maxDate: '+0m +0w'
        };
        $.datepicker.setDefaults($.datepicker.regional['es']);
    });
</script>

