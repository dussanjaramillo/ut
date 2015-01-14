<h1>Simulación Kactus</h1>
<?php
if(isset($message))
{
echo $message;	
}
?>
 <?php     
        echo form_open(current_url()); ?>
<center>
<table>
	    <tr>
	<td>&nbsp;</td>
    <td>Mes:&nbsp&nbsp&nbsp</td>
    <td><select name="mes" id="mes" title="Mes:">
    <option value="01">Enero</option>
    <option value="02">Febrero</option>
    <option value="03">Marzo</option>
    <option value="04">Abril</option>
    <option value="05">Mayo</option>
    <option value="06">Junio</option>
  	<option value="07">Julio</option>
  	<option value="08">Agosto</option>	
    <option value="09">Septiembre</option>	
    <option value="10">Octubre</option>	
    <option value="11">Noviembre</option>	
    <option value="12">Diciembre</option>	
    	
    </select></td>
    <td>&nbsp;</td>
    <td>Año:&nbsp&nbsp&nbsp</td>
    <td><input type="text" id="anio" name="anio" style="width : 91%;" readonly="readonly" value="<?=date('Y')?>"></td>
    <td>&nbsp;</td>
  </tr>
	
</table>
</center>


<center>
	<input type="hidden" id="vista_flag" name="vista_flag" value="1" >
	<input type='button' name='cancelar' class='btn btn-success' value='Cancelar'  id='cancelar' >
                <?php 
                $data = array(
                       'name' => 'button',
                       'id' => 'submit-button',
                       'value' => 'Realizar Descuento Nómina',
                       'type' => 'submit',
                       'content' => '<i class="fa fa-floppy-o fa-lg"></i> Realizar Descuento Nómina',
                       'class' => 'btn btn-success'
                       );

                echo form_button($data);    
                ?>

</center>
<?php echo form_close(); ?>   
  <form id="rangotasa" action="<?= base_url('index.php/cnm_rango_tipo_tasa_interes_historica') ?>" method="post" >
     <input type="hidden" id="cod_tasa_hist" name="cod_tasa_hist">
</form>

<div id="editar"></div>
<div id="rangos"></div>
<script type="text/javascript" language="javascript" charset="utf-8">
  

$('#cancelar').click(function(){
        	window.history.back()
       });  

       
    function ajaxValidationCallback(status, form, json, options) {
	
}
</script> 
