<?php
    if( ! defined('BASEPATH') ) exit('No direct script access allowed');
    if ( isset($message )) {
        echo $message;
    }
?>



<div class="center-form-large">

    <!-- INICIO Form -->
    <?php echo form_open(current_url()); ?>
        <?php echo $custom_error; ?>

        <h2>Nueva Empresa</h2>

        <div class="controls controls-row">
        <div class="span3">
       <?php
          echo form_label('Tipo de Identificación <span class="required">*</span>', 'tipo_id');
          $select = array(
                            '' => 'Seleccione...'
                        );
          foreach($tipo_id as $row) {
              $select[$row->CODTIPODOCUMENTO] = $row->NOMBRETIPODOC;
           }
            echo form_dropdown('tipo_id', $select,'','id="tipo_id" class="span3" placeholder="seleccione..." ');


            echo form_error('tipo_id','<div>','</div>');
        ?>
        </div>
        <div class="span3">
        <?php

           echo form_label('Numero Identificacion<span class="required">*</span>', 'n_id');
           $datan_id = array(
                      'name'        => 'n_id',
                      'id'          => 'n_id',
                      'value'       => set_value('n_id'),
                      'maxlength'   => '20',
                      'class'       => 'span3',
                      'required'    => 'required'
                    );

           echo form_input($datan_id);
           echo form_error('n_id','<div>','</div>');
        ?>
        </div>
        </div>
        <div class="controls controls-row">
        <div class="span3">
        <?php

           echo form_label('Razón Social<span class="required">*</span>', 'r_soc');
           $datar_soc = array(
                      'name'        => 'r_soc',
                      'id'          => 'r_soc',
                      'value'       => set_value('r_soc'),
                      'maxlength'   => '100',
                      'class'       => 'span6',
                      'required'    => 'required'
                    );

           echo form_input($datar_soc);
           echo form_error('r_soc','<div>','</div>');
        ?>
        </div>
        </div>
        <div class="controls controls-row">
        <div class="span3">
       <?php
         echo form_label('CIIU<span class="required">*</span>', 'ciu');  
               $select = array(
                            '' => 'Seleccione...'
                        );
              foreach($ciu as $row) {
                  $select[$row->CLASE] = $row->CLASE.' - '.$row->DESCRIPCION;
               }
          echo form_dropdown('ciu', $select,'','id="ciu" class="chosen span8" data-placeholder="seleccione..." ');
         
           echo form_error('ciu','<div>','</div>');
        ?>
        </div>
        </div>
        
        <div class="controls controls-row">
        <div class="span3">
        <?php
         echo form_label('Pais<span class="required">*</span>', 'pais_id');  
              $select = array(
                            '' => 'Seleccione...'
                        );
              foreach($pais as $row) {
                  $select[$row->CODPAIS] = $row->NOMBREPAIS;
               }
          echo form_dropdown('pais_id', $select,'','id="pais_id" class="span3" data-placeholder="seleccione..." ');
         
           echo form_error('pais_id','<div>','</div>');
        ?>
        </div>
        <div class="span3">
        <?php
         echo form_label('Regional<span class="required">*</span>', 'regional_id');  
               $select = array(
                            '' => 'Seleccione...'
                        );
              foreach($regional as $row) {
                  $select[$row->COD_REGIONAL] = $row->NOMBRE_REGIONAL;
               }
          echo form_dropdown('regional_id', $select,'','id="regional_id" class="span3" data-placeholder="seleccione..." ');
         
           echo form_error('regional_id','<div>','</div>');
        ?>
        </div>
        <div class="span2">
      <?php

           echo form_label('Ciudad<span class="required">*</span>', 'ciudad');
          
        ?>
        <select name="ciudad" id="ciudad" class="span2">
            <option value="">Seleccione...</option>
        </select>
        </div>
        </div>
        <div class="controls controls-row">
        <div class="span3">
         <?php

           echo form_label('Teléfono<span class="required">*</span>', 'telefono');
           $datatelefono = array(
                      'name'        => 'telefono',
                      'id'          => 'telefono',
                      'value'       => set_value('telefono'),
                      'maxlength'   => '12',
                      'class'       => 'span3',
                      'required'    => 'required'
                    );

           echo form_input($datatelefono);
           echo form_error('telefono','<div>','</div>');
        ?>
        </div>
        <div class="span3">
        <?php

           echo form_label('Celular<span class="required">*</span>', 'celular');
           $datacelular = array(
                      'name'        => 'celular',
                      'id'          => 'celular',
                      'value'       => set_value('celular'),
                      'maxlength'   => '10',
                      'class'       => 'span3',
                      'required'    => 'required'
                    );

           echo form_input($datacelular);
           echo form_error('celular','<div>','</div>');
        ?>
        </div>
        </div>
        <div class="controls controls-row">
        <div class="span3">
        <?php

           echo form_label('Nombre Representante Legal<span class="required">*</span>', 'norele');
           $datanorele = array(
                      'name'        => 'norele',
                      'id'          => 'norele',
                      'value'       => set_value('norele'),
                      'maxlength'   => '80',
                      'class'       => 'span3',
                      'required'    => 'required'
                    );

           echo form_input($datanorele);
           echo form_error('norele','<div>','</div>');
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
           echo form_error('direccion','<div>','</div>');
        ?>
        </div>
        </div>
        <div class="controls controls-row">
        <div class="span3">
        <?php

           echo form_label('Número de Identificación Representante Legal<span class="required">*</span>', 'nidrele');
           $datanidrele = array(
                      'name'        => 'nidrele',
                      'id'          => 'nidrele',
                      'value'       => set_value('nidrele'),
                      'maxlength'   => '12',
                      'class'       => 'span3',
                      'required'    => 'required'
                    );

           echo form_input($datanidrele);
           echo form_error('nidrele','<div>','</div>');
        ?>
        </div>
        <div class="span3">
        <?php

           echo form_label('Correo Electrónico<span class="required">*</span>', 'email');
           $dataemail = array(
                      'name'        => 'email',
                      'id'          => 'email',
                      'value'       => set_value('email'),
                      'maxlength'   => '50',
                      'class'       => 'span3',
                      'placeholder' => 'ejemplo@sudominio.com',
                      'required'    => 'required'
                    );

           echo form_input($dataemail);
           echo form_error('email','<div>','</div>');
        ?>
        </div>
      </div>
        <div class="controls controls-row">
        <div class="span3">
        <?php

           echo form_label('Persona Contacto<span class="required">*</span>', 'contacto');
           $datacontacto = array(
                      'name'        => 'contacto',
                      'id'          => 'contacto',
                      'value'       => set_value('contacto'),
                      'maxlength'   => '80',
                      'class'       => 'span3',
                      'required'    => 'required'
                    );

           echo form_input($datacontacto);
           echo form_error('contacto','<div>','</div>');
        ?>
        </div>
        <div class="span3">
        <?php

           echo form_label('Identificación Contacto<span class="required">*</span>', 'id_cont');
           $dataid_cont = array(
                      'name'        => 'id_cont',
                      'id'          => 'id_cont',
                      'value'       => set_value('id_cont'),
                      'maxlength'   => '10',
                      'class'       => 'span3',
                      'required'    => 'required'
                    );

           echo form_input($dataid_cont);
           echo form_error('id_cont','<div>','</div>');
        ?>
        </div>
        </div>
        <div class="controls controls-row">
        <div class="span3">
        <?php
         echo form_label('Cargo<span class="required">*</span>', 'cargo_id');                               
              $select = array(
                            '' => 'Seleccione...'
                        );
              foreach($cargos as $row) {
                  $select[$row->IDCARGO] = $row->NOMBRECARGO;
               }
            echo form_dropdown('cargo_id', $select,'','id="cargo" class="span3" placeholder="seleccione..." ');

         
           echo form_error('cargo_id','<div>','</div>');
        ?>
        </div>
        <div class="span3">
           <?php
            echo form_label('Metodo de Contacto<span class="required">*</span>', 'm_contacto');                               
              $select = array(
                            '' => 'Seleccione...'
                        );
              foreach($metodo as $row) {
                  $select[$row->COD_METODO_CONTACTO] = $row->DESCRIPCION;
               }
               echo form_dropdown('m_contacto', $select,'','id="m_contacto" class="span3" placeholder="seleccione..." ');

         
               echo form_error('m_contacto','<div>','</div>');
           ?>
        </div>
        </div>   
        <div class="controls controls-row"></div>
        <div class="controls controls-row">
        <p class="pull-right">
                <?php  echo anchor('crearempresa', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?>
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
  <script type="text/javascript">
  //style selects
    var config = {
      '.chosen'           : {}
    }
    for (var selector in config) {
      $(selector).chosen(config[selector]);
    }

  </script>
<!--Script para select dependientes-->
<script type="text/javascript">
        $(document).ready(function() {
            $("#regional_id").change(function() {
                $("#regional_id option:selected").each(function() {
                    regional_id = $('#regional_id').val();
                    $.post("<?php echo base_url(); ?>index.php/crearempresa/llenarcombo", {
                        regional_id : regional_id
                    }, function(data) {
                        $("#ciudad").html(data);
                    });
                });
            })
        });
    </script>
