<?php
$tipdoc_ordrem      = array('name'=>'tipdoc_ordrem','class'=>'search-query','id'=>'tipdoc_ordrem','readonly'=>'true','value'=>$regimen->NOMBRETIPODOC);
$numexp_ordrem      = array('name'=>'numexp_ordrem','class'=>'search-query input-small','id'=>'numexp_ordrem','readonly'=>'true','value'=>$regimen->COD_RECEPCIONTITULO);
$numdoc_ordrem      = array('name'=>'numdoc_ordrem','class'=>'search-query','id'=>'numdoc_ordrem','readonly'=>'true','value'=>$regimen->NITEMPRESA);
$num_fiscaliza      = array('name'=>'num_fiscaliza','type'=>'hidden','class'=>'search-query','id'=>'num_fiscaliza','readonly'=>'true','value'=>$regimen->COD_FISCALIZACION);
$razsoc_ordrem      = array('name'=>'razsoc_ordrem','id'=>'razsoc_ordrem','readonly'=>'true','value'=>$regimen->RAZON_SOCIAL,'class'=>'input-xxlarge search-query');
$para_ordrem        = array('name'=>'para_ordrem','class'=>'search-query','id'=>'para_ordrem','type'=>'email','required'=>'true');
$cc_ordrem          = array('name'=>'cc_ordrem','class'=>'search-query','id'=>'cc_ordrem','type'=>'email');
$cco_ordrem         = array('name'=>'cco_ordrem','class'=>'search-query','id'=>'cco_ordrem','type'=>'email');
$asu_ordrem         = array('name'=>'asu_ordrem','class'=>'search-query','id'=>'asu_ordrem','required'=>'true');
$adj_orrem          = array('name'=>'adj_ordrem','class'=>'search-query','id'=>'adj_ordrem','type'=>'file');
$datanotificacion   = array('name'=>'notificacion','id'=> 'notificacion','value'=> set_value(),'width'=>'80%','heigth'=> '150%');
$button             = array('name'=>'enviar_ordrem','id'=>'enviar_ordrem','type'=>'submit','content'=>'<i class=""></i> Enviar','class'=>'btn btn-success btn1');
$button1            = array('name'=>'cancelar_ordrem','id'=>'cancelar_ordrem','type'=>'submit','content'=>'<i class=""></i> Cancelar','class'=>'btn btn-warning btn1','onclick'=>'window.location=\''.base_url().'index.php/insolvencia/ordenarremision\'');
$attributes         = array('onsubmit' => 'return comprobarextension()', "id" => "myform");
?>
<div style="max-width: 886px;min-width: 320px;padding: 30px 40px 500px;width: 88%;background: none repeat scroll 0 0 #F0F0F0;margin: auto;padding-top: 30px;">
    <?=form_open_multipart(base_url('index.php/insolvencia/enviar_remision'), $attributes);?>
    <table>
        <tr>
            <td colspan="4">
                    <center><h3>Ordernar Remisión</h3></center>
            </td>
        </tr>
        <tr>
            <td>Tipo de Documento</td><td><?= form_input($tipdoc_ordrem)?><?= form_input($num_fiscaliza)?></td>
            <td>Número de Expediente</td><td><?= form_input($numexp_ordrem)?></td>
        </tr>
        <tr>
            <td>Número de Documento</td><td><?= form_input($numdoc_ordrem)?></td>
        </tr>
        <tr>
            <td>Razón Social</td><td><?= form_input($razsoc_ordrem)?></td>
        </tr>
    </table>
    <table align="left">
        <tr>
            <td>Para</td><td><?= form_input($para_ordrem)?></td>
        </tr>
        <tr>
            <td>CC</td><td><?= form_input($cc_ordrem)?></td>
        </tr>
        <tr>
            <td>CCO</td><td><?= form_input($cco_ordrem)?></td>
        </tr>
        <tr>
            <td>Asunto</td><td><?= form_input($asu_ordrem)?></td>
        </tr>
        <tr>
            <td>Adjuntar</td>
        </tr>
        <tr>
            <td colspan="2"><div id="subirFile">
            
            <p><span id="addImg" class="btn btn-info"><i class="fa fa-cloud-upload"></i> Agregar mas...</span></p>
            <div class="input-append" id="arch0">
                <div >
                    <input type="file" name="archivo0" id="documento" class="file_uploader">
                </div>
            </div>
            <div id="file_source"></div>
        </div></td>
        </tr>
        <tr>
            <td colspan='2'>
                <?= form_textarea($datanotificacion); ?>
            </td>
        </tr>
        <tr>
            <td align="center">
                <?= form_button($button)?>
                <?= form_button($button1)?>
            </td>
        </tr>
    </table>    
    <?= form_close()?>
</div>
<div style="display:none" id="error" class="alert alert-danger"></div>
<script type="text/javascript" language="javascript" charset="utf-8">    
function comprobarextension() {
    if ($("#documento").val() != "") {
        var archivo = $("#documento").val();
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
    function deleteSource(elemento){
        $("#arch"+elemento).fadeOut("slow",function(){
            $("#arch"+elemento).remove();
        });
    }
    (function() {
        jQuery("#addImg").on('click',addSource);

    })();
</script>

