<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
$attributes = array("id" => "myform", "class" => "myform");
echo form_open_multipart("mcremate/Gestion_ConsignacionDinero", $attributes);
?>
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<input type="hidden" id="cod_avaluo" name="cod_avaluo" value="<?php echo $cod_avaluo; ?>">   
<input type="hidden" id="cod_coactivo" name="cod_coactivo" value="<?php echo $cod_coactivo; ?>"> 
<input type="hidden" id="respuesta" name="respuesta">  
<div class="informacion" id="informacion">
    <?php require_once('encabezado.php'); ?>
</div>
<br>
<div class="avaluo" id="avaluo" style="background: white;width: 90%; border-color: black; border: 1px solid grey; margin: auto; overflow: hidden;alignment-adjust: central ;padding: 15px 50px 0">
    <div class="controls controls-row">
        <center>

            <div class="span10 opciones" id="opciones" style="alignment-adjust: central">
                <?php
                echo form_label('<h3>¿Se Realizó la Consignación del Saldo?</h3>', 'recibio_documento');
                $data = array(
                    'name' => 'consignacion',
                    'id' => 'consignacion',
                    'value' => CONSIGNO_SALDO,
                    'style' => 'margin:10px',
                    'onclick' => 'redireccionar(\'S\')'
                );

                echo (form_radio($data) . 'Si Consigno');
                $data = array(
                    'name' => 'consignacion',
                    'id' => 'consignacion',
                    'value' => NO_CONSIGNO_SALDO,
                    'style' => 'margin:10px',
                    'onclick' => 'redireccionar(\'N\')'
                );

                echo (form_radio($data) . 'No Consigno');
                ?>
            </div>
        </center>      
    </div> <br>
    <div class="subir_documento" id="subir_documento" style="width: 91%; border-color: black; border: 1px solid grey; margin: auto; overflow: hidden;alignment-adjust: central ;padding: 15px 50px 0">
        <center>                   
            <?php
            if (sizeof($posturas) != 0) {
                ?>
                <div style="color: red"><h2>Datos del Postor</h2></div> 
                <table class="table" width="80%" border="1" cellspacing="0" cellpadding="0">
                    <tr>
                        <td bgcolor="#FC7323"><div align="center"><strong>Numero de Identificacion</strong></div></td>
                        <td bgcolor="#FC7323"><div align="center"><strong>Nombre</strong></div></td>
                        <td bgcolor="#FC7323"><div align="center"><strong>Bien Adjudicado</strong></div></td>
                    </tr>
                    <?php
                    for ($i = 0; $i < sizeof($posturas); $i++) {
                        ?>
                        <tr>
                            <td><?php echo $posturas[$i]['NUMERO_IDENTIFICACION']; ?></td>
                            <td><?php echo $posturas[$i]['NOMBRE']; ?></td>
                            <td><label class="radio">
                                    <div align="center">
                                        <input type="radio" name="optionsRadios" id="optionsRadios" value="<?php echo $posturas[$i]['NUMERO_IDENTIFICACION']; ?>" checked>
                                    </div>
                                </label></td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>                
                <?php
            }
            ?>
            <div class="controls controls-row">
                <center>
                        <?php
                        echo form_label('<h3>¿El postor aportó el pago del impuesto al Banco Popular?</h3>', 'recibio_documento');
                        $data = array(
                            'name' => 'impuesto',
                            'id' => 'impuesto',
                            'value' => 'S',
                            'style' => 'margin:10px',
                        );

                        echo (form_radio($data) . 'Si');
                        $data = array(
                            'name' => 'impuesto',
                            'id' => 'impuesto',
                            'value' => 'N',
                            'style' => 'margin:10px',
                        );

                        echo (form_radio($data) . 'No');
                        ?>
                </center>      
            </div> <br>
            <?php
            echo '';
            echo form_label('<b>Recibo de Consignacion:<span class="required"></span></b><br>', 'lb_cargar1');
            echo '<div class="alert-success" style="width: 81%; border-color: black; border: 1px solid grey;padding: 15px 50px 0">';
            $data = array(
                'multiple' => '',
                'name' => 'userfile[]',
                'id' => 'imagen',
                'class' => 'validate[required]'
            );
            echo form_upload($data);
            echo '<br><br>';
            echo '</div>';
            echo '<br>';
            echo '</div>';
            ?>

        </center>
        <br>
    </div>
    <br>
    <?php
    $data_1 = array(
        'name' => 'button',
        'id' => 'info',
        'value' => 'seleccionar',
        'content' => '<i class="fa fa-eye"></i> Informacion del Avaluo',
        'class' => 'btn  btn-success info'
    );
    $data_2 = array(
        'name' => 'button',
        'id' => 'aceptar',
        'type' => 'submit',
        'value' => 'seleccionar',
        'content' => '<i class="icon-ok"></i> Aceptar',
        'class' => 'btn btn-primary aceptar'
    );
    ?>
    <center>
        <?php
        echo form_button($data_2) . ' ' . form_button($data_1) . ' ';
        echo anchor('mcremate/Menu_GestionRemateA', '<i class="fa fa-reply"></i> Atras', 'class="btn"');
        ?>
    </center>
    <br>
</div>

<?php
echo form_close();
?>
<script>
    $(".informacion").hide();
    $(".preload, .load").hide();
    $(".subir_documento").hide();
    $("#info").click(function() {
        $(".informacion").show();
    });
    function redireccionar(valor) {
        if (valor == 'S') {
            $(".subir_documento").show();
        } else {
            $(".subir_documento").hide();
        }
    }
</script>

