<?php
$dataguardar        = array('name'=>'button','id'=>'guardar','value'=>'Guardar','type'=>'button','content'=>'<i class="fa fa-floppy-o fa-lg"></i> Guardar','class'=>'btn btn-success');
$datacancel         = array('name'=>'button','id'=>'cancel-button','value' => 'Cancelar','type' => 'button','onclick' => 'window.location=\''.base_url().'index.php/insolvencia/consultarTitulos\';','content' => '<i class="fa fa-minus-circle"></i> Cancelar','class' => 'btn btn-warning');
$dataadicionales    = array('name'=>'adicionales','id'=>'adicionales','checked'=>FALSE,'style'=>'margin:10px');
$verificacion       = array('name'=>'verificacion','id'=>'verificacion','type'=>'hidden');
$tipoProceso        = array('name'=>'gestion','id'=>'gestion','type'=>'hidden','value'=>$gestion);
$fiscalizacionPago  = array('name'=>'fiscalizacion','id'=>'fiscalizacion','type'=>'hidden','value'=>$fiscalizacion);
$nitPago            = array('name'=>'nit','id'=>'nit','type'=>'hidden','value'=>$nit);
$acuerdoPago        = array('name'=>'regimen','id'=>'regimen','type'=>'hidden','value'=>$regimen);
$titulo             = array('name'=>'titulo','id'=>'titulo','type'=>'hidden','value'=>$titulo);
$attributes         = array('name'=>'devolucionFrm','id'=>'devolucionFrm','class' => 'form-inline', 'method' => 'POST', 'enctype' => 'multipart/form-data');


echo form_open_multipart(current_url(),$attributes);                
echo form_input($acuerdoPago);
echo form_input($tipoProceso);
echo form_input($fiscalizacionPago);
echo form_input($nitPago);
echo form_input($verificacion);
echo form_input($titulo);
?>

