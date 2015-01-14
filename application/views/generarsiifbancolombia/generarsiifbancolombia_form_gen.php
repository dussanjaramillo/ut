<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<script type="text/javascript" language="javascript" charset="utf-8">
$(document).ready(function() {
  $('#fechadesde').datepicker();
  $('#fechahasta').datepicker();
  }
);
</script>

<?php     

echo form_open(current_url()); ?>
<?php echo $custom_error; ?>

<div align="center">

          <h2>GENERAR SIIF BANCOLOMBIA</h2>
          <br>
          <br>

          <table border="0" align="center">
            <tr>
              <td>

                  <table width="601" border="0" align="center">
                        
                        <tr>
                          <td align="center" width="97">

                            <?php echo form_label('Fecha Inicio: <span class="required">*</span>', 'fechadesde');?>

                          </td>
                          <td align="center" width="189">
                            <?php echo form_label('Fecha Fin: <span class="required">*</span>', 'fechahasta');?>
                             
                          </td>
                        </tr>
                        <tr>
                          <td align="center" width="94">

                            <div class="input-append date" id="fechadesde" data-date="dateValue: Customer.DateOfBirth" data-date-format="dd/mm/yyyy">
                            <input class="span2" size="16" name="vigenciadesde"  id="vigenciadesde" required type="text"  value="<?php echo date("d/m/Y"); ?>"  />
                            <span class="add-on"><i class="icon-calendar"></i></span>
                            </div>

                          </td>
                          <td align="center" width="193">

                            <div class="input-append date" id="fechahasta" data-date="dateValue: Customer.DateOfBirth" data-date-format="dd/mm/yyyy">
                            <input class="span2" size="16" name="vigenciahasta"  id="vigenciahasta" required type="text" value="<?php echo date("d/m/Y"); ?>"   />
                            <span class="add-on"><i class="icon-calendar"></i></span>
                            </div>

                          </td>
                        </tr>
                        
                    </table>
                  </td>
                </tr>
              </table>

              <br>
              <br>

            <table border="0" align="center" width="300">
              <tr>
                <td align="center" width="150">
                  <input type="radio" name="group1" value="e-collect" checked> E-Collect </input>
                </td>
                <td align="center" width="150">
                  <input type="radio" name="group1" value="planillaunica"> Planilla Única </input>
                </td>
              </tr>
            </table>


        </div>

          <br>
          <br>

          <br>
          <br>
        <div align="center">
        <?php  echo anchor('admestructuras', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?>
        <?php 
        $data = array(
               'name' => 'button',
               'id' => 'submit-button',
               'value' => 'aceptar',
               'type' => 'submit',
               'content' => '<i class="fa fa-floppy-o fa-lg"></i> Aceptar',
               'class' => 'btn btn-success'
               );

        echo form_button($data);    
        ?>
        </div>


<?php echo form_close(); ?>

  <script type="text/javascript">
  //style selects
    var config = {
      '.chosen'           : {}
    }
    for (var selector in config) {
      $(selector).chosen(config[selector]);
    }

  </script>

