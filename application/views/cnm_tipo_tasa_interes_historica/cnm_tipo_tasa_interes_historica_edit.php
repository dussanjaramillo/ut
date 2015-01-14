<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<div class="center-form" width='450' height= '480'>

 <center>
 	       <?php     
        echo form_open(current_url()); ?>
        <?php echo $custom_error; ?>
        <h2>Edición Tipo de Cartera</h2>

        <p>
        <?php
         echo form_label('Nombre<span class="required">*</span>', 'nombre');
           $datanombre = array(
                      'name'        => 'nombre',
                      'id'          => 'nombre',
                      'value'       => $datos_edit['NOMBRE_CARTERA'],
                      'maxlength'   => '128',
                      'required'    => 'required'
                    );

           echo form_input($datanombre);
           echo form_error('nombre','<div>','</div>');
        ?>
        </p>
		<input type="hidden" id="cod_cartera" name="cod_cartera" value="<?=$datos_edit['COD_TIPOCARTERA']?>" >
        <p>
        <?php
         echo form_label('Estado<span class="required"></span>', 'tipo_id');  
            
                  $select[0] = "Inactivo";
				  $select[1] = "Activo";
            
          echo form_dropdown('tipo_id', $select,$datos_edit['COD_ESTADO'],'id="tipo_id" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('tipo_id','<div>','</div>');
        ?>
        </span>
        </p>
        <p>
                <input type='button' name='cancelar' class='btn btn-success' value='Cancelar'  id='cancelar' >
                <?php 
                $data = array(
                       'name' => 'button',
                       'id' => 'submit-button',
                       'value' => 'Guardar',
                       'type' => 'submit',
                       'content' => '<i class="fa fa-floppy-o fa-lg"></i> Guardar',
                       'class' => 'btn btn-success'
                       );

                echo form_button($data);    
                ?>
                <input type="hidden" id="vista_flag" name="vista_flag" value="1" >
        </p>

        <?php echo form_close(); ?>
</center>
</div>
  <script type="text/javascript">
 $('#editar').dialog({
                autoOpen: true,
                width: 450,
                height: 480,
                modal:true,
                title:'Editar Tipo Cartera',
                close: function() {
                    $('#editar *').remove();
                }
            });	

$('#cancelar').click(function(){
        	$('#editar').dialog('close');
       });
 
  function ajaxValidationCallback(status, form, json, options) {
	
}
</script> 

<style>
    .ui-widget-overlay{z-index: 10000;}
    .ui-dialog{
        z-index: 15000;
    }
    
 </style>