<h1>Actualización Seguros Crédito Hipotecario</h1>
<?php
if(isset($message))
{
echo $message;	
}
?>
 <?php     
        echo form_open(current_url()); ?>
<br><br>
<center>
	<b>
	Tipo Seguro:&nbsp&nbsp<?php

          foreach($seguro->result_array as $row) {
              	
                  $selecttipoestado[$row['COD_TIPOCARTERA']] = $row['NOMBRE_CARTERA'];
               }
          echo form_dropdown('tipo_seguro_id', $selecttipoestado,'','id="tipo_seguro_id" style="width : 20%;" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('tipo_seguro_id','<div>','</div>');
        

        ?>
	</b>
</center>
<br><br>

<input type="hidden" id="vista_flag" name="vista_flag" value="1" >
<center>
<?php  echo anchor('', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?>
<?php 
                $data = array(
                       'name' => 'button',
                       'id' => 'submit_button',
                       'value' => 'Actualizar',
                       'type' => 'submit',
                       'content' => '<i class="fa fa-floppy-o fa-lg"></i> Actualizar',
                       'class' => 'btn btn-success',
                       );

                echo form_button($data);    
                ?>
<?php echo form_close(); ?>
		

</center>




<div id="crear"></div>
<br><br>
<div id="variables"></div>

  <form id="crea" action="<?= base_url('index.php/cnm_ac_componentes/add') ?>" method="post" >
    <input type="hidden" id="cod_ac_comp" name="cod_ac_comp">
</form>



<script type="text/javascript" language="javascript" charset="utf-8">

$('#cancelar').click(function(){
        	window.history.back()
       });  
       
           $('.at1').click(function(){
    	var cod_acuerdo=$(this).attr('at1');
	var url="<?= base_url('index.php/cnm_ac_variables') ?>";
	
	$('#variables').load(url,{cod_acuerdo : cod_acuerdo });
    });
       


    
        $('#crear').click(function(){
    	var cod_acuerdo_crear=$("#cod_acuerdo_c").val();
    		$('#cod_ac_comp').val(cod_acuerdo_crear);
 $('#crea').submit();

    });
       
    $('.at3').click(function(){
	var cod_componente=$(this).attr('at3');
	var nombre_tasa=$(this).attr('at4');
        var url = "<?php echo base_url('index.php/cnm_ac_componentes/delete') ?>";
        var confirmar = confirm("Desea eliminar este componente?");
          if(confirmar == true){
          	
        $.post(url, {cod_componente: cod_componente})
        	.done(function(data)
        {
        	alert("Se ha eliminado el Componente "+nombre_tasa);
        	
        	 $('#componentes').submit();
        	  })
        	.fail(function(data)
        {
        alert("No se pudo Eliminar Componente");
        	
        	});
        
        }
    });
    

    
    function ajaxValidationCallback(status, form, json, options) {
	
}
</script> 
