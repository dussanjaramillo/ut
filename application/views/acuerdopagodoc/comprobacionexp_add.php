<form id="form_volver" action="<?= base_url('index.php/acuerdopagodoc/Gestion_EliminarPlantillas') ?>" method="post" >
     <input type="hidden" id="cod_fiscalizacion" name="cod_fiscalizacion" value="<?php echo $cod_fiscalizacion; ?>" readonly>
</form>
<form id="form1" action="<?= base_url('index.php/acuerdopagodoc/Gestion_EliminarSoporte') ?>" method="post" >
    <input type="hidden" id="cod_soporte" name="cod_soporte">  
    <input type="hidden" id="cod_fiscalizacion" name="cod_fiscalizacion" value="<?php echo $cod_fiscalizacion; ?>" readonly>  
    <?php
    echo form_hidden('cod_respuesta', $cod_respuesta);
    ?>
</form>  
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<?php
$attributes = array("id" => "myform");
echo form_open_multipart("acuerdodepago/gestionarAcuerdo", $attributes);
echo form_hidden('cod_fiscalizacion', $cod_fiscalizacion);
echo form_hidden('cod_respuesta', $cod_respuesta);
?>  
<center>
    <h2>Acuerdo de Pago</h2>
    <h3>Verificación de Archivos Subidos</h3>
</center>
<br>
<div  style="width: 85%; background: white; border-color: black; border: 1px solid grey; margin: auto; overflow: hidden;alignment-adjust: central ;padding: 15px 50px 0">
    <br>
    <div class="table-responsive">
        <table class="table table-bordered" width="100%" border="1" align="center">
            <tr style="background-color: #FC7323; color: #FFF">
                <td width="70" rowspan="2"><div align="center"><strong>Documento</strong></div></td>
                <td width="71" rowspan="2"><div align="center"><strong>Fecha Radicación  </strong></div></td>
                <td width="71" rowspan="2"><div align="center"><strong>Numero Radicación  </strong></div></td>
                <td colspan="3"><div align="center"><strong>Gestión </strong></div></td>
            </tr>
            <tr style="background-color: #FC7323; color: #FFF">
<!--                <td width="75"><div align="center"><strong>Eliminar</strong></div></td>-->
                <td width="75" colspan='2'><div align="center"><strong>Visualizar</strong></div></td>
            </tr>
            <?php
            for ($i = 0; $i < sizeof($archivos); $i++) {
//                $data_1 = array(
//                    'name' => 'eliminar',
//                    'id' => 'eliminar',
//                    'value' => '',
//                    'style' => 'margin:10px',
//                    'onclick' => 'eliminacion(' . $archivos[$i]['ID_EXPEDIENTE'] . ')'
//                );
                $data_2 = array(
                    'name' => 'button',
                    'id' => 'ir',
                    'value' => 'seleccionar',
                    'content' => '<i class="fa fa-repeat"></i> Continuar con el Proceso',
                    'class' => 'btn btn-success ir'
                );
                $data_3 = array(
                    'name' => 'button',
                    'id' => 'cancelar',
                    'value' => 'info',
                    'content' => '<i class="fa fa-minus-circle"></i> Cancelar',
                    'class' => 'btn btn-warning'
                );
                ?>
                <tr>
                    <td><?php echo $archivos[$i]['NOMBRE_DOCUMENTO']; ?></td>
                    <td><?php echo $archivos[$i]['FECHA_RADICADO']; ?></td>
                    <td><?php echo $archivos[$i]['NUMERO_RADICADO']; ?></td>
<!--                    <td><div align="center">
                            <?php echo form_radio($data_1); ?>
                        </div></td>-->
                    <td>
                        <center>
                            <?php echo anchor(base_url() . $archivos[$i]['RUTA_DOCUMENTO'], '<i class="fa fa-eye"></i>', 'target=_blank' , 'class="btn btn-default"'); ?>
                        </center>
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>
    </div>
    <br>
</div>
<br>
<center>
    <?php echo form_button($data_2) . " " . form_button($data_3); ?>
    <?php echo form_close(); ?>
    <script>
        $("#envia").click(function(){
           var ruta = $(this).attr('ruta'); 
           window.open(ruta, '_blank');
        });
        
        $("#preloadmini").hide();
        jQuery(".preload, .load").hide();
        var cantidad_documentos = 0;
        function eliminacion(valor) {
            $('#cod_soporte').val(valor);
            $("#form1").submit();
        }
        $('#cancelar').click(function() {
            window.history.back(-1);
        });
        $("#generar").click(function() {
            var Descripcion = $('#descripcion').val();
            $('#comentario').val(Descripcion);
            $('#myform').submit();

        });
        $("#ir").click(function() {
            window.location = '<?= base_url('index.php/acuerdodepago')?>';
            //$('#form_volver').submit();
            
        });
        $("#fecha_radicado").datepicker({
            dateFormat: "yy/mm/dd",
            maxDate: "0"
        });

        $('#resultado').dialog({
            autoOpen: true,
            width: 900,
            height: 570,
            modal: true,
            title: "Expediente de Acuerdo de Pago",
            close: function() {
                $('#resultado *').remove();
            }
        });
    </script> 
    <style>
        .columna_derecha {
            float:right; /* Alineación a la derecha */
            width:20%;        
        }

        .columna_izquierda {
            float:left; /* Alineación a la izquierda */
            width:50%;
            margin-left:10%;
        }

        .columna_central {
            margin-left:20%; /* Espacio para la columna izquierda */
            margin-right:10%; /* Espacio para la columna derecha */
        }
    </style>