<script type="text/javascript" language="javascript" charset="utf-8">
    function ver() {
        document.getElementById('ruta').value = document.getElementById('ruta').value + "-" + document.getElementById('archivo').value;
    }
</script>

<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="center-form-large">

    <?php
    $attributes = array('onsubmit' => 'return comprobarextension()', "id" => "myform");
    echo form_open_multipart("procesojudicial/Carga_SoportesLibrarMandamiento", $attributes);
    $fecha_hoy = date("Y/m/d H:i:s", strtotime("-6 hour"));
    echo form_hidden('tb_fecha', $fecha_hoy);
    echo form_hidden('cod_titulo', $id_titulo);
    ?>
    <br>
    <div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>
        <?php
        if(isset($message)){
            echo $message;
        }else{
            echo "Solo se admiten archivos en PDF";
        }        
        ?>
    </div>   
    
    <br>
    <div class="controls ">           

        <div style="overflow: hidden; clear: both; margin:230px 0; width: 100%; margin: 0 auto; text-align: center">
            <h2>Documentos Soporte de Librar Mandamiento</h2><br><br>
            <?php
            echo form_label('<div style="color: #FC7323"><b>Auto que Librá Mandamiento <span class="required"></span></b></div>', 'lb_cargar1');
            $data = array(
                'multiple' => '',
                'name' => 'userfile[]',
                'id' => 'imagen2',
                'class' => 'validate[required]'
            );
            echo form_upload($data);
            ?>
        </div>

    </div>     
  
<br>
<div class="controls controls-row">
    <p class="pull-right">
        <br><br>
        <?php echo anchor('procesojudicial/Lista_SoportesLibrarMandamiento', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?>
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
        if ($("#imagen1").val() != "") {
            var archivo = $("#imagen1").val();
            var archivo2 = $("#imagen2").val();
            var extensiones_permitidas = new Array(".pdf");
            var mierror = "";
            //recupero la extensi�n de este nombre de archivo
            var extension = (archivo.substring(archivo.lastIndexOf("."))).toLowerCase();
            var extension2 = (archivo2.substring(archivo2.lastIndexOf("."))).toLowerCase();
            //alert (extension);
            //compruebo si la extensi�n est� entre las permitidas
            var permitida = false;
            for (var i = 0; i < extensiones_permitidas.length; i++) {
                if (extensiones_permitidas[i] == extension && extensiones_permitidas[i] == extension2) {
                    permitida = true;
                    break;
                }
            }
            if (!permitida) {
                jQuery("#imagen1").val("");
                jQuery("#imagen2").val("");
                mierror = "Comprueba la extensi�n de los archivos a subir.\nS�lo se pueden subir archivos con extensiones: " + extensiones_permitidas.join();
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
<style type="text/css">
    div.preload{
        position: fixed;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        background-color: white;
        opacity: 0.8;
        z-index: 10000;
    }

    div img.load{
        position: absolute;
        left: 50%;
        top: 50%;
        margin-left: -64px;
        margin-top: -64px;
        z-index: 15000;
    }
    .columna_derecha {
        float:right; /* Alineaci�n a la derecha */
        width:250px;
        //border:solid lightblue 1px;

    }

    .columna_izquierda {
        float:left; /* Alineaci�n a la izquierda */
        width:250px;
        //border:solid lightblue 1px;

    }
    .columna_central {
        margin-left:450px; /* Espacio para la columna izquierda */
        margin-right:350px; /* Espacio para la columna derecha */
        //border:solid navy 1px;

    } 
</style>