<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
if (isset($message)) {
    echo $message;
}
?>
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<div style="background: #f0f0f0; width: 700px; margin: auto; overflow: hidden">
    <?php
    $attributes = array("id" => "myform");
    echo form_open("procesojudicial/Agregar_Resultado_Demanda", $attributes);
    ?>       
    <?php
    $fecha_hoy = date("Y/m/d H:i:s", strtotime("-6 hour"));
    echo form_hidden('tb_fecha', $fecha_hoy);
    echo form_hidden('cod_titulo', $id_titulo);
    ?>
    <?php echo $custom_error; ?>
    <br>

    <div style="overflow: hidden; width: 90%; margin: 0 auto">
        <?php if ($class != "") { ?>
            <div class="alert alert-<?php echo $class ?>"></div>
        <?php } ?>


    </div>
    <div style="overflow: hidden; width: 80%; margin: 0 auto">
        <h2 class="text-center">Registrar Resultado de la Demanda</h2>
    </div>
    <div style="overflow: hidden; width: 88%; margin: 0 auto">
        <br>     
        <div class="columna_derecha"> 
            <input type="radio"  id="opc_res" name="opc_res" value="rechazo">Rechazo 
            <br><br>
            <div class="competencia"  id="competencia" name="competencia">
                Por competencia?<select name="competencia" id="competencia" style="width:120px">
                    <option value="s"> Si</option>
                    <option value="n"> No</option>
                </select>

            </div>
        </div>
        <div class="columna_izquierda">            
            <input type="radio"  id="opc_res1" name="opc_res" value="librar_mandamiento" checked="checked">Librar Mandamiento
        </div>
        <div class="columna_central">   
            <input type="radio" class='at'  id="opc_res2" name="opc_res" value="inadmitida"> Inadmitida
             <input type="radio" class='at'  id="opc_res3" name="opc_res" value="negar_mandamiento"> Negar Mandamiento
        </div>

    </div>
    <br><br>
    <div style="overflow: hidden; width: 40%; margin: 0 auto">
        <div class="text-center">
            <p class="pull-right">
                <?php echo anchor('procesojudicial', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?>
                <?php
                $data = array(
                    'name' => 'button',
                    'id' => 'submit-button',
                    'value' => 'Confirmar',
                    'type' => 'submit',
                    'content' => '<i class="fa fa-floppy-o fa-lg"></i> Confirmar',
                    'class' => 'btn btn-success'
                );

                echo form_button($data);
                ?>
            </p>

        </div>
    </div>

    <?php echo form_close(); ?>
    <script>
        jQuery(".preload, .load").hide();
        $(".competencia").hide();
        $('#opc_res').click(function() {
            $.post("competencia", {}, function(data) {
                $("#competencia").html(data);
            });
            jQuery("#competencia").show();
        });
        $('#opc_res1').click(function() {
            $(".competencia").hide();
        });
        $('#opc_res2').click(function() {
            $(".competencia").hide();
        });
    </script>
    <style type="text/css">
        .columna_derecha {
            float:right; /* Alineación a la derecha */
            width:150px;
            //  border:solid lightblue 1px;

        }

        .columna_izquierda {
            float:left; /* Alineación a la izquierda */
            width:180px;
            //    border:solid lightblue 1px;

        }
        .columna_central {
            margin-left:190px; /* Espacio para la columna izquierda */
            margin-right:190px; /* Espacio para la columna derecha */
            // border:solid navy 1px;

        } 
    </style>