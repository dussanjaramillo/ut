
<?php
if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<!-- <div class="center-form"> -->
        <?php
        $attributes = array('id' => 'actaFrm', 'name' => 'actaFrm','class' => 'form-inline', 'method' => 'POST', 'enctype' => 'multipart/form-data', );
        echo form_open_multipart(current_url(),$attributes); 
        echo "<center>$titulo</center>";        
        ?>       
        <div class="center-form-large-20">
        <br>
        <br>
        <table cellspacing="0" cellspading="0" border="0" align="center">
        <tr>
            <td>
            <p>
            <input type="hidden" name="vRuta" id="vRuta">
            <input type="hidden" name="vUbicacion" id="vUbicacion">
            <input type="hidden" name="mandamiento" id="mandamiento">
            <input type="hidden" name="idmandamiento" id="idmandamiento" value="<?php echo $post['clave'];?>">
            <input type="hidden" name="nit" id="nit" value="<?php echo $informacion[0]['CODEMPRESA'];?>">
            <input type="hidden" name="cod_coactivo" id="cod_coactivo" value="<?php echo $cod_coactivo ?>">
            <input type='hidden' value='<?php echo $user->IDUSUARIO ?>' name='iduser' id='iduser'>
            <input type="hidden" name="tipo" id="tipo" value="<?php echo $tipo ?>">
            </td>
        </tr>
        <tr>
            <?php
            $this->load->view('mandamientopago/cabecera',$this->data); 
            ?>
        </tr>
        </table>
        <br>
        <br>
                <table cellspacing="0" cellspading="0" border="0" align="center" width="55%">
        <tr>
            <td>
            <p>
            <?php
                echo form_label('Presenta recurso', 'recurso')."<br>";
                $datarecurso = array(
                    'name'        => 'recurso',
                    'id'          => 'recurso',
                    'checked'     => FALSE,
                    'style'       => 'margin:10px'
                    );
                //echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                echo "&nbsp;".form_radio($datarecurso,"1")."&nbsp;&nbsp;SI<br>";
                echo "&nbsp;".form_radio($datarecurso,"0")."&nbsp;&nbsp;NO";

                echo form_error('pago','<div>','</div>');
            ?>
            </p>    
            </td>
        </tr>        
        <tr>
            <td colspan="2">
            <p>
            <?php
                echo form_label('Observaci&oacute;n&nbsp;&nbsp;', 'observacion');
                $data = array(
                            'name'        => 'observacion',
                            'id'          => 'observacion',
                            'value'       => set_value('observacion'),
                            'style'        => 'width:99%'
                          );

                echo "&nbsp;&nbsp;".form_textarea($data);
                echo form_error('observacion','<div><b><font color="red">','</font></b></div>');
            ?>
            </p>
            </td>
        </tr>
        <tr>
            <td align="center" colspan="2">
            <p>
                <div style="display:none" id="error" class="alert alert-danger"></div>
            </p>
            <p>
            <?php 
            $dataguardar = array(
                   'name' => 'button',
                   'id' => 'guardar',
                   'value' => 'Guardar',
                   'type' => 'button',
                   'content' => '<i class="fa fa-floppy-o fa-lg"></i> Guardar',
                   'class' => 'btn btn-success'
                   );
            echo form_button($dataguardar)."&nbsp;&nbsp;";
            $datacancel = array(
                   'name' => 'button',
                   'id' => 'cancel-button',
                   'value' => 'Cancelar',
                   'type' => 'button',
                   'onclick' => 'window.location=\''.base_url().'index.php/bandejaunificada/procesos\';',
                   'content' => '<i class="fa fa-undo"></i> Cancelar',
                   'class' => 'btn btn-warning'
                   );
            echo form_button($datacancel);
            ?>
            </p>
            </td>
        </tr>
        </table>
        <?php echo form_close(); ?>

</div>

<div id="ajax_load" class="ajax_load" style="display: none">
        <div class="preload" id="preload" >
            <img  id="load" class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
        </div>
</div>
                
<script type="text/javascript">   
$('#observacion').keyup(function() {
    var max_chars = 100;
    var chars = $(this).val().length;
    var diff = max_chars - chars;
    if (diff <= 0){
        $('#error').show();
        $('#error').html('Excedio el limite de carácteres en el campo comentarios');
        $('#guardar').attr('disabled',true);   
    }else{
        $('#guardar').attr('disabled',false); 
        $('#error').hide();
    }
}); 

function char_especiales(string){
    var comentarios = string.replace(/[^a-zA-Z 0-9.]+/g,' ');
    $('#observacion').val(comentarios);
}   

      $('#guardar').click(function() {
        window.history.forward(-1);
        $('#error').html("");
        tinyMCE.triggerSave();
        var recurso = $('input[name="recurso"]').is(":checked");
        var observacion = $('#observacion').val();        
        var tipo = $('#tipo').val();
        char_especiales(observacion);
        var url = '<?php echo base_url('index.php/mandamientopago/')?>';
        if (tipo == 'recurso'){
            url = url+'/recurso';
        }
        var valida = true;
        if (recurso == false) {
            $('#error').show();
            $('#error').html("<font color='red'><b>Seleccione Presenta recurso</b></font>");
            valida = false;
        }else if (observacion == "") {
            $('#error').show();
            $('#error').html("<font color='red'><b>Ingrese una Observaci&oacute;n</b></font>");
            valida = false;
        }

        if (valida == true) {
            $(".ajax_load").show("slow");
            $('#guardar').prop("disabled",true);
            $("#actaFrm").attr("action", url);
            $('#actaFrm').submit();
            //alert ("ok");
        }
    });
    
    (function() {
        jQuery("#addImg").on('click',addSource);
    })();
    
    function deleteFile(elemento){
        $("#arch"+elemento).fadeOut("slow",function(){
            $("#arch"+elemento).remove();
        });
    
    }

    function addSource(){
        var numItems = jQuery('.file_uploader').length;
        var template =  '<div  class="field" id="arch'+numItems+'">'+
                            '<div class="input-append">'+
                                '<div >'+
                                    '<input class="file_uploader" type="file" name="archivo'+numItems+'" id="archivo'+numItems+'">'+
                                    ' <button type="button" class="close" id="'+numItems+'" class="btn btn-success" onclick="deleteFile(this.id)">&times;</button>'+
                                '</div>'+
                            '</div>'+
                        '</div>';
        jQuery(template).appendTo('#file_source');
        console.log(numItems);
    }    
</script>
  