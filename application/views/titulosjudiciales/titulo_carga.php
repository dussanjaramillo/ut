<script type="text/javascript" language="javascript" charset="utf-8">
function ver(){
      document.getElementById('ruta').value = document.getElementById('ruta').value + "-" + document.getElementById('archivo').value;
}
</script>

<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<?php 
if (isset($message)){
    echo $message;
   }
?>
<div class="center-form-large">
         
    <?php $attributes = array('onsubmit' => 'return comprobarextension()', "id" => "myform");
        echo form_open_multipart("titulo/carga", $attributes);
    ?>
    <br>
    <div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Solo se admiten archivos en PDF</div>   
        <h2>Cargar Archivo del Titulo</h2>
        <br>
        <div class="controls ">            
            <div class="span4">      
                    <div style="overflow: hidden; clear: both; margin:210px 0; width: 90%; margin: 0 auto; text-align: center">
                        <?php
                        $data = array(
                            'name' => 'userfile',
                            'id' => 'imagen',
                            'class' => 'validate[required]'
                        );
                        echo form_upload($data);
                        ?>
                    </div>
        
            </div>       
        </div>   
        <br>
        <div class="controls controls-row">
        <p class="pull-right">
            <br><br>
            <?php  echo anchor('#', '<i class="fa fa-folder-open"></i> Digitalizar Titulo', 'class="btn"'); ?>
                <?php  echo anchor('titulo', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?>
                <?php 
                $data = array(
                       'name' => 'button',
                       'id' => 'submit-button',
                       'value' => 'Confirmar',
                       'type' => 'submit',
                       'content' => '<i class="fa fa-cloud-upload fa-lg"></i> Confirmar',
                       'class' => 'btn btn-success'
                       );

                echo form_button($data);    
                ?>
        </p>
        </div>
        <?php echo form_close(); ?>
</div>

<script type="text/javascript" language="javascript" charset="utf-8">
  function comprobarextension() {
    if ($("#imagen").val() != "") {
        var archivo = $("#imagen").val();
        var extensiones_permitidas = new Array(".pdf");
        var mierror = "";
        //recupero la extensión de este nombre de archivo
        var extension = (archivo.substring(archivo.lastIndexOf("."))).toLowerCase();
        //alert (extension);
        //compruebo si la extensión está entre las permitidas
        var permitida = false;
        for (var i = 0; i < extensiones_permitidas.length; i++) {
            if (extensiones_permitidas[i] == extension) {
              permitida = true;
              break;
            }
        }
        if (!permitida) {
          jQuery("#imagen").val("");
          mierror = "Comprueba la extensión de los archivos a subir.\nSólo se pueden subir archivos con extensiones: " + extensiones_permitidas.join();
        }
        //si estoy aqui es que no se ha podido submitir
        if (mierror != "") {
          alert(mierror);
          return false;
        }
        return true;
    }
  }
  </script>
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
   