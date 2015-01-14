<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
if (isset($message)) {
    echo $message;
}
if (isset($custom_error))
    echo $custom_error;
?>
 
<div id="ajax_load" class="ajax_load" style="display: none">
    <div class="preload" id="preload" ></div><img  id="load" class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
</div>
 <?php
        $attributes = array("id" => "myform");
        echo form_open_multipart("mc_avaluo/guarda_notificacion", $attributes); ?>
    
<input type="hidden" name="id" id="id" value="<?php echo $post['id']?>">
<input type="hidden" id="cod_siguiente" name="cod_siguiente" value="<?php echo $post['cod_siguiente'] ?>">
    <input type="hidden" id="tipo_gestion" name="tipo_gestion" value="<?php echo $post['tipo_gestion'] ?>">
    <input type="hidden" id="cod_fisc" name="cod_fisc" value="<?php echo $post['cod_fisc'] ?>">
    <input type="hidden" id="respuesta" name="respuesta" value="<?php echo $post['respuesta'] ?>">
    <input type="hidden" id="titulo" name="titulo" value="<?php echo $post['titulo'] ?>">
    <input type="hidden" id="tipo_doc" name="tipo_doc" value="<?php echo $post['tipo_doc'] ?>">
   <input type="hidden" id="nombre" name="nombre" value="">
   <div id="datos">
    <table id="tabla" style="width:auto;">
         <tr>
                <td class="sub"> <br>   N° Proceso Coactivo</td>
                <td class="color">    <br>                                       
                    <?php echo $consulta[0]['COD_FISCALIZACION'] ?>   
                </td>
                <td class="sub"> <br>   N° Avaluo </td>
                <td> <br>   
                    <?php echo $consulta[0]['COD_AVALUO'] ?>   
                </td>
            </tr>
        <tr>
            <td class="sub" style="text-align:left;">Nit</td>
            <td class="sub" style="text-align:left;">
             <?php $data = array('name' => 'nit_empresa', 'id' => 'nit_empresa', 'class' => 'validate[required]', 'maxlength' => '15',
            'required' => 'required', 'readonly'=>'readonly','value'=>$empresa[0]['CODEMPRESA']) ; echo form_input($data); ?>   
            </td>
            <td class="sub" style="margin-left:50px;">Ejecutado</td>
            <td style="text-align: left;" >
             <?php $data = array('name' => 'razon_social', 'id' => 'razon_social', 
            'required' => 'required', 'readonly'=>'readonly','readonly'=>'readonly','value'=>$empresa[0]['NOMBRE_EMPRESA']); echo form_input($data); ?> </td>
        </tr>
<!--        <tr>
            <td class="sub" style="text-align:left;">Concepto </td>
            <td style="text-align: left;">
              <?php $data = array('name' => 'concepto', 'id' => 'concepto', 
            'required' => 'required','readonly'=>'readonly',); echo form_input($data); ?>   
            </td>
            <td class="sub" style="text-align:left;  margin-left:15px;">Instancia</td>
            <td style="text-align: left;">
              <?php $data = array('name' => 'instancia', 'id' => 'instancia', 
            'required' => 'required','readonly'=>'readonly',); echo form_input($data); ?>   
            </td>
        </tr>-->
         <tr>
           
            <td class="sub" style=" margin-left:15px;">Teléfono</td>
            <td style="text-align: left;">
              <?php $data = array('name' => 'telefono', 'id' => 'telefono', 
            'required' => 'required','readonly'=>'readonly','value'=>$empresa[0]['TELEFONO_FIJO']); echo form_input($data); ?>   
            </td>
            <td class="sub" style="text-align:left;">Estado </td>
            <td style="text-align: left;">
              <?php $data = array('name' => 'estado', 'id' => 'estado', 
            'required' => 'required','readonly'=>'readonly', 'value'=> AVALUO_RECIBIDO); echo form_input($data); ?>   
            </td>
        </tr>
       </table>
   </div>
    <div id="descripcion_not" style="width: auto">
       <textarea id="informacion" style="width: 100%;height: 400px"><?php  echo $documento; ?></textarea>
    </div>
    <div id="datos" style="margin-top:20px;">
         <table id="tabla" style="width:auto;">
