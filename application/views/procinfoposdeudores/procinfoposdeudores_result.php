<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<div class="center-form-large">
       

       <?php echo $custom_error; ?>
        <h2 align="center">Resultado Cargue Archivo Confecamaras</h2>
   
        
      
        <?php echo form_open("cargarextractoasobancaria/exportarerror"); ?>
        <table align="center">
          <tr align="center">
            <td align="center">
               <p><?php echo "Cantidad Total De Registros: " .$registros; ?></p>

            </td>
          </tr>
          <tr align="center">
            <td align="center">
              	<?php  echo anchor('procinfoposdeudores/add', '<i class="icon-remove"></i> Regresar', 'class="btn"'); ?>
                
                <!--/*<?php 
                //$data = array(
                       //'name' => 'button',
                       //'id' => 'submit-button',
                       //'value' => 'Aceptar',
                       //'type' => 'submit',
                       //'content' => '<i class="fa fa-cloud-upload fa-lg"></i> Aceptar',
                       //'class' => 'btn btn-success'
                       //);

                //echo form_button($data);    
                ?>*/-->
            </td>
          </tr>
        </table> 
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