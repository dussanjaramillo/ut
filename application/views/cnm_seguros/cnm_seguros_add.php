<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>

<div class="center-form-large">
 <center>
 	       <?php     
        echo form_open(current_url()); ?>
        <?php echo $custom_error; ?>
        <h2>Ingresar Nueva Tasa</h2>

<table width="350" class="tabla2">
  
  <tr>
  	<td>&nbsp;</td>	
  	
  	</tr>


    <tr>
    <td>&nbsp;</td>
    <td>Concepto:&nbsp&nbsp</td>
    <td><input type="text" id="concepto" name="concepto" style="width : 91%;" readonly="readonly" value="<?=$concepto_nombre?>"><input type="hidden" id="id_concepto" name="id_concepto" style="width : 91%;" readonly="readonly" value="<?=$concepto?>"></td>
    <td>&nbsp;</td>
  </tr>
  
      <tr>
    <td>&nbsp;</td>
    <td>Identificación Aseguradora:</td>
    <td><input type="text" id="id_aseguradora" name="id_aseguradora" style="width : 91%;" required="required"></td>
  	<td>&nbsp;</td>
   </tr>
  
  <tr>
    <td>&nbsp;</td>
    <td>Aseguradora:</td>
    <td><input type="text" id="nombre_aseguradora" name="nombre_aseguradora" style="width : 91%;" required="required"></td>
  	<td>&nbsp;</td>
  </tr>
  
      <tr>
    <td>&nbsp;</td>
    <td>Inicio Vigencia:</td>
    <td><input type="text" id="ini_vigencia" name="ini_vigencia" style="width : 72%;" readonly="readonly"></td>
  	<td>&nbsp;</td>

  </tr>

  
  
        <tr>
    <td>&nbsp;</td>
    <td>Valor Tasa<p>((v. tasa/1000)/12):</p></td>
	<td><input type="text" id="tasa" name="tasa" style="width : 91%;" required="required"></td>
  	<td>&nbsp;</td>
  </tr>
  
  </table>

        
	 <?php  echo anchor('', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?>
               
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
 $('#ingresar_seguro').dialog({
                autoOpen: true,
                width: 520,
                height: 550,
                modal:true,
                title:'Ingresar Seguro',
                close: function() {
                  $('#ingresar_seguro *').remove();
                
                
                }
            });

$( "#ini_vigencia").datepicker({
	
	showOn: 'button',
	buttonText: 'Inicio Vigencia',
	buttonImage: '<?php echo base_url() . "img/calendario.png" ?>',
	buttonImageOnly: true,
	numberOfMonths: 1,
	changeMonth: true,
	changeYear: true,
	dateFormat: 'dd/mm/yy',

	
});

$( "#fin_vigencia").datepicker({
	
	showOn: 'button',
	buttonText: 'Fin Vigencia',
	buttonImage: '<?php echo base_url() . "img/calendario.png" ?>',
	buttonImageOnly: true,
	numberOfMonths: 1,
	changeMonth: true,
	changeYear: true,
	dateFormat: 'dd/mm/yy',

	
});
function ajaxValidationCallback(status, form, json, options) {
	
}
</script> 