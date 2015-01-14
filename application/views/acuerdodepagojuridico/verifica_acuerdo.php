<?php
$dataguardar        = array('name'=>'button','id'=>'guardar','value'=>'Guardar','type'=>'button','content'=>'<i class="fa fa-floppy-o fa-lg"></i> Guardar','class'=>'btn btn-success');
$datacancel         = array('name'=>'button','id'=>'cancel-button','value' => 'Cancelar','type' => 'button','onclick' => 'window.location=\''.base_url().'index.php/bandejaunificada/procesos\';','content' => '<i class="fa fa-minus-circle"></i> Cancelar','class' => 'btn btn-warning');
$dataadicionales    = array('name'=>'adicionales','id'=>'adicionales','style'=>'margin:10px');
$verificacion       = array('name'=>'verificacion','id'=>'verificacion','type'=>'hidden');
$tipoProceso        = array('name'=>'tipo','id'=>'tipo','type'=>'hidden','value'=>$tipo);
$ajustado           = array('name'=>'ajustado','id'=>'ajustado','type'=>'hidden','value'=>$ajustado);
$fiscalizacionPago  = array('name'=>'cod_coactivo','id'=>'cod_coactivo','type'=>'hidden','value'=>$cod_coactivo);
$nitPago            = array('name'=>'nit','id'=>'nit','type'=>'hidden','value'=>$nit);
$acuerdoPago        = array('name'=>'acuerdo','id'=>'acuerdo','type'=>'hidden','value'=>$acuerdo);
$attributes         = array('name'=>'devolucionFrm','id'=>'devolucionFrm','class' => 'form-inline', 'method' => 'POST', 'enctype' => 'multipart/form-data');

if (@$pago_inicial[0]['DIAS_MORA']>0):    
    $mora = @$pago_inicial[0]['DIAS_MORA'];
else:
    $mora = 0;
endif;
echo form_open_multipart(current_url(),$attributes);                
echo form_input($acuerdoPago);
echo form_input($tipoProceso);
echo form_input($fiscalizacionPago);
echo form_input($nitPago);
echo form_input($verificacion);
echo form_input($ajustado);
?>
<table align='center'>
    <tr>
        <td align='center' colspan='2'><h2>Documentos</h2></td>
    </tr>
    <tr>
        <td colspan='2' id='info'>
            
        </td>
    </tr>    
    <tr id='reciboPago' style="display:none">
            <?php $date          = date("d/m/Y"); ?>
            <td colspan='2' align='center'>
                <button id="<?= $cuotaDeuda ?>,<?= $date ?>,<?= $acuerdo ?>,<?= $cod_coactivo ?>,<?= $liquidacion ?>,<?= $nit ?>,<?= $procedencia ?>" type="button"  onclick="recibo(this.id)" class="generar btn btn-info" id="generar">Emisión Consulta Valor a Pagar</button>
            <td>           
    </tr>
    <tr>
        <td id='mensaje_tipo'>

        </td>
        <td id='check_decision'>
            <?php            
            if ($tipo == 'pago_minimo'):
                if (@$pago_inicial[0]['PROYACUPAG_ESTADO'] == 0):
                    $checked2 = TRUE;
                    $checked1 = FALSE;
                    else:
                    $checked2 = FALSE;
                    $checked1 = TRUE;
                endif;
                echo "&nbsp;&nbsp;SI".form_radio($dataadicionales,"1",$checked1)."&nbsp;&nbsp;";
                echo "&nbsp;&nbsp;NO".form_radio($dataadicionales,"0",$checked2)."&nbsp;";
            else:
                echo "&nbsp;&nbsp;SI".form_radio($dataadicionales,"1")."&nbsp;&nbsp;";
                echo "&nbsp;&nbsp;NO".form_radio($dataadicionales,"0")."&nbsp;";
            endif;                                           
            ?>
        </td>
    </tr>   
    <tr>
        <td colspan='2' id="TituloObs">
            
        </td>
    </tr>
    <tr>
        <td colspan='2' id="observaciones">
            
        </td>
    </tr>
    <tr>
        <td colspan='2' align='center'>
            <div style="display:none" id="error" class="alert alert-danger"></div>
        </td>
    </tr><tr><td><br></td></tr>
    <tr>
        <td align='center' colspan='2' id="botones">
            <?=
            form_button($dataguardar)."&nbsp;&nbsp;";
            echo form_button($datacancel);
            ?>
        </td>
    </tr>
