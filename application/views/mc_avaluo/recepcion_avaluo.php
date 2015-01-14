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
        echo form_open_multipart("mc_avaluo/genera_avaluo", $attributes); ?>
    
    <div id="titulo">Recepción de Avalúo</div><br>
    <table id="tabla1" style="width:auto; ">
        <tr><td colspan="4" style="text-align:center"><span style="color:red" text-align: center;>PROCESO COACTIVO</span>
                <span style="margin-left:10px;">
            <?php $data = array('name' => 'nit_empresa', 'id' => 'nit_empresa', 'class' => 'validate[required]', 'maxlength' => '15',
                'required' => 'required', 'readonly'=>'readonly',) ; echo form_input($data); ?></span></td></tr>
        <tr>
            <td colspan="2" style="text-align:justify" ><span style=" text-align: justify ">
                        A continuación vamos a ingresar los datos de<br>  
                                registro OnBase del avaluo si se tienen, luego<br>
                                             cargamos el archivo que contiene el avaluo y<br>
                                 pasamos a generar la notificación personal
                                 <br>
                            </span>
            </td>
        </tr>
        <tr>
            <td>
                <?php
                    $data = array(
                               'name'    => 'button',
                               'id'      => 'salir',
                               'value'   => 'Salir',
                               'type'    => 'button',
                               'style'   =>'margin-left:60px;',
                               'content' => 'Salir',
                               'class'   => 'btn btn-success',
                               
                               );
                         echo form_button($data); 
                    ?>
               
                    <?php
                     $data = array(
                               'name'    => 'button',
                               'id'      => 'Continuar',
                               'value'   => 'Continuar',
                               'type'    => 'button',
                               'style'   =>'margin-left:120px;',
                               'content' => 'Continuar',
                               'class'   => 'btn btn-success',
                               
                               );
                         echo form_button($data);  ?>
            </td>
        </tr>
    </table>
    <script>
     $(document).ready(function() {
     $('#salir').click(function(){ 
     $('#div_contenido').dialog('close');
     
     });});
 
 
   
    
 
        </script>
    <style>  
        #tabla1{
        text-align:center;
       
    }
        
    </style>