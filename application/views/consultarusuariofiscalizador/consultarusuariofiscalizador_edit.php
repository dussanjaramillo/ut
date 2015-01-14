<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<div class="center-form-large">

<?php     

echo form_open(current_url()); ?>
<?php echo $custom_error; ?>
<?php echo form_hidden('id',$result->IDUSUARIO) ?>
<h2>Editar Usuario Fiscalizador</h2>
<div class="controls controls-row">
        <div class="span3">
        <?php     

           echo form_label('Nombres<span class="required">*</span>', 'nombre');
           $datanombre = array(
                      'name'        => 'nombre',
                      'id'          => 'nombre',
                      'value'       => $result->NOMBRES,
                      'maxlength'   => '80',
                      'class'       => 'span3',
                      'required'    => 'required',
                      'readonly'    => 'readonly'
                    );

           echo form_input($datanombre);
           echo form_error('nombre','<div style="color: red">','</div>');
        ?>
        </div>
        <div class="span3">
        <?php     

           echo form_label('Apellidos<span class="required">*</span>', 'apellido');
           $dataapellido = array(
                      'name'        => 'apellido',
                      'id'          => 'apellido',
                      'value'       => $result->APELLIDOS,
                      'maxlength'   => '80',
                      'class'       => 'span3',
                      'required'    => 'required',
                      'readonly'    => 'readonly'
                    );

           echo form_input($dataapellido);
           echo form_error('apellido','<div style="color: red">','</div>');
        ?>
        </div>
        <div class="controls controls-row">
        <div class="span3">
        <?php     

           echo form_label('Nro. Cédula<span class="required">*</span>', 'cedula');
           $datacedula = array(
                      'name'        => 'cedula',
                      'id'          => 'cedula',
                      'value'       => $result->IDUSUARIO,
                      'maxlength'   => '12',
                      'class'       => 'span3',
                      'required'    => 'required',
                      'readonly'    => 'readonly'
                    );

           echo form_input($datacedula);
           echo form_error('cedula','<div style="color: red">','</div>');
        ?>
        </div>
        <div class="span3">
        <?php     

           echo form_label('Dirección<span class="required">*</span>', 'direccion');
           $datadireccion = array(
                      'name'        => 'direccion',
                      'id'          => 'direccion',
                      'value'       => $result->DIRECCION,
                      'maxlength'   => '100',
                      'class'       => 'span3',
                      'required'    => 'required'
                    );

           echo form_input($datadireccion);
           echo form_error('direccion','<div style="color: red">','</div>');
        ?>
        </div>
        <div class="controls controls-row">
        <div class="span3">
        <?php     

           echo form_label('Teléfono', 'telefono');
           $datatelefono = array(
                      'name'        => 'telefono',
                      'id'          => 'telefono',
                      'value'       => $result->TELEFONO,
                      'class'       => 'span3',
                      'maxlength'   => '12'
                      
                    );

           echo form_input($datatelefono);
           echo form_error('telefono','<div style="color: red">','</div>');
        ?>
        </div>
        <div class="span3">
        <?php     

           echo form_label('Celular<span class="required">*</span>', 'celular');
           $datacelular = array(
                      'name'        => 'celular',
                      'id'          => 'celular',
                      'value'       => $result->CELULAR,
                      'maxlength'   => '12',
                      'class'       => 'span3',
                      'required'    => 'required'
                    );

           echo form_input($datacelular);
           echo form_error('celular','<div style="color: red">','</div>');
        ?>
        </div>
        <div class="controls controls-row">
        <div class="span3">
        <?php     

           echo form_label('Correo Empresarial<span class="required">*</span>', 'emaile');
           $dataemaile = array(
                      'name'        => 'emaile',
                      'id'          => 'emaile',
                      'value'       => $result->EMAIL,
                      'maxlength'   => '50',
                      'placeholder' => 'ejemplo@sudominio.com',
                      'class'       => 'span3',
                      'required'    => 'required'
                    );

           echo form_input($dataemaile);
           echo form_error('emaile','<div style="color: red">','</div>');
        ?>
        </div>
        <div class="span3">
        <?php

           echo form_label('Correo Personal<span class="required">*</span>', 'emailp');
           $dataemailp = array(
                      'name'        => 'emailp',
                      'id'          => 'emailp',
                      'value'       => $result->CORREO_PERSONAL,
                      'maxlength'   => '50',
                      'placeholder' => 'ejemplo@sudominio.com',
                      'class'       => 'span3',
                      'required'    => 'required'
                    );

           echo form_input($dataemailp);
           echo form_error('emailp','<div style="color: red">','</div>');
        ?>
        </div>
        <div class="controls controls-row">
        <div class="span3">
        <?php
         echo form_label('Regional<span class="required">*</span>', 'regional_id');  
              foreach($regional as $row) {
                  $selectr[$row->COD_REGIONAL] = $row->NOMBRE_REGIONAL;
               }
          echo form_dropdown('regional_id', $selectr,$result->COD_REGIONAL,'id="regional_id" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('regional_id','<div style="color: red">','</div>');
        ?>
        </div>
        <div class="controls controls-row">
        <div class="span3">
        <?php
         echo form_label('Estado<span class="required">*</span>', 'estado_id');  
              foreach($estados as $row) {
                  $selecte[$row->IDESTADO] = $row->NOMBREESTADO;
               }
          echo form_dropdown('estado_id', $selecte,$result->ACTIVO,'id="estado_id" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('estado_id','<div style="color: red">','</div>');
        ?>
        </div>
        

        <div class="controls controls-row"></div>
        <div class="controls controls-row">
        <p class="pull-right">        
        <?php  echo anchor('consultarusuariofiscalizador', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?>
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

        <?php  /*if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('consultarusuariofiscalizador/delete'))
               {echo anchor('#', '<i class="fa fa-trash-o fa-lg"></i> Eliminar', 'class="btn btn-danger" id="borrar"');
               }*/ ?>
        
</p>
</div>
<table border="1" align="left">
  <tr>
    <th bgcolor="red"><font color="white">FECHA</font></th>
    <th bgcolor="red"><font color="white">USUARIO</font></th>
    <th bgcolor="red"><font color="white">ACCION</font></th>
  </tr>
  <tr>
    <td><?php echo $result->CREADO; ?></td>
    <td><?php echo $result->USUARIO_CREACION; ?></td>
    <td>CREADO</td>
  </tr>
  <tr>
    <td><?php echo $result->MODIFICADO; ?></td>
    <td><?php echo $result->USUARIO_MODIFICACION; ?></td>
    <td>ACTUALIZADO</td>
  </tr>
 
</table>
<?php echo form_close(); ?>


  
</div>
</div>
</div>
</div>
</div>
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

<script>
    $(function() {
        // a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
    $("#borrar").click(function(evento){    
        evento.preventDefault();
         var link = $(this).attr('href');
        $( "#dialog:ui-dialog" ).dialog( "destroy" );
    
        $( "#dialog-confirm" ).dialog({
            resizable: false,
            height:180,
            modal: true,
            buttons: {
                "Confirmar": function() {
                    location.href='<?php echo base_url()."index.php/crearusuariofiscalizador/delete/".$result->IDUSUARIO; ?>';
                    
                },
                Cancelar: function() {
                    $( this ).dialog( "close" );
                }
            }
        });
       });
    });
    </script>

<div id="dialog-confirm" title="¿Eliminar el usuario?" style="display:none;">
    <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>¿Confirma que desea eliminar el usuario "<?php echo $result->IDUSUARIO; ?>"?</p>
</div>