<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>

<?php 
if (isset($message)){
    echo $message;
   }
?>
<div class="center-form-large">
<?php     

echo form_open(current_url()); ?>
<?php echo form_hidden('id_empresa',$result->CODEMPRESA) ?>
   <?php //echo form_hidden('cod_resolucion',$cod_resolucion) ?>
<?php echo $custom_error; ?>
<h2>Editar Datos Deudor</h2>
  <div class="controls controls-row">

<div class="span3">
 <?php
         echo form_label('Nombre Completo<span class="required">*</span>', 'nombre');
           $data = array(
                      'name'        => 'nombre',
                      'id'          => 'nombre',
                      'value'       => $result->NOMBRE_EMPRESA,
                      'maxlength'   => '128',
                      'required'    => 'required',
                      'class'   => 'span3',
                    );

           echo form_input($data);
           echo form_error('nombre','<div>','</div>');
        ?>
</div>
<div class="span3">
 <?php
          echo form_label('Tipo de Identificación <span class="required">*</span>', 'tipo_doc');                               
          foreach($tipo_doc as $row) {
              $select[$row->CODTIPODOCUMENTO] = $row->NOMBRETIPODOC;
           }
            echo form_dropdown('tipo_doc', $select,'','id="tipo_doc" class="chosen" placeholder="seleccione..." ');

 
            echo form_error('tipo_doc','<div>','</div>');
        ?>

</div>
  </div>
<div class="span3">
<?php

 echo form_label('Número Documento<span class="required"></span>', 'Número Documento');
   $data= array(
              'name'        => 'numdocumento',
              'id'          => 'numdocumento',
              'value'       => $result->CODEMPRESA,
              'maxlength'   => '128',
              'disabled'    => 'disabled',
       
              'class'   => 'span3'
            );

   echo form_input($data);
   echo form_error('numdocumento','<div>','</div>');

?>
</div>

  <div class="controls controls-row">

<div class="span3">
<?php
  echo form_label('Fecha Actual<span class="required"></span>', 'Fecha Actual');
   $data = array(
              'name'        => 'fechaactual',
              'id'          => 'fechaactual',
              'value'       => date("Y-m-d"),
              'maxlength'   => '100',
              'class'       => 'span3',
              'disabled'    => 'disabled'
            );

   echo form_input($data );
   echo form_error('numdocumento','<div>','</div>');
?>

</div>
      <div class="span3">
<?php
  echo form_label('Razón Social<span class="required">*</span>', 'Razón Social');
   $data = array(
              'name'        => 'razonsocial',
              'id'          => 'razonsocial',
              'value'       => $result->RAZON_SOCIAL,
              'maxlength'   => '150',
              'class'   => 'span3',
              'required'    => 'required',
            );

   echo form_input($data);
   echo form_error('numdocumento','<div>','</div>');
?>
      </div>
      <div class="controls controls-row">
<div class="span3">
    
 <?php
 echo form_label('Domicilio<span class="required">*</span>', 'Domicilio');
   $data= array(
              'name'        => 'domicilio',
              'id'          => 'domicilio',
              'value'       => '',
              'maxlength'   => '128',
              'required'    => 'required',
            );

   echo form_input($data);
   echo form_error('razonsocial','<div>','</div>');
?>
   
</div>

</div>
<div class="controls controls-row">
<div class="span3">
    
 <?php
 echo form_label('Telefono<span class="required">*</span>', 'telefono');
   $data = array(
              'name'        => 'telefono',
              'id'          => 'telefono',
              'value'       => $result->TELEFONO_FIJO,
              'maxlength'   => '128',
              'class'=>'span3',
              'required'    => 'required',
            );

   echo form_input($data);
   echo form_error('telefono','<div>','</div>');
?>
</div>

<div class="span3">
<?php
 echo form_label('Celular<span class="required">*</span>', 'celular');
   $data= array(
              'name'        => 'celular',
              'id'          => 'celular',
              'value'       => $result->TELEFONO_CELULAR,
              'maxlength'   => '128',
              'required'    => 'required',
            );

   echo form_input($data);
   echo form_error('celular','<div>','</div>');
?>

</div>
    
 </div>
<div class="controls controls-row"> 
    <div class="span3"> 
    <?php
echo form_label('Autoriza envio de correo?<span class="required">*</span>', '');
  ?>
    <input type="checkbox" name="autoriza" value="autoriza"> 
    </div>
    <div class="span3">  
   <?php
 echo form_label('Email<span class="required">*</span>', 'Email');
   $data = array(
              'name'        => 'email',
              'id'          => 'email',
              'value'       => $result->EMAILAUTORIZADO,
              'maxlength'   => '230',
              'required'    => 'required',
              'class'       => 'span3'
            );

   echo form_email($data);
   
   
   echo form_error('email','<div>','</div>');
   
   
?> 
</div>
</div>
<div class="controls controls-row"></div>
<div class="controls controls-row">
<p class="pull-right">
       
        <?php 
        $data = array(
               'name' => 'button',
               'id' => 'submit-button',
               'value' => 'Guardar',
               'type' => 'submit',
               'content' => '<i class="fa fa-floppy-o fa-lg"></i> Continuar',
               'class' => 'btn btn-success'
               );

        echo form_button($data);    
        ?>
         
</div>
<?php echo form_close(); ?>
  
</div>


  <script type="text/javascript">
  //style selects
    var config = {
      '.chosen'           : {}
    }
    for (var selector in config) {
      $(selector).chosen(config[selector]);
    }

  </script>



