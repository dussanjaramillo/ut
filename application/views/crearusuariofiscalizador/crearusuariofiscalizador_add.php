<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<?php 
if (isset($message)){
    echo $message;
   }
?>
<div class="center-form-large">
        <?php     

        echo form_open(current_url()); ?>
        <?php echo $custom_error; ?>
        <div id="resultado"></div>
        <?php echo form_hidden('grupo_id','2') ?>
        <h2>Nuevo Usuario Fiscalizador</h2>
        <div class="controls controls-row">
        <div class="span3">
        <?php     

           echo form_label('Nombres<span class="required">*</span>', 'nombre');
           $datanombre = array(
                      'name'        => 'nombre',
                      'id'          => 'nombre',
                      'value'       => set_value('nombre'),
                      'maxlength'   => '80',
                      'class'       => 'span3',
                      'required'    => 'required'
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
                      'value'       => set_value('apellido'),
                      'maxlength'   => '80',
                      'class'       => 'span3',
                      'required'    => 'required'
                    );

           echo form_input($dataapellido);
           echo form_error('apellido','<div style="color: red">','</div>');
        ?>
        </div>
        </div>
        <div class="controls controls-row">
        <div class="span3">
        <?php     

           echo form_label('Nro. Cédula<span class="required">*</span>', 'cedula');
           $datacedula = array(
                      'name'        => 'cedula',
                      'id'          => 'cedula',
                      'value'       => set_value('cedula'),
                      'maxlength'   => '12',
                      'class'       => 'span3',
                      'required'    => 'required'
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
                      'value'       => set_value('direccion'),
                      'maxlength'   => '100',
                      'class'       => 'span3',
                      'required'    => 'required'
                    );

           echo form_input($datadireccion);
           echo form_error('direccion','<div style="color: red">','</div>');
        ?>
        </div>
        </div>
        <div class="controls controls-row">
        <div class="span3">
        <?php     

           echo form_label('Teléfono', 'telefono');
           $datatelefono = array(
                      'name'        => 'telefono',
                      'id'          => 'telefono',
                      'value'       => set_value('telefono'),
                      'maxlength'   => '12',
                      'class'       => 'span3'
                      
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
                      'value'       => set_value('celular'),
                      'maxlength'   => '12',
                      'class'       => 'span3',
                      'required'    => 'required'
                    );

           echo form_input($datacelular);
           echo form_error('celular','<div style="color: red">','</div>');
        ?>
        </div>
        </div>
        <div class="controls controls-row">
        <div class="span3">
        <?php     

           echo form_label('Correo Empresarial<span class="required">*</span>', 'emaile');
           $dataemaile = array(
                      'name'        => 'emaile',
                      'id'          => 'emaile',
                      'value'       => set_value('emaile'),
                      'maxlength'   => '50',
                      'class'       => 'span3',
                      'placeholder' => 'ejemplo@sudominio.com',
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
                      'value'       => set_value('emailp'),
                      'maxlength'   => '50',
                      'class'       => 'span3',
                      'placeholder' => 'ejemplo@sudominio.com',
                      'required'    => 'required'
                    );

           echo form_input($dataemailp);
           echo form_error('emailp','<div style="color: red">','</div>');
        ?>
        </div>
      </div>
        <div class="controls controls-row">
        <div class="span2">
        <?php
         echo form_label('Alias <span class="required">*</span>', 'alias');
           $data = array(
                      'name'        => 'alias',
                      'id'          => 'alias',
                      'value'       => set_value('alias'),
                      'maxlength'   => '128',
                      'required'    => 'required',
                       'class'       => 'span2'

                    );

           echo form_input($data);
           echo form_error('nombre','<div style="color: red">','</div>');
        ?>
        </div>
        <div class="span3">
        <?php
         echo form_label('Contraseña<span class="required">*</span>', 'password');
           $data = array(
                      'name'        => 'password',
                      'id'          => 'password',
                      'value'       => set_value('password'),
                      'maxlength'   => '128',
                      'required'    => 'required',
                       'class'       => 'span3'

                    );

           echo form_password($data);
           echo form_error('password','<div style="color: red">','</div>');
        ?>
        </div>

        <div class="span3">
        <?php
         echo form_label('Repita su contraseña<span class="required">*</span>', 'repassword');
           $data = array(
                      'name'        => 'repassword',
                      'id'          => 'repassword',
                      'value'       => set_value('repassword'),
                      'maxlength'   => '128',
                      'required'    => 'required',
                       'class'       => 'span3'

                    );

           echo form_password($data);
           echo form_error('repassword','<div style="color: red">','</div>');
        ?>
        </div>
        </div>
       
        <div class="controls controls-row">
        <!-- <div class="span2">
        <?php
         echo form_label('Estado<span class="required">*</span>', 'estado_id');  
               $selecte = array('' => "Seleccione..." );
              foreach($estados as $row) {
                  $selecte[$row->IDESTADO] = $row->NOMBREESTADO;
               }
          echo form_dropdown('estado_id', $selecte,'','id="estado_id" class="chosen span2" data-placeholder="seleccione..." ');
         
           echo form_error('estado_id','<div style="color: red">','</div>');
        ?>
        </div> -->
        <div class="span3">
        <?php
         echo form_label('Regional<span class="required">*</span>', 'regional_id');  
              $selectr = array('' => "Seleccione..." );
              foreach($regional as $row) {
                  $selectr[$row->COD_REGIONAL] = $row->NOMBRE_REGIONAL;
               }
          echo form_dropdown('regional_id', $selectr,'','id="regional_id" class="chosen span3" data-placeholder="seleccione..." ');
         
           echo form_error('regional_id','<div style="color: red">','</div>');
        ?>
        </div>
        <div class="span3">
        <?php
         echo form_label('Cargo<span class="required">*</span>', 'cargo_id');                               
              $select = array('' => "Seleccione..." );
              foreach($cargos as $row) {
                  $select[$row->IDCARGO] = $row->NOMBRECARGO;
               }
            echo form_dropdown('cargo_id', $select,'','id="cargo" class="chosen span3" placeholder="seleccione..." ');

         
           echo form_error('cargo_id','<div style="color: red">','</div>');
        ?>
        </div>
        </div>
       
       
        <div class="controls controls-row"></div>
        <div class="controls controls-row">
        <p class="pull-right">
                <?php  echo anchor('crearusuariofiscalizador', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?>
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
                
        </p>
        </div>
            <?php echo form_close(); ?>
</div> 

 <script type="text/javascript">

  function fnc(v) {
    alert(v);
  } 

  //style selects
     function format(state) {
    if (!state.id) return state.text; // optgroup
         return "<i class='fa fa-home fa-fw fa-lg'></i>" + state.id.toLowerCase() + " " + state.text;
    }
    $(".chosen0").select2({
    formatResult: format,
    formatSelection: format,
    escapeMarkup: function(m) { return m; }
    });


    $(document).ready(function() { $(".chosen").select2();

     });

  



  </script>

<!-- // |:::::Script para consultar el contenido de un campo a la DB con ajax -->
<!-- // |:::::Consulta el contenido del campo "cedula" -->
<script>
$(document).ready(function(){
                         
      var consulta;
             
      //hacemos focus
      //$("#cedula").focus();
                                                 
      //comprobamos si se pulsa una tecla
      $("#cedula").focusout(function(e){
             //obtenemos el texto introducido en el campo
             consulta = $("#cedula").val();
                                      
             //hace la búsqueda
             $("#resultado").delay(1).queue(function(n) {     
                                           
                  //$("#resultado").html('<img src="ajax-loader.gif" />');
                                           
                        $.ajax({
                              type: "POST",
                              url: "<?php echo base_url(); ?>index.php/crearusuariofiscalizador/id_check",
                              data: "c="+consulta,
                              dataType: "html",
                              error: function(){
                                    alert("error petición ajax");
                              },
                              success: function(data){                                                     
                                    $("#resultado").html(data);
                                    n();
                              }
                  });
                                           
             });
                                
      });
                          
});
</script>

<!-- // |:::::Script para consultar el contenido de un campo a la DB con ajax -->
<!-- // |:::::Consulta el contenido del campo "emaile" -->
<script>
$(document).ready(function(){
                         
      var consulta;
             
      //hacemos focus
      //$("#cedula").focus();
                                                 
      //comprobamos si se pulsa una tecla
      $("#emaile").focusout(function(e){
             //obtenemos el texto introducido en el campo
             consulta = $("#emaile").val();
                                      
             //hace la búsqueda
             $("#resultado").delay(1).queue(function(n) {     
                                           
                  //$("#resultado").html('<img src="ajax-loader.gif" />');
                                           
                        $.ajax({
                              type: "POST",
                              url: "<?php echo base_url(); ?>index.php/crearusuariofiscalizador/email_check",
                              data: "c="+consulta,
                              dataType: "html",
                              error: function(){
                                    alert("error petición ajax");
                              },
                              success: function(data){                                                     
                                    $("#resultado").html(data);
                                    n();
                              }
                  });
                                           
             });
                                
      });
                          
});
</script>

<!-- // |:::::Script para consultar el contenido de un campo a la DB con ajax -->
<!-- // |:::::Consulta el contenido del campo "alias" -->
<script>
$(document).ready(function(){
                         
      var consulta;
             
      //hacemos focus
      //$("#cedula").focus();
                                                 
      //comprobamos si se pulsa una tecla
      $("#alias").focusout(function(e){
             //obtenemos el texto introducido en el campo
             consulta = $("#alias").val();
                                      
             //hace la búsqueda
             $("#resultado").delay(1).queue(function(n) {     
                                           
                  //$("#resultado").html('<img src="ajax-loader.gif" />');
                                           
                        $.ajax({
                              type: "POST",
                              url: "<?php echo base_url(); ?>index.php/crearusuariofiscalizador/username_check",
                              data: "c="+consulta,
                              dataType: "html",
                              error: function(){
                                    alert("error petición ajax");
                              },
                              success: function(data){                                                     
                                    $("#resultado").html(data);
                                    n();
                              }
                  });
                                           
             });
                                
      });
                          
});
</script>
   