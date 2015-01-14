<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>

<div align="center">
        <?php     

        echo form_open(current_url()); ?>

        <h2>Agregar Campo</h2>

        <?php echo form_hidden('idcodarchivo',$result->COD_ARCHIVO) ?>
        <?php echo form_hidden('fechacreacion',date("d/m/Y")) ?>
        
        <br>
        <br>
        <div>
          <table border="0" align="center">
            <tr>
              <td>

                  <table width="100%" border="0" align="center">
                        <tr>
                          <td colspan"2"><?php echo form_label('Archivo: <span class="required">*</span>', 'archivo');?></td>
                          <td colspan"2">
                          <?php 
                            $data = array(
                              'name'        => 'archivo',
                              'id'          => 'archivo',
                              'value'       => $result->NOMBRE_ARCHIVO,
                              'maxlength'   => '10',
                              'readOnly'    => 'readOnly'
                            );

                             echo form_input($data);
                             
                             echo form_error('archivo','<div>','</div>');
                            ?>
                          </td>
                        </tr>
                        
                    </table>
                  </td>
                </tr>
              </table>

        </div>

          <br>
          <br>

        <div class="center-form">

        <?php echo $custom_error; ?>
        <p>
        <?php
         echo form_label('Nombre Campo<span class="required">*</span>', 'nombrecampo');
           $datan = array(
                      'name'        => 'nombrecampo',
                      'id'          => 'nombrecampo',
                      'value'       => set_value('nombrecampo'),
                      'maxlength'   => '128'
                    );

           echo form_input($datan);
           echo form_error('nombrecampo','<div>','</div>');
        ?>
        </p>

        <p>
        <?php
         echo form_label('Tipo Registro <span class="required">*</span>', 'tiporeg');  
              foreach($tiposreg as $row) {
                  $select[$row->COD_TIPOREGISTRO] = $row->NOMBRE_TIPO;
               }
          echo form_dropdown('tiporeg', $select,'','id="tiporeg" data-placeholder="seleccione..." ');
         
           echo form_error('tiporeg','<div>','</div>');
        ?>

        </p>

        <p>
        <?php
         echo form_label('Posición<span class="required">*</span>', 'posi');
         $datap = array(
                      'name'        => 'posi',
                      'id'          => 'posi',
                      'value'       => set_value('posi'),
                      'maxlength'   => '10'
                    );

          echo form_input($datap);               
          echo form_error('posi','<div>','</div>');
                          
        ?>
        </p>

        <p>
        <?php
         echo form_label('Longitud <span class="required">*</span>', 'long');
         $datalong = array(
                      'name'        => 'long',
                      'id'          => 'long',
                      'value'       => set_value('long'),
                      'maxlength'   => '128'
                    );

          echo form_input($datalong);               
          echo form_error('long','<div>','</div>');
                          
        ?>
        </p>

        <p>
        <?php
         echo form_label('Alineación <span class="required">*</span>', 'aling');
         $dataaling = array(
                      'name'        => 'aling',
                      'id'          => 'aling',
                      'value'       => set_value('aling'),
                      'maxlength'   => '128'
                    );

          echo form_input($dataaling);               
          echo form_error('aling','<div>','</div>');
                          
        ?>
        </p>

        <p>
        <?php
         echo form_label('Carácter de Relleno <span class="required">*</span>', 'relleno');
         $datarelleno = array(
                      'name'        => 'relleno',
                      'id'          => 'relleno',
                      'value'       => set_value('relleno'),
                      'maxlength'   => '1'
                    );

          echo form_input($datarelleno);               
          echo form_error('relleno','<div>','</div>');
                          
        ?>
        </p>

        <?php
         echo form_label('Tipo Dato <span class="required">*</span>', 'tipodato');
         $datatipodato = array(
                        "NUMERICO",
                        "ALFANUMERICO",
                        "FECHA");

          echo form_dropdown('tipodato', $datatipodato,'','id="tipodato" data-placeholder="seleccione..." ');               
          echo form_error('tipodato','<div>','</div>');
                          
        ?>
        </p>

        <p>
        <?php
         echo form_label('Formato <span class="required">*</span>', 'formato');
         $dataformato = array(
                        "dd/mm/aaaa",
                        "dd-mm-aaaa",
                        "mm-aaaa",
                        "dd-mmm-aaaa",
                        "mmm/aaaa",
                        "mmm-aaaa",
                        "dd",
                        "aaaa",
                        "mm",
                        "hh:mm",
                        "hh:mm:ss",
                        "##,###.##",
                        "##.###.##",
                        "##,###",
                        "##.###",
                        "#####");

          echo form_dropdown('formato', $dataformato,'','id="formato" data-placeholder="seleccione..." ');
                         
          echo form_error('formato','<div>','</div>');
                          
        ?>
        </p>

        <p>
        <?php
         echo form_label('Separador de decimales <span class="required">*</span>', 'separator');
         $dataseparator = array(
                        ','        => 'Coma (,)',
                        ';'        => 'Punto y Coma (;)',
                        ''        => 'Ninguno',);

          echo form_dropdown('separator', $dataseparator,'','id="separator" data-placeholder="seleccione..." ');
                         
          echo form_error('separator','<div>','</div>');
                          
        ?>
        </p>

        <p>
        <?php
         echo form_label('Vista Consulta <span class="required">*</span>', 'vistacons');
         $datavistacons = array(
                      'name'        => 'vistacons',
                      'id'          => 'vistacons',
                      'value'       => set_value('vistacons'),
                      'maxlength'   => '80'
                    );

          echo form_input($datavistacons);               
          echo form_error('vistacons','<div>','</div>');
                          
        ?>
        </p>



        <p>
                <?php  echo anchor('admestructuras/camposarchivo/'.$this->uri->segment(3).'', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?>
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
   