</table>
<?php echo form_close(); ?>
<script>
    $(document).ready(function() {        
        var tipo = '<?php echo $tipo ?>';
        var estado = '<?php echo @$pago_inicial[0]['PROYACUPAG_ESTADO'] ?>';
        var mora = <?php echo @$mora ?>;
        
            switch (tipo){
                case 'condiciones':
                    msg = 'Estudio de condiciones y garantias propuestas';
                break;
                case 'solicitud_ajuste':
                    msg = 'Solicitud de Ajuste?';
                break;
                case 'pago_minimo':
                    msg = 'Realizó el Pago Mínimo?';
                    if (estado == '0' && mora <= 0){
                        $('#guardar').attr('disabled',true);
                    }
                    $('#TituloObs').append('<b>Dias en Mora <?php echo $mora ?> </b>');
                    $('input[name=adicionales]').attr("disabled",true);
                break;
                case 'garantias':
                    msg = 'Se han Aprobado las Garantías?';
                break;
                case 'verifica_garantias':
                    msg = 'Desea Modificar las Garantías?';
                    $('#observaciones').append("<textarea id='obser' style='width: 538px; height: 143px;'></textarea>");                    
                break;
                case 'autoriza_garantias':
                    msg = 'Autoriza que no se presenten garantías?';
                    $('#TituloObs').append('<b>Observaciones</b>');
                    $('#observaciones').append("<textarea id='obser' style='width: 538px; height: 143px;'></textarea>");
                    $('#info').append("<b>Comentarios</b>: <textarea id='observa' style='width: 442px; height: 35px;' readonly><?= @$excepcion->MOTIVOEXCEPCION ?></textarea>");                    
                break;
                case 'deudor_acuerdo':
                    msg = 'Deudor Firmó Facilidad de Pago?';
                break;
                case 'acto_admin':
                    $('#check_decision').hide();
                    $('#guardar').hide();
                    msg = 'La Facilidad fue notificado mediante Acto Administrativo';
                break;
                case 'acto_fin':
                    $('#check_decision').hide();
                    $('#guardar').hide();
                    msg = 'La Facilidad de Pago ha Finalizado';
                break;
                case 'presenta_recurso':
                    msg = 'El Deudor Presentó Recursos?';
                break;
                case 'ejecutar_garantias':
                    msg = 'Existen Garantias para Ejecutar?';
                break;
                case 'recurso_favorable':
                    msg = 'El Recurso es favorable al Deudor?';
                break;
                case 'pago_total':
                    msg = 'Se Efectuo el pago total de la deuda?';
                break;
                case 'fin_proceso':
                    msg = 'Fin de Proceso?';
                break;
                case 'incumplimiento':
                    $('#check_decision').hide();
                    $('#guardar').hide();
                    msg = 'Incumplimiento en Facilidad de pago';
                break;
                case 'nuevas_garantias':
                    msg = 'Se presentaron Nuevas Garantías?';
                break;
                case 'medida':
                    $('#check_decision').hide();
                    $('#guardar').hide();
                    msg = 'Proceso en Medidas Cautelares';
                break;
            }
       $('#mensaje_tipo').append('<td><b>'+msg+'</b></td>');        
    });  
    
    $('#guardar').click(function() {       
        var adicionales  = $('input[name="adicionales"]:checked').val();   
        var tipo = '<?php echo $tipo ?>';
        var url = '<?php echo base_url('index.php/acuerdodepagojuridico/guardar_verificacion')?>';
        var valida  = true;        
        if (adicionales == undefined){
            $('#error').show();            
            $('#error').html("<font color='red'><b>Seleccione alguna opción por favor</b></font>");
            $('#adicionales').focus();
            valida = false;
        }
        
            if (valida == true) { 
                if (adicionales == 1) {
                    var verificacion = 'S';
            } else {
                var verificacion = 'N';
            }
            if (tipo == 'condiciones' && verificacion == 'S'){
                $('#reciboPago').show();
                $('#botones').hide();        
                $('input[name=adicionales]').attr("disabled",true);
            }else {
                $("#verificacion").val(verificacion);
                $(".ajax_load").show("slow");
                $('#guardar').prop("disabled",true);
                $("#devolucionFrm").attr("action", url);
                $('#devolucionFrm').removeAttr('target');
                $('#devolucionFrm').submit();
            }            

        }
    });   
    
         function recibo(id){
         $(".ajax_load").show("slow");             
         var datos      = id.split(',');
         var valorcuota     = datos[0];
         var fecha          = datos[1];
         var acuerdo        = <?= $acuerdo ?>;
         var nit            = <?= $nit ?>;
         var cod_coactivo  = '<?= $cod_coactivo ?>';
         var razonsocial    = '<?= $razon ?>';
         var deuda    = <?= $deuda ?>;
         var capital    = <?= $capital ?>;
         var intereses    = <?= $intereses ?>;
         var estado    = <?= $estado ?>;
         var procedencia    = <?= $procedencia ?>;
         var url = "<?= base_url('index.php/acuerdodepagojuridico/generarrecibo') ?>";
         
            $('#modal').load(url,{nit : nit,
                razon : razonsocial, 
                valorcuota : valorcuota,
                fecha : fecha, 
                cod_coactivo : cod_coactivo, 
                acuerdo:acuerdo,
                deuda:deuda,
                capital:capital,
                intereses:intereses,
                nit:nit,
                estado:estado,
                procedencia:procedencia
            },function(data){       
                 $('#modal').modal('toggle');
                 $(".preloadminicuota").hide();
                 $(".generar" ).show();
                 $(".ajax_load").hide("slow");
                });
     }
</script>    

