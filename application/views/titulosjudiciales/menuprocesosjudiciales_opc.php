<style type="text/css">

    .columna_derecha {
        float:right; /* Alineaci�n a la derecha */
        width:310px;
        //border:solid lightblue 1px;

    }

    .columna_izquierda {
        float:left; /* Alineaci�n a la izquierda */
        width:290px;
        //border:solid lightblue 1px;

    }

    .columna_central {
        margin-left:350px; /* Espacio para la columna izquierda */
        margin-right:350px; /* Espacio para la columna derecha */
        //border:solid navy 1px;

    }
</style>
<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
if (isset($message)) {
    echo $message;
}
?>
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<div class="center-form-large-20">
    <?php
    echo form_open('procesojudicial/Redireccionar_Menu');
    ?>
    <?php //echo $custom_error; ?>
    <br>
    <center><h2>Registro de Gestión de Proceso Judicial</h2>
        <h2>Títulos Ejecutivos</h2></center>
    <br>
    <div class="controls controls-row">            
        <?php
        $data = array(
            'name' => 'id_opcion',
            'id' => 'id_opcion',
            'checked' => FALSE,
            'style' => 'margin: 10px',
        );
        ?>
        <?php if ($admin) { ?>  
            <div class="controls columna_izquierda">            
                <div class="span4">                                           
                    <?php echo form_radio($data, 'procesojudicial/Recepcion_Titulos'); ?>Registrar Titulo<br>
                    <?php echo form_radio($data, 'procesojudicial/Lista_Asignacion_Abogado'); ?>Asignar Abogado<br>                        
                    <?php echo form_radio($data, 'procesojudicial/Lista_Verificar_Exigibilidad'); ?>Verificar Exigibilidad<br>
                    <?php echo form_radio($data, 'procesojudicial/Lista_Presentar_Demanda'); ?>Presentar Demanda<br>
                    <?php echo form_radio($data, 'procesojudicial/Lista_Resultado_Demanda'); ?>Registrar Resultado de la Demanda<br>
                    <?php echo form_radio($data, 'procesojudicial/Lista_Subsanar_Demanda'); ?>Demanda Subsanada<br>
                    <?php echo form_radio($data, 'procesojudicial/Lista_VerificarMedidasCautelares'); ?>Verificar Medidas Cautelares<br>
                    <?php echo form_radio($data, 'procesojudicial/Lista_CargaFDP'); ?>Auto de Finalización de Proceso<br>
                    <?php echo form_radio($data, 'procesojudicial/Lista_AcuerdoPagos'); ?>Verificación de Acuerdo de Pago<br>
                </div>
            </div> 
            <div class="controls columna_central"> 
                <div class="span5">
                    <?php echo form_radio($data, 'procesojudicial/Lista_Demanda_Retirada'); ?>Demanda Retirada<br>                
                    <?php echo form_radio($data, 'procesojudicial/Lista_Nueva_Demanda'); ?>Nueva Presentacion Demanda Rechazada<br>
                    <?php echo form_radio($data, 'procesojudicial/Lista_SoportesLibrarMandamiento'); ?>Libra Mandamiento<br>
                    <?php echo form_radio($data, 'procesojudicial/Lista_Demandado_Excepciona'); ?>Registrar Si Demandado Excepciona<br>
                    <?php echo form_radio($data, 'procesojudicial/Lista_Verificar_Proferimiento'); ?>Verificar proferimiento del auto que ordena continuar<br>
                    <?php echo form_radio($data, 'procesojudicial/Lista_Pronunciamiento_Excepcion'); ?>Descorrer Traslado de las Excepciones<br>
                    <?php echo form_radio($data, 'procesojudicial/Lista_MedidasCautelares'); ?>Avalúo bienes embargados o secuestrados<br>
                    <?php echo form_radio($data, 'procesojudicial/Lista_PagoRemateBienes'); ?>Verificar Pago de Remate<br> 
                </div>
            </div>
            <div class="controls columna_derecha"> 
                <div class="span4">     
                    <?php echo form_radio($data, 'procesojudicial/Lista_Pruebas_Decretadas'); ?>Verificar auto que cita audiencia<br>             
                    <?php echo form_radio($data, 'procesojudicial/Lista_Verificar_Sentencia'); ?>Asistir audiencia de instrucción y fallo<br>
                    <?php echo form_radio($data, 'procesojudicial/Lista_Generar_Liquidacion'); ?>Registrar recibido de Liquidación<br>
                    <?php echo form_radio($data, 'procesojudicial/Lista_Entregar_Liquidacion'); ?>Liquidación Radicada<br>
                    <?php echo form_radio($data, 'procesojudicial/Lista_ModificarTitulos'); ?>Modificación de Titulos<br>
                    <?php echo form_radio($data, 'procesojudicial/Lista_DemandasApoderadoNoActuo'); ?>Rechazar Demanda<br>
                    <?php echo form_radio($data, 'procesojudicial/Lista_CostasSena'); ?>Costas a Cargo del SENA<br>
                    <?php echo form_radio($data, 'procesojudicial/Lista_PosiblesAcuerdoPago'); ?>Realizar Acuerdo de Pago<br>
                    <?php echo form_error('id_opcion', '<div>', '</div>'); ?>
                </div>
            </div>
        <?php } elseif ($grupo == '61' || $grupo == '62') { ?>  
            <div class="controls columna_izquierda">            
                <div class="span4">                                           
                    <?php echo form_radio($data, 'procesojudicial/Recepcion_Titulos'); ?>Registrar Titulo<br>
                    <?php echo form_radio($data, 'procesojudicial/Lista_Asignacion_Abogado'); ?>Asignar Abogado<br> 
                    <?php echo form_radio($data, 'procesojudicial/Lista_ModificarTitulos'); ?>Modificación de Titulos<br>
                    
                </div>
            </div>                 
        <?php } elseif ($grupo == '45') { ?>  
            <div class="controls columna_izquierda">            
                <div class="span4">                                                                  
                   <?php echo form_radio($data, 'procesojudicial/Lista_Verificar_Exigibilidad'); ?>Verificar Exigibilidad<br>
                    <?php echo form_radio($data, 'procesojudicial/Lista_Presentar_Demanda'); ?>Presentar Demanda<br>
                    <?php echo form_radio($data, 'procesojudicial/Lista_Resultado_Demanda'); ?>Registrar Resultado de la Demanda<br>
                    <?php echo form_radio($data, 'procesojudicial/Lista_Subsanar_Demanda'); ?>Demanda Subsanada<br>
                    <?php echo form_radio($data, 'procesojudicial/Lista_VerificarMedidasCautelares'); ?>Verificar Medidas Cautelares<br>
                    <?php echo form_radio($data, 'procesojudicial/Lista_CargaFDP'); ?>Auto de Finalización de Proceso<br>
                    <?php echo form_radio($data, 'procesojudicial/Lista_AcuerdoPagos'); ?>Verificación de Acuerdo de Pago<br>
                </div>
            </div> 
            <div class="controls columna_central"> 
                <div class="span5">
                    <?php echo form_radio($data, 'procesojudicial/Lista_Demanda_Retirada'); ?>Demanda Retirada<br>                
                    <?php echo form_radio($data, 'procesojudicial/Lista_Nueva_Demanda'); ?>Nueva Presentacion Demanda Rechazada<br>
                    <?php echo form_radio($data, 'procesojudicial/Lista_SoportesLibrarMandamiento'); ?>Libra Mandamiento<br>
                    <?php echo form_radio($data, 'procesojudicial/Lista_Demandado_Excepciona'); ?>Registrar Si Demandado Excepciona<br>
                    <?php echo form_radio($data, 'procesojudicial/Lista_Verificar_Proferimiento'); ?>Verificar proferimiento del auto que ordena continuar<br>
                    <?php echo form_radio($data, 'procesojudicial/Lista_Pronunciamiento_Excepcion'); ?>Descorrer Traslado de las Excepciones<br>
                    <?php echo form_radio($data, 'procesojudicial/Lista_MedidasCautelares'); ?>Avalúo bienes embargados o secuestrados<br>
                    <?php echo form_radio($data, 'procesojudicial/Lista_PagoRemateBienes'); ?>Verificar Pago de Remate<br> 
                </div>
            </div>
            <div class="controls columna_derecha"> 
                <div class="span4">     
                    <?php echo form_radio($data, 'procesojudicial/Lista_Pruebas_Decretadas'); ?>Verificar auto que cita audiencia<br>             
                    <?php echo form_radio($data, 'procesojudicial/Lista_Verificar_Sentencia'); ?>Asistir audiencia de instrucción y fallo<br>
                    <?php echo form_radio($data, 'procesojudicial/Lista_Generar_Liquidacion'); ?>Registrar recibido de Liquidación<br>
                    <?php echo form_radio($data, 'procesojudicial/Lista_Entregar_Liquidacion'); ?>Liquidación Radicada<br>
                    <?php echo form_radio($data, 'procesojudicial/Lista_ModificarTitulos'); ?>Modificación de Titulos<br>
                    <?php echo form_radio($data, 'procesojudicial/Lista_DemandasApoderadoNoActuo'); ?>Rechazar Demanda<br>
                    <?php echo form_radio($data, 'procesojudicial/Lista_CostasSena'); ?>Costas a Cargo del SENA<br>
                    <?php echo form_radio($data, 'procesojudicial/Lista_PosiblesAcuerdoPago'); ?>Realizar Acuerdo de Pago<br>
                    <?php echo form_error('id_opcion', '<div>', '</div>'); ?>
                </div>
            </div>
        <?php } ?>
    </div>
    <br>
    <div class="controls columna_central">
        <center>
            <?php
            $data = array(
                'name' => 'enviar',
                'id' => 'enviar',
                'value' => 'Aceptar',
                'type' => 'submit',
                'content' => '<i class="fa fa-floppy-o fa-lg"></i> Confirmar',
                'class' => 'btn btn-success push'
            );
            echo form_button($data);
            ?>
            <?php echo anchor('', '<i class="icon-remove"></i>Salir', 'class="btn"'); ?>
        </center>
    </div>
    <?php echo form_close(); ?>
</div>
<script>
    jQuery(".preload, .load").hide();
    $('.push').click(function() {
        $(".preload, .load").show();
        setTimeout(function() {
            jQuery(".preload, .load").hide();
        }, 2000);
    });
</script> 