<!--        <tr>
            <td class="sub" style="text-align:left;">Fecha:</td>
            <td class="sub" style="text-align:left;">
             <?php $data = array('name' => 'fecha', 'id' => 'fecha',
            'required' => 'required',) ; echo form_input($data); ?>   
            </td>
        </tr>    
        <tr>   
            <td class="sub" style="text-align:left;">Estado</td>
            <td style="text-align: left;" >
             <?php // $data = array('name' => 'estado', 'id' => 'estado', 
            //'required' => 'required', 'readonly'=>'readonly',); echo form_input($data); ?> </td>
        </tr>
         <tr>   
            <td class="sub" style="text-align:left;">Número de radicado Onbase</td>
            <td style="text-align: left;" >
             <?php $data = array('name' => 'num_radicado', 'id' => 'num_radicado', 
            'required' => 'required', 'readonly'=>'readonly',); echo form_input($data); ?> </td>
        </tr>
        <tr>   
            <td class="sub" style="text-align:left;">Motivo</td>
            <td style="text-align: left;" >
             <?php $data = array('name' => 'motivo', 'id' => 'motivo', 
            'required' => 'required', 'readonly'=>'readonly',); echo form_input($data); ?> </td>
        </tr>
         <tr>   
            <td class="sub" style="text-align:left;">No de Colilla</td>
            <td style="text-align: left;" >
             <?php $data = array('name' => 'num_colilla', 'id' => 'num_colilla', 
            'required' => 'required', 'readonly'=>'readonly',); echo form_input($data); ?> </td>
        </tr>
          <tr>   
            <td class="sub" style="text-align:left;">Subir Soporte</td>
            <td style="text-align: left;" >
                <?php
        $data = array(
            'name' => 'userfile',
            'id' => 'imagen',
            'class' => 'validate[required]'
        );
        echo form_upload($data);
        ?>
              </td>
        </tr>
        <tr>
            <td class="sub" style="text-align:left;">Observaciones</td>
            <td>
                <?php
             $datadesc = array(
            'name' => 'descripcion_datos',
            'id' => 'descripcion_datos',
            'required' => 'required',
            'rows'=>'4' ,
            'cols'=>'100',
            'style'=>'margin: 0px 0px 10px; width: 644px; height: 100px;'
        );

        echo form_textarea($datadesc);
        ?>
            </td>
            
        </tr>-->
        <tr><td></td>
            <td >
<!--                <button id="pdf" onclick="genera_pdf()" class='btn btn-success' style="margin-left:60px;">PDF</button>-->
                             <?php
                       
                           $data = array(
                               'name'    => 'button',
                               'id'      => 'pdf',
                               'value'   => 'PDF',
                               'type'    => 'button',
                               'style'   =>'margin-left:60px;',
                               'content' => 'PDF',
                               'class'   => 'btn btn-success',
                               'onclick' => 'genera_pdf()'
                               );
                         echo form_button($data);  
                          $data = array(
                               'name'    => 'button',
                               'id'      => 'cancelar',
                               'value'   => 'Cancelar',
                               'type'    => 'button',
                               'style'   =>'margin-left:60px;',
                               'content' => 'Cancelar',
                               'class'   => 'btn btn-success',
                               
                               );
                         echo form_button($data); 
                           $data = array(
                               'name'    => 'button',
                               'id'      => 'Aceptar',
                               'value'   => 'Aceptar',
                               'type'    => 'button',
                               'style'   =>'margin-left:60px;',
                               'content' => 'Aceptar',
                               'class'   => 'btn btn-success',
                               
                               );
                         echo form_button($data);  
                 ?>
            
  
            </td>
            
        </tr>
         </table>
     
        
    </div>

    <?php echo form_close(); ?>
    <form id="form_pdf" name="form_pdf" target = "_blank"  method="post" action="<?php echo base_url('index.php/mc_avaluo/pdf') ?>">
        <textarea id="descripcion_pdf" name="descripcion_pdf" style="width: 100%;height: 300px; display:none"></textarea>  
        <input type="hidden" name="nombre_archivo" id="nombre_archivo">
    </form>
  
    <script>
        $(document).ready(function() {
        var nit = "<?php echo $empresa[0]['CODEMPRESA'] ?>";
        var nombre_archivo = "<?php echo date("d-m-Y H:i:s"); ?>";
        var nombre_archivo = nombre_archivo.replace(":", "_");
        var nombre_archivo = nombre_archivo.replace(":", "_");
        var nombre_archivo = nombre_archivo.replace(" ", "_");
        var nombre_archivo = nombre_archivo + "_" + nit;
        document.getElementById("nombre_archivo").value=nombre_archivo;
});