<table align='center'>
    <tr>
        <!--<td align='center' colspan='2' id='titulo'><h2><div id='titulo' name='titulouno'></div></h2></td>-->
        <td align='center' colspan='2' id='titulouno'></td>
    </tr>
    <tr>
        <td colspan='2' id='info'>
            
        </td>
    </tr>    
    <tr>
        <td id='mensaje_tipo'>

        </td>
        <td id='check_decision' align='center'>
            <?=
               "&nbsp;".form_radio($dataadicionales,"1")."&nbsp;&nbsp;SI&nbsp;&nbsp;";
               echo "&nbsp;".form_radio($dataadicionales,"0")."&nbsp;&nbsp;NO";
            ?>
        </td>
    </tr> 
    <tr>
        <td id="cargar" style="display:none" colspan='2'>
            <p>
                <?php
                    $datafile = array(
                               'name'        => 'filecolilla',
                               'id'          => 'filecolilla',
                               'value'       => '',//$data->DOC_COLILLA,
                               'maxlength'   => '10',
                             );
                    echo "<br>".form_upload($datafile);
                ?>
             </p>
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
    var gestion = '<?php echo $gestion ?>';    
    if (gestion == 611 || gestion == 608){  
            $("#adicionales").prop('checked', 'checked');
        }
    if (gestion == 604 || gestion == 608 || gestion == 611){
        $('#filecolilla').change(function(){ 
                var extensiones_permitidas = new Array(".pdf"); 
                var archivo = $('#filecolilla').val();
                var extension = (archivo.substring(archivo.lastIndexOf("."))).toLowerCase();
                var permitida = false;

                for (var i = 0; i < extensiones_permitidas.length; i++) {
                    if (extensiones_permitidas[i] == extension) {
                    permitida = true;
                    break;
                    }
                } 
                    if (!permitida) {
                        $('#filecolilla').val('');
                        $('#error').show();
                        $('#error').html("<font color='red'><b>Comprueba la extensión de los archivos a subir. \nSólo se pueden subir archivos con extensiones: " + extensiones_permitidas.join()+"</b></font>");
                        $("#filecolilla").focus();
                    }else{
                        $('#error').html("<font color='red'><b>Archivo Subido Correctamente</b></font>");
                         $('#aproved').show();
                         $('#APROBADO').show();
                        return 1;
                    } 
              });
    }
        
    switch (gestion){
        case '593':
            var msg = 'La Documentación Cumple con los requisitos?'; 
            var tituloinicio = 'Validar Documentación';
            break;
        case '806':
            var msg = 'Existe titulo?'; 
            var tituloinicio = 'Validar Titulos';
            break;
        case '601':
            var msg = 'Resultado de Audiencia Aprobado?'; 
            var tituloinicio = 'Registrar resultado audiencia <br>reorganización';
            break;
        case '1342':
            $('#cargar').show();           
            var msg = 'Se ha Recibido el Pago?'; 
            var tituloinicio = 'Registrar Pago';
            break;
        case '1343':
            $('#cargar').show();           
            var msg = 'Se ha Recibido el Pago?'; 
            var tituloinicio = 'Registrar Pago';
        break;
        case '1347':
            var msg = 'Se ha Recibido el Pago?'; 
            var tituloinicio = 'Registrar Pago';
            break;
        case '1348':
            var msg = 'Existe titulo?'; 
            var tituloinicio = 'Validar Titulos';
            break;
        case '1349':
            var msg = 'Existen Otras Acreencias?'; 
            var tituloinicio = 'Validar Acreencias';
            break;
        case '837':
            var msg = 'Adjudicación Aceptada?';
            var tituloinicio = 'Registrar Adjudicación';
            break;
        case '604':
            var msg = 'Accede a la petición del SENA?'; 
            var tituloinicio = 'Cargar la decisión del<br> juez respecto al memorial';
            $('#cargar').show();            
        break;
        case '608':
            var msg = 'Cargar Notificacion del Abogado'; 
            var tituloinicio = 'Cargar Notificacion del Abogado';
            $('#cargar').show();            
            $('#check_decision').hide();  
        break;
        case '611':
            var msg = 'Cargar Memorial'; 
            var tituloinicio = 'Cargar Memorial';
            $('#cargar').show();            
            $('#check_decision').hide();  
        break;
        case '614':
            var msg = 'Resultado de Audiencia Aprobado?'; 
            var tituloinicio = 'Registrar resultado audiencia reorgnización';
        break;
        case '602':
            var msg = '<h3>Pago Recibido</h3>'; 
            var tituloinicio = 'Registrar Pago';
            $('#check_decision').hide();  
            $('#botones').hide();  
        break;
        case '838':
            var msg = '<h3>Pago Recibido</h3>'; 
            var tituloinicio = 'Registrar Pago';
            $('#check_decision').hide();  
            $('#botones').hide();  
        break;
        case '603':
            var msg = '<h3>Pago No Recibido</h3>'; 
            var tituloinicio = 'Registrar Pago';
            $('#check_decision').hide();  
            $('#botones').hide();  
        break;
    }
    $('#titulouno').append('<td><h2>'+tituloinicio+'</h2></td>');
    $('#mensaje_tipo').append('<td align="center"><b>'+msg+'</b></td>');
});

    $('#guardar').click(function() {       
        var adicionales  = $('input[name="adicionales"]:checked').val();   
        var gestion = '<?php echo $gestion ?>';   
        var url = '<?php echo base_url('index.php/insolvencia/guardar_verificacion')?>';
        var filecolilla = $('#filecolilla').val();
        var valida  = true;          
        if (adicionales == undefined){
            $('#error').show();            
            $('#error').html("<font color='red'><b>Seleccione alguna opción por favor</b></font>");
            $('#adicionales').focus();
            valida = false;
        }if ((gestion == 604 || gestion == 608 || gestion == 611) && filecolilla == ''){
            $('#error').show();            
            $('#error').html("<font color='red'><b>Por Favor Adjuntar Documento</b></font>");
            $('#filecolilla').focus();
            valida = false;
        }if (gestion == 1342 && adicionales == 1 && filecolilla == ''){
            $('#error').show();            
            $('#error').html("<font color='red'><b>Por Favor Adjuntar Documento</b></font>");
            $('#filecolilla').focus();
            valida = false;
        }
        
            if (valida == true) { 
                //alert ('ok');
                if (adicionales == 1) {
                    var verificacion = 'S';
                } else {
                var verificacion = 'N';
                }
                $("#verificacion").val(verificacion);
                $(".ajax_load").show("slow");
                $('#guardar').prop("disabled",true);
                $("#devolucionFrm").attr("action", url);
                $('#devolucionFrm').removeAttr('target');
                $('#devolucionFrm').submit();           
            }
    });
</script>    