function genera_pdf() {
            var url = "<?php echo base_url('index.php/mc_avaluo/pdf') ?>";
            var informacion = tinymce.get('informacion').getContent();

            if (informacion == '')
            {
                mierror = 'Debe ingresar el contenido de la notificación';
                document.getElementById("respuesta").innerHTML = mierror;
                return false;
            }
            var nit = "<?php echo $consulta[0]['NIT_EMPRESA'] ?>";
            var nombre_archivo = "<?php echo date("d-m-Y H:i:s"); ?>";
            var nombre_archivo = nombre_archivo.replace(":", "_");
            var nombre_archivo = nombre_archivo.replace(":", "_");
            var nombre_archivo = nombre_archivo.replace(" ", "_");
            var nombre_archivo = nombre_archivo + "_" + nit;
            document.getElementById("nombre_archivo").value = nombre_archivo;
            document.getElementById("descripcion_pdf").value = informacion;
            $("#form_pdf").submit();



}
    jQuery(".preload, .load").hide();
    $('#resultado').dialog({
        autoOpen: true,
        width: 800,
        height: 550,
        modal: true,
        title: "<?php echo $post['titulo']; ?>",
        close: function() {
            $('#resultado *').remove();
        }
    });   
         tinymce.init({
        selector: "textarea#informacion",
        theme: "modern",
        plugins: [
            "advlist autolink lists link  charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code fullscreen",
                    //"insertdatetime media nonbreaking save table contextmenu directionality",
                    //"emoticons template paste textcolor moxiemanager"
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
     $('#cancelar').click(function(){ 
     $('#resultado').dialog('close');
      $('#resultado *').remove();
     });  });  
     


//guarda_notificacion
 $('#Aceptar').click(function() {
        $(".ajax_load").show();
        var url = "<?php echo base_url('index.php/mc_avaluo/guarda_notificacion') ?>";
        var nit = "<?php echo $empresa[0]['CODEMPRESA']?>";
        var id = "<?php echo $consulta[0]['COD_AVALUO'] ?>";
        var tipo_gestion = "<?php echo $post['tipo_gestion'] ?>";
        var cod_siguiente = "<?php echo $post['cod_siguiente'] ?>";
        var cod_fisc = "<?php echo $post['cod_fisc'] ?>";
        var titulo = "<?php echo $post['titulo'] ?>";
        var tipo_doc="<?php echo $post['tipo_doc'] ?>";
        var respuesta="<?php echo $post['respuesta'] ?>";
        var nombre_archivo = "<?php echo date("d-m-Y H:i:s"); ?>";
        var nombre_archivo = nombre_archivo.replace(":", "_");
        var nombre_archivo = nombre_archivo.replace(":", "_");
        var nombre_archivo = nombre_archivo.replace(" ", "_");
        var nombre_archivo = nombre_archivo + "_" + nit;
        
     var informacion = tinymce.get('informacion').getContent();
           if (informacion == "") {
             mierror = "Debe ingresar el contenido del documento";
              $("#mensaje").css('display','block');
              $("#boton_mensaje").css('display','none');
              document.getElementById("mensaje").innerHTML =document.getElementById("boton_mensaje").innerHTML + mierror;
         // 
                     
                      
            return false;
           
        }
       $.post(url, {id:id,informacion:informacion, nit: nit,tipo_gestion:tipo_gestion,cod_siguiente:cod_siguiente,cod_fisc :cod_fisc,nombre:nombre_archivo,tipo_doc:tipo_doc,respuesta:respuesta,titulo:titulo }
       ,function(data){
           $("#div_mensaje").html(data);
           $('#resultado').dialog('close');
           $('#resultado *').remove();
           window.location.href = "<?php echo base_url('index.php/mc_avaluo/abogado') ?>";}) 
         
        
  })


        </script>
        
<style>
    #titulo{
        font-family: Geneva, Arial, Helvetica, sans-serif; 
        font-size: 18px;
        background-color: #5BB75B;
        width:auto;
        text-align:center;
        color:#FFFFFF;
        height:30px;

    }
  
    div.preload{
        position: fixed;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        background-color: white;
        opacity: 0.8;
        z-index: 10000;
    }

    div img.load{
        position: absolute;
        left: 50%;
        top: 50%;
        margin-left: -64px;
        margin-top: -64px;
        z-index: 15000;
    }
    .tabla1
    {
        background-color: white;  
        text-align: left;

    }
    .sub{
        font-weight: bold;
        margin-left:0px;
        font-size: 14px;
    }

    .color{
        color: #FC7323;
        font:bold 12px;
    }
    #tabla_inicial{
        border-radius: 50px;
        border: 1px solid #CCC;
    }

</